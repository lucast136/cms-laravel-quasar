import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { COMPONENT_TYPES } from '@/constants/componentTypes'
import { useAdminPagesStore } from './admin-pages'
import { usePublicCmsStore } from './public-cms'
import { usePagesApi } from '@/composables/usePagesApi'

/**
 * Store del Constructor Visual de Páginas (Page Builder)
 * CMS IESTP DEPERU MASTER
 *
 * Administra el árbol reactivo de `content_sections` de la página seleccionada,
 * la selección activa, operaciones de reordenamiento drag & drop, historial (undo/redo),
 * y la persistencia en formato JSON compatible con el modelo ERD mediante `usePagesApi`.
 *
 * =========================================================================
 * @backend-contract CONTRATO LARAVEL 13 (Sincronización de Secciones)
 * =========================================================================
 *
 * Endpoint de Guardado en Masa de Secciones:
 * PUT /api/v1/admin/contents/{content_id}/sections
 * Headers: { "Authorization": "Bearer {token}", "Content-Type": "application/json" }
 *
 * Backend Service:
 * ContentService::syncPageSections(int $contentId, array $sectionsPayload): Collection
 * =========================================================================
 */
export const useAdminBuilderStore = defineStore('admin-builder', () => {
  const pagesStore = useAdminPagesStore()
  const { syncPageSectionsRequest, getComponentTypesRequest } = usePagesApi()

  const componentTypes = ref([...COMPONENT_TYPES])
  const currentPage = ref(null)
  const sections = ref([])
  const selectedSectionId = ref(null)
  const previewDevice = ref('desktop') // 'desktop' | 'tablet' | 'mobile'
  const isDirty = ref(false)
  const isSaving = ref(false)

  // Historial para Deshacer / Rehacer
  const history = ref([])
  const historyIndex = ref(-1)

  const selectedSection = computed(() => {
    return sections.value.find((s) => s.id === selectedSectionId.value) || null
  })

  const selectedComponentType = computed(() => {
    if (!selectedSection.value) return null
    return (
      componentTypes.value.find((c) => c.code === selectedSection.value.component_code) ||
      COMPONENT_TYPES.find((c) => c.code === selectedSection.value.component_code) ||
      null
    )
  })

  const sortedSections = computed(() => {
    return [...sections.value].sort((a, b) => a.sort_order - b.sort_order)
  })

  async function fetchComponentTypes() {
    try {
      const list = await getComponentTypesRequest()
      if (Array.isArray(list) && list.length > 0) {
        componentTypes.value = list
      }
    } catch {
      // Mantiene COMPONENT_TYPES locales como respaldo
    }
    return componentTypes.value
  }

  function saveSnapshot() {
    if (historyIndex.value < history.value.length - 1) {
      history.value = history.value.slice(0, historyIndex.value + 1)
    }
    history.value.push(JSON.stringify(sections.value))
    if (history.value.length > 20) history.value.shift()
    historyIndex.value = history.value.length - 1
    isDirty.value = true
  }

  function undo() {
    if (historyIndex.value > 0) {
      historyIndex.value--
      sections.value = JSON.parse(history.value[historyIndex.value])
      isDirty.value = true
    }
  }

  function redo() {
    if (historyIndex.value < history.value.length - 1) {
      historyIndex.value++
      sections.value = JSON.parse(history.value[historyIndex.value])
      isDirty.value = true
    }
  }

  const canUndo = computed(() => historyIndex.value > 0)
  const canRedo = computed(() => historyIndex.value < history.value.length - 1)

  function hydrateFromPageObject(page) {
    currentPage.value = JSON.parse(JSON.stringify(page))

    if (page.sections && page.sections.length > 0) {
      sections.value = page.sections.map((sec, idx) => {
        const code = sec.component_code || sec.component_type?.code || 'rich_text'
        const compType =
          componentTypes.value.find((c) => c.code === code) ||
          COMPONENT_TYPES.find((c) => c.code === code)
        const defaultSettings = JSON.parse(JSON.stringify(compType?.default_settings || {}))
        const defaultData = JSON.parse(JSON.stringify(compType?.default_data || {}))
        const mergedSettings = {
          ...defaultSettings,
          ...(sec.settings ? JSON.parse(JSON.stringify(sec.settings)) : {}),
        }
        const mergedData = {
          ...defaultData,
          ...(sec.data ? JSON.parse(JSON.stringify(sec.data)) : {}),
        }
        // Asegurar que las colecciones de objetos (arrays) tengan sus ítems por defecto si vienen vacías o indefinidas
        Object.keys(defaultData).forEach((key) => {
          if (Array.isArray(defaultData[key]) && !Array.isArray(mergedData[key])) {
            mergedData[key] = defaultData[key]
          }
        })

        return {
          id: sec.id || Date.now() + idx,
          component_type_id: sec.component_type_id || compType?.id || 1,
          component_name: sec.component_name || compType?.name || 'Sección',
          component_code: code,
          sort_order: sec.sort_order ?? idx + 1,
          is_active: sec.is_active ?? true,
          settings: mergedSettings,
          data: mergedData,
        }
      })
    } else {
      sections.value = []
    }

    selectedSectionId.value = sections.value[0]?.id || null
    history.value = [JSON.stringify(sections.value)]
    historyIndex.value = 0
    isDirty.value = false
  }

  /**
   * Carga una página institucional en el Page Builder (local + sincronización desde API)
   */
  function loadPage(pageId) {
    const localPage = pagesStore.getPageById(pageId)
    if (localPage) {
      hydrateFromPageObject(localPage)
    }

    // Hidratación asíncrona desde MySQL mediante usePagesApi
    fetchComponentTypes()
    pagesStore.fetchPageById(pageId).then((remotePage) => {
      if (remotePage && !isDirty.value) {
        hydrateFromPageObject(remotePage)
      }
    })

    return Boolean(localPage) || true
  }

  /**
   * Agrega un nuevo componente desde el catálogo
   */
  function addSection(componentCode, insertIndex = null) {
    const compType =
      componentTypes.value.find((c) => c.code === componentCode) ||
      COMPONENT_TYPES.find((c) => c.code === componentCode)
    if (!compType) return null

    saveSnapshot()

    const newId = Date.now()
    const newSection = {
      id: newId,
      component_type_id: compType.id,
      component_name: compType.code
        .split('_')
        .map((w) => w.charAt(0).toUpperCase() + w.slice(1))
        .join(''),
      component_code: compType.code,
      sort_order: sections.value.length + 1,
      is_active: true,
      settings: JSON.parse(JSON.stringify(compType.default_settings || {})),
      data: JSON.parse(JSON.stringify(compType.default_data || {})),
    }

    if (insertIndex !== null && insertIndex >= 0 && insertIndex <= sections.value.length) {
      sections.value.splice(insertIndex, 0, newSection)
    } else {
      sections.value.push(newSection)
    }

    reindexSortOrders()
    selectedSectionId.value = newId
    return newSection
  }

  /**
   * Elimina una sección
   */
  function removeSection(sectionId) {
    saveSnapshot()
    const index = sections.value.findIndex((s) => s.id === sectionId)
    if (index !== -1) {
      sections.value.splice(index, 1)
      reindexSortOrders()
      if (selectedSectionId.value === sectionId) {
        selectedSectionId.value = sections.value[0]?.id || null
      }
    }
  }

  /**
   * Duplica una sección existente
   */
  function duplicateSection(sectionId) {
    const original = sections.value.find((s) => s.id === sectionId)
    if (!original) return null

    saveSnapshot()
    const cloned = JSON.parse(JSON.stringify(original))
    cloned.id = Date.now()
    cloned.sort_order = original.sort_order + 1

    const originalIdx = sections.value.findIndex((s) => s.id === sectionId)
    sections.value.splice(originalIdx + 1, 0, cloned)

    reindexSortOrders()
    selectedSectionId.value = cloned.id
    return cloned
  }

  /**
   * Mueve una sección hacia arriba o hacia abajo
   */
  function moveSection(index, direction) {
    if (direction === 'up' && index === 0) return
    if (direction === 'down' && index === sections.value.length - 1) return

    saveSnapshot()
    const targetIndex = direction === 'up' ? index - 1 : index + 1
    const [moved] = sections.value.splice(index, 1)
    sections.value.splice(targetIndex, 0, moved)
    reindexSortOrders()
  }

  function reindexSortOrders() {
    sections.value.forEach((sec, idx) => {
      sec.sort_order = idx + 1
    })
  }

  function updateSectionData(sectionId, partialData) {
    const sec = sections.value.find((s) => s.id === sectionId)
    if (!sec) return
    Object.assign(sec.data, partialData)
    isDirty.value = true
  }

  function updateSectionSettings(sectionId, partialSettings) {
    const sec = sections.value.find((s) => s.id === sectionId)
    if (!sec) return
    Object.assign(sec.settings, partialSettings)
    isDirty.value = true
  }

  function toggleSectionActive(sectionId) {
    const sec = sections.value.find((s) => s.id === sectionId)
    if (!sec) return
    saveSnapshot()
    sec.is_active = !sec.is_active
  }

  /**
   * Guarda los cambios de secciones en el backend (PUT /api/v1/admin/contents/{id}/sections)
   * mediante el composable usePagesApi
   */
  async function savePageSections() {
    if (!currentPage.value) return false
    isSaving.value = true

    try {
      const payload = sections.value.map((sec, idx) => ({
        id: sec.id,
        component_type_id: sec.component_type_id,
        component_name: sec.component_name,
        sort_order: idx + 1,
        is_active: sec.is_active,
        settings: sec.settings || {},
        data: sec.data || {},
      }))

      try {
        const synced = await syncPageSectionsRequest(currentPage.value.id, payload)
        if (Array.isArray(synced) && synced.length > 0) {
          sections.value = synced.map((sec, idx) => ({
            id: sec.id,
            component_type_id: sec.component_type_id || sec.component_type?.id || 1,
            component_name: sec.component_name,
            component_code:
              sec.component_type?.code || sections.value[idx]?.component_code || 'rich_text',
            sort_order: sec.sort_order ?? idx + 1,
            is_active: sec.is_active ?? true,
            settings: sec.settings || {},
            data: sec.data || {},
          }))
        }
      } catch {
        // Mantiene actualización local si el backend está offline
      }

      const localPage = pagesStore.getPageById(currentPage.value.id)
      if (localPage) {
        localPage.sections = JSON.parse(JSON.stringify(sections.value))
        localPage.updated_at = new Date().toISOString().slice(0, 16).replace('T', ' ')
      }

      // Invalidar la caché pública para que "Ver en Vivo" cargue los bloques recién guardados
      const publicCmsStore = usePublicCmsStore()
      publicCmsStore.invalidatePageCache()

      isDirty.value = false
      return true
    } finally {
      isSaving.value = false
    }
  }

  return {
    componentTypes,
    currentPage,
    sections,
    sortedSections,
    selectedSectionId,
    selectedSection,
    selectedComponentType,
    previewDevice,
    isDirty,
    isSaving,
    canUndo,
    canRedo,
    fetchComponentTypes,
    loadPage,
    addSection,
    removeSection,
    duplicateSection,
    moveSection,
    updateSectionData,
    updateSectionSettings,
    toggleSectionActive,
    savePageSections,
    undo,
    redo,
  }
})

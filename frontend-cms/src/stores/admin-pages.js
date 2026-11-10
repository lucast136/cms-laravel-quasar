import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { usePagesApi } from '@/composables/usePagesApi'

/**
 * Store de Gestión de Páginas (contents WHERE type = 'page')
 * CMS IESTP DEPERU MASTER
 */
export const useAdminPagesStore = defineStore('admin-pages', () => {
  const {
    loading,
    getPagesRequest,
    getPageByIdRequest,
    createPageRequest,
    updatePageRequest,
    deletePageRequest,
    syncPageSectionsRequest,
  } = usePagesApi()

  // Lista inicial sincronizada con MySQL vía fetchPages
  const pages = ref([
    {
      id: 1,
      title: 'Página Principal (Inicio)',
      slug: 'inicio',
      template: 'landing',
      status: 'published',
      author: 'Super Administrador',
      updated_at: '2026-10-31 03:30',
      featured_image: '/img/banner/slide1.jpg',
      meta_description:
        'Sitio web oficial del IESTP DEPERU MASTER. Carreras técnicas profesionales de alta demanda.',
      sections: [],
    },
    {
      id: 2,
      title: 'Sobre Nosotros - Identidad Institucional',
      slug: 'nosotros',
      template: 'full-width',
      status: 'published',
      author: 'Super Administrador',
      updated_at: '2026-10-31 03:30',
      featured_image: '/img/content/about-01.jpg',
      meta_description:
        'Misión, visión, plana directiva, historia y valores institucionales del IESTP DEPERU MASTER.',
      sections: [],
    },
    {
      id: 3,
      title: 'Carreras Profesionales & Oferta Académica',
      slug: 'carreras',
      template: 'with-sidebar',
      status: 'published',
      author: 'Super Administrador',
      updated_at: '2026-10-31 03:30',
      featured_image: '/img/courses/course-01.jpg',
      meta_description:
        'Catálogo oficial de carreras técnicas de 3 años con titulación a nombre de la nación.',
      sections: [],
    },
    {
      id: 4,
      title: 'Plana Docente & Coordinadores Académicos',
      slug: 'docentes',
      template: 'full-width',
      status: 'published',
      author: 'Super Administrador',
      updated_at: '2026-10-31 03:30',
      featured_image: '/img/team/team-01.jpg',
      meta_description:
        'Conoce a nuestros docentes e instructores especializados con amplia trayectoria laboral.',
      sections: [],
    },
    {
      id: 5,
      title: 'Agenda de Eventos, Talleres y Webinars',
      slug: 'eventos',
      template: 'full-width',
      status: 'published',
      author: 'Super Administrador',
      updated_at: '2026-10-31 03:30',
      featured_image: '/img/content/event-01.jpg',
      meta_description:
        'Cronograma de seminarios web, talleres prácticos gratuitos y charlas vocacionales.',
      sections: [],
    },
    {
      id: 6,
      title: 'Noticias, Artículos & Comunicados Oficiales',
      slug: 'noticias',
      template: 'with-sidebar',
      status: 'published',
      author: 'Super Administrador',
      updated_at: '2026-10-31 03:30',
      featured_image: '/img/blog/blog-01.jpg',
      meta_description:
        'Entérate de las últimas novedades académicas, convenios y comunicados oficiales.',
      sections: [],
    },
    {
      id: 7,
      title: 'Admisión 2026 & Cronograma Oficial',
      slug: 'admision',
      template: 'full-width',
      status: 'published',
      author: 'Super Administrador',
      updated_at: '2026-10-31 03:30',
      featured_image: '/img/banner/slide2.jpg',
      meta_description: 'Fechas, aranceles, requisitos y cronograma del proceso de admisión.',
      sections: [],
    },
    {
      id: 8,
      title: 'Contacto, Sede Institucional & Mesa de Partes',
      slug: 'contacto',
      template: 'full-width',
      status: 'published',
      author: 'Super Administrador',
      updated_at: '2026-10-31 03:30',
      featured_image: '/img/content/contact-img-01.jpg',
      meta_description:
        'Ubicación de nuestro campus en Lircay, teléfonos de admisión y formulario de contacto.',
      sections: [],
    },
  ])

  const searchQuery = ref('')
  const statusFilter = ref('all')

  function normalizePage(raw) {
    return {
      id: raw.id,
      title: raw.title,
      slug: raw.slug,
      template: raw.template || 'full-width',
      status: raw.status || 'draft',
      author:
        typeof raw.author === 'object'
          ? raw.author?.name || 'Super Administrador'
          : raw.author || 'Super Administrador',
      updated_at: raw.updated_at
        ? String(raw.updated_at).slice(0, 16).replace('T', ' ')
        : new Date().toISOString().slice(0, 16).replace('T', ' '),
      featured_image: raw.featured_image || '',
      meta_description: raw.meta_description || raw.excerpt || '',
      sections: (raw.sections || []).map((sec) => ({
        id: sec.id,
        component_type_id: sec.component_type_id || sec.component_type?.id || 1,
        component_name: sec.component_name || sec.component_type?.name || 'Sección',
        component_code: sec.component_code || sec.component_type?.code || 'rich_text',
        sort_order: sec.sort_order ?? 1,
        is_active: sec.is_active ?? true,
        settings: sec.settings || sec.component_type?.default_settings || {},
        data: sec.data || sec.component_type?.default_data || {},
      })),
    }
  }

  /**
   * Obtiene el listado real de páginas desde Laravel Orion mediante usePagesApi
   */
  async function fetchPages() {
    try {
      const list = await getPagesRequest()
      if (Array.isArray(list) && list.length > 0) {
        pages.value = list.map(normalizePage)
      }
      return pages.value
    } catch {
      return pages.value
    }
  }

  /**
   * Obtiene una página individual con sus secciones desde el backend mediante usePagesApi
   */
  async function fetchPageById(id) {
    try {
      const raw = await getPageByIdRequest(id)
      if (raw) {
        const normalized = normalizePage(raw)
        const idx = pages.value.findIndex((p) => p.id === normalized.id)
        if (idx !== -1) {
          pages.value[idx] = normalized
        } else {
          pages.value.push(normalized)
        }
        return normalized
      }
    } catch {
      // Fallback
    }
    return getPageById(id)
  }

  const filteredPages = computed(() => {
    const query = (searchQuery.value || '').toLowerCase()
    return pages.value.filter((p) => {
      const matchSearch =
        p.title.toLowerCase().includes(query) || p.slug.toLowerCase().includes(query)
      const matchStatus = statusFilter.value === 'all' || p.status === statusFilter.value
      return matchSearch && matchStatus
    })
  })

  const totalPages = computed(() => pages.value.length)
  const publishedPagesCount = computed(
    () => pages.value.filter((p) => p.status === 'published').length,
  )
  const draftPagesCount = computed(() => pages.value.filter((p) => p.status === 'draft').length)

  function getPageById(id) {
    const pageId = Number(id)
    return pages.value.find((p) => p.id === pageId) || null
  }

  function getPageBySlug(slug) {
    return pages.value.find((p) => p.slug === slug) || null
  }

  async function createPage(payload) {
    const cleanSlug =
      payload.slug ||
      payload.title
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/(^-|-$)/g, '')

    try {
      const raw = await createPageRequest({
        title: payload.title,
        slug: cleanSlug,
        template: payload.template || 'full-width',
        status: payload.status || 'draft',
        excerpt: payload.meta_description || '',
        featured_image: payload.featured_image || '',
      })
      if (raw) {
        const created = normalizePage(raw)
        pages.value.unshift(created)
        return created
      }
    } catch {
      // Fallback
    }

    const newId = Math.max(...pages.value.map((p) => p.id), 0) + 1
    const newPage = {
      id: newId,
      title: payload.title,
      slug: cleanSlug,
      template: payload.template || 'full-width',
      status: payload.status || 'draft',
      author: 'Super Administrador',
      updated_at: new Date().toISOString().slice(0, 16).replace('T', ' '),
      featured_image: payload.featured_image || '',
      meta_description: payload.meta_description || '',
      sections: [],
    }
    pages.value.unshift(newPage)
    return newPage
  }

  async function updatePage(id, payload) {
    const page = getPageById(id)
    if (page) {
      Object.assign(page, payload, {
        updated_at: new Date().toISOString().slice(0, 16).replace('T', ' '),
      })
    }

    try {
      const apiPayload = {}
      if (payload.title !== undefined) apiPayload.title = payload.title
      if (payload.slug !== undefined) apiPayload.slug = payload.slug
      if (payload.template !== undefined) apiPayload.template = payload.template
      if (payload.status !== undefined) apiPayload.status = payload.status
      if (payload.meta_description !== undefined) apiPayload.excerpt = payload.meta_description
      if (payload.featured_image !== undefined) apiPayload.featured_image = payload.featured_image

      if (Object.keys(apiPayload).length > 0) {
        await updatePageRequest(id, apiPayload)
      }
      return true
    } catch {
      return Boolean(page)
    }
  }

  async function deletePage(id) {
    const index = pages.value.findIndex((p) => p.id === Number(id))
    if (index !== -1) {
      pages.value.splice(index, 1)
    }
    try {
      await deletePageRequest(id)
      return true
    } catch {
      return index !== -1
    }
  }

  async function duplicatePage(id) {
    const original = getPageById(id)
    if (!original) return null

    const newTitle = `${original.title} (Copia)`
    const newSlug = `${original.slug}-copia-${Date.now().toString().slice(-4)}`

    try {
      const createdRaw = await createPageRequest({
        title: newTitle,
        slug: newSlug,
        template: original.template,
        status: 'draft',
        excerpt: original.meta_description || '',
      })
      if (createdRaw) {
        const newPageId = createdRaw.id
        if (original.sections?.length > 0) {
          await syncPageSectionsRequest(
            newPageId,
            original.sections.map((sec, idx) => ({
              component_type_id: sec.component_type_id || 1,
              component_name: sec.component_name,
              sort_order: idx + 1,
              is_active: sec.is_active ?? true,
              settings: sec.settings || {},
              data: sec.data || {},
            })),
          )
        }
        return await fetchPageById(newPageId)
      }
    } catch {
      // Fallback
    }

    const cloned = JSON.parse(JSON.stringify(original))
    cloned.id = Math.max(...pages.value.map((p) => p.id), 0) + 1
    cloned.title = newTitle
    cloned.slug = newSlug
    cloned.status = 'draft'
    cloned.updated_at = new Date().toISOString().slice(0, 16).replace('T', ' ')
    pages.value.unshift(cloned)
    return cloned
  }

  return {
    pages,
    loading,
    searchQuery,
    statusFilter,
    filteredPages,
    totalPages,
    publishedPagesCount,
    draftPagesCount,
    fetchPages,
    fetchPageById,
    getPageById,
    getPageBySlug,
    createPage,
    updatePage,
    deletePage,
    duplicatePage,
  }
})

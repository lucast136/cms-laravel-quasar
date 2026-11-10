<template>
  <AdminLayout>
    <q-page class="q-pa-lg">
      <!-- Encabezado -->
      <div class="row items-center justify-between q-mb-lg">
        <div>
          <q-breadcrumbs class="text-caption text-grey-7">
            <q-breadcrumbs-el label="Dashboard" icon="dashboard" to="/admin" />
            <q-breadcrumbs-el label="Menús & Navegación" icon="menu_open" />
          </q-breadcrumbs>
          <h1 class="text-h4 text-weight-bold text-dark q-my-xs">
            Menús & Estructura de Navegación
          </h1>
          <div class="text-subtitle2 text-grey-7">
            Configura las colecciones de enlaces, niveles jerárquicos (submenús desplegables) de la cabecera y pie de página.
          </div>
        </div>

        <div class="row items-center q-gutter-sm">
          <q-btn
            outline
            color="primary"
            icon="add_circle"
            label="Nuevo Menú (Ubicación)"
            no-caps
            @click="openMenuModal(null)"
          />
          <q-btn
            color="primary"
            unelevated
            icon="save"
            label="Guardar Estructura"
            no-caps
            :loading="loading"
            @click="saveCurrentMenuStructure"
          />
        </div>
      </div>

      <!-- Barra de Selección y Gestión de Ubicaciones de Menú -->
      <q-card flat bordered class="q-mb-md rounded-borders bg-white shadow-1">
        <q-card-section class="q-py-md">
          <div class="row items-center justify-between q-col-gutter-md">
            <!-- Selector de Menú -->
            <div class="col-12 col-md-5">
              <q-select
                v-model="selectedMenuId"
                :options="menuSelectOptions"
                label="Menú a Administrar"
                dense
                outlined
                emit-value
                map-options
                @update:model-value="onSelectMenuChange"
              >
                <template #prepend>
                  <q-icon name="menu" color="primary" />
                </template>
                <template #option="scope">
                  <q-item v-bind="scope.itemProps">
                    <q-item-section>
                      <q-item-label>{{ scope.opt.label }}</q-item-label>
                      <q-item-label caption class="font-mono text-grey-6">
                        Ubicación: {{ scope.opt.location }}
                      </q-item-label>
                    </q-item-section>
                    <q-item-section side v-if="scope.opt.is_system">
                      <q-badge color="grey-3" text-color="grey-8" label="Sistema" />
                    </q-item-section>
                  </q-item>
                </template>
              </q-select>
            </div>

            <!-- Acciones del Menú Activo -->
            <div class="col-12 col-md-7 text-right">
              <div class="row items-center justify-end q-gutter-xs">
                <q-btn
                  flat
                  dense
                  color="grey-8"
                  icon="edit"
                  label="Editar Menú"
                  no-caps
                  class="q-px-sm"
                  @click="openMenuModal(currentMenuObject)"
                />
                <q-btn
                  v-if="!isCurrentMenuSystem"
                  flat
                  dense
                  color="negative"
                  icon="delete"
                  label="Eliminar Menú"
                  no-caps
                  class="q-px-sm"
                  @click="confirmDeleteMenu"
                />
                <q-separator vertical inset class="q-mx-xs" />
                <q-btn
                  unelevated
                  color="secondary"
                  icon="add_link"
                  label="Agregar Enlace Principal"
                  no-caps
                  @click="openItemDialog(null)"
                />
              </div>
            </div>
          </div>
        </q-card-section>
      </q-card>

      <!-- Panel Central: Lista Jerárquica de Ítems -->
      <div class="row q-col-gutter-lg">
        <div class="col-12 col-lg-8">
          <q-card flat bordered class="rounded-borders bg-white shadow-1">
            <q-card-section class="border-bottom q-py-md">
              <div class="row items-center justify-between">
                <div>
                  <div class="text-subtitle1 text-weight-bold text-dark">
                    Estructura: {{ currentMenuObject?.name || 'Menú' }}
                  </div>
                  <div class="text-caption text-grey-6 font-mono">
                    Ubicación API: {{ currentMenuObject?.location }} ({{ flatOrderedItems.length }} elementos en total)
                  </div>
                </div>
                <q-badge
                  :color="currentMenuObject?.is_active ? 'positive' : 'grey-6'"
                  :label="currentMenuObject?.is_active ? 'Activo en Frontend' : 'Inactivo'"
                />
              </div>
            </q-card-section>

            <q-card-section class="q-pa-md">
              <!-- Estado Vacío -->
              <div v-if="flatOrderedItems.length === 0" class="text-center q-pa-xl text-grey-6">
                <q-icon name="format_list_bulleted" size="48px" class="q-mb-sm text-grey-4" />
                <div class="text-subtitle2 font-weight-600">Este menú aún no tiene enlaces.</div>
                <div class="text-caption q-mb-md">Comienza agregando el primer enlace con el botón superior.</div>
                <q-btn
                  unelevated
                  color="secondary"
                  icon="add_link"
                  label="Agregar Enlace Principal"
                  no-caps
                  size="sm"
                  @click="openItemDialog(null)"
                />
              </div>

              <!-- Lista con niveles jerárquicos (Árbol visual) -->
              <q-list v-else separator class="menu-tree-list">
                <q-item
                  v-for="(item, idx) in flatOrderedItems"
                  :key="item.id"
                  class="q-py-sm q-my-xs rounded-borders transition-bg"
                  :class="[
                    item.level === 0 ? 'bg-grey-1 root-item' : 'bg-white child-item border-child',
                  ]"
                  :style="{ marginLeft: `${item.level * 28}px` }"
                >
                  <!-- Conector Visual de Submenú -->
                  <q-item-section avatar class="item-avatar-col">
                    <div class="row items-center no-wrap">
                      <span v-if="item.level > 0" class="tree-connector q-mr-xs text-grey-5">
                        <q-icon name="subdirectory_arrow_right" size="18px" color="primary" />
                      </span>
                      <q-avatar
                        size="26px"
                        :color="item.level === 0 ? 'primary' : 'secondary'"
                        text-color="white"
                        class="text-weight-bold text-caption shadow-1"
                      >
                        {{ idx + 1 }}
                      </q-avatar>
                    </div>
                  </q-item-section>

                  <!-- Información del Ítem -->
                  <q-item-section>
                    <div class="row items-center q-gutter-x-sm">
                      <q-icon v-if="item.icon_class" :name="item.icon_class" size="xs" color="grey-7" />
                      <span class="text-weight-bold text-dark text-body2">{{ item.title }}</span>

                      <q-badge color="grey-3" text-color="grey-9" class="font-mono text-caption">
                        {{ item.url }}
                      </q-badge>

                      <q-badge
                        v-if="item.level > 0"
                        color="blue-1"
                        text-color="primary"
                        label="Submenú"
                        class="q-px-xs text-weight-bold"
                      />

                      <q-badge
                        v-if="item.target === '_blank'"
                        color="teal-1"
                        text-color="teal-9"
                        label="_blank"
                        class="q-px-xs"
                      />
                    </div>
                  </q-item-section>

                  <!-- Acciones del Ítem -->
                  <q-item-section side>
                    <div class="row items-center q-gutter-x-xs">
                      <!-- Agregar Sub-ítem -->
                      <q-btn
                        flat
                        round
                        dense
                        size="sm"
                        icon="add_circle"
                        color="secondary"
                        title="Agregar Sub-ítem (Hijo)"
                        @click="openItemDialog(null, item.id)"
                      >
                        <q-tooltip>Agregar Sub-ítem debajo de este</q-tooltip>
                      </q-btn>

                      <!-- Editar Ítem -->
                      <q-btn
                        flat
                        round
                        dense
                        size="sm"
                        icon="edit"
                        color="primary"
                        title="Editar elemento"
                        @click="openItemDialog(item)"
                      >
                        <q-tooltip>Editar enlace</q-tooltip>
                      </q-btn>

                      <!-- Reordenar Arriba -->
                      <q-btn
                        flat
                        round
                        dense
                        size="sm"
                        icon="keyboard_arrow_up"
                        :disable="idx === 0"
                        @click="moveItemPosition(idx, -1)"
                      >
                        <q-tooltip>Subir posición</q-tooltip>
                      </q-btn>

                      <!-- Reordenar Abajo -->
                      <q-btn
                        flat
                        round
                        dense
                        size="sm"
                        icon="keyboard_arrow_down"
                        :disable="idx === flatOrderedItems.length - 1"
                        @click="moveItemPosition(idx, 1)"
                      >
                        <q-tooltip>Bajar posición</q-tooltip>
                      </q-btn>

                      <!-- Eliminar Ítem -->
                      <q-btn
                        flat
                        round
                        dense
                        size="sm"
                        icon="delete"
                        color="negative"
                        @click="removeItem(item.id)"
                      >
                        <q-tooltip>Eliminar enlace</q-tooltip>
                      </q-btn>
                    </div>
                  </q-item-section>
                </q-item>
              </q-list>
            </q-card-section>
          </q-card>
        </div>

        <!-- Panel Lateral Informativo -->
        <div class="col-12 col-lg-4">
          <q-card flat bordered class="rounded-borders bg-white shadow-1 q-mb-md">
            <q-card-section class="border-bottom q-py-sm">
              <div class="text-subtitle2 text-weight-bold text-dark">
                Jerarquía y Submenús Recursivos
              </div>
            </q-card-section>
            <q-card-section class="text-caption text-grey-8 leading-relaxed">
              <p>
                <strong>¿Cómo funcionan los submenús?</strong><br />
                Al hacer clic en el botón <q-icon name="add_circle" color="secondary" /> <em>(Agregar Sub-ítem)</em> de cualquier elemento, el nuevo enlace se vinculará a través del campo <code>parent_id</code>.
              </p>
              <p class="q-mb-none">
                En el Navbar público, los elementos que cuenten con sub-ítems se desplegarán automáticamente como un menú flotante (*dropdown*) en pantallas de escritorio y como acordeones en dispositivos móviles.
              </p>
            </q-card-section>
          </q-card>

          <q-card flat bordered class="rounded-borders bg-blue-1 border-primary">
            <q-card-section>
              <div class="text-subtitle2 text-weight-bold text-primary q-mb-xs">
                Ubicaciones del Sistema (Frontend)
              </div>
              <ul class="q-pl-md q-mb-none text-caption text-grey-8">
                <li><code>header_main</code>: Menú principal de la barra superior.</li>
                <li><code>footer_quick_links</code>: Columna de enlaces rápidos en el pie.</li>
                <li><code>footer_courses</code>: Columna de carreras en el pie de página.</li>
              </ul>
            </q-card-section>
          </q-card>
        </div>
      </div>

      <!-- Diálogo 1: Crear / Editar Menú (Ubicación) -->
      <q-dialog v-model="menuModal.open" persistent>
        <q-card style="min-width: 440px" class="rounded-borders">
          <q-card-section class="row items-center justify-between border-bottom q-pb-md">
            <div class="text-h6 font-weight-700 text-dark">
              {{ menuModal.isEdit ? 'Editar Menú' : 'Crear Nuevo Menú' }}
            </div>
            <q-btn flat round dense icon="close" v-close-popup />
          </q-card-section>

          <q-card-section class="q-pt-md">
            <q-form @submit="saveMenuModal" class="q-gutter-md">
              <q-input
                v-model="menuModal.form.name"
                label="Nombre Descriptivo del Menú *"
                dense
                outlined
                hint="Ej: Menú de Redes Sociales, Menú Topbar"
                :rules="[(val) => !!val || 'El nombre es obligatorio']"
              />

              <q-input
                v-model="menuModal.form.location"
                label="Identificador de Ubicación (location) *"
                dense
                outlined
                hint="Clave única en minúsculas. Ej: sidebar_links, top_bar"
                :disable="menuModal.isEdit && isCurrentMenuSystem"
                :rules="[
                  (val) => !!val || 'La ubicación es requerida',
                  (val) => /^[a-z0-9_]+$/.test(val) || 'Solo letras minúsculas, números y guiones bajos',
                ]"
              />

              <q-toggle
                v-model="menuModal.form.is_active"
                label="Menú activo en el portal público"
                dense
              />

              <div class="row justify-end q-gutter-sm q-mt-md">
                <q-btn flat label="Cancelar" color="grey-7" v-close-popup no-caps />
                <q-btn unelevated :label="menuModal.isEdit ? 'Actualizar' : 'Crear Menú'" color="primary" type="submit" no-caps />
              </div>
            </q-form>
          </q-card-section>
        </q-card>
      </q-dialog>

      <!-- Diálogo 2: Crear / Editar Ítem del Menú (Recursivo) -->
      <q-dialog v-model="itemModal.open" persistent>
        <q-card style="min-width: 480px" class="rounded-borders">
          <q-card-section class="row items-center justify-between border-bottom q-pb-md">
            <div class="text-h6 font-weight-700 text-dark">
              {{ itemModal.isEdit ? 'Editar Elemento del Menú' : 'Agregar Elemento al Menú' }}
            </div>
            <q-btn flat round dense icon="close" v-close-popup />
          </q-card-section>

          <q-card-section class="q-pt-md">
            <q-form @submit="saveItemModal" class="q-gutter-md">
              <q-input
                v-model="itemModal.form.title"
                label="Texto visible del enlace *"
                dense
                outlined
                :rules="[(val) => !!val || 'El título es requerido']"
              />

              <q-input
                v-model="itemModal.form.url"
                label="Destino URL (interna o externa) *"
                dense
                outlined
                hint="Ej: /carreras, /nosotros o https://..."
                :rules="[(val) => !!val || 'La URL es requerida']"
              />

              <!-- Selector de Elemento Padre (Recursividad) -->
              <q-select
                v-model="itemModal.form.parent_id"
                :options="parentSelectOptions"
                label="Elemento Padre (Para crear Submenús)"
                dense
                outlined
                emit-value
                map-options
                clearable
                hint="Déjalo vacío para que sea un enlace principal de primer nivel"
              >
                <template #prepend>
                  <q-icon name="account_tree" color="secondary" />
                </template>
              </q-select>

              <q-input
                v-model="itemModal.form.icon_class"
                label="Ícono opcional (Material o FontAwesome)"
                dense
                outlined
                hint="Ej: home, school, info, far fa-book"
              />

              <q-toggle
                v-model="itemModal.form.newTab"
                label="Abrir enlace en una pestaña nueva (_blank)"
                dense
              />

              <div class="row justify-end q-gutter-sm q-mt-md">
                <q-btn flat label="Cancelar" color="grey-7" v-close-popup no-caps />
                <q-btn unelevated :label="itemModal.isEdit ? 'Guardar Cambios' : 'Agregar Enlace'" color="primary" type="submit" no-caps />
              </div>
            </q-form>
          </q-card-section>
        </q-card>
      </q-dialog>
    </q-page>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, reactive, onMounted } from 'vue'
import { useQuasar } from 'quasar'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { useNotification } from '@/composables/useNotification'
import { useMenusApi } from '@/composables/useMenusApi'

const $q = useQuasar()
const notify = useNotification()
const {
  loading,
  getMenusRequest,
  createMenuRequest,
  updateMenuRequest,
  deleteMenuRequest,
  syncMenuItemsRequest,
} = useMenusApi()

const SYSTEM_LOCATIONS = ['header_main', 'footer_quick_links', 'footer_courses']

const menusList = ref([])
const selectedMenuId = ref(null)

// Colección plana reactiva de ítems del menú seleccionado: [{ id, parent_id, title, url, target, icon_class, sort_order }]
const currentItems = ref([])

onMounted(async () => {
  await loadMenus()
})

async function loadMenus() {
  try {
    const list = await getMenusRequest()
    if (Array.isArray(list) && list.length > 0) {
      menusList.value = list

      // Si no hay seleccionado o el seleccionado ya no existe, seleccionar el primero
      if (!selectedMenuId.value || !list.some((m) => m.id === selectedMenuId.value)) {
        selectedMenuId.value = list[0].id
      }
      populateCurrentItemsFromSelectedMenu()
    } else {
      // Fallback predeterminado inicial
      menusList.value = [
        { id: 1, name: 'Cabecera Principal', location: 'header_main', is_active: true, items: [] },
        { id: 2, name: 'Enlaces Rápidos del Pie', location: 'footer_quick_links', is_active: true, items: [] },
        { id: 3, name: 'Carreras en Pie de Página', location: 'footer_courses', is_active: true, items: [] },
      ]
      selectedMenuId.value = 1
      populateCurrentItemsFromSelectedMenu()
    }
  } catch (err) {
    notify.error('Error al cargar la lista de menús: ' + (err.message || 'Error de red'))
  }
}

const currentMenuObject = computed(() => {
  return menusList.value.find((m) => m.id === selectedMenuId.value) || null
})

const isCurrentMenuSystem = computed(() => {
  return SYSTEM_LOCATIONS.includes(currentMenuObject.value?.location)
})

const menuSelectOptions = computed(() => {
  return menusList.value.map((m) => ({
    label: m.name,
    value: m.id,
    location: m.location,
    is_system: SYSTEM_LOCATIONS.includes(m.location),
  }))
})

function onSelectMenuChange() {
  populateCurrentItemsFromSelectedMenu()
}

/**
 * Aplanar recursivamente los ítems provenientes del backend (items -> children)
 */
function flattenTree(items, parentId = null) {
  let result = []
  if (!Array.isArray(items)) return result

  // Filtrar duplicados si items viene como colección plana combinada con children
  // Si parentId es null, solo consideramos elementos que realmente sean raíz (parent_id nulo o falso)
  const filtered = parentId === null ? items.filter((it) => !it.parent_id) : items

  filtered.forEach((it, idx) => {
    result.push({
      id: it.id,
      parent_id: it.parent_id || parentId,
      title: it.title,
      url: it.url,
      target: it.target || '_self',
      icon_class: it.icon_class || null,
      sort_order: it.sort_order ?? idx + 1,
    })

    if (Array.isArray(it.children) && it.children.length > 0) {
      result = result.concat(flattenTree(it.children, it.id))
    }
  })
  return result
}

function populateCurrentItemsFromSelectedMenu() {
  const menu = currentMenuObject.value
  if (!menu) {
    currentItems.value = []
    return
  }

  // Preferir root_items o rootItems si viene de la relación jerárquica
  const itemsSource =
    (Array.isArray(menu.root_items) && menu.root_items.length > 0)
      ? menu.root_items
      : (Array.isArray(menu.rootItems) && menu.rootItems.length > 0)
        ? menu.rootItems
        : Array.isArray(menu.items)
          ? menu.items
          : []

  if (itemsSource.length > 0) {
    currentItems.value = flattenTree(itemsSource)
  } else {
    currentItems.value = []
  }
}

/**
 * Construir la lista ordenada visualmente con su nivel jerárquico (level: 0 = raíz, 1 = hijo, etc.)
 */
const flatOrderedItems = computed(() => {
  const all = [...currentItems.value]
  const result = []

  function traverse(parentId, level) {
    const directChildren = all
      .filter((i) => (parentId === null ? !i.parent_id : i.parent_id === parentId))
      .sort((a, b) => (a.sort_order ?? 0) - (b.sort_order ?? 0))

    directChildren.forEach((child) => {
      result.push({
        ...child,
        level,
      })
      traverse(child.id, level + 1)
    })
  }

  traverse(null, 0)
  return result
})

/**
 * Opciones para seleccionar un elemento padre en el modal de ítem
 */
const parentSelectOptions = computed(() => {
  const currentEditingId = itemModal.form.id
  // Excluirse a sí mismo y a sus descendientes para evitar ciclos
  return flatOrderedItems.value
    .filter((i) => i.id !== currentEditingId && i.level < 2) // Máximo 2 niveles de anidación recomendado
    .map((i) => ({
      label: `${'— '.repeat(i.level)}${i.title} (${i.url})`,
      value: i.id,
    }))
})

// -------------------------------------------------------------
// CRUD de Menú (Ubicación)
// -------------------------------------------------------------
const menuModal = reactive({
  open: false,
  isEdit: false,
  menuId: null,
  form: {
    name: '',
    location: '',
    is_active: true,
  },
})

function openMenuModal(menu = null) {
  if (menu) {
    menuModal.isEdit = true
    menuModal.menuId = menu.id
    menuModal.form.name = menu.name
    menuModal.form.location = menu.location
    menuModal.form.is_active = menu.is_active !== false
  } else {
    menuModal.isEdit = false
    menuModal.menuId = null
    menuModal.form.name = ''
    menuModal.form.location = ''
    menuModal.form.is_active = true
  }
  menuModal.open = true
}

async function saveMenuModal() {
  try {
    if (menuModal.isEdit) {
      const updated = await updateMenuRequest(menuModal.menuId, {
        name: menuModal.form.name,
        location: menuModal.form.location,
        is_active: menuModal.form.is_active,
      })
      notify.success('Menú actualizado correctamente.')
      if (updated) {
        const idx = menusList.value.findIndex((m) => m.id === menuModal.menuId)
        if (idx !== -1) {
          menusList.value[idx] = { ...menusList.value[idx], ...updated }
        }
      }
    } else {
      const created = await createMenuRequest({
        name: menuModal.form.name,
        location: menuModal.form.location,
        is_active: menuModal.form.is_active,
      })
      notify.success('Nuevo menú creado exitosamente.')
      if (created) {
        menusList.value.push({ ...created, items: [] })
        selectedMenuId.value = created.id
        currentItems.value = []
      } else {
        await loadMenus()
      }
    }
    menuModal.open = false
  } catch (err) {
    notify.error('No se pudo guardar el menú: ' + (err.message || 'Error del servidor'))
  }
}

function confirmDeleteMenu() {
  const menu = currentMenuObject.value
  if (!menu || isCurrentMenuSystem.value) return

  $q.dialog({
    title: 'Confirmar Eliminación',
    message: `¿Estás seguro de eliminar el menú "${menu.name}" (${menu.location}) y todos sus enlaces?`,
    cancel: true,
    persistent: true,
    ok: { color: 'negative', label: 'Eliminar' },
  }).onOk(async () => {
    try {
      await deleteMenuRequest(menu.id)
      notify.success('Menú eliminado correctamente.')
      menusList.value = menusList.value.filter((m) => m.id !== menu.id)
      selectedMenuId.value = menusList.value[0]?.id || null
      populateCurrentItemsFromSelectedMenu()
    } catch (err) {
      notify.error('No se pudo eliminar el menú: ' + (err.message || 'Error'))
    }
  })
}

// -------------------------------------------------------------
// CRUD de Ítems (Recursivos / Submenús)
// -------------------------------------------------------------
const itemModal = reactive({
  open: false,
  isEdit: false,
  form: {
    id: null,
    parent_id: null,
    title: '',
    url: '',
    icon_class: '',
    newTab: false,
  },
})

function openItemDialog(item = null, defaultParentId = null) {
  if (item) {
    itemModal.isEdit = true
    itemModal.form.id = item.id
    itemModal.form.parent_id = item.parent_id || null
    itemModal.form.title = item.title
    itemModal.form.url = item.url
    itemModal.form.icon_class = item.icon_class || ''
    itemModal.form.newTab = item.target === '_blank'
  } else {
    itemModal.isEdit = false
    itemModal.form.id = Date.now()
    itemModal.form.parent_id = defaultParentId || null
    itemModal.form.title = ''
    itemModal.form.url = ''
    itemModal.form.icon_class = ''
    itemModal.form.newTab = false
  }
  itemModal.open = true
}

function saveItemModal() {
  const payload = {
    id: itemModal.form.id,
    parent_id: itemModal.form.parent_id || null,
    title: itemModal.form.title,
    url: itemModal.form.url,
    icon_class: itemModal.form.icon_class || null,
    target: itemModal.form.newTab ? '_blank' : '_self',
  }

  if (itemModal.isEdit) {
    const idx = currentItems.value.findIndex((i) => i.id === payload.id)
    if (idx !== -1) {
      currentItems.value[idx] = {
        ...currentItems.value[idx],
        ...payload,
      }
      notify.success('Elemento actualizado.')
    }
  } else {
    // Asignar el siguiente sort_order
    payload.sort_order = currentItems.value.length + 1
    currentItems.value.push(payload)
    notify.success('Elemento agregado a la lista.')
  }

  itemModal.open = false
}

function removeItem(itemId) {
  // Comprobar si tiene hijos dependientes
  const hasChildren = currentItems.value.some((i) => i.parent_id === itemId)
  const confirmMsg = hasChildren
    ? 'Este elemento tiene submenús asociados. Si lo eliminas, los submenús también serán eliminados. ¿Deseas continuar?'
    : '¿Estás seguro de eliminar este elemento del menú?'

  $q.dialog({
    title: 'Eliminar Elemento',
    message: confirmMsg,
    cancel: true,
    persistent: true,
    ok: { color: 'negative', label: 'Eliminar' },
  }).onOk(() => {
    // Eliminar el ítem y en cascada a sus hijos
    const idsToRemove = new Set([itemId])
    let added = true
    while (added) {
      added = false
      currentItems.value.forEach((it) => {
        if (it.parent_id && idsToRemove.has(it.parent_id) && !idsToRemove.has(it.id)) {
          idsToRemove.add(it.id)
          added = true
        }
      })
    }

    currentItems.value = currentItems.value.filter((i) => !idsToRemove.has(i.id))
    notify.info('Elemento(s) eliminado(s) de la estructura local.')
  })
}

function moveItemPosition(index, offset) {
  const items = [...flatOrderedItems.value]
  const targetIdx = index + offset
  if (targetIdx < 0 || targetIdx >= items.length) return

  const itemA = items[index]
  const itemB = items[targetIdx]

  // Si comparten el mismo padre, simplemente intercambiamos sus sort_order
  if (itemA.parent_id === itemB.parent_id) {
    const realIdxA = currentItems.value.findIndex((i) => i.id === itemA.id)
    const realIdxB = currentItems.value.findIndex((i) => i.id === itemB.id)
    if (realIdxA !== -1 && realIdxB !== -1) {
      const tempOrder = currentItems.value[realIdxA].sort_order || realIdxA
      currentItems.value[realIdxA].sort_order = currentItems.value[realIdxB].sort_order || realIdxB
      currentItems.value[realIdxB].sort_order = tempOrder
    }
  } else {
    // Si están en diferentes ramas, reasignamos sort_order secuenciales según su orden visual
    const [moved] = items.splice(index, 1)
    items.splice(targetIdx, 0, moved)
    items.forEach((it, pos) => {
      const rIdx = currentItems.value.findIndex((c) => c.id === it.id)
      if (rIdx !== -1) {
        currentItems.value[rIdx].sort_order = pos + 1
      }
    })
  }
}

// -------------------------------------------------------------
// Guardado en Base de Datos (Sync API)
// -------------------------------------------------------------
async function saveCurrentMenuStructure() {
  if (!selectedMenuId.value) return

  const menu = currentMenuObject.value
  const itemsToSync = flatOrderedItems.value.map((it, idx) => ({
    id: it.id,
    parent_id: it.parent_id || null,
    title: it.title,
    url: it.url,
    target: it.target || '_self',
    icon_class: it.icon_class || null,
    sort_order: idx + 1,
  }))

  try {
    const res = await syncMenuItemsRequest(selectedMenuId.value, itemsToSync)
    notify.success(`Menú "${menu?.name}" guardado exitosamente en base de datos.`)
    // Recargar menús para traer IDs reales asignados por MySQL
    if (res && res.items) {
      menu.items = res.items
      populateCurrentItemsFromSelectedMenu()
    } else {
      await loadMenus()
    }
  } catch (err) {
    notify.error('No se pudo guardar la estructura del menú: ' + (err.message || 'Error del servidor'))
  }
}
</script>

<style scoped lang="scss">
@import '@/css/template/variables';

.rounded-borders {
  border-radius: 10px;
}

.border-bottom {
  border-bottom: 1px solid #e2e8f0;
}

.font-mono {
  font-family: monospace;
}

.transition-bg {
  transition: background-color 0.2s ease, box-shadow 0.2s ease;
}

.root-item {
  border: 1px solid #e2e8f0;
  border-left: 4px solid $theme-primary;
}

.child-item {
  border: 1px dashed #cbd5e1;
  border-left: 3px solid $theme-secondary;
}

.item-avatar-col {
  min-width: 44px;
}

.tree-connector {
  display: inline-flex;
  align-items: center;
}
</style>

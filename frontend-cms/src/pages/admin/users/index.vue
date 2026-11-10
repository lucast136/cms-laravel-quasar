<template>
  <AdminLayout>
    <q-page class="q-pa-lg">
      <!-- Encabezado -->
      <div class="row items-center justify-between q-mb-lg">
        <div>
          <q-breadcrumbs class="text-caption text-grey-7">
            <q-breadcrumbs-el label="Dashboard" icon="dashboard" to="/admin" />
            <q-breadcrumbs-el label="Usuarios & Roles RBAC" icon="manage_accounts" />
          </q-breadcrumbs>
          <h1 class="text-h4 text-weight-bold text-dark q-my-xs">
            Gestión de Usuarios & Roles (RBAC)
          </h1>
          <div class="text-subtitle2 text-grey-7">
            Administra las cuentas de acceso al CMS, asigna roles institucionales y revisa la matriz
            de permisos.
          </div>
        </div>

        <q-btn
          color="primary"
          unelevated
          icon="person_add"
          label="Nuevo Usuario"
          no-caps
          @click="openCreateDialog"
        />
      </div>

      <!-- Pestañas: Usuarios / Roles & Permisos -->
      <q-card flat bordered class="rounded-borders">
        <q-tabs
          v-model="activeTab"
          dense
          active-color="primary"
          indicator-color="primary"
          align="left"
          class="bg-grey-1"
        >
          <q-tab name="users" icon="people" label="Usuarios del Sistema (users)" no-caps />
          <q-tab
            name="roles"
            icon="admin_panel_settings"
            label="Roles & Matriz de Permisos (roles / permissions)"
            no-caps
          />
        </q-tabs>
        <q-separator />

        <q-tab-panels v-model="activeTab" animated>
          <!-- PESTAÑA 1: TABLA DE USUARIOS -->
          <q-tab-panel name="users" class="q-pa-none">
            <q-table
              flat
              :rows="users"
              :columns="columns"
              row-key="id"
              :loading="loading"
              :pagination="{ rowsPerPage: 10 }"
            >
              <!-- Usuario + Avatar -->
              <template #body-cell-name="props">
                <q-td :props="props">
                  <div class="row items-center no-wrap">
                    <q-avatar size="38px" class="q-mr-md">
                      <img
                        :src="props.row.avatar_url || 'https://cdn.quasar.dev/img/avatar.png'"
                        alt="Avatar"
                      />
                    </q-avatar>
                    <div>
                      <div class="text-weight-bold text-dark">{{ props.row.name }}</div>
                      <div class="text-caption text-grey-6 font-mono">{{ props.row.email }}</div>
                    </div>
                  </div>
                </q-td>
              </template>

              <!-- Rol Asignado -->
              <template #body-cell-role="props">
                <q-td :props="props">
                  <q-badge
                    :color="getRoleBadgeColor(props.row.role_slug)"
                    class="q-px-sm q-py-xs text-weight-bold"
                  >
                    {{ props.row.role_name }}
                  </q-badge>
                </q-td>
              </template>

              <!-- Estado -->
              <template #body-cell-status="props">
                <q-td :props="props" class="text-center">
                  <q-chip
                    size="sm"
                    dense
                    :color="props.row.status === 'active' ? 'positive' : 'grey-6'"
                    text-color="white"
                    :icon="props.row.status === 'active' ? 'check_circle' : 'block'"
                  >
                    {{ props.row.status === 'active' ? 'Activo' : 'Inactivo' }}
                  </q-chip>
                </q-td>
              </template>

              <!-- Acciones -->
              <template #body-cell-actions="props">
                <q-td :props="props" class="text-right">
                  <q-btn
                    flat
                    round
                    dense
                    size="sm"
                    icon="edit"
                    color="primary"
                    @click="openEditDialog(props.row)"
                  >
                    <q-tooltip>Editar usuario y rol</q-tooltip>
                  </q-btn>
                  <q-btn
                    v-if="props.row.id !== 1"
                    flat
                    round
                    dense
                    size="sm"
                    icon="delete"
                    color="negative"
                    @click="handleDelete(props.row)"
                  >
                    <q-tooltip>Eliminar usuario</q-tooltip>
                  </q-btn>
                </q-td>
              </template>
            </q-table>
          </q-tab-panel>

          <!-- PESTAÑA 2: ROLES Y PERMISOS -->
          <q-tab-panel name="roles" class="q-pa-md">
            <div class="row q-col-gutter-md">
              <div v-for="role in rolesList" :key="role.slug" class="col-12 col-md-4">
                <q-card flat bordered class="full-height rounded-borders">
                  <q-card-section class="bg-grey-1">
                    <div class="row items-center justify-between">
                      <div class="text-subtitle1 text-weight-bold text-dark">
                        {{ role.name }}
                      </div>
                      <q-badge :color="getRoleBadgeColor(role.slug)" :label="role.slug" />
                    </div>
                    <div class="text-caption text-grey-7 q-mt-xs">
                      {{ role.description }}
                    </div>
                  </q-card-section>
                  <q-separator />
                  <q-card-section>
                    <div class="text-caption text-weight-bold text-grey-8 q-mb-sm">
                      Permisos habilitados ({{ role.permissions?.length || 0 }}):
                    </div>
                    <div class="row q-gutter-xs">
                      <q-chip
                        v-for="perm in role.permissions"
                        :key="perm.slug"
                        size="sm"
                        dense
                        color="blue-1"
                        text-color="primary"
                        icon="verified"
                      >
                        {{ perm.name || perm.slug }}
                      </q-chip>
                    </div>
                  </q-card-section>
                </q-card>
              </div>
            </div>
          </q-tab-panel>
        </q-tab-panels>
      </q-card>

      <!-- Diálogo Crear / Editar Usuario -->
      <q-dialog v-model="userDialog.open" persistent>
        <q-card style="min-width: 460px; max-width: 560px" class="rounded-borders">
          <q-card-section class="row items-center justify-between q-pb-none">
            <div class="text-h6 text-weight-bold">
              {{ userDialog.isEditing ? 'Editar Usuario' : 'Registrar Nuevo Usuario' }}
            </div>
            <q-btn flat round dense icon="close" v-close-popup />
          </q-card-section>

          <q-card-section class="q-pt-md">
            <q-form class="q-gutter-md" @submit="saveUser">
              <q-input
                v-model="userForm.name"
                label="Nombre Completo *"
                outlined
                dense
                :rules="[(val) => !!val || 'El nombre es obligatorio']"
              />

              <q-input
                v-model="userForm.email"
                type="email"
                label="Correo Electrónico Institucional *"
                outlined
                dense
                :rules="[(val) => !!val || 'El correo es obligatorio']"
              />

              <q-input
                v-model="userForm.password"
                type="password"
                :label="userDialog.isEditing ? 'Nueva Contraseña (opcional)' : 'Contraseña *'"
                outlined
                dense
                :rules="userDialog.isEditing ? [] : [(val) => !!val || 'Ingresa una contraseña']"
              />

              <div class="row q-col-gutter-sm">
                <div class="col-12 col-sm-6">
                  <q-select
                    v-model="userForm.role_slug"
                    label="Rol RBAC"
                    outlined
                    dense
                    :options="[
                      { label: 'Super Administrador', value: 'super_admin' },
                      { label: 'Editor de Contenidos', value: 'editor' },
                      { label: 'Redactor Académico', value: 'redactor' },
                    ]"
                    emit-value
                    map-options
                  />
                </div>
                <div class="col-12 col-sm-6">
                  <q-select
                    v-model="userForm.status"
                    label="Estado"
                    outlined
                    dense
                    :options="[
                      { label: 'Activo', value: 'active' },
                      { label: 'Inactivo', value: 'inactive' },
                    ]"
                    emit-value
                    map-options
                  />
                </div>
              </div>

              <q-input v-model="userForm.phone" label="Teléfono / Anexo" outlined dense />

              <div class="row justify-end q-gutter-sm q-pt-sm">
                <q-btn flat label="Cancelar" color="grey-7" v-close-popup no-caps />
                <q-btn
                  unelevated
                  :label="userDialog.isEditing ? 'Guardar Cambios' : 'Crear Usuario'"
                  color="primary"
                  type="submit"
                  no-caps
                />
              </div>
            </q-form>
          </q-card-section>
        </q-card>
      </q-dialog>
    </q-page>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { useUsersApi } from '@/composables/useUsersApi'
import { useNotification } from '@/composables/useNotification'

const notify = useNotification()
const {
  loading,
  getUsersRequest,
  createUserRequest,
  updateUserRequest,
  deleteUserRequest,
  getRolesRequest,
} = useUsersApi()

const activeTab = ref('users')

const users = ref([
  {
    id: 1,
    name: 'Super Administrador',
    email: 'admin@deperumaster.edu.pe',
    phone: '(01) 456-7890',
    avatar_url: 'https://cdn.quasar.dev/img/avatar.png',
    status: 'active',
    role_slug: 'super_admin',
    role_name: 'Super Administrador',
  },
  {
    id: 2,
    name: 'Coordinador Académico (Editor)',
    email: 'editor@deperumaster.edu.pe',
    phone: '+51 987 654 321',
    avatar_url: 'https://cdn.quasar.dev/img/avatar2.jpg',
    status: 'active',
    role_slug: 'editor',
    role_name: 'Editor de Contenidos',
  },
  {
    id: 3,
    name: 'Prensa e Imagen Institucional',
    email: 'prensa@deperumaster.edu.pe',
    phone: '+51 954 321 987',
    avatar_url: 'https://cdn.quasar.dev/img/avatar4.jpg',
    status: 'active',
    role_slug: 'redactor',
    role_name: 'Redactor Académico',
  },
])

const rolesList = ref([
  {
    slug: 'super_admin',
    name: 'Super Administrador',
    description: 'Acceso total y control de todo el sistema CMS',
    permissions: [
      { slug: 'pages.view', name: 'Ver Páginas' },
      { slug: 'pages.create', name: 'Crear Páginas' },
      { slug: 'pages.edit', name: 'Editar Páginas' },
      { slug: 'pages.builder', name: 'Usar Page Builder' },
      { slug: 'menus.manage', name: 'Gestionar Menús' },
      { slug: 'settings.manage', name: 'Gestionar Ajustes' },
    ],
  },
  {
    slug: 'editor',
    name: 'Editor de Contenidos',
    description: 'Puede editar y publicar páginas, medios y noticias',
    permissions: [
      { slug: 'pages.view', name: 'Ver Páginas' },
      { slug: 'pages.edit', name: 'Editar Páginas' },
      { slug: 'posts.create', name: 'Crear Noticias' },
      { slug: 'media.upload', name: 'Subir Medios' },
    ],
  },
  {
    slug: 'redactor',
    name: 'Redactor Académico',
    description: 'Puede crear y editar borradores de noticias',
    permissions: [
      { slug: 'posts.view', name: 'Ver Noticias' },
      { slug: 'posts.create', name: 'Crear Noticias' },
      { slug: 'posts.edit', name: 'Editar Noticias' },
      { slug: 'media.view', name: 'Ver Medios' },
    ],
  },
])

const columns = [
  { name: 'name', label: 'Usuario & Correo', field: 'name', align: 'left', sortable: true },
  { name: 'role', label: 'Rol RBAC', field: 'role_name', align: 'left' },
  { name: 'phone', label: 'Teléfono', field: 'phone', align: 'left' },
  { name: 'status', label: 'Estado', field: 'status', align: 'center' },
  { name: 'actions', label: 'Acciones', align: 'right' },
]

function normalizeUser(raw) {
  const firstRole = raw.roles?.[0]
  return {
    id: raw.id,
    name: raw.name,
    email: raw.email,
    phone: raw.phone || '-',
    avatar_url: raw.avatar_url || 'https://cdn.quasar.dev/img/avatar.png',
    status: raw.status || 'active',
    role_slug: firstRole?.slug || raw.role_slug || 'editor',
    role_name: firstRole?.name || raw.role_name || 'Editor de Contenidos',
  }
}

async function fetchUsersAndRoles() {
  try {
    const [uList, rList] = await Promise.all([getUsersRequest(), getRolesRequest()])
    if (Array.isArray(uList) && uList.length > 0) {
      users.value = uList.map(normalizeUser)
    }
    if (Array.isArray(rList) && rList.length > 0) {
      rolesList.value = rList
    }
  } catch {
    // Mantiene respaldo local
  }
}

onMounted(() => {
  fetchUsersAndRoles()
})

function getRoleBadgeColor(slug) {
  if (slug === 'super_admin') return 'primary'
  if (slug === 'editor') return 'teal'
  return 'orange-8'
}

const userDialog = reactive({
  open: false,
  isEditing: false,
  editingId: null,
})

const userForm = reactive({
  name: '',
  email: '',
  password: '',
  phone: '',
  role_slug: 'editor',
  status: 'active',
})

function openCreateDialog() {
  userDialog.isEditing = false
  userDialog.editingId = null
  userForm.name = ''
  userForm.email = ''
  userForm.password = 'password123#'
  userForm.phone = ''
  userForm.role_slug = 'editor'
  userForm.status = 'active'
  userDialog.open = true
}

function openEditDialog(row) {
  userDialog.isEditing = true
  userDialog.editingId = row.id
  userForm.name = row.name
  userForm.email = row.email
  userForm.password = ''
  userForm.phone = row.phone === '-' ? '' : row.phone
  userForm.role_slug = row.role_slug
  userForm.status = row.status
  userDialog.open = true
}

async function saveUser() {
  const payload = {
    name: userForm.name,
    email: userForm.email,
    phone: userForm.phone,
    status: userForm.status,
    role_slug: userForm.role_slug,
  }
  if (userForm.password) {
    payload.password = userForm.password
  }

  if (userDialog.isEditing) {
    try {
      const updated = await updateUserRequest(userDialog.editingId, payload)
      if (updated) {
        await fetchUsersAndRoles()
      }
    } catch {
      const target = users.value.find((u) => u.id === userDialog.editingId)
      if (target) Object.assign(target, payload)
    }
    notify.success('Usuario actualizado correctamente')
  } else {
    try {
      const created = await createUserRequest(payload)
      if (created) {
        await fetchUsersAndRoles()
      }
    } catch {
      users.value.push(
        normalizeUser({
          id: Date.now(),
          ...payload,
        }),
      )
    }
    notify.success('Usuario creado exitosamente')
  }
  userDialog.open = false
}

async function handleDelete(row) {
  const ok = await notify.confirm({
    title: '¿Eliminar usuario?',
    message: `¿Estás seguro de eliminar la cuenta "${row.email}"?`,
    ok: 'Eliminar',
    color: 'negative',
  })
  if (ok) {
    users.value = users.value.filter((u) => u.id !== row.id)
    try {
      await deleteUserRequest(row.id)
    } catch {
      // Mantiene borrado local
    }
    notify.success('Usuario eliminado')
  }
}
</script>

<style scoped>
.rounded-borders {
  border-radius: 12px;
}
.font-mono {
  font-family: monospace;
}
</style>

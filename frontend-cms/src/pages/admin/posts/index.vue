<template>
  <AdminLayout>
    <q-page class="q-pa-lg">
      <!-- Encabezado -->
      <div class="row items-center justify-between q-mb-lg">
        <div>
          <q-breadcrumbs class="text-caption text-grey-7">
            <q-breadcrumbs-el label="Dashboard" icon="dashboard" to="/admin" />
            <q-breadcrumbs-el label="Noticias & Blog" icon="article" />
          </q-breadcrumbs>
          <h1 class="text-h4 text-weight-bold text-dark q-my-xs">
            Noticias & Publicaciones del Blog
          </h1>
          <div class="text-subtitle2 text-grey-7">
            Gestiona los comunicados institucionales, notas de prensa, artículos académicos y si
            muestran barra lateral (Aside).
          </div>
        </div>

        <q-btn
          color="primary"
          unelevated
          icon="add"
          label="Nueva Noticia"
          no-caps
          @click="openCreatePost"
        />
      </div>

      <!-- Filtros de Búsqueda y Categorías -->
      <q-card flat bordered class="q-mb-md rounded-borders">
        <q-card-section class="q-py-sm">
          <div class="row q-col-gutter-md items-center justify-between">
            <div class="col-12 col-sm-6 col-md-4">
              <q-input
                v-model="search"
                dense
                outlined
                placeholder="Buscar por título o contenido..."
                clearable
              >
                <template #prepend><q-icon name="search" /></template>
              </q-input>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
              <q-select
                v-model="selectedCategory"
                dense
                outlined
                :options="[
                  { label: 'Todas las Categorías', value: 'all' },
                  { label: 'Institucional & Comunicados', value: 'institucional' },
                  { label: 'Admisión & Matrícula', value: 'admision' },
                  { label: 'Tecnología & Carreras', value: 'tecnologia' },
                  { label: 'Convenios', value: 'convenios' },
                  { label: 'Vida Estudiantil & Eventos', value: 'vida' },
                ]"
                emit-value
                map-options
              />
            </div>
          </div>
        </q-card-section>
      </q-card>

      <!-- Tabla de Noticias -->
      <q-card flat bordered class="rounded-borders">
        <q-table
          flat
          :rows="filteredPosts"
          :columns="columns"
          row-key="id"
          :pagination="{ rowsPerPage: 10 }"
        >
          <!-- Portada y Título -->
          <template #body-cell-title="props">
            <q-td :props="props">
              <div class="row items-center no-wrap">
                <q-img
                  :src="props.row.featured_image || '/img/blog/blog-01.jpg'"
                  style="width: 50px; height: 38px; border-radius: 4px"
                  class="q-mr-md"
                />
                <div>
                  <div class="text-weight-bold text-dark">{{ props.row.title }}</div>
                  <div class="text-caption text-grey-6">
                    /{{ props.row.slug }} · Por {{ props.row.author }}
                  </div>
                </div>
              </div>
            </q-td>
          </template>

          <!-- Categoría (Taxonomía) -->
          <template #body-cell-category="props">
            <q-td :props="props">
              <q-badge color="primary" outline :label="props.row.category" />
            </q-td>
          </template>

          <!-- Diseño (Con Aside vs Sin Aside) -->
          <template #body-cell-template="props">
            <q-td :props="props" class="text-center">
              <q-badge
                :color="props.row.template === 'full-width' ? 'blue-grey-6' : 'teal-7'"
                class="q-px-sm q-py-xs"
              >
                <q-icon
                  :name="props.row.template === 'full-width' ? 'aspect_ratio' : 'vertical_split'"
                  size="14px"
                  class="q-mr-xs"
                />
                {{ props.row.template === 'full-width' ? 'Sin Aside (Ancho Completo)' : 'Con Aside Derecha' }}
              </q-badge>
            </q-td>
          </template>

          <!-- Estado -->
          <template #body-cell-status="props">
            <q-td :props="props" class="text-center">
              <q-chip
                size="sm"
                dense
                :color="props.row.status === 'published' ? 'positive' : 'warning'"
                text-color="white"
              >
                {{ props.row.status === 'published' ? 'Publicado' : 'Borrador' }}
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
                @click="openEditPost(props.row)"
              >
                <q-tooltip>Editar noticia</q-tooltip>
              </q-btn>
              <q-btn
                flat
                round
                dense
                size="sm"
                icon="visibility"
                color="grey-8"
                :href="`/#/noticias/${props.row.slug}`"
                target="_blank"
              >
                <q-tooltip>Ver noticia en vivo</q-tooltip>
              </q-btn>
              <q-btn
                flat
                round
                dense
                size="sm"
                icon="delete"
                color="negative"
                @click="deletePost(props.row)"
              >
                <q-tooltip>Eliminar</q-tooltip>
              </q-btn>
            </q-td>
          </template>
        </q-table>
      </q-card>

      <!-- Diálogo de Redacción / Edición de Noticia -->
      <q-dialog v-model="postDialog.open" persistent>
        <q-card style="width: 780px; max-width: 95vw" class="rounded-borders">
          <q-card-section class="row items-center justify-between q-pb-sm bg-grey-1">
            <div class="text-h6 text-weight-bold text-primary">
              {{ postDialog.isEditing ? 'Editar Noticia / Comunicado' : 'Redactar Nueva Noticia' }}
            </div>
            <q-btn v-close-popup flat round dense icon="close" />
          </q-card-section>

          <q-separator />

          <q-card-section class="q-pt-md scroll" style="max-height: 78vh">
            <q-form class="q-gutter-md" @submit="savePost">
              <q-input
                v-model="postForm.title"
                label="Título de la Noticia *"
                outlined
                dense
                :rules="[(val) => !!val || 'El título es requerido']"
              />

              <div class="row q-col-gutter-sm">
                <div class="col-12 col-sm-4">
                  <q-select
                    v-model="postForm.category"
                    label="Categoría Académica"
                    outlined
                    dense
                    :options="['Admisión', 'Tecnología', 'Convenios', 'Vida Estudiantil', 'Institucional']"
                  />
                </div>
                <div class="col-12 col-sm-4">
                  <q-select
                    v-model="postForm.template"
                    label="Diseño de Página (Aside Opcional)"
                    outlined
                    dense
                    :options="[
                      { label: 'Con Barra Lateral Derecha (Aside)', value: 'with-sidebar' },
                      { label: 'Ancho Completo (Sin Aside)', value: 'full-width' },
                    ]"
                    emit-value
                    map-options
                  />
                </div>
                <div class="col-12 col-sm-4">
                  <q-select
                    v-model="postForm.status"
                    label="Estado de Publicación"
                    outlined
                    dense
                    :options="[
                      { label: 'Publicado', value: 'published' },
                      { label: 'Borrador', value: 'draft' },
                    ]"
                    emit-value
                    map-options
                  />
                </div>
              </div>

              <MediaPickerInput
                v-model="postForm.featured_image"
                label="Imagen Principal de Portada"
              />

              <q-input
                v-model="postForm.excerpt"
                label="Resumen / Extracto de la Entrada (SEO & Listado)"
                type="textarea"
                rows="2"
                outlined
                dense
              />

              <div>
                <div class="row items-center justify-between q-mb-xs">
                  <span class="text-caption text-weight-bold text-grey-8">
                    Cuerpo Completo del Artículo (Soporta citas Blockquote y listas)
                  </span>
                  <q-btn-toggle
                    v-model="editorMode"
                    dense
                    unelevated
                    size="sm"
                    no-caps
                    toggle-color="primary"
                    color="grey-2"
                    text-color="grey-8"
                    :options="[
                      { label: 'Editor Visual', value: 'wysiwyg' },
                      { label: 'Código HTML', value: 'html' },
                    ]"
                  />
                </div>

                <q-editor
                  v-if="editorMode === 'wysiwyg'"
                  v-model="postForm.content"
                  min-height="220px"
                  :toolbar="[
                    ['left', 'center', 'right', 'justify'],
                    ['bold', 'italic', 'underline'],
                    ['quote', 'unordered', 'ordered'],
                    ['undo', 'redo'],
                    ['viewsource'],
                  ]"
                />
                <q-input
                  v-else
                  v-model="postForm.content"
                  type="textarea"
                  rows="8"
                  outlined
                  dense
                  class="font-mono"
                />
              </div>

              <div class="row justify-end q-gutter-sm q-pt-sm">
                <q-btn v-close-popup flat label="Cancelar" color="grey-7" no-caps />
                <q-btn unelevated label="Guardar Noticia" color="primary" type="submit" no-caps />
              </div>
            </q-form>
          </q-card-section>
        </q-card>
      </q-dialog>
    </q-page>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import MediaPickerInput from '@/components/admin/builder/MediaPickerInput.vue'
import { useNotification } from '@/composables/useNotification'
import { usePostsApi } from '@/composables/usePostsApi'
import { usePublicCmsStore } from '@/stores/public-cms'

const notify = useNotification()
const publicCmsStore = usePublicCmsStore()
const { getPostsRequest, createPostRequest, updatePostRequest, deletePostRequest } = usePostsApi()

const search = ref('')
const selectedCategory = ref('all')
const editorMode = ref('wysiwyg')

const posts = ref([])

function normalizePost(raw) {
  return {
    id: raw.id,
    title: raw.title,
    slug: raw.slug,
    template: raw.template || 'with-sidebar',
    author:
      typeof raw.author === 'object'
        ? raw.author?.name || 'Super Administrador'
        : raw.author || 'Super Administrador',
    category: raw.terms?.[0]?.name || raw.category || 'Institucional',
    featured_image: raw.featured_image || '/img/blog/blog-01.jpg',
    status: raw.status || 'published',
    published_at: raw.published_at
      ? String(raw.published_at).slice(0, 16).replace('T', ' ')
      : String(raw.created_at || new Date().toISOString())
          .slice(0, 16)
          .replace('T', ' '),
    excerpt: raw.excerpt || '',
    content: raw.content_html || raw.content || '',
  }
}

onMounted(async () => {
  try {
    const list = await getPostsRequest()
    if (Array.isArray(list) && list.length > 0) {
      posts.value = list.map(normalizePost)
    }
  } catch {
    // Fallback
  }
})

const columns = [
  { name: 'title', label: 'Noticia / Autor', field: 'title', align: 'left', sortable: true },
  { name: 'category', label: 'Categoría', field: 'category', align: 'left' },
  { name: 'template', label: 'Diseño / Aside', field: 'template', align: 'center', sortable: true },
  { name: 'status', label: 'Estado', field: 'status', align: 'center', sortable: true },
  { name: 'published_at', label: 'Fecha', field: 'published_at', align: 'left', sortable: true },
  { name: 'actions', label: 'Acciones', align: 'right' },
]

const postDialog = reactive({
  open: false,
  isEditing: false,
  editingId: null,
})

const postForm = reactive({
  title: '',
  category: 'Institucional',
  template: 'with-sidebar',
  status: 'published',
  featured_image: '/img/blog/blog-01.jpg',
  excerpt: '',
  content: '',
})

const filteredPosts = computed(() => {
  const q = (search.value || '').toLowerCase()
  return posts.value.filter((p) => {
    const matchSearch = p.title.toLowerCase().includes(q)
    const matchCat =
      selectedCategory.value === 'all' || p.category.toLowerCase().includes(selectedCategory.value)
    return matchSearch && matchCat
  })
})

function openCreatePost() {
  postDialog.isEditing = false
  postDialog.editingId = null
  postForm.title = ''
  postForm.category = 'Institucional'
  postForm.template = 'with-sidebar'
  postForm.status = 'published'
  postForm.featured_image = '/img/blog/blog-01.jpg'
  postForm.excerpt = ''
  postForm.content = ''
  postDialog.open = true
}

function openEditPost(post) {
  postDialog.isEditing = true
  postDialog.editingId = post.id
  postForm.title = post.title
  postForm.category = post.category
  postForm.template = post.template || 'with-sidebar'
  postForm.status = post.status
  postForm.featured_image = post.featured_image
  postForm.excerpt = post.excerpt || ''
  postForm.content = post.content || ''
  postDialog.open = true
}

async function savePost() {
  const cleanSlug = postForm.title
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/(^-|-$)/g, '')

  if (postDialog.isEditing) {
    const target = posts.value.find((p) => p.id === postDialog.editingId)
    if (target) Object.assign(target, postForm)
    try {
      await updatePostRequest(postDialog.editingId, {
        title: postForm.title,
        template: postForm.template,
        status: postForm.status,
        featured_image: postForm.featured_image,
        excerpt: postForm.excerpt,
        content_html: postForm.content,
      })
      if (target?.slug) {
        delete publicCmsStore.postsBySlug[target.slug]
      }
    } catch {
      // Mantiene edición optimista
    }
    notify.success('Noticia actualizada correctamente')
  } else {
    try {
      const created = await createPostRequest({
        title: postForm.title,
        slug: `${cleanSlug}-${Date.now().toString().slice(-4)}`,
        template: postForm.template,
        status: postForm.status,
        featured_image: postForm.featured_image,
        excerpt: postForm.excerpt,
        content_html: postForm.content,
        published_at: new Date().toISOString(),
      })
      if (created) {
        posts.value.unshift(normalizePost({ ...created, category: postForm.category }))
      }
    } catch {
      posts.value.unshift({
        id: Date.now(),
        title: postForm.title,
        slug: cleanSlug,
        template: postForm.template,
        author: 'Super Administrador',
        category: postForm.category,
        featured_image: postForm.featured_image,
        status: postForm.status,
        published_at: new Date().toISOString().slice(0, 16).replace('T', ' '),
        excerpt: postForm.excerpt,
        content: postForm.content,
      })
    }
    notify.success('Noticia creada con éxito')
  }
  postDialog.open = false
}

async function deletePost(post) {
  const ok = await notify.confirm({
    title: '¿Eliminar noticia?',
    message: `¿Estás seguro de eliminar "${post.title}"?`,
    ok: 'Eliminar',
    color: 'negative',
  })
  if (ok) {
    posts.value = posts.value.filter((p) => p.id !== post.id)
    try {
      await deletePostRequest(post.id)
      if (post.slug) {
        delete publicCmsStore.postsBySlug[post.slug]
      }
    } catch {
      // Mantiene borrado local
    }
    notify.success('Noticia eliminada')
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

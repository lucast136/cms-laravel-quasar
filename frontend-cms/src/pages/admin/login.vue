<template>
  <q-layout view="lHh Lpr lFf" class="login-layout">
    <q-page-container>
      <q-page class="flex flex-center q-pa-md">
        <q-card flat bordered class="login-card shadow-10">
          <!-- Cabecera Institucional -->
          <q-card-section class="bg-primary text-white text-center q-py-lg">
            <q-avatar
              size="64px"
              color="white"
              text-color="primary"
              class="text-weight-bolder shadow-3 q-mb-sm"
            >
              <span style="font-size: 22px; font-weight: 900">DPM</span>
            </q-avatar>
            <div class="text-h5 text-weight-bold">IESTP DEPERU MASTER</div>
            <div class="text-caption text-blue-2 q-mt-xs">
              Acceso al Panel de Administración CMS (Sanctum RBAC)
            </div>
          </q-card-section>

          <!-- Formulario de Inicio de Sesión -->
          <q-card-section class="q-pa-lg">
            <q-form class="q-gutter-y-md" @submit="handleLogin">
              <q-input
                v-model="form.email"
                type="email"
                label="Correo Electrónico Institucional"
                outlined
                dense
                lazy-rules
                :rules="[(val) => !!val || 'Ingresa tu correo electrónico']"
              >
                <template #prepend>
                  <q-icon name="mail" color="primary" />
                </template>
              </q-input>

              <q-input
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                label="Contraseña"
                outlined
                dense
                lazy-rules
                :rules="[(val) => !!val || 'Ingresa tu contraseña']"
              >
                <template #prepend>
                  <q-icon name="lock" color="primary" />
                </template>
                <template #append>
                  <q-icon
                    :name="showPassword ? 'visibility_off' : 'visibility'"
                    class="cursor-pointer"
                    @click="showPassword = !showPassword"
                  />
                </template>
              </q-input>

              <q-banner
                v-if="errorMessage"
                dense
                rounded
                class="bg-red-1 text-negative text-caption"
              >
                <template #avatar>
                  <q-icon name="error_outline" color="negative" size="xs" />
                </template>
                {{ errorMessage }}
              </q-banner>

              <q-btn
                type="submit"
                color="primary"
                unelevated
                class="full-width q-py-sm text-weight-bold"
                icon="login"
                label="Iniciar Sesión en el CMS"
                no-caps
                :loading="authStore.loading"
              />
            </q-form>

            <q-separator class="q-my-md" />

            <!-- Selector Rápido de Cuentas Seeders para Pruebas RBAC -->
            <div class="text-caption text-grey-7 text-weight-bold q-mb-xs">
              Cuentas creadas en Base de Datos (Clic para autocompletar):
            </div>
            <div class="column q-gutter-y-xs">
              <q-item
                v-for="acc in demoAccounts"
                :key="acc.email"
                clickable
                v-ripple
                dense
                class="bg-grey-1 rounded-borders border-grey q-py-xs"
                @click="fillCredentials(acc)"
              >
                <q-item-section avatar>
                  <q-icon :name="acc.icon" :color="acc.color" size="sm" />
                </q-item-section>
                <q-item-section>
                  <q-item-label class="text-caption text-weight-bold text-dark">
                    {{ acc.label }}
                  </q-item-label>
                  <q-item-label caption style="font-size: 11px" class="font-mono">
                    {{ acc.email }}
                  </q-item-label>
                </q-item-section>
                <q-item-section side>
                  <q-badge :color="acc.color" :label="acc.role" />
                </q-item-section>
              </q-item>
            </div>
          </q-card-section>

          <q-separator />

          <q-card-actions align="between" class="q-px-md bg-grey-1">
            <q-btn
              flat
              dense
              size="sm"
              color="grey-7"
              icon="arrow_back"
              label="Volver a la Web Pública"
              no-caps
              to="/"
            />
            <span class="text-caption text-grey-6">Laravel 13 + Sanctum</span>
          </q-card-actions>
        </q-card>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useNotification } from '@/composables/useNotification'

const router = useRouter()
const authStore = useAuthStore()
const notify = useNotification()

const showPassword = ref(false)
const errorMessage = ref('')

const form = reactive({
  email: 'admin@deperumaster.edu.pe',
  password: 'password123#',
  device_name: 'QuasarAdminSPA',
})

const demoAccounts = [
  {
    label: 'Super Administrador',
    email: 'admin@deperumaster.edu.pe',
    password: 'password123#',
    role: 'super_admin',
    icon: 'verified_user',
    color: 'primary',
  },
  {
    label: 'Coordinador Académico',
    email: 'editor@deperumaster.edu.pe',
    password: 'password123#',
    role: 'editor',
    icon: 'edit_note',
    color: 'teal',
  },
  {
    label: 'Prensa e Imagen',
    email: 'prensa@deperumaster.edu.pe',
    password: 'password123#',
    role: 'redactor',
    icon: 'article',
    color: 'orange-8',
  },
]

function fillCredentials(acc) {
  form.email = acc.email
  form.password = acc.password
  errorMessage.value = ''
}

async function handleLogin() {
  errorMessage.value = ''
  const ok = await authStore.login({
    email: form.email,
    password: form.password,
    device_name: form.device_name,
  })
  if (ok) {
    notify.success(`Bienvenido, ${authStore.user?.name || 'Administrador'}`)
    router.push('/admin')
  } else {
    errorMessage.value = 'Credenciales incorrectas o servidor API no disponible en el puerto 8000.'
  }
}
</script>

<style scoped>
.login-layout {
  background: linear-gradient(135deg, #0a1128 0%, #003278 60%, #004b87 100%);
  min-height: 100vh;
}
.login-card {
  width: 100%;
  max-width: 440px;
  border-radius: 16px;
  overflow: hidden;
}
.border-grey {
  border: 1px solid #e2e8f0;
}
.font-mono {
  font-family: monospace;
}
</style>

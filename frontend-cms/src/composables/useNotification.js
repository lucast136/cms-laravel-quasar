import { useQuasar } from 'quasar'

/**
 * Composable para notificaciones y diálogos nativos de Quasar
 * con el estilo institucional del IESTP DEPERU MASTER.
 */
export function useNotification() {
  const $q = useQuasar()

  function success(message, caption = '') {
    $q.notify({
      type: 'positive',
      icon: 'check_circle',
      message,
      caption: caption || undefined,
      position: 'top-right',
      timeout: 3000,
      progress: true,
    })
  }

  function error(message, caption = '') {
    $q.notify({
      type: 'negative',
      icon: 'error',
      message,
      caption: caption || undefined,
      position: 'top-right',
      timeout: 5000,
      progress: true,
    })
  }

  function warning(message, caption = '') {
    $q.notify({
      type: 'warning',
      icon: 'warning',
      textColor: 'dark',
      message,
      caption: caption || undefined,
      position: 'top-right',
      timeout: 4000,
      progress: true,
    })
  }

  function info(message, caption = '') {
    $q.notify({
      type: 'info',
      icon: 'info',
      message,
      caption: caption || undefined,
      position: 'top-right',
      timeout: 3000,
      progress: true,
    })
  }

  function confirm({
    title = '¿Estás seguro?',
    message = 'Esta acción no se puede deshacer.',
    ok = 'Aceptar',
    cancel = 'Cancelar',
    color = 'primary',
  }) {
    return new Promise((resolve) => {
      $q.dialog({
        title,
        message,
        cancel: {
          label: cancel,
          flat: true,
          color: 'grey-7',
        },
        ok: {
          label: ok,
          unelevated: true,
          color,
        },
        persistent: true,
      })
        .onOk(() => resolve(true))
        .onCancel(() => resolve(false))
        .onDismiss(() => resolve(false))
    })
  }

  return {
    success,
    error,
    warning,
    info,
    confirm,
  }
}

import { reactive, ref } from 'vue'
import { siteConfig } from '@/config/site.config'

const emptyForm = (source) => ({
  name: '',
  phone: '',
  email: '',
  message: source === 'callback' ? 'Please call me back.' : '',
  source,
  website: '',
})

export function useLeadForm(source = 'contact') {
  const form = reactive(emptyForm(source))
  const status = ref('idle')
  const feedback = ref('')

  function reset() {
    Object.assign(form, emptyForm(source))
    status.value = 'idle'
    feedback.value = ''
  }

  async function submit() {
    if (status.value === 'submitting') return

    status.value = 'submitting'
    feedback.value = ''

    try {
      const response = await fetch(siteConfig.api.endpoint, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
        },
        body: JSON.stringify(form),
      })

      let result = {}
      try {
        result = await response.json()
      } catch {
        throw new Error(siteConfig.ui.contactError)
      }

      if (!response.ok || !result.success) {
        throw new Error(result.message || siteConfig.ui.contactError)
      }

      status.value = 'success'
      feedback.value = result.message || siteConfig.ui.contactSuccess
      Object.assign(form, emptyForm(source))
    } catch (error) {
      status.value = 'error'
      feedback.value = error instanceof Error
        ? error.message
        : siteConfig.ui.contactError
    }
  }

  return {
    form,
    status,
    feedback,
    reset,
    submit,
  }
}

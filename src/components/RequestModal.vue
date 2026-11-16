<script setup>
import { watch } from 'vue'
import { ArrowUpRight, X } from '@lucide/vue'
import { siteConfig } from '@/config/site.config'
import { useLeadForm } from '@/composables/useLeadForm'

const props = defineProps({
  open: {
    type: Boolean,
    default: false,
  },
})
const emit = defineEmits(['close'])
const { form, status, feedback, submit, reset } = useLeadForm('callback')

watch(() => props.open, (isOpen) => {
  if (isOpen) reset()
})

function close() {
  emit('close')
}
</script>

<template>
  <Teleport to="body">
    <div v-if="open" class="modal-backdrop" role="presentation" @click.self="close">
      <section class="request-modal" role="dialog" aria-modal="true" aria-labelledby="callback-title">
        <button class="modal-close" type="button" :aria-label="siteConfig.ui.close" @click="close">
          <X :size="22" />
        </button>
        <div class="section-kicker"><span></span> {{ siteConfig.ui.callbackKicker }}</div>
        <h2 id="callback-title">{{ siteConfig.ui.callbackTitle }}</h2>
        <p>{{ siteConfig.ui.callbackDescription }}</p>
        <form class="lead-form lead-form--modal" @submit.prevent="submit">
          <label class="sr-only" for="callback-name">{{ siteConfig.ui.namePlaceholder }}</label>
          <input id="callback-name" v-model.trim="form.name" type="text" :placeholder="siteConfig.ui.namePlaceholder" autocomplete="name" maxlength="100" required />

          <label class="sr-only" for="callback-phone">{{ siteConfig.ui.phonePlaceholder }}</label>
          <input id="callback-phone" v-model.trim="form.phone" type="tel" :placeholder="siteConfig.ui.phonePlaceholder" autocomplete="tel" maxlength="24" required />

          <div class="form-honeypot" aria-hidden="true">
            <label for="callback-website">Website</label>
            <input id="callback-website" v-model="form.website" type="text" tabindex="-1" autocomplete="off" />
          </div>

          <p v-if="feedback" class="form-feedback" :class="'is-' + status" role="status" aria-live="polite">{{ feedback }}</p>
          <button class="btn btn-primary btn-submit" type="submit" :disabled="status === 'submitting'">
            {{ status === 'submitting' ? siteConfig.ui.sending : siteConfig.ui.send }}
            <ArrowUpRight :size="18" />
          </button>
        </form>
      </section>
    </div>
  </Teleport>
</template>

<script setup>
import { MessageCircle } from 'lucide-vue-next'
import { siteConfig } from '@/config/site.config'
</script>

<template>
  <a
    class="floating-whatsapp"
    :href="siteConfig.whatsapp.messageUrl"
    target="_blank"
    rel="noreferrer"
    :aria-label="siteConfig.ui.messageOnWhatsApp"
    :title="siteConfig.ui.messageOnWhatsApp"
  >
    <MessageCircle :size="25" :stroke-width="2.2" />
    <span>{{ siteConfig.ui.messageOnWhatsApp }}</span>
  </a>
</template>

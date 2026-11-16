<script setup>
import { Mail, MapPin, Phone } from '@lucide/vue'
import { siteConfig } from '@/config/site.config'
import { useLeadForm } from '@/composables/useLeadForm'

const { form, status, feedback, submit } = useLeadForm('contact')
</script>

<template>
  <section id="s-contact" class="contact-section section-space">
    <div class="container contact-layout">
      <div class="contact-copy">
        <div class="section-kicker"><span></span> {{ siteConfig.ui.contactKicker }}</div>
        <h2 class="section-title section-title--left">{{ siteConfig.contactSection.title }}</h2>
        <p class="body-copy">{{ siteConfig.contactSection.description }}</p>

        <div class="contact-image">
          <img :src="siteConfig.contactSection.image" :alt="siteConfig.contactSection.imageAlt" loading="lazy" />
        </div>

        <div class="contact-details">
          <a :href="siteConfig.contact.mapUrl" target="_blank" rel="noreferrer" class="contact-detail">
            <span><MapPin :size="19" /></span>
            <span><small>{{ siteConfig.ui.address }}</small><strong>{{ siteConfig.contact.address }}</strong></span>
          </a>
          <a :href="'tel:' + siteConfig.contact.phoneE164" class="contact-detail">
            <span><Phone :size="19" /></span>
            <span><small>{{ siteConfig.ui.phone }}</small><strong>{{ siteConfig.contact.phoneDisplay }}</strong></span>
          </a>
          <a v-if="siteConfig.contact.email" :href="'mailto:' + siteConfig.contact.email" class="contact-detail">
            <span><Mail :size="19" /></span>
            <span><small>{{ siteConfig.ui.email }}</small><strong>{{ siteConfig.contact.email }}</strong></span>
          </a>
        </div>
      </div>

      <div class="contact-form-wrap">
        <div class="form-heading">
          <span class="form-heading__eyebrow">{{ siteConfig.ui.formEyebrow }}</span>
          <h3>{{ siteConfig.ui.formTitle }}</h3>
        </div>
        <form class="lead-form" @submit.prevent="submit">
          <label class="sr-only" for="contact-name">{{ siteConfig.ui.namePlaceholder }}</label>
          <input id="contact-name" v-model.trim="form.name" type="text" name="name" :placeholder="siteConfig.ui.namePlaceholder" autocomplete="name" maxlength="100" required />

          <label class="sr-only" for="contact-phone">{{ siteConfig.ui.phonePlaceholder }}</label>
          <input id="contact-phone" v-model.trim="form.phone" type="tel" name="phone" :placeholder="siteConfig.ui.phonePlaceholder" autocomplete="tel" maxlength="24" required />

          <label class="sr-only" for="contact-email">{{ siteConfig.ui.emailPlaceholder }}</label>
          <input id="contact-email" v-model.trim="form.email" type="email" name="email" :placeholder="siteConfig.ui.emailPlaceholder" autocomplete="email" maxlength="190" />

          <label class="sr-only" for="contact-message">{{ siteConfig.ui.messagePlaceholder }}</label>
          <textarea id="contact-message" v-model.trim="form.message" name="message" :placeholder="siteConfig.ui.messagePlaceholder" rows="4" maxlength="2000"></textarea>

          <div class="form-honeypot" aria-hidden="true">
            <label for="contact-website">Website</label>
            <input id="contact-website" v-model="form.website" type="text" name="website" tabindex="-1" autocomplete="off" />
          </div>

          <p v-if="feedback" class="form-feedback" :class="'is-' + status" role="status" aria-live="polite">{{ feedback }}</p>
          <button class="btn btn-primary btn-submit" type="submit" :disabled="status === 'submitting'">
            {{ status === 'submitting' ? siteConfig.ui.sending : siteConfig.ui.send }}
            <span aria-hidden="true">→</span>
          </button>
          <p class="form-privacy">{{ siteConfig.ui.formPrivacy }}</p>
        </form>
      </div>
    </div>
  </section>
</template>

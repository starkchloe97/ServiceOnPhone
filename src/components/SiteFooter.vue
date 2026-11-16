<script setup>
import { ArrowUpRight, MapPin, Phone } from '@lucide/vue'
import { siteConfig } from '@/config/site.config'
</script>

<template>
  <footer class="site-footer">
    <div class="container footer-main">
      <div class="footer-brand">
        <a class="brand brand--footer" href="#top">
          <span class="brand-mark"><span>{{ siteConfig.brandMarkText }}</span></span>
          <span class="brand-copy">
            <strong>{{ siteConfig.siteName }}</strong>
            <small>{{ siteConfig.businessName }}</small>
          </span>
        </a>
        <p>{{ siteConfig.footer.description }}</p>
        <div v-if="siteConfig.socialLinks.length" class="footer-social">
          <a v-for="social in siteConfig.socialLinks" :key="social.name" :href="social.url" target="_blank" rel="noreferrer" :aria-label="social.name">
            {{ social.name }}
          </a>
        </div>
      </div>

      <div class="footer-column">
        <h3>{{ siteConfig.ui.ourServices }}</h3>
        <ul>
          <li v-for="service in siteConfig.services" :key="service.title">
            <a href="#s-services">{{ service.title }}</a>
          </li>
        </ul>
      </div>

      <div class="footer-column footer-schedule">
        <h3>{{ siteConfig.ui.schedule }}</h3>
        <ul>
          <li v-for="item in siteConfig.openingHours" :key="item.day">
            <span>{{ item.day }}</span>
            <span>{{ item.hours }}</span>
          </li>
        </ul>
      </div>

      <div class="footer-column footer-contact">
        <h3>{{ siteConfig.ui.contacts }}</h3>
        <a :href="siteConfig.contact.mapUrl" target="_blank" rel="noreferrer">
          <MapPin :size="17" />
          <span>{{ siteConfig.contact.address }}</span>
        </a>
        <a :href="'tel:' + siteConfig.contact.phoneE164">
          <Phone :size="17" />
          <span>{{ siteConfig.contact.phoneDisplay }}</span>
        </a>
        <a v-if="siteConfig.contact.email" :href="'mailto:' + siteConfig.contact.email">
          <ArrowUpRight :size="17" />
          <span>{{ siteConfig.contact.email }}</span>
        </a>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="container footer-bottom__row">
        <span>© {{ new Date().getFullYear() }} {{ siteConfig.siteName }}. {{ siteConfig.footer.copyright }}</span>
        <a href="#top">{{ siteConfig.ui.backToTop }} ↑</a>
      </div>
    </div>
  </footer>
</template>

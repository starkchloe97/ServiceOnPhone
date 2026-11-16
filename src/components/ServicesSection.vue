<script setup>
import { ArrowUpRight } from '@lucide/vue'
import { siteConfig } from '@/config/site.config'

const emit = defineEmits(['request-service'])
</script>

<template>
  <section id="s-services" class="services-section section-space">
    <div class="container">
      <div class="section-heading">
        <div class="section-kicker"><span></span> {{ siteConfig.ui.servicesKicker }}</div>
        <h2 class="section-title">{{ siteConfig.ui.servicesTitle }}</h2>
        <p class="section-intro">{{ siteConfig.ui.servicesIntro }}</p>
      </div>

      <div class="services-grid">
        <article v-for="(service, index) in siteConfig.services" :key="service.title" class="service-card">
          <div class="service-card__image">
            <img :src="service.image" :alt="service.imageAlt" loading="lazy" />
            <span class="service-card__number">0{{ index + 1 }}</span>
          </div>
          <div class="service-card__body">
            <h3>{{ service.title }}</h3>
            <p>{{ service.description }}</p>
            <button class="service-link" type="button" @click="emit('request-service')">
              {{ siteConfig.ui.orderService }}
              <ArrowUpRight :size="17" />
            </button>
          </div>
        </article>
      </div>
    </div>
  </section>
</template>

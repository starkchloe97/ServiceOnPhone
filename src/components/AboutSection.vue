<script setup>
import { ArrowUpRight, ShieldCheck } from '@lucide/vue'
import { siteConfig } from '@/config/site.config'

const emit = defineEmits(['request-service'])
</script>

<template>
  <section id="s-about" class="about-section section-space">
    <div class="container about-layout">
      <div class="about-copy">
        <div class="section-kicker"><span></span> {{ siteConfig.ui.aboutKicker }}</div>
        <h2 class="section-title section-title--left">{{ siteConfig.about.title }}</h2>
        <p v-for="paragraph in siteConfig.about.paragraphs" :key="paragraph" class="body-copy">{{ paragraph }}</p>
        <div class="about-assurance">
          <span class="assurance-icon"><ShieldCheck :size="23" /></span>
          <div>
            <strong>{{ siteConfig.ui.assuranceTitle }}</strong>
            <span>{{ siteConfig.ui.assuranceText }}</span>
          </div>
        </div>
        <button class="btn btn-primary" type="button" @click="emit('request-service')">
          {{ siteConfig.about.buttonLabel }}
          <ArrowUpRight :size="18" />
        </button>
      </div>
      <div class="about-visual">
        <img :src="siteConfig.about.image" :alt="siteConfig.about.imageAlt" loading="lazy" />
        <div class="about-visual__note">
          <span class="note-icon"><ShieldCheck :size="20" /></span>
          <span>{{ siteConfig.ui.aboutVisualNote }}</span>
        </div>
        <span class="about-visual__shape" aria-hidden="true"></span>
      </div>
    </div>
  </section>
</template>

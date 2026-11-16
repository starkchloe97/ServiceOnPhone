<script setup>
import { siteConfig } from '@/config/site.config'
</script>

<template>
  <section class="stats-section">
    <div class="container stats-layout">
      <div class="stats-intro">
        <div class="section-kicker section-kicker--light"><span></span> {{ siteConfig.ui.statsKicker }}</div>
        <h2 class="section-title section-title--light">{{ siteConfig.stats.title }}</h2>
        <p>{{ siteConfig.stats.description }}</p>
      </div>
      <div class="stats-grid">
        <div v-for="item in siteConfig.stats.items" :key="item.label" class="stat-item">
          <strong>{{ item.value }}</strong>
          <span>{{ item.label }}</span>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed, ref } from 'vue'
import { ArrowUpRight, X } from 'lucide-vue-next'
import { siteConfig } from '@/config/site.config'

const expanded = ref(false)
const selectedImage = ref(null)
const visibleImages = computed(() => expanded.value ? siteConfig.gallery : siteConfig.gallery.slice(0, 6))

function closeImage() {
  selectedImage.value = null
}
</script>

<template>
  <section id="s-gallery" class="gallery-section section-space">
    <div class="container">
      <div class="section-heading">
        <div class="section-kicker"><span></span> {{ siteConfig.ui.galleryKicker }}</div>
        <h2 class="section-title">{{ siteConfig.ui.galleryTitle }}</h2>
        <p class="section-intro">{{ siteConfig.ui.galleryIntro }}</p>
      </div>

      <div class="gallery-grid">
        <button
          v-for="(item, index) in visibleImages"
          :key="item.caption + index"
          class="gallery-card"
          type="button"
          :aria-label="siteConfig.ui.viewImage + ': ' + item.caption"
          @click="selectedImage = item"
        >
          <img :src="item.image" :alt="item.alt" loading="lazy" />
          <span class="gallery-card__overlay">
            <span>{{ item.caption }}</span>
            <ArrowUpRight :size="20" />
          </span>
        </button>
      </div>

      <div v-if="siteConfig.gallery.length > 6" class="gallery-action">
        <button class="btn btn-outline" type="button" @click="expanded = !expanded">
          {{ expanded ? siteConfig.ui.showLess : siteConfig.ui.showMore }}
          <ArrowUpRight :size="18" />
        </button>
      </div>
    </div>

    <div v-if="selectedImage" class="lightbox" role="dialog" aria-modal="true" :aria-label="selectedImage.caption" @click.self="closeImage">
      <button class="lightbox__close" type="button" :aria-label="siteConfig.ui.close" @click="closeImage">
        <X :size="25" />
      </button>
      <figure>
        <img :src="selectedImage.image" :alt="selectedImage.alt" />
        <figcaption>{{ selectedImage.caption }}</figcaption>
      </figure>
    </div>
  </section>
</template>

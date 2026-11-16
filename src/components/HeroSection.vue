<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue'
import { ArrowRight, ChevronLeft, ChevronRight, MessageCircle, Phone } from '@lucide/vue'
import { siteConfig } from '@/config/site.config'

const activeIndex = ref(0)
let slideTimer

const activeSlide = computed(() => siteConfig.hero.slides[activeIndex.value])

function setSlide(index) {
  const total = siteConfig.hero.slides.length
  activeIndex.value = (index + total) % total
}

function nextSlide() {
  setSlide(activeIndex.value + 1)
}

function previousSlide() {
  setSlide(activeIndex.value - 1)
}

onMounted(() => {
  slideTimer = window.setInterval(nextSlide, 6500)
})

onUnmounted(() => {
  window.clearInterval(slideTimer)
})
</script>

<template>
  <section id="top" class="hero-section" :aria-label="siteConfig.ui.heroAriaLabel">
    <div class="hero-backdrop" :style="{ backgroundImage: 'url(' + activeSlide.image + ')' }"></div>
    <div class="hero-overlay"></div>
    <div class="container hero-content">
      <div class="hero-copy">
        <span class="hero-eyebrow"><span class="eyebrow-dot"></span>{{ siteConfig.hero.eyebrow }}</span>
        <Transition name="hero-fade" mode="out-in">
          <div :key="activeIndex" class="hero-copy__slide">
            <h1>{{ activeSlide.title }}</h1>
            <p>{{ activeSlide.description }}</p>
          </div>
        </Transition>
        <div class="hero-actions">
          <a class="btn btn-primary" :href="'tel:' + siteConfig.contact.phoneE164">
            <Phone :size="18" />
            {{ siteConfig.ui.callNow }}
          </a>
          <a class="btn btn-whatsapp" :href="siteConfig.whatsapp.callUrl" target="_blank" rel="noreferrer">
            <MessageCircle :size="18" />
            {{ siteConfig.ui.callOnWhatsApp }}
          </a>
          <a class="hero-text-link" :href="siteConfig.whatsapp.messageUrl" target="_blank" rel="noreferrer">
            {{ siteConfig.ui.messageOnWhatsApp }}
            <ArrowRight :size="17" />
          </a>
        </div>
      </div>

      <div class="hero-controls" aria-label="Hero slides">
        <button type="button" class="hero-arrow" :aria-label="siteConfig.ui.previousSlide" @click="previousSlide">
          <ChevronLeft :size="23" />
        </button>
        <div class="hero-dots">
          <button
            v-for="(slide, index) in siteConfig.hero.slides"
            :key="slide.title"
            type="button"
            :class="{ active: index === activeIndex }"
            :aria-label="siteConfig.ui.slideLabel + ' ' + (index + 1)"
            :aria-current="index === activeIndex ? 'true' : undefined"
            @click="setSlide(index)"
          ></button>
        </div>
        <button type="button" class="hero-arrow" :aria-label="siteConfig.ui.nextSlide" @click="nextSlide">
          <ChevronRight :size="23" />
        </button>
      </div>
    </div>
    <div class="hero-index"><span>0{{ activeIndex + 1 }}</span><i></i><span>0{{ siteConfig.hero.slides.length }}</span></div>
  </section>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue'
import { ArrowUpRight, MapPin, Menu, Phone, X } from 'lucide-vue-next'
import { siteConfig } from '@/config/site.config'

const emit = defineEmits(['request-call'])
const menuOpen = ref(false)
const isScrolled = ref(false)

function updateScroll() {
  isScrolled.value = window.scrollY > 130
}

function closeMenu() {
  menuOpen.value = false
}

onMounted(() => window.addEventListener('scroll', updateScroll, { passive: true }))
onUnmounted(() => window.removeEventListener('scroll', updateScroll))
</script>

<template>
  <header class="site-header">
    <div class="header-top">
      <div class="container header-top__row">
        <p class="header-tagline">{{ siteConfig.tagline }}</p>
        <div class="header-top__details">
          <a class="header-info" :href="siteConfig.contact.mapUrl" target="_blank" rel="noreferrer">
            <MapPin :size="17" :stroke-width="1.8" />
            <span>{{ siteConfig.contact.address }}</span>
          </a>
          <a class="header-info" :href="'tel:' + siteConfig.contact.phoneE164">
            <Phone :size="17" :stroke-width="1.8" />
            <span>{{ siteConfig.contact.phoneDisplay }}</span>
          </a>
        </div>
      </div>
    </div>

    <div class="header-nav-wrap" :class="{ 'is-fixed': isScrolled }">
      <div class="container">
        <div class="header-nav-card">
          <a class="brand" href="#top" aria-label="Go to top" @click="closeMenu">
            <span class="brand-mark"><span>{{ siteConfig.brandMarkText }}</span></span>
            <span class="brand-copy">
              <strong>{{ siteConfig.siteName }}</strong>
              <small>{{ siteConfig.businessName }}</small>
            </span>
          </a>

          <nav class="desktop-nav" aria-label="Main navigation">
            <a v-for="item in siteConfig.navigation" :key="item.href" :href="item.href">{{ item.label }}</a>
          </nav>

          <button class="btn btn-nav" type="button" @click="emit('request-call')">
            {{ siteConfig.ui.requestCall }}
            <ArrowUpRight :size="17" />
          </button>

          <button
            class="menu-toggle"
            type="button"
            :aria-label="menuOpen ? siteConfig.ui.menuClose : siteConfig.ui.menuOpen"
            :aria-expanded="menuOpen"
            @click="menuOpen = !menuOpen"
          >
            <X v-if="menuOpen" :size="24" />
            <Menu v-else :size="24" />
          </button>
        </div>

        <nav v-if="menuOpen" class="mobile-nav" aria-label="Mobile navigation">
          <a v-for="item in siteConfig.navigation" :key="item.href" :href="item.href" @click="closeMenu">
            {{ item.label }}
          </a>
          <button type="button" class="mobile-nav__request" @click="closeMenu(); emit('request-call')">
            {{ siteConfig.ui.requestCall }}
            <ArrowUpRight :size="17" />
          </button>
        </nav>
      </div>
    </div>
  </header>
</template>

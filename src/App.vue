<script setup>
import { ref } from 'vue'
import SiteHeader from '@/components/SiteHeader.vue'
import HeroSection from '@/components/HeroSection.vue'
import AboutSection from '@/components/AboutSection.vue'
import StatsSection from '@/components/StatsSection.vue'
import ServicesSection from '@/components/ServicesSection.vue'
import GallerySection from '@/components/GallerySection.vue'
import ContactSection from '@/components/ContactSection.vue'
import SiteFooter from '@/components/SiteFooter.vue'
import FloatingWhatsApp from '@/components/FloatingWhatsApp.vue'
import RequestModal from '@/components/RequestModal.vue'

const requestModalOpen = ref(false)
</script>

<template>
  <SiteHeader @request-call="requestModalOpen = true" />
  <main>
    <HeroSection />
    <AboutSection @request-service="requestModalOpen = true" />
    <StatsSection />
    <ServicesSection @request-service="requestModalOpen = true" />
    <GallerySection />
    <ContactSection />
  </main>
  <SiteFooter />
  <FloatingWhatsApp />
  <RequestModal :open="requestModalOpen" @close="requestModalOpen = false" />
</template>

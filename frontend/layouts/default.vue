<template>
  <div style="min-height: 100vh; display: flex; flex-direction: column;">
    <AppHeader />
    <main style="flex-grow: 1;">
      <slot />
    </main>
    <AppFooter />
    <CartDrawer />
    <ToastNotification />

    <!-- Customer Engagement & Acquisition Modules -->
    <WelcomeOfferModal />
    <ShowroomConcierge />
    <SocialProofToast />
  </div>
</template>

<script setup lang="ts">
import AppHeader from '~/components/AppHeader.vue'
import AppFooter from '~/components/AppFooter.vue'
import CartDrawer from '~/components/CartDrawer.vue'
import ToastNotification from '~/components/ToastNotification.vue'
import WelcomeOfferModal from '~/components/WelcomeOfferModal.vue'
import ShowroomConcierge from '~/components/ShowroomConcierge.vue'
import SocialProofToast from '~/components/SocialProofToast.vue'
import { useSchemaOrg } from '~/composables/useSchemaOrg'

const { injectLocalBusinessSchema } = useSchemaOrg()

onMounted(() => {
  injectLocalBusinessSchema()
})
</script>


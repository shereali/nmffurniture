<template>
  <div style="min-height: 100vh; display: flex; flex-direction: column;">
    <AppHeader />
    <main style="flex-grow: 1;">
      <slot />
    </main>
    <AppFooter />
    <CartDrawer />
    <ToastNotification />
  </div>
</template>

<script setup lang="ts">
import AppHeader from '~/components/AppHeader.vue'
import AppFooter from '~/components/AppFooter.vue'
import CartDrawer from '~/components/CartDrawer.vue'
import ToastNotification from '~/components/ToastNotification.vue'
</script>

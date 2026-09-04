<template>
  <div class="p-8 text-center">
    <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 2rem; color: var(--color-secondary-dark);"></i>
    <p style="margin-top: 1rem; color: var(--color-text-muted);">Loading Navigation Menu Manager...</p>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: 'admin',
  middleware: ['auth']
})

await navigateTo('/admin/settings?tab=menus')
</script>

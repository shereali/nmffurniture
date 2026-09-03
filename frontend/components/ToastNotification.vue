<template>
  <div class="toast-container" v-if="toastStore.toasts.length > 0">
    <div
      v-for="toast in toastStore.toasts"
      :key="toast.id"
      :class="['toast', toast.type]"
    >
      <i v-if="toast.type === 'success'" class="fa-solid fa-circle-check" style="color: #25D366;"></i>
      <i v-else-if="toast.type === 'error'" class="fa-solid fa-circle-exclamation" style="color: var(--color-danger);"></i>
      <i v-else class="fa-solid fa-circle-info" style="color: var(--color-info);"></i>
      <span>{{ toast.message }}</span>
      <button @click="toastStore.remove(toast.id)" style="background: transparent; border: none; color: #888; cursor: pointer; margin-left: 0.5rem;">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useToastStore } from '~/stores/toast'
const toastStore = useToastStore()
</script>

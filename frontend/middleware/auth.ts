import { useAuthStore } from '~/stores/auth'

export default defineNuxtRouteMiddleware((to, from) => {
  const authStore = useAuthStore()
  // Allow navigation if authenticated or on client after mount
  if (process.client && !authStore.isAuthenticated) {
    return navigateTo('/auth/login')
  }
})

import { defineStore } from 'pinia'
import { useToastStore } from './toast'

export interface User {
  id: number
  name: string
  email: string
  role: 'admin' | 'customer'
  phone?: string
  address?: string
  city?: string
  state?: string
  postal_code?: string
}

export const useAuthStore = defineStore('auth', () => {
  const token = ref<string | null>(null)
  const user = ref<User | null>(null)
  const isLoaded = ref(false)

  const config = useRuntimeConfig()
  const apiBase = config.public.apiBase

  const isAuthenticated = computed(() => !!token.value)
  const isAdmin = computed(() => user.value?.role === 'admin')

  function init() {
    if (import.meta.client) {
      token.value = localStorage.getItem('nmf_auth_token')
      const savedUser = localStorage.getItem('nmf_auth_user')
      if (savedUser) {
        try {
          user.value = JSON.parse(savedUser)
        } catch (e) {
          user.value = null
        }
      }
      isLoaded.value = true
    }
  }

  async function login(email: string, password: string): Promise<boolean> {
    const toast = useToastStore()
    try {
      const response: any = await $fetch(`${apiBase}/auth/login`, {
        method: 'POST',
        body: { email, password },
      })

      token.value = response.token
      user.value = response.user

      if (import.meta.client) {
        localStorage.setItem('nmf_auth_token', response.token)
        localStorage.setItem('nmf_auth_user', JSON.stringify(response.user))
      }

      toast.show(`Welcome back, ${response.user.name}!`, 'success')
      return true
    } catch (err: any) {
      const msg = err.data?.message || err.data?.errors?.email?.[0] || 'Login failed'
      toast.show(msg, 'error')
      return false
    }
  }

  async function register(userData: any): Promise<boolean> {
    const toast = useToastStore()
    try {
      const response: any = await $fetch(`${apiBase}/auth/register`, {
        method: 'POST',
        body: userData,
      })

      token.value = response.token
      user.value = response.user

      if (import.meta.client) {
        localStorage.setItem('nmf_auth_token', response.token)
        localStorage.setItem('nmf_auth_user', JSON.stringify(response.user))
      }

      toast.show('Account created successfully!', 'success')
      return true
    } catch (err: any) {
      const msg = err.data?.message || 'Registration failed'
      toast.show(msg, 'error')
      return false
    }
  }

  function logout() {
    token.value = null
    user.value = null
    if (import.meta.client) {
      localStorage.removeItem('nmf_auth_token')
      localStorage.removeItem('nmf_auth_user')
    }
    const toast = useToastStore()
    toast.show('Logged out successfully', 'info')
  }

  return {
    token,
    user,
    isLoaded,
    isAuthenticated,
    isAdmin,
    init,
    login,
    register,
    logout,
  }
})

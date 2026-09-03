import { defineStore } from 'pinia'

export interface ToastMessage {
  id: number
  message: string
  type: 'success' | 'error' | 'info'
}

export const useToastStore = defineStore('toast', () => {
  const toasts = ref<ToastMessage[]>([])
  let nextId = 1

  function show(message: string, type: 'success' | 'error' | 'info' = 'success', duration = 4000) {
    const id = nextId++
    toasts.value.push({ id, message, type })

    setTimeout(() => {
      remove(id)
    }, duration)
  }

  function remove(id: number) {
    toasts.value = toasts.value.filter(t => t.id !== id)
  }

  return {
    toasts,
    show,
    remove,
  }
})

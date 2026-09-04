import { defineStore } from 'pinia'

export interface NavigationMenuItemData {
  id: number
  navigation_menu_id: number
  parent_id?: number | null
  title: string
  url: string
  icon?: string | null
  badge?: string | null
  target?: string
  sort_order: number
  is_active: boolean
  children?: NavigationMenuItemData[]
}

export interface NavigationMenuData {
  id: number
  title: string
  location: string
  items: NavigationMenuItemData[]
}

export const useSettingsStore = defineStore('settings', {
  state: () => ({
    settings: {} as Record<string, any>,
    menus: {} as Record<string, NavigationMenuData>,
    isLoaded: false,
    isLoading: false,
  }),

  getters: {
    getSetting: (state) => (key: string, fallback: string = ''): string => {
      if (state.settings && state.settings[key] !== undefined && state.settings[key] !== null) {
        return state.settings[key]
      }
      return fallback
    },

    getMenu: (state) => (location: string): NavigationMenuItemData[] => {
      if (state.menus && state.menus[location] && state.menus[location].items && Array.isArray(state.menus[location].items)) {
        return state.menus[location].items
      }
      return []
    },

    isAnnouncementEnabled(): boolean {
      const val = this.getSetting('announcement_enabled', '1')
      return val === '1' || val === 'true' || val === true
    },
  },

  actions: {
    async fetchSettings() {
      const config = useRuntimeConfig()
      const apiBase = config.public.apiBase || 'http://localhost:8001/api'

      this.isLoading = true
      try {
        const data: any = await $fetch(`${apiBase}/settings`)
        if (data) {
          if (data.settings) this.settings = data.settings
          if (data.menus) this.menus = data.menus
          this.isLoaded = true
        }
      } catch (err) {
        console.warn('Failed to load dynamic website settings, using defaults', err)
      } finally {
        this.isLoading = false
      }
    },

    async refreshSettings() {
      const config = useRuntimeConfig()
      const apiBase = config.public.apiBase || 'http://localhost:8001/api'

      try {
        const data: any = await $fetch(`${apiBase}/settings`)
        if (data) {
          if (data.settings) this.settings = data.settings
          if (data.menus) this.menus = data.menus
        }
      } catch (err) {
        console.error('Failed to refresh settings', err)
      }
    }
  }
})

import { useSettingsStore } from '~/stores/settings'

export default defineNuxtPlugin(async () => {
  const settingsStore = useSettingsStore()
  if (!settingsStore.isLoaded) {
    await settingsStore.fetchSettings()
  }
})

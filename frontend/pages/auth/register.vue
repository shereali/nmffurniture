<template>
  <div class="py-16" style="background-color: var(--color-bg-alt); min-height: 80vh; display: flex; align-items: center;">
    <div class="container" style="max-width: 500px;">
      <div style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 2.5rem; box-shadow: var(--shadow-md);">
        <div style="text-align: center; margin-bottom: 2rem;">
          <h2 style="font-size: 1.8rem; margin-bottom: 0.5rem;">Create Account</h2>
          <p style="font-size: 0.9rem; color: var(--color-text-muted);">Join NMFFurniture for custom order tracking and VIP perks.</p>
        </div>

        <form @submit.prevent="handleRegister" class="flex flex-col gap-4">
          <div class="form-group">
            <label class="form-label">Full Name *</label>
            <input v-model="form.name" type="text" class="form-input" required placeholder="e.g. Faris Danial" />
          </div>

          <div class="form-group">
            <label class="form-label">Email Address *</label>
            <input v-model="form.email" type="email" class="form-input" required placeholder="e.g. faris@example.com" />
          </div>

          <div class="form-group">
            <label class="form-label">Password * (min 6 characters)</label>
            <input v-model="form.password" type="password" class="form-input" required placeholder="••••••••" />
          </div>

          <div class="form-group">
            <label class="form-label">Phone / WhatsApp Number</label>
            <input v-model="form.phone" type="tel" class="form-input" placeholder="e.g. +60123456789" />
          </div>

          <button
            :disabled="loading"
            type="submit"
            class="btn btn-primary btn-lg"
            style="width: 100%; margin-top: 0.5rem;"
          >
            <i class="fa-solid fa-user-plus"></i>
            {{ loading ? 'Creating Account...' : 'Register Account' }}
          </button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem; border-top: 1px solid var(--color-border); padding-top: 1.25rem;">
          Already have an account?
          <NuxtLink to="/auth/login" style="color: var(--color-secondary-dark); font-weight: 700;">
            Sign in here
          </NuxtLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'

const authStore = useAuthStore()
const router = useRouter()

const loading = ref(false)
const form = reactive({
  name: '',
  email: '',
  password: '',
  phone: '',
})

async function handleRegister() {
  loading.value = true
  const success = await authStore.register(form)
  loading.value = false

  if (success) {
    router.push('/')
  }
}
</script>

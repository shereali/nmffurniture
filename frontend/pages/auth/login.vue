<template>
  <div class="py-16" style="background-color: var(--color-bg-alt); min-height: 80vh; display: flex; align-items: center;">
    <div class="container" style="max-width: 460px;">
      <div style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 2.5rem; box-shadow: var(--shadow-md);">
        <div style="text-align: center; margin-bottom: 2rem;">
          <h2 style="font-size: 1.8rem; margin-bottom: 0.5rem;">Sign In</h2>
          <p style="font-size: 0.9rem; color: var(--color-text-muted);">Access your orders and account privileges.</p>
        </div>

        <!-- Quick Admin / Demo Credentials Hint -->
        <div style="background: var(--color-secondary-light); border: 1px solid rgba(197, 168, 128, 0.5); padding: 0.75rem 1rem; border-radius: var(--radius-sm); font-size: 0.8rem; margin-bottom: 1.5rem;">
          <div style="font-weight: 700; color: var(--color-secondary-dark); margin-bottom: 0.25rem;">
            Demo Credentials:
          </div>
          <div>Admin: <strong>admin@nmffurniture.com</strong> / <strong>password123</strong></div>
          <div>Customer: <strong>customer@example.com</strong> / <strong>password123</strong></div>
        </div>

        <form @submit.prevent="handleLogin" class="flex flex-col gap-4">
          <div class="form-group">
            <label class="form-label">Email Address</label>
            <input
              v-model="email"
              type="email"
              class="form-input"
              required
              placeholder="e.g. admin@nmffurniture.com"
            />
          </div>

          <div class="form-group">
            <label class="form-label">Password</label>
            <input
              v-model="password"
              type="password"
              class="form-input"
              required
              placeholder="••••••••"
            />
          </div>

          <button
            :disabled="loading"
            type="submit"
            class="btn btn-primary btn-lg"
            style="width: 100%; margin-top: 0.5rem;"
          >
            <i class="fa-solid fa-right-to-bracket"></i>
            {{ loading ? 'Signing In...' : 'Sign In' }}
          </button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem; border-top: 1px solid var(--color-border); padding-top: 1.25rem;">
          Don't have an account?
          <NuxtLink to="/auth/register" style="color: var(--color-secondary-dark); font-weight: 700;">
            Register here
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
const route = useRoute()

const email = ref('admin@nmffurniture.com')
const password = ref('password123')
const loading = ref(false)

async function handleLogin() {
  loading.value = true
  const success = await authStore.login(email.value, password.value)
  loading.value = false

  if (success) {
    const redirect = (route.query.redirect as string) || (authStore.isAdmin ? '/admin' : '/')
    router.push(redirect)
  }
}
</script>

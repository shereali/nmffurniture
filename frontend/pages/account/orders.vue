<template>
  <div class="py-12" style="background-color: var(--color-bg-alt); min-height: 80vh;">
    <div class="container">
      <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
        <div>
          <h2>MY ACCOUNT & ORDERS</h2>
          <div class="divider-gold" style="margin-left: 0; margin-bottom: 0;"></div>
        </div>
        <button @click="handleLogout" class="btn btn-outline btn-sm">
          <i class="fa-solid fa-right-from-bracket"></i> Logout
        </button>
      </div>

      <!-- User Profile Card -->
      <div style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 1.5rem 2rem; margin-bottom: 2.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem;">
        <div class="flex items-center gap-4">
          <div style="width: 54px; height: 54px; border-radius: 50%; background: var(--color-secondary-light); color: var(--color-secondary-dark); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700;">
            {{ authStore.user?.name?.charAt(0) || 'U' }}
          </div>
          <div>
            <h3 style="font-size: 1.25rem; margin-bottom: 0.2rem;">{{ authStore.user?.name }}</h3>
            <div style="font-size: 0.85rem; color: var(--color-text-muted);">
              {{ authStore.user?.email }} • {{ authStore.user?.phone || 'No phone added' }}
            </div>
          </div>
        </div>

        <div style="font-size: 0.85rem; color: var(--color-text-muted); max-width: 350px;">
          <strong>Default Shipping:</strong><br />
          {{ authStore.user?.address || 'No address saved yet' }}, {{ authStore.user?.city }} {{ authStore.user?.state }}
        </div>
      </div>

      <!-- Orders Section -->
      <h3 style="font-size: 1.4rem; margin-bottom: 1.25rem;">Order History</h3>

      <div v-if="loading" class="text-center py-12">
        <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 2rem; color: var(--color-secondary-dark);"></i>
      </div>

      <div v-else-if="orders.length === 0" style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 3.5rem; text-align: center;">
        <i class="fa-solid fa-box-open" style="font-size: 3rem; color: var(--color-border-dark); margin-bottom: 1rem;"></i>
        <h4>No orders placed yet</h4>
        <p style="margin-bottom: 1.5rem;">Explore our sofas, armchairs, and dining tables to place your first order.</p>
        <NuxtLink to="/shop" class="btn btn-primary">Start Shopping</NuxtLink>
      </div>

      <div v-else class="flex flex-col gap-4">
        <div
          v-for="order in orders"
          :key="order.id"
          style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 1.5rem;"
        >
          <div class="flex justify-between items-center" style="border-bottom: 1px solid var(--color-border); padding-bottom: 1rem; margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem;">
            <div>
              <span style="font-weight: 700; font-size: 1.1rem; color: var(--color-primary);">{{ order.order_number }}</span>
              <div style="font-size: 0.8rem; color: var(--color-text-light);">
                Placed on {{ new Date(order.created_at).toLocaleDateString('en-MY', { day: 'numeric', month: 'short', year: 'numeric' }) }}
              </div>
            </div>

            <div class="flex items-center gap-3">
              <span :class="['badge-status', order.order_status]">
                Status: {{ order.order_status }}
              </span>
              <span :class="['badge-status', order.payment_status === 'paid' ? 'paid' : 'pending']">
                {{ order.payment_status }}
              </span>
              <span style="font-weight: 700; font-size: 1.15rem; color: var(--color-primary);">
                RM {{ Number(order.total).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
              </span>
            </div>
          </div>

          <!-- Items list -->
          <div class="flex flex-col gap-2">
            <div
              v-for="item in order.items"
              :key="item.id"
              class="flex justify-between items-center"
              style="font-size: 0.9rem;"
            >
              <div>
                <span style="font-weight: 600;">{{ item.product_name }}</span>
                <span v-if="item.variant_option" style="color: var(--color-secondary-dark); margin-left: 0.5rem;">
                  ({{ item.variant_option }})
                </span>
                <span style="color: var(--color-text-light); margin-left: 0.5rem;">x{{ item.quantity }}</span>
              </div>
              <span style="font-weight: 600;">
                RM {{ Number(item.total).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'

const authStore = useAuthStore()
const config = useRuntimeConfig()
const apiBase = config.public.apiBase
const router = useRouter()

const orders = ref<any[]>([])
const loading = ref(true)

async function loadUserOrders() {
  if (!authStore.token) return
  loading.value = true
  try {
    const res: any = await $fetch(`${apiBase}/user/orders`, {
      headers: {
        Authorization: `Bearer ${authStore.token}`
      }
    })
    orders.value = res.orders || []
  } catch (e) {
    console.error('Failed to load orders', e)
  } finally {
    loading.value = false
  }
}

function handleLogout() {
  authStore.logout()
  router.push('/auth/login')
}

onMounted(() => {
  authStore.init()
  if (!authStore.isAuthenticated) {
    router.push('/auth/login?redirect=/account/orders')
  } else {
    loadUserOrders()
  }
})
</script>

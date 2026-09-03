<template>
  <div>
    <div style="margin-bottom: 2rem;">
      <h1 style="font-size: 1.85rem; font-family: var(--font-sans); font-weight: 700; margin-bottom: 0.25rem;">
        Dashboard Overview
      </h1>
      <p style="font-size: 0.9rem; color: var(--color-text-muted);">Real-time performance metrics and store management.</p>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon" style="background-color: #E8F5E9; color: #2E7D32;">
          <i class="fa-solid fa-money-bill-wave"></i>
        </div>
        <div class="stat-info">
          <h3>RM {{ Number(metrics.total_sales || 0).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</h3>
          <p>Total Revenue (Paid)</p>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background-color: #E3F2FD; color: #1565C0;">
          <i class="fa-solid fa-cart-flatbed"></i>
        </div>
        <div class="stat-info">
          <h3>{{ metrics.total_orders || 0 }}</h3>
          <p>Total Orders ({{ metrics.pending_orders || 0 }} Pending)</p>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background-color: #FFF3E0; color: #E65100;">
          <i class="fa-solid fa-couch"></i>
        </div>
        <div class="stat-info">
          <h3>{{ metrics.total_products || 0 }}</h3>
          <p>Active Models</p>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background-color: #F3E5F5; color: #6A1B9A;">
          <i class="fa-solid fa-users"></i>
        </div>
        <div class="stat-info">
          <h3>{{ metrics.total_customers || 0 }}</h3>
          <p>Registered Customers</p>
        </div>
      </div>
    </div>

    <!-- Recent Orders & Stock Alerts Grid -->
    <div style="display: grid; grid-template-columns: 1.4fr 0.8fr; gap: 2rem; align-items: start;">
      <!-- Recent Orders Card -->
      <div class="data-table-card">
        <div class="data-table-header">
          <h3 style="font-size: 1.15rem; font-weight: 700; font-family: var(--font-sans);">Recent Orders</h3>
          <NuxtLink to="/admin/orders" class="btn btn-outline btn-sm" style="font-size: 0.75rem;">
            View All Orders →
          </NuxtLink>
        </div>

        <table class="data-table" v-if="recentOrders.length > 0">
          <thead>
            <tr>
              <th>Order #</th>
              <th>Customer</th>
              <th>Total (RM)</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="ord in recentOrders" :key="ord.id">
              <td style="font-weight: 700;">{{ ord.order_number }}</td>
              <td>
                <div>{{ ord.customer_name }}</div>
                <div style="font-size: 0.75rem; color: var(--color-text-light);">{{ ord.customer_phone }}</div>
              </td>
              <td style="font-weight: 700;">
                RM {{ Number(ord.total).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
              </td>
              <td>
                <span :class="['badge-status', ord.order_status]">
                  {{ ord.order_status }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-else style="padding: 2.5rem; text-align: center; color: var(--color-text-muted);">
          No recent orders recorded.
        </div>
      </div>

      <!-- Low Stock Alerts -->
      <div class="data-table-card">
        <div class="data-table-header">
          <h3 style="font-size: 1.15rem; font-weight: 700; font-family: var(--font-sans);">Inventory Health</h3>
          <NuxtLink to="/admin/products" class="btn btn-outline btn-sm" style="font-size: 0.75rem;">
            Manage Stock
          </NuxtLink>
        </div>

        <div v-if="lowStockProducts.length > 0" class="flex flex-col gap-3" style="padding: 1.25rem;">
          <div
            v-for="p in lowStockProducts"
            :key="p.id"
            class="flex items-center justify-between"
            style="padding-bottom: 0.75rem; border-bottom: 1px solid var(--color-border);"
          >
            <div>
              <div style="font-weight: 600; font-size: 0.9rem;">{{ p.name }}</div>
              <div style="font-size: 0.75rem; color: var(--color-text-light);">SKU: {{ p.sku }}</div>
            </div>
            <span style="font-weight: 700; color: var(--color-danger); font-size: 0.85rem; background: var(--color-danger-bg); padding: 0.2rem 0.5rem; border-radius: var(--radius-sm);">
              {{ p.stock }} left in stock
            </span>
          </div>
        </div>

        <div v-else style="padding: 2.5rem; text-align: center; color: #2E7D32; font-weight: 600;">
          <i class="fa-solid fa-circle-check"></i> All inventory healthy!
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'

definePageMeta({
  layout: 'admin',
})

const authStore = useAuthStore()
const config = useRuntimeConfig()
const apiBase = config.public.apiBase

const metrics = ref<any>({})
const recentOrders = ref<any[]>([])
const lowStockProducts = ref<any[]>([])

async function loadDashboard() {
  if (!authStore.token) return
  try {
    const res: any = await $fetch(`${apiBase}/admin/dashboard`, {
      headers: {
        Authorization: `Bearer ${authStore.token}`
      }
    })
    metrics.value = res.metrics || {}
    recentOrders.value = res.recent_orders || []
    lowStockProducts.value = res.low_stock_products || []
  } catch (e) {
    console.error('Failed to load admin dashboard', e)
  }
}

onMounted(() => {
  loadDashboard()
})
</script>

<style scoped>
@media (max-width: 992px) {
  div[style*="grid-template-columns: 1.4fr 0.8fr"] {
    grid-template-columns: 1fr !important;
  }
}
</style>

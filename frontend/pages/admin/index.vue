<template>
  <div>
    <div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
      <div>
        <h1 style="font-size: 1.85rem; font-family: var(--font-sans); font-weight: 700; margin-bottom: 0.25rem;">
          Executive Dashboard
        </h1>
        <p style="font-size: 0.9rem; color: var(--color-text-muted);">
          Real-time performance metrics, order fulfillment, customer leads, and factory inventory health.
        </p>
      </div>

      <!-- Quick Action Shortcuts -->
      <div class="flex gap-2 flex-wrap">
        <NuxtLink to="/admin/products" class="btn btn-primary btn-sm">
          <i class="fa-solid fa-plus"></i> New Product
        </NuxtLink>
        <NuxtLink to="/admin/inquiries" class="btn btn-outline btn-sm">
          <i class="fa-solid fa-envelope-open-text"></i> View Leads
        </NuxtLink>
        <button @click="loadDashboard" class="btn btn-outline btn-sm">
          <i class="fa-solid fa-rotate-right"></i>
        </button>
      </div>
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
          <p>Orders ({{ metrics.pending_orders || 0 }} Pending)</p>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background-color: #FCE8E6; color: #C5221F;">
          <i class="fa-solid fa-comments"></i>
        </div>
        <div class="stat-info">
          <h3>{{ metrics.new_inquiries || 0 }}</h3>
          <p>New Inquiries / Leads</p>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background-color: #FFF3E0; color: #E65100;">
          <i class="fa-solid fa-couch"></i>
        </div>
        <div class="stat-info">
          <h3>{{ metrics.total_products || 0 }}</h3>
          <p>Catalog Models</p>
        </div>
      </div>
    </div>

    <!-- Recent Orders & Recent Inquiries Grid -->
    <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 2rem; align-items: start; margin-bottom: 2rem;">
      <!-- Recent Orders Card -->
      <div class="data-table-card">
        <div class="data-table-header">
          <div>
            <h3 style="font-size: 1.15rem; font-weight: 700; font-family: var(--font-sans); margin-bottom: 0.15rem;">
              Recent Orders
            </h3>
            <div style="font-size: 0.75rem; color: var(--color-text-muted);">Awaiting packaging & delivery</div>
          </div>
          <NuxtLink to="/admin/orders" class="btn btn-outline btn-sm" style="font-size: 0.75rem;">
            View All →
          </NuxtLink>
        </div>

        <table class="data-table" v-if="recentOrders.length > 0">
          <thead>
            <tr>
              <th>Order #</th>
              <th>Customer</th>
              <th>Total</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="ord in recentOrders" :key="ord.id">
              <td style="font-weight: 700; color: var(--color-primary);">{{ ord.order_number }}</td>
              <td>
                <div style="font-weight: 600;">{{ ord.customer_name }}</div>
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

      <!-- Recent Inquiries & Leads Card -->
      <div class="data-table-card">
        <div class="data-table-header">
          <div>
            <h3 style="font-size: 1.15rem; font-weight: 700; font-family: var(--font-sans); margin-bottom: 0.15rem;">
              Incoming Customer Leads
            </h3>
            <div style="font-size: 0.75rem; color: var(--color-text-muted);">Custom sofas & showroom requests</div>
          </div>
          <NuxtLink to="/admin/inquiries" class="btn btn-outline btn-sm" style="font-size: 0.75rem;">
            View All →
          </NuxtLink>
        </div>

        <table class="data-table" v-if="recentInquiries.length > 0">
          <thead>
            <tr>
              <th>Client</th>
              <th>Subject</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="inq in recentInquiries" :key="inq.id">
              <td>
                <div style="font-weight: 700;">{{ inq.name }}</div>
                <div style="font-size: 0.75rem; color: var(--color-text-light);">{{ inq.phone }}</div>
              </td>
              <td>
                <div style="font-size: 0.85rem; font-weight: 600;">{{ inq.subject || 'Custom Living' }}</div>
                <div style="font-size: 0.75rem; color: var(--color-text-muted); max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                  {{ inq.message }}
                </div>
              </td>
              <td>
                <span :class="['badge-status', inq.status]">
                  {{ inq.status }}
                </span>
              </td>
              <td>
                <a
                  :href="getWhatsAppLink(inq.phone)"
                  target="_blank"
                  class="btn btn-sm"
                  style="background: #25D366; color: #FFFFFF; border: none; font-size: 0.75rem; padding: 0.2rem 0.5rem;"
                  title="WhatsApp"
                >
                  <i class="fa-brands fa-whatsapp"></i>
                </a>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-else style="padding: 2.5rem; text-align: center; color: var(--color-text-muted);">
          No pending inquiries.
        </div>
      </div>
    </div>

    <!-- Inventory Health -->
    <div class="data-table-card">
      <div class="data-table-header">
        <div>
          <h3 style="font-size: 1.15rem; font-weight: 700; font-family: var(--font-sans); margin-bottom: 0.15rem;">
            Low Stock Inventory Alerts (≤ 10 Units)
          </h3>
          <div style="font-size: 0.75rem; color: var(--color-text-muted);">Products requiring assembly restock</div>
        </div>
        <NuxtLink to="/admin/products" class="btn btn-outline btn-sm" style="font-size: 0.75rem;">
          Manage Catalog →
        </NuxtLink>
      </div>

      <table class="data-table" v-if="lowStockProducts.length > 0">
        <thead>
          <tr>
            <th>Product Model</th>
            <th>SKU</th>
            <th>Stock Left</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="p in lowStockProducts" :key="p.id">
            <td>
              <div class="flex items-center gap-3">
                <img
                  :src="p.images?.[0]?.image_url || 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=100&q=80'"
                  :alt="p.name"
                  style="width: 42px; height: 42px; object-fit: cover; border-radius: var(--radius-sm);"
                />
                <span style="font-weight: 600;">{{ p.name }}</span>
              </div>
            </td>
            <td style="font-family: monospace; font-size: 0.85rem;">{{ p.sku }}</td>
            <td>
              <span style="color: var(--color-danger); font-weight: 700;">
                {{ p.stock }} units remaining
              </span>
            </td>
            <td>
              <NuxtLink to="/admin/products" class="btn btn-outline btn-sm" style="font-size: 0.75rem;">
                Restock
              </NuxtLink>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-else style="padding: 2.5rem; text-align: center; color: var(--color-text-muted);">
        All models have healthy inventory levels (> 10 units).
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
const recentInquiries = ref<any[]>([])
const lowStockProducts = ref<any[]>([])
const loading = ref(false)

function getWhatsAppLink(phone: string) {
  const cleanPhone = (phone || '').replace(/[^0-9]/g, '')
  const formattedPhone = cleanPhone.startsWith('60') ? cleanPhone : cleanPhone.startsWith('0') ? '6' + cleanPhone : cleanPhone
  return `https://wa.me/${formattedPhone}`
}

async function loadDashboard() {
  if (!authStore.token) return
  loading.value = true
  try {
    const res: any = await $fetch(`${apiBase}/admin/dashboard`, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })

    metrics.value = res.metrics || {}
    recentOrders.value = res.recent_orders || []
    recentInquiries.value = res.recent_inquiries || []
    lowStockProducts.value = res.low_stock_products || []
  } catch (e) {
    console.error('Failed to load dashboard metrics', e)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadDashboard()
})
</script>

<template>
  <div>
    <!-- Executive Header & Operational Tooling -->
    <div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
      <div>
        <h1 style="font-size: 1.85rem; font-family: var(--font-sans); font-weight: 700; margin-bottom: 0.25rem;">
          Executive Intelligence Dashboard
        </h1>
        <p style="font-size: 0.9rem; color: var(--color-text-muted);">
          Real-time enterprise analytics, 14-day sales trends, factory fulfillment, and export accounting tools.
        </p>
      </div>

      <!-- Quick Action & Export Shortcuts -->
      <div class="flex gap-2 flex-wrap">
        <!-- CSV Export Dropdown / Actions -->
        <a
          :href="`${apiBase}/admin/export/orders`"
          target="_blank"
          class="btn btn-outline-gold btn-sm"
          title="Export complete order ledger for LHDN/tax & courier bulk manifest"
        >
          <i class="fa-solid fa-file-excel"></i> Export Orders (CSV)
        </a>
        <a
          :href="`${apiBase}/admin/export/inquiries`"
          target="_blank"
          class="btn btn-outline btn-sm"
          title="Export customer swatch requests & leads"
        >
          <i class="fa-solid fa-file-csv"></i> Export Leads (CSV)
        </a>
        <NuxtLink to="/admin/products" class="btn btn-primary btn-sm">
          <i class="fa-solid fa-plus"></i> New Product
        </NuxtLink>
        <button @click="loadDashboard" class="btn btn-outline btn-sm" title="Refresh Live Data">
          <i class="fa-solid fa-rotate-right" :class="{ 'fa-spin': loading }"></i>
        </button>
      </div>
    </div>

    <!-- Primary KPIs Grid -->
    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: 2rem;">
      <div class="stat-card">
        <div class="stat-icon" style="background-color: #E8F5E9; color: #2E7D32;">
          <i class="fa-solid fa-money-bill-wave"></i>
        </div>
        <div class="stat-info">
          <h3>RM {{ Number(metrics.total_sales || 0).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</h3>
          <p>Gross Revenue (Paid)</p>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background-color: #EDE7F6; color: #512DA8;">
          <i class="fa-solid fa-chart-line"></i>
        </div>
        <div class="stat-info">
          <h3>RM {{ Number(metrics.aov || 0).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</h3>
          <p>Average Order Value (AOV)</p>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background-color: #E3F2FD; color: #1565C0;">
          <i class="fa-solid fa-cart-flatbed"></i>
        </div>
        <div class="stat-info">
          <h3>{{ metrics.total_orders || 0 }}</h3>
          <p>Orders ({{ metrics.conversion_rate || 0 }}% Paid Ratio)</p>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background-color: #FCE8E6; color: #C5221F;">
          <i class="fa-solid fa-comments"></i>
        </div>
        <div class="stat-info">
          <h3>{{ metrics.new_inquiries || 0 }}</h3>
          <p>New Inquiries ({{ metrics.inquiry_resolution_rate || 0 }}% Closed)</p>
        </div>
      </div>
    </div>

    <!-- 14-Day Sales Trends Chart & Best Sellers Leaderboard -->
    <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 2rem; margin-bottom: 2rem; align-items: start;">
      <!-- Daily Revenue Bar Chart -->
      <div class="data-table-card" style="padding: 1.5rem;">
        <div class="flex justify-between items-center" style="margin-bottom: 1.25rem;">
          <div>
            <h3 style="font-size: 1.15rem; font-weight: 700; font-family: var(--font-sans); margin-bottom: 0.15rem;">
              14-Day Revenue & Daily Velocity
            </h3>
            <div style="font-size: 0.75rem; color: var(--color-text-muted);">Daily paid order receipts (RM)</div>
          </div>
          <span style="font-size: 0.75rem; background: var(--color-bg-alt); padding: 0.25rem 0.6rem; border-radius: 4px; font-weight: 700; color: var(--color-secondary-dark);">
            LIVE VELOCITY
          </span>
        </div>

        <!-- Custom Visual Bar Chart -->
        <div v-if="dailyTrends.length > 0" class="chart-container">
          <div
            v-for="(day, idx) in dailyTrends"
            :key="idx"
            class="chart-bar-wrap"
            :title="`${day.label}: RM ${day.revenue.toFixed(2)} (${day.orders} orders)`"
          >
            <div class="chart-bar-tooltip">RM {{ Math.round(day.revenue) }}</div>
            <div
              class="chart-bar"
              :style="{
                height: `${calculateBarHeight(day.revenue)}%`,
                backgroundColor: day.revenue > 0 ? 'var(--color-primary)' : '#E2E8F0'
              }"
            ></div>
            <div class="chart-bar-label">{{ day.label }}</div>
          </div>
        </div>
        <div v-else style="text-align: center; padding: 2rem; color: var(--color-text-muted);">
          No sales trends available yet.
        </div>
      </div>

      <!-- Top Selling Furniture Pieces -->
      <div class="data-table-card" style="padding: 1.5rem;">
        <div class="flex justify-between items-center" style="margin-bottom: 1.25rem;">
          <div>
            <h3 style="font-size: 1.15rem; font-weight: 700; font-family: var(--font-sans); margin-bottom: 0.15rem;">
              Top Selling Furniture
            </h3>
            <div style="font-size: 0.75rem; color: var(--color-text-muted);">Ranked by gross revenue</div>
          </div>
          <NuxtLink to="/admin/products" class="btn btn-outline btn-sm" style="font-size: 0.75rem;">
            Catalog →
          </NuxtLink>
        </div>

        <div v-if="topProducts.length > 0" class="flex flex-col gap-3">
          <div
            v-for="(prod, pIdx) in topProducts"
            :key="pIdx"
            class="flex items-center justify-between"
            style="padding: 0.6rem 0.75rem; background: var(--color-bg-alt); border-radius: var(--radius-sm);"
          >
            <div class="flex items-center gap-2.5">
              <span style="font-weight: 800; font-size: 0.85rem; color: var(--color-secondary-dark); width: 20px;">
                #{{ pIdx + 1 }}
              </span>
              <div>
                <div style="font-weight: 700; font-size: 0.9rem; color: var(--color-primary);">
                  {{ prod.product_name }}
                </div>
                <div style="font-size: 0.72rem; color: var(--color-text-muted);">
                  SKU: {{ prod.product_sku }} • {{ prod.units_sold }} units sold
                </div>
              </div>
            </div>
            <div style="text-align: right;">
              <span style="font-weight: 800; font-size: 0.9rem; color: var(--color-primary);">
                RM {{ Number(prod.revenue).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
              </span>
            </div>
          </div>
        </div>

        <div v-else style="text-align: center; padding: 2rem; color: var(--color-text-muted);">
          Sales records will populate top selling pieces automatically.
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
            <div style="font-size: 0.75rem; color: var(--color-text-muted);">Click invoice icon to print packing slip</div>
          </div>
          <NuxtLink to="/admin/orders" class="btn btn-outline btn-sm" style="font-size: 0.75rem;">
            View All Orders →
          </NuxtLink>
        </div>

        <table class="data-table" v-if="recentOrders.length > 0">
          <thead>
            <tr>
              <th>Order #</th>
              <th>Customer</th>
              <th>Total</th>
              <th>Status</th>
              <th style="text-align: right;">Invoice</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="ord in recentOrders" :key="ord.id">
              <td style="font-weight: 700; color: var(--color-primary);">
                {{ ord.order_number }}
              </td>
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
              <td style="text-align: right;">
                <button
                  @click="selectedInvoiceOrder = ord; isInvoiceModalOpen = true;"
                  class="btn btn-outline btn-sm"
                  style="font-size: 0.75rem; padding: 0.25rem 0.5rem;"
                  title="Print Tax Invoice / Dispatch Slip"
                >
                  <i class="fa-solid fa-file-invoice"></i>
                </button>
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
            <div style="font-size: 0.75rem; color: var(--color-text-muted);">Fabric swatches & bespoke requests</div>
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
              <th>WhatsApp</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="inq in recentInquiries" :key="inq.id">
              <td>
                <div style="font-weight: 700;">{{ inq.name }}</div>
                <div style="font-size: 0.75rem; color: var(--color-text-light);">{{ inq.phone }}</div>
              </td>
              <td>
                <div style="font-size: 0.85rem; font-weight: 600;">{{ inq.subject || 'Fabric Swatches' }}</div>
                <div style="font-size: 0.75rem; color: var(--color-text-muted); max-width: 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
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
                  title="WhatsApp Lead"
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
          <div style="font-size: 0.75rem; color: var(--color-text-muted);">Pieces requiring timber frame assembly restock</div>
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

    <!-- Printable Invoice Modal -->
    <InvoiceModal
      v-if="isInvoiceModalOpen && selectedInvoiceOrder"
      :order="selectedInvoiceOrder"
      @close="isInvoiceModalOpen = false"
    />
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'
import InvoiceModal from '~/components/InvoiceModal.vue'

definePageMeta({
  layout: 'admin',
})

const authStore = useAuthStore()
const config = useRuntimeConfig()
const apiBase = config.public.apiBase

const metrics = ref<any>({})
const dailyTrends = ref<any[]>([])
const topProducts = ref<any[]>([])
const recentOrders = ref<any[]>([])
const recentInquiries = ref<any[]>([])
const lowStockProducts = ref<any[]>([])
const loading = ref(false)

// Printable Invoice Modal State
const isInvoiceModalOpen = ref(false)
const selectedInvoiceOrder = ref<any>(null)

function getWhatsAppLink(phone: string) {
  const cleanPhone = (phone || '').replace(/[^0-9]/g, '')
  const formattedPhone = cleanPhone.startsWith('60') ? cleanPhone : cleanPhone.startsWith('0') ? '6' + cleanPhone : cleanPhone
  return `https://wa.me/${formattedPhone}`
}

function calculateBarHeight(revenue: number): number {
  const maxRevenue = Math.max(...dailyTrends.value.map(d => d.revenue), 1000)
  return Math.max(12, Math.round((revenue / maxRevenue) * 100))
}

async function loadDashboard() {
  if (!authStore.token) return
  loading.value = true
  try {
    const res: any = await $fetch(`${apiBase}/admin/dashboard`, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })

    metrics.value = res.metrics || {}
    dailyTrends.value = res.daily_trends || []
    topProducts.value = res.top_products || []
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

<style scoped>
.chart-container {
  display: flex;
  align-items: flex-end;
  height: 180px;
  gap: 0.6rem;
  padding-top: 1.5rem;
  border-bottom: 1px solid var(--color-border);
}

.chart-bar-wrap {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  height: 100%;
  justify-content: flex-end;
  position: relative;
  cursor: pointer;
}

.chart-bar {
  width: 100%;
  max-width: 28px;
  border-radius: 4px 4px 0 0;
  transition: all 0.3s ease;
}

.chart-bar-wrap:hover .chart-bar {
  background-color: var(--color-secondary-dark) !important;
}

.chart-bar-tooltip {
  display: none;
  position: absolute;
  top: -24px;
  background: #161719;
  color: #FFFFFF;
  font-size: 0.65rem;
  font-weight: 700;
  padding: 0.2rem 0.4rem;
  border-radius: 4px;
  white-space: nowrap;
  pointer-events: none;
}

.chart-bar-wrap:hover .chart-bar-tooltip {
  display: block;
}

.chart-bar-label {
  font-size: 0.68rem;
  color: var(--color-text-muted);
  margin-top: 0.4rem;
  white-space: nowrap;
}
</style>


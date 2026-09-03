<template>
  <div>
    <div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
      <div>
        <h1 style="font-size: 1.85rem; font-family: var(--font-sans); font-weight: 700; margin-bottom: 0.25rem;">
          Orders Management
        </h1>
        <p style="font-size: 0.9rem; color: var(--color-text-muted);">
          Track fulfillment status, inspect itemized customer orders, generate invoices, and dispatch delivery updates.
        </p>
      </div>

      <button @click="loadOrders" class="btn btn-outline btn-sm">
        <i class="fa-solid fa-rotate-right"></i> Refresh
      </button>
    </div>

    <!-- Filter & Search Bar -->
    <div style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 1.25rem; margin-bottom: 1.5rem; display: flex; gap: 1rem; align-items: center; justify-content: space-between; flex-wrap: wrap;">
      <div class="flex gap-2 flex-wrap">
        <button
          v-for="st in statusTabs"
          :key="st.value"
          @click="filterStatus(st.value)"
          class="btn btn-sm"
          :class="activeStatus === st.value ? 'btn-primary' : 'btn-outline'"
          style="text-transform: none;"
        >
          {{ st.label }}
        </button>
      </div>

      <div style="flex: 1 1 260px; position: relative; max-width: 360px;">
        <input
          v-model="searchQuery"
          @input="handleSearch"
          type="text"
          placeholder="Search order #, customer, phone..."
          class="form-input"
          style="padding-left: 2.25rem;"
        />
        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); color: var(--color-text-light);"></i>
      </div>
    </div>

    <!-- Orders Table -->
    <div class="data-table-card">
      <div v-if="loading" style="padding: 3rem; text-align: center; color: var(--color-text-muted);">
        <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 2rem; color: var(--color-secondary);"></i>
        <div style="margin-top: 0.75rem;">Loading orders...</div>
      </div>

      <table v-else-if="orders.length > 0" class="data-table">
        <thead>
          <tr>
            <th>Order #</th>
            <th>Customer</th>
            <th>Destination</th>
            <th>Items</th>
            <th>Total Amount</th>
            <th>Payment</th>
            <th>Fulfillment</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="ord in orders" :key="ord.id">
            <td>
              <div style="font-weight: 700; color: var(--color-primary); font-family: var(--font-sans);">
                {{ ord.order_number }}
              </div>
              <div style="font-size: 0.75rem; color: var(--color-text-light);">
                {{ new Date(ord.created_at).toLocaleDateString() }}
              </div>
            </td>
            <td>
              <div style="font-weight: 600;">{{ ord.customer_name }}</div>
              <a
                :href="getWhatsAppLink(ord)"
                target="_blank"
                style="font-size: 0.8rem; color: #25D366; font-weight: 600;"
                title="Direct WhatsApp"
              >
                <i class="fa-brands fa-whatsapp"></i> {{ ord.customer_phone }}
              </a>
            </td>
            <td>
              <div style="font-size: 0.85rem; font-weight: 500;">{{ ord.city }}, {{ ord.state }}</div>
              <div style="font-size: 0.75rem; color: var(--color-text-light);">{{ ord.postal_code }}</div>
            </td>
            <td>
              <div style="font-size: 0.85rem; font-weight: 600;">
                {{ ord.items?.length || 0 }} items
              </div>
              <div v-if="ord.items?.[0]" style="font-size: 0.75rem; color: var(--color-text-muted); max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                {{ ord.items[0].product_name }}
              </div>
            </td>
            <td style="font-weight: 700; color: var(--color-primary); font-family: var(--font-sans);">
              RM {{ Number(ord.total).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
            </td>
            <td>
              <span :class="['badge-status', ord.payment_status === 'paid' ? 'paid' : 'pending']">
                {{ ord.payment_status }}
              </span>
            </td>
            <td>
              <span :class="['badge-status', ord.order_status]">
                {{ ord.order_status }}
              </span>
            </td>
            <td>
              <div class="flex gap-2">
                <button
                  @click="openOrderDetail(ord)"
                  class="btn btn-outline btn-sm"
                  title="View Itemized Breakdown & Invoice"
                >
                  <i class="fa-solid fa-file-invoice"></i> Details
                </button>
                <button
                  @click="openStatusModal(ord)"
                  class="btn btn-outline btn-sm"
                  title="Update status"
                >
                  <i class="fa-solid fa-pen-to-square"></i>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-else style="padding: 3rem; text-align: center; color: var(--color-text-muted);">
        <i class="fa-solid fa-box-open" style="font-size: 2.5rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
        <p>No orders found matching this filter.</p>
      </div>

      <!-- Pagination -->
      <div
        v-if="pagination.total > pagination.per_page"
        style="padding: 1rem 1.5rem; border-top: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center;"
      >
        <div style="font-size: 0.85rem; color: var(--color-text-muted);">
          Showing {{ ((pagination.current_page - 1) * pagination.per_page) + 1 }} to
          {{ Math.min(pagination.current_page * pagination.per_page, pagination.total) }} of {{ pagination.total }} orders
        </div>
        <div class="flex gap-2">
          <button
            :disabled="pagination.current_page <= 1"
            @click="changePage(pagination.current_page - 1)"
            class="btn btn-outline btn-sm"
          >
            Previous
          </button>
          <button
            :disabled="pagination.current_page >= pagination.last_page"
            @click="changePage(pagination.current_page + 1)"
            class="btn btn-outline btn-sm"
          >
            Next
          </button>
        </div>
      </div>
    </div>

    <!-- Order Detail & Invoice Modal / Drawer -->
    <div
      v-if="viewingOrder"
      style="position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 500; display: flex; align-items: center; justify-content: center; padding: 1.5rem;"
      @click.self="viewingOrder = null"
    >
      <div style="background: #FFFFFF; width: 100%; max-width: 720px; border-radius: var(--radius-sm); padding: 2rem; max-height: 90vh; overflow-y: auto;">
        <!-- Modal Top Bar -->
        <div class="flex justify-between items-center" style="border-bottom: 1px solid var(--color-border); padding-bottom: 1rem; margin-bottom: 1.5rem;">
          <div class="flex items-center gap-3">
            <h3 style="font-size: 1.35rem; font-family: var(--font-sans); font-weight: 700; margin-bottom: 0;">
              Order #{{ viewingOrder.order_number }}
            </h3>
            <span :class="['badge-status', viewingOrder.order_status]">
              {{ viewingOrder.order_status }}
            </span>
          </div>
          <div class="flex items-center gap-2">
            <button @click="printInvoice" class="btn btn-outline btn-sm">
              <i class="fa-solid fa-print"></i> Print Invoice
            </button>
            <button
              @click="viewingOrder = null"
              style="background: transparent; border: none; font-size: 1.25rem; color: var(--color-text-light); cursor: pointer;"
            >
              <i class="fa-solid fa-xmark"></i>
            </button>
          </div>
        </div>

        <!-- Printable Invoice Container -->
        <div id="printable-invoice">
          <!-- Invoice Header -->
          <div class="flex justify-between items-start" style="margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--color-border);">
            <div>
              <div style="font-family: var(--font-heading); font-size: 1.35rem; font-weight: 700; color: var(--color-primary);">
                NMFFURNITURE SDN BHD
              </div>
              <div style="font-size: 0.8rem; color: var(--color-text-muted);">
                UG41 Komersial Radia, Bukit Jelutong, 40150 Shah Alam, Selangor<br />
                SSM Registration: 1400875-P · Phone: +603 7832 9920
              </div>
            </div>
            <div style="text-align: right;">
              <div style="font-size: 1.1rem; font-weight: 700; color: var(--color-secondary-dark);">
                OFFICIAL TAX INVOICE
              </div>
              <div style="font-size: 0.85rem; font-weight: 600;">#{{ viewingOrder.order_number }}</div>
              <div style="font-size: 0.8rem; color: var(--color-text-muted);">
                Date: {{ new Date(viewingOrder.created_at).toLocaleDateString() }}
              </div>
            </div>
          </div>

          <!-- Customer & Delivery Address Grid -->
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; background: var(--color-bg-alt); padding: 1.25rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem;">
            <div>
              <div style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--color-text-light); margin-bottom: 0.35rem;">
                Deliver To (Customer):
              </div>
              <div style="font-weight: 700; font-size: 0.95rem;">{{ viewingOrder.customer_name }}</div>
              <div style="font-size: 0.85rem; color: var(--color-text-muted);">{{ viewingOrder.customer_phone }}</div>
              <div style="font-size: 0.85rem; color: var(--color-text-muted);">{{ viewingOrder.customer_email }}</div>
            </div>
            <div>
              <div style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--color-text-light); margin-bottom: 0.35rem;">
                Shipping Address:
              </div>
              <div style="font-size: 0.9rem; line-height: 1.5;">
                {{ viewingOrder.shipping_address }}<br />
                {{ viewingOrder.city }}, {{ viewingOrder.state }} {{ viewingOrder.postal_code }}<br />
                {{ viewingOrder.country || 'Malaysia' }}
              </div>
            </div>
          </div>

          <!-- Order Items Table -->
          <table class="data-table" style="margin-bottom: 1.5rem;">
            <thead>
              <tr>
                <th>Item & Specifications</th>
                <th>Option / Fabric</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Unit Price</th>
                <th style="text-align: right;">Total</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="it in viewingOrder.items" :key="it.id">
                <td>
                  <div style="font-weight: 600;">{{ it.product_name }}</div>
                  <div v-if="it.product_sku" style="font-size: 0.75rem; color: var(--color-text-light); font-family: monospace;">
                    SKU: {{ it.product_sku }}
                  </div>
                </td>
                <td style="font-size: 0.85rem; color: var(--color-secondary-dark); font-weight: 600;">
                  {{ it.variant_option || 'Standard Model' }}
                </td>
                <td style="text-align: center; font-weight: 600;">{{ it.quantity }}</td>
                <td style="text-align: right;">RM {{ Number(it.price).toFixed(2) }}</td>
                <td style="text-align: right; font-weight: 700;">
                  RM {{ Number(it.total).toFixed(2) }}
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Financial Summary -->
          <div style="display: flex; justify-content: flex-end; margin-bottom: 1.5rem;">
            <div style="width: 280px; font-size: 0.9rem;">
              <div class="flex justify-between" style="padding: 0.4rem 0;">
                <span style="color: var(--color-text-muted);">Subtotal:</span>
                <span style="font-weight: 600;">RM {{ Number(viewingOrder.subtotal).toFixed(2) }}</span>
              </div>
              <div class="flex justify-between" style="padding: 0.4rem 0;">
                <span style="color: var(--color-text-muted);">Peninsular Delivery:</span>
                <span style="font-weight: 600;">
                  {{ Number(viewingOrder.shipping_fee) === 0 ? 'FREE (Orders RM 3,000+)' : `RM ${Number(viewingOrder.shipping_fee).toFixed(2)}` }}
                </span>
              </div>
              <div class="flex justify-between" style="padding: 0.6rem 0; border-top: 1px solid var(--color-border); font-size: 1.15rem; font-weight: 700; color: var(--color-primary);">
                <span>Total Amount:</span>
                <span>RM {{ Number(viewingOrder.total).toFixed(2) }}</span>
              </div>
              <div class="flex justify-between" style="font-size: 0.8rem; color: var(--color-text-light);">
                <span>Payment Status:</span>
                <span style="text-transform: uppercase; font-weight: 700; color: #2E7D32;">{{ viewingOrder.payment_status }}</span>
              </div>
            </div>
          </div>

          <!-- Customer Order Notes -->
          <div v-if="viewingOrder.notes" style="background: #FFF9E6; border: 1px solid #FFE082; padding: 1rem; border-radius: var(--radius-sm); font-size: 0.85rem; margin-bottom: 1rem;">
            <strong>Customer Notes:</strong> {{ viewingOrder.notes }}
          </div>
        </div>

        <!-- Action Footer (Screen only) -->
        <div class="no-print flex justify-between items-center" style="border-top: 1px solid var(--color-border); padding-top: 1.25rem;">
          <a
            :href="getWhatsAppStatusUpdateLink(viewingOrder)"
            target="_blank"
            class="btn btn-sm"
            style="background: #25D366; color: #FFFFFF; border: none; font-weight: 600;"
          >
            <i class="fa-brands fa-whatsapp"></i> WhatsApp Status Update
          </a>

          <div class="flex gap-2">
            <button @click="openStatusModal(viewingOrder)" class="btn btn-primary btn-sm">
              Change Status
            </button>
            <button @click="viewingOrder = null" class="btn btn-outline btn-sm">
              Close
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Status Update Modal -->
    <div
      v-if="selectedOrder"
      style="position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 600; display: flex; align-items: center; justify-content: center; padding: 2rem;"
      @click.self="selectedOrder = null"
    >
      <div style="background: #FFFFFF; width: 100%; max-width: 480px; border-radius: var(--radius-sm); padding: 2rem;">
        <h3 style="font-size: 1.35rem; margin-bottom: 1rem;">
          Update Order #{{ selectedOrder.order_number }}
        </h3>

        <div class="form-group">
          <label class="form-label">Fulfillment Status</label>
          <select v-model="statusForm.order_status" class="form-select">
            <option value="pending">Pending (Awaiting Verification)</option>
            <option value="processing">Processing (In Assembly / Production)</option>
            <option value="shipped">Shipped (Out for Delivery)</option>
            <option value="delivered">Delivered (Completed)</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>

        <div class="form-group" style="margin-top: 1rem;">
          <label class="form-label">Payment Status</label>
          <select v-model="statusForm.payment_status" class="form-select">
            <option value="pending">Pending Verification</option>
            <option value="paid">Paid</option>
            <option value="failed">Failed / Refunded</option>
          </select>
        </div>

        <div class="flex justify-end gap-3" style="margin-top: 1.5rem;">
          <button type="button" @click="selectedOrder = null" class="btn btn-outline">
            Cancel
          </button>
          <button type="button" @click="saveStatus" class="btn btn-primary">
            Save Status
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'
import { useToastStore } from '~/stores/toast'

definePageMeta({
  layout: 'admin',
})

const authStore = useAuthStore()
const toast = useToastStore()
const config = useRuntimeConfig()
const apiBase = config.public.apiBase

const orders = ref<any[]>([])
const loading = ref(false)
const activeStatus = ref('all')
const searchQuery = ref('')
const selectedOrder = ref<any>(null)
const viewingOrder = ref<any>(null)

const statusForm = ref({
  order_status: 'pending',
  payment_status: 'pending',
})

const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 20,
  total: 0,
})

const statusTabs = [
  { label: 'All Orders', value: 'all' },
  { label: 'Pending', value: 'pending' },
  { label: 'Processing', value: 'processing' },
  { label: 'Shipped', value: 'shipped' },
  { label: 'Delivered', value: 'delivered' },
  { label: 'Cancelled', value: 'cancelled' },
]

let searchTimer: any = null
function handleSearch() {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => {
    pagination.value.current_page = 1
    loadOrders()
  }, 350)
}

function filterStatus(status: string) {
  activeStatus.value = status
  pagination.value.current_page = 1
  loadOrders()
}

function changePage(page: number) {
  pagination.value.current_page = page
  loadOrders()
}

function printInvoice() {
  window.print()
}

function getWhatsAppLink(ord: any) {
  const cleanPhone = (ord.customer_phone || '').replace(/[^0-9]/g, '')
  const formattedPhone = cleanPhone.startsWith('60') ? cleanPhone : cleanPhone.startsWith('0') ? '6' + cleanPhone : cleanPhone
  return `https://wa.me/${formattedPhone}`
}

function getWhatsAppStatusUpdateLink(ord: any) {
  const cleanPhone = (ord.customer_phone || '').replace(/[^0-9]/g, '')
  const formattedPhone = cleanPhone.startsWith('60') ? cleanPhone : cleanPhone.startsWith('0') ? '6' + cleanPhone : cleanPhone
  const msg = encodeURIComponent(`Hi ${ord.customer_name}, your NMFFurniture order #${ord.order_number} status has been updated to: ${ord.order_status.toUpperCase()}. Track your delivery anytime at: http://localhost:3000/orders/track/${ord.order_number}`)
  return `https://wa.me/${formattedPhone}?text=${msg}`
}

function openOrderDetail(ord: any) {
  viewingOrder.value = ord
}

function openStatusModal(ord: any) {
  selectedOrder.value = ord
  statusForm.value = {
    order_status: ord.order_status,
    payment_status: ord.payment_status,
  }
}

async function loadOrders() {
  if (!authStore.token) return
  loading.value = true
  try {
    const params: any = {
      page: pagination.value.current_page,
    }
    if (activeStatus.value !== 'all') {
      params.status = activeStatus.value
    }
    if (searchQuery.value) {
      params.search = searchQuery.value
    }

    const res: any = await $fetch(`${apiBase}/admin/orders`, {
      headers: { Authorization: `Bearer ${authStore.token}` },
      params,
    })

    orders.value = res.data || []
    pagination.value.current_page = res.current_page || 1
    pagination.value.last_page = res.last_page || 1
    pagination.value.per_page = res.per_page || 20
    pagination.value.total = res.total || 0
  } catch (e: any) {
    toast.show('Failed to load orders', 'error')
  } finally {
    loading.value = false
  }
}

async function saveStatus() {
  if (!selectedOrder.value || !authStore.token) return
  try {
    const res: any = await $fetch(`${apiBase}/admin/orders/${selectedOrder.value.id}/status`, {
      method: 'PATCH',
      headers: { Authorization: `Bearer ${authStore.token}` },
      body: statusForm.value,
    })

    toast.show('Order status updated successfully', 'success')
    selectedOrder.value = null
    if (viewingOrder.value) {
      viewingOrder.value.order_status = statusForm.value.order_status
      viewingOrder.value.payment_status = statusForm.value.payment_status
    }
    loadOrders()
  } catch (e: any) {
    toast.show('Failed to update status', 'error')
  }
}

onMounted(() => {
  loadOrders()
})
</script>

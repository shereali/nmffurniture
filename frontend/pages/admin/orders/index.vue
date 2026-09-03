<template>
  <div>
    <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
      <div>
        <h1 style="font-size: 1.85rem; font-family: var(--font-sans); font-weight: 700; margin-bottom: 0.25rem;">
          Orders Management
        </h1>
        <p style="font-size: 0.9rem; color: var(--color-text-muted);">Track customer orders, update fulfillment workflow, and manage invoices.</p>
      </div>
    </div>

    <!-- Orders Filter Tabs -->
    <div class="flex gap-2 flex-wrap" style="margin-bottom: 1.5rem;">
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

    <!-- Orders Table -->
    <div class="data-table-card">
      <table class="data-table">
        <thead>
          <tr>
            <th>Order Number</th>
            <th>Customer & Delivery</th>
            <th>Items Ordered</th>
            <th>Total Amount</th>
            <th>Payment</th>
            <th>Order Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="ord in orders" :key="ord.id">
            <td style="font-weight: 700; color: var(--color-primary);">
              {{ ord.order_number }}
              <div style="font-size: 0.75rem; color: var(--color-text-light); font-weight: normal;">
                {{ new Date(ord.created_at).toLocaleDateString() }}
              </div>
            </td>
            <td>
              <div style="font-weight: 600;">{{ ord.customer_name }}</div>
              <div style="font-size: 0.8rem; color: var(--color-text-muted);">{{ ord.customer_phone }}</div>
              <div style="font-size: 0.75rem; color: var(--color-text-light);">{{ ord.city }}, {{ ord.state }}</div>
            </td>
            <td>
              <div v-for="it in ord.items" :key="it.id" style="font-size: 0.85rem;">
                • {{ it.product_name }} (x{{ it.quantity }})
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
              <button @click="openStatusModal(ord)" class="btn btn-outline btn-sm">
                Update Status
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Status Update Modal -->
    <div
      v-if="selectedOrder"
      style="position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 300; display: flex; align-items: center; justify-content: center; padding: 2rem;"
      @click.self="selectedOrder = null"
    >
      <div style="background: #FFFFFF; width: 100%; max-width: 480px; border-radius: var(--radius-sm); padding: 2rem;">
        <h3 style="font-size: 1.35rem; margin-bottom: 1rem;">
          Update {{ selectedOrder.order_number }}
        </h3>

        <div class="form-group">
          <label class="form-label">Fulfillment Status</label>
          <select v-model="statusForm.order_status" class="form-select">
            <option value="pending">Pending</option>
            <option value="processing">Processing (In Assembly)</option>
            <option value="shipped">Shipped (Out for Delivery)</option>
            <option value="delivered">Delivered</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Payment Status</label>
          <select v-model="statusForm.payment_status" class="form-select">
            <option value="pending">Pending Payment</option>
            <option value="paid">Paid</option>
            <option value="failed">Failed</option>
          </select>
        </div>

        <div class="flex justify-end gap-3" style="margin-top: 1.5rem;">
          <button @click="selectedOrder = null" class="btn btn-outline">Cancel</button>
          <button @click="saveOrderStatus" class="btn btn-primary">Save Changes</button>
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
const activeStatus = ref('')
const selectedOrder = ref<any>(null)

const statusForm = reactive({
  order_status: 'pending',
  payment_status: 'pending',
})

const statusTabs = [
  { label: 'All Orders', value: '' },
  { label: 'Pending', value: 'pending' },
  { label: 'Processing', value: 'processing' },
  { label: 'Shipped', value: 'shipped' },
  { label: 'Delivered', value: 'delivered' },
]

async function loadOrders() {
  if (!authStore.token) return
  try {
    const url = activeStatus.value
      ? `${apiBase}/admin/orders?status=${activeStatus.value}`
      : `${apiBase}/admin/orders`
    const res: any = await $fetch(url, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    orders.value = res.data || []
  } catch (e) {
    console.error('Failed to load orders', e)
  }
}

function filterStatus(st: string) {
  activeStatus.value = st
  loadOrders()
}

function openStatusModal(ord: any) {
  selectedOrder.value = ord
  statusForm.order_status = ord.order_status
  statusForm.payment_status = ord.payment_status
}

async function saveOrderStatus() {
  if (!selectedOrder.value) return
  try {
    await $fetch(`${apiBase}/admin/orders/${selectedOrder.value.id}/status`, {
      method: 'PATCH',
      body: statusForm,
      headers: { Authorization: `Bearer ${authStore.token}` },
    })
    toast.show('Order status updated successfully!', 'success')
    selectedOrder.value = null
    loadOrders()
  } catch (e) {
    toast.show('Failed to update status', 'error')
  }
}

onMounted(() => {
  loadOrders()
})
</script>

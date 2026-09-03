<template>
  <div>
    <div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
      <div>
        <h1 style="font-size: 1.85rem; font-family: var(--font-sans); font-weight: 700; margin-bottom: 0.25rem;">
          Customer Directory
        </h1>
        <p style="font-size: 0.9rem; color: var(--color-text-muted);">
          Registered client accounts, order statistics, contact information, and address book.
        </p>
      </div>

      <button @click="loadCustomers" class="btn btn-outline btn-sm">
        <i class="fa-solid fa-rotate-right"></i> Refresh
      </button>
    </div>

    <!-- Search Bar -->
    <div style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 1.25rem; margin-bottom: 1.5rem; display: flex; gap: 1rem; align-items: center; justify-content: space-between; flex-wrap: wrap;">
      <div style="position: relative; max-width: 380px; width: 100%;">
        <input
          v-model="searchQuery"
          @input="handleSearch"
          type="text"
          placeholder="Search customer name, email, phone..."
          class="form-input"
          style="padding-left: 2.25rem;"
        />
        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); color: var(--color-text-light);"></i>
      </div>

      <div style="font-size: 0.85rem; color: var(--color-text-muted);">
        Total registered customers: <strong>{{ pagination.total }}</strong>
      </div>
    </div>

    <!-- Customers Table -->
    <div class="data-table-card">
      <div v-if="loading" style="padding: 3rem; text-align: center; color: var(--color-text-muted);">
        <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 2rem; color: var(--color-secondary);"></i>
        <div style="margin-top: 0.75rem;">Loading customers...</div>
      </div>

      <table v-else-if="customers.length > 0" class="data-table">
        <thead>
          <tr>
            <th>Customer Name</th>
            <th>Email Address</th>
            <th>Phone / WhatsApp</th>
            <th>Location</th>
            <th>Total Orders</th>
            <th>Registered</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="c in customers" :key="c.id">
            <td>
              <div style="font-weight: 700; color: var(--color-primary);">{{ c.name }}</div>
              <div v-if="c.address" style="font-size: 0.75rem; color: var(--color-text-light); max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                {{ c.address }}
              </div>
            </td>
            <td>
              <a :href="`mailto:${c.email}`" style="color: inherit; text-decoration: underline;">
                {{ c.email }}
              </a>
            </td>
            <td>
              <a
                v-if="c.phone"
                :href="getWhatsAppLink(c.phone)"
                target="_blank"
                class="btn btn-sm"
                style="background: #25D366; color: #FFFFFF; border: none; font-weight: 600; font-size: 0.8rem; padding: 0.25rem 0.6rem;"
                title="Direct WhatsApp"
              >
                <i class="fa-brands fa-whatsapp"></i> {{ c.phone }}
              </a>
              <span v-else style="color: var(--color-text-light); font-size: 0.8rem;">None</span>
            </td>
            <td>
              <span v-if="c.city">{{ c.city }}, {{ c.state }}</span>
              <span v-else style="color: var(--color-text-light);">-</span>
            </td>
            <td style="font-weight: 700;">
              <span style="background: var(--color-secondary-light); color: var(--color-secondary-dark); padding: 0.2rem 0.55rem; border-radius: var(--radius-sm); font-size: 0.8rem;">
                {{ c.orders_count || 0 }} orders
              </span>
            </td>
            <td style="font-size: 0.85rem; color: var(--color-text-muted);">
              {{ new Date(c.created_at).toLocaleDateString() }}
            </td>
            <td>
              <button @click="openCustomerDetail(c)" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-user"></i> Profile
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-else style="padding: 3rem; text-align: center; color: var(--color-text-muted);">
        <p>No customers found matching this query.</p>
      </div>

      <!-- Pagination -->
      <div
        v-if="pagination.total > pagination.per_page"
        style="padding: 1rem 1.5rem; border-top: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center;"
      >
        <div style="font-size: 0.85rem; color: var(--color-text-muted);">
          Showing {{ ((pagination.current_page - 1) * pagination.per_page) + 1 }} to
          {{ Math.min(pagination.current_page * pagination.per_page, pagination.total) }} of {{ pagination.total }} clients
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

    <!-- Customer Profile Modal -->
    <div
      v-if="selectedCustomer"
      style="position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 500; display: flex; align-items: center; justify-content: center; padding: 1.5rem;"
      @click.self="selectedCustomer = null"
    >
      <div style="background: #FFFFFF; width: 100%; max-width: 520px; border-radius: var(--radius-sm); padding: 2rem;">
        <div class="flex justify-between items-center" style="margin-bottom: 1.25rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem;">
          <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 0;">
            Customer Profile
          </h3>
          <button
            @click="selectedCustomer = null"
            style="background: transparent; border: none; font-size: 1.25rem; color: var(--color-text-light); cursor: pointer;"
          >
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>

        <div style="background: var(--color-bg-alt); padding: 1.25rem; border-radius: var(--radius-sm); margin-bottom: 1.25rem;">
          <h4 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 0.25rem;">
            {{ selectedCustomer.name }}
          </h4>
          <div style="font-size: 0.85rem; color: var(--color-text-muted);">
            Client ID #{{ selectedCustomer.id }} · Registered {{ new Date(selectedCustomer.created_at).toLocaleDateString() }}
          </div>
        </div>

        <div class="flex flex-col gap-2" style="font-size: 0.9rem; margin-bottom: 1.5rem;">
          <div><strong>Email:</strong> {{ selectedCustomer.email }}</div>
          <div><strong>Phone:</strong> {{ selectedCustomer.phone || 'Not specified' }}</div>
          <div>
            <strong>Delivery Address:</strong><br />
            <span v-if="selectedCustomer.address">
              {{ selectedCustomer.address }}<br />
              {{ selectedCustomer.city }}, {{ selectedCustomer.state }} {{ selectedCustomer.postal_code }}
            </span>
            <span v-else style="color: var(--color-text-light);">No saved address</span>
          </div>
          <div><strong>Total Orders Placed:</strong> {{ selectedCustomer.orders_count || 0 }} orders</div>
        </div>

        <div class="flex justify-between items-center" style="border-top: 1px solid var(--color-border); padding-top: 1rem;">
          <a
            v-if="selectedCustomer.phone"
            :href="getWhatsAppLink(selectedCustomer.phone)"
            target="_blank"
            class="btn btn-sm"
            style="background: #25D366; color: #FFFFFF; border: none;"
          >
            <i class="fa-brands fa-whatsapp"></i> Chat on WhatsApp
          </a>
          <span v-else></span>

          <button @click="selectedCustomer = null" class="btn btn-primary btn-sm">
            Close
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

const customers = ref<any[]>([])
const loading = ref(false)
const searchQuery = ref('')
const selectedCustomer = ref<any>(null)

const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 20,
  total: 0,
})

let searchTimer: any = null
function handleSearch() {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => {
    pagination.value.current_page = 1
    loadCustomers()
  }, 350)
}

function changePage(page: number) {
  pagination.value.current_page = page
  loadCustomers()
}

function getWhatsAppLink(phone: string) {
  const cleanPhone = (phone || '').replace(/[^0-9]/g, '')
  const formattedPhone = cleanPhone.startsWith('60') ? cleanPhone : cleanPhone.startsWith('0') ? '6' + cleanPhone : cleanPhone
  return `https://wa.me/${formattedPhone}`
}

function openCustomerDetail(c: any) {
  selectedCustomer.value = c
}

async function loadCustomers() {
  if (!authStore.token) return
  loading.value = true
  try {
    const params: any = {
      page: pagination.value.current_page,
    }
    if (searchQuery.value) {
      params.search = searchQuery.value
    }

    const res: any = await $fetch(`${apiBase}/admin/customers`, {
      headers: { Authorization: `Bearer ${authStore.token}` },
      params,
    })

    customers.value = res.data || []
    pagination.value.current_page = res.current_page || 1
    pagination.value.last_page = res.last_page || 1
    pagination.value.per_page = res.per_page || 20
    pagination.value.total = res.total || 0
  } catch (e: any) {
    toast.show('Failed to load customers', 'error')
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadCustomers()
})
</script>

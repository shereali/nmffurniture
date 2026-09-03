<template>
  <div>
    <div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
      <div>
        <h1 style="font-size: 1.85rem; font-family: var(--font-sans); font-weight: 700; margin-bottom: 0.25rem;">
          Inquiries & Leads Management
        </h1>
        <p style="font-size: 0.9rem; color: var(--color-text-muted);">
          Respond to custom sofa requests, material consultations, and showroom booking requests.
        </p>
      </div>

      <div class="flex gap-2">
        <button @click="loadInquiries" class="btn btn-outline btn-sm">
          <i class="fa-solid fa-rotate-right"></i> Refresh
        </button>
      </div>
    </div>

    <!-- Filter Tabs & Search Bar -->
    <div style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 1.25rem; margin-bottom: 1.5rem; display: flex; gap: 1rem; align-items: center; justify-content: space-between; flex-wrap: wrap;">
      <div class="flex gap-2 flex-wrap">
        <button
          v-for="tab in statusTabs"
          :key="tab.value"
          @click="setStatusFilter(tab.value)"
          class="btn btn-sm"
          :class="activeStatus === tab.value ? 'btn-primary' : 'btn-outline'"
        >
          {{ tab.label }}
          <span
            v-if="counts[tab.countKey] !== undefined"
            style="margin-left: 0.4rem; padding: 0.1rem 0.45rem; border-radius: 999px; font-size: 0.75rem; background: rgba(0,0,0,0.08);"
          >
            {{ counts[tab.countKey] }}
          </span>
        </button>
      </div>

      <div style="flex: 1 1 260px; position: relative; max-width: 380px;">
        <input
          v-model="searchQuery"
          @input="handleSearch"
          type="text"
          placeholder="Search name, phone, email, subject..."
          class="form-input"
          style="padding-left: 2.25rem;"
        />
        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); color: var(--color-text-light);"></i>
      </div>
    </div>

    <!-- Inquiries Table -->
    <div class="data-table-card">
      <div v-if="loading" style="padding: 3rem; text-align: center; color: var(--color-text-muted);">
        <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 2rem; color: var(--color-secondary);"></i>
        <div style="margin-top: 0.75rem;">Loading leads...</div>
      </div>

      <table v-else-if="inquiries.length > 0" class="data-table">
        <thead>
          <tr>
            <th>Lead Details</th>
            <th>Contact</th>
            <th>Subject / Product</th>
            <th>Message Snippet</th>
            <th>Status</th>
            <th>Received</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="inq in inquiries" :key="inq.id">
            <td>
              <div style="font-weight: 700; color: var(--color-primary);">{{ inq.name }}</div>
              <div v-if="inq.email" style="font-size: 0.8rem; color: var(--color-text-muted);">
                {{ inq.email }}
              </div>
            </td>
            <td>
              <div class="flex items-center gap-2">
                <a
                  :href="getWhatsAppLink(inq)"
                  target="_blank"
                  class="btn btn-sm"
                  style="background: #25D366; color: #FFFFFF; border: none; padding: 0.3rem 0.6rem; font-size: 0.8rem; font-weight: 600;"
                  title="Chat on WhatsApp"
                >
                  <i class="fa-brands fa-whatsapp"></i> {{ inq.phone }}
                </a>
              </div>
            </td>
            <td>
              <div style="font-weight: 600;">{{ inq.subject || 'General Inquiry' }}</div>
              <div v-if="inq.product" style="font-size: 0.75rem; color: var(--color-secondary-dark); font-weight: 600;">
                <i class="fa-solid fa-couch"></i> {{ inq.product.name }}
              </div>
            </td>
            <td style="max-width: 280px;">
              <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 0.85rem; color: var(--color-text-muted);">
                {{ inq.message }}
              </div>
            </td>
            <td>
              <span :class="['badge-status', inq.status]">
                {{ inq.status }}
              </span>
            </td>
            <td style="font-size: 0.8rem; color: var(--color-text-light); white-space: nowrap;">
              {{ new Date(inq.created_at).toLocaleDateString() }}
              <div style="font-size: 0.7rem;">{{ new Date(inq.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) }}</div>
            </td>
            <td>
              <div class="flex gap-2">
                <button
                  @click="openDetail(inq)"
                  class="btn btn-outline btn-sm"
                  title="View full message & reply"
                >
                  <i class="fa-solid fa-eye"></i> View
                </button>
                <button
                  @click="deleteInquiry(inq.id)"
                  class="btn btn-outline btn-sm"
                  style="color: var(--color-danger); border-color: var(--color-danger);"
                  title="Delete lead"
                >
                  <i class="fa-regular fa-trash-can"></i>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-else style="padding: 3rem; text-align: center; color: var(--color-text-muted);">
        <i class="fa-regular fa-envelope" style="font-size: 2.5rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
        <p>No customer inquiries found in this view.</p>
      </div>

      <!-- Pagination -->
      <div
        v-if="pagination.total > pagination.per_page"
        style="padding: 1rem 1.5rem; border-top: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center;"
      >
        <div style="font-size: 0.85rem; color: var(--color-text-muted);">
          Showing {{ ((pagination.current_page - 1) * pagination.per_page) + 1 }} to
          {{ Math.min(pagination.current_page * pagination.per_page, pagination.total) }} of {{ pagination.total }} entries
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

    <!-- Inquiry Detail Modal -->
    <div
      v-if="selectedInquiry"
      style="position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 500; display: flex; align-items: center; justify-content: center; padding: 1.5rem;"
      @click.self="selectedInquiry = null"
    >
      <div style="background: #FFFFFF; width: 100%; max-width: 600px; border-radius: var(--radius-sm); padding: 2rem; max-height: 90vh; overflow-y: auto;">
        <div class="flex justify-between items-start" style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--color-border); padding-bottom: 1rem;">
          <div>
            <h3 style="font-size: 1.35rem; margin-bottom: 0.25rem;">
              {{ selectedInquiry.subject || 'Customer Inquiry' }}
            </h3>
            <div style="font-size: 0.8rem; color: var(--color-text-muted);">
              Received {{ new Date(selectedInquiry.created_at).toLocaleString() }}
            </div>
          </div>
          <button
            @click="selectedInquiry = null"
            style="background: transparent; border: none; font-size: 1.25rem; color: var(--color-text-light); cursor: pointer;"
          >
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>

        <!-- Customer Summary Card -->
        <div style="background: var(--color-bg-alt); border-radius: var(--radius-sm); padding: 1.25rem; margin-bottom: 1.5rem;">
          <div style="font-weight: 700; font-size: 1.05rem; margin-bottom: 0.5rem; color: var(--color-primary);">
            {{ selectedInquiry.name }}
          </div>
          <div class="flex flex-col gap-1" style="font-size: 0.9rem;">
            <div><strong>Phone:</strong> {{ selectedInquiry.phone }}</div>
            <div v-if="selectedInquiry.email"><strong>Email:</strong> {{ selectedInquiry.email }}</div>
            <div v-if="selectedInquiry.product">
              <strong>Inquired Product:</strong> {{ selectedInquiry.product.name }} (SKU: {{ selectedInquiry.product.sku }})
            </div>
          </div>
        </div>

        <!-- Full Message -->
        <div style="margin-bottom: 1.5rem;">
          <label style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: var(--color-text-light); display: block; margin-bottom: 0.5rem;">
            Customer Message:
          </label>
          <div style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 1rem; font-size: 0.95rem; line-height: 1.6; white-space: pre-wrap;">
            {{ selectedInquiry.message }}
          </div>
        </div>

        <!-- Quick Reply Actions -->
        <div style="margin-bottom: 1.5rem;">
          <label style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: var(--color-text-light); display: block; margin-bottom: 0.5rem;">
            Direct Customer Reply:
          </label>
          <div class="flex gap-3 flex-wrap">
            <a
              :href="getWhatsAppLink(selectedInquiry)"
              target="_blank"
              class="btn btn-sm"
              style="background: #25D366; color: #FFFFFF; border: none; padding: 0.5rem 1rem;"
            >
              <i class="fa-brands fa-whatsapp"></i> Chat on WhatsApp
            </a>
            <a
              v-if="selectedInquiry.email"
              :href="`mailto:${selectedInquiry.email}?subject=Re: ${encodeURIComponent(selectedInquiry.subject || 'NMFFurniture Inquiry')}`"
              class="btn btn-outline btn-sm"
            >
              <i class="fa-regular fa-envelope"></i> Send Email
            </a>
          </div>
        </div>

        <!-- Status Selector -->
        <div class="form-group" style="margin-bottom: 1.5rem;">
          <label class="form-label">Update Lead Status</label>
          <select v-model="editStatus" class="form-select">
            <option value="new">New (Uncontacted)</option>
            <option value="contacted">Contacted (In Discussion)</option>
            <option value="closed">Closed (Resolved / Converted)</option>
          </select>
        </div>

        <div class="flex justify-end gap-3">
          <button type="button" @click="selectedInquiry = null" class="btn btn-outline">
            Cancel
          </button>
          <button type="button" @click="saveStatus" class="btn btn-primary">
            Save Changes
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

const inquiries = ref<any[]>([])
const loading = ref(false)
const activeStatus = ref('all')
const searchQuery = ref('')
const selectedInquiry = ref<any>(null)
const editStatus = ref('new')

const counts = ref({
  all: 0,
  new: 0,
  contacted: 0,
  closed: 0,
})

const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 20,
  total: 0,
})

const statusTabs = [
  { label: 'All Inquiries', value: 'all', countKey: 'all' as const },
  { label: 'New Leads', value: 'new', countKey: 'new' as const },
  { label: 'Contacted', value: 'contacted', countKey: 'contacted' as const },
  { label: 'Closed', value: 'closed', countKey: 'closed' as const },
]

let searchTimer: any = null
function handleSearch() {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => {
    pagination.value.current_page = 1
    loadInquiries()
  }, 350)
}

function setStatusFilter(status: string) {
  activeStatus.value = status
  pagination.value.current_page = 1
  loadInquiries()
}

function changePage(page: number) {
  pagination.value.current_page = page
  loadInquiries()
}

function getWhatsAppLink(inq: any) {
  const cleanPhone = (inq.phone || '').replace(/[^0-9]/g, '')
  const formattedPhone = cleanPhone.startsWith('60') ? cleanPhone : cleanPhone.startsWith('0') ? '6' + cleanPhone : cleanPhone
  const productName = inq.product ? ` regarding ${inq.product.name}` : ''
  const text = encodeURIComponent(`Hi ${inq.name}, thank you for contacting NMFFurniture${productName}. How may we assist you with your living space today?`)
  return `https://wa.me/${formattedPhone}?text=${text}`
}

function openDetail(inq: any) {
  selectedInquiry.value = inq
  editStatus.value = inq.status
}

async function loadInquiries() {
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

    const res: any = await $fetch(`${apiBase}/admin/inquiries`, {
      headers: { Authorization: `Bearer ${authStore.token}` },
      params,
    })

    inquiries.value = res.inquiries?.data || []
    pagination.value.current_page = res.inquiries?.current_page || 1
    pagination.value.last_page = res.inquiries?.last_page || 1
    pagination.value.per_page = res.inquiries?.per_page || 20
    pagination.value.total = res.inquiries?.total || 0

    if (res.counts) {
      counts.value = res.counts
    }
  } catch (e: any) {
    toast.show('Failed to load inquiries', 'error')
  } finally {
    loading.value = false
  }
}

async function saveStatus() {
  if (!selectedInquiry.value || !authStore.token) return
  try {
    const res: any = await $fetch(`${apiBase}/admin/inquiries/${selectedInquiry.value.id}/status`, {
      method: 'PATCH',
      headers: { Authorization: `Bearer ${authStore.token}` },
      body: { status: editStatus.value },
    })
    toast.show(res.message || 'Status updated', 'success')
    selectedInquiry.value = null
    loadInquiries()
  } catch (e: any) {
    toast.show('Failed to update inquiry status', 'error')
  }
}

async function deleteInquiry(id: number) {
  if (!confirm('Are you sure you want to delete this customer inquiry?')) return
  if (!authStore.token) return
  try {
    await $fetch(`${apiBase}/admin/inquiries/${id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${authStore.token}` },
    })
    toast.show('Inquiry deleted', 'info')
    loadInquiries()
  } catch (e: any) {
    toast.show('Failed to delete inquiry', 'error')
  }
}

onMounted(() => {
  loadInquiries()
})
</script>

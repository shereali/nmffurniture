<template>
  <div>
    <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
      <div>
        <h1 style="font-size: 1.85rem; font-family: var(--font-sans); font-weight: 700; margin-bottom: 0.25rem;">
          Customer Directory
        </h1>
        <p style="font-size: 0.9rem; color: var(--color-text-muted);">Registered client accounts, order counts, and address book.</p>
      </div>
    </div>

    <!-- Customers Table -->
    <div class="data-table-card">
      <table class="data-table">
        <thead>
          <tr>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Phone / WhatsApp</th>
            <th>Location</th>
            <th>Total Orders</th>
            <th>Joined Date</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="c in customers" :key="c.id">
            <td style="font-weight: 600;">
              {{ c.name }}
            </td>
            <td>{{ c.email }}</td>
            <td>
              <a
                v-if="c.phone"
                :href="`https://wa.me/${c.phone.replace(/[^0-9]/g, '')}`"
                target="_blank"
                style="color: #25D366; font-weight: 600;"
              >
                <i class="fa-brands fa-whatsapp"></i> {{ c.phone }}
              </a>
              <span v-else style="color: var(--color-text-light);">None</span>
            </td>
            <td>
              <span v-if="c.city">{{ c.city }}, {{ c.state }}</span>
              <span v-else style="color: var(--color-text-light);">-</span>
            </td>
            <td style="font-weight: 700;">
              {{ c.orders_count || 0 }} orders
            </td>
            <td style="font-size: 0.85rem; color: var(--color-text-muted);">
              {{ new Date(c.created_at).toLocaleDateString() }}
            </td>
          </tr>
        </tbody>
      </table>
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

const customers = ref<any[]>([])

async function loadCustomers() {
  if (!authStore.token) return
  try {
    const res: any = await $fetch(`${apiBase}/admin/customers`, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    customers.value = res.data || []
  } catch (e) {
    console.error('Failed to load customers', e)
  }
}

onMounted(() => {
  loadCustomers()
})
</script>

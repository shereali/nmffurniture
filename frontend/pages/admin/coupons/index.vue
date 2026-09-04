<template>
  <div>
    <div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
      <div>
        <h1 style="font-size: 1.85rem; font-family: var(--font-sans); font-weight: 700; margin-bottom: 0.25rem;">
          Promotional Coupons & Vouchers
        </h1>
        <p style="font-size: 0.9rem; color: var(--color-text-muted);">
          Create discount campaigns, percentage vouchers, minimum-spend rules, and seasonal promo codes.
        </p>
      </div>

      <button @click="openCreateModal" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Create Coupon
      </button>
    </div>

    <!-- Coupons Table -->
    <div class="data-table-card">
      <div v-if="loading" style="padding: 3rem; text-align: center; color: var(--color-text-muted);">
        <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 2rem; color: var(--color-secondary);"></i>
        <div style="margin-top: 0.75rem;">Loading promo vouchers...</div>
      </div>

      <table v-else-if="coupons.length > 0" class="data-table">
        <thead>
          <tr>
            <th>Promo Code</th>
            <th>Discount Value</th>
            <th>Min. Spend (RM)</th>
            <th>Max Discount</th>
            <th>Redemptions</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="c in coupons" :key="c.id">
            <td>
              <div style="font-family: monospace; font-size: 1rem; font-weight: 700; color: var(--color-primary); display: flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-ticket" style="color: var(--color-secondary-dark);"></i>
                {{ c.code }}
              </div>
              <div v-if="c.expires_at" style="font-size: 0.75rem; color: var(--color-text-light);">
                Expires: {{ new Date(c.expires_at).toLocaleDateString() }}
              </div>
            </td>
            <td>
              <span style="font-weight: 700; font-size: 1.05rem;">
                {{ c.type === 'percentage' ? `${c.value}% OFF` : `RM ${Number(c.value).toFixed(2)} OFF` }}
              </span>
            </td>
            <td>
              <span v-if="c.min_spend > 0" style="font-weight: 600;">
                RM {{ Number(c.min_spend).toFixed(2) }}
              </span>
              <span v-else style="color: var(--color-text-light);">None</span>
            </td>
            <td>
              <span v-if="c.max_discount">
                Cap RM {{ Number(c.max_discount).toFixed(2) }}
              </span>
              <span v-else style="color: var(--color-text-light);">-</span>
            </td>
            <td>
              <span style="font-weight: 600;">
                {{ c.used_count || 0 }}
                <span v-if="c.usage_limit" style="color: var(--color-text-light); font-weight: normal;">
                  / {{ c.usage_limit }}
                </span>
              </span>
            </td>
            <td>
              <button
                @click="toggleActive(c)"
                :class="['badge-status', c.is_active ? 'active' : 'inactive']"
                style="border: none; cursor: pointer;"
              >
                {{ c.is_active ? 'Active' : 'Disabled' }}
              </button>
            </td>
            <td>
              <div class="flex gap-2">
                <button
                  @click="openEditModal(c)"
                  class="btn btn-outline btn-sm"
                  title="Edit coupon"
                >
                  <i class="fa-regular fa-pen-to-square"></i>
                </button>
                <button
                  @click="deleteCoupon(c.id)"
                  class="btn btn-outline btn-sm"
                  style="color: var(--color-danger); border-color: var(--color-danger);"
                  title="Delete coupon"
                >
                  <i class="fa-regular fa-trash-can"></i>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-else style="padding: 3rem; text-align: center; color: var(--color-text-muted);">
        <p>No coupons found. Click "Create Coupon" to start a campaign.</p>
      </div>
    </div>

    <!-- Modal -->
    <div
      v-if="isModalOpen"
      style="position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 500; display: flex; align-items: center; justify-content: center; padding: 1.5rem;"
      @click.self="isModalOpen = false"
    >
      <div style="background: #FFFFFF; width: 100%; max-width: 520px; border-radius: var(--radius-sm); padding: 2rem; max-height: 90vh; overflow-y: auto;">
        <div class="flex justify-between items-center" style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem;">
          <h3 style="font-size: 1.35rem; font-weight: 700; margin-bottom: 0;">
            {{ isEditing ? 'Edit Promo Voucher' : 'Create New Promo Voucher' }}
          </h3>
          <button
            @click="isModalOpen = false"
            style="background: transparent; border: none; font-size: 1.25rem; color: var(--color-text-light); cursor: pointer;"
          >
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>

        <form @submit.prevent="saveCoupon" class="flex flex-col gap-4">
          <div class="form-group">
            <label class="form-label">Coupon Code *</label>
            <input
              v-model="form.code"
              type="text"
              class="form-input"
              required
              placeholder="e.g. WELCOME100"
              style="text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;"
            />
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
              <label class="form-label">Discount Type *</label>
              <select v-model="form.type" class="form-select" required>
                <option value="fixed">Fixed Amount (RM OFF)</option>
                <option value="percentage">Percentage (% OFF)</option>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label">Discount Value *</label>
              <input
                v-model.number="form.value"
                type="number"
                step="0.01"
                min="0"
                class="form-input"
                required
                :placeholder="form.type === 'percentage' ? 'e.g. 10 (for 10%)' : 'e.g. 100 (for RM 100)'"
              />
            </div>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
              <label class="form-label">Min. Spend Requirement (RM)</label>
              <input v-model.number="form.min_spend" type="number" step="0.01" min="0" class="form-input" placeholder="0" />
            </div>

            <div class="form-group" v-if="form.type === 'percentage'">
              <label class="form-label">Max. Discount Cap (RM)</label>
              <input v-model.number="form.max_discount" type="number" step="0.01" min="0" class="form-input" placeholder="e.g. 500" />
            </div>

            <div class="form-group" v-else>
              <label class="form-label">Usage Limit (Times)</label>
              <input v-model.number="form.usage_limit" type="number" min="1" class="form-input" placeholder="Optional" />
            </div>
          </div>

          <div class="form-group">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.9rem; font-weight: 600;">
              <input v-model="form.is_active" type="checkbox" style="width: 18px; height: 18px;" />
              Coupon is Active for Checkout
            </label>
          </div>

          <div class="flex justify-end gap-3" style="margin-top: 1rem; border-top: 1px solid var(--color-border); padding-top: 1rem;">
            <button type="button" @click="isModalOpen = false" class="btn btn-outline">Cancel</button>
            <button type="submit" class="btn btn-primary">
              {{ isEditing ? 'Save Changes' : 'Create Voucher' }}
            </button>
          </div>
        </form>
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

const coupons = ref<any[]>([])
const loading = ref(false)
const isModalOpen = ref(false)
const isEditing = ref(false)

const form = ref({
  id: null as number | null,
  code: '',
  type: 'fixed',
  value: 0,
  min_spend: 0,
  max_discount: null as number | null,
  usage_limit: null as number | null,
  is_active: true,
})

function openCreateModal() {
  isEditing.value = false
  form.value = {
    id: null,
    code: '',
    type: 'fixed',
    value: 50,
    min_spend: 500,
    max_discount: null,
    usage_limit: 100,
    is_active: true,
  }
  isModalOpen.value = true
}

function openEditModal(c: any) {
  isEditing.value = true
  form.value = {
    id: c.id,
    code: c.code,
    type: c.type,
    value: Number(c.value),
    min_spend: Number(c.min_spend),
    max_discount: c.max_discount ? Number(c.max_discount) : null,
    usage_limit: c.usage_limit,
    is_active: Boolean(c.is_active),
  }
  isModalOpen.value = true
}

async function loadCoupons() {
  if (!authStore.token) return
  loading.value = true
  try {
    const res: any = await $fetch(`${apiBase}/admin/coupons`, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    coupons.value = res.coupons || []
  } catch (e: any) {
    toast.show('Failed to load coupons', 'error')
  } finally {
    loading.value = false
  }
}

async function toggleActive(c: any) {
  if (!authStore.token) return
  const newStatus = !c.is_active
  try {
    await $fetch(`${apiBase}/admin/coupons/${c.id}`, {
      method: 'PUT',
      headers: { Authorization: `Bearer ${authStore.token}` },
      body: { is_active: newStatus },
    })
    c.is_active = newStatus
    toast.show(`Coupon ${c.code} set to ${newStatus ? 'Active' : 'Disabled'}`, 'info')
  } catch (e: any) {
    toast.show('Failed to update status', 'error')
  }
}

async function saveCoupon() {
  if (!authStore.token) return
  try {
    if (isEditing.value && form.value.id) {
      await $fetch(`${apiBase}/admin/coupons/${form.value.id}`, {
        method: 'PUT',
        headers: { Authorization: `Bearer ${authStore.token}` },
        body: form.value,
      })
      toast.show('Coupon updated', 'success')
    } else {
      await $fetch(`${apiBase}/admin/coupons`, {
        method: 'POST',
        headers: { Authorization: `Bearer ${authStore.token}` },
        body: form.value,
      })
      toast.show('Coupon created', 'success')
    }

    isModalOpen.value = false
    loadCoupons()
  } catch (e: any) {
    toast.show(e.data?.message || 'Failed to save coupon', 'error')
  }
}

async function deleteCoupon(id: number) {
  if (!confirm('Are you sure you want to delete this coupon?')) return
  if (!authStore.token) return
  try {
    await $fetch(`${apiBase}/admin/coupons/${id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${authStore.token}` },
    })
    toast.show('Coupon deleted', 'info')
    loadCoupons()
  } catch (e: any) {
    toast.show('Failed to delete coupon', 'error')
  }
}

onMounted(() => {
  loadCoupons()
})
</script>

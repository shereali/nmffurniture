<template>
  <div>
    <div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
      <div>
        <h1 style="font-size: 1.85rem; font-family: var(--font-sans); font-weight: 700; margin-bottom: 0.25rem;">
          Showroom Locations
        </h1>
        <p style="font-size: 0.9rem; color: var(--color-text-muted);">
          Manage physical showroom branches, operating hours, WhatsApp consultation links, and Google Maps.
        </p>
      </div>

      <button @click="openCreateModal" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add Showroom Branch
      </button>
    </div>

    <!-- Showrooms Grid -->
    <div v-if="loading" style="padding: 4rem; text-align: center; color: var(--color-text-muted);">
      <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 2rem; color: var(--color-secondary);"></i>
      <div style="margin-top: 0.75rem;">Loading showroom locations...</div>
    </div>

    <div
      v-else-if="showrooms.length > 0"
      style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 1.5rem;"
    >
      <div
        v-for="s in showrooms"
        :key="s.id"
        style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); overflow: hidden; box-shadow: var(--shadow-sm); display: flex; flex-direction: column;"
      >
        <div style="position: relative; height: 160px; background: #000000;">
          <img
            :src="s.image || 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=800&q=80'"
            :alt="s.name"
            style="width: 100%; height: 100%; object-fit: cover;"
          />
          <span
            :class="['badge-status', s.is_active ? 'active' : 'inactive']"
            style="position: absolute; top: 12px; right: 12px;"
          >
            {{ s.is_active ? 'Open / Active' : 'Temporarily Closed' }}
          </span>
        </div>

        <div style="padding: 1.5rem; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
          <div>
            <div style="font-size: 0.8rem; font-weight: 700; color: var(--color-secondary-dark); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">
              {{ s.branch }}
            </div>
            <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 0.75rem;">
              {{ s.name }}
            </h3>

            <div class="flex flex-col gap-2" style="font-size: 0.85rem; color: var(--color-text-muted);">
              <div class="flex items-start gap-2">
                <i class="fa-solid fa-location-dot" style="margin-top: 0.2rem; color: var(--color-text-light);"></i>
                <div>{{ s.address }}, {{ s.postal_code }} {{ s.city }}, {{ s.state }}</div>
              </div>

              <div class="flex items-center gap-2">
                <i class="fa-regular fa-clock" style="color: var(--color-text-light);"></i>
                <div>{{ s.opening_hours }}</div>
              </div>

              <div class="flex items-center gap-2">
                <i class="fa-brands fa-whatsapp" style="color: #25D366;"></i>
                <a :href="`https://wa.me/${s.whatsapp_number}`" target="_blank" style="color: #25D366; font-weight: 600;">
                  +{{ s.whatsapp_number }}
                </a>
              </div>

              <div v-if="s.phone" class="flex items-center gap-2">
                <i class="fa-solid fa-phone" style="color: var(--color-text-light);"></i>
                <div>{{ s.phone }}</div>
              </div>
            </div>
          </div>

          <div class="flex justify-between items-center" style="margin-top: 1.5rem; border-top: 1px solid var(--color-border); padding-top: 1rem;">
            <button
              @click="toggleActive(s)"
              class="btn btn-outline btn-sm"
              style="font-size: 0.8rem;"
            >
              {{ s.is_active ? 'Set Inactive' : 'Set Active' }}
            </button>

            <div class="flex gap-2">
              <button
                @click="openEditModal(s)"
                class="btn btn-outline btn-sm"
                title="Edit details"
              >
                <i class="fa-regular fa-pen-to-square"></i> Edit
              </button>
              <button
                @click="deleteShowroom(s.id)"
                class="btn btn-outline btn-sm"
                style="color: var(--color-danger); border-color: var(--color-danger);"
                title="Delete branch"
              >
                <i class="fa-regular fa-trash-can"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Create / Edit Modal -->
    <div
      v-if="isModalOpen"
      style="position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 500; display: flex; align-items: center; justify-content: center; padding: 1.5rem;"
      @click.self="isModalOpen = false"
    >
      <div style="background: #FFFFFF; width: 100%; max-width: 640px; border-radius: var(--radius-sm); padding: 2rem; max-height: 90vh; overflow-y: auto;">
        <div class="flex justify-between items-center" style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem;">
          <h3 style="font-size: 1.35rem; font-weight: 700; margin-bottom: 0;">
            {{ isEditing ? 'Edit Showroom Branch' : 'Add Showroom Branch' }}
          </h3>
          <button
            @click="isModalOpen = false"
            style="background: transparent; border: none; font-size: 1.25rem; color: var(--color-text-light); cursor: pointer;"
          >
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>

        <form @submit.prevent="saveShowroom" class="flex flex-col gap-4">
          <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
            <div class="form-group">
              <label class="form-label">Showroom Name *</label>
              <input v-model="form.name" type="text" class="form-input" required placeholder="e.g. NMFFurniture Shah Alam Flagship" />
            </div>

            <div class="form-group">
              <label class="form-label">Branch District *</label>
              <input v-model="form.branch" type="text" class="form-input" required placeholder="e.g. Bukit Jelutong" />
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Full Street Address *</label>
            <textarea v-model="form.address" class="form-textarea" rows="2" required placeholder="UG41 Komersial Radia, Persiaran Arked..."></textarea>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
            <div class="form-group">
              <label class="form-label">City *</label>
              <input v-model="form.city" type="text" class="form-input" required placeholder="Shah Alam" />
            </div>

            <div class="form-group">
              <label class="form-label">State *</label>
              <input v-model="form.state" type="text" class="form-input" required placeholder="Selangor" />
            </div>

            <div class="form-group">
              <label class="form-label">Postal Code *</label>
              <input v-model="form.postal_code" type="text" class="form-input" required placeholder="40150" />
            </div>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
              <label class="form-label">WhatsApp Contact Number *</label>
              <input v-model="form.whatsapp_number" type="text" class="form-input" required placeholder="60192589920" />
            </div>

            <div class="form-group">
              <label class="form-label">Telephone (Landline)</label>
              <input v-model="form.phone" type="text" class="form-input" placeholder="+603 7832 9920" />
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Opening Hours *</label>
            <input v-model="form.opening_hours" type="text" class="form-input" required placeholder="Tue - Sun: 10:30 AM - 7:30 PM (Closed Mon)" />
          </div>

          <div class="form-group">
            <label class="form-label">Showroom Photo URL</label>
            <input v-model="form.image" type="url" class="form-input" placeholder="https://images.unsplash.com/..." />
            <div v-if="form.image" style="margin-top: 0.5rem;">
              <img
                :src="form.image"
                alt="Preview"
                style="width: 100%; height: 120px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--color-border);"
              />
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Google Maps Embed URL</label>
            <input v-model="form.map_embed_url" type="url" class="form-input" placeholder="https://maps.google.com/maps?q=..." />
          </div>

          <div class="form-group">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.9rem; font-weight: 600;">
              <input v-model="form.is_active" type="checkbox" style="width: 18px; height: 18px;" />
              Branch is Open & Active for Visitors
            </label>
          </div>

          <div class="flex justify-end gap-3" style="margin-top: 1rem; border-top: 1px solid var(--color-border); padding-top: 1rem;">
            <button type="button" @click="isModalOpen = false" class="btn btn-outline">Cancel</button>
            <button type="submit" class="btn btn-primary">
              {{ isEditing ? 'Save Changes' : 'Create Branch' }}
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

const showrooms = ref<any[]>([])
const loading = ref(false)
const isModalOpen = ref(false)
const isEditing = ref(false)

const form = ref({
  id: null as number | null,
  name: '',
  branch: '',
  address: '',
  city: '',
  state: '',
  postal_code: '',
  phone: '',
  whatsapp_number: '',
  opening_hours: 'Tue - Sun: 10:30 AM - 7:30 PM',
  image: '',
  map_embed_url: '',
  is_active: true,
})

function openCreateModal() {
  isEditing.value = false
  form.value = {
    id: null,
    name: '',
    branch: '',
    address: '',
    city: 'Shah Alam',
    state: 'Selangor',
    postal_code: '40150',
    phone: '',
    whatsapp_number: '60192589920',
    opening_hours: 'Tue - Sun: 10:30 AM - 7:30 PM (Closed on Monday)',
    image: 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=800&q=80',
    map_embed_url: '',
    is_active: true,
  }
  isModalOpen.value = true
}

function openEditModal(s: any) {
  isEditing.value = true
  form.value = {
    id: s.id,
    name: s.name,
    branch: s.branch,
    address: s.address,
    city: s.city,
    state: s.state,
    postal_code: s.postal_code,
    phone: s.phone || '',
    whatsapp_number: s.whatsapp_number,
    opening_hours: s.opening_hours,
    image: s.image || '',
    map_embed_url: s.map_embed_url || '',
    is_active: Boolean(s.is_active),
  }
  isModalOpen.value = true
}

async function loadShowrooms() {
  if (!authStore.token) return
  loading.value = true
  try {
    const res: any = await $fetch(`${apiBase}/admin/showrooms`, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    showrooms.value = res.showrooms || []
  } catch (e: any) {
    toast.show('Failed to load showrooms', 'error')
  } finally {
    loading.value = false
  }
}

async function toggleActive(s: any) {
  if (!authStore.token) return
  const newStatus = !s.is_active
  try {
    await $fetch(`${apiBase}/admin/showrooms/${s.id}`, {
      method: 'PUT',
      headers: { Authorization: `Bearer ${authStore.token}` },
      body: { is_active: newStatus },
    })
    s.is_active = newStatus
    toast.show(`Showroom set to ${newStatus ? 'Active' : 'Inactive'}`, 'info')
  } catch (e: any) {
    toast.show('Failed to toggle status', 'error')
  }
}

async function saveShowroom() {
  if (!authStore.token) return
  try {
    if (isEditing.value && form.value.id) {
      await $fetch(`${apiBase}/admin/showrooms/${form.value.id}`, {
        method: 'PUT',
        headers: { Authorization: `Bearer ${authStore.token}` },
        body: form.value,
      })
      toast.show('Showroom branch updated', 'success')
    } else {
      await $fetch(`${apiBase}/admin/showrooms`, {
        method: 'POST',
        headers: { Authorization: `Bearer ${authStore.token}` },
        body: form.value,
      })
      toast.show('Showroom branch created', 'success')
    }

    isModalOpen.value = false
    loadShowrooms()
  } catch (e: any) {
    toast.show(e.data?.message || 'Failed to save showroom', 'error')
  }
}

async function deleteShowroom(id: number) {
  if (!confirm('Are you sure you want to delete this showroom location?')) return
  if (!authStore.token) return
  try {
    await $fetch(`${apiBase}/admin/showrooms/${id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${authStore.token}` },
    })
    toast.show('Showroom location deleted', 'info')
    loadShowrooms()
  } catch (e: any) {
    toast.show('Failed to delete showroom', 'error')
  }
}

onMounted(() => {
  loadShowrooms()
})
</script>

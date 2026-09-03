<template>
  <div>
    <div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
      <div>
        <h1 style="font-size: 1.85rem; font-family: var(--font-sans); font-weight: 700; margin-bottom: 0.25rem;">
          Showcase Portfolio Gallery
        </h1>
        <p style="font-size: 0.9rem; color: var(--color-text-muted);">
          Curate client living room installations, bespoke sectional styling, and showroom photography.
        </p>
      </div>

      <button @click="openCreateModal" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add Showcase Photo
      </button>
    </div>

    <!-- Filter Bar -->
    <div style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 1.25rem; margin-bottom: 1.5rem; display: flex; gap: 1rem; align-items: center; justify-content: space-between; flex-wrap: wrap;">
      <div class="flex gap-2 flex-wrap">
        <button
          v-for="cat in categoryTabs"
          :key="cat.value"
          @click="filterCategory(cat.value)"
          class="btn btn-sm"
          :class="activeCategory === cat.value ? 'btn-primary' : 'btn-outline'"
        >
          {{ cat.label }}
        </button>
      </div>

      <button @click="loadGallery" class="btn btn-outline btn-sm">
        <i class="fa-solid fa-rotate-right"></i>
      </button>
    </div>

    <!-- Gallery Grid -->
    <div v-if="loading" style="padding: 4rem; text-align: center; color: var(--color-text-muted);">
      <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 2rem; color: var(--color-secondary);"></i>
      <div style="margin-top: 0.75rem;">Loading showcase photos...</div>
    </div>

    <div
      v-else-if="items.length > 0"
      style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;"
    >
      <div
        v-for="item in items"
        :key="item.id"
        style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); overflow: hidden; box-shadow: var(--shadow-sm); display: flex; flex-direction: column;"
      >
        <div style="position: relative; height: 190px; background: #000000;">
          <img
            :src="item.image_url"
            :alt="item.title"
            style="width: 100%; height: 100%; object-fit: cover;"
          />
          <span
            v-if="item.is_featured"
            style="position: absolute; top: 10px; left: 10px; background: var(--color-secondary); color: #FFFFFF; font-size: 0.7rem; font-weight: 700; padding: 0.2rem 0.5rem; border-radius: var(--radius-sm);"
          >
            Featured
          </span>
          <span
            style="position: absolute; bottom: 10px; right: 10px; background: rgba(0,0,0,0.65); color: #FFFFFF; font-size: 0.7rem; font-weight: 600; padding: 0.2rem 0.5rem; border-radius: var(--radius-sm); text-transform: uppercase;"
          >
            {{ item.category }}
          </span>
        </div>

        <div style="padding: 1.25rem; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
          <div>
            <h4 style="font-size: 1.05rem; font-weight: 700; margin-bottom: 0.35rem;">
              {{ item.title }}
            </h4>
            <div v-if="item.client_name" style="font-size: 0.8rem; color: var(--color-secondary-dark); font-weight: 600; margin-bottom: 0.5rem;">
              <i class="fa-solid fa-location-dot"></i> {{ item.client_name }}
            </div>
            <p v-if="item.description" style="font-size: 0.85rem; color: var(--color-text-muted); line-height: 1.4; margin-bottom: 0;">
              {{ item.description }}
            </p>
          </div>

          <div class="flex justify-between items-center" style="margin-top: 1.25rem; border-top: 1px solid var(--color-border); padding-top: 0.75rem;">
            <span style="font-size: 0.75rem; color: var(--color-text-light);">
              Sort #{{ item.sort_order || 0 }}
            </span>

            <div class="flex gap-2">
              <button
                @click="openEditModal(item)"
                class="btn btn-outline btn-sm"
                title="Edit item"
              >
                <i class="fa-regular fa-pen-to-square"></i>
              </button>
              <button
                @click="deleteItem(item.id)"
                class="btn btn-outline btn-sm"
                style="color: var(--color-danger); border-color: var(--color-danger);"
                title="Delete photo"
              >
                <i class="fa-regular fa-trash-can"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-else style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 4rem; text-align: center; color: var(--color-text-muted);">
      <i class="fa-regular fa-images" style="font-size: 2.5rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
      <p>No gallery images found in this category.</p>
    </div>

    <!-- Create / Edit Modal -->
    <div
      v-if="isModalOpen"
      style="position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 500; display: flex; align-items: center; justify-content: center; padding: 1.5rem;"
      @click.self="isModalOpen = false"
    >
      <div style="background: #FFFFFF; width: 100%; max-width: 540px; border-radius: var(--radius-sm); padding: 2rem; max-height: 90vh; overflow-y: auto;">
        <div class="flex justify-between items-center" style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem;">
          <h3 style="font-size: 1.35rem; font-weight: 700; margin-bottom: 0;">
            {{ isEditing ? 'Edit Showcase Photo' : 'Add Showcase Photo' }}
          </h3>
          <button
            @click="isModalOpen = false"
            style="background: transparent; border: none; font-size: 1.25rem; color: var(--color-text-light); cursor: pointer;"
          >
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>

        <form @submit.prevent="saveItem" class="flex flex-col gap-4">
          <div class="form-group">
            <label class="form-label">Project / Installation Title *</label>
            <input v-model="form.title" type="text" class="form-input" required placeholder="e.g. Minimalist Living Room in Bukit Jelutong" />
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
              <label class="form-label">Category *</label>
              <select v-model="form.category" class="form-select" required>
                <option value="living">Living Room</option>
                <option value="bedroom">Bedroom Suite</option>
                <option value="dining">Dining Space</option>
                <option value="showroom">Showroom Floor</option>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label">Client / Location</label>
              <input v-model="form.client_name" type="text" class="form-input" placeholder="e.g. Radia Bukit Jelutong" />
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Image URL *</label>
            <input v-model="form.image_url" type="url" class="form-input" required placeholder="https://images.unsplash.com/..." />
            <div v-if="form.image_url" style="margin-top: 0.5rem;">
              <img
                :src="form.image_url"
                alt="Preview"
                style="width: 100%; height: 130px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--color-border);"
              />
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Craftsmanship Story / Details</label>
            <textarea v-model="form.description" class="form-textarea" rows="2" placeholder="Custom 3-seater channel sofa upholstered in champagne velvet..."></textarea>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
              <label class="form-label">Display Order Number</label>
              <input v-model.number="form.sort_order" type="number" class="form-input" min="0" placeholder="0" />
            </div>

            <div class="form-group" style="display: flex; flex-direction: column; justify-content: flex-end;">
              <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.9rem; font-weight: 600; margin-bottom: 0.5rem;">
                <input v-model="form.is_featured" type="checkbox" style="width: 18px; height: 18px;" />
                Feature on Homepage
              </label>
            </div>
          </div>

          <div class="flex justify-end gap-3" style="margin-top: 1rem; border-top: 1px solid var(--color-border); padding-top: 1rem;">
            <button type="button" @click="isModalOpen = false" class="btn btn-outline">Cancel</button>
            <button type="submit" class="btn btn-primary">
              {{ isEditing ? 'Save Changes' : 'Add Photo' }}
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

const items = ref<any[]>([])
const loading = ref(false)
const activeCategory = ref('all')
const isModalOpen = ref(false)
const isEditing = ref(false)

const categoryTabs = [
  { label: 'All Photos', value: 'all' },
  { label: 'Living', value: 'living' },
  { label: 'Bedroom', value: 'bedroom' },
  { label: 'Dining', value: 'dining' },
  { label: 'Showroom Floor', value: 'showroom' },
]

const form = ref({
  id: null as number | null,
  title: '',
  category: 'living',
  image_url: '',
  description: '',
  client_name: '',
  sort_order: 0,
  is_featured: false,
})

function filterCategory(cat: string) {
  activeCategory.value = cat
  loadGallery()
}

function openCreateModal() {
  isEditing.value = false
  form.value = {
    id: null,
    title: '',
    category: 'living',
    image_url: 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1200&q=80',
    description: '',
    client_name: '',
    sort_order: items.value.length + 1,
    is_featured: false,
  }
  isModalOpen.value = true
}

function openEditModal(item: any) {
  isEditing.value = true
  form.value = {
    id: item.id,
    title: item.title,
    category: item.category,
    image_url: item.image_url,
    description: item.description || '',
    client_name: item.client_name || '',
    sort_order: item.sort_order || 0,
    is_featured: Boolean(item.is_featured),
  }
  isModalOpen.value = true
}

async function loadGallery() {
  if (!authStore.token) return
  loading.value = true
  try {
    const params: any = {}
    if (activeCategory.value !== 'all') {
      params.category = activeCategory.value
    }

    const res: any = await $fetch(`${apiBase}/admin/gallery`, {
      headers: { Authorization: `Bearer ${authStore.token}` },
      params,
    })

    items.value = res.data || []
  } catch (e: any) {
    toast.show('Failed to load gallery items', 'error')
  } finally {
    loading.value = false
  }
}

async function saveItem() {
  if (!authStore.token) return
  try {
    if (isEditing.value && form.value.id) {
      await $fetch(`${apiBase}/admin/gallery/${form.value.id}`, {
        method: 'PUT',
        headers: { Authorization: `Bearer ${authStore.token}` },
        body: form.value,
      })
      toast.show('Gallery photo updated', 'success')
    } else {
      await $fetch(`${apiBase}/admin/gallery`, {
        method: 'POST',
        headers: { Authorization: `Bearer ${authStore.token}` },
        body: form.value,
      })
      toast.show('Showcase photo added', 'success')
    }

    isModalOpen.value = false
    loadGallery()
  } catch (e: any) {
    toast.show(e.data?.message || 'Failed to save gallery item', 'error')
  }
}

async function deleteItem(id: number) {
  if (!confirm('Are you sure you want to remove this photo from the showcase?')) return
  if (!authStore.token) return
  try {
    await $fetch(`${apiBase}/admin/gallery/${id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${authStore.token}` },
    })
    toast.show('Photo removed from showcase', 'info')
    loadGallery()
  } catch (e: any) {
    toast.show('Failed to delete photo', 'error')
  }
}

onMounted(() => {
  loadGallery()
})
</script>

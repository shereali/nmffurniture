<template>
  <div>
    <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
      <div>
        <h1 style="font-size: 1.85rem; font-family: var(--font-sans); font-weight: 700; margin-bottom: 0.25rem;">
          Categories Manager
        </h1>
        <p style="font-size: 0.9rem; color: var(--color-text-muted);">Manage furniture taxonomy, hierarchy, and showcase banners.</p>
      </div>

      <button @click="openCreateModal" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add Category
      </button>
    </div>

    <!-- Category Table -->
    <div class="data-table-card">
      <table class="data-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Parent Category</th>
            <th>Slug</th>
            <th>Total Products</th>
            <th>Featured</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="cat in categories" :key="cat.id">
            <td style="font-weight: 600;">
              {{ cat.name }}
            </td>
            <td>
              <span v-if="cat.parent" style="color: var(--color-secondary-dark); font-weight: 600;">
                {{ cat.parent.name }}
              </span>
              <span v-else style="color: var(--color-text-light);">Root Category</span>
            </td>
            <td style="font-family: monospace; font-size: 0.85rem;">{{ cat.slug }}</td>
            <td style="font-weight: 600;">{{ cat.products_count || 0 }} products</td>
            <td>
              <span v-if="cat.is_featured" style="background: #E8F5E9; color: #2E7D32; font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.5rem; border-radius: var(--radius-sm);">
                Featured
              </span>
            </td>
            <td>
              <button @click="deleteCategory(cat.id)" class="btn btn-outline btn-sm" style="color: var(--color-danger); border-color: var(--color-danger);">
                <i class="fa-regular fa-trash-can"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Category Modal -->
    <div
      v-if="isModalOpen"
      style="position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 300; display: flex; align-items: center; justify-content: center; padding: 2rem;"
      @click.self="isModalOpen = false"
    >
      <div style="background: #FFFFFF; width: 100%; max-width: 500px; border-radius: var(--radius-sm); padding: 2rem;">
        <h3 style="font-size: 1.35rem; margin-bottom: 1.25rem;">Create New Category</h3>

        <form @submit.prevent="saveCategory" class="flex flex-col gap-4">
          <div class="form-group">
            <label class="form-label">Category Name *</label>
            <input v-model="form.name" type="text" class="form-input" required placeholder="e.g. Ottoman & Stool" />
          </div>

          <div class="form-group">
            <label class="form-label">Parent Category</label>
            <select v-model="form.parent_id" class="form-select">
              <option :value="null">-- None (Root Category) --</option>
              <option v-for="c in rootCategories" :key="c.id" :value="c.id">{{ c.name }}</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Description</label>
            <textarea v-model="form.description" class="form-textarea" rows="2" placeholder="Category story..."></textarea>
          </div>

          <div class="flex justify-end gap-3" style="margin-top: 1rem;">
            <button type="button" @click="isModalOpen = false" class="btn btn-outline">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Category</button>
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

const categories = ref<any[]>([])
const isModalOpen = ref(false)

const form = reactive({
  name: '',
  parent_id: null as number | null,
  description: '',
})

const rootCategories = computed(() => {
  return categories.value.filter(c => !c.parent_id)
})

async function loadCategories() {
  if (!authStore.token) return
  try {
    const res: any = await $fetch(`${apiBase}/admin/categories`, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    categories.value = res.categories || []
  } catch (e) {
    console.error('Failed to load categories', e)
  }
}

function openCreateModal() {
  form.name = ''
  form.parent_id = null
  form.description = ''
  isModalOpen.value = true
}

async function saveCategory() {
  try {
    await $fetch(`${apiBase}/admin/categories`, {
      method: 'POST',
      body: form,
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    toast.show('Category created successfully!', 'success')
    isModalOpen.value = false
    loadCategories()
  } catch (e) {
    toast.show('Failed to create category', 'error')
  }
}

async function deleteCategory(id: number) {
  if (!confirm('Are you sure you want to delete this category?')) return
  try {
    await $fetch(`${apiBase}/admin/categories/${id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    toast.show('Category deleted', 'info')
    loadCategories()
  } catch (e) {
    toast.show('Failed to delete category', 'error')
  }
}

onMounted(() => {
  loadCategories()
})
</script>

<template>
  <div>
    <div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
      <div>
        <h1 style="font-size: 1.85rem; font-family: var(--font-sans); font-weight: 700; margin-bottom: 0.25rem;">
          Categories Manager
        </h1>
        <p style="font-size: 0.9rem; color: var(--color-text-muted);">
          Organize furniture taxonomy, nested collections, featured showcase tags, and ordering.
        </p>
      </div>

      <button @click="openCreateModal" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add Category
      </button>
    </div>

    <!-- Category Table -->
    <div class="data-table-card">
      <div v-if="loading" style="padding: 3rem; text-align: center; color: var(--color-text-muted);">
        <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 2rem; color: var(--color-secondary);"></i>
        <div style="margin-top: 0.75rem;">Loading categories...</div>
      </div>

      <table v-else-if="categories.length > 0" class="data-table">
        <thead>
          <tr>
            <th>Category</th>
            <th>Type / Hierarchy</th>
            <th>Slug</th>
            <th>Catalog Size</th>
            <th>Sort Order</th>
            <th>Featured</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="cat in categories" :key="cat.id">
            <td>
              <div class="flex items-center gap-3">
                <img
                  :src="cat.image || 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=120&q=80'"
                  :alt="cat.name"
                  style="width: 46px; height: 46px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--color-border);"
                />
                <div>
                  <div style="font-weight: 700; color: var(--color-primary);">{{ cat.name }}</div>
                  <div v-if="cat.description" style="font-size: 0.75rem; color: var(--color-text-muted); max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    {{ cat.description }}
                  </div>
                </div>
              </div>
            </td>
            <td>
              <span v-if="cat.parent" style="background: #EDE7F6; color: #512DA8; font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.55rem; border-radius: var(--radius-sm);">
                Sub of {{ cat.parent.name }}
              </span>
              <span v-else style="background: #E0F2F1; color: #00796B; font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.55rem; border-radius: var(--radius-sm);">
                Root Category
              </span>
            </td>
            <td style="font-family: monospace; font-size: 0.85rem; color: var(--color-text-muted);">
              {{ cat.slug }}
            </td>
            <td style="font-weight: 700;">
              {{ cat.products_count || 0 }} models
            </td>
            <td style="font-size: 0.85rem; color: var(--color-text-muted);">
              #{{ cat.sort_order || 0 }}
            </td>
            <td>
              <span v-if="cat.is_featured" style="background: #E8F5E9; color: #2E7D32; font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.5rem; border-radius: var(--radius-sm);">
                Featured
              </span>
              <span v-else style="color: var(--color-text-light); font-size: 0.8rem;">
                Standard
              </span>
            </td>
            <td>
              <div class="flex gap-2">
                <button
                  @click="openEditModal(cat)"
                  class="btn btn-outline btn-sm"
                  title="Edit category"
                >
                  <i class="fa-regular fa-pen-to-square"></i>
                </button>
                <button
                  @click="deleteCategory(cat.id)"
                  class="btn btn-outline btn-sm"
                  style="color: var(--color-danger); border-color: var(--color-danger);"
                  title="Delete category"
                >
                  <i class="fa-regular fa-trash-can"></i>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-else style="padding: 3rem; text-align: center; color: var(--color-text-muted);">
        <p>No categories found. Click "Add Category" to begin.</p>
      </div>
    </div>

    <!-- Category Create / Edit Modal -->
    <div
      v-if="isModalOpen"
      style="position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 500; display: flex; align-items: center; justify-content: center; padding: 1.5rem;"
      @click.self="isModalOpen = false"
    >
      <div style="background: #FFFFFF; width: 100%; max-width: 540px; border-radius: var(--radius-sm); padding: 2rem; max-height: 90vh; overflow-y: auto;">
        <div class="flex justify-between items-center" style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem;">
          <h3 style="font-size: 1.35rem; font-weight: 700; margin-bottom: 0;">
            {{ isEditing ? 'Edit Category' : 'Create New Category' }}
          </h3>
          <button
            @click="isModalOpen = false"
            style="background: transparent; border: none; font-size: 1.25rem; color: var(--color-text-light); cursor: pointer;"
          >
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>

        <form @submit.prevent="saveCategory" class="flex flex-col gap-4">
          <div class="form-group">
            <label class="form-label">Category Name *</label>
            <input v-model="form.name" type="text" class="form-input" required placeholder="e.g. Ottomans & Benches" />
          </div>

          <div class="form-group">
            <label class="form-label">Parent Category (Optional)</label>
            <select v-model="form.parent_id" class="form-select">
              <option :value="null">-- None (Root Category) --</option>
              <option
                v-for="c in rootCategories"
                :key="c.id"
                :value="c.id"
                :disabled="isEditing && form.id === c.id"
              >
                {{ c.name }}
              </option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Banner Image URL</label>
            <input v-model="form.image" type="url" class="form-input" placeholder="https://images.unsplash.com/..." />
            <div v-if="form.image" style="margin-top: 0.5rem;">
              <img
                :src="form.image"
                alt="Preview"
                style="width: 100%; height: 110px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--color-border);"
                @error="form.image = ''"
              />
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Short Description</label>
            <textarea v-model="form.description" class="form-textarea" rows="2" placeholder="Handcrafted artisanal living room pieces..."></textarea>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
              <label class="form-label">Sort Order Number</label>
              <input v-model.number="form.sort_order" type="number" class="form-input" min="0" placeholder="0" />
            </div>

            <div class="form-group" style="display: flex; flex-direction: column; justify-content: flex-end;">
              <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.9rem; font-weight: 600; margin-bottom: 0.5rem;">
                <input v-model="form.is_featured" type="checkbox" style="width: 18px; height: 18px;" />
                Featured Category
              </label>
            </div>
          </div>

          <div class="flex justify-end gap-3" style="margin-top: 1rem; border-top: 1px solid var(--color-border); padding-top: 1rem;">
            <button type="button" @click="isModalOpen = false" class="btn btn-outline">Cancel</button>
            <button type="submit" class="btn btn-primary">
              {{ isEditing ? 'Save Changes' : 'Create Category' }}
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

const categories = ref<any[]>([])
const loading = ref(false)
const isModalOpen = ref(false)
const isEditing = ref(false)

const form = ref({
  id: null as number | null,
  parent_id: null as number | null,
  name: '',
  description: '',
  image: '',
  is_featured: false,
  sort_order: 0,
})

const rootCategories = computed(() => {
  return categories.value.filter(c => !c.parent_id)
})

function openCreateModal() {
  isEditing.value = false
  form.value = {
    id: null,
    parent_id: null,
    name: '',
    description: '',
    image: '',
    is_featured: false,
    sort_order: categories.value.length + 1,
  }
  isModalOpen.value = true
}

function openEditModal(cat: any) {
  isEditing.value = true
  form.value = {
    id: cat.id,
    parent_id: cat.parent_id || null,
    name: cat.name,
    description: cat.description || '',
    image: cat.image || '',
    is_featured: Boolean(cat.is_featured),
    sort_order: cat.sort_order || 0,
  }
  isModalOpen.value = true
}

async function loadCategories() {
  if (!authStore.token) return
  loading.value = true
  try {
    const res: any = await $fetch(`${apiBase}/admin/categories`, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    categories.value = res.categories || []
  } catch (e: any) {
    toast.show('Failed to load categories', 'error')
  } finally {
    loading.value = false
  }
}

async function saveCategory() {
  if (!authStore.token) return
  try {
    if (isEditing.value && form.value.id) {
      await $fetch(`${apiBase}/admin/categories/${form.value.id}`, {
        method: 'PUT',
        headers: { Authorization: `Bearer ${authStore.token}` },
        body: form.value,
      })
      toast.show('Category updated successfully', 'success')
    } else {
      await $fetch(`${apiBase}/admin/categories`, {
        method: 'POST',
        headers: { Authorization: `Bearer ${authStore.token}` },
        body: form.value,
      })
      toast.show('Category created successfully', 'success')
    }

    isModalOpen.value = false
    loadCategories()
  } catch (e: any) {
    toast.show(e.data?.message || 'Failed to save category', 'error')
  }
}

async function deleteCategory(id: number) {
  if (!confirm('Are you sure you want to delete this category? Products in this category will become uncategorized.')) return
  if (!authStore.token) return
  try {
    await $fetch(`${apiBase}/admin/categories/${id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${authStore.token}` },
    })
    toast.show('Category deleted successfully', 'info')
    loadCategories()
  } catch (e: any) {
    toast.show('Failed to delete category', 'error')
  }
}

onMounted(() => {
  loadCategories()
})
</script>

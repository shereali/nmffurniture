<template>
  <div>
    <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
      <div>
        <h1 style="font-size: 1.85rem; font-family: var(--font-sans); font-weight: 700; margin-bottom: 0.25rem;">
          Products Catalog
        </h1>
        <p style="font-size: 0.9rem; color: var(--color-text-muted);">Manage furniture models, variants, pricing, and stock.</p>
      </div>

      <button @click="openCreateModal" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add New Product
      </button>
    </div>

    <!-- Filter Bar -->
    <div style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 1.25rem; margin-bottom: 1.5rem; display: flex; gap: 1rem; align-items: center; justify-content: space-between; flex-wrap: wrap;">
      <div style="flex: 1 1 300px; position: relative;">
        <input
          v-model="searchQuery"
          @input="debounceSearch"
          type="text"
          placeholder="Search by product name or SKU..."
          class="form-input"
          style="padding-left: 2.5rem;"
        />
        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-text-light);"></i>
      </div>
    </div>

    <!-- Products Table -->
    <div class="data-table-card">
      <table class="data-table">
        <thead>
          <tr>
            <th>Product Details</th>
            <th>Category</th>
            <th>SKU</th>
            <th>Price (RM)</th>
            <th>Stock</th>
            <th>Warranty</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="p in products" :key="p.id">
            <td>
              <div class="flex items-center gap-3">
                <img
                  :src="p.images?.[0]?.image_url || 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=150&q=80'"
                  :alt="p.name"
                  style="width: 54px; height: 54px; object-fit: cover; border-radius: var(--radius-sm);"
                />
                <div>
                  <div style="font-weight: 600; color: var(--color-primary);">{{ p.name }}</div>
                  <span v-if="p.is_featured" style="font-size: 0.7rem; background: var(--color-secondary-light); color: var(--color-secondary-dark); padding: 0.1rem 0.4rem; border-radius: var(--radius-sm); font-weight: 700;">
                    Featured
                  </span>
                </div>
              </div>
            </td>
            <td>{{ p.category?.name || 'Uncategorized' }}</td>
            <td style="font-family: monospace; font-size: 0.85rem;">{{ p.sku }}</td>
            <td style="font-weight: 700;">
              <div>RM {{ Number(p.discount_price ?? p.price).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</div>
              <span v-if="p.discount_price" style="font-size: 0.75rem; color: var(--color-text-light); text-decoration: line-through;">
                RM {{ Number(p.price).toFixed(2) }}
              </span>
            </td>
            <td>
              <span :style="{ color: p.stock <= 10 ? 'var(--color-danger)' : 'inherit', fontWeight: 600 }">
                {{ p.stock }} units
              </span>
            </td>
            <td>{{ p.warranty_years }} Years</td>
            <td>
              <div class="flex gap-2">
                <button
                  @click="openEditModal(p)"
                  class="btn btn-outline btn-sm"
                  title="Edit product"
                >
                  <i class="fa-regular fa-pen-to-square"></i>
                </button>
                <button
                  @click="deleteProduct(p.id)"
                  class="btn btn-outline btn-sm"
                  style="color: var(--color-danger); border-color: var(--color-danger);"
                  title="Delete product"
                >
                  <i class="fa-regular fa-trash-can"></i>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Product Create / Edit Modal -->
    <div
      v-if="isModalOpen"
      style="position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 300; display: flex; align-items: center; justify-content: center; padding: 2rem;"
      @click.self="isModalOpen = false"
    >
      <div style="background: #FFFFFF; width: 100%; max-width: 700px; max-height: 90vh; overflow-y: auto; border-radius: var(--radius-sm); padding: 2.5rem;">
        <div class="flex justify-between items-center" style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem;">
          <h3 style="font-size: 1.4rem;">{{ editingId ? 'Edit Product' : 'Add New Product' }}</h3>
          <button @click="isModalOpen = false" style="background: transparent; border: none; font-size: 1.25rem; cursor: pointer;">
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>

        <form @submit.prevent="saveProduct" class="flex flex-col gap-4">
          <div class="grid grid-cols-2 gap-4">
            <div class="form-group">
              <label class="form-label">Product Name *</label>
              <input v-model="form.name" type="text" class="form-input" required placeholder="e.g. Modern Curve Sofa" />
            </div>
            <div class="form-group">
              <label class="form-label">Category *</label>
              <select v-model="form.category_id" class="form-select" required>
                <option v-for="c in flatCategories" :key="c.id" :value="c.id">{{ c.name }}</option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-3 gap-4">
            <div class="form-group">
              <label class="form-label">SKU *</label>
              <input v-model="form.sku" type="text" class="form-input" required placeholder="e.g. CURVE-SOFA-01" />
            </div>
            <div class="form-group">
              <label class="form-label">Regular Price (RM) *</label>
              <input v-model="form.price" type="number" step="0.01" class="form-input" required placeholder="2800" />
            </div>
            <div class="form-group">
              <label class="form-label">Discount Price (RM)</label>
              <input v-model="form.discount_price" type="number" step="0.01" class="form-input" placeholder="2500" />
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="form-group">
              <label class="form-label">Stock Quantity *</label>
              <input v-model="form.stock" type="number" class="form-input" required placeholder="50" />
            </div>
            <div class="form-group">
              <label class="form-label">Warranty (Years) *</label>
              <input v-model="form.warranty_years" type="number" class="form-input" required placeholder="5" />
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Primary Image URL</label>
            <input v-model="form.primary_image" type="url" class="form-input" placeholder="https://images.unsplash.com/..." />
          </div>

          <div class="form-group">
            <label class="form-label">Short Summary</label>
            <input v-model="form.short_description" type="text" class="form-input" placeholder="Signature curved sofa in pet-friendly fabric" />
          </div>

          <div class="form-group">
            <label class="form-label">Full Description</label>
            <textarea v-model="form.description" class="form-textarea" rows="3" placeholder="Full craftsmanship details and materials..."></textarea>
          </div>

          <div class="flex justify-end gap-3" style="margin-top: 1rem;">
            <button type="button" @click="isModalOpen = false" class="btn btn-outline">Cancel</button>
            <button type="submit" class="btn btn-primary">
              {{ editingId ? 'Update Product' : 'Create Product' }}
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

const products = ref<any[]>([])
const flatCategories = ref<any[]>([])
const searchQuery = ref('')
const isModalOpen = ref(false)
const editingId = ref<number | null>(null)

const form = reactive({
  name: '',
  category_id: 1,
  sku: '',
  price: 2500,
  discount_price: null,
  stock: 20,
  warranty_years: 5,
  primary_image: '',
  short_description: '',
  description: '',
})

let timer: any = null

async function loadProducts() {
  if (!authStore.token) return
  try {
    const res: any = await $fetch(`${apiBase}/admin/products?search=${searchQuery.value}`, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    products.value = res.data || []
  } catch (e) {
    console.error('Failed to load products', e)
  }
}

async function loadCategories() {
  if (!authStore.token) return
  try {
    const res: any = await $fetch(`${apiBase}/admin/categories`, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    flatCategories.value = res.categories || []
    if (flatCategories.value.length > 0 && !form.category_id) {
      form.category_id = flatCategories.value[0].id
    }
  } catch (e) {
    console.error('Failed to load categories', e)
  }
}

function debounceSearch() {
  clearTimeout(timer)
  timer = setTimeout(() => {
    loadProducts()
  }, 400)
}

function openCreateModal() {
  editingId.value = null
  form.name = ''
  form.sku = `NMF-${Math.floor(1000 + Math.random() * 9000)}`
  form.price = 2800
  form.discount_price = null
  form.stock = 30
  form.warranty_years = 5
  form.primary_image = 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1200&q=80'
  form.short_description = ''
  form.description = ''
  isModalOpen.value = true
}

function openEditModal(p: any) {
  editingId.value = p.id
  form.name = p.name
  form.category_id = p.category_id
  form.sku = p.sku
  form.price = Number(p.price)
  form.discount_price = p.discount_price ? Number(p.discount_price) : null
  form.stock = p.stock
  form.warranty_years = p.warranty_years
  form.primary_image = p.images?.[0]?.image_url || ''
  form.short_description = p.short_description || ''
  form.description = p.description || ''
  isModalOpen.value = true
}

async function saveProduct() {
  try {
    const payload = {
      ...form,
      images: form.primary_image ? [form.primary_image] : [],
    }

    if (editingId.value) {
      await $fetch(`${apiBase}/admin/products/${editingId.value}`, {
        method: 'PUT',
        body: payload,
        headers: { Authorization: `Bearer ${authStore.token}` },
      })
      toast.show('Product updated successfully!', 'success')
    } else {
      await $fetch(`${apiBase}/admin/products`, {
        method: 'POST',
        body: payload,
        headers: { Authorization: `Bearer ${authStore.token}` },
      })
      toast.show('Product created successfully!', 'success')
    }

    isModalOpen.value = false
    loadProducts()
  } catch (err: any) {
    const msg = err.data?.message || 'Failed to save product'
    toast.show(msg, 'error')
  }
}

async function deleteProduct(id: number) {
  if (!confirm('Are you sure you want to delete this product?')) return
  try {
    await $fetch(`${apiBase}/admin/products/${id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${authStore.token}` },
    })
    toast.show('Product deleted', 'info')
    loadProducts()
  } catch (e) {
    toast.show('Failed to delete product', 'error')
  }
}

onMounted(() => {
  loadProducts()
  loadCategories()
})
</script>

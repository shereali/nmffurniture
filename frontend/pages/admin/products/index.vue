<template>
  <div>
    <div class="flex justify-between items-center" style="margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
      <div>
        <h1 style="font-size: 1.85rem; font-family: var(--font-sans); font-weight: 700; margin-bottom: 0.25rem;">
          Products Catalog
        </h1>
        <p style="font-size: 0.9rem; color: var(--color-text-muted);">
          Manage handcrafted furniture models, fabrics/variants, stock availability, and promotional pricing.
        </p>
      </div>

      <button @click="openCreateModal" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add New Product
      </button>
    </div>

    <!-- Filter & Search Bar -->
    <div style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 1.25rem; margin-bottom: 1.5rem; display: flex; gap: 1rem; align-items: center; justify-content: space-between; flex-wrap: wrap;">
      <div class="flex gap-2 flex-wrap" style="flex: 1 1 500px;">
        <!-- Search Input -->
        <div style="position: relative; min-width: 220px; flex-grow: 1;">
          <input
            v-model="searchQuery"
            @input="handleSearch"
            type="text"
            placeholder="Search name or SKU..."
            class="form-input"
            style="padding-left: 2.25rem;"
          />
          <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); color: var(--color-text-light);"></i>
        </div>

        <!-- Category Dropdown -->
        <select v-model="selectedCategory" @change="applyFilter" class="form-select" style="min-width: 160px; max-width: 200px;">
          <option value="all">All Categories</option>
          <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
        </select>

        <!-- Stock Status Filter -->
        <select v-model="selectedStockStatus" @change="applyFilter" class="form-select" style="min-width: 140px; max-width: 180px;">
          <option value="all">All Inventory</option>
          <option value="low">Low Stock (≤ 10)</option>
          <option value="out">Out of Stock (0)</option>
        </select>
      </div>

      <button @click="loadProducts" class="btn btn-outline btn-sm">
        <i class="fa-solid fa-rotate-right"></i>
      </button>
    </div>

    <!-- Products Table -->
    <div class="data-table-card">
      <div v-if="loading" style="padding: 3rem; text-align: center; color: var(--color-text-muted);">
        <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 2rem; color: var(--color-secondary);"></i>
        <div style="margin-top: 0.75rem;">Loading catalog...</div>
      </div>

      <table v-else-if="filteredProducts.length > 0" class="data-table">
        <thead>
          <tr>
            <th>Product Details</th>
            <th>Category</th>
            <th>SKU</th>
            <th>Pricing (RM)</th>
            <th>Stock</th>
            <th>Status</th>
            <th>Warranty</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="p in filteredProducts" :key="p.id">
            <td>
              <div class="flex items-center gap-3">
                <img
                  :src="p.images?.[0]?.image_url || 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=150&q=80'"
                  :alt="p.name"
                  style="width: 52px; height: 52px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--color-border);"
                />
                <div>
                  <div style="font-weight: 700; color: var(--color-primary);">{{ p.name }}</div>
                  <div class="flex items-center gap-1" style="margin-top: 0.2rem;">
                    <span v-if="p.is_featured" style="font-size: 0.7rem; background: var(--color-secondary-light); color: var(--color-secondary-dark); padding: 0.1rem 0.4rem; border-radius: var(--radius-sm); font-weight: 700;">
                      Featured
                    </span>
                    <span v-if="p.variants?.length" style="font-size: 0.7rem; background: #F1F3F4; color: var(--color-text-muted); padding: 0.1rem 0.4rem; border-radius: var(--radius-sm);">
                      {{ p.variants.length }} options
                    </span>
                  </div>
                </div>
              </div>
            </td>
            <td>
              <span v-if="p.category" style="font-weight: 600;">{{ p.category.name }}</span>
              <span v-else style="color: var(--color-text-light);">Uncategorized</span>
            </td>
            <td style="font-family: monospace; font-size: 0.85rem; color: var(--color-text-muted);">
              {{ p.sku }}
            </td>
            <td style="font-weight: 700; font-family: var(--font-sans);">
              <div>RM {{ Number(p.discount_price ?? p.price).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</div>
              <span v-if="p.discount_price" style="font-size: 0.75rem; color: var(--color-text-light); text-decoration: line-through;">
                RM {{ Number(p.price).toFixed(2) }}
              </span>
            </td>
            <td>
              <span
                :style="{
                  color: p.stock === 0 ? 'var(--color-danger)' : p.stock <= 10 ? '#E65100' : 'inherit',
                  fontWeight: 700,
                  fontSize: '0.9rem'
                }"
              >
                {{ p.stock }} units
              </span>
            </td>
            <td>
              <button
                @click="toggleProductActive(p)"
                :class="['badge-status', p.is_active ? 'active' : 'inactive']"
                style="border: none; cursor: pointer;"
                title="Click to toggle active status"
              >
                {{ p.is_active ? 'Active' : 'Draft' }}
              </button>
            </td>
            <td style="font-size: 0.85rem; color: var(--color-text-muted);">
              {{ p.warranty_years }} Years
            </td>
            <td>
              <div class="flex gap-2">
                <NuxtLink
                  :to="`/shop/${p.slug}`"
                  target="_blank"
                  class="btn btn-outline btn-sm"
                  title="View on storefront"
                >
                  <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </NuxtLink>
                <button
                  @click="openEditModal(p)"
                  class="btn btn-outline btn-sm"
                  title="Edit product details"
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

      <div v-else style="padding: 3rem; text-align: center; color: var(--color-text-muted);">
        <i class="fa-solid fa-couch" style="font-size: 2.5rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
        <p>No products found matching the criteria.</p>
      </div>

      <!-- Pagination -->
      <div
        v-if="pagination.total > pagination.per_page"
        style="padding: 1rem 1.5rem; border-top: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center;"
      >
        <div style="font-size: 0.85rem; color: var(--color-text-muted);">
          Showing {{ ((pagination.current_page - 1) * pagination.per_page) + 1 }} to
          {{ Math.min(pagination.current_page * pagination.per_page, pagination.total) }} of {{ pagination.total }} models
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

    <!-- Product Create / Edit Modal -->
    <div
      v-if="isModalOpen"
      style="position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 500; display: flex; align-items: center; justify-content: center; padding: 1.5rem;"
      @click.self="isModalOpen = false"
    >
      <div style="background: #FFFFFF; width: 100%; max-width: 740px; border-radius: var(--radius-sm); padding: 2rem; max-height: 90vh; overflow-y: auto;">
        <div class="flex justify-between items-center" style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem;">
          <h3 style="font-size: 1.35rem; font-weight: 700; margin-bottom: 0;">
            {{ isEditing ? 'Edit Furniture Model' : 'Add New Furniture Model' }}
          </h3>
          <button
            @click="isModalOpen = false"
            style="background: transparent; border: none; font-size: 1.25rem; color: var(--color-text-light); cursor: pointer;"
          >
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>

        <form @submit.prevent="saveProduct" class="flex flex-col gap-4">
          <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
            <div class="form-group">
              <label class="form-label">Model Name *</label>
              <input v-model="form.name" type="text" class="form-input" required placeholder="e.g. English Modern Channel 4 Seater" />
            </div>

            <div class="form-group">
              <label class="form-label">SKU Code *</label>
              <input v-model="form.sku" type="text" class="form-input" required placeholder="e.g. ENMOCH4-P" />
            </div>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
            <div class="form-group">
              <label class="form-label">Category *</label>
              <select v-model="form.category_id" class="form-select" required>
                <option value="" disabled>Select category</option>
                <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label">Regular Price (RM) *</label>
              <input v-model.number="form.price" type="number" step="0.01" min="0" class="form-input" required />
            </div>

            <div class="form-group">
              <label class="form-label">Discount Price (RM)</label>
              <input v-model.number="form.discount_price" type="number" step="0.01" min="0" class="form-input" placeholder="Optional promo" />
            </div>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
            <div class="form-group">
              <label class="form-label">Inventory Stock *</label>
              <input v-model.number="form.stock" type="number" min="0" class="form-input" required />
            </div>

            <div class="form-group">
              <label class="form-label">Wood Warranty (Years)</label>
              <input v-model.number="form.warranty_years" type="number" min="0" class="form-input" />
            </div>

            <div class="form-group" style="display: flex; gap: 1.5rem; align-items: center; padding-top: 1.8rem;">
              <label style="display: flex; align-items: center; gap: 0.4rem; cursor: pointer; font-size: 0.85rem; font-weight: 600;">
                <input v-model="form.is_featured" type="checkbox" style="width: 16px; height: 16px;" /> Featured
              </label>
              <label style="display: flex; align-items: center; gap: 0.4rem; cursor: pointer; font-size: 0.85rem; font-weight: 600;">
                <input v-model="form.is_active" type="checkbox" style="width: 16px; height: 16px;" /> Active
              </label>
            </div>
          </div>

          <!-- Product Media Image URLs -->
          <div class="form-group">
            <div class="flex justify-between items-center" style="margin-bottom: 0.5rem;">
              <label class="form-label" style="margin-bottom: 0;">Showcase Image URLs</label>
              <button type="button" @click="addImageUrl" class="btn btn-outline btn-sm" style="font-size: 0.75rem; padding: 0.2rem 0.5rem;">
                <i class="fa-solid fa-plus"></i> Add Another Image
              </button>
            </div>

            <div class="flex flex-col gap-2">
              <div v-for="(img, idx) in form.images" :key="idx" class="flex gap-2 items-center">
                <input
                  v-model="form.images[idx]"
                  type="url"
                  class="form-input"
                  placeholder="https://images.unsplash.com/..."
                  required
                />
                <button
                  v-if="form.images.length > 1"
                  type="button"
                  @click="removeImageUrl(idx)"
                  class="btn btn-outline btn-sm"
                  style="color: var(--color-danger); border-color: var(--color-danger); padding: 0.4rem 0.6rem;"
                >
                  <i class="fa-regular fa-trash-can"></i>
                </button>
              </div>
            </div>

            <!-- Image Thumbnail Previews -->
            <div v-if="form.images.some(u => !!u)" class="flex gap-2 flex-wrap" style="margin-top: 0.75rem;">
              <img
                v-for="(img, idx) in form.images.filter(u => !!u)"
                :key="idx"
                :src="img"
                alt="Preview"
                style="width: 60px; height: 60px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--color-border);"
              />
            </div>
          </div>

          <!-- Variant / Fabric Options -->
          <div class="form-group">
            <div class="flex justify-between items-center" style="margin-bottom: 0.5rem;">
              <label class="form-label" style="margin-bottom: 0;">Product Options & Swatches</label>
              <button type="button" @click="addVariant" class="btn btn-outline btn-sm" style="font-size: 0.75rem; padding: 0.2rem 0.5rem;">
                <i class="fa-solid fa-plus"></i> Add Option
              </button>
            </div>

            <div v-for="(v, idx) in form.variants" :key="idx" style="background: var(--color-bg-alt); padding: 0.75rem; border-radius: var(--radius-sm); margin-bottom: 0.5rem; display: grid; grid-template-columns: 1fr 1fr 1fr 1fr auto; gap: 0.5rem; align-items: center;">
              <input v-model="v.name" type="text" class="form-input" placeholder="Type (e.g. Fabric)" style="font-size: 0.85rem;" />
              <input v-model="v.option" type="text" class="form-input" placeholder="Option (e.g. Boucle)" style="font-size: 0.85rem;" />
              <input v-model.number="v.price_adjustment" type="number" step="0.01" class="form-input" placeholder="+RM" style="font-size: 0.85rem;" />
              <div class="flex items-center gap-1">
                <input v-model="v.color_code" type="color" style="width: 32px; height: 32px; padding: 0; border: none; cursor: pointer;" />
                <input v-model="v.color_name" type="text" class="form-input" placeholder="Color Name" style="font-size: 0.85rem;" />
              </div>
              <button type="button" @click="removeVariant(idx)" style="background: transparent; border: none; color: var(--color-danger); cursor: pointer;">
                <i class="fa-regular fa-trash-can"></i>
              </button>
            </div>
          </div>

          <!-- Dimensions & Materials -->
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
              <label class="form-label">Dimensions</label>
              <input v-model="form.dimensions" type="text" class="form-input" placeholder="W 260cm x D 95cm x H 85cm" />
            </div>

            <div class="form-group">
              <label class="form-label">Materials</label>
              <input v-model="form.materials" type="text" class="form-input" placeholder="Solid Hardwood Chassis, High-Resilience Foam" />
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Short Description</label>
            <input v-model="form.short_description" type="text" class="form-input" placeholder="Summary for product cards..." />
          </div>

          <div class="form-group">
            <label class="form-label">Full Story & Craftsmanship Description</label>
            <textarea v-model="form.description" class="form-textarea" rows="3" placeholder="Detailed architectural description..."></textarea>
          </div>

          <div class="flex justify-end gap-3" style="margin-top: 1rem; border-top: 1px solid var(--color-border); padding-top: 1rem;">
            <button type="button" @click="isModalOpen = false" class="btn btn-outline">Cancel</button>
            <button type="submit" class="btn btn-primary">
              {{ isEditing ? 'Save Changes' : 'Create Product' }}
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
const categories = ref<any[]>([])
const loading = ref(false)
const searchQuery = ref('')
const selectedCategory = ref('all')
const selectedStockStatus = ref('all')
const isModalOpen = ref(false)
const isEditing = ref(false)

const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 20,
  total: 0,
})

const form = ref({
  id: null as number | null,
  category_id: '' as any,
  name: '',
  sku: '',
  price: 0,
  discount_price: null as number | null,
  stock: 50,
  is_featured: false,
  is_active: true,
  warranty_years: 5,
  dimensions: '',
  materials: '',
  short_description: '',
  description: '',
  images: [''],
  variants: [] as any[],
})

let searchTimer: any = null
function handleSearch() {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => {
    pagination.value.current_page = 1
    loadProducts()
  }, 350)
}

function applyFilter() {
  pagination.value.current_page = 1
  loadProducts()
}

function changePage(page: number) {
  pagination.value.current_page = page
  loadProducts()
}

function addImageUrl() {
  form.value.images.push('')
}

function removeImageUrl(idx: number) {
  form.value.images.splice(idx, 1)
}

function addVariant() {
  form.value.variants.push({
    name: 'Fabric Type',
    option: '',
    price_adjustment: 0,
    color_code: '#C5A880',
    color_name: '',
  })
}

function removeVariant(idx: number) {
  form.value.variants.splice(idx, 1)
}

const filteredProducts = computed(() => {
  if (selectedStockStatus.value === 'low') {
    return products.value.filter(p => p.stock > 0 && p.stock <= 10)
  }
  if (selectedStockStatus.value === 'out') {
    return products.value.filter(p => p.stock === 0)
  }
  return products.value
})

function openCreateModal() {
  isEditing.value = false
  form.value = {
    id: null,
    category_id: categories.value[0]?.id || '',
    name: '',
    sku: '',
    price: 0,
    discount_price: null,
    stock: 20,
    is_featured: false,
    is_active: true,
    warranty_years: 5,
    dimensions: '',
    materials: '',
    short_description: '',
    description: '',
    images: ['https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1200&q=80'],
    variants: [],
  }
  isModalOpen.value = true
}

function openEditModal(p: any) {
  isEditing.value = true
  form.value = {
    id: p.id,
    category_id: p.category_id,
    name: p.name,
    sku: p.sku,
    price: Number(p.price),
    discount_price: p.discount_price ? Number(p.discount_price) : null,
    stock: p.stock,
    is_featured: Boolean(p.is_featured),
    is_active: Boolean(p.is_active),
    warranty_years: p.warranty_years || 5,
    dimensions: p.dimensions || '',
    materials: p.materials || '',
    short_description: p.short_description || '',
    description: p.description || '',
    images: p.images?.length ? p.images.map((im: any) => im.image_url) : [''],
    variants: p.variants?.length ? JSON.parse(JSON.stringify(p.variants)) : [],
  }
  isModalOpen.value = true
}

async function toggleProductActive(p: any) {
  if (!authStore.token) return
  const newActive = !p.is_active
  try {
    await $fetch(`${apiBase}/admin/products/${p.id}`, {
      method: 'PUT',
      headers: { Authorization: `Bearer ${authStore.token}` },
      body: { is_active: newActive },
    })
    p.is_active = newActive
    toast.show(`Product set to ${newActive ? 'Active' : 'Draft'}`, 'info')
  } catch (e: any) {
    toast.show('Failed to toggle status', 'error')
  }
}

async function loadProducts() {
  if (!authStore.token) return
  loading.value = true
  try {
    const params: any = {
      page: pagination.value.current_page,
    }
    if (searchQuery.value) {
      params.search = searchQuery.value
    }
    if (selectedCategory.value !== 'all') {
      params.category_id = selectedCategory.value
    }

    const res: any = await $fetch(`${apiBase}/admin/products`, {
      headers: { Authorization: `Bearer ${authStore.token}` },
      params,
    })

    products.value = res.data || []
    pagination.value.current_page = res.current_page || 1
    pagination.value.last_page = res.last_page || 1
    pagination.value.per_page = res.per_page || 20
    pagination.value.total = res.total || 0
  } catch (e: any) {
    toast.show('Failed to load products', 'error')
  } finally {
    loading.value = false
  }
}

async function loadCategories() {
  try {
    const res: any = await $fetch(`${apiBase}/categories`)
    categories.value = res.categories || []
  } catch (e) {
    // silently catch
  }
}

async function saveProduct() {
  if (!authStore.token) return
  const payload = {
    ...form.value,
    images: form.value.images.filter(img => img.trim().length > 0),
  }

  try {
    if (isEditing.value && form.value.id) {
      await $fetch(`${apiBase}/admin/products/${form.value.id}`, {
        method: 'PUT',
        headers: { Authorization: `Bearer ${authStore.token}` },
        body: payload,
      })
      toast.show('Product updated successfully', 'success')
    } else {
      await $fetch(`${apiBase}/admin/products`, {
        method: 'POST',
        headers: { Authorization: `Bearer ${authStore.token}` },
        body: payload,
      })
      toast.show('Product created successfully', 'success')
    }

    isModalOpen.value = false
    loadProducts()
  } catch (e: any) {
    toast.show(e.data?.message || 'Failed to save product', 'error')
  }
}

async function deleteProduct(id: number) {
  if (!confirm('Are you sure you want to delete this product model?')) return
  if (!authStore.token) return
  try {
    await $fetch(`${apiBase}/admin/products/${id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${authStore.token}` },
    })
    toast.show('Product deleted successfully', 'info')
    loadProducts()
  } catch (e: any) {
    toast.show('Failed to delete product', 'error')
  }
}

onMounted(() => {
  loadCategories()
  loadProducts()
})
</script>

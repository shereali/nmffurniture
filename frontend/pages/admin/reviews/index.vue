<template>
  <div class="admin-page-container">
    <!-- Header -->
    <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
      <div>
        <h1 style="font-size: 1.6rem; font-family: var(--font-heading); font-weight: 700; color: var(--color-primary); margin-bottom: 0.25rem;">
          Customer Reviews & Ratings
        </h1>
        <p style="font-size: 0.85rem; color: var(--color-text-muted); margin: 0;">
          Moderate customer product testimonials, ratings, and photo submissions.
        </p>
      </div>

      <div class="flex items-center gap-3">
        <button
          @click="loadReviews"
          class="btn btn-outline btn-sm"
          style="display: inline-flex; align-items: center; gap: 0.4rem;"
        >
          <i class="fa-solid fa-rotate-right" :class="{ 'fa-spin': loading }"></i>
          <span>Refresh</span>
        </button>
      </div>
    </div>

    <!-- Filters Bar -->
    <div class="admin-card mb-6" style="padding: 1rem 1.25rem;">
      <div class="flex items-center justify-between gap-4 flex-wrap">
        <div class="flex items-center gap-3 flex-wrap flex-1">
          <!-- Search -->
          <div style="position: relative; min-width: 240px; flex: 1;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); color: var(--color-text-light); font-size: 0.85rem;"></i>
            <input
              v-model="searchQuery"
              @input="onSearchInput"
              type="text"
              placeholder="Search by customer, product, comment..."
              class="form-input"
              style="padding-left: 2.25rem; font-size: 0.85rem;"
            />
          </div>

          <!-- Status Filter -->
          <select v-model="filterStatus" @change="loadReviews" class="form-input" style="width: auto; font-size: 0.85rem;">
            <option value="">All Statuses</option>
            <option value="1">Approved Reviews</option>
            <option value="0">Pending Moderation</option>
          </select>

          <!-- Rating Filter -->
          <select v-model="filterRating" @change="loadReviews" class="form-input" style="width: auto; font-size: 0.85rem;">
            <option value="">All Ratings</option>
            <option value="5">⭐⭐⭐⭐⭐ (5 Stars)</option>
            <option value="4">⭐⭐⭐⭐ (4 Stars)</option>
            <option value="3">⭐⭐⭐ (3 Stars)</option>
            <option value="2">⭐⭐ (2 Stars)</option>
            <option value="1">⭐ (1 Star)</option>
          </select>
        </div>

        <div style="font-size: 0.85rem; color: var(--color-text-muted); font-weight: 600;">
          Total: {{ totalReviews }} reviews
        </div>
      </div>
    </div>

    <!-- Reviews List -->
    <div v-if="loading" class="text-center py-12">
      <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 2rem; color: var(--color-secondary-dark);"></i>
      <p style="margin-top: 0.75rem; color: var(--color-text-muted); font-size: 0.9rem;">Loading reviews...</p>
    </div>

    <div v-else-if="reviews.length === 0" class="admin-card text-center py-12">
      <i class="fa-regular fa-star" style="font-size: 2.5rem; color: var(--color-border); margin-bottom: 0.75rem;"></i>
      <h3 style="font-size: 1.15rem; font-weight: 600; margin-bottom: 0.25rem;">No reviews found</h3>
      <p style="font-size: 0.85rem; color: var(--color-text-muted);">There are no customer reviews matching your active filter criteria.</p>
    </div>

    <div v-else class="flex flex-col gap-4">
      <div
        v-for="r in reviews"
        :key="r.id"
        class="admin-card"
        style="padding: 1.25rem; display: flex; flex-direction: column; gap: 0.85rem;"
      >
        <div class="flex items-start justify-between gap-4 flex-wrap">
          <div>
            <div class="flex items-center gap-2 flex-wrap">
              <span style="font-weight: 700; color: var(--color-primary); font-size: 0.95rem;">{{ r.customer_name }}</span>
              <span v-if="r.customer_email" style="font-size: 0.8rem; color: var(--color-text-muted);">({{ r.customer_email }})</span>
              <span v-if="r.is_verified" class="badge-pill badge-pill--emerald" style="font-size: 0.65rem; padding: 0.15rem 0.45rem;">
                <i class="fa-solid fa-circle-check"></i> Verified Homeowner
              </span>
              <span
                class="badge-pill"
                :class="r.is_approved ? 'badge-pill--gold' : 'badge-pill--dark'"
                style="font-size: 0.65rem; padding: 0.15rem 0.45rem;"
              >
                {{ r.is_approved ? 'Approved / Live' : 'Pending Approval' }}
              </span>
            </div>

            <!-- Product Ref -->
            <div v-if="r.product" style="font-size: 0.8rem; color: var(--color-text-muted); margin-top: 0.25rem;">
              Product: <strong style="color: var(--color-primary);">{{ r.product.name }}</strong> (SKU: {{ r.product.sku }})
            </div>
          </div>

          <!-- Date & Stars -->
          <div class="text-right">
            <div style="color: var(--color-secondary); font-size: 0.95rem;">
              <i v-for="s in 5" :key="s" class="fa-star" :class="s <= r.rating ? 'fa-solid' : 'fa-regular'"></i>
            </div>
            <div style="font-size: 0.75rem; color: var(--color-text-light); margin-top: 0.2rem;">
              {{ new Date(r.created_at).toLocaleDateString('en-MY', { day: 'numeric', month: 'short', year: 'numeric' }) }}
            </div>
          </div>
        </div>

        <!-- Review Title & Comment -->
        <div style="background: var(--color-bg-alt); padding: 0.85rem 1rem; border-radius: var(--radius-sm); border-left: 3px solid var(--color-secondary);">
          <div v-if="r.title" style="font-weight: 600; font-size: 0.9rem; margin-bottom: 0.35rem; color: var(--color-primary);">
            "{{ r.title }}"
          </div>
          <p style="font-size: 0.85rem; color: var(--color-text-main); margin: 0; line-height: 1.5;">
            {{ r.comment }}
          </p>
          <div v-if="r.image_url" style="margin-top: 0.75rem;">
            <img :src="r.image_url" alt="Customer photo" style="height: 70px; border-radius: 4px; object-fit: cover; border: 1px solid var(--color-border);" />
          </div>
        </div>

        <!-- Action Bar -->
        <div class="flex items-center justify-end gap-3 pt-2" style="border-top: 1px solid rgba(0, 0, 0, 0.05);">
          <button
            @click="toggleApproval(r)"
            class="btn btn-sm"
            :class="r.is_approved ? 'btn-outline' : 'btn-primary'"
            style="font-size: 0.75rem; padding: 0.35rem 0.75rem;"
          >
            <i class="fa-solid" :class="r.is_approved ? 'fa-eye-slash' : 'fa-check'"></i>
            {{ r.is_approved ? 'Unpublish / Hide' : 'Approve & Publish' }}
          </button>

          <button
            @click="deleteReview(r.id)"
            class="btn btn-outline btn-sm"
            style="color: #E74C3C; border-color: rgba(231, 76, 60, 0.4); font-size: 0.75rem; padding: 0.35rem 0.75rem;"
          >
            <i class="fa-solid fa-trash-can"></i> Delete
          </button>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="totalPages > 1" class="flex justify-center items-center gap-2 mt-4">
        <button
          :disabled="currentPage === 1"
          @click="changePage(currentPage - 1)"
          class="btn btn-outline btn-sm"
        >
          Previous
        </button>
        <span style="font-size: 0.85rem; font-weight: 600; padding: 0 0.75rem;">
          Page {{ currentPage }} of {{ totalPages }}
        </span>
        <button
          :disabled="currentPage === totalPages"
          @click="changePage(currentPage + 1)"
          class="btn btn-outline btn-sm"
        >
          Next
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'

definePageMeta({
  layout: 'admin',
  middleware: ['auth']
})

const authStore = useAuthStore()
const config = useRuntimeConfig()
const apiBase = config.public.apiBase || 'http://localhost:8001/api'

const reviews = ref<any[]>([])
const loading = ref(true)
const totalReviews = ref(0)
const currentPage = ref(1)
const totalPages = ref(1)

const searchQuery = ref('')
const filterStatus = ref('')
const filterRating = ref('')

let searchDebounce: any = null

function onSearchInput() {
  clearTimeout(searchDebounce)
  searchDebounce = setTimeout(() => {
    currentPage.value = 1
    loadReviews()
  }, 300)
}

async function loadReviews() {
  loading.value = true
  try {
    const token = authStore.token
    const params = new URLSearchParams()
    params.append('page', currentPage.value.toString())
    if (searchQuery.value.trim()) params.append('search', searchQuery.value.trim())
    if (filterStatus.value !== '') params.append('is_approved', filterStatus.value)
    if (filterRating.value) params.append('rating', filterRating.value)

    const res: any = await $fetch(`${apiBase}/admin/reviews?${params.toString()}`, {
      headers: { Authorization: `Bearer ${token}` }
    })

    reviews.value = res.data || []
    totalReviews.value = res.total || 0
    currentPage.value = res.current_page || 1
    totalPages.value = res.last_page || 1
  } catch (err) {
    console.error('Failed to load reviews', err)
  } finally {
    loading.value = false
  }
}

async function toggleApproval(review: any) {
  try {
    const token = authStore.token
    await $fetch(`${apiBase}/admin/reviews/${review.id}/status`, {
      method: 'PATCH',
      headers: { Authorization: `Bearer ${token}` },
      body: { is_approved: !review.is_approved }
    })
    review.is_approved = !review.is_approved
  } catch (err) {
    alert('Failed to update review status')
  }
}

async function deleteReview(id: number) {
  if (!confirm('Are you sure you want to delete this customer review permanently?')) return
  try {
    const token = authStore.token
    await $fetch(`${apiBase}/admin/reviews/${id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${token}` }
    })
    reviews.value = reviews.value.filter(r => r.id !== id)
    totalReviews.value--
  } catch (err) {
    alert('Failed to delete review')
  }
}

function changePage(page: number) {
  currentPage.value = page
  loadReviews()
}

onMounted(() => {
  loadReviews()
})
</script>

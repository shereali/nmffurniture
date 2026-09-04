<template>
  <header class="site-header">
    <!-- Announcement Bar -->
    <div class="announcement-bar">
      <span>5-Year Solid Wood Warranty on All Sofas</span>
      <span class="sep">•</span>
      <span>In-House Malaysian Manufacturer</span>
      <span class="sep">•</span>
      <NuxtLink to="/our-showroom">Visit Showrooms in Shah Alam & PJ →</NuxtLink>
    </div>

    <!-- Navigation Bar -->
    <nav class="nav-container">
      <!-- Logo -->
      <NuxtLink to="/" class="brand-logo">
        NMF <span class="accent">FURNITURE</span>
        <span class="brand-badge">SDN BHD</span>
      </NuxtLink>

      <!-- Desktop Navigation Menu -->
      <div class="nav-menu" style="display: flex;">
        <div class="dropdown">
          <NuxtLink to="/shop" class="nav-link">
            CATEGORY <i class="fa-solid fa-chevron-down" style="font-size: 0.7rem; margin-left: 0.25rem;"></i>
          </NuxtLink>
          <div class="dropdown-menu">
            <NuxtLink to="/shop" class="dropdown-item">
              <i class="fa-solid fa-border-all" style="width: 20px; color: var(--color-secondary-dark);"></i> All Products
            </NuxtLink>
            <NuxtLink to="/shop?category=sofa" class="dropdown-item">
              <i class="fa-solid fa-couch" style="width: 20px; color: var(--color-secondary-dark);"></i> Sofas & Lounges
            </NuxtLink>
            <NuxtLink to="/shop?category=wing-chair" class="dropdown-item">
              <i class="fa-solid fa-chair" style="width: 20px; color: var(--color-secondary-dark);"></i> Wing Chairs
            </NuxtLink>
            <NuxtLink to="/shop?category=coffee-table" class="dropdown-item">
              <i class="fa-solid fa-table" style="width: 20px; color: var(--color-secondary-dark);"></i> Coffee Tables
            </NuxtLink>
            <NuxtLink to="/shop?category=bed-frame" class="dropdown-item">
              <i class="fa-solid fa-bed" style="width: 20px; color: var(--color-secondary-dark);"></i> Bed Frames
            </NuxtLink>
            <NuxtLink to="/shop?category=dining-table" class="dropdown-item">
              <i class="fa-solid fa-utensils" style="width: 20px; color: var(--color-secondary-dark);"></i> Dining Sets
            </NuxtLink>
          </div>
        </div>

        <NuxtLink to="/our-showroom" class="nav-link">
          OUR SHOWROOM
        </NuxtLink>

        <NuxtLink to="/gallery" class="nav-link">
          GALLERY
        </NuxtLink>

        <NuxtLink to="/track-order" class="nav-link">
          TRACK ORDER
        </NuxtLink>
      </div>

      <!-- Nav Actions -->
      <div class="nav-actions">
        <!-- Live Search Trigger -->
        <button
          @click="openSearch"
          class="action-btn"
          title="Instant Search"
          aria-label="Search furniture catalog"
        >
          <i class="fa-solid fa-magnifying-glass"></i>
        </button>

        <!-- Wishlist Link with Live Badge -->
        <NuxtLink
          to="/wishlist"
          class="action-btn"
          title="My Saved Wishlist"
          aria-label="View Saved Wishlist"
          style="position: relative;"
        >
          <i class="fa-regular fa-heart"></i>
          <span v-if="wishlistStore.count > 0" class="action-badge" style="background-color: #E11D48;">
            {{ wishlistStore.count }}
          </span>
        </NuxtLink>

        <!-- User / Admin -->
        <template v-if="authStore.isAuthenticated">
          <NuxtLink v-if="authStore.isAdmin" to="/admin" class="btn btn-outline-gold btn-sm" style="font-size: 0.75rem;">
            <i class="fa-solid fa-gauge"></i> Admin Panel
          </NuxtLink>
          <NuxtLink v-else to="/account/orders" class="action-btn" title="My Account" aria-label="View my account orders">
            <i class="fa-regular fa-user"></i>
          </NuxtLink>
        </template>
        <template v-else>
          <NuxtLink to="/auth/login" class="btn btn-outline btn-sm">
            Sign In
          </NuxtLink>
        </template>

        <!-- Cart Bag Trigger -->
        <button
          @click="cartStore.toggleDrawer(true)"
          class="action-btn"
          title="View Bag"
          aria-label="View Shopping Bag"
          style="background-color: var(--color-bg-muted);"
        >
          <i class="fa-solid fa-bag-shopping"></i>
          <span v-if="cartStore.count > 0" class="action-badge">
            {{ cartStore.count }}
          </span>
        </button>

        <!-- Mobile Menu Toggle Button -->
        <button
          @click="isMobileMenuOpen = !isMobileMenuOpen"
          class="action-btn"
          style="display: none;"
          id="mobile-toggle"
          :aria-expanded="isMobileMenuOpen"
          aria-label="Toggle navigation menu"
        >
          <i :class="isMobileMenuOpen ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'"></i>
        </button>
      </div>
    </nav>

    <!-- Mobile Drawer Menu -->
    <div
      v-if="isMobileMenuOpen"
      class="mobile-nav-panel animate-fade-in"
    >
      <NuxtLink to="/shop" @click="isMobileMenuOpen = false" class="mobile-nav-link">
        <i class="fa-solid fa-border-all" style="width: 22px; color: var(--color-secondary-dark);"></i> All Products
      </NuxtLink>
      <NuxtLink to="/shop?category=sofa" @click="isMobileMenuOpen = false" class="mobile-nav-link">
        <i class="fa-solid fa-couch" style="width: 22px; color: var(--color-secondary-dark);"></i> Sofas & Sectionals
      </NuxtLink>
      <NuxtLink to="/shop?category=bed-frame" @click="isMobileMenuOpen = false" class="mobile-nav-link">
        <i class="fa-solid fa-bed" style="width: 22px; color: var(--color-secondary-dark);"></i> Bed Frames
      </NuxtLink>
      <NuxtLink to="/shop?category=dining-table" @click="isMobileMenuOpen = false" class="mobile-nav-link">
        <i class="fa-solid fa-utensils" style="width: 22px; color: var(--color-secondary-dark);"></i> Dining Sets
      </NuxtLink>
      <NuxtLink to="/wishlist" @click="isMobileMenuOpen = false" class="mobile-nav-link">
        <i class="fa-solid fa-heart" style="width: 22px; color: #E11D48;"></i> Saved Wishlist ({{ wishlistStore.count }})
      </NuxtLink>
      <NuxtLink to="/track-order" @click="isMobileMenuOpen = false" class="mobile-nav-link">
        <i class="fa-solid fa-truck-fast" style="width: 22px; color: var(--color-secondary-dark);"></i> Track Delivery
      </NuxtLink>
      <NuxtLink to="/our-showroom" @click="isMobileMenuOpen = false" class="mobile-nav-link">
        <i class="fa-solid fa-location-dot" style="width: 22px; color: var(--color-secondary-dark);"></i> Showrooms (Shah Alam & PJ)
      </NuxtLink>
      <NuxtLink to="/gallery" @click="isMobileMenuOpen = false" class="mobile-nav-link">
        <i class="fa-solid fa-images" style="width: 22px; color: var(--color-secondary-dark);"></i> Project Lookbook
      </NuxtLink>

      <div v-if="authStore.isAuthenticated" class="flex gap-2" style="margin-top: 0.75rem; border-top: 1px solid var(--color-border); padding-top: 1rem;">
        <NuxtLink v-if="authStore.isAdmin" to="/admin" @click="isMobileMenuOpen = false" class="btn btn-secondary btn-sm flex-1">Admin Panel</NuxtLink>
        <NuxtLink v-else to="/account/orders" @click="isMobileMenuOpen = false" class="btn btn-outline btn-sm flex-1">My Orders</NuxtLink>
        <button @click="authStore.logout(); isMobileMenuOpen = false;" class="btn btn-outline btn-sm">Logout</button>
      </div>
      <div v-else style="margin-top: 0.75rem; border-top: 1px solid var(--color-border); padding-top: 1rem;">
        <NuxtLink to="/auth/login" @click="isMobileMenuOpen = false" class="btn btn-primary btn-sm" style="width: 100%;">Sign In / Register</NuxtLink>
      </div>
    </div>

    <!-- Live Search Overlay Modal -->
    <div
      v-if="isSearchOpen"
      class="search-modal-backdrop"
      @click.self="isSearchOpen = false"
    >
      <div class="search-modal-box animate-slide-up">
        <!-- Search Input Header -->
        <div class="search-input-wrap">
          <i class="fa-solid fa-magnifying-glass search-icon"></i>
          <input
            ref="searchInputRef"
            type="text"
            v-model="searchQuery"
            @input="onSearchInput"
            placeholder="Search Malaysian sofas, Meranti dining tables, wing chairs, SKU..."
            class="search-input"
            @keydown.esc="isSearchOpen = false"
            @keydown.enter="navigateToShopSearch"
          />
          <button
            v-if="searchQuery"
            @click="searchQuery = ''; searchResults = []"
            class="search-clear-btn"
            title="Clear search"
          >
            <i class="fa-solid fa-xmark"></i>
          </button>
          <button
            @click="isSearchOpen = false"
            class="search-close-btn"
            title="Close Search (ESC)"
          >
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>

        <!-- Quick Suggestions Pill Bar -->
        <div class="search-suggestions-bar">
          <span style="font-size: 0.75rem; font-weight: 700; color: var(--color-text-muted); text-transform: uppercase;">Trending:</span>
          <button
            v-for="tag in ['Chesterfield Sofa', 'Meranti Dining', 'Wing Chair', 'Boucle Lounge', 'Bed Frame']"
            :key="tag"
            @click="selectSuggestedTag(tag)"
            class="suggestion-pill"
          >
            {{ tag }}
          </button>
        </div>

        <!-- Results / Loading Body -->
        <div class="search-results-area">
          <div v-if="isSearching" class="text-center py-8">
            <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 1.75rem; color: var(--color-secondary-dark);"></i>
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-top: 0.5rem;">Searching catalogue...</p>
          </div>

          <div v-else-if="searchQuery && searchResults.length === 0" class="text-center py-8">
            <p style="color: var(--color-text-muted); font-size: 0.95rem; margin-bottom: 0.5rem;">
              No bespoke furniture found matching "<strong>{{ searchQuery }}</strong>"
            </p>
            <NuxtLink
              to="/shop"
              @click="isSearchOpen = false"
              class="btn btn-outline btn-sm"
            >
              Browse All Collections
            </NuxtLink>
          </div>

          <div v-else-if="searchResults.length > 0">
            <div style="font-size: 0.8rem; font-weight: 700; color: var(--color-text-muted); margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
              Products Found ({{ searchResults.length }})
            </div>
            <div class="search-results-list">
              <NuxtLink
                v-for="item in searchResults"
                :key="item.id"
                :to="`/shop/${item.slug}`"
                @click="isSearchOpen = false"
                class="search-result-row"
              >
                <div class="search-thumb">
                  <img
                    :src="item.images?.[0]?.image_url || 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=150&q=80'"
                    :alt="item.name"
                  />
                </div>
                <div class="search-item-info">
                  <div class="search-item-cat">{{ item.category?.name || 'Handcrafted' }} • SKU: {{ item.sku }}</div>
                  <div class="search-item-title">{{ item.name }}</div>
                  <div class="search-item-price">
                    RM {{ Number(item.discount_price ?? item.price).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
                    <span v-if="item.discount_price" class="search-item-orig">
                      RM {{ Number(item.price).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
                    </span>
                  </div>
                </div>
                <div class="search-item-arrow">
                  <i class="fa-solid fa-arrow-right"></i>
                </div>
              </NuxtLink>
            </div>

            <div class="text-center" style="margin-top: 1rem; padding-top: 0.75rem; border-top: 1px solid var(--color-border);">
              <NuxtLink
                :to="`/shop?search=${encodeURIComponent(searchQuery)}`"
                @click="isSearchOpen = false"
                class="btn btn-outline btn-sm"
                style="width: 100%;"
              >
                View all results for "{{ searchQuery }}" →
              </NuxtLink>
            </div>
          </div>

          <div v-else class="search-empty-prompt">
            <i class="fa-solid fa-couch" style="font-size: 2rem; color: var(--color-secondary); margin-bottom: 0.5rem;"></i>
            <p style="font-size: 0.9rem; color: var(--color-text-muted); margin-bottom: 0;">
              Type product name, material, or category to find handcrafted Malaysian furniture.
            </p>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'
import { useCartStore } from '~/stores/cart'
import { useWishlistStore } from '~/stores/wishlist'

const authStore = useAuthStore()
const cartStore = useCartStore()
const wishlistStore = useWishlistStore()
const config = useRuntimeConfig()
const apiBase = config.public.apiBase
const router = useRouter()

const isMobileMenuOpen = ref(false)

// Instant Search State
const isSearchOpen = ref(false)
const searchQuery = ref('')
const searchResults = ref<any[]>([])
const isSearching = ref(false)
const searchInputRef = ref<HTMLInputElement | null>(null)
let debounceTimeout: any = null

function openSearch() {
  isSearchOpen.value = true
  nextTick(() => {
    searchInputRef.value?.focus()
  })
}

function onSearchInput() {
  clearTimeout(debounceTimeout)
  if (!searchQuery.value.trim()) {
    searchResults.value = []
    isSearching.value = false
    return
  }

  isSearching.value = true
  debounceTimeout = setTimeout(async () => {
    try {
      const res: any = await $fetch(`${apiBase}/products?search=${encodeURIComponent(searchQuery.value)}&per_page=6`)
      searchResults.value = res.data || []
    } catch (e) {
      console.error('Search error', e)
      searchResults.value = []
    } finally {
      isSearching.value = false
    }
  }, 250)
}

function selectSuggestedTag(tag: string) {
  searchQuery.value = tag
  onSearchInput()
}

function navigateToShopSearch() {
  if (searchQuery.value.trim()) {
    isSearchOpen.value = false
    router.push(`/shop?search=${encodeURIComponent(searchQuery.value.trim())}`)
  }
}

onMounted(() => {
  authStore.init()
  cartStore.init()
  wishlistStore.init()
})
</script>

<style scoped>
.mobile-nav-panel {
  background: #FFFFFF;
  border-top: 1px solid var(--color-border);
  padding: 1.25rem 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  box-shadow: var(--shadow-lg);
}

.mobile-nav-link {
  font-family: var(--font-sans);
  font-size: 0.95rem;
  font-weight: 600;
  color: var(--color-text-main);
  padding: 0.6rem 0;
  display: flex;
  align-items: center;
  border-bottom: 1px solid rgba(232, 228, 218, 0.5);
  transition: color var(--transition-fast);
}

.mobile-nav-link:hover {
  color: var(--color-secondary-dark);
}

/* Search Modal Styles */
.search-modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.6);
  backdrop-filter: blur(5px);
  z-index: 1200;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding: 3rem 1rem 1rem;
}

.search-modal-box {
  background: #FFFFFF;
  border-radius: var(--radius-sm);
  width: 100%;
  max-width: 680px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
  overflow: hidden;
  border: 1px solid var(--color-border);
}

.search-input-wrap {
  display: flex;
  align-items: center;
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid var(--color-border);
  gap: 1rem;
  background: #FFFFFF;
}

.search-icon {
  font-size: 1.25rem;
  color: var(--color-secondary-dark);
}

.search-input {
  flex: 1;
  border: none;
  font-size: 1.1rem;
  font-family: var(--font-sans);
  outline: none;
  color: var(--color-text-main);
  background: transparent;
}

.search-clear-btn,
.search-close-btn {
  background: none;
  border: none;
  font-size: 1.15rem;
  color: var(--color-text-light);
  cursor: pointer;
  padding: 0.25rem;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: color 0.2s;
}

.search-clear-btn:hover,
.search-close-btn:hover {
  color: var(--color-primary);
}

.search-suggestions-bar {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  background: var(--color-bg-alt);
  border-bottom: 1px solid var(--color-border);
  overflow-x: auto;
  white-space: nowrap;
}

.suggestion-pill {
  background: #FFFFFF;
  border: 1px solid var(--color-border);
  padding: 0.25rem 0.65rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  color: var(--color-text-main);
  cursor: pointer;
  transition: all 0.2s ease;
}

.suggestion-pill:hover {
  border-color: var(--color-primary);
  color: var(--color-primary);
  background: #FAFAFA;
}

.search-results-area {
  padding: 1.25rem 1.5rem;
  max-height: 480px;
  overflow-y: auto;
}

.search-results-list {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.search-result-row {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 0.6rem;
  border-radius: var(--radius-sm);
  text-decoration: none;
  color: inherit;
  transition: background 0.15s ease;
}

.search-result-row:hover {
  background: var(--color-bg-alt);
}

.search-thumb {
  width: 60px;
  height: 50px;
  border-radius: 4px;
  overflow: hidden;
  background: var(--color-bg-muted);
  flex-shrink: 0;
}

.search-thumb img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.search-item-info {
  flex: 1;
}

.search-item-cat {
  font-size: 0.72rem;
  font-weight: 700;
  color: var(--color-secondary-dark);
  text-transform: uppercase;
}

.search-item-title {
  font-size: 0.95rem;
  font-weight: 600;
  color: var(--color-primary);
  line-height: 1.3;
}

.search-item-price {
  font-size: 0.85rem;
  font-weight: 700;
  color: var(--color-primary);
}

.search-item-orig {
  font-size: 0.75rem;
  color: var(--color-text-light);
  text-decoration: line-through;
  margin-left: 0.35rem;
}

.search-item-arrow {
  color: var(--color-text-light);
  font-size: 0.85rem;
  transition: transform 0.2s;
}

.search-result-row:hover .search-item-arrow {
  color: var(--color-primary);
  transform: translateX(4px);
}

.search-empty-prompt {
  text-align: center;
  padding: 2.5rem 1rem;
}

@media (max-width: 860px) {
  .nav-menu {
    display: none !important;
  }
  #mobile-toggle {
    display: flex !important;
  }
}
</style>

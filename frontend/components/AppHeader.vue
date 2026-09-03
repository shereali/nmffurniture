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
      </div>

      <!-- Nav Actions -->
      <div class="nav-actions">
        <!-- Search Quick Link -->
        <NuxtLink to="/shop" class="action-btn" title="Search catalog" aria-label="Search furniture catalog">
          <i class="fa-solid fa-magnifying-glass"></i>
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

    <!-- Mobile Drawer Menu with Transition -->
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
  </header>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'
import { useCartStore } from '~/stores/cart'

const authStore = useAuthStore()
const cartStore = useCartStore()
const isMobileMenuOpen = ref(false)

onMounted(() => {
  authStore.init()
  cartStore.init()
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

@media (max-width: 860px) {
  .nav-menu {
    display: none !important;
  }
  #mobile-toggle {
    display: flex !important;
  }
}
</style>

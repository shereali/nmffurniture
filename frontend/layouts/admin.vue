<template>
  <div class="admin-layout">
    <!-- Mobile Backdrop -->
    <div
      v-if="isMobileMenuOpen"
      @click="isMobileMenuOpen = false"
      style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 490;"
    ></div>

    <!-- Sidebar -->
    <aside class="admin-sidebar" :class="{ open: isMobileMenuOpen }">
      <div class="admin-sidebar-header justify-between">
        <div class="flex items-center gap-3">
          <i class="fa-solid fa-couch" style="color: var(--color-secondary); font-size: 1.25rem;"></i>
          <div>
            <div class="admin-sidebar-brand">NMF ADMIN</div>
            <div style="font-size: 0.7rem; color: #8E939A; letter-spacing: 0.05em;">FURNITURE STUDIO</div>
          </div>
        </div>
        <button
          v-if="isMobileMenuOpen"
          @click="isMobileMenuOpen = false"
          style="background: transparent; border: none; color: #FFFFFF; font-size: 1.1rem; cursor: pointer;"
        >
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <nav class="admin-sidebar-nav">
        <NuxtLink to="/admin" class="admin-nav-item" exact-active-class="active" @click="closeMobile">
          <i class="fa-solid fa-chart-line" style="width: 20px;"></i>
          <span>Dashboard Overview</span>
        </NuxtLink>

        <NuxtLink to="/admin/orders" class="admin-nav-item" active-class="active" @click="closeMobile">
          <i class="fa-solid fa-cart-shopping" style="width: 20px;"></i>
          <span>Orders Management</span>
          <span v-if="counts.pending_orders > 0" class="admin-badge-count warning">
            {{ counts.pending_orders }}
          </span>
        </NuxtLink>

        <NuxtLink to="/admin/inquiries" class="admin-nav-item" active-class="active" @click="closeMobile">
          <i class="fa-solid fa-envelope-open-text" style="width: 20px;"></i>
          <span>Inquiries & Leads</span>
          <span v-if="counts.new_inquiries > 0" class="admin-badge-count danger">
            {{ counts.new_inquiries }}
          </span>
        </NuxtLink>

        <NuxtLink to="/admin/products" class="admin-nav-item" active-class="active" @click="closeMobile">
          <i class="fa-solid fa-boxes-stacked" style="width: 20px;"></i>
          <span>Products Catalog</span>
        </NuxtLink>

        <NuxtLink to="/admin/categories" class="admin-nav-item" active-class="active" @click="closeMobile">
          <i class="fa-solid fa-layer-group" style="width: 20px;"></i>
          <span>Categories</span>
        </NuxtLink>

        <NuxtLink to="/admin/gallery" class="admin-nav-item" active-class="active" @click="closeMobile">
          <i class="fa-solid fa-images" style="width: 20px;"></i>
          <span>Showcase Gallery</span>
        </NuxtLink>

        <NuxtLink to="/admin/showrooms" class="admin-nav-item" active-class="active" @click="closeMobile">
          <i class="fa-solid fa-store" style="width: 20px;"></i>
          <span>Showroom Locations</span>
        </NuxtLink>

        <NuxtLink to="/admin/customers" class="admin-nav-item" active-class="active" @click="closeMobile">
          <i class="fa-solid fa-users" style="width: 20px;"></i>
          <span>Customer Directory</span>
        </NuxtLink>

        <NuxtLink to="/admin/coupons" class="admin-nav-item" active-class="active" @click="closeMobile">
          <i class="fa-solid fa-ticket" style="width: 20px;"></i>
          <span>Promo Vouchers</span>
        </NuxtLink>

        <div style="height: 1px; background: rgba(255, 255, 255, 0.1); margin: 0.75rem 0;"></div>

        <NuxtLink to="/" class="admin-nav-item" style="color: var(--color-secondary);" target="_blank">
          <i class="fa-solid fa-arrow-up-right-from-square" style="width: 20px;"></i>
          <span>View Public Store</span>
        </NuxtLink>
      </nav>

      <div style="padding: 1.25rem; border-top: 1px solid rgba(255, 255, 255, 0.1); display: flex; align-items: center; justify-content: space-between;">
        <div style="font-size: 0.85rem;">
          <div style="font-weight: 600; color: #FFFFFF;">{{ authStore.user?.name || 'Administrator' }}</div>
          <div style="font-size: 0.75rem; color: #8E939A;">Super Admin</div>
        </div>
        <button
          @click="handleLogout"
          title="Sign out"
          style="background: transparent; border: none; color: #E74C3C; cursor: pointer; font-size: 1rem;"
        >
          <i class="fa-solid fa-right-from-bracket"></i>
        </button>
      </div>
    </aside>

    <!-- Main Content Area -->
    <div class="admin-main">
      <header class="admin-topbar">
        <div class="flex items-center gap-3">
          <button
            @click="isMobileMenuOpen = !isMobileMenuOpen"
            class="btn btn-outline btn-sm lg:hidden"
            style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; padding: 0;"
          >
            <i class="fa-solid fa-bars"></i>
          </button>
          <div>
            <h2 style="font-size: 1.35rem; font-family: var(--font-sans); font-weight: 700; margin-bottom: 0;">
              Administration Portal
            </h2>
            <div style="font-size: 0.75rem; color: var(--color-text-muted);">
              NMFFurniture Operating Management
            </div>
          </div>
        </div>

        <div class="flex items-center gap-3">
          <span style="font-size: 0.75rem; background: #E8F5E9; color: #2E7D32; padding: 0.3rem 0.6rem; border-radius: var(--radius-sm); font-weight: 700; display: inline-flex; align-items: center; gap: 0.4rem;">
            <span style="width: 8px; height: 8px; border-radius: 50%; background: #2E7D32; display: inline-block;"></span>
            LIVE API
          </span>
          <NuxtLink to="/shop" class="btn btn-outline btn-sm" target="_blank">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> Storefront
          </NuxtLink>
        </div>
      </header>

      <div class="admin-content">
        <slot />
      </div>
    </div>

    <ToastNotification />
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'
import ToastNotification from '~/components/ToastNotification.vue'

const authStore = useAuthStore()
const router = useRouter()
const config = useRuntimeConfig()
const apiBase = config.public.apiBase

const isMobileMenuOpen = ref(false)
const counts = ref({
  pending_orders: 0,
  new_inquiries: 0,
})

function closeMobile() {
  isMobileMenuOpen.value = false
}

async function loadBadges() {
  if (!authStore.token) return
  try {
    const res: any = await $fetch(`${apiBase}/admin/dashboard`, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    if (res?.metrics) {
      counts.value.pending_orders = res.metrics.pending_orders || 0
      counts.value.new_inquiries = res.metrics.new_inquiries || 0
    }
  } catch (e) {
    // silently catch badge load error
  }
}

onMounted(() => {
  authStore.init()
  if (!authStore.isAuthenticated || !authStore.isAdmin) {
    router.push('/auth/login?redirect=/admin')
  } else {
    loadBadges()
  }
})

function handleLogout() {
  authStore.logout()
  router.push('/auth/login')
}
</script>

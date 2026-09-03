<template>
  <div class="admin-layout">
    <!-- Sidebar -->
    <aside class="admin-sidebar">
      <div class="admin-sidebar-header">
        <i class="fa-solid fa-couch" style="color: var(--color-secondary); font-size: 1.25rem;"></i>
        <div>
          <div class="admin-sidebar-brand">NMF ADMIN</div>
          <div style="font-size: 0.7rem; color: #8E939A; letter-spacing: 0.05em;">NMF MANAGEMENT</div>
        </div>
      </div>

      <nav class="admin-sidebar-nav">
        <NuxtLink to="/admin" class="admin-nav-item" exact-active-class="active">
          <i class="fa-solid fa-chart-line" style="width: 20px;"></i>
          <span>Dashboard Overview</span>
        </NuxtLink>

        <NuxtLink to="/admin/products" class="admin-nav-item" active-class="active">
          <i class="fa-solid fa-boxes-stacked" style="width: 20px;"></i>
          <span>Products Catalog</span>
        </NuxtLink>

        <NuxtLink to="/admin/categories" class="admin-nav-item" active-class="active">
          <i class="fa-solid fa-layer-group" style="width: 20px;"></i>
          <span>Categories</span>
        </NuxtLink>

        <NuxtLink to="/admin/orders" class="admin-nav-item" active-class="active">
          <i class="fa-solid fa-cart-shopping" style="width: 20px;"></i>
          <span>Orders Management</span>
        </NuxtLink>

        <NuxtLink to="/admin/customers" class="admin-nav-item" active-class="active">
          <i class="fa-solid fa-users" style="width: 20px;"></i>
          <span>Customer Leads</span>
        </NuxtLink>

        <div style="height: 1px; background: rgba(255, 255, 255, 0.1); margin: 0.75rem 0;"></div>

        <NuxtLink to="/" class="admin-nav-item" style="color: var(--color-secondary);">
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
          <h2 style="font-size: 1.35rem; font-family: var(--font-sans); font-weight: 700; margin-bottom: 0;">
            Administration Portal
          </h2>
          <span style="font-size: 0.75rem; background: #E8F5E9; color: #2E7D32; padding: 0.2rem 0.5rem; border-radius: var(--radius-sm); font-weight: 700;">
            LIVE API CONNECTED
          </span>
        </div>

        <div class="flex items-center gap-4">
          <NuxtLink to="/shop" class="btn btn-outline btn-sm" target="_blank">
            <i class="fa-solid fa-store"></i> Open Storefront
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

onMounted(() => {
  authStore.init()
  if (!authStore.isAuthenticated || !authStore.isAdmin) {
    router.push('/auth/login?redirect=/admin')
  }
})

function handleLogout() {
  authStore.logout()
  router.push('/auth/login')
}
</script>

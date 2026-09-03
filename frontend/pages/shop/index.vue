<template>
  <div class="py-8" style="background-color: var(--color-bg-alt); min-height: 80vh;">
    <div class="container">
      <!-- Breadcrumb -->
      <nav aria-label="Breadcrumb" style="display: flex; gap: 0.5rem; font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 1.5rem; flex-wrap: wrap;">
        <NuxtLink to="/" style="color: var(--color-text-main);">Home</NuxtLink>
        <span>/</span>
        <NuxtLink to="/shop" :style="{ color: selectedCategory ? 'var(--color-text-main)' : 'var(--color-primary)', fontWeight: selectedCategory ? '400' : '600' }">Shop Catalog</NuxtLink>
        <template v-if="selectedCategory">
          <span>/</span>
          <span style="color: var(--color-primary); font-weight: 600; text-transform: capitalize;">{{ selectedCategory.replace('-', ' ') }}</span>
        </template>
      </nav>

      <!-- Mobile Filter Toggle Button (Shown on <= 900px) -->
      <div class="mobile-only" style="margin-bottom: 1rem;">
        <button
          @click="showMobileFilter = !showMobileFilter"
          class="mobile-filter-toggle"
          aria-label="Toggle Catalog Filters"
          :aria-expanded="showMobileFilter"
        >
          <i class="fa-solid fa-sliders"></i>
          <span>{{ showMobileFilter ? 'Hide Filters & Categories' : 'Filter by Category & Price' }}</span>
          <i :class="showMobileFilter ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down'" style="font-size: 0.75rem; margin-left: auto;"></i>
        </button>
      </div>

      <!-- Shop Layout: Sidebar + Grid -->
      <div class="shop-grid-layout">
        <!-- Sidebar Filter -->
        <aside
          :class="['shop-sidebar', { 'mobile-hidden': !showMobileFilter }]"
        >
          <div class="flex items-center justify-between" style="border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem; margin-bottom: 1.25rem;">
            <h4 style="font-size: 1.05rem; font-weight: 700; margin-bottom: 0;">
              Categories
            </h4>
            <button
              v-if="selectedCategory || minPrice || maxPrice || searchQuery"
              @click="resetFilters"
              style="font-size: 0.75rem; color: var(--color-secondary-dark); background: transparent; border: none; cursor: pointer; text-decoration: underline;"
            >
              Reset All
            </button>
          </div>

          <ul class="flex flex-col gap-2" style="font-size: 0.9rem;">
            <li>
              <button
                @click="filterCategory('')"
                :style="{
                  background: 'transparent',
                  border: 'none',
                  cursor: 'pointer',
                  fontWeight: selectedCategory === '' ? '700' : '500',
                  color: selectedCategory === '' ? 'var(--color-secondary-dark)' : 'var(--color-text-main)',
                  textAlign: 'left',
                  width: '100%',
                  padding: '0.45rem 0',
                  display: 'flex',
                  alignItems: 'center',
                  gap: '0.5rem'
                }"
              >
                <i class="fa-solid fa-border-all" style="width: 18px;"></i> All Products
              </button>
            </li>
            <li v-for="cat in categories" :key="cat.slug">
              <div class="flex items-center justify-between" style="padding: 0.35rem 0;">
                <button
                  @click="filterCategory(cat.slug)"
                  :style="{
                    background: 'transparent',
                    border: 'none',
                    cursor: 'pointer',
                    fontWeight: selectedCategory === cat.slug ? '700' : '500',
                    color: selectedCategory === cat.slug ? 'var(--color-secondary-dark)' : 'var(--color-text-main)',
                    textAlign: 'left',
                    flexGrow: 1
                  }"
                >
                  {{ cat.name }} ({{ cat.products_count || 0 }})
                </button>
              </div>

              <!-- Subcategories -->
              <ul v-if="cat.children && cat.children.length > 0" class="flex flex-col gap-1" style="padding-left: 1.25rem; margin-top: 0.25rem;">
                <li v-for="sub in cat.children" :key="sub.slug">
                  <button
                    @click="filterCategory(sub.slug)"
                    :style="{
                      background: 'transparent',
                      border: 'none',
                      cursor: 'pointer',
                      fontSize: '0.85rem',
                      color: selectedCategory === sub.slug ? 'var(--color-secondary-dark)' : 'var(--color-text-muted)',
                      fontWeight: selectedCategory === sub.slug ? '700' : '400',
                      textAlign: 'left',
                      padding: '0.2rem 0'
                    }"
                  >
                    • {{ sub.name }}
                  </button>
                </li>
              </ul>
            </li>
          </ul>

          <!-- Price Range Filter -->
          <div style="margin-top: 2rem; border-top: 1px solid var(--color-border); padding-top: 1.5rem;">
            <h4 style="font-size: 1.05rem; font-weight: 700; margin-bottom: 0.75rem;">
              Filter by Price (RM)
            </h4>
            <div class="flex gap-2 items-center">
              <input
                v-model="minPrice"
                type="number"
                placeholder="Min RM"
                aria-label="Minimum Price in Ringgit Malaysia"
                class="form-input"
                style="padding: 0.55rem; font-size: 0.85rem;"
              />
              <span>-</span>
              <input
                v-model="maxPrice"
                type="number"
                placeholder="Max RM"
                aria-label="Maximum Price in Ringgit Malaysia"
                class="form-input"
                style="padding: 0.55rem; font-size: 0.85rem;"
              />
            </div>
            <button @click="applyFilters" class="btn btn-primary btn-sm" style="width: 100%; margin-top: 0.75rem;">
              Apply Filter
            </button>
          </div>
        </aside>

        <!-- Main Products Content -->
        <main>
          <!-- Toolbar (Search + Sort) -->
          <div class="shop-toolbar">
            <!-- Search Bar -->
            <div style="flex: 1 1 260px; position: relative;">
              <input
                v-model="searchQuery"
                @input="debounceSearch"
                type="text"
                placeholder="Search sofa, SKU, table, bedframe..."
                aria-label="Search furniture pieces by keyword or SKU"
                class="form-input"
                style="padding-left: 2.5rem; height: 44px;"
              />
              <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-text-light);"></i>
            </div>

            <!-- Sort Dropdown -->
            <div class="flex items-center gap-2" style="flex-shrink: 0;">
              <label for="catalog-sort" style="font-size: 0.85rem; color: var(--color-text-muted); font-weight: 600; white-space: nowrap;">Sort By:</label>
              <select id="catalog-sort" v-model="selectedSort" @change="applyFilters" class="form-select" style="width: auto; padding: 0.55rem 1rem; height: 44px;">
                <option value="featured">Featured</option>
                <option value="newest">Newest Arrivals</option>
                <option value="price_asc">Price: Low to High</option>
                <option value="price_desc">Price: High to Low</option>
                <option value="name_asc">Name: A to Z</option>
              </select>
            </div>
          </div>

          <!-- Product Grid -->
          <div v-if="loading" class="text-center py-16">
            <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 2rem; color: var(--color-secondary-dark);"></i>
            <p style="margin-top: 1rem; color: var(--color-text-muted);">Loading handcrafted furniture collection...</p>
          </div>

          <div v-else-if="products.length === 0" style="background: #FFFFFF; border: 1px solid var(--color-border); padding: 4rem 2rem; text-align: center; border-radius: var(--radius-sm);">
            <i class="fa-solid fa-box-open" style="font-size: 3rem; color: var(--color-border-dark); margin-bottom: 1rem;"></i>
            <h3>No products found</h3>
            <p style="margin-bottom: 1.5rem; color: var(--color-text-muted);">Try adjusting your search query or resetting active price and category filters.</p>
            <button @click="resetFilters" class="btn btn-outline">Reset All Filters</button>
          </div>

          <div v-else>
            <div class="grid grid-cols-3 gap-6">
              <ProductCard
                v-for="prod in products"
                :key="prod.id"
                :product="prod"
              />
            </div>

            <!-- Pagination -->
            <div v-if="totalPages > 1" class="flex justify-center items-center gap-2 flex-wrap" style="margin-top: 3.5rem;">
              <button
                :disabled="currentPage === 1"
                @click="goToPage(currentPage - 1)"
                class="btn btn-outline btn-sm"
                aria-label="Previous Page"
              >
                Previous
              </button>

              <span style="font-size: 0.9rem; font-weight: 600; padding: 0 1rem;">
                Page {{ currentPage }} of {{ totalPages }}
              </span>

              <button
                :disabled="currentPage === totalPages"
                @click="goToPage(currentPage + 1)"
                class="btn btn-outline btn-sm"
                aria-label="Next Page"
              >
                Next
              </button>
            </div>
          </div>
        </main>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import ProductCard from '~/components/ProductCard.vue'

const config = useRuntimeConfig()
const apiBase = config.public.apiBase
const route = useRoute()
const router = useRouter()

const categories = ref<any[]>([])
const products = ref<any[]>([])
const loading = ref(true)
const showMobileFilter = ref(false)

const selectedCategory = ref((route.query.category as string) || '')
const searchQuery = ref((route.query.search as string) || '')
const selectedSort = ref((route.query.sort as string) || 'featured')
const minPrice = ref((route.query.min_price as string) || '')
const maxPrice = ref((route.query.max_price as string) || '')

const currentPage = ref(1)
const totalPages = ref(1)

let searchTimer: any = null

// Dynamic SEO
useSeoMeta({
  title: computed(() => selectedCategory.value ? `${selectedCategory.value.replace('-', ' ').toUpperCase()} Collection | Handcrafted Furniture Malaysia` : 'Shop Handcrafted Sofas & Luxury Furniture Malaysia'),
  description: 'Explore Malaysian handcrafted solid hardwood sofas, bespoke wing chairs, luxury bedframes, and dining tables with 5-Year Wood Warranty & direct factory pricing.',
  ogTitle: computed(() => selectedCategory.value ? `${selectedCategory.value.replace('-', ' ').toUpperCase()} Collection · NMFFurniture` : 'Shop Handcrafted Sofas & Furniture · NMFFurniture'),
  ogDescription: 'Direct in-house furniture manufacturer in Shah Alam & PJ. 200+ pet-friendly fabrics & 5-Year Warranty.',
  ogImage: 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1200&q=80',
})

useHead({
  link: [
    { rel: 'canonical', href: computed(() => `https://nmffurniture.com/shop${selectedCategory.value ? `?category=${selectedCategory.value}` : ''}`) }
  ],
  script: [
    {
      type: 'application/ld+json',
      children: JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'BreadcrumbList',
        'itemListElement': [
          {
            '@type': 'ListItem',
            'position': 1,
            'name': 'Home',
            'item': 'https://nmffurniture.com'
          },
          {
            '@type': 'ListItem',
            'position': 2,
            'name': 'Shop Catalog',
            'item': 'https://nmffurniture.com/shop'
          }
        ]
      })
    }
  ]
})

async function loadCategories() {
  try {
    const res: any = await $fetch(`${apiBase}/categories`)
    categories.value = res.categories || []
  } catch (e) {
    console.error('Failed to load categories', e)
  }
}

async function loadProducts() {
  loading.value = true
  try {
    const params = new URLSearchParams()
    if (selectedCategory.value) params.append('category', selectedCategory.value)
    if (searchQuery.value) params.append('search', searchQuery.value)
    if (selectedSort.value) params.append('sort', selectedSort.value)
    if (minPrice.value) params.append('min_price', minPrice.value)
    if (maxPrice.value) params.append('max_price', maxPrice.value)
    params.append('page', currentPage.value.toString())
    params.append('per_page', '9')

    const res: any = await $fetch(`${apiBase}/products?${params.toString()}`)
    products.value = res.data || []
    currentPage.value = res.current_page || 1
    totalPages.value = res.last_page || 1
  } catch (e) {
    console.error('Failed to load products', e)
  } finally {
    loading.value = false
  }
}

function filterCategory(slug: string) {
  selectedCategory.value = slug
  currentPage.value = 1
  showMobileFilter.value = false
  applyFilters()
}

function debounceSearch() {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => {
    currentPage.value = 1
    applyFilters()
  }, 400)
}

function applyFilters() {
  router.push({
    path: '/shop',
    query: {
      category: selectedCategory.value || undefined,
      search: searchQuery.value || undefined,
      sort: selectedSort.value || undefined,
      min_price: minPrice.value || undefined,
      max_price: maxPrice.value || undefined,
    }
  })
  loadProducts()
}

function resetFilters() {
  selectedCategory.value = ''
  searchQuery.value = ''
  selectedSort.value = 'featured'
  minPrice.value = ''
  maxPrice.value = ''
  currentPage.value = 1
  showMobileFilter.value = false
  applyFilters()
}

function goToPage(page: number) {
  currentPage.value = page
  loadProducts()
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

onMounted(() => {
  loadCategories()
  loadProducts()
})

watch(() => route.query, (newQuery) => {
  selectedCategory.value = (newQuery.category as string) || ''
  searchQuery.value = (newQuery.search as string) || ''
  selectedSort.value = (newQuery.sort as string) || 'featured'
  loadProducts()
})
</script>

<style scoped>
.shop-grid-layout {
  display: grid;
  grid-template-columns: 280px 1fr;
  gap: 2.5rem;
  align-items: start;
}

.shop-sidebar {
  background: #FFFFFF;
  border: 1px solid var(--color-border);
  border-radius: var(--radius-sm);
  padding: 1.5rem;
  position: sticky;
  top: 90px;
}

.shop-toolbar {
  background: #FFFFFF;
  border: 1px solid var(--color-border);
  border-radius: var(--radius-sm);
  padding: 1.25rem;
  margin-bottom: 2rem;
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  align-items: center;
  justify-content: space-between;
}

@media (max-width: 900px) {
  .shop-grid-layout {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }
  .shop-sidebar {
    position: static;
    margin-bottom: 1.5rem;
  }
  .shop-sidebar.mobile-hidden {
    display: none;
  }
}
</style>

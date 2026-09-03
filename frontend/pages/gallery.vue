<template>
  <div class="py-12" style="background-color: var(--color-bg-alt); min-height: 85vh;">
    <div class="container">
      <div class="section-title">
        <span class="section-eyebrow">PORTFOLIO & INSPIRATION</span>
        <h2>PROJECT LOOKBOOK</h2>
        <p>Explore custom sofa installations, show units, and bespoke living spaces handcrafted by NMFFurniture.</p>
      </div>

      <!-- Filter Tabs -->
      <div class="flex justify-center gap-2 flex-wrap" style="margin-bottom: 3rem;">
        <button
          v-for="tab in filterTabs"
          :key="tab.value"
          @click="activeCategory = tab.value"
          class="btn btn-sm"
          :class="activeCategory === tab.value ? 'btn-primary' : 'btn-outline'"
          style="border-radius: var(--radius-full); text-transform: none; padding: 0.5rem 1.4rem;"
        >
          {{ tab.label }}
        </button>
      </div>

      <!-- Gallery Grid -->
      <div v-if="filteredItems.length === 0" class="text-center py-16">
        <p style="color: var(--color-text-muted);">No installations found in this category.</p>
      </div>

      <div v-else class="grid grid-cols-3 gap-6">
        <div
          v-for="(item, idx) in filteredItems"
          :key="idx"
          @click="openLightbox(item)"
          class="gallery-item-card"
        >
          <div style="aspect-ratio: 4 / 3; overflow: hidden; position: relative;">
            <img
              :src="item.image_url"
              :alt="item.title"
              style="width: 100%; height: 100%; object-fit: cover; transition: transform var(--transition-slow);"
              loading="lazy"
            />
            <span
              v-if="item.location || item.client_name"
              style="position: absolute; bottom: 12px; left: 12px; background: rgba(22, 23, 25, 0.82); backdrop-filter: blur(4px); color: #FFFFFF; font-size: 0.75rem; font-weight: 600; padding: 0.3rem 0.7rem; border-radius: var(--radius-sm);"
            >
              <i class="fa-solid fa-location-dot" style="color: var(--color-secondary); margin-right: 0.35rem;"></i>
              {{ item.location || item.client_name }}
            </span>
          </div>

          <div style="padding: 1.25rem;">
            <div style="font-size: 0.72rem; font-weight: 700; color: var(--color-secondary-dark); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.35rem;">
              {{ item.category }}
            </div>
            <h4 style="font-size: 1.05rem; font-weight: 600; margin-bottom: 0.35rem; color: var(--color-primary);">
              {{ item.title }}
            </h4>
            <p v-if="item.description" style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 0; line-height: 1.5;">
              {{ item.description }}
            </p>
          </div>
        </div>
      </div>

      <!-- Lightbox Modal -->
      <div
        v-if="activeLightboxItem"
        style="position: fixed; inset: 0; background: rgba(22, 23, 25, 0.88); backdrop-filter: blur(8px); z-index: 400; display: flex; align-items: center; justify-content: center; padding: 2rem;"
        @click.self="activeLightboxItem = null"
      >
        <div style="max-width: 880px; width: 100%; background: #FFFFFF; border-radius: var(--radius-sm); overflow: hidden; position: relative; box-shadow: var(--shadow-xl);">
          <button
            @click="activeLightboxItem = null"
            style="position: absolute; top: 14px; right: 14px; z-index: 10; background: rgba(0,0,0,0.6); color: #FFFFFF; border: none; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; font-size: 1.1rem; display: flex; align-items: center; justify-content: center;"
          >
            <i class="fa-solid fa-xmark"></i>
          </button>
          <img
            :src="activeLightboxItem.image_url"
            :alt="activeLightboxItem.title"
            style="width: 100%; max-height: 65vh; object-fit: cover;"
          />
          <div style="padding: 1.75rem;">
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--color-secondary-dark); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.35rem;">
              {{ activeLightboxItem.category }}
            </div>
            <h3 style="font-size: 1.4rem; font-family: var(--font-heading); margin-bottom: 0.5rem; color: var(--color-primary);">
              {{ activeLightboxItem.title }}
            </h3>
            <p v-if="activeLightboxItem.description" style="font-size: 0.95rem; color: var(--color-text-muted); margin-bottom: 0.75rem; line-height: 1.6;">
              {{ activeLightboxItem.description }}
            </p>
            <div v-if="activeLightboxItem.location || activeLightboxItem.client_name" style="font-size: 0.85rem; font-weight: 600; color: var(--color-primary);">
              <i class="fa-solid fa-location-dot" style="color: var(--color-secondary-dark); margin-right: 0.35rem;"></i>
              Installation Site: {{ activeLightboxItem.location || activeLightboxItem.client_name }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const config = useRuntimeConfig()
const apiBase = config.public.apiBase

const items = ref<any[]>([])
const activeCategory = ref('all')
const activeLightboxItem = ref<any>(null)

const filterTabs = [
  { label: 'All Projects', value: 'all' },
  { label: 'Living Room Sofas', value: 'living' },
  { label: 'Bedframes & Suites', value: 'bedroom' },
  { label: 'Dining Sets', value: 'dining' },
  { label: 'Showroom Galleries', value: 'showroom' },
]

const filteredItems = computed(() => {
  if (activeCategory.value === 'all') return items.value
  return items.value.filter(it => it.category === activeCategory.value)
})

// SEO Optimization
useSeoMeta({
  title: 'Project Lookbook & Residential Installations | NMFFurniture Malaysia',
  description: 'View real living room transformations, custom sofa configurations, and luxury installations by NMFFurniture across Klang Valley and Peninsular Malaysia.',
  ogTitle: 'Project Lookbook · NMFFurniture Malaysia',
  ogDescription: 'Real residential installations and bespoke living furniture lookbook.',
  ogImage: 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1200&q=80',
})

useHead({
  link: [
    { rel: 'canonical', href: 'https://nmffurniture.com/gallery' }
  ]
})

async function loadGallery() {
  try {
    const res: any = await $fetch(`${apiBase}/gallery`)
    items.value = res.items || []
  } catch (e) {
    console.error('Failed to load gallery', e)
  }
}

function openLightbox(item: any) {
  activeLightboxItem.value = item
}

onMounted(() => {
  loadGallery()
})
</script>

<style scoped>
.gallery-item-card {
  background: #FFFFFF;
  border: 1px solid var(--color-border);
  border-radius: var(--radius-sm);
  overflow: hidden;
  cursor: pointer;
  transition: transform var(--transition-normal), box-shadow var(--transition-normal), border-color var(--transition-normal);
}

.gallery-item-card:hover {
  transform: translateY(-3px);
  box-shadow: var(--shadow-md);
  border-color: var(--color-secondary);
}

.gallery-item-card:hover img {
  transform: scale(1.03);
}

@media (max-width: 640px) {
  div[style*="max-width: 880px"] {
    max-width: 95vw !important;
  }
}
</style>

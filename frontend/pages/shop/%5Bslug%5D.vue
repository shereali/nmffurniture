<template>
  <div class="py-8" style="background-color: #FFFFFF; min-height: 85vh;">
    <div class="container">
      <!-- Breadcrumbs -->
      <nav aria-label="Breadcrumb" style="display: flex; gap: 0.5rem; font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 2rem; flex-wrap: wrap;">
        <NuxtLink to="/" style="color: var(--color-text-main);">Home</NuxtLink>
        <span>/</span>
        <NuxtLink to="/shop" style="color: var(--color-text-main);">Shop</NuxtLink>
        <template v-if="product?.category">
          <span>/</span>
          <NuxtLink :to="`/shop?category=${product.category.slug}`" style="color: var(--color-text-main);">
            {{ product.category.name }}
          </NuxtLink>
        </template>
        <span>/</span>
        <span style="color: var(--color-primary); font-weight: 600;">{{ product?.name || 'Handcrafted Piece' }}</span>
      </nav>

      <div v-if="loading" class="text-center py-20">
        <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 2.5rem; color: var(--color-secondary-dark);"></i>
        <p style="margin-top: 1rem; color: var(--color-text-muted);">Loading handcrafted details...</p>
      </div>

      <div v-else-if="!product" class="text-center py-20">
        <h3>Product not found</h3>
        <NuxtLink to="/shop" class="btn btn-primary" style="margin-top: 1rem;">Back to Shop</NuxtLink>
      </div>

      <div v-else>
        <!-- Product Main Grid -->
        <div class="product-detail-grid">
          <!-- Left: Gallery -->
          <div>
            <!-- Main Image -->
            <div class="main-image-container">
              <img
                :src="activeImage"
                :alt="`${product.name} - Handcrafted Solid Wood Furniture`"
                style="width: 100%; height: 100%; object-fit: cover;"
              />
              <span class="product-badge" style="position: absolute; top: 16px; left: 16px;">
                <i class="fa-solid fa-shield-halved"></i> 5-Year Wood Warranty
              </span>
            </div>

            <!-- Thumbnail Selector -->
            <div v-if="product.images && product.images.length > 1" class="flex gap-3 flex-wrap" style="margin-top: 0.75rem;">
              <div
                v-for="(img, i) in product.images"
                :key="i"
                @click="activeImage = img.image_url"
                :style="{
                  width: '80px',
                  height: '65px',
                  borderRadius: 'var(--radius-sm)',
                  overflow: 'hidden',
                  cursor: 'pointer',
                  border: activeImage === img.image_url ? '2px solid var(--color-primary)' : '1px solid var(--color-border)'
                }"
              >
                <img :src="img.image_url" :alt="`${product.name} thumbnail ${i + 1}`" style="width: 100%; height: 100%; object-fit: cover;" />
              </div>
            </div>
          </div>

          <!-- Right: Details & Purchase -->
          <div>
            <div style="font-size: 0.8rem; font-weight: 700; color: var(--color-secondary-dark); letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 0.5rem;">
              SKU: {{ product.sku }} • {{ product.category?.name }}
            </div>

            <h1 style="font-size: clamp(1.8rem, 3.5vw, 2.35rem); font-family: var(--font-heading); margin-bottom: 1rem; line-height: 1.25; color: var(--color-primary);">
              {{ product.name }}
            </h1>

            <!-- Price -->
            <div class="flex items-baseline gap-3 flex-wrap" style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--color-border);">
              <span style="font-size: 2rem; font-weight: 700; font-family: var(--font-sans); color: var(--color-primary);">
                RM {{ calculateTotalPrice().toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
              </span>
              <span v-if="product.discount_price" style="font-size: 1.2rem; color: var(--color-text-light); text-decoration: line-through;">
                RM {{ Number(product.price).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
              </span>
              <span style="font-size: 0.75rem; background: var(--color-success-bg); color: var(--color-success); padding: 0.25rem 0.6rem; border-radius: var(--radius-sm); font-weight: 700;">
                Made to Order (10-14 Working Days)
              </span>
            </div>

            <!-- Short description -->
            <p style="font-size: 1rem; color: var(--color-text-main); margin-bottom: 1.5rem; line-height: 1.7;">
              {{ product.short_description }}
            </p>

            <!-- Variants Selector -->
            <div v-if="product.variants && product.variants.length > 0" style="margin-bottom: 2rem;">
              <label class="form-label" style="font-size: 0.85rem; margin-bottom: 0.75rem;">
                Select Material / Fabric Finish:
              </label>
              <div class="flex flex-col gap-2">
                <label
                  v-for="v in product.variants"
                  :key="v.id"
                  :style="{
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'space-between',
                    padding: '0.85rem 1.25rem',
                    border: selectedVariant?.id === v.id ? '2px solid var(--color-primary)' : '1px solid var(--color-border)',
                    borderRadius: 'var(--radius-sm)',
                    cursor: 'pointer',
                    backgroundColor: selectedVariant?.id === v.id ? 'var(--color-bg-alt)' : '#FFFFFF'
                  }"
                >
                  <div class="flex items-center gap-3">
                    <input
                      type="radio"
                      name="variant"
                      :value="v"
                      v-model="selectedVariant"
                    />
                    <span
                      v-if="v.color_code"
                      :style="{ width: '18px', height: '18px', borderRadius: '50%', backgroundColor: v.color_code, border: '1px solid #CCC' }"
                    ></span>
                    <span style="font-weight: 600; font-size: 0.95rem;">{{ v.option }} ({{ v.color_name || v.name }})</span>
                  </div>
                  <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-secondary-dark);">
                    {{ Number(v.price_adjustment) > 0 ? `+RM ${Number(v.price_adjustment).toFixed(2)}` : 'Standard' }}
                  </span>
                </label>
              </div>
            </div>

            <!-- Quantity & Actions Row -->
            <div class="product-action-row">
              <div class="quantity-picker">
                <button
                  @click="quantity = Math.max(1, quantity - 1)"
                  aria-label="Decrease quantity"
                  style="background: transparent; border: none; font-size: 1.1rem; padding: 0.4rem 0.85rem; cursor: pointer;"
                >-</button>
                <span style="font-weight: 700; font-size: 1.1rem; padding: 0 0.8rem;">{{ quantity }}</span>
                <button
                  @click="quantity++"
                  aria-label="Increase quantity"
                  style="background: transparent; border: none; font-size: 1.1rem; padding: 0.4rem 0.85rem; cursor: pointer;"
                >+</button>
              </div>

              <button
                @click="addToCart"
                class="btn btn-primary btn-lg flex-1"
                style="min-height: 48px;"
              >
                <i class="fa-solid fa-bag-shopping"></i> Add to Bag
              </button>
            </div>

            <!-- Direct WhatsApp Order CTA -->
            <div style="background-color: var(--color-secondary-light); border: 1px solid rgba(181, 149, 109, 0.35); border-radius: var(--radius-sm); padding: 1.25rem; margin-bottom: 2rem;">
              <div class="flex items-center gap-3" style="margin-bottom: 0.5rem;">
                <i class="fa-brands fa-whatsapp" style="font-size: 1.5rem; color: #25D366;"></i>
                <h4 style="font-size: 1rem; margin-bottom: 0; color: var(--color-primary);">Need Custom Sizing or Swatches?</h4>
              </div>
              <p style="font-size: 0.875rem; color: var(--color-text-main); margin-bottom: 0.75rem; line-height: 1.6;">
                Speak directly with our Bukit Jelutong workshop to customize sofa length, seat firmness, or view 200+ fabric swatches.
              </p>
              <a
                :href="whatsappDetailUrl"
                target="_blank"
                class="btn btn-whatsapp btn-sm"
                style="width: 100%; text-transform: none;"
              >
                <i class="fa-brands fa-whatsapp"></i> Chat with Factory Specialist
              </a>
            </div>

            <!-- Value Props List -->
            <div class="grid grid-cols-2 gap-3" style="font-size: 0.85rem; color: var(--color-text-muted); border-top: 1px solid var(--color-border); padding-top: 1.5rem;">
              <div class="flex items-center gap-2">
                <i class="fa-solid fa-tree" style="color: var(--color-secondary-dark);"></i> Solid Hardwood Chassis
              </div>
              <div class="flex items-center gap-2">
                <i class="fa-solid fa-shield" style="color: var(--color-secondary-dark);"></i> 5-Year Wood Warranty
              </div>
              <div class="flex items-center gap-2">
                <i class="fa-solid fa-truck" style="color: var(--color-secondary-dark);"></i> Free Peninsular Delivery &gt;RM3k
              </div>
              <div class="flex items-center gap-2">
                <i class="fa-solid fa-certificate" style="color: var(--color-secondary-dark);"></i> 100% Made in Malaysia
              </div>
            </div>
          </div>
        </div>

        <!-- Tabs: Description & Specs -->
        <div style="border-top: 1px solid var(--color-border); padding-top: 3.5rem; margin-bottom: 4rem;">
          <h3 style="font-size: 1.6rem; margin-bottom: 1.5rem;">Craftsmanship & Specifications</h3>
          <div class="specs-grid">
            <div>
              <h4 style="font-size: 1.15rem; margin-bottom: 0.75rem;">Full Product Story</h4>
              <p style="line-height: 1.8; margin-bottom: 1rem;">
                {{ product.description }}
              </p>
              <p style="line-height: 1.8;">
                <strong>Materials Used:</strong> {{ product.materials || 'Kiln-dried Malaysian Meranti Hardwood, High-Density Latex Blend Foam, Pocket Spring Core, Premium Fabric.' }}
              </p>
            </div>

            <div class="specs-table-card">
              <h4 style="font-size: 1.15rem; margin-bottom: 1rem;">Dimensions & Frame Specs</h4>
              <div class="table-responsive">
                <table style="width: 100%; font-size: 0.9rem;">
                  <tbody>
                    <tr style="border-bottom: 1px solid var(--color-border);">
                      <td style="padding: 0.6rem 0; font-weight: 600;">Dimensions:</td>
                      <td style="padding: 0.6rem 0; color: var(--color-text-muted);">{{ product.dimensions || 'W 240cm x D 95cm x H 85cm' }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--color-border);">
                      <td style="padding: 0.6rem 0; font-weight: 600;">Frame Material:</td>
                      <td style="padding: 0.6rem 0; color: var(--color-text-muted);">100% Solid Kiln-Dried Hardwood</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--color-border);">
                      <td style="padding: 0.6rem 0; font-weight: 600;">Warranty Coverage:</td>
                      <td style="padding: 0.6rem 0; color: var(--color-secondary-dark); font-weight: 700;">5 Years (Structural Wood Frame)</td>
                    </tr>
                    <tr>
                      <td style="padding: 0.6rem 0; font-weight: 600;">Maintenance:</td>
                      <td style="padding: 0.6rem 0; color: var(--color-text-muted);">Wipe clean with damp microfiber cloth</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Related Products -->
        <div v-if="relatedProducts.length > 0" style="border-top: 1px solid var(--color-border); padding-top: 3.5rem;">
          <div class="section-title" style="text-align: left; margin-bottom: 2rem;">
            <span class="section-eyebrow">CURATED RECOMMENDATIONS</span>
            <h2>COMPLEMENTARY PIECES</h2>
          </div>

          <div class="grid grid-cols-4 gap-6">
            <ProductCard
              v-for="rel in relatedProducts"
              :key="rel.id"
              :product="rel"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useCartStore } from '~/stores/cart'
import ProductCard from '~/components/ProductCard.vue'

const config = useRuntimeConfig()
const apiBase = config.public.apiBase
const whatsappDefault = config.public.whatsappDefault
const route = useRoute()
const cartStore = useCartStore()

const slug = route.params.slug as string
const product = ref<any>(null)
const relatedProducts = ref<any[]>([])
const loading = ref(true)
const activeImage = ref('')
const selectedVariant = ref<any>(null)
const quantity = ref(1)

// Dynamic SEO & Structured Data
useSeoMeta({
  title: computed(() => product.value ? `${product.value.name} | Handcrafted Solid Wood Furniture` : 'Product Details | NMFFurniture'),
  description: computed(() => product.value ? `${product.value.short_description} Backed by 5-Year Solid Wood Frame Warranty. Custom fabrics and sizing available.` : 'Handcrafted Malaysian luxury sofa with 5-Year Solid Wood Warranty.'),
  ogTitle: computed(() => product.value ? `${product.value.name} · NMFFurniture Malaysia` : 'Handcrafted Furniture Piece'),
  ogDescription: computed(() => product.value?.short_description || 'Handcrafted bespoke furniture piece in Malaysia.'),
  ogImage: computed(() => activeImage.value || 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1200&q=80'),
})

useHead({
  link: [
    { rel: 'canonical', href: computed(() => `https://nmffurniture.com/shop/${slug}`) }
  ],
  script: computed(() => {
    if (!product.value) return []
    return [
      {
        type: 'application/ld+json',
        children: JSON.stringify({
          '@context': 'https://schema.org',
          '@type': 'Product',
          'name': product.value.name,
          'image': product.value.images?.map((i: any) => i.image_url) || [activeImage.value],
          'description': product.value.description || product.value.short_description,
          'sku': product.value.sku,
          'brand': {
            '@type': 'Brand',
            'name': 'NMFFurniture'
          },
          'offers': {
            '@type': 'Offer',
            'url': `https://nmffurniture.com/shop/${slug}`,
            'priceCurrency': 'MYR',
            'price': calculateTotalPrice(),
            'itemCondition': 'https://schema.org/NewCondition',
            'availability': 'https://schema.org/InStock',
            'seller': {
              '@type': 'Organization',
              'name': 'NMFFurniture Sdn Bhd'
            }
          }
        })
      }
    ]
  })
})

async function loadProduct() {
  loading.value = true
  try {
    const res: any = await $fetch(`${apiBase}/products/${slug}`)
    product.value = res.product
    relatedProducts.value = res.related_products || []

    if (product.value.images && product.value.images.length > 0) {
      activeImage.value = product.value.images[0].image_url
    } else {
      activeImage.value = 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1200&q=80'
    }

    if (product.value.variants && product.value.variants.length > 0) {
      selectedVariant.value = product.value.variants[0]
    }
  } catch (e) {
    console.error('Failed to load product', e)
  } finally {
    loading.value = false
  }
}

function calculateTotalPrice() {
  if (!product.value) return 0
  const base = Number(product.value.discount_price ?? product.value.price)
  const variantAdj = selectedVariant.value ? Number(selectedVariant.value.price_adjustment || 0) : 0
  return base + variantAdj
}

function addToCart() {
  const finalProduct = {
    ...product.value,
    discount_price: calculateTotalPrice(),
    price: calculateTotalPrice(),
  }
  const variantLabel = selectedVariant.value ? selectedVariant.value.option : undefined
  cartStore.addItem(finalProduct, variantLabel, quantity.value)
}

const whatsappDetailUrl = computed(() => {
  if (!product.value) return ''
  const variantText = selectedVariant.value ? ` (${selectedVariant.value.option})` : ''
  const text = `Hello NMFFurniture, I would like to inquire about customizing the ${product.value.name}${variantText} SKU: ${product.value.sku} priced at RM ${calculateTotalPrice().toFixed(2)}.`
  return `https://wa.me/${whatsappDefault}?text=${encodeURIComponent(text)}`
})

onMounted(() => {
  loadProduct()
})
</script>

<style scoped>
.product-detail-grid {
  display: grid;
  grid-template-columns: 1.1fr 0.9fr;
  gap: 4rem;
  margin-bottom: 4rem;
  align-items: start;
}

.main-image-container {
  aspect-ratio: 4 / 3;
  background-color: var(--color-bg-alt);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-sm);
  overflow: hidden;
  position: relative;
}

.product-action-row {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 2rem;
  flex-wrap: wrap;
}

.quantity-picker {
  display: flex;
  align-items: center;
  border: 1px solid var(--color-border);
  border-radius: var(--radius-sm);
  padding: 0.2rem 0.4rem;
  min-height: 48px;
}

.specs-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 3rem;
}

.specs-table-card {
  background-color: var(--color-bg-alt);
  padding: 1.5rem;
  border-radius: var(--radius-sm);
  border: 1px solid var(--color-border);
}

@media (max-width: 900px) {
  .product-detail-grid {
    grid-template-columns: 1fr;
    gap: 2rem;
  }
  .specs-grid {
    grid-template-columns: 1fr;
    gap: 2rem;
  }
}

@media (max-width: 500px) {
  .product-action-row {
    flex-direction: column;
    align-items: stretch;
  }
  .quantity-picker {
    justify-content: space-between;
  }
}
</style>

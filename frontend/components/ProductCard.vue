<template>
  <div class="product-card">
    <div class="product-card-thumb">
      <NuxtLink :to="`/shop/${product.slug}`">
        <img
          :src="primaryImage"
          :alt="product.name"
          loading="lazy"
        />
      </NuxtLink>
      <span v-if="product.discount_price" class="product-badge sale">Sale</span>
      <span v-else-if="product.is_featured" class="product-badge">Featured</span>

      <!-- Wishlist Toggle Button -->
      <button
        @click.stop.prevent="wishlistStore.toggleWishlist(product)"
        class="product-wishlist-btn"
        :class="{ active: isWishlisted }"
        :title="isWishlisted ? 'Remove from Wishlist' : 'Save to Wishlist'"
        aria-label="Wishlist"
      >
        <i :class="isWishlisted ? 'fa-solid fa-heart' : 'fa-regular fa-heart'"></i>
      </button>
    </div>

    <div class="product-card-body">
      <div class="flex items-center justify-between" v-if="product.category">
        <span class="product-card-category">{{ product.category.name }}</span>
      </div>

      <h3 class="product-card-name">
        <NuxtLink :to="`/shop/${product.slug}`">
          {{ product.name }}
        </NuxtLink>
      </h3>

      <!-- Customer Star Rating Snippet -->
      <div class="product-card-rating">
        <span style="letter-spacing: 1px;">★★★★★</span>
        <span class="product-card-rating-num">5.0</span>
        <span class="product-card-rating-verified">• Handcrafted</span>
      </div>

      <div class="product-card-price">
        <span class="price-current">
          RM {{ formattedPrice }}
        </span>
        <span v-if="product.discount_price" class="price-original">
          RM {{ Number(product.price).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
        </span>
      </div>

      <div class="flex gap-2" style="margin-top: 0.75rem;">
        <button
          @click="addToCart"
          class="btn btn-outline btn-sm flex-1"
        >
          <i class="fa-solid fa-bag-shopping"></i> Add to Bag
        </button>
        <a
          :href="whatsappProductUrl"
          target="_blank"
          class="btn btn-whatsapp btn-sm"
          title="Inquire on WhatsApp"
        >
          <i class="fa-brands fa-whatsapp"></i>
        </a>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useCartStore } from '~/stores/cart'
import { useWishlistStore } from '~/stores/wishlist'

const props = defineProps<{
  product: any
}>()

const cartStore = useCartStore()
const wishlistStore = useWishlistStore()
const config = useRuntimeConfig()
const whatsappDefault = config.public.whatsappDefault

const isWishlisted = computed(() => {
  return wishlistStore.isInWishlist(props.product.id)
})

const primaryImage = computed(() => {
  if (props.product.images && props.product.images.length > 0) {
    return props.product.images[0].image_url
  }
  return 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=600&q=80'
})

const currentPrice = computed(() => {
  return Number(props.product.discount_price ?? props.product.price)
})

const formattedPrice = computed(() => {
  return currentPrice.value.toLocaleString(undefined, { minimumFractionDigits: 2 })
})

const whatsappProductUrl = computed(() => {
  const text = `Hello NMFFurniture, I would like to inquire about the ${props.product.name} (SKU: ${props.product.sku}) priced at RM ${formattedPrice.value}.`
  return `https://wa.me/${whatsappDefault}?text=${encodeURIComponent(text)}`
})

function addToCart() {
  const defaultVariant = props.product.variants?.[0]?.option
  cartStore.addItem(props.product, defaultVariant, 1)
}
</script>

<style scoped>
.product-wishlist-btn {
  position: absolute;
  top: 10px;
  right: 10px;
  z-index: 3;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.92);
  backdrop-filter: blur(4px);
  border: 1px solid rgba(0, 0, 0, 0.08);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #64748B;
  font-size: 0.85rem;
  transition: all 0.2s ease;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

.product-wishlist-btn:hover {
  background: #FFFFFF;
  color: #E11D48;
  transform: scale(1.1);
}

.product-wishlist-btn.active {
  color: #E11D48;
  background: #FFF1F2;
  border-color: #FECDD3;
}

.product-card-rating {
  display: flex;
  align-items: center;
  gap: 0.35rem;
  font-size: 0.72rem;
  color: #F59E0B;
  margin-top: 0.25rem;
  margin-bottom: 0.35rem;
}

.product-card-rating-num {
  font-weight: 700;
  color: var(--color-text-main);
}

.product-card-rating-verified {
  color: var(--color-text-muted);
  font-size: 0.7rem;
}
</style>

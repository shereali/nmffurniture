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
    </div>

    <div class="product-card-body">
      <div class="product-card-category" v-if="product.category">
        {{ product.category.name }}
      </div>
      <h3 class="product-card-name">
        <NuxtLink :to="`/shop/${product.slug}`">
          {{ product.name }}
        </NuxtLink>
      </h3>

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

const props = defineProps<{
  product: any
}>()

const cartStore = useCartStore()
const config = useRuntimeConfig()
const whatsappDefault = config.public.whatsappDefault

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

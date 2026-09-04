<template>
  <div class="py-12" style="background-color: var(--color-bg-alt); min-height: 85vh;">
    <div class="container">
      <div class="section-title" style="text-align: left; margin-bottom: 2.5rem;">
        <span class="section-eyebrow">YOUR SAVED FAVORITES</span>
        <h2>WISHLIST & COMPARISON</h2>
      </div>

      <!-- Empty State -->
      <div
        v-if="wishlistStore.items.length === 0"
        style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 5rem 2rem; text-align: center;"
      >
        <div style="width: 70px; height: 70px; border-radius: 50%; background: var(--color-bg-alt); color: var(--color-secondary-dark); display: inline-flex; align-items: center; justify-content: center; font-size: 2rem; margin-bottom: 1.5rem;">
          <i class="fa-regular fa-heart"></i>
        </div>
        <h3 style="font-size: 1.6rem; margin-bottom: 0.5rem;">Your Wishlist is Empty</h3>
        <p style="color: var(--color-text-muted); max-width: 440px; margin: 0 auto 2rem; line-height: 1.6;">
          Save your favorite handcrafted sofas, wing chairs, and dining pieces to compare finishes, sizing, and pricing anytime.
        </p>
        <NuxtLink to="/shop" class="btn btn-primary">
          Explore Collections
        </NuxtLink>
      </div>

      <!-- Wishlist Grid -->
      <div v-else>
        <div class="flex justify-between items-center" style="margin-bottom: 1.5rem;">
          <div style="font-size: 0.95rem; color: var(--color-text-muted);">
            You have <strong>{{ wishlistStore.count }}</strong> items saved
          </div>
          <button @click="wishlistStore.clearWishlist" class="btn btn-outline btn-sm" style="color: var(--color-danger); border-color: var(--color-danger);">
            Clear All
          </button>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2rem;">
          <div
            v-for="item in wishlistStore.items"
            :key="item.productId"
            class="product-card"
            style="background: #FFFFFF;"
          >
            <div class="product-card-thumb" style="position: relative;">
              <NuxtLink :to="`/shop/${item.slug}`">
                <img :src="item.image" :alt="item.name" loading="lazy" />
              </NuxtLink>
              <button
                @click="wishlistStore.removeFromWishlist(item.productId)"
                style="position: absolute; top: 12px; right: 12px; width: 34px; height: 34px; border-radius: 50%; background: rgba(255,255,255,0.9); border: none; color: #E74C3C; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: var(--shadow-sm);"
                title="Remove from Wishlist"
              >
                <i class="fa-solid fa-heart"></i>
              </button>
            </div>

            <div class="product-card-body">
              <div class="product-card-category" v-if="item.categoryName">
                {{ item.categoryName }}
              </div>
              <h3 class="product-card-name">
                <NuxtLink :to="`/shop/${item.slug}`">
                  {{ item.name }}
                </NuxtLink>
              </h3>

              <div class="product-card-price">
                <span class="price-current">
                  RM {{ (item.discountPrice ?? item.price).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
                </span>
                <span v-if="item.discountPrice" class="price-original">
                  RM {{ Number(item.price).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
                </span>
              </div>

              <div class="flex gap-2" style="margin-top: 1rem;">
                <button
                  @click="moveToCart(item)"
                  class="btn btn-primary btn-sm flex-1"
                >
                  <i class="fa-solid fa-bag-shopping"></i> Add to Bag
                </button>
                <NuxtLink
                  :to="`/shop/${item.slug}`"
                  class="btn btn-outline btn-sm"
                  title="View details"
                >
                  <i class="fa-solid fa-arrow-right"></i>
                </NuxtLink>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useWishlistStore } from '~/stores/wishlist'
import { useCartStore } from '~/stores/cart'

const wishlistStore = useWishlistStore()
const cartStore = useCartStore()

function moveToCart(item: any) {
  cartStore.addItem({
    id: item.productId,
    name: item.name,
    sku: item.sku,
    price: item.price,
    discount_price: item.discountPrice,
    images: [{ image_url: item.image }],
  })
}

onMounted(() => {
  wishlistStore.init()
})

useSeoMeta({
  title: 'My Wishlist | NMFFurniture Malaysia',
  description: 'View and manage your saved bespoke sofas and luxury living furniture pieces.',
})
</script>

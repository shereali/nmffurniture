<template>
  <div class="py-12" style="background-color: var(--color-bg-alt); min-height: 80vh;">
    <div class="container">
      <div class="section-title" style="text-align: left; margin-bottom: 2.5rem;">
        <span class="section-eyebrow">REVIEW ITEMS</span>
        <h2>YOUR SHOPPING BAG</h2>
      </div>

      <div v-if="cartStore.items.length === 0" style="background: #FFFFFF; border: 1px solid var(--color-border); padding: 5rem 2rem; text-align: center; border-radius: var(--radius-sm);">
        <i class="fa-solid fa-bag-shopping" style="font-size: 3.5rem; color: var(--color-border-dark); margin-bottom: 1.5rem;"></i>
        <h3>Your shopping bag is empty</h3>
        <p style="margin-bottom: 1.5rem; color: var(--color-text-muted);">Discover our handcrafted solid hardwood sofas, bedframes, and dining pieces.</p>
        <NuxtLink to="/shop" class="btn btn-primary">
          Start Exploring Collections
        </NuxtLink>
      </div>

      <div v-else class="cart-layout-grid">
        <!-- Desktop Cart Items Table -->
        <div class="desktop-only" style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); overflow: hidden;">
          <table class="data-table">
            <thead>
              <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in cartStore.items" :key="`${item.productId}-${item.variantOption}`">
                <td>
                  <div class="flex items-center gap-3">
                    <img :src="item.image" :alt="item.name" style="width: 70px; height: 70px; object-fit: cover; border-radius: var(--radius-sm);" />
                    <div>
                      <div style="font-weight: 600; color: var(--color-primary);">{{ item.name }}</div>
                      <div v-if="item.variantOption" style="font-size: 0.8rem; color: var(--color-secondary-dark); font-weight: 600;">
                        {{ item.variantOption }}
                      </div>
                      <div style="font-size: 0.75rem; color: var(--color-text-light);">SKU: {{ item.sku }}</div>
                    </div>
                  </div>
                </td>
                <td style="font-weight: 600;">
                  RM {{ Number(item.price).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
                </td>
                <td>
                  <div class="flex items-center" style="border: 1px solid var(--color-border); border-radius: var(--radius-sm); width: fit-content;">
                    <button @click="cartStore.updateQuantity(item.productId, item.variantOption, item.quantity - 1)" aria-label="Decrease quantity" style="padding: 0.2rem 0.6rem; background: transparent; border: none; cursor: pointer;">-</button>
                    <span style="padding: 0.2rem 0.6rem; font-weight: 600;">{{ item.quantity }}</span>
                    <button @click="cartStore.updateQuantity(item.productId, item.variantOption, item.quantity + 1)" aria-label="Increase quantity" style="padding: 0.2rem 0.6rem; background: transparent; border: none; cursor: pointer;">+</button>
                  </div>
                </td>
                <td style="font-weight: 700; color: var(--color-primary);">
                  RM {{ (item.price * item.quantity).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
                </td>
                <td>
                  <button @click="cartStore.removeItem(item.productId, item.variantOption)" aria-label="Remove item" style="background: transparent; border: none; color: var(--color-danger); cursor: pointer; padding: 0.4rem;">
                    <i class="fa-regular fa-trash-can"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Mobile Cart Items Cards (Shown on <= 768px) -->
        <div class="mobile-only flex flex-col gap-3">
          <div
            v-for="item in cartStore.items"
            :key="`m-${item.productId}-${item.variantOption}`"
            class="cart-item-card-mobile"
          >
            <div class="item-top">
              <img :src="item.image" :alt="item.name" class="item-img" />
              <div style="flex-grow: 1;">
                <div style="font-weight: 600; color: var(--color-primary); font-size: 0.95rem; margin-bottom: 0.25rem;">
                  {{ item.name }}
                </div>
                <div v-if="item.variantOption" style="font-size: 0.8rem; color: var(--color-secondary-dark); font-weight: 600; margin-bottom: 0.25rem;">
                  {{ item.variantOption }}
                </div>
                <div style="font-size: 0.9rem; font-weight: 700; color: var(--color-primary);">
                  RM {{ Number(item.price).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
                </div>
              </div>
            </div>

            <div class="item-bottom">
              <!-- Quantity Controls -->
              <div class="flex items-center" style="border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
                <button
                  @click="cartStore.updateQuantity(item.productId, item.variantOption, item.quantity - 1)"
                  aria-label="Decrease quantity"
                  style="padding: 0.3rem 0.75rem; background: transparent; border: none; cursor: pointer;"
                >-</button>
                <span style="padding: 0.3rem 0.75rem; font-weight: 600; font-size: 0.9rem;">{{ item.quantity }}</span>
                <button
                  @click="cartStore.updateQuantity(item.productId, item.variantOption, item.quantity + 1)"
                  aria-label="Increase quantity"
                  style="padding: 0.3rem 0.75rem; background: transparent; border: none; cursor: pointer;"
                >+</button>
              </div>

              <!-- Subtotal & Remove -->
              <div class="flex items-center gap-3">
                <span style="font-weight: 700; font-size: 0.95rem; color: var(--color-primary);">
                  RM {{ (item.price * item.quantity).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
                </span>
                <button
                  @click="cartStore.removeItem(item.productId, item.variantOption)"
                  aria-label="Remove item"
                  style="background: transparent; border: none; color: var(--color-danger); cursor: pointer; padding: 0.4rem;"
                >
                  <i class="fa-regular fa-trash-can"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Summary Order Box -->
        <div style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 2rem;">
          <h3 style="font-size: 1.35rem; margin-bottom: 1.25rem;">Order Summary</h3>

          <div class="flex justify-between items-center" style="margin-bottom: 0.75rem; font-size: 0.95rem;">
            <span style="color: var(--color-text-muted);">Items Total:</span>
            <span style="font-weight: 600;">RM {{ cartStore.subtotal.toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</span>
          </div>

          <div class="flex justify-between items-center" style="margin-bottom: 0.75rem; font-size: 0.95rem;">
            <span style="color: var(--color-text-muted);">Peninsular Shipping:</span>
            <span style="font-weight: 600; color: cartStore.shippingFee === 0 ? 'var(--color-success)' : 'inherit';">
              {{ cartStore.shippingFee === 0 ? 'FREE' : `RM ${cartStore.shippingFee.toFixed(2)}` }}
            </span>
          </div>

          <div class="flex justify-between items-center" style="margin-top: 1.25rem; padding-top: 1.25rem; border-top: 1px solid var(--color-border); margin-bottom: 1.5rem;">
            <span style="font-size: 1.15rem; font-weight: 700;">Grand Total:</span>
            <span style="font-size: 1.4rem; font-weight: 700; color: var(--color-primary); font-family: var(--font-sans);">
              RM {{ cartStore.total.toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
            </span>
          </div>

          <NuxtLink to="/checkout" class="btn btn-primary btn-lg" style="width: 100%; margin-bottom: 0.75rem;">
            <i class="fa-solid fa-lock"></i> Checkout Now
          </NuxtLink>

          <NuxtLink to="/shop" class="btn btn-outline btn-sm" style="width: 100%;">
            Continue Shopping
          </NuxtLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useCartStore } from '~/stores/cart'

const cartStore = useCartStore()

useSeoMeta({
  title: 'Your Shopping Bag | NMFFurniture Malaysia',
  robots: 'noindex, nofollow',
})
</script>

<style scoped>
.cart-layout-grid {
  display: grid;
  grid-template-columns: 1fr 380px;
  gap: 3rem;
  align-items: start;
}

@media (max-width: 900px) {
  .cart-layout-grid {
    grid-template-columns: 1fr;
    gap: 2rem;
  }
}
</style>

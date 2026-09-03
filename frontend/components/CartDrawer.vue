<template>
  <div>
    <!-- Overlay -->
    <div
      :class="['cart-drawer-overlay', { active: cartStore.isDrawerOpen }]"
      @click="cartStore.toggleDrawer(false)"
    ></div>

    <!-- Drawer Content -->
    <div :class="['cart-drawer', { active: cartStore.isDrawerOpen }]">
      <div class="cart-drawer-header">
        <div class="flex items-center gap-2">
          <h3 style="font-size: 1.25rem; font-weight: 700;">Shopping Bag</h3>
          <span style="font-size: 0.85rem; color: var(--color-text-light);">({{ cartStore.count }} items)</span>
        </div>
        <button
          @click="cartStore.toggleDrawer(false)"
          style="background: transparent; border: none; font-size: 1.25rem; cursor: pointer; color: var(--color-text-main);"
        >
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <!-- Free shipping meter -->
      <div style="background-color: var(--color-secondary-light); padding: 0.75rem 1.5rem; font-size: 0.85rem; border-bottom: 1px solid var(--color-border);">
        <div v-if="cartStore.subtotal >= 3000" style="color: var(--color-success); font-weight: 600;">
          <i class="fa-solid fa-truck-fast"></i> You have unlocked <strong>FREE DELIVERY</strong> across West Malaysia!
        </div>
        <div v-else style="color: var(--color-text-main);">
          Add <strong>RM {{ (3000 - cartStore.subtotal).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</strong> more to get <strong>FREE SHIPPING</strong>!
        </div>
      </div>

      <!-- Item List -->
      <div class="cart-drawer-body">
        <div v-if="cartStore.items.length === 0" style="text-align: center; padding: 4rem 1rem;">
          <i class="fa-solid fa-bag-shopping" style="font-size: 3rem; color: var(--color-border-dark); margin-bottom: 1rem;"></i>
          <h4 style="margin-bottom: 0.5rem;">Your bag is empty</h4>
          <p style="font-size: 0.88rem; color: var(--color-text-muted);">Explore our handcrafted living room collections and custom designs.</p>
          <NuxtLink to="/shop" class="btn btn-primary btn-sm" @click="cartStore.toggleDrawer(false)" style="margin-top: 1rem;">
            Explore Shop
          </NuxtLink>
        </div>

        <div v-else class="flex flex-col gap-4">
          <div
            v-for="item in cartStore.items"
            :key="`${item.productId}-${item.variantOption}`"
            style="display: flex; gap: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--color-border);"
          >
            <img
              :src="item.image"
              :alt="item.name"
              style="width: 80px; height: 80px; object-fit: cover; border-radius: var(--radius-sm);"
            />
            <div style="flex-grow: 1;">
              <h5 style="font-size: 0.95rem; font-weight: 600; line-height: 1.3; margin-bottom: 0.25rem;">
                {{ item.name }}
              </h5>
              <p v-if="item.variantOption" style="font-size: 0.8rem; color: var(--color-secondary-dark); font-weight: 600; margin-bottom: 0.4rem;">
                {{ item.variantOption }}
              </p>
              <div style="font-size: 0.95rem; font-weight: 700; color: var(--color-primary); margin-bottom: 0.5rem;">
                RM {{ (item.price * item.quantity).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
              </div>

              <div class="flex items-center justify-between">
                <!-- Quantity Controls -->
                <div class="flex items-center" style="border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
                  <button
                    @click="cartStore.updateQuantity(item.productId, item.variantOption, item.quantity - 1)"
                    style="padding: 0.2rem 0.6rem; background: transparent; border: none; cursor: pointer;"
                  >-</button>
                  <span style="padding: 0.2rem 0.6rem; font-size: 0.85rem; font-weight: 600;">{{ item.quantity }}</span>
                  <button
                    @click="cartStore.updateQuantity(item.productId, item.variantOption, item.quantity + 1)"
                    style="padding: 0.2rem 0.6rem; background: transparent; border: none; cursor: pointer;"
                  >+</button>
                </div>

                <button
                  @click="cartStore.removeItem(item.productId, item.variantOption)"
                  style="background: transparent; border: none; color: var(--color-text-light); font-size: 0.85rem; cursor: pointer;"
                >
                  <i class="fa-regular fa-trash-can"></i> Remove
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div v-if="cartStore.items.length > 0" class="cart-drawer-footer">
        <div class="flex justify-between items-center" style="margin-bottom: 0.5rem; font-size: 0.9rem;">
          <span style="color: var(--color-text-muted);">Subtotal:</span>
          <span style="font-weight: 600;">RM {{ cartStore.subtotal.toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</span>
        </div>
        <div class="flex justify-between items-center" style="margin-bottom: 0.75rem; font-size: 0.9rem;">
          <span style="color: var(--color-text-muted);">Shipping (Peninsular Malaysia):</span>
          <span style="font-weight: 600;">
            {{ cartStore.shippingFee === 0 ? 'FREE' : `RM ${cartStore.shippingFee.toFixed(2)}` }}
          </span>
        </div>
        <div class="flex justify-between items-center" style="margin-bottom: 1.25rem; font-size: 1.15rem; font-weight: 700; border-top: 1px solid var(--color-border); padding-top: 0.75rem;">
          <span>Estimated Total:</span>
          <span style="color: var(--color-primary); font-family: var(--font-sans);">
            RM {{ cartStore.total.toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
          </span>
        </div>

        <div class="flex flex-col gap-2">
          <NuxtLink
            to="/checkout"
            class="btn btn-primary"
            style="width: 100%;"
            @click="cartStore.toggleDrawer(false)"
          >
            <i class="fa-solid fa-lock"></i> Proceed to Checkout
          </NuxtLink>
          <a
            :href="whatsappCheckoutUrl"
            target="_blank"
            class="btn btn-whatsapp"
            style="width: 100%;"
          >
            <i class="fa-brands fa-whatsapp"></i> Order via WhatsApp Specialist
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useCartStore } from '~/stores/cart'
const cartStore = useCartStore()

const config = useRuntimeConfig()
const whatsappDefault = config.public.whatsappDefault

const whatsappCheckoutUrl = computed(() => {
  if (cartStore.items.length === 0) {
    return `https://wa.me/${whatsappDefault}?text=Hello%20NMFFurniture,%20I%20am%20interested%20in%20your%20sofa%20collection.`
  }

  const itemsList = cartStore.items.map(it => `- ${it.name} (${it.variantOption || 'Default'}) x${it.quantity} = RM ${(it.price * it.quantity).toFixed(2)}`).join('%0A')
  const msg = `Hello NMFFurniture!%0AI would like to place an order:%0A%0A${itemsList}%0A%0ATotal: RM ${cartStore.total.toFixed(2)}%0A%0APlease assist with my delivery schedule.`
  return `https://wa.me/${whatsappDefault}?text=${msg}`
})
</script>

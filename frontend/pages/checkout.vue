<template>
  <div class="py-12" style="background-color: var(--color-bg-alt); min-height: 85vh;">
    <div class="container">
      <div class="section-title" style="text-align: left; margin-bottom: 2.5rem;">
        <span class="section-eyebrow">ORDER FINALIZATION</span>
        <h2>CHECKOUT & DELIVERY</h2>
      </div>

      <div v-if="orderCompleted" style="max-width: 650px; margin: 2rem auto; background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 3rem 2rem; text-align: center;">
        <i class="fa-solid fa-circle-check" style="font-size: 3.5rem; color: #25D366; margin-bottom: 1.5rem;"></i>
        <h3 style="font-size: 1.8rem; margin-bottom: 0.5rem;">Order Successfully Placed!</h3>
        <p style="font-size: 1.05rem; color: var(--color-text-main); margin-bottom: 1rem;">
          Thank you for choosing NMFFurniture. Your order number is:
        </p>
        <div style="font-size: 1.4rem; font-weight: 700; color: var(--color-primary); background: var(--color-secondary-light); padding: 0.75rem 1.5rem; border-radius: var(--radius-sm); display: inline-block; margin-bottom: 1.5rem; letter-spacing: 0.05em;">
          {{ completedOrderNumber }}
        </div>
        <p style="font-size: 0.9rem; color: var(--color-text-muted); margin-bottom: 2rem;">
          Our logistics team will contact you on WhatsApp to confirm delivery scheduling and installation at your address.
        </p>

        <div class="flex gap-4 justify-center">
          <NuxtLink to="/shop" class="btn btn-primary">
            Continue Shopping
          </NuxtLink>
          <a
            :href="`https://wa.me/${whatsappDefault}?text=Hello%20NMFFurniture,%20I%20have%20just%20placed%20order%20${completedOrderNumber}.%20Please%20assist.`"
            target="_blank"
            class="btn btn-whatsapp"
          >
            <i class="fa-brands fa-whatsapp"></i> Chat Delivery Team
          </a>
        </div>
      </div>

      <div v-else-if="cartStore.items.length === 0" class="text-center py-20" style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
        <h3>Your shopping bag is empty</h3>
        <NuxtLink to="/shop" class="btn btn-primary" style="margin-top: 1rem;">Return to Shop</NuxtLink>
      </div>

      <form v-else @submit.prevent="placeOrder" style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 3rem; align-items: start;">
        <!-- Left: Customer Information & Delivery Address -->
        <div style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 2.5rem;">
          <h3 style="font-size: 1.35rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem;">
            1. Contact & Delivery Details
          </h3>

          <div class="grid grid-cols-2 gap-4">
            <div class="form-group">
              <label class="form-label">Full Name *</label>
              <input v-model="form.customer_name" type="text" class="form-input" required placeholder="e.g. Nurul Huda" />
            </div>
            <div class="form-group">
              <label class="form-label">WhatsApp Contact *</label>
              <input v-model="form.customer_phone" type="tel" class="form-input" required placeholder="e.g. +60123456789" />
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Email Address *</label>
            <input v-model="form.customer_email" type="email" class="form-input" required placeholder="e.g. nurul@example.com" />
          </div>

          <div class="form-group">
            <label class="form-label">Delivery Street Address *</label>
            <textarea v-model="form.shipping_address" class="form-textarea" rows="3" required placeholder="Unit number, Building / Condo name, Street"></textarea>
          </div>

          <div class="grid grid-cols-3 gap-4">
            <div class="form-group">
              <label class="form-label">City *</label>
              <input v-model="form.city" type="text" class="form-input" required placeholder="e.g. Shah Alam" />
            </div>
            <div class="form-group">
              <label class="form-label">State *</label>
              <select v-model="form.state" class="form-select" required>
                <option value="Selangor">Selangor</option>
                <option value="Kuala Lumpur">Kuala Lumpur</option>
                <option value="Johor">Johor</option>
                <option value="Penang">Penang</option>
                <option value="Perak">Perak</option>
                <option value="Melaka">Melaka</option>
                <option value="Negeri Sembilan">Negeri Sembilan</option>
                <option value="Pahang">Pahang</option>
                <option value="Kedah">Kedah</option>
                <option value="Terengganu">Terengganu</option>
                <option value="Kelantan">Kelantan</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Postal Code *</label>
              <input v-model="form.postal_code" type="text" class="form-input" required placeholder="e.g. 40150" />
            </div>
          </div>

          <h3 style="font-size: 1.35rem; margin: 2rem 0 1.5rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem;">
            2. Payment Method
          </h3>

          <div class="flex flex-col gap-3">
            <label style="display: flex; align-items: center; gap: 1rem; padding: 1rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); cursor: pointer;">
              <input type="radio" value="fpx_online_banking" v-model="form.payment_method" />
              <div>
                <div style="font-weight: 600;">FPX Online Banking / DuitNow QR (Instant Verification)</div>
                <div style="font-size: 0.8rem; color: var(--color-text-muted);">Maybank2u, CIMB Clicks, Public Bank, RHB, Hong Leong</div>
              </div>
            </label>

            <label style="display: flex; align-items: center; gap: 1rem; padding: 1rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); cursor: pointer;">
              <input type="radio" value="credit_debit_card" v-model="form.payment_method" />
              <div>
                <div style="font-weight: 600;">Credit / Debit Card (Visa / Mastercard)</div>
                <div style="font-size: 0.8rem; color: var(--color-text-muted);">Secure 256-bit SSL encrypted gateway</div>
              </div>
            </label>

            <label style="display: flex; align-items: center; gap: 1rem; padding: 1rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); cursor: pointer;">
              <input type="radio" value="whatsapp_order" v-model="form.payment_method" />
              <div>
                <div style="font-weight: 600;">Pay via Showroom Invoice / WhatsApp Assisted Transfer</div>
                <div style="font-size: 0.8rem; color: var(--color-text-muted);">Confirm order first, our consultant will send official company bank details.</div>
              </div>
            </label>
          </div>

          <div class="form-group" style="margin-top: 1.5rem;">
            <label class="form-label">Special Delivery / Custom Notes</label>
            <input v-model="form.notes" type="text" class="form-input" placeholder="e.g. Lift access available, please call 1 hour before arrival" />
          </div>
        </div>

        <!-- Right: Order Summary -->
        <div style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 2rem; position: sticky; top: 90px;">
          <h3 style="font-size: 1.35rem; margin-bottom: 1.25rem;">Order Review</h3>

          <div class="flex flex-col gap-3" style="max-height: 260px; overflow-y: auto; padding-right: 0.5rem; margin-bottom: 1.5rem;">
            <div
              v-for="item in cartStore.items"
              :key="`${item.productId}-${item.variantOption}`"
              class="flex items-center justify-between"
              style="font-size: 0.875rem; border-bottom: 1px solid var(--color-bg-muted); padding-bottom: 0.6rem;"
            >
              <div>
                <div style="font-weight: 600;">{{ item.name }} x{{ item.quantity }}</div>
                <div v-if="item.variantOption" style="font-size: 0.75rem; color: var(--color-secondary-dark);">
                  {{ item.variantOption }}
                </div>
              </div>
              <span style="font-weight: 700;">
                RM {{ (item.price * item.quantity).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
              </span>
            </div>
          </div>

          <div class="flex justify-between items-center" style="margin-bottom: 0.6rem; font-size: 0.9rem;">
            <span style="color: var(--color-text-muted);">Subtotal:</span>
            <span style="font-weight: 600;">RM {{ cartStore.subtotal.toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</span>
          </div>

          <div class="flex justify-between items-center" style="margin-bottom: 0.6rem; font-size: 0.9rem;">
            <span style="color: var(--color-text-muted);">Shipping:</span>
            <span style="font-weight: 600; color: #2E7D32;">
              {{ cartStore.shippingFee === 0 ? 'FREE' : `RM ${cartStore.shippingFee.toFixed(2)}` }}
            </span>
          </div>

          <div class="flex justify-between items-center" style="margin: 1.25rem 0; padding-top: 1rem; border-top: 1px solid var(--color-border); font-size: 1.3rem; font-weight: 700;">
            <span>Total:</span>
            <span style="color: var(--color-primary); font-family: var(--font-sans);">
              RM {{ cartStore.total.toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
            </span>
          </div>

          <button :disabled="submitting" type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
            <i class="fa-solid fa-check"></i>
            {{ submitting ? 'Processing Order...' : 'Place Order Now' }}
          </button>

          <div style="font-size: 0.75rem; text-align: center; color: var(--color-text-light); margin-top: 1rem;">
            <i class="fa-solid fa-lock"></i> SSL Encrypted & Protected by 5-Year Wood Warranty
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useCartStore } from '~/stores/cart'
import { useAuthStore } from '~/stores/auth'
import { useToastStore } from '~/stores/toast'

const config = useRuntimeConfig()
const apiBase = config.public.apiBase
const whatsappDefault = config.public.whatsappDefault

const cartStore = useCartStore()
const authStore = useAuthStore()
const toast = useToastStore()

const submitting = ref(false)
const orderCompleted = ref(false)
const completedOrderNumber = ref('')

const form = reactive({
  customer_name: authStore.user?.name || '',
  customer_email: authStore.user?.email || '',
  customer_phone: authStore.user?.phone || '',
  shipping_address: authStore.user?.address || '',
  city: authStore.user?.city || 'Shah Alam',
  state: authStore.user?.state || 'Selangor',
  postal_code: authStore.user?.postal_code || '40150',
  payment_method: 'fpx_online_banking',
  notes: '',
})

useSeoMeta({
  title: 'Secure Checkout & Delivery Details | NMFFurniture Malaysia',
  robots: 'noindex, nofollow',
})

async function placeOrder() {
  if (cartStore.items.length === 0) return
  submitting.value = true

  try {
    const payload = {
      ...form,
      items: cartStore.items.map(it => ({
        product_id: it.productId,
        variant_option: it.variantOption,
        quantity: it.quantity,
      }))
    }

    const headers: any = {}
    if (authStore.token) {
      headers['Authorization'] = `Bearer ${authStore.token}`
    }

    const res: any = await $fetch(`${apiBase}/orders`, {
      method: 'POST',
      body: payload,
      headers,
    })

    completedOrderNumber.value = res.order.order_number
    orderCompleted.value = true
    cartStore.clearCart()
    toast.show('Order placed successfully!', 'success')
  } catch (err: any) {
    const msg = err.data?.message || 'Failed to place order. Please check inputs.'
    toast.show(msg, 'error')
  } finally {
    submitting.value = false
  }
}
</script>

<style scoped>
@media (max-width: 900px) {
  form[style*="grid-template-columns: 1.2fr 0.8fr"] {
    grid-template-columns: 1fr !important;
  }
}

@media (max-width: 640px) {
  div[style*="padding: 2.5rem"] {
    padding: 1.25rem !important;
  }
}
</style>

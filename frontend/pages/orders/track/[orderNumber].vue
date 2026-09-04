<template>
  <div class="py-12" style="background-color: var(--color-bg-alt); min-height: 85vh;">
    <div class="container" style="max-width: 820px;">
      <!-- Breadcrumb -->
      <nav style="display: flex; gap: 0.5rem; font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 2rem;">
        <NuxtLink to="/">Home</NuxtLink>
        <span>/</span>
        <NuxtLink to="/track-order">Order Tracking</NuxtLink>
        <span>/</span>
        <span style="color: var(--color-primary); font-weight: 600;">{{ orderNumber }}</span>
      </nav>

      <!-- Loading State -->
      <div v-if="loading" style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 4rem 2rem; text-align: center;">
        <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 2.5rem; color: var(--color-secondary);"></i>
        <p style="margin-top: 1rem; color: var(--color-text-muted);">Fetching real-time fulfillment status...</p>
      </div>

      <!-- Error / Not Found -->
      <div v-else-if="error" style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 4rem 2rem; text-align: center;">
        <i class="fa-solid fa-triangle-exclamation" style="font-size: 3rem; color: var(--color-danger); margin-bottom: 1rem;"></i>
        <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem;">Order Not Found</h3>
        <p style="color: var(--color-text-muted); max-width: 420px; margin: 0 auto 2rem;">
          We couldn't locate an order with reference <strong>{{ orderNumber }}</strong>. Please verify your order number on your receipt or WhatsApp confirmation.
        </p>
        <NuxtLink to="/track-order" class="btn btn-primary">
          Try Another Order Number
        </NuxtLink>
      </div>

      <!-- Order Details & Timeline -->
      <div v-else-if="order" style="display: flex; flex-direction: column; gap: 2rem;">
        <!-- Header Card -->
        <div style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 2rem;">
          <div class="flex justify-between items-start" style="flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--color-border); padding-bottom: 1rem;">
            <div>
              <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--color-secondary-dark); letter-spacing: 0.05em;">
                LIVE FACTORY TRACKING
              </span>
              <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--color-primary); margin-top: 0.25rem;">
                Order #{{ order.order_number }}
              </h1>
              <div style="font-size: 0.85rem; color: var(--color-text-muted);">
                Placed on {{ new Date(order.created_at).toLocaleDateString(undefined, { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
              </div>
            </div>

            <div style="text-align: right;">
              <span :class="['badge-status', order.order_status]" style="font-size: 0.85rem; padding: 0.35rem 0.85rem;">
                {{ order.order_status }}
              </span>
              <div style="font-size: 0.8rem; color: var(--color-text-light); margin-top: 0.4rem;">
                Payment: <strong style="text-transform: uppercase;">{{ order.payment_status }}</strong>
              </div>
              <div style="margin-top: 0.5rem;">
                <button
                  @click="isInvoiceOpen = true"
                  class="btn btn-outline btn-sm"
                  style="font-size: 0.75rem; padding: 0.25rem 0.6rem;"
                >
                  <i class="fa-solid fa-file-invoice"></i> Official Invoice
                </button>
              </div>
            </div>
          </div>


          <!-- 5-Step Visual Progress Bar -->
          <div style="margin: 2.5rem 0 1rem;">
            <div style="display: flex; justify-content: space-between; position: relative;">
              <!-- Connecting Line -->
              <div
                style="position: absolute; top: 20px; left: 20px; right: 20px; height: 4px; background: var(--color-border); z-index: 1;"
              >
                <div
                  :style="{
                    height: '100%',
                    background: order.order_status === 'cancelled' ? 'var(--color-danger)' : 'var(--color-secondary)',
                    width: `${stepProgressPercent}%`,
                    transition: 'width 0.5s ease'
                  }"
                ></div>
              </div>

              <!-- Steps -->
              <div
                v-for="(step, idx) in trackerSteps"
                :key="step.title"
                style="display: flex; flex-direction: column; align-items: center; position: relative; z-index: 2; width: 80px; text-align: center;"
              >
                <div
                  :style="{
                    width: '40px',
                    height: '40px',
                    borderRadius: '50%',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    background: idx <= currentStepIndex ? 'var(--color-secondary)' : '#FFFFFF',
                    border: idx <= currentStepIndex ? '2px solid var(--color-secondary)' : '2px solid var(--color-border)',
                    color: idx <= currentStepIndex ? '#FFFFFF' : 'var(--color-text-light)',
                    fontWeight: 700,
                    marginBottom: '0.5rem',
                    boxShadow: idx === currentStepIndex ? '0 0 0 4px var(--color-secondary-light)' : 'none',
                  }"
                >
                  <i :class="step.icon" style="font-size: 0.9rem;"></i>
                </div>
                <div style="font-size: 0.75rem; font-weight: 700; color: idx <= currentStepIndex ? 'var(--color-primary)' : 'var(--color-text-muted);'; line-height: 1.2;">
                  {{ step.title }}
                </div>
              </div>
            </div>
          </div>

          <div v-if="order.order_status === 'cancelled'" style="background: #FCE8E6; border: 1px solid #F5C6CB; padding: 1rem; border-radius: var(--radius-sm); color: #721C24; font-size: 0.9rem; margin-top: 1.5rem; text-align: center;">
            <i class="fa-solid fa-circle-xmark"></i> This order was cancelled. Please contact our support if you have any questions.
          </div>
        </div>

        <!-- Itemized Order Breakdown -->
        <div style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 2rem;">
          <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem;">
            Order Items ({{ order.items?.length || 0 }})
          </h3>

          <div class="flex flex-col gap-4">
            <div
              v-for="it in order.items"
              :key="it.id"
              class="flex justify-between items-center"
              style="padding-bottom: 1rem; border-bottom: 1px solid var(--color-bg-muted);"
            >
              <div>
                <div style="font-weight: 700; color: var(--color-primary); font-size: 1rem;">
                  {{ it.product_name }}
                </div>
                <div v-if="it.variant_option" style="font-size: 0.8rem; color: var(--color-secondary-dark); font-weight: 600;">
                  Fabric / Variant: {{ it.variant_option }}
                </div>
                <div v-if="it.product_sku" style="font-size: 0.75rem; color: var(--color-text-light); font-family: monospace;">
                  SKU: {{ it.product_sku }}
                </div>
              </div>

              <div style="text-align: right;">
                <div style="font-size: 0.85rem; color: var(--color-text-muted);">
                  Qty: {{ it.quantity }} × RM {{ Number(it.price).toFixed(2) }}
                </div>
                <div style="font-weight: 700; font-size: 1rem; color: var(--color-primary);">
                  RM {{ Number(it.total).toFixed(2) }}
                </div>
              </div>
            </div>
          </div>

          <!-- Total Summary Block -->
          <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--color-border); display: flex; flex-direction: column; gap: 0.5rem; align-items: flex-end;">
            <div class="flex justify-between" style="width: 240px; font-size: 0.9rem;">
              <span style="color: var(--color-text-muted);">Subtotal:</span>
              <span style="font-weight: 600;">RM {{ Number(order.subtotal).toFixed(2) }}</span>
            </div>
            <div v-if="order.discount_amount > 0" class="flex justify-between" style="width: 240px; font-size: 0.9rem; color: #2E7D32;">
              <span>Promo Discount:</span>
              <span style="font-weight: 700;">-RM {{ Number(order.discount_amount).toFixed(2) }}</span>
            </div>
            <div class="flex justify-between" style="width: 240px; font-size: 0.9rem;">
              <span style="color: var(--color-text-muted);">Shipping:</span>
              <span style="font-weight: 600;">
                {{ Number(order.shipping_fee) === 0 ? 'FREE' : `RM ${Number(order.shipping_fee).toFixed(2)}` }}
              </span>
            </div>
            <div class="flex justify-between" style="width: 240px; font-size: 1.25rem; font-weight: 700; color: var(--color-primary); border-top: 1px solid var(--color-border); padding-top: 0.5rem;">
              <span>Total:</span>
              <span>RM {{ Number(order.total).toFixed(2) }}</span>
            </div>
          </div>
        </div>

        <!-- Destination & Dispatch Support -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
          <div style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 1.5rem;">
            <h4 style="font-size: 1rem; font-weight: 700; margin-bottom: 0.75rem;">
              Delivery Destination
            </h4>
            <div style="font-size: 0.9rem; line-height: 1.6;">
              <strong>{{ order.customer_name }}</strong><br />
              {{ order.customer_phone }}<br />
              {{ order.shipping_address }}<br />
              {{ order.postal_code }} {{ order.city }}, {{ order.state }}
            </div>
          </div>

          <div style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
              <h4 style="font-size: 1rem; font-weight: 700; margin-bottom: 0.75rem;">
                Need Delivery Assistance?
              </h4>
              <p style="font-size: 0.85rem; color: var(--color-text-muted); line-height: 1.5;">
                Have questions regarding delivery time slots, floor elevator access, or custom scheduling? Chat directly with our logistics team.
              </p>
            </div>

            <a
              :href="`https://wa.me/${whatsappDefault}?text=Hello%20NMFFurniture%20Logistics,%20I%20am%20checking%20on%20my%20order%20${order.order_number}.`"
              target="_blank"
              class="btn btn-whatsapp"
              style="width: 100%; justify-content: center;"
            >
              <i class="fa-brands fa-whatsapp"></i> Chat Delivery Team
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Official Invoice & Packing Slip Modal -->
    <InvoiceModal
      v-if="isInvoiceOpen && order"
      :order="order"
      @close="isInvoiceOpen = false"
    />
  </div>
</template>

<script setup lang="ts">
import InvoiceModal from '~/components/InvoiceModal.vue'

const route = useRoute()
const config = useRuntimeConfig()
const apiBase = config.public.apiBase
const whatsappDefault = config.public.whatsappDefault

const orderNumber = computed(() => route.params.orderNumber as string)
const order = ref<any>(null)
const loading = ref(true)
const error = ref(false)
const isInvoiceOpen = ref(false)


const trackerSteps = [
  { title: 'Order Placed', icon: 'fa-solid fa-file-lines' },
  { title: 'In Assembly', icon: 'fa-solid fa-hammer' },
  { title: 'Inspection', icon: 'fa-solid fa-clipboard-check' },
  { title: 'Out for Delivery', icon: 'fa-solid fa-truck-fast' },
  { title: 'Delivered', icon: 'fa-solid fa-house-chimney' },
]

const currentStepIndex = computed(() => {
  if (!order.value) return 0
  switch (order.value.order_status) {
    case 'pending': return 0
    case 'processing': return 1
    case 'shipped': return 3
    case 'delivered': return 4
    case 'cancelled': return 0
    default: return 1
  }
})

const stepProgressPercent = computed(() => {
  if (!order.value) return 0
  if (order.value.order_status === 'cancelled') return 100
  return (currentStepIndex.value / (trackerSteps.length - 1)) * 100
})

async function fetchOrder() {
  loading.value = true
  error.value = false
  try {
    const res: any = await $fetch(`${apiBase}/orders/track/${orderNumber.value}`)
    order.value = res.order
  } catch (err) {
    error.value = true
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  if (orderNumber.value) {
    fetchOrder()
  }
})

useSeoMeta({
  title: computed(() => `Track Order #${orderNumber.value} | NMFFurniture Malaysia`),
  robots: 'noindex, nofollow',
})
</script>

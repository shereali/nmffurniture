<template>
  <div v-if="isOpen" class="modal-backdrop" @click.self="closeModal">
    <div class="welcome-modal-card animate-slide-up">
      <button @click="closeModal" class="close-btn" title="Close offer">&times;</button>
      
      <div class="welcome-modal-grid">
        <!-- Visual Image Half -->
        <div class="welcome-image-side">
          <img
            src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=800&q=80"
            alt="Handcrafted Meranti Sofa"
          />
          <div class="image-overlay">
            <span class="offer-pill">MALAYSIAN HANDCRAFTED</span>
            <h3>Bespoke Luxury For Your Home</h3>
            <p>5-Year Solid Meranti Hardwood Frame Warranty</p>
          </div>
        </div>

        <!-- Form Offer Half -->
        <div class="welcome-form-side">
          <div class="welcome-badge">FIRST-TIME HOMEOWNER PRIVILEGE</div>
          <h2 class="welcome-title">
            Take <span style="color: var(--color-primary); font-family: var(--font-heading);">RM 100 Off</span> Your First Order
          </h2>
          <p class="welcome-sub">
            Plus receive complimentary physical fabric and velvet swatches mailed to your doorstep anywhere in Peninsular Malaysia.
          </p>

          <form @submit.prevent="claimOffer" class="flex flex-col gap-3">
            <div>
              <label class="form-label" style="font-size: 0.8rem;">Full Name *</label>
              <input
                type="text"
                v-model="form.name"
                required
                class="form-control"
                placeholder="e.g. Nurul Huda"
              />
            </div>

            <div>
              <label class="form-label" style="font-size: 0.8rem;">WhatsApp / Mobile Number *</label>
              <input
                type="tel"
                v-model="form.phone"
                required
                class="form-control"
                placeholder="012-3456789"
              />
            </div>

            <div>
              <label class="form-label" style="font-size: 0.8rem;">Email Address (For Instant Voucher Copy)</label>
              <input
                type="email"
                v-model="form.email"
                class="form-control"
                placeholder="huda@gmail.com"
              />
            </div>

            <button
              type="submit"
              class="btn btn-primary"
              style="margin-top: 0.5rem; padding: 0.85rem;"
              :disabled="loading"
            >
              <i v-if="loading" class="fa-solid fa-spinner fa-spin"></i>
              <span v-else><i class="fa-solid fa-tag"></i> Claim RM 100 Voucher Now</span>
            </button>

            <div class="offer-guarantee">
              <i class="fa-solid fa-shield-check" style="color: #2E7D32;"></i>
              <span>Coupon code <strong>WELCOME100</strong> will be automatically applied to your bag.</span>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useCartStore } from '~/stores/cart'
import { useToastStore } from '~/stores/toast'

const cartStore = useCartStore()
const toast = useToastStore()
const config = useRuntimeConfig()
const apiBase = config.public.apiBase

const isOpen = ref(false)
const loading = ref(false)

const form = reactive({
  name: '',
  phone: '',
  email: '',
})

function closeModal() {
  isOpen.value = false
  if (import.meta.client) {
    sessionStorage.setItem('nmf_welcome_closed', 'true')
  }
}

async function claimOffer() {
  loading.value = true
  try {
    // Record lead in backend inquiries
    await $fetch(`${apiBase}/inquiries`, {
      method: 'POST',
      body: {
        name: form.name,
        phone: form.phone,
        email: form.email || undefined,
        subject: 'Claimed WELCOME100 Voucher & Free Swatches',
        message: `Customer claimed RM 100 discount voucher (WELCOME100). Mobile: ${form.phone}`,
      }
    })

    // Auto-apply coupon in Pinia cart store
    await cartStore.applyCoupon('WELCOME100')

    // Mark as welcomed
    if (import.meta.client) {
      localStorage.setItem('nmf_welcomed', 'true')
      sessionStorage.setItem('nmf_welcome_closed', 'true')
    }

    toast.show('Voucher WELCOME100 applied! RM 100 discount activated.', 'success')
    isOpen.value = false
  } catch (e) {
    // Fallback: still apply coupon for customer
    cartStore.applyCoupon('WELCOME100')
    isOpen.value = false
    toast.show('WELCOME100 voucher unlocked!', 'success')
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  if (import.meta.client) {
    const isWelcomed = localStorage.getItem('nmf_welcomed')
    const isClosedSession = sessionStorage.getItem('nmf_welcome_closed')

    if (!isWelcomed && !isClosedSession) {
      setTimeout(() => {
        isOpen.value = true
      }, 7000)
    }
  }
})
</script>

<style scoped>
.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.7);
  backdrop-filter: blur(5px);
  z-index: 1500;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
}

.welcome-modal-card {
  background: #FFFFFF;
  border-radius: var(--radius-sm);
  width: 100%;
  max-width: 780px;
  overflow: hidden;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
  position: relative;
}

.close-btn {
  position: absolute;
  top: 14px;
  right: 16px;
  background: rgba(255, 255, 255, 0.85);
  border: none;
  font-size: 1.5rem;
  width: 34px;
  height: 34px;
  border-radius: 50%;
  cursor: pointer;
  z-index: 10;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #64748B;
  transition: all 0.2s;
}

.close-btn:hover {
  color: #161719;
  background: #FFFFFF;
}

.welcome-modal-grid {
  display: grid;
  grid-template-columns: 1fr 1.25fr;
}

.welcome-image-side {
  position: relative;
  background: #161719;
  min-height: 440px;
}

.welcome-image-side img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  opacity: 0.75;
}

.image-overlay {
  position: absolute;
  bottom: 2rem;
  left: 1.5rem;
  right: 1.5rem;
  color: #FFFFFF;
}

.offer-pill {
  font-size: 0.65rem;
  font-weight: 800;
  letter-spacing: 0.1em;
  background: var(--color-secondary-dark);
  color: #FFFFFF;
  padding: 0.2rem 0.55rem;
  border-radius: 3px;
  display: inline-block;
  margin-bottom: 0.5rem;
}

.image-overlay h3 {
  font-size: 1.25rem;
  font-family: var(--font-heading);
  margin-bottom: 0.3rem;
  color: #FFFFFF;
}

.image-overlay p {
  font-size: 0.78rem;
  color: rgba(255, 255, 255, 0.8);
  margin-bottom: 0;
}

.welcome-form-side {
  padding: 2.5rem 2rem;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.welcome-badge {
  font-size: 0.72rem;
  font-weight: 800;
  color: var(--color-secondary-dark);
  letter-spacing: 0.08em;
  margin-bottom: 0.4rem;
}

.welcome-title {
  font-size: 1.65rem;
  font-family: var(--font-heading);
  margin-bottom: 0.5rem;
  line-height: 1.25;
}

.welcome-sub {
  font-size: 0.85rem;
  color: var(--color-text-muted);
  line-height: 1.5;
  margin-bottom: 1.25rem;
}

.offer-guarantee {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.75rem;
  color: var(--color-text-muted);
  margin-top: 0.35rem;
}

@media (max-width: 768px) {
  .welcome-modal-grid {
    grid-template-columns: 1fr;
  }
  .welcome-image-side {
    display: none;
  }
}
</style>

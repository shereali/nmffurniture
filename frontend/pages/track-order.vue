<template>
  <div class="py-16" style="background-color: var(--color-bg-alt); min-height: 80vh; display: flex; align-items: center;">
    <div class="container" style="max-width: 520px;">
      <div style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 3rem 2.5rem; box-shadow: var(--shadow-sm); text-align: center;">
        <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--color-secondary-light); color: var(--color-secondary-dark); display: inline-flex; align-items: center; justify-content: center; font-size: 1.6rem; margin-bottom: 1.5rem;">
          <i class="fa-solid fa-truck-fast"></i>
        </div>

        <h1 style="font-size: 1.75rem; font-family: var(--font-heading); margin-bottom: 0.5rem;">
          Track Your Furniture Order
        </h1>
        <p style="font-size: 0.9rem; color: var(--color-text-muted); margin-bottom: 2rem; line-height: 1.5;">
          Enter your order reference number (e.g. <strong>NMF-XXXX-1234</strong>) to view real-time carpentry assembly and delivery milestones.
        </p>

        <form @submit.prevent="handleTrack" class="flex flex-col gap-4">
          <div class="form-group" style="text-align: left;">
            <label class="form-label">Order Number</label>
            <input
              v-model="orderNumberInput"
              type="text"
              class="form-input"
              required
              placeholder="e.g. NMF-ABCD-1234"
              style="text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; font-size: 1rem; text-align: center;"
            />
          </div>

          <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
            <i class="fa-solid fa-magnifying-glass"></i> Track Status
          </button>
        </form>

        <div style="margin-top: 2rem; border-top: 1px solid var(--color-border); padding-top: 1.5rem; font-size: 0.85rem; color: var(--color-text-muted);">
          Can't locate your order number? Check your order confirmation WhatsApp message or email receipt.
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const router = useRouter()
const orderNumberInput = ref('')

function handleTrack() {
  const clean = orderNumberInput.value.trim().toUpperCase()
  if (clean) {
    router.push(`/orders/track/${clean}`)
  }
}

useSeoMeta({
  title: 'Track Your Order | NMFFurniture Malaysia',
  description: 'Track your handcrafted sofa and living furniture assembly and delivery progress live with NMFFurniture.',
})
</script>

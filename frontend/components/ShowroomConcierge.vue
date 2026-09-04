<template>
  <div class="concierge-wrapper">
    <!-- Popover Card -->
    <div v-if="isOpen" class="concierge-popover animate-slide-up">
      <div class="concierge-popover-header">
        <div class="flex items-center gap-2.5">
          <div class="concierge-avatar">
            <i class="fa-solid fa-couch"></i>
          </div>
          <div>
            <div style="font-weight: 700; font-size: 0.95rem; color: #FFFFFF;">
              NMF Factory Concierge
            </div>
            <div class="flex items-center gap-1.5" style="font-size: 0.72rem; color: #A7F3D0;">
              <span class="online-indicator"></span> Online • Bukit Jelutong Hub
            </div>
          </div>
        </div>
        <button @click="isOpen = false" class="concierge-close">&times;</button>
      </div>

      <div class="concierge-popover-body">
        <p style="font-size: 0.85rem; color: var(--color-text-main); margin-bottom: 1rem; line-height: 1.5;">
          Hello! How can our Malaysian furniture specialists assist your home today?
        </p>

        <div class="flex flex-col gap-2">
          <!-- Action 1: Book Showroom -->
          <a
            :href="whatsappShowroomUrl"
            target="_blank"
            class="concierge-action-btn"
          >
            <div class="concierge-action-icon" style="background: #E0F2FE; color: #0284C7;">
              <i class="fa-solid fa-location-dot"></i>
            </div>
            <div>
              <div class="action-title">Book Showroom Visit</div>
              <div class="action-sub">Shah Alam & Petaling Jaya Flagships</div>
            </div>
          </a>

          <!-- Action 2: Custom Sizing & Fabrics -->
          <a
            :href="whatsappCustomUrl"
            target="_blank"
            class="concierge-action-btn"
          >
            <div class="concierge-action-icon" style="background: #FEF3C7; color: #D97706;">
              <i class="fa-solid fa-swatchbook"></i>
            </div>
            <div>
              <div class="action-title">200+ Free Fabric Swatches</div>
              <div class="action-sub">Free postal delivery to your home</div>
            </div>
          </a>

          <!-- Action 3: Track Order -->
          <NuxtLink
            to="/track-order"
            @click="isOpen = false"
            class="concierge-action-btn"
          >
            <div class="concierge-action-icon" style="background: #EDE9FE; color: #7C3AED;">
              <i class="fa-solid fa-truck-fast"></i>
            </div>
            <div>
              <div class="action-title">Track My Order</div>
              <div class="action-sub">Real-time factory delivery timeline</div>
            </div>
          </NuxtLink>
        </div>

        <div style="margin-top: 1rem; padding-top: 0.75rem; border-top: 1px solid var(--color-border); text-align: center;">
          <a
            :href="`https://wa.me/${whatsappDefault}?text=${encodeURIComponent('Hello NMFFurniture, I would like to speak directly with your factory specialist.')}`"
            target="_blank"
            class="btn btn-whatsapp btn-sm"
            style="width: 100%;"
          >
            <i class="fa-brands fa-whatsapp"></i> Chat Directly on WhatsApp
          </a>
        </div>
      </div>
    </div>

    <!-- Floating Trigger Bubble -->
    <button
      @click="isOpen = !isOpen"
      class="concierge-trigger"
      :class="{ active: isOpen }"
      title="NMF Malaysian Furniture Concierge"
    >
      <div v-if="!isOpen" class="flex items-center gap-2">
        <i class="fa-brands fa-whatsapp" style="font-size: 1.35rem; color: #25D366;"></i>
        <span class="trigger-text">Showrooms & WhatsApp</span>
      </div>
      <i v-else class="fa-solid fa-xmark" style="font-size: 1.25rem;"></i>
      <span v-if="!isOpen" class="trigger-pulse"></span>
    </button>
  </div>
</template>

<script setup lang="ts">
const config = useRuntimeConfig()
const whatsappDefault = config.public.whatsappDefault || '60192589920'
const isOpen = ref(false)

const whatsappShowroomUrl = computed(() => {
  const text = 'Hello NMFFurniture, I would like to schedule a visit to your showroom in Shah Alam / PJ to view your handcrafted sofas.'
  return `https://wa.me/${whatsappDefault}?text=${encodeURIComponent(text)}`
})

const whatsappCustomUrl = computed(() => {
  const text = 'Hello NMFFurniture, I would like to request free fabric and velvet swatches mailed to my home address.'
  return `https://wa.me/${whatsappDefault}?text=${encodeURIComponent(text)}`
})
</script>

<style scoped>
.concierge-wrapper {
  position: fixed;
  bottom: 24px;
  right: 24px;
  z-index: 1000;
}

.concierge-trigger {
  background: #161719;
  color: #FFFFFF;
  border: 1px solid rgba(255, 255, 255, 0.15);
  border-radius: 40px;
  padding: 0.75rem 1.25rem;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
  transition: all 0.25s ease;
  position: relative;
}

.concierge-trigger:hover {
  transform: translateY(-2px);
  box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.4);
  background: #232529;
}

.concierge-trigger.active {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  padding: 0;
}

.trigger-text {
  font-family: var(--font-sans);
  font-size: 0.85rem;
  font-weight: 700;
  letter-spacing: 0.02em;
}

.trigger-pulse {
  position: absolute;
  top: -3px;
  right: -3px;
  width: 12px;
  height: 12px;
  background-color: #25D366;
  border-radius: 50%;
  border: 2px solid #FFFFFF;
}

.concierge-popover {
  position: absolute;
  bottom: calc(100% + 14px);
  right: 0;
  width: 330px;
  background: #FFFFFF;
  border-radius: var(--radius-sm);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
  overflow: hidden;
  border: 1px solid var(--color-border);
}

.concierge-popover-header {
  background: #161719;
  padding: 1rem 1.25rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.concierge-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: var(--color-secondary-dark);
  color: #FFFFFF;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.95rem;
}

.online-indicator {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: #25D366;
  display: inline-block;
}

.concierge-close {
  background: none;
  border: none;
  color: #94A3B8;
  font-size: 1.35rem;
  cursor: pointer;
}

.concierge-close:hover {
  color: #FFFFFF;
}

.concierge-popover-body {
  padding: 1.25rem;
}

.concierge-action-btn {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.7rem;
  border-radius: var(--radius-sm);
  border: 1px solid var(--color-border);
  text-decoration: none;
  color: inherit;
  transition: all 0.2s ease;
}

.concierge-action-btn:hover {
  background: var(--color-bg-alt);
  border-color: var(--color-secondary);
}

.concierge-action-icon {
  width: 36px;
  height: 36px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.95rem;
  flex-shrink: 0;
}

.action-title {
  font-weight: 700;
  font-size: 0.85rem;
  color: var(--color-primary);
}

.action-sub {
  font-size: 0.72rem;
  color: var(--color-text-muted);
}
</style>

<template>
  <div
    v-if="currentNotification && isVisible"
    class="social-proof-pill animate-slide-up"
  >
    <button @click="dismiss" class="social-proof-close">&times;</button>
    <div class="flex items-center gap-3">
      <div class="social-proof-img">
        <img :src="currentNotification.image" :alt="currentNotification.item" />
      </div>
      <div>
        <div class="social-proof-person">
          {{ currentNotification.name }} in {{ currentNotification.location }}
        </div>
        <div class="social-proof-action">
          {{ currentNotification.action }} <span class="social-proof-item">{{ currentNotification.item }}</span>
        </div>
        <div class="social-proof-time">
          <i class="fa-solid fa-clock" style="font-size: 0.65rem;"></i> {{ currentNotification.timeAgo }} • <span style="color: #16A34A; font-weight: 700;">Verified</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
interface NotificationItem {
  name: string
  location: string
  action: string
  item: string
  image: string
  timeAgo: string
}

const notifications: NotificationItem[] = [
  {
    name: 'Datin Azira',
    location: 'Petaling Jaya',
    action: 'Just ordered the',
    item: 'Meranti Chesterfield 3-Seater',
    image: 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=100&q=80',
    timeAgo: '14 mins ago'
  },
  {
    name: 'Encik Hafiz',
    location: 'Shah Alam',
    action: 'Requested free',
    item: 'Bouclé & Velvet Fabric Swatches',
    image: 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=100&q=80',
    timeAgo: '6 mins ago'
  },
  {
    name: 'Sarah Wong',
    location: 'Subang Jaya',
    action: 'Just purchased the',
    item: 'Solid Meranti 6-Seater Dining Set',
    image: 'https://images.unsplash.com/photo-1617806118233-18e1de247200?auto=format&fit=crop&w=100&q=80',
    timeAgo: '28 mins ago'
  },
  {
    name: 'Tuan Kamaruddin',
    location: 'Bukit Jelutong',
    action: 'Booked a consultation for',
    item: 'Custom L-Shape Sectional',
    image: 'https://images.unsplash.com/photo-1550581190-9c1c48d21d6c?auto=format&fit=crop&w=100&q=80',
    timeAgo: '42 mins ago'
  }
]

const currentIndex = ref(0)
const isVisible = ref(false)
const currentNotification = computed(() => notifications[currentIndex.value])

let intervalId: any = null
let timeoutId: any = null

function dismiss() {
  isVisible.value = false
  clearTimeout(timeoutId)
}

onMounted(() => {
  if (import.meta.client) {
    // Initial display after 8 seconds
    timeoutId = setTimeout(() => {
      isVisible.value = true
      // Hide after 6 seconds
      setTimeout(() => { isVisible.value = false }, 6000)
    }, 8000)

    // Repeat every 28 seconds
    intervalId = setInterval(() => {
      currentIndex.value = (currentIndex.value + 1) % notifications.length
      isVisible.value = true
      setTimeout(() => { isVisible.value = false }, 6500)
    }, 28000)
  }
})

onUnmounted(() => {
  if (intervalId) clearInterval(intervalId)
  if (timeoutId) clearTimeout(timeoutId)
})
</script>

<style scoped>
.social-proof-pill {
  position: fixed;
  bottom: 24px;
  left: 24px;
  z-index: 990;
  background: rgba(255, 255, 255, 0.98);
  backdrop-filter: blur(8px);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-sm);
  padding: 0.85rem 1.25rem 0.85rem 0.85rem;
  box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.15);
  max-width: 340px;
}

.social-proof-close {
  position: absolute;
  top: 4px;
  right: 6px;
  background: none;
  border: none;
  font-size: 1rem;
  color: #94A3B8;
  cursor: pointer;
}

.social-proof-img {
  width: 44px;
  height: 44px;
  border-radius: 6px;
  overflow: hidden;
  flex-shrink: 0;
  background: #F1F5F9;
}

.social-proof-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.social-proof-person {
  font-size: 0.78rem;
  font-weight: 700;
  color: var(--color-primary);
}

.social-proof-action {
  font-size: 0.74rem;
  color: var(--color-text-main);
  line-height: 1.3;
}

.social-proof-item {
  font-weight: 600;
  color: var(--color-secondary-dark);
}

.social-proof-time {
  font-size: 0.68rem;
  color: var(--color-text-light);
  margin-top: 0.15rem;
}

@media (max-width: 768px) {
  .social-proof-pill {
    display: none; /* Keep mobile clean */
  }
}
</style>

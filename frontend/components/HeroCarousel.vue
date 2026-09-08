<template>
  <div
    class="hero-carousel-wrapper"
    :class="[`height-${heightMode}`, { 'is-single': normalizedSlides.length <= 1 }]"
    @mouseenter="onMouseEnter"
    @mouseleave="onMouseLeave"
    @touchstart="onTouchStart"
    @touchmove="onTouchMove"
    @touchend="onTouchEnd"
    tabindex="0"
    @keydown.left="prevSlide"
    @keydown.right="nextSlide"
    role="region"
    aria-label="Homepage Featured Luxury Carousel"
  >
    <!-- Slide Items -->
    <div class="carousel-track">
      <div
        v-for="(slide, index) in normalizedSlides"
        :key="slide.id || index"
        class="carousel-slide"
        :class="{
          'is-active': currentIndex === index,
          'is-prev': isPrevSlide(index),
          'is-next': isNextSlide(index),
          'transition-fade': transitionEffect === 'fade',
          'transition-slide': transitionEffect === 'slide'
        }"
        :aria-hidden="currentIndex !== index"
      >
        <!-- Background Media: Video or Image -->
        <div class="slide-media-container">
          <video
            v-if="slide.media_type === 'video' && slide.media_url"
            :src="slide.media_url"
            :poster="slide.video_poster || ''"
            autoplay
            muted
            loop
            playsinline
            class="slide-bg-media slide-bg-video"
          ></video>
          <img
            v-else
            :src="slide.media_url || defaultFallbackImage"
            :alt="slide.title || 'Luxury bespoke living furniture'"
            class="slide-bg-media slide-bg-image"
            :loading="index === 0 ? 'eager' : 'lazy'"
            :fetchpriority="index === 0 ? 'high' : 'auto'"
            decoding="async"
          />

          <!-- Dual Gradient Overlay: Deep Brand Burgundy + Obsidian Vignette for Ultimate Readability -->
          <div class="slide-overlay"></div>
          <div class="slide-vignette"></div>
        </div>

        <!-- Slide Content (Client Hunting Display) -->
        <div class="container slide-content-container">
          <div class="slide-content" :class="{ 'animate-content': currentIndex === index }">
            <!-- Client Hunting Trust Badge -->
            <div v-if="slide.badge_text" class="slide-badge-wrap">
              <span class="badge-royal-client">
                <i class="fa-solid fa-crown" style="color: var(--color-brand-gold); font-size: 0.8rem;"></i>
                {{ slide.badge_text }}
              </span>
            </div>

            <!-- Eyebrow Tagline -->
            <div v-if="slide.eyebrow" class="slide-eyebrow">
              <span class="gold-line"></span>
              <span>{{ slide.eyebrow }}</span>
            </div>

            <!-- Main Title / Caption (Playfair Display) -->
            <h1 class="slide-title">
              {{ slide.title }}
            </h1>

            <!-- Subtitle Description -->
            <p v-if="slide.subtitle" class="slide-subtitle">
              {{ slide.subtitle }}
            </p>

            <!-- Call to Action Buttons (Single or Double) -->
            <div class="slide-actions">
              <!-- Primary Action Button -->
              <NuxtLink
                v-if="slide.btn_primary_text"
                :to="slide.btn_primary_link || '/shop'"
                :class="getButtonClass(slide.btn_primary_style, true)"
                class="btn btn-lg carousel-btn"
                :target="isExternal(slide.btn_primary_link) ? '_blank' : '_self'"
              >
                <i v-if="isWhatsApp(slide.btn_primary_link)" class="fa-brands fa-whatsapp" style="font-size: 1.15rem; margin-right: 0.4rem;"></i>
                <span>{{ slide.btn_primary_text }}</span>
                <i v-if="!isWhatsApp(slide.btn_primary_link)" class="fa-solid fa-arrow-right" style="font-size: 0.8rem; margin-left: 0.45rem;"></i>
              </NuxtLink>

              <!-- Secondary Action Button (When Double Button mode) -->
              <NuxtLink
                v-if="slide.button_mode === 'double' && slide.btn_secondary_text"
                :to="slide.btn_secondary_link || '/our-showroom'"
                :class="getButtonClass(slide.btn_secondary_style, false)"
                class="btn btn-lg carousel-btn"
                :target="isExternal(slide.btn_secondary_link) ? '_blank' : '_self'"
              >
                <i v-if="isWhatsApp(slide.btn_secondary_link)" class="fa-brands fa-whatsapp" style="font-size: 1.15rem; margin-right: 0.4rem; color: #25D366;"></i>
                <i v-else-if="isShowroom(slide.btn_secondary_link)" class="fa-solid fa-location-dot" style="font-size: 0.9rem; margin-right: 0.4rem; color: var(--color-brand-gold);"></i>
                <span>{{ slide.btn_secondary_text }}</span>
              </NuxtLink>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Navigation Arrows (Glassmorphic with Royal Gold Accent) -->
    <template v-if="normalizedSlides.length > 1">
      <button
        type="button"
        class="carousel-nav-btn prev"
        @click="prevSlide"
        aria-label="Previous slide"
      >
        <i class="fa-solid fa-chevron-left"></i>
      </button>

      <button
        type="button"
        class="carousel-nav-btn next"
        @click="nextSlide"
        aria-label="Next slide"
      >
        <i class="fa-solid fa-chevron-right"></i>
      </button>

      <!-- Bottom Bar: Indicators + Live Progress + Slide Counter -->
      <div class="carousel-bottom-bar">
        <div class="container flex items-center justify-between">
          <!-- Slide Indicator Bars with Fill Progress -->
          <div class="carousel-indicators" role="tablist">
            <button
              v-for="(slide, index) in normalizedSlides"
              :key="'ind-' + (slide.id || index)"
              type="button"
              class="indicator-item"
              :class="{ active: currentIndex === index }"
              @click="goToSlide(index)"
              :aria-label="`Go to slide ${index + 1}`"
              role="tab"
              :aria-selected="currentIndex === index"
            >
              <div class="indicator-bar">
                <div
                  v-if="currentIndex === index && isAutoplayRunning"
                  class="indicator-progress"
                  :style="{ animationDuration: `${intervalDuration}ms` }"
                ></div>
                <div v-else-if="currentIndex === index" class="indicator-progress is-filled"></div>
              </div>
            </button>
          </div>

          <!-- Slide Counter (e.g. 01 / 04) -->
          <div class="carousel-counter">
            <span class="current-num">{{ String(currentIndex + 1).padStart(2, '0') }}</span>
            <span class="sep">/</span>
            <span class="total-num">{{ String(normalizedSlides.length).padStart(2, '0') }}</span>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup lang="ts">
export interface HeroSlide {
  id?: number | string
  media_type?: 'image' | 'video'
  media_url: string
  video_poster?: string
  eyebrow?: string
  title: string
  subtitle?: string
  badge_text?: string
  button_mode?: 'single' | 'double'
  btn_primary_text?: string
  btn_primary_link?: string
  btn_primary_style?: string
  btn_secondary_text?: string
  btn_secondary_link?: string
  btn_secondary_style?: string
  is_active?: boolean
}

export interface CarouselSettings {
  enabled?: boolean | string
  autoplay?: boolean | string
  interval?: number | string
  height?: string
  transition?: string
}

const props = withDefaults(
  defineProps<{
    slides?: HeroSlide[]
    settings?: CarouselSettings
  }>(),
  {
    slides: () => [],
    settings: () => ({
      enabled: '1',
      autoplay: '1',
      interval: 6000,
      height: 'standard',
      transition: 'fade',
    }),
  }
)

const defaultFallbackImage = 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=2000&q=85'

// Normalized slides filtering only active ones
const normalizedSlides = computed<HeroSlide[]>(() => {
  if (!props.slides || props.slides.length === 0) return []
  const filtered = props.slides.filter(s => s.is_active !== false)
  return filtered.length > 0 ? filtered : props.slides
})

// Current Slide Index
const currentIndex = ref(0)
const isPaused = ref(false)
let timer: any = null

// Height Mode & Transition
const heightMode = computed(() => props.settings?.height || 'standard')
const transitionEffect = computed(() => props.settings?.transition || 'fade')
const intervalDuration = computed(() => {
  const val = Number(props.settings?.interval)
  return !isNaN(val) && val >= 2000 ? val : 6000
})

const isAutoplayEnabled = computed(() => {
  const val = props.settings?.autoplay
  return val === '1' || val === true || val === 'true'
})

const isAutoplayRunning = computed(() => {
  return isAutoplayEnabled.value && !isPaused.value && normalizedSlides.value.length > 1
})

function startAutoplay() {
  stopAutoplay()
  if (!isAutoplayEnabled.value || normalizedSlides.value.length <= 1) return
  timer = setInterval(() => {
    if (!isPaused.value) {
      nextSlide()
    }
  }, intervalDuration.value)
}

function stopAutoplay() {
  if (timer) {
    clearInterval(timer)
    timer = null
  }
}

function nextSlide() {
  if (normalizedSlides.value.length <= 1) return
  currentIndex.value = (currentIndex.value + 1) % normalizedSlides.value.length
}

function prevSlide() {
  if (normalizedSlides.value.length <= 1) return
  currentIndex.value = (currentIndex.value - 1 + normalizedSlides.value.length) % normalizedSlides.value.length
}

function goToSlide(index: number) {
  currentIndex.value = index
  if (isAutoplayEnabled.value) {
    startAutoplay()
  }
}

function isPrevSlide(index: number) {
  const total = normalizedSlides.value.length
  return index === (currentIndex.value - 1 + total) % total
}

function isNextSlide(index: number) {
  const total = normalizedSlides.value.length
  return index === (currentIndex.value + 1) % total
}

// Hover Pause handlers
function onMouseEnter() {
  isPaused.value = true
}

function onMouseLeave() {
  isPaused.value = false
}

// Touch Swipe Handling for Mobile Devices
const touchStartX = ref(0)
const touchEndX = ref(0)
const minSwipeDistance = 45

function onTouchStart(e: TouchEvent) {
  isPaused.value = true
  if (e.touches[0]) {
    touchStartX.value = e.touches[0].clientX
    touchEndX.value = e.touches[0].clientX
  }
}

function onTouchMove(e: TouchEvent) {
  if (e.touches[0]) {
    touchEndX.value = e.touches[0].clientX
  }
}

function onTouchEnd() {
  isPaused.value = false
  const diff = touchStartX.value - touchEndX.value
  if (Math.abs(diff) > minSwipeDistance) {
    if (diff > 0) {
      nextSlide()
    } else {
      prevSlide()
    }
  }
}

// Button Class Helpers
function getButtonClass(styleName?: string, isPrimary = true): string {
  if (!styleName) {
    return isPrimary ? 'btn-royal-gold' : 'btn-glass-gold'
  }
  switch (styleName.toLowerCase()) {
    case 'royal-gold':
    case 'gold':
      return 'btn-royal-gold'
    case 'wine':
    case 'burgundy':
      return 'btn-wine'
    case 'glass':
    case 'glass-gold':
      return 'btn-glass-gold'
    case 'whatsapp':
      return 'btn-whatsapp-cta'
    case 'outline':
      return 'btn-outline-gold'
    case 'primary':
      return 'btn-primary'
    case 'secondary':
      return 'btn-secondary'
    default:
      return isPrimary ? 'btn-royal-gold' : 'btn-glass-gold'
  }
}

function isExternal(url?: string) {
  if (!url) return false
  return url.startsWith('http://') || url.startsWith('https://') || url.startsWith('//')
}

function isWhatsApp(url?: string) {
  if (!url) return false
  return url.includes('wa.me') || url.includes('whatsapp.com')
}

function isShowroom(url?: string) {
  if (!url) return false
  return url.includes('showroom')
}

onMounted(() => {
  if (isAutoplayEnabled.value) {
    startAutoplay()
  }
})

onUnmounted(() => {
  stopAutoplay()
})

watch(() => props.settings?.interval, () => {
  startAutoplay()
})

watch(() => props.settings?.autoplay, () => {
  if (isAutoplayEnabled.value) {
    startAutoplay()
  } else {
    stopAutoplay()
  }
})
</script>

<style scoped>
.hero-carousel-wrapper {
  position: relative;
  width: 100%;
  background-color: var(--color-brand-burgundy-dark);
  overflow: hidden;
  outline: none;
  user-select: none;
}

/* Standard Height Presets */
.hero-carousel-wrapper.height-standard {
  height: calc(100vh - 128px);
  min-height: 640px;
  max-height: 820px;
}

.hero-carousel-wrapper.height-compact {
  height: 560px;
  min-height: 520px;
  max-height: 620px;
}

.hero-carousel-wrapper.height-immersive {
  height: 90vh;
  min-height: 700px;
  max-height: 950px;
}

/* Carousel Track & Slides */
.carousel-track {
  position: relative;
  width: 100%;
  height: 100%;
}

.carousel-slide {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  opacity: 0;
  visibility: hidden;
  pointer-events: none;
  transition: opacity 0.8s cubic-bezier(0.25, 1, 0.5, 1), visibility 0.8s;
  z-index: 1;
}

.carousel-slide.is-active {
  opacity: 1;
  visibility: visible;
  pointer-events: auto;
  z-index: 2;
}

/* Slide Transition Types */
.carousel-slide.transition-slide {
  transform: translateX(100%);
  transition: transform 0.75s cubic-bezier(0.25, 1, 0.5, 1), opacity 0.75s;
}

.carousel-slide.transition-slide.is-active {
  transform: translateX(0);
}

.carousel-slide.transition-slide.is-prev {
  transform: translateX(-100%);
}

/* Background Media & Cinematic Zoom */
.slide-media-container {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  overflow: hidden;
  z-index: 1;
}

.slide-bg-media {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
  transform: scale(1);
  transition: transform 7s cubic-bezier(0.25, 1, 0.5, 1);
}

.carousel-slide.is-active .slide-bg-media {
  transform: scale(1.06);
}

/* Luxurious Brand Gradient Overlays (Deep Velvet Burgundy & Shadow) */
.slide-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(
    90deg,
    rgba(22, 4, 7, 0.94) 0%,
    rgba(37, 5, 10, 0.82) 42%,
    rgba(40, 6, 12, 0.58) 72%,
    rgba(15, 2, 4, 0.35) 100%
  );
  z-index: 2;
}

.slide-vignette {
  position: absolute;
  inset: 0;
  background: radial-gradient(
    circle at 75% 45%,
    rgba(0, 0, 0, 0) 30%,
    rgba(20, 3, 6, 0.55) 85%,
    rgba(15, 2, 4, 0.85) 100%
  );
  z-index: 2;
}

/* Content Container */
.slide-content-container {
  position: relative;
  z-index: 10;
  display: flex;
  align-items: center;
  height: 100%;
  padding-top: 3.5rem;
  padding-bottom: 5.5rem;
}

.slide-content {
  max-width: 720px;
  color: #FFFFFF;
}

/* Fade & Slide Up Animation for Text Content */
.animate-content .slide-badge-wrap {
  animation: heroTextFade 0.65s cubic-bezier(0.16, 1, 0.3, 1) both;
}

.animate-content .slide-eyebrow {
  animation: heroTextFade 0.75s cubic-bezier(0.16, 1, 0.3, 1) 0.08s both;
}

.animate-content .slide-title {
  animation: heroTextFade 0.85s cubic-bezier(0.16, 1, 0.3, 1) 0.16s both;
}

.animate-content .slide-subtitle {
  animation: heroTextFade 0.95s cubic-bezier(0.16, 1, 0.3, 1) 0.24s both;
}

.animate-content .slide-actions {
  animation: heroTextFade 1.05s cubic-bezier(0.16, 1, 0.3, 1) 0.32s both;
}

@keyframes heroTextFade {
  0% {
    opacity: 0;
    transform: translateY(22px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Badge Wrap */
.slide-badge-wrap {
  margin-bottom: 1.25rem;
}

/* Eyebrow */
.slide-eyebrow {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-family: var(--font-sans);
  font-size: 0.82rem;
  letter-spacing: 0.16em;
  font-weight: 700;
  color: var(--color-brand-gold-light);
  margin-bottom: 1.25rem;
  text-transform: uppercase;
}

.gold-line {
  width: 28px;
  height: 2px;
  background: var(--gradient-royal-gold);
  border-radius: 2px;
}

/* Title (Playfair Display) */
.slide-title {
  font-family: var(--font-heading);
  font-size: 3.5rem;
  line-height: 1.15;
  font-weight: 700;
  color: #FFFFFF;
  margin-bottom: 1.4rem;
  letter-spacing: -0.01em;
  text-shadow: 0 4px 18px rgba(0, 0, 0, 0.5);
}

/* Subtitle */
.slide-subtitle {
  font-family: var(--font-body);
  font-size: 1.125rem;
  line-height: 1.7;
  color: #F1ECE4;
  margin-bottom: 2.25rem;
  max-width: 640px;
  text-shadow: 0 2px 8px rgba(0, 0, 0, 0.45);
}

/* Action Buttons */
.slide-actions {
  display: flex;
  align-items: center;
  gap: 1.1rem;
  flex-wrap: wrap;
}

.carousel-btn {
  padding: 0.9rem 1.85rem;
  font-size: 0.95rem;
  border-radius: var(--radius-sm);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
}

/* Nav Arrows */
.carousel-nav-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 52px;
  height: 52px;
  border-radius: 50%;
  background: rgba(35, 5, 10, 0.55);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(212, 175, 55, 0.4);
  color: #F5DEB3;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.15rem;
  cursor: pointer;
  z-index: 20;
  transition: all var(--transition-normal);
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.35);
}

.carousel-nav-btn:hover {
  background: rgba(212, 175, 55, 0.3);
  border-color: var(--color-brand-gold-light);
  color: #FFFFFF;
  transform: translateY(-50%) scale(1.08);
  box-shadow: 0 6px 22px rgba(212, 175, 55, 0.4);
}

.carousel-nav-btn.prev {
  left: 1.75rem;
}

.carousel-nav-btn.next {
  right: 1.75rem;
}

/* Bottom Bar: Indicators & Counter */
.carousel-bottom-bar {
  position: absolute;
  bottom: 1.5rem;
  left: 0;
  right: 0;
  z-index: 20;
}

.carousel-indicators {
  display: flex;
  align-items: center;
  gap: 0.6rem;
}

.indicator-item {
  background: transparent;
  border: none;
  padding: 0.5rem 0;
  cursor: pointer;
}

.indicator-bar {
  width: 44px;
  height: 3px;
  background: rgba(255, 255, 255, 0.25);
  border-radius: 3px;
  overflow: hidden;
  position: relative;
  transition: width 0.3s ease, background 0.3s ease;
}

.indicator-item.active .indicator-bar {
  width: 64px;
  background: rgba(255, 255, 255, 0.35);
}

.indicator-progress {
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 0%;
  background: var(--gradient-royal-gold);
  border-radius: 3px;
  box-shadow: 0 0 10px rgba(212, 175, 55, 0.8);
  animation: progressFill linear forwards;
}

.indicator-progress.is-filled {
  width: 100%;
  animation: none;
}

@keyframes progressFill {
  0% {
    width: 0%;
  }
  100% {
    width: 100%;
  }
}

/* Counter */
.carousel-counter {
  font-family: var(--font-sans);
  font-size: 0.95rem;
  font-weight: 700;
  color: #FFFFFF;
  letter-spacing: 0.1em;
  display: flex;
  align-items: center;
  gap: 0.35rem;
  background: rgba(30, 4, 8, 0.65);
  backdrop-filter: blur(10px);
  padding: 0.35rem 0.85rem;
  border-radius: 9999px;
  border: 1px solid rgba(212, 175, 55, 0.35);
}

.current-num {
  color: var(--color-brand-gold);
}

.carousel-counter .sep {
  opacity: 0.45;
}

.total-num {
  color: #D2C8BF;
}

/* =========================================================
   RESPONSIVE DESIGN (TABLET & MOBILE BREAKPOINTS)
   ========================================================= */

@media (max-width: 1024px) {
  .slide-title {
    font-size: 2.85rem;
  }
  .slide-subtitle {
    font-size: 1.05rem;
  }
  .hero-carousel-wrapper.height-standard {
    height: 600px;
    min-height: 560px;
  }
}

@media (max-width: 768px) {
  .hero-carousel-wrapper.height-standard,
  .hero-carousel-wrapper.height-compact,
  .hero-carousel-wrapper.height-immersive {
    height: auto;
    min-height: 520px;
  }

  .slide-content-container {
    padding-top: 3rem;
    padding-bottom: 4.5rem;
  }

  .slide-title {
    font-size: 2.15rem;
    line-height: 1.2;
    margin-bottom: 1rem;
  }

  .slide-subtitle {
    font-size: 0.95rem;
    line-height: 1.55;
    margin-bottom: 1.65rem;
  }

  .slide-eyebrow {
    font-size: 0.72rem;
    margin-bottom: 0.85rem;
  }

  .carousel-btn {
    padding: 0.75rem 1.35rem;
    font-size: 0.88rem;
    width: 100%;
    justify-content: center;
  }

  .slide-actions {
    flex-direction: column;
    width: 100%;
    gap: 0.75rem;
  }

  .carousel-nav-btn {
    display: none; /* Swipe gestures used on mobile */
  }

  .carousel-bottom-bar {
    bottom: 1rem;
  }

  .indicator-bar {
    width: 28px;
  }

  .indicator-item.active .indicator-bar {
    width: 44px;
  }
}
</style>

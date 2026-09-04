<template>
  <div>
    <!-- Editorial Hero Section -->
    <section class="hero-section">
      <div class="hero-bg-container">
        <img
          :src="heroImageUrl"
          alt="Handcrafted luxury sofa living room"
          class="hero-bg-image"
          fetchpriority="high"
          loading="eager"
          decoding="async"
        />
        <div class="hero-gradient-overlay"></div>
      </div>

      <div class="container hero-container">
        <div class="hero-content animate-fade-in">
          <span class="hero-eyebrow">{{ heroEyebrow }}</span>
          <h1 class="hero-title">{{ heroTitle }}</h1>
          <p class="hero-subtitle">
            {{ heroSubtitle }}
          </p>
          <div class="flex gap-4 items-center flex-wrap">
            <NuxtLink :to="heroBtnPrimaryLink" class="btn btn-secondary btn-lg">
              {{ heroBtnPrimaryText }}
            </NuxtLink>
            <NuxtLink :to="heroBtnSecondaryLink" class="btn btn-glass btn-lg">
              <i class="fa-solid fa-location-dot" style="color: var(--color-secondary);"></i>
              {{ heroBtnSecondaryText }}
            </NuxtLink>
          </div>
        </div>
      </div>

      <!-- Trust Pillars Bar -->
      <div class="trust-bar">
        <div class="container">
          <div class="trust-grid">
            <div class="trust-item">
              <i class="fa-solid fa-shield-halved"></i>
              <div>
                <strong>5-Year Warranty</strong>
                <span>Solid Meranti Hardwood Frame</span>
              </div>
            </div>
            <div class="trust-item">
              <i class="fa-solid fa-industry"></i>
              <div>
                <strong>Direct Factory Price</strong>
                <span>Zero Middleman Markup</span>
              </div>
            </div>
            <div class="trust-item">
              <i class="fa-solid fa-paw"></i>
              <div>
                <strong>200+ Fabric Swatches</strong>
                <span>Pet-Friendly & Easy-Clean</span>
              </div>
            </div>
            <div class="trust-item">
              <i class="fa-solid fa-ruler-combined"></i>
              <div>
                <strong>Custom Sizing</strong>
                <span>Tailored to Your Floorplan</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Curated Category Grid -->
    <section class="py-20" style="background-color: #FFFFFF;">
      <div class="container">
        <div class="section-title">
          <span class="section-eyebrow">CURATED COLLECTIONS</span>
          <h2>EXPLORE BY CATEGORY</h2>
          <p>Each piece is individually constructed to order in our Malaysian workshop with your choice of dimensions, firmness, and fabric.</p>
        </div>

        <div class="grid grid-cols-4 gap-6">
          <div v-for="cat in homeCategories" :key="cat.slug" class="category-card">
            <NuxtLink :to="`/shop?category=${cat.slug}`" class="category-card-media">
              <img :src="cat.image" :alt="cat.name" class="category-card-img" loading="lazy" />
            </NuxtLink>
            <div class="category-card-body">
              <h3 class="category-card-title">{{ cat.name }}</h3>
              <NuxtLink :to="`/shop?category=${cat.slug}`" class="category-card-link">
                View Collection <i class="fa-solid fa-arrow-right" style="font-size: 0.75rem; margin-left: 0.25rem;"></i>
              </NuxtLink>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Masterpieces Showcase -->
    <section class="py-20" style="background-color: var(--color-bg-alt); border-top: 1px solid var(--color-border); border-bottom: 1px solid var(--color-border);">
      <div class="container">
        <div class="section-title">
          <span class="section-eyebrow">SIGNATURE DESIGNS</span>
          <h2>FEATURED FURNITURE</h2>
          <p>Our most celebrated living pieces, handcrafted for residential homes across Malaysia and Singapore.</p>
        </div>

        <div v-if="featuredProducts.length > 0" class="grid grid-cols-4 gap-6">
          <ProductCard
            v-for="prod in featuredProducts"
            :key="prod.id"
            :product="prod"
          />
        </div>
        <div v-else class="text-center py-12">
          <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 2rem; color: var(--color-secondary-dark);"></i>
          <p style="margin-top: 1rem; color: var(--color-text-muted);">Loading handcrafted collections...</p>
        </div>

        <div class="text-center" style="margin-top: 3.5rem;">
          <NuxtLink to="/shop" class="btn btn-primary btn-lg">
            View All Products
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- WARRANTY & CRAFTSMANSHIP Section -->
    <section class="py-20" style="background-color: #FFFFFF;">
      <div class="container">
        <div class="feature-split">
          <div class="feature-split-img">
            <img
              src="https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?auto=format&fit=crop&w=1200&q=80"
              alt="Solid Wood Framework Craftsmanship"
              loading="lazy"
            />
          </div>
          <div class="feature-split-content">
            <span class="badge-pill badge-pill--gold">
              <i class="fa-solid fa-shield-halved"></i> 5-Year Solid Wood Warranty
            </span>
            <h2>ENGINEERED FOR A LIFETIME OF COMFORT</h2>
            <p>
              Unlike mass-manufactured flatpack furniture using particle boards, every NMFFurniture frame is constructed from 100% kiln-dried Malaysian solid hardwood. This ensures unmatched structural integrity, eliminating frame warping, creaking, or joint failure.
            </p>
            <p>
              Beneath the upholstery, our dual-tier pocket-spring suspension system works alongside 45D high-resilience latex-infused foam to provide supportive, ergonomic posture that preserves its shape year after year.
            </p>

            <div class="craft-specs-grid">
              <div class="craft-spec-item">
                <i class="fa-solid fa-circle-check"></i>
                <span>100% Kiln-Dried Meranti Hardwood (Zero Warping)</span>
              </div>
              <div class="craft-spec-item">
                <i class="fa-solid fa-circle-check"></i>
                <span>Pocket Spring Suspension & 45D Latex Foam</span>
              </div>
              <div class="craft-spec-item">
                <i class="fa-solid fa-circle-check"></i>
                <span>Master Mortise & Tenon Structural Joinery</span>
              </div>
            </div>

            <div style="margin-top: 2rem;">
              <NuxtLink to="/our-showroom" class="btn btn-outline">
                Visit Showroom to Test Cushion Firmness →
              </NuxtLink>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- FABRIC & MATERIALS PALETTE -->
    <section class="py-20" style="background-color: var(--color-bg-alt); border-top: 1px solid var(--color-border);">
      <div class="container">
        <div class="feature-split reverse">
          <div class="feature-split-content">
            <span class="badge-pill badge-pill--emerald">
              <i class="fa-solid fa-paw"></i> Pet-Friendly & Water-Repellent
            </span>
            <h2>OVER 200+ DESIGNER TEXTURES & COLOURS</h2>
            <p>
              Our performance fabric collection is curated specifically for modern living with pets and families. Spills, stains, and daily mishaps bead on the surface and can be wiped clean with just water and a microfiber cloth.
            </p>
            <p>
              Explore tactile French boucle, ultra-soft Dutch chenille, heavy woven linen, and stain-resistant velvet across an extensive palette of over 200 shades.
            </p>

            <!-- Interactive Palette Swatches -->
            <div class="fabric-interactive-box">
              <div class="flex items-center justify-between" style="margin-bottom: 0.75rem;">
                <div style="font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-primary);">
                  Featured Designer Palette:
                </div>
                <div class="active-swatch-pill">
                  <span class="active-swatch-dot" :style="{ backgroundColor: selectedSwatch.code }"></span>
                  <span class="active-swatch-name">{{ selectedSwatch.name }}</span>
                </div>
              </div>

              <div class="swatch-group">
                <button
                  v-for="(c, i) in sampleColors"
                  :key="i"
                  type="button"
                  class="swatch-item"
                  :class="{ active: selectedSwatch.name === c.name }"
                  :style="{ backgroundColor: c.code }"
                  :title="c.name"
                  @click="selectedSwatch = c"
                  @mouseenter="selectedSwatch = c"
                  :aria-label="`Select ${c.name} fabric finish`"
                ></button>
              </div>

              <div class="fabric-trust-notes">
                <span><i class="fa-solid fa-shield-cat"></i> Pet Claw Resistant</span>
                <span><i class="fa-solid fa-droplet-slash"></i> Liquid Repellent</span>
                <span><i class="fa-solid fa-sparkles"></i> High Martindale Rub Count</span>
              </div>
            </div>

            <a
              :href="swatchWhatsappUrl"
              target="_blank"
              class="btn btn-whatsapp"
            >
              <i class="fa-brands fa-whatsapp"></i> Request Swatch Book on WhatsApp
            </a>
          </div>
          <div class="feature-split-img">
            <img
              src="https://images.unsplash.com/photo-1540574163026-643ea20ade25?auto=format&fit=crop&w=1200&q=80"
              alt="Designer Fabric Swatches and Textures"
              loading="lazy"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- IN-HOUSE WORKSHOP HIGHLIGHT -->
    <section class="py-20" style="background-color: var(--color-primary); color: #FFFFFF;">
      <div class="container">
        <div class="feature-split">
          <div class="feature-split-img" style="border: 1px solid rgba(255,255,255,0.15);">
            <img
              src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=1200&q=80"
              alt="Malaysian Furniture Joinery and Workshop"
              loading="lazy"
            />
          </div>
          <div class="feature-split-content">
            <span class="badge-pill badge-pill--dark">
              <i class="fa-solid fa-industry"></i> Factory Direct • Bukit Jelutong
            </span>
            <h2 style="color: #FFFFFF;">HANDCRAFTED WITHOUT MIDDLEMAN MARKUPS</h2>
            <p style="color: #D6D8DC;">
              NMFFurniture Sdn Bhd is a direct manufacturer with in-house frame joinery, precision sewing, and master upholstery facilities. By buying direct from our workshop, you receive heirloom-grade craftsmanship at genuine factory pricing.
            </p>
            <p style="color: #D6D8DC;">
              Need custom length to fit an exact living room alcove or specific cushion density? Our master craftsmen can customize dimensions for your home layout.
            </p>

            <div class="workshop-badges">
              <div class="workshop-badge-item">
                <i class="fa-solid fa-ruler-combined"></i>
                <span>Custom Dimensions Tailored to Floorplan</span>
              </div>
              <div class="workshop-badge-item">
                <i class="fa-solid fa-couch"></i>
                <span>3 Firmness Options (Plush / Balanced / Firm)</span>
              </div>
              <div class="workshop-badge-item">
                <i class="fa-solid fa-truck-fast"></i>
                <span>White-Glove Delivery & Installation</span>
              </div>
            </div>

            <div class="flex gap-4 flex-wrap" style="margin-top: 2rem;">
              <NuxtLink to="/shop" class="btn btn-secondary">
                Explore Catalog
              </NuxtLink>
              <NuxtLink to="/gallery" class="btn btn-outline-white">
                View Workshop Gallery
              </NuxtLink>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- REAL RESIDENTIAL INSTALLATIONS GALLERY -->
    <section class="py-20" style="background-color: #FFFFFF;">
      <div class="container">
        <div class="section-title-with-nav">
          <div class="section-title text-left" style="margin-bottom: 0; text-align: left;">
            <span class="section-eyebrow">LOOKBOOK & INSPIRATION</span>
            <h2>REAL LIVING ROOM INSTALLATIONS</h2>
            <p style="margin: 0;">A glimpse into recent custom sofa setups and interior projects across Malaysia.</p>
          </div>
          <div class="slider-nav-btns">
            <button
              @click="scrollShowcase('left')"
              class="slider-btn"
              type="button"
              aria-label="Previous installations"
            >
              <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button
              @click="scrollShowcase('right')"
              class="slider-btn"
              type="button"
              aria-label="Next installations"
            >
              <i class="fa-solid fa-chevron-right"></i>
            </button>
          </div>
        </div>

        <div ref="showcaseSliderRef" class="showcase-slider">
          <div v-for="(img, idx) in galleryImages" :key="idx" class="showcase-slide">
            <div class="showcase-slide-media">
              <img :src="img.url" :alt="img.title" loading="lazy" />
              <span class="showcase-verified-tag">
                <i class="fa-solid fa-circle-check"></i> Verified Setup
              </span>
            </div>
            <div class="showcase-slide-caption">
              <div class="showcase-slide-title">{{ img.title }}</div>
              <div class="showcase-slide-desc">
                <i class="fa-solid fa-location-dot"></i>
                {{ img.desc }}
              </div>
            </div>
          </div>
        </div>

        <div class="text-center" style="margin-top: 2.5rem;">
          <NuxtLink to="/gallery" class="btn btn-outline btn-sm">
            View Complete Project Lookbook →
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- AUTHENTIC CLIENT REVIEWS -->
    <section class="py-20" style="background-color: var(--color-bg-alt); border-top: 1px solid var(--color-border);">
      <div class="container">
        <div class="section-title">
          <span class="section-eyebrow">VERIFIED REVIEWS</span>
          <h2>EXPERIENCES FROM HOMEOWNERS</h2>
          <p>Read what homeowners in Klang Valley and across Peninsular Malaysia say about our custom sofas and craftsmanship.</p>
        </div>

        <div class="grid grid-cols-3 gap-6">
          <div v-for="(rev, rIdx) in customerReviews" :key="rIdx" class="review-card">
            <div class="flex items-center justify-between" style="margin-bottom: 1rem;">
              <div class="review-stars">
                <i v-for="s in 5" :key="s" class="fa-solid fa-star"></i>
              </div>
              <span class="verified-badge">
                <i class="fa-solid fa-circle-check"></i> Verified Homeowner
              </span>
            </div>
            <p class="review-quote">"{{ rev.quote }}"</p>
            <div class="review-author">
              <div class="review-avatar">{{ rev.initials }}</div>
              <div class="review-meta">
                <div class="review-name">{{ rev.name }}</div>
                <div class="review-location">
                  <i class="fa-solid fa-location-dot" style="color: var(--color-secondary-dark); margin-right: 0.25rem;"></i>
                  {{ rev.location }} • <span style="font-weight: 600; color: var(--color-primary);">{{ rev.product }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Official Manufacturer Trust Seal Bar -->
    <section class="trust-seal-bar">
      <div class="container">
        <div class="trust-seal-grid">
          <div class="trust-seal-item">
            <div class="trust-seal-icon">
              <i class="fa-solid fa-award"></i>
            </div>
            <div>
              <strong>NMFFURNITURE SDN. BHD.</strong>
              <span>SSM No: {{ ssmNumber }} • Registered In-House Manufacturer</span>
            </div>
          </div>
          <div class="trust-seal-item">
            <div class="trust-seal-icon">
              <i class="fa-solid fa-tree"></i>
            </div>
            <div>
              <strong>100% Solid Meranti Wood</strong>
              <span>5-Year Structural Frame Warranty Guaranteed</span>
            </div>
          </div>
          <div class="trust-seal-item">
            <div class="trust-seal-icon">
              <i class="fa-solid fa-warehouse"></i>
            </div>
            <div>
              <strong>Direct Workshop & Showrooms</strong>
              <span>Shah Alam & Bukit Jelutong, Selangor</span>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import ProductCard from '~/components/ProductCard.vue'
import { useSettingsStore } from '~/stores/settings'

const settingsStore = useSettingsStore()
const config = useRuntimeConfig()
const apiBase = config.public.apiBase
const ssmNumber = computed(() => settingsStore.getSetting('ssm_number', config.public.ssmNumber || 'SSM 1400875-P'))
const whatsappDefault = computed(() => settingsStore.getSetting('support_whatsapp', config.public.whatsappDefault || '60192589920'))

// Dynamic Hero Settings
const heroEyebrow = computed(() => settingsStore.getSetting('hero_eyebrow', 'BUKIT JELUTONG WORKSHOP • IN-HOUSE MANUFACTURING'))
const heroTitle = computed(() => settingsStore.getSetting('hero_title', 'Bespoke Sofas & Handcrafted Living Furniture'))
const heroSubtitle = computed(() => settingsStore.getSetting('hero_subtitle', 'Engineered with reinforced kiln-dried solid hardwood frames, pocket spring suspension, and over 200+ pet-friendly, water-repellent designer fabrics.'))
const heroImageUrl = computed(() => settingsStore.getSetting('hero_image_url', 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1920&q=80'))
const heroBtnPrimaryText = computed(() => settingsStore.getSetting('hero_btn_primary_text', 'Explore Collections'))
const heroBtnPrimaryLink = computed(() => settingsStore.getSetting('hero_btn_primary_link', '/shop'))
const heroBtnSecondaryText = computed(() => settingsStore.getSetting('hero_btn_secondary_text', 'Visit Our Showrooms'))
const heroBtnSecondaryLink = computed(() => settingsStore.getSetting('hero_btn_secondary_link', '/our-showroom'))

// 8 Category Cards for NMFFurniture collections
const homeCategories = [
  { name: 'Sofas & Sectionals', slug: 'sofa', image: 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=800&q=80' },
  { name: 'Wing Chairs', slug: 'wing-chair', image: 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=800&q=80' },
  { name: 'Dining Chairs', slug: 'dining-chair', image: 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?auto=format&fit=crop&w=800&q=80' },
  { name: 'Benches & Ottomans', slug: 'bench', image: 'https://images.unsplash.com/photo-1540574163026-643ea20ade25?auto=format&fit=crop&w=800&q=80' },
  { name: 'Coffee Tables', slug: 'coffee-table', image: 'https://images.unsplash.com/photo-1533090161767-e6ffed986c88?auto=format&fit=crop&w=800&q=80' },
  { name: 'Lounge Armchairs', slug: 'armchair', image: 'https://images.unsplash.com/photo-1580481077195-c999335a968a?auto=format&fit=crop&w=800&q=80' },
  { name: 'Bed Frames', slug: 'bed-frame', image: 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=800&q=80' },
  { name: 'Dining Tables', slug: 'dining-table', image: 'https://images.unsplash.com/photo-1617806118233-18e1de247200?auto=format&fit=crop&w=800&q=80' },
]

const sampleColors = [
  { name: 'Champagne Oat Boucle', code: '#C5A880' },
  { name: 'Warm Stone Chenille', code: '#D8D2C2' },
  { name: 'Olive Moss Velvet', code: '#6A7059' },
  { name: 'Midnight Navy Weave', code: '#21303E' },
  { name: 'Terracotta Rust Velvet', code: '#A6533A' },
  { name: 'Charcoal Noir Linen', code: '#2C2E35' },
  { name: 'French Cream Boucle', code: '#F4EBE1' },
  { name: 'Saddle Tan Vegan Leather', code: '#B08048' },
]

const galleryImages = [
  { title: 'Modern Channel Curved Sectional in Champagne Velvet', desc: 'Bukit Jelutong Bungalow Installation', url: 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=800&q=80' },
  { title: 'Elize Curved 3-Seater Lounge in Cream Boucle', desc: 'Mont Kiara Penthouse Residence', url: 'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?auto=format&fit=crop&w=800&q=80' },
  { title: 'Adele Upholstered King Bedframe with Wing Flanks', desc: 'Tropicana Golf Villa Residence', url: 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=800&q=80' },
  { title: 'Solid Meranti Dining Table & Padded Chairs', desc: 'Setia Eco Park Home Residence', url: 'https://images.unsplash.com/photo-1617806118233-18e1de247200?auto=format&fit=crop&w=800&q=80' },
  { title: 'Bespoke High-Back Wing Chair in Emerald Velvet', desc: 'Private Library Corner, Ara Damansara', url: 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=800&q=80' },
]

const customerReviews = [
  {
    name: 'Farhan & Sarah',
    initials: 'FS',
    location: 'Setia Alam, Selangor',
    product: 'Custom 4-Seater L-Shape Sofa',
    quote: 'We visited their Bukit Jelutong showroom and were able to customize the sofa length by an extra 30cm to fit our living wall. The pet-friendly fabric has held up amazingly against our two cats with zero pulls.'
  },
  {
    name: 'Dr. Michael Tan',
    initials: 'MT',
    location: 'Mont Kiara, KL',
    product: 'Curved Lounge in French Boucle',
    quote: 'The solid hardwood frame is substantially built and heavy. You can immediately feel the difference in cushion density and seam stitching compared to imported retail store pieces.'
  },
  {
    name: 'Aina & Haziq',
    initials: 'AH',
    location: 'Shah Alam, Selangor',
    product: 'Adele Bedframe & Dining Set',
    quote: 'Direct factory pricing saved us thousands compared to mall retailers. Delivery team arrived right on schedule, assembled everything cleanly, and took away all packaging.'
  }
]

// High-Speed SSR Data Fetching (Zero Layout Shift)
const { data: featuredData } = await useAsyncData('featured-products', () =>
  $fetch(`${apiBase}/products/featured`).catch((e) => {
    console.error('Featured products fetch error', e)
    return { products: [] }
  })
)

const featuredProducts = computed(() => (featuredData.value as any)?.products || [])

const selectedSwatch = ref(sampleColors[0])

const swatchWhatsappUrl = computed(() => {
  const text = `Hello NMFFurniture, I am interested in the ${selectedSwatch.value.name} fabric finish and would like to request your 200+ fabric swatch catalog.`
  return `https://wa.me/${whatsappDefault.value}?text=${encodeURIComponent(text)}`
})

const showcaseSliderRef = ref<HTMLElement | null>(null)

const scrollShowcase = (direction: 'left' | 'right') => {
  if (!showcaseSliderRef.value) return
  const scrollAmount = 400
  showcaseSliderRef.value.scrollBy({
    left: direction === 'left' ? -scrollAmount : scrollAmount,
    behavior: 'smooth'
  })
}

// SEO Optimization & Metadata
useSeoMeta({
  title: 'Handcrafted Modern Sofas & Living Furniture Malaysia | 5-Year Wood Warranty',
  ogTitle: 'NMFFurniture | Handcrafted Bespoke Sofas & Living Furniture Malaysia',
  description: 'Direct in-house Malaysian manufacturer of bespoke sofas, wing chairs, and luxury bedframes. 5-Year Solid Wood Warranty, 200+ pet-friendly fabrics. Visit our Shah Alam & PJ showrooms.',
  ogDescription: 'Bespoke living room sofas and handcrafted Malaysian furniture with 5-Year Solid Hardwood Warranty & 200+ fabric choices.',
  ogImage: 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1200&q=80',
  twitterCard: 'summary_large_image',
})

// Structured Data (JSON-LD) for LocalBusiness / FurnitureStore & LCP Preload
useHead({
  link: [
    { rel: 'canonical', href: 'https://nmffurniture.com/' },
    { rel: 'preload', as: 'image', href: heroImageUrl.value, fetchpriority: 'high' }
  ],
  script: [
    {
      type: 'application/ld+json',
      children: JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'FurnitureStore',
        'name': 'NMFFurniture Sdn Bhd',
        'image': 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1200&q=80',
        'url': 'https://nmffurniture.com',
        'telephone': '+60378329920',
        'priceRange': 'RM 450 - RM 8,500',
        'address': {
          '@type': 'PostalAddress',
          'streetAddress': '2nd Floor, Lobby 3, UG41 Komersial Radia, Persiaran Arked, Bukit Jelutong',
          'addressLocality': 'Shah Alam',
          'addressRegion': 'Selangor',
          'postalCode': '40150',
          'addressCountry': 'MY'
        },
        'geo': {
          '@type': 'GeoCoordinates',
          'latitude': 3.1017,
          'longitude': 101.5303
        },
        'openingHoursSpecification': [
          {
            '@type': 'OpeningHoursSpecification',
            'dayOfWeek': ['Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            'opens': '10:30',
            'closes': '19:30'
          }
        ],
        'sameAs': [
          'https://facebook.com/nmffurniture',
          'https://instagram.com/nmffurniture'
        ]
      })
    }
  ]
})
</script>

<style scoped>
.hero-section {
  position: relative;
  min-height: 85vh;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  background-color: var(--color-primary);
  overflow: hidden;
}

.hero-bg-container {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
}

.hero-bg-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
}

.hero-gradient-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(
    90deg,
    rgba(22, 23, 25, 0.88) 0%,
    rgba(22, 23, 25, 0.72) 50%,
    rgba(22, 23, 25, 0.4) 100%
  );
}

.hero-container {
  position: relative;
  z-index: 10;
  padding-top: 5rem;
  padding-bottom: 4rem;
}

.hero-content {
  max-width: 680px;
  color: #FFFFFF;
}

.hero-eyebrow {
  display: inline-block;
  font-family: var(--font-sans);
  font-size: 0.78rem;
  letter-spacing: 0.18em;
  font-weight: 700;
  color: var(--color-secondary);
  margin-bottom: 1.25rem;
  text-transform: uppercase;
}

.hero-title {
  color: #FFFFFF;
  font-size: 3.25rem;
  line-height: 1.18;
  margin-bottom: 1.25rem;
  font-weight: 600;
}

.hero-subtitle {
  font-size: 1.1rem;
  color: #E0DFDB;
  line-height: 1.65;
  margin-bottom: 2.25rem;
}

/* Trust Bar */
.trust-bar {
  position: relative;
  z-index: 10;
  background: rgba(22, 23, 25, 0.95);
  backdrop-filter: blur(12px);
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  padding: 1.25rem 0;
}

.trust-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
}

.trust-item {
  display: flex;
  align-items: center;
  gap: 0.9rem;
  color: #FFFFFF;
}

.trust-item i {
  font-size: 1.35rem;
  color: var(--color-secondary);
  flex-shrink: 0;
}

.trust-item strong {
  display: block;
  font-size: 0.88rem;
  font-weight: 600;
  letter-spacing: 0.02em;
}

.trust-item span {
  display: block;
  font-size: 0.75rem;
  color: #9C9A95;
}

/* Category Card specific styling */
.category-card-media {
  display: block;
  aspect-ratio: 4 / 3;
  overflow: hidden;
  background-color: var(--color-bg-muted);
}

.category-card-link {
  font-size: 0.8rem;
  font-weight: 600;
  color: var(--color-secondary-dark);
  letter-spacing: 0.05em;
  text-transform: uppercase;
  transition: color var(--transition-fast);
}

.category-card-link:hover {
  color: var(--color-primary);
}

/* Craftsmanship Specs Checklist */
.craft-specs-grid {
  display: flex;
  flex-direction: column;
  gap: 0.65rem;
  margin-top: 1.5rem;
  margin-bottom: 0.5rem;
}

.craft-spec-item {
  display: flex;
  align-items: center;
  gap: 0.65rem;
  font-size: 0.9rem;
  font-weight: 500;
  color: var(--color-primary);
}

.craft-spec-item i {
  color: #15803D;
  font-size: 0.95rem;
  flex-shrink: 0;
}

/* Fabric Interactive Box */
.fabric-interactive-box {
  background: #FFFFFF;
  border: 1px solid var(--color-border);
  border-radius: var(--radius-sm);
  padding: 1.25rem 1.4rem;
  margin: 1.75rem 0 2rem;
  box-shadow: var(--shadow-sm);
}

.active-swatch-pill {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: var(--color-bg-alt);
  border: 1px solid var(--color-border);
  padding: 0.2rem 0.65rem;
  border-radius: var(--radius-full);
}

.active-swatch-dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  border: 1px solid rgba(0, 0, 0, 0.15);
}

.active-swatch-name {
  font-size: 0.78rem;
  font-weight: 600;
  color: var(--color-primary);
}

.fabric-trust-notes {
  display: flex;
  flex-wrap: wrap;
  gap: 1.25rem;
  margin-top: 1rem;
  padding-top: 0.85rem;
  border-top: 1px dashed var(--color-border);
  font-size: 0.76rem;
  color: var(--color-text-muted);
}

.fabric-trust-notes span {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
}

.fabric-trust-notes i {
  color: var(--color-secondary-dark);
}

/* Workshop Badges */
.workshop-badges {
  display: flex;
  flex-direction: column;
  gap: 0.65rem;
  margin-top: 1.5rem;
  margin-bottom: 0.5rem;
}

.workshop-badge-item {
  display: flex;
  align-items: center;
  gap: 0.65rem;
  font-size: 0.9rem;
  color: #F3E8DC;
}

.workshop-badge-item i {
  color: var(--color-secondary);
  font-size: 0.95rem;
  flex-shrink: 0;
}

/* Section Title With Navigation for Lookbook Slider */
.section-title-with-nav {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  margin-bottom: 2.5rem;
  gap: 1.5rem;
  flex-wrap: wrap;
}

.slider-nav-btns {
  display: flex;
  gap: 0.5rem;
}

.slider-btn {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  border: 1px solid var(--color-border);
  background: #FFFFFF;
  color: var(--color-primary);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all var(--transition-fast);
}

.slider-btn:hover {
  background: var(--color-primary);
  color: #FFFFFF;
  border-color: var(--color-primary);
  transform: translateY(-1px);
}

/* Showcase slide enhancements */
.showcase-slide-media {
  position: relative;
  aspect-ratio: 4 / 3;
  overflow: hidden;
  background-color: var(--color-bg-muted);
}

.showcase-slide-media img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform var(--transition-slow);
}

.showcase-slide:hover .showcase-slide-media img {
  transform: scale(1.04);
}

.showcase-verified-tag {
  position: absolute;
  top: 12px;
  left: 12px;
  background: rgba(22, 23, 25, 0.85);
  backdrop-filter: blur(4px);
  color: #FFFFFF;
  font-size: 0.68rem;
  font-weight: 600;
  padding: 0.25rem 0.6rem;
  border-radius: var(--radius-full);
  display: inline-flex;
  align-items: center;
  gap: 0.3rem;
  border: 1px solid rgba(255, 255, 255, 0.15);
}

.showcase-verified-tag i {
  color: #4ADE80;
}

.showcase-slide-caption {
  padding: 1.25rem 1rem;
  background: #FFFFFF;
  border-top: 1px solid var(--color-border);
}

.showcase-slide-title {
  font-size: 0.92rem;
  font-weight: 700;
  color: var(--color-primary);
  margin-bottom: 0.25rem;
}

.showcase-slide-desc {
  font-size: 0.78rem;
  color: var(--color-text-muted);
  display: flex;
  align-items: center;
  gap: 0.3rem;
}

.showcase-slide-desc i {
  color: var(--color-secondary-dark);
}

/* Official Manufacturer Trust Seal Bar */
.trust-seal-bar {
  padding: 2.5rem 1.5rem;
  background: #FFFFFF;
  border-top: 1px solid var(--color-border);
}

.trust-seal-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem;
}

.trust-seal-item {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.trust-seal-icon {
  width: 48px;
  height: 48px;
  border-radius: var(--radius-sm);
  background-color: var(--color-bg-alt);
  border: 1px solid var(--color-border);
  color: var(--color-secondary-dark);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  flex-shrink: 0;
}

.trust-seal-item strong {
  display: block;
  font-size: 0.92rem;
  font-weight: 700;
  color: var(--color-primary);
  letter-spacing: 0.02em;
}

.trust-seal-item span {
  display: block;
  font-size: 0.78rem;
  color: var(--color-text-muted);
  margin-top: 0.15rem;
}

@media (max-width: 900px) {
  .trust-seal-grid {
    grid-template-columns: 1fr;
    gap: 1.25rem;
  }
}

@media (max-width: 992px) {
  .hero-title {
    font-size: 2.5rem;
  }
  .trust-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
  }
}

@media (max-width: 600px) {
  .hero-title {
    font-size: 2rem;
  }
  .hero-subtitle {
    font-size: 0.98rem;
  }
  .trust-grid {
    grid-template-columns: 1fr;
  }
}
</style>

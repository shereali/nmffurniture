<template>
  <div class="py-12" style="background-color: #FFFFFF;">
    <div class="container">
      <div class="section-title">
        <span class="section-eyebrow">
          EXPERIENCE IN PERSON
        </span>
        <h2>OUR FLAGSHIP SHOWROOMS</h2>
        <p>
          Visit our design galleries in Shah Alam and Petaling Jaya to touch fabric textures, test cushion firmness, and consult directly with our furniture specialists.
        </p>
      </div>

      <!-- Showrooms List -->
      <div class="flex flex-col gap-12" style="margin-bottom: 5rem;">
        <div
          v-for="(showroom, idx) in showrooms"
          :key="showroom.id"
          class="feature-split"
          :class="{ reverse: idx % 2 === 1 }"
          style="background-color: var(--color-bg-alt); border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 2.5rem; gap: 3rem;"
        >
          <!-- Info -->
          <div>
            <span style="font-size: 0.8rem; font-weight: 700; color: var(--color-secondary-dark); text-transform: uppercase; letter-spacing: 0.08em;">
              {{ showroom.branch }}
            </span>
            <h3 style="font-size: 1.85rem; margin: 0.5rem 0 1rem;">{{ showroom.name }}</h3>

            <p style="font-size: 1rem; color: var(--color-text-main); margin-bottom: 1.25rem; line-height: 1.7;">
              <i class="fa-solid fa-location-dot" style="color: var(--color-secondary-dark); margin-right: 0.5rem;"></i>
              {{ showroom.address }}, {{ showroom.postal_code }} {{ showroom.city }}, {{ showroom.state }}.
            </p>

            <div style="font-size: 0.9rem; margin-bottom: 1.5rem; display: flex; flex-direction: column; gap: 0.5rem;">
              <div>
                <i class="fa-regular fa-clock" style="color: var(--color-secondary-dark); margin-right: 0.5rem;"></i>
                <strong>Hours:</strong> {{ showroom.opening_hours }}
              </div>
              <div v-if="showroom.phone">
                <i class="fa-solid fa-phone" style="color: var(--color-secondary-dark); margin-right: 0.5rem;"></i>
                <strong>Call:</strong> {{ showroom.phone }}
              </div>
            </div>

            <!-- WhatsApp CTA Badge matching reference -->
            <div style="background-color: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-full); padding: 0.75rem 1.25rem; display: inline-flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
              <span style="color: #25D366; font-size: 1.25rem;">
                <i class="fa-brands fa-whatsapp"></i>
              </span>
              <span style="font-size: 0.9rem; font-weight: 600;">
                Living in {{ showroom.city }} / {{ showroom.state }} area?
              </span>
              <a
                :href="`https://wa.me/${showroom.whatsapp_number}?text=${encodeURIComponent(showroom.whatsapp_prefill_text || 'Hye NMFFurniture')}`"
                target="_blank"
                style="color: var(--color-primary); font-weight: 700; text-decoration: underline;"
              >
                WhatsApp Us →
              </a>
            </div>

            <div>
              <a
                :href="`https://wa.me/${showroom.whatsapp_number}?text=Hello%20NMFFurniture,%20I%20would%20like%20to%20schedule%20a%20VIP%20visit%20to%20${encodeURIComponent(showroom.branch)}.`"
                target="_blank"
                class="btn btn-whatsapp"
              >
                <i class="fa-brands fa-whatsapp"></i> Book Showroom Appointment
              </a>
            </div>
          </div>

          <!-- Embedded Map -->
          <div style="height: 380px; border-radius: var(--radius-sm); overflow: hidden; border: 1px solid var(--color-border); box-shadow: var(--shadow-md);">
            <iframe
              :src="showroom.map_embed_url || `https://maps.google.com/maps?q=${encodeURIComponent(showroom.address)}&t=m&z=14&output=embed`"
              width="100%"
              height="100%"
              style="border: 0;"
              allowfullscreen="false"
              loading="lazy"
            ></iframe>
          </div>
        </div>
      </div>

      <!-- Quick Lead Form -->
      <div style="max-width: 750px; margin: 0 auto; background: var(--color-bg-alt); border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
          <h3 style="font-size: 1.6rem; margin-bottom: 0.5rem;">Request Custom Quotation or Swatch Pack</h3>
          <p style="font-size: 0.95rem; margin-bottom: 0;">Fill in your contact details below and our furniture consultant will assist you.</p>
        </div>

        <form @submit.prevent="submitInquiry" class="flex flex-col gap-4">
          <div class="grid grid-cols-2 gap-4">
            <div class="form-group">
              <label class="form-label">Full Name *</label>
              <input v-model="inquiryForm.name" type="text" class="form-input" required placeholder="e.g. Aina Farhana" />
            </div>
            <div class="form-group">
              <label class="form-label">WhatsApp Number *</label>
              <input v-model="inquiryForm.phone" type="tel" class="form-input" required placeholder="e.g. +60123456789" />
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Email Address</label>
            <input v-model="inquiryForm.email" type="email" class="form-input" placeholder="e.g. aina@example.com" />
          </div>

          <div class="form-group">
            <label class="form-label">Your Requirement / Custom Sizing</label>
            <textarea v-model="inquiryForm.message" class="form-textarea" rows="4" required placeholder="Tell us about the sofa model, size, fabric or living room design you need..."></textarea>
          </div>

          <button :disabled="submitting" type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
            <i class="fa-solid fa-paper-plane"></i>
            {{ submitting ? 'Submitting...' : 'Submit Inquiry' }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useToastStore } from '~/stores/toast'

const config = useRuntimeConfig()
const apiBase = config.public.apiBase
const toast = useToastStore()

const showrooms = ref<any[]>([])
const submitting = ref(false)

const inquiryForm = reactive({
  name: '',
  phone: '',
  email: '',
  message: '',
})

// SEO & LocalBusiness Schema
useSeoMeta({
  title: 'Our Showrooms in Shah Alam & Petaling Jaya | NMFFurniture Malaysia',
  description: 'Visit NMFFurniture design galleries at Radia Bukit Jelutong (Shah Alam) and Seksyen 13 (PJ). Experience 200+ fabric swatches, test sofa comfort, and consult with our specialists.',
  ogTitle: 'Visit NMFFurniture Showrooms in Shah Alam & PJ',
  ogDescription: 'Experience luxury handcrafted sofas live. Radia Bukit Jelutong & Seksyen 13 Petaling Jaya.',
  ogImage: 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1200&q=80',
})

useHead({
  link: [
    { rel: 'canonical', href: 'https://nmffurniture.com/our-showroom' }
  ],
  script: [
    {
      type: 'application/ld+json',
      children: JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'FurnitureStore',
        'name': 'NMFFurniture Flagship Showroom Bukit Jelutong',
        'image': 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1200&q=80',
        'url': 'https://nmffurniture.com/our-showroom',
        'telephone': '+60378329920',
        'address': {
          '@type': 'PostalAddress',
          'streetAddress': '2nd Floor, Lobby 3, UG41 Komersial Radia, Persiaran Arked, Bukit Jelutong',
          'addressLocality': 'Shah Alam',
          'addressRegion': 'Selangor',
          'postalCode': '40150',
          'addressCountry': 'MY'
        },
        'openingHoursSpecification': [
          {
            '@type': 'OpeningHoursSpecification',
            'dayOfWeek': ['Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            'opens': '10:30',
            'closes': '19:30'
          }
        ]
      })
    }
  ]
})

async function loadShowrooms() {
  try {
    const res: any = await $fetch(`${apiBase}/showrooms`)
    showrooms.value = res.showrooms || []
  } catch (e) {
    console.error('Failed to load showrooms', e)
  }
}

async function submitInquiry() {
  submitting.value = true
  try {
    const res: any = await $fetch(`${apiBase}/inquiries`, {
      method: 'POST',
      body: inquiryForm,
    })
    toast.show(res.message || 'Inquiry submitted successfully!', 'success')
    inquiryForm.name = ''
    inquiryForm.phone = ''
    inquiryForm.email = ''
    inquiryForm.message = ''
  } catch (err: any) {
    toast.show('Failed to submit inquiry. Please try again.', 'error')
  } finally {
    submitting.value = false
  }
}

onMounted(() => {
  loadShowrooms()
})
</script>

<style scoped>
@media (max-width: 640px) {
  .grid-cols-2 {
    grid-template-columns: 1fr !important;
  }
  div[style*="padding: 2.5rem"] {
    padding: 1.5rem !important;
  }
}
</style>

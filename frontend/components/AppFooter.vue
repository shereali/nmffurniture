<template>
  <footer class="site-footer">
    <div class="container">
      <div class="footer-grid">
        <!-- Col 1: About & SSM -->
        <div class="footer-col">
          <h3 style="color: #FFFFFF; font-size: 1.5rem; margin-bottom: 0.35rem; letter-spacing: 0.05em;">
            {{ siteName }}
          </h3>
          <p style="font-size: 0.85rem; color: var(--color-secondary); margin-bottom: 0.85rem; font-weight: 500;">
            {{ siteTagline }}
          </p>
          <p style="margin-bottom: 1rem;">
            {{ footerAboutText }}
          </p>
          <div style="font-size: 0.85rem; color: #8E939A;">
            <strong>Registration:</strong> {{ ssmNumber }}
          </div>
        </div>

        <!-- Col 2: Collections (Dynamic Menu) -->
        <div class="footer-col">
          <h4>Collections</h4>
          <ul class="flex flex-col gap-2">
            <li v-for="item in footerCol1Items" :key="item.id">
              <NuxtLink :to="item.url" :target="item.target || '_self'">
                <i v-if="item.icon" :class="item.icon" style="margin-right: 0.35rem; font-size: 0.8rem; color: var(--color-secondary-dark);"></i>
                {{ item.title }}
                <span v-if="item.badge" class="brand-badge" style="margin-left: 0.4rem; font-size: 0.6rem;">{{ item.badge }}</span>
              </NuxtLink>
            </li>
          </ul>
        </div>

        <!-- Col 3: Experience & Services (Dynamic Menu) -->
        <div class="footer-col">
          <h4>Experience</h4>
          <ul class="flex flex-col gap-2">
            <li v-for="item in footerCol2Items" :key="item.id">
              <NuxtLink :to="item.url" :target="item.target || '_self'">
                <i v-if="item.icon" :class="item.icon" style="margin-right: 0.35rem; font-size: 0.8rem; color: var(--color-secondary-dark);"></i>
                {{ item.title }}
                <span v-if="item.badge" class="brand-badge" style="margin-left: 0.4rem; font-size: 0.6rem;">{{ item.badge }}</span>
              </NuxtLink>
            </li>
          </ul>
        </div>

        <!-- Col 4: Showroom & Direct Contact -->
        <div class="footer-col">
          <h4>{{ showroomTitle }}</h4>
          <p style="font-size: 0.875rem; margin-bottom: 0.65rem;">
            {{ showroomAddress }}
          </p>

          <div class="footer-hours-badge">
            <i class="fa-regular fa-clock"></i>
            <span>{{ showroomHours }}</span>
          </div>

          <div style="margin-top: 1rem;">
            <a
              :href="`https://wa.me/${footerWhatsapp}?text=Hye%20NMFFurniture%20BJ`"
              target="_blank"
              class="footer-whatsapp-btn"
            >
              <i class="fa-brands fa-whatsapp"></i>
              <span>{{ whatsappBtnText }}</span>
            </a>
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        <p style="margin-bottom: 0.35rem;">
          © {{ new Date().getFullYear() }} {{ copyrightText }} ({{ ssmNumber }}).
        </p>
        <p style="font-size: 0.775rem; color: #6E737B; margin-bottom: 0;">
          {{ guaranteeBadge }}
        </p>
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts">
import { useSettingsStore } from '~/stores/settings'

const settingsStore = useSettingsStore()
const config = useRuntimeConfig()

const siteName = computed(() => settingsStore.getSetting('site_name', 'NMF'))
const siteTagline = computed(() => settingsStore.getSetting('site_tagline', 'FURNITURE'))
const ssmNumber = computed(() => settingsStore.getSetting('ssm_number', config.public.ssmNumber || 'SSM 1400875-P'))
const footerAboutText = computed(() => settingsStore.getSetting('footer_about_text', 'NMFFurniture Sdn Bhd is a leading in-house manufacturer of high-quality handcrafted sofas and bespoke living furniture in Malaysia. Every piece is built with a reinforced solid wood frame backed by our 5-Year Wood Warranty.'))

const showroomTitle = computed(() => settingsStore.getSetting('footer_showroom_title', 'Shah Alam Showroom'))
const showroomAddress = computed(() => settingsStore.getSetting('footer_showroom_address', '2nd Floor, Lobby 3, UG41 Komersial Radia, Persiaran Arked, Bukit Jelutong, 40150 Shah Alam, Selangor.'))
const showroomHours = computed(() => settingsStore.getSetting('footer_showroom_hours', 'Tue – Sun: 10:30 AM – 7:30 PM'))
const footerWhatsapp = computed(() => settingsStore.getSetting('support_whatsapp', config.public.whatsappDefault || '60192589920'))
const whatsappBtnText = computed(() => settingsStore.getSetting('footer_whatsapp_btn_text', 'Chat with Showroom Concierge'))
const copyrightText = computed(() => settingsStore.getSetting('footer_copyright_text', 'NMFFURNITURE SDN. BHD. All rights reserved.'))
const guaranteeBadge = computed(() => settingsStore.getSetting('footer_guarantee_badge', 'Handcrafted Luxury Sofas • Solid Wood Frame 5-Year Warranty • Made in Malaysia'))

const footerCol1Items = computed(() => {
  const items = settingsStore.getMenu('footer_col_1')
  if (items && items.length > 0) return items
  return [
    { id: 1, title: 'Living Sofas', url: '/shop?category=sofa' },
    { id: 2, title: 'Wing Chairs', url: '/shop?category=wing-chair' },
    { id: 3, title: 'Coffee Tables', url: '/shop?category=coffee-table' },
    { id: 4, title: 'Luxury Bedframes', url: '/shop?category=bed-frame' },
    { id: 5, title: 'Dining Sets', url: '/shop?category=dining-table' },
    { id: 6, title: 'View All Products', url: '/shop' },
  ]
})

const footerCol2Items = computed(() => {
  const items = settingsStore.getMenu('footer_col_2')
  if (items && items.length > 0) return items
  return [
    { id: 1, title: 'Showroom Locations', url: '/our-showroom' },
    { id: 2, title: 'Project Lookbook', url: '/gallery' },
    { id: 3, title: 'Pet-Friendly Fabrics', url: '/shop' },
    { id: 4, title: 'Track Live Order', url: '/track-order' },
    { id: 5, title: 'Account & Past Orders', url: '/account/orders' },
  ]
})
</script>

<style scoped>
.footer-hours-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: rgba(255, 255, 255, 0.06);
  border: 1px solid rgba(255, 255, 255, 0.12);
  padding: 0.35rem 0.75rem;
  border-radius: var(--radius-sm);
  font-size: 0.78rem;
  color: #D1D5DB;
  margin-top: 0.25rem;
}

.footer-hours-badge i {
  color: var(--color-secondary);
  font-size: 0.85rem;
}

.footer-whatsapp-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.65rem;
  width: 100%;
  padding: 0.85rem 1.25rem;
  background: linear-gradient(135deg, #1EBE5D 0%, #15803D 100%);
  color: #FFFFFF !important;
  font-family: var(--font-sans);
  font-size: 0.85rem;
  font-weight: 600;
  letter-spacing: 0.02em;
  border-radius: var(--radius-sm);
  border: 1px solid rgba(255, 255, 255, 0.18);
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.3), 0 2px 6px rgba(30, 190, 93, 0.2);
  text-decoration: none;
  transition: all var(--transition-fast);
  cursor: pointer;
}

.footer-whatsapp-btn i {
  font-size: 1.15rem;
  color: #FFFFFF !important;
  flex-shrink: 0;
  transition: transform 0.2s ease;
}

.footer-whatsapp-btn:hover {
  background: linear-gradient(135deg, #22C55E 0%, #16A34A 100%);
  color: #FFFFFF !important;
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(30, 190, 93, 0.4), 0 2px 6px rgba(0, 0, 0, 0.2);
}

.footer-whatsapp-btn:hover i {
  transform: scale(1.1);
}
</style>

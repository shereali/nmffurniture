<template>
  <footer class="site-footer">
    <div class="container">
      <div class="footer-grid">
        <!-- Col 1: About & SSM -->
        <div class="footer-col">
          <h3 style="color: #FFFFFF; font-size: 1.5rem; margin-bottom: 0.75rem; letter-spacing: 0.05em;">
            NMF <span style="color: var(--color-secondary);">FURNITURE</span>
          </h3>
          <p style="margin-bottom: 1rem;">
            NMFFurniture Sdn Bhd is a leading in-house manufacturer of high-quality handcrafted sofas and bespoke living furniture in Malaysia. Every piece is built with a reinforced solid wood frame backed by our 5-Year Wood Warranty.
          </p>
          <div style="font-size: 0.85rem; color: #8E939A;">
            <strong>Registration:</strong> {{ ssmNumber }}
          </div>
        </div>

        <!-- Col 2: Collections -->
        <div class="footer-col">
          <h4>Collections</h4>
          <ul class="flex flex-col gap-2">
            <li><NuxtLink to="/shop?category=sofa">Living Sofas</NuxtLink></li>
            <li><NuxtLink to="/shop?category=wing-chair">Wing Chairs</NuxtLink></li>
            <li><NuxtLink to="/shop?category=coffee-table">Coffee Tables</NuxtLink></li>
            <li><NuxtLink to="/shop?category=bed-frame">Luxury Bedframes</NuxtLink></li>
            <li><NuxtLink to="/shop?category=dining-table">Dining Sets</NuxtLink></li>
            <li><NuxtLink to="/shop">View All Products</NuxtLink></li>
          </ul>
        </div>

        <!-- Col 3: Experience -->
        <div class="footer-col">
          <h4>Experience</h4>
          <ul class="flex flex-col gap-2">
            <li><NuxtLink to="/our-showroom">Showroom Locations</NuxtLink></li>
            <li><NuxtLink to="/gallery">Project Lookbook</NuxtLink></li>
            <li><NuxtLink to="/shop">Pet-Friendly Fabrics</NuxtLink></li>
            <li><NuxtLink to="/track-order">Track Live Order</NuxtLink></li>
            <li><NuxtLink to="/account/orders">Account & Past Orders</NuxtLink></li>
          </ul>
        </div>

        <!-- Col 4: Showroom & Direct Contact -->
        <div class="footer-col">
          <h4>Shah Alam Showroom</h4>
          <p style="font-size: 0.875rem; margin-bottom: 0.65rem;">
            2nd Floor, Lobby 3, UG41 Komersial Radia, Persiaran Arked, Bukit Jelutong, 40150 Shah Alam, Selangor.
          </p>

          <div class="footer-hours-badge">
            <i class="fa-regular fa-clock"></i>
            <span>Tue – Sun: 10:30 AM – 7:30 PM</span>
          </div>

          <div style="margin-top: 1rem;">
            <a
              :href="`https://wa.me/${whatsappDefault}?text=Hye%20NMFFurniture%20BJ`"
              target="_blank"
              class="footer-whatsapp-btn"
            >
              <i class="fa-brands fa-whatsapp"></i>
              <span>Chat with Showroom Concierge</span>
            </a>
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        <p style="margin-bottom: 0.35rem;">
          © {{ new Date().getFullYear() }} NMFFURNITURE SDN. BHD. ({{ ssmNumber }}). All rights reserved.
        </p>
        <p style="font-size: 0.775rem; color: #6E737B; margin-bottom: 0;">
          Handcrafted Luxury Sofas • Solid Wood Frame 5-Year Warranty • Made in Malaysia
        </p>
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts">
const config = useRuntimeConfig()
const ssmNumber = config.public.ssmNumber || 'SSM 1400875-P'
const whatsappDefault = config.public.whatsappDefault || '60192589920'
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

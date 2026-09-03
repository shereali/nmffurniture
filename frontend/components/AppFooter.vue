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
            <li><NuxtLink to="/our-showroom">Custom Color Palette</NuxtLink></li>
            <li><NuxtLink to="/account/orders">Track My Order</NuxtLink></li>
          </ul>
        </div>

        <!-- Col 4: Showroom & Direct Contact -->
        <div class="footer-col">
          <h4>Shah Alam Showroom</h4>
          <p style="font-size: 0.875rem; margin-bottom: 0.75rem;">
            2nd Floor, Lobby 3, UG41 Komersial Radia, Persiaran Arked, Bukit Jelutong, 40150 Shah Alam, Selangor.
          </p>
          <div style="margin-top: 1rem;">
            <a
              :href="`https://wa.me/${whatsappDefault}?text=Hye%20NMFFurniture%20BJ`"
              target="_blank"
              class="btn btn-whatsapp btn-sm"
              style="width: 100%; text-transform: none;"
            >
              <i class="fa-brands fa-whatsapp"></i> Chat with Shah Alam Showroom
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

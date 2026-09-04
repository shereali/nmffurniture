// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  srcDir: '.',
  devtools: { enabled: true },

  modules: [
    '@pinia/nuxt',
  ],

  css: [
    '~/assets/css/variables.css',
    '~/assets/css/main.css',
    '~/assets/css/components.css',
    '~/assets/css/admin.css',
  ],

  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:8001/api',
      siteName: 'NMFFurniture | Handcrafted Luxury Sofas & Living in Malaysia',
      ssmNumber: 'SSM 1400875-P',
      whatsappDefault: '60192589920',
    }
  },

  app: {
    head: {
      htmlAttrs: {
        lang: 'en-MY'
      },
      title: 'NMFFurniture | Handcrafted Modern Sofas & Living Furniture Malaysia',
      titleTemplate: '%s · NMFFurniture Malaysia',
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1, maximum-scale=5' },
        { name: 'theme-color', content: '#161719' },
        { name: 'format-detection', content: 'telephone=no' },
        { name: 'robots', content: 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1' },
        { name: 'description', content: 'NMFFurniture Sdn Bhd - Leading in-house sofa & luxury living manufacturer in Malaysia. 5-Year Solid Wood Warranty, 200+ pet-friendly fabrics, bespoke sizing & direct factory pricing in Shah Alam & PJ.' },
        { name: 'keywords', content: 'sofa malaysia, custom sofa shah alam, pet friendly fabric sofa, solid wood sofa warranty, handcrafted furniture bukit jelutong, luxury furniture kuala lumpur, factory direct sofa malaysia' },
        // Open Graph / Facebook
        { property: 'og:site_name', content: 'NMFFurniture Malaysia' },
        { property: 'og:type', content: 'website' },
        { property: 'og:locale', content: 'en_MY' },
        { property: 'og:title', content: 'NMFFurniture | Handcrafted Modern Sofas & Living Furniture' },
        { property: 'og:description', content: 'Leading Malaysian in-house manufacturer of luxury living furniture. 5-Year Solid Wood Warranty & 200+ pet-friendly fabrics.' },
        { property: 'og:image', content: 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1200&q=80' },
        // Twitter
        { name: 'twitter:card', content: 'summary_large_image' },
        { name: 'twitter:title', content: 'NMFFurniture | Handcrafted Sofas & Living Furniture Malaysia' },
        { name: 'twitter:description', content: 'Factory-direct handcrafted sofas with 5-Year Solid Hardwood Warranty & 200+ pet-friendly fabrics.' },
        { name: 'twitter:image', content: 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1200&q=80' },
      ],
      link: [
        { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
        { rel: 'preconnect', href: 'https://fonts.googleapis.com' },
        { rel: 'preconnect', href: 'https://fonts.gstatic.com', crossorigin: '' },
        { rel: 'preconnect', href: 'https://images.unsplash.com' },
        { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap' },
        { rel: 'stylesheet', href: 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css' },
      ]
    }
  }
})

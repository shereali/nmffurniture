export default defineNuxtPlugin((nuxtApp) => {
  const config = useRuntimeConfig()
  const gaId = config.public.googleAnalyticsId || 'G-NMFFURNITURE'

  // Inject Google Analytics script if on client
  if (import.meta.client && typeof window !== 'undefined') {
    window.dataLayer = window.dataLayer || []
    function gtag(...args: any[]) {
      window.dataLayer.push(args)
    }
    window.gtag = gtag
    gtag('js', new Date())
    gtag('config', gaId, { send_page_view: true })

    // Load gtag script asynchronously
    const script = document.createElement('script')
    script.async = true
    script.src = `https://www.googletagmanager.com/gtag/js?id=${gaId}`
    document.head.appendChild(script)
  }

  return {
    provide: {
      analytics: {
        trackViewItem(product: any) {
          if (!import.meta.client || !window.gtag) return
          window.gtag('event', 'view_item', {
            currency: 'MYR',
            value: Number(product.discount_price ?? product.price),
            items: [
              {
                item_id: String(product.sku || product.id),
                item_name: product.name,
                item_category: product.category?.name || 'Furniture',
                price: Number(product.discount_price ?? product.price),
              }
            ]
          })
        },

        trackAddToCart(product: any, variant?: string, quantity: number = 1) {
          if (!import.meta.client || !window.gtag) return
          window.gtag('event', 'add_to_cart', {
            currency: 'MYR',
            value: Number(product.discount_price ?? product.price) * quantity,
            items: [
              {
                item_id: String(product.sku || product.id),
                item_name: product.name,
                item_variant: variant,
                price: Number(product.discount_price ?? product.price),
                quantity,
              }
            ]
          })
        },

        trackBeginCheckout(items: any[], total: number) {
          if (!import.meta.client || !window.gtag) return
          window.gtag('event', 'begin_checkout', {
            currency: 'MYR',
            value: total,
            items: items.map(item => ({
              item_id: String(item.product?.sku || item.productId),
              item_name: item.product?.name || item.name,
              item_variant: item.variant,
              price: Number(item.price),
              quantity: item.quantity,
            }))
          })
        },

        trackPurchase(orderNumber: string, total: number, items: any[], couponCode?: string) {
          if (!import.meta.client || !window.gtag) return
          window.gtag('event', 'purchase', {
            transaction_id: orderNumber,
            value: total,
            currency: 'MYR',
            coupon: couponCode || undefined,
            items: items.map(item => ({
              item_id: String(item.product?.sku || item.productId || item.id),
              item_name: item.product?.name || item.name,
              item_variant: item.variant,
              price: Number(item.price),
              quantity: item.quantity,
            }))
          })
        },

        trackLead(type: string, label: string) {
          if (!import.meta.client || !window.gtag) return
          window.gtag('event', 'generate_lead', {
            event_category: 'Lead',
            lead_type: type,
            lead_label: label,
          })
        }
      }
    }
  }
})

declare global {
  interface Window {
    dataLayer: any[]
    gtag: (...args: any[]) => void
  }
}

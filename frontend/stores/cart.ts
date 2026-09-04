import { defineStore } from 'pinia'
import { useToastStore } from './toast'

export interface CartItem {
  productId: number
  name: string
  sku: string
  price: number
  image: string
  variantOption?: string
  quantity: number
}

export interface AppliedCoupon {
  code: string
  type: 'fixed' | 'percentage'
  value: number
  discountAmount: number
}

export const useCartStore = defineStore('cart', () => {
  const items = ref<CartItem[]>([])
  const isDrawerOpen = ref(false)
  const appliedCoupon = ref<AppliedCoupon | null>(null)

  const config = useRuntimeConfig()
  const apiBase = config.public.apiBase

  const count = computed(() => items.value.reduce((acc, item) => acc + item.quantity, 0))
  
  const subtotal = computed(() => {
    return items.value.reduce((acc, item) => acc + (item.price * item.quantity), 0)
  })

  const discountAmount = computed(() => {
    if (!appliedCoupon.value) return 0
    return appliedCoupon.value.discountAmount
  })

  const shippingFee = computed(() => {
    if (subtotal.value === 0) return 0
    return subtotal.value >= 3000 ? 0 : 150
  })

  const total = computed(() => {
    return Math.max(0, subtotal.value - discountAmount.value) + shippingFee.value
  })

  function init() {
    if (import.meta.client) {
      const saved = localStorage.getItem('nmf_cart_items')
      if (saved) {
        try {
          items.value = JSON.parse(saved)
        } catch (e) {
          items.value = []
        }
      }

      const savedCoupon = localStorage.getItem('nmf_applied_coupon')
      if (savedCoupon) {
        try {
          appliedCoupon.value = JSON.parse(savedCoupon)
        } catch (e) {
          appliedCoupon.value = null
        }
      }
    }
  }

  function save() {
    if (import.meta.client) {
      localStorage.setItem('nmf_cart_items', JSON.stringify(items.value))
      if (appliedCoupon.value) {
        localStorage.setItem('nmf_applied_coupon', JSON.stringify(appliedCoupon.value))
      } else {
        localStorage.removeItem('nmf_applied_coupon')
      }
    }
  }

  async function applyCoupon(code: string): Promise<boolean> {
    const toast = useToastStore()
    if (!code || !code.trim()) {
      toast.show('Please enter a coupon code', 'error')
      return false
    }

    try {
      const res: any = await $fetch(`${apiBase}/coupons/validate`, {
        method: 'POST',
        body: {
          code: code.trim(),
          subtotal: subtotal.value,
        }
      })

      if (res.valid) {
        appliedCoupon.value = {
          code: res.code,
          type: res.type,
          value: res.value,
          discountAmount: res.discount_amount,
        }
        save()
        toast.show(res.message || 'Coupon applied!', 'success')
        return true
      }
      return false
    } catch (err: any) {
      const msg = err.data?.message || 'Invalid promo code'
      toast.show(msg, 'error')
      return false
    }
  }

  function removeCoupon() {
    appliedCoupon.value = null
    save()
    const toast = useToastStore()
    toast.show('Coupon removed', 'info')
  }

  function addItem(product: any, variantOption?: string, quantity = 1) {
    const toast = useToastStore()
    const price = Number(product.discount_price ?? product.price)
    const primaryImg = product.images?.[0]?.image_url || 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=600&q=80'

    const existingIndex = items.value.findIndex(
      it => it.productId === product.id && it.variantOption === variantOption
    )

    if (existingIndex > -1) {
      items.value[existingIndex].quantity += quantity
    } else {
      items.value.push({
        productId: product.id,
        name: product.name,
        sku: product.sku,
        price,
        image: primaryImg,
        variantOption,
        quantity,
      })
    }

    save()
    toast.show(`Added "${product.name}" to cart!`, 'success')
    isDrawerOpen.value = true
  }

  function updateQuantity(productId: number, variantOption: string | undefined, qty: number) {
    const item = items.value.find(
      it => it.productId === productId && it.variantOption === variantOption
    )
    if (item) {
      if (qty <= 0) {
        removeItem(productId, variantOption)
      } else {
        item.quantity = qty
        save()
      }
    }
  }

  function removeItem(productId: number, variantOption?: string) {
    const toast = useToastStore()
    items.value = items.value.filter(
      it => !(it.productId === productId && it.variantOption === variantOption)
    )
    save()
    toast.show('Item removed from cart', 'info')
  }

  function clearCart() {
    items.value = []
    appliedCoupon.value = null
    save()
  }

  function toggleDrawer(open?: boolean) {
    isDrawerOpen.value = open !== undefined ? open : !isDrawerOpen.value
  }

  return {
    items,
    isDrawerOpen,
    appliedCoupon,
    count,
    subtotal,
    discountAmount,
    shippingFee,
    total,
    init,
    addItem,
    updateQuantity,
    removeItem,
    clearCart,
    toggleDrawer,
    applyCoupon,
    removeCoupon,
  }
})

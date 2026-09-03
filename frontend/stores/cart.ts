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

export const useCartStore = defineStore('cart', () => {
  const items = ref<CartItem[]>([])
  const isDrawerOpen = ref(false)

  const count = computed(() => items.value.reduce((acc, item) => acc + item.quantity, 0))
  
  const subtotal = computed(() => {
    return items.value.reduce((acc, item) => acc + (item.price * item.quantity), 0)
  })

  const shippingFee = computed(() => {
    if (subtotal.value === 0) return 0
    return subtotal.value >= 3000 ? 0 : 150
  })

  const total = computed(() => subtotal.value + shippingFee.value)

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
    }
  }

  function save() {
    if (import.meta.client) {
      localStorage.setItem('nmf_cart_items', JSON.stringify(items.value))
    }
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
    save()
  }

  function toggleDrawer(open?: boolean) {
    isDrawerOpen.value = open !== undefined ? open : !isDrawerOpen.value
  }

  return {
    items,
    isDrawerOpen,
    count,
    subtotal,
    shippingFee,
    total,
    init,
    addItem,
    updateQuantity,
    removeItem,
    clearCart,
    toggleDrawer,
  }
})

import { defineStore } from 'pinia'
import { useToastStore } from './toast'

export interface WishlistItem {
  productId: number
  name: string
  sku: string
  price: number
  discountPrice?: number | null
  image: string
  slug: string
  categoryName?: string
}

export const useWishlistStore = defineStore('wishlist', () => {
  const items = ref<WishlistItem[]>([])

  const count = computed(() => items.value.length)

  function init() {
    if (import.meta.client) {
      const saved = localStorage.getItem('nmf_wishlist_items')
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
      localStorage.setItem('nmf_wishlist_items', JSON.stringify(items.value))
    }
  }

  function isInWishlist(productId: number): boolean {
    return items.value.some(it => it.productId === productId)
  }

  function toggleWishlist(product: any) {
    const toast = useToastStore()
    const index = items.value.findIndex(it => it.productId === product.id)

    if (index > -1) {
      items.value.splice(index, 1)
      save()
      toast.show(`Removed "${product.name}" from your Wishlist`, 'info')
    } else {
      const primaryImg = product.images?.[0]?.image_url || 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=600&q=80'
      items.value.push({
        productId: product.id,
        name: product.name,
        sku: product.sku,
        price: Number(product.price),
        discountPrice: product.discount_price ? Number(product.discount_price) : null,
        image: primaryImg,
        slug: product.slug,
        categoryName: product.category?.name,
      })
      save()
      toast.show(`Saved "${product.name}" to your Wishlist!`, 'success')
    }
  }

  function removeFromWishlist(productId: number) {
    const toast = useToastStore()
    items.value = items.value.filter(it => it.productId !== productId)
    save()
    toast.show('Item removed from Wishlist', 'info')
  }

  function clearWishlist() {
    items.value = []
    save()
  }

  return {
    items,
    count,
    init,
    isInWishlist,
    toggleWishlist,
    removeFromWishlist,
    clearWishlist,
  }
})

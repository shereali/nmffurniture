/**
 * Google Rich Snippets JSON-LD Structured Data Composable for NMFFurniture
 */
export function useSchemaOrg() {
  function injectProductSchema(product: any, reviewData?: any) {
    if (!product) return

    const price = Number(product.discount_price ?? product.price)
    const images = (product.images || []).map((img: any) => img.image_url)

    const schema: Record<string, any> = {
      '@context': 'https://schema.org',
      '@type': 'Product',
      name: product.name,
      image: images.length > 0 ? images : ['https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1200&q=80'],
      description: product.short_description || product.description,
      sku: product.sku,
      mpn: product.sku,
      brand: {
        '@type': 'Brand',
        name: 'NMFFurniture'
      },
      manufacturer: {
        '@type': 'Organization',
        name: 'NMFFurniture Sdn Bhd'
      },
      offers: {
        '@type': 'Offer',
        url: typeof window !== 'undefined' ? window.location.href : `https://nmffurniture.my/shop/${product.slug}`,
        priceCurrency: 'MYR',
        price: price.toFixed(2),
        priceValidUntil: '2027-12-31',
        itemCondition: 'https://schema.org/NewCondition',
        availability: 'https://schema.org/InStock',
        seller: {
          '@type': 'Organization',
          name: 'NMFFurniture Sdn Bhd'
        }
      }
    }

    if (reviewData && reviewData.total_reviews > 0) {
      schema.aggregateRating = {
        '@type': 'AggregateRating',
        ratingValue: reviewData.average_rating.toFixed(1),
        reviewCount: reviewData.total_reviews,
        bestRating: '5',
        worstRating: '1'
      }
    }

    useHead({
      script: [
        {
          type: 'application/ld+json',
          innerHTML: JSON.stringify(schema)
        }
      ]
    })
  }

  function injectLocalBusinessSchema() {
    const localSchema = {
      '@context': 'https://schema.org',
      '@type': 'FurnitureStore',
      name: 'NMFFurniture Malaysia',
      image: 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1200&q=80',
      telephone: '+60192589920',
      email: 'care@nmffurniture.my',
      address: {
        '@type': 'PostalAddress',
        streetAddress: 'Bukit Jelutong Industrial Hub',
        addressLocality: 'Shah Alam',
        addressRegion: 'Selangor',
        postalCode: '40150',
        addressCountry: 'MY'
      },
      geo: {
        '@type': 'GeoCoordinates',
        latitude: 3.1025,
        longitude: 101.5284
      },
      openingHoursSpecification: [
        {
          '@type': 'OpeningHoursSpecification',
          dayOfWeek: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
          opens: '10:00',
          closes: '19:00'
        }
      ],
      priceRange: 'RM 500 - RM 10,000'
    }

    useHead({
      script: [
        {
          type: 'application/ld+json',
          innerHTML: JSON.stringify(localSchema)
        }
      ]
    })
  }

  return {
    injectProductSchema,
    injectLocalBusinessSchema
  }
}

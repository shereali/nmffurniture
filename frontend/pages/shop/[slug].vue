<template>
  <div class="py-8" style="background-color: #FFFFFF; min-height: 85vh;">
    <div class="container">
      <!-- Breadcrumbs -->
      <nav style="display: flex; gap: 0.5rem; font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 2rem;">
        <NuxtLink to="/">Home</NuxtLink>
        <span>/</span>
        <NuxtLink to="/shop">Shop</NuxtLink>
        <span v-if="product?.category">/</span>
        <NuxtLink v-if="product?.category" :to="`/shop?category=${product.category.slug}`">
          {{ product.category.name }}
        </NuxtLink>
        <span>/</span>
        <span style="color: var(--color-primary); font-weight: 600;">{{ product?.name || 'Product' }}</span>
      </nav>

      <div v-if="loading" class="text-center py-20">
        <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 2.5rem; color: var(--color-secondary-dark);"></i>
        <p style="margin-top: 1rem;">Loading handcrafted details...</p>
      </div>

      <div v-else-if="!product" class="text-center py-20">
        <h3>Product not found</h3>
        <NuxtLink to="/shop" class="btn btn-primary" style="margin-top: 1rem;">Back to Shop</NuxtLink>
      </div>

      <div v-else>
        <!-- Product Main Grid -->
        <div style="display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 4rem; margin-bottom: 4rem; align-items: start;">
          <!-- Left: Gallery -->
          <div>
            <!-- Main Image Zoom -->
            <div style="aspect-ratio: 4 / 3; background-color: var(--color-bg-alt); border: 1px solid var(--color-border); border-radius: var(--radius-sm); overflow: hidden; margin-bottom: 1rem; position: relative;">
              <img
                :src="activeImage"
                :alt="product.name"
                style="width: 100%; height: 100%; object-fit: cover;"
              />
              <span class="product-badge" style="position: absolute; top: 16px; left: 16px;">
                <i class="fa-solid fa-shield-halved"></i> 5-Year Wood Warranty
              </span>

              <!-- Wishlist Floating Icon -->
              <button
                @click="wishlistStore.toggleWishlist(product)"
                class="detail-wishlist-btn"
                :class="{ active: isWishlisted }"
                :title="isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist'"
              >
                <i :class="isWishlisted ? 'fa-solid fa-heart' : 'fa-regular fa-heart'"></i>
              </button>
            </div>

            <!-- Thumbnail Selector -->
            <div v-if="product.images && product.images.length > 1" class="flex gap-3">
              <div
                v-for="(img, i) in product.images"
                :key="i"
                @click="activeImage = img.image_url"
                :style="{
                  width: '90px',
                  height: '70px',
                  borderRadius: 'var(--radius-sm)',
                  overflow: 'hidden',
                  cursor: 'pointer',
                  border: activeImage === img.image_url ? '2px solid var(--color-primary)' : '1px solid var(--color-border)'
                }"
              >
                <img :src="img.image_url" :alt="product.name" style="width: 100%; height: 100%; object-fit: cover;" />
              </div>
            </div>
          </div>

          <!-- Right: Details & Purchase -->
          <div>
            <div class="flex items-center justify-between" style="margin-bottom: 0.5rem;">
              <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-secondary-dark); letter-spacing: 0.08em; text-transform: uppercase;">
                SKU: {{ product.sku }} • {{ product.category?.name }}
              </span>
              <!-- Rating Snapshot -->
              <a href="#reviews-section" class="flex items-center gap-1.5" style="text-decoration: none; font-size: 0.85rem; color: #F59E0B;">
                <span>★★★★★</span>
                <span style="font-weight: 700; color: var(--color-text-main);">{{ reviewData.average_rating.toFixed(1) }}</span>
                <span style="color: var(--color-text-muted);">({{ reviewData.total_reviews }} reviews)</span>
              </a>
            </div>

            <h1 style="font-size: 2.25rem; font-family: var(--font-heading); margin-bottom: 1rem; line-height: 1.25;">
              {{ product.name }}
            </h1>

            <!-- Price -->
            <div class="flex items-baseline gap-3" style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--color-border);">
              <span style="font-size: 2rem; font-weight: 700; font-family: var(--font-sans); color: var(--color-primary);">
                RM {{ calculateTotalPrice().toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
              </span>
              <span v-if="product.discount_price" style="font-size: 1.2rem; color: var(--color-text-light); text-decoration: line-through;">
                RM {{ Number(product.price).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}
              </span>
              <span style="font-size: 0.8rem; background: #E8F5E9; color: #2E7D32; padding: 0.25rem 0.6rem; border-radius: var(--radius-sm); font-weight: 700;">
                In Stock (Direct Factory Price)
              </span>
            </div>

            <!-- Short description -->
            <p style="font-size: 1rem; color: var(--color-text-main); margin-bottom: 1.5rem; line-height: 1.7;">
              {{ product.short_description }}
            </p>

            <!-- Variants Selector -->
            <div v-if="product.variants && product.variants.length > 0" style="margin-bottom: 2rem;">
              <div class="flex items-center justify-between" style="margin-bottom: 0.75rem;">
                <label class="form-label" style="font-size: 0.9rem; margin-bottom: 0;">
                  Select Material / Fabric Finish:
                </label>
                <button
                  @click="isSwatchModalOpen = true"
                  class="link-button"
                  style="font-size: 0.8rem; color: var(--color-secondary-dark); text-decoration: underline; background: none; border: none; cursor: pointer; font-weight: 600;"
                >
                  <i class="fa-solid fa-swatchbook"></i> Request Free Swatches
                </button>
              </div>

              <div class="flex flex-col gap-2">
                <label
                  v-for="v in product.variants"
                  :key="v.id"
                  :style="{
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'space-between',
                    padding: '0.85rem 1.25rem',
                    border: selectedVariant?.id === v.id ? '2px solid var(--color-primary)' : '1px solid var(--color-border)',
                    borderRadius: 'var(--radius-sm)',
                    cursor: 'pointer',
                    backgroundColor: selectedVariant?.id === v.id ? 'var(--color-bg-alt)' : '#FFFFFF'
                  }"
                >
                  <div class="flex items-center gap-3">
                    <input
                      type="radio"
                      name="variant"
                      :value="v"
                      v-model="selectedVariant"
                    />
                    <span
                      v-if="v.color_code"
                      :style="{ width: '18px', height: '18px', borderRadius: '50%', backgroundColor: v.color_code, border: '1px solid #CCC' }"
                    ></span>
                    <span style="font-weight: 600; font-size: 0.95rem;">{{ v.option }} ({{ v.color_name || v.name }})</span>
                  </div>
                  <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-secondary-dark);">
                    {{ Number(v.price_adjustment) > 0 ? `+RM ${Number(v.price_adjustment).toFixed(2)}` : 'Standard' }}
                  </span>
                </label>
              </div>
            </div>

            <!-- Quantity & Actions -->
            <div class="flex items-center gap-3" style="margin-bottom: 2rem;">
              <div class="flex items-center" style="border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 0.35rem 0.5rem;">
                <button
                  @click="quantity = Math.max(1, quantity - 1)"
                  style="background: transparent; border: none; font-size: 1.1rem; padding: 0.3rem 0.8rem; cursor: pointer;"
                >-</button>
                <span style="font-weight: 700; font-size: 1.1rem; padding: 0 0.8rem;">{{ quantity }}</span>
                <button
                  @click="quantity++"
                  style="background: transparent; border: none; font-size: 1.1rem; padding: 0.3rem 0.8rem; cursor: pointer;"
                >+</button>
              </div>

              <button
                @click="addToCart"
                class="btn btn-primary btn-lg flex-1"
              >
                <i class="fa-solid fa-bag-shopping"></i> Add to Bag
              </button>

              <button
                @click="wishlistStore.toggleWishlist(product)"
                class="btn btn-outline btn-lg"
                :style="{
                  borderColor: isWishlisted ? '#E11D48' : 'var(--color-border)',
                  color: isWishlisted ? '#E11D48' : 'var(--color-primary)',
                  backgroundColor: isWishlisted ? '#FFF1F2' : 'transparent',
                  padding: '0 1.25rem'
                }"
                :title="isWishlisted ? 'Remove from Wishlist' : 'Save to Wishlist'"
              >
                <i :class="isWishlisted ? 'fa-solid fa-heart' : 'fa-regular fa-heart'"></i>
              </button>
            </div>

            <!-- Free Swatch & Customization Banner CTA -->
            <div style="background-color: var(--color-secondary-light); border: 1px solid rgba(197, 168, 128, 0.4); border-radius: var(--radius-sm); padding: 1.25rem; margin-bottom: 2rem;">
              <div class="flex items-center justify-between" style="margin-bottom: 0.5rem;">
                <div class="flex items-center gap-2">
                  <i class="fa-solid fa-swatchbook" style="color: var(--color-secondary-dark); font-size: 1.2rem;"></i>
                  <h4 style="font-size: 1rem; margin-bottom: 0;">Complimentary Fabric Swatches</h4>
                </div>
                <span style="font-size: 0.75rem; background: #FFFFFF; color: var(--color-primary); padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 700;">FREE DELIVERY</span>
              </div>
              <p style="font-size: 0.875rem; margin-bottom: 0.85rem; color: var(--color-text-main);">
                Undecided on color or texture? We mail real fabric and velvet swatches directly to your doorstep anywhere in Peninsular Malaysia at zero cost.
              </p>
              <div class="flex gap-2">
                <button
                  @click="isSwatchModalOpen = true"
                  class="btn btn-outline btn-sm flex-1"
                  style="background: #FFFFFF; font-size: 0.85rem;"
                >
                  <i class="fa-solid fa-envelope-open-text"></i> Order Free Swatches
                </button>
                <a
                  :href="whatsappDetailUrl"
                  target="_blank"
                  class="btn btn-whatsapp btn-sm flex-1"
                  style="text-transform: none; font-size: 0.85rem;"
                >
                  <i class="fa-brands fa-whatsapp"></i> Chat with Specialist
                </a>
              </div>
            </div>

            <!-- Value Props List -->
            <div class="grid grid-cols-2 gap-3" style="font-size: 0.85rem; color: var(--color-text-muted); border-top: 1px solid var(--color-border); padding-top: 1.5rem;">
              <div class="flex items-center gap-2">
                <i class="fa-solid fa-tree" style="color: var(--color-secondary-dark);"></i> Solid Hardwood Chassis
              </div>
              <div class="flex items-center gap-2">
                <i class="fa-solid fa-shield" style="color: var(--color-secondary-dark);"></i> 5-Year Wood Warranty
              </div>
              <div class="flex items-center gap-2">
                <i class="fa-solid fa-truck" style="color: var(--color-secondary-dark);"></i> Free Klang Valley &gt;RM3k
              </div>
              <div class="flex items-center gap-2">
                <i class="fa-solid fa-certificate" style="color: var(--color-secondary-dark);"></i> 100% Made in Malaysia
              </div>
            </div>
          </div>
        </div>

        <!-- Craftsmanship & Specs Tabs -->
        <div style="border-top: 1px solid var(--color-border); padding-top: 3rem; margin-bottom: 4rem;">
          <h3 style="font-size: 1.6rem; margin-bottom: 1.5rem;">Craftsmanship & Specifications</h3>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem;">
            <div>
              <h4 style="font-size: 1.15rem; margin-bottom: 0.75rem;">Full Product Story</h4>
              <p style="line-height: 1.8; margin-bottom: 1rem;">
                {{ product.description }}
              </p>
              <p style="line-height: 1.8;">
                <strong>Materials Used:</strong> {{ product.materials || 'Kiln-dried Malaysian Meranti Hardwood, High-Density Latex Blend Foam, Pocket Spring Core, Premium Pet-Friendly Fabric.' }}
              </p>
            </div>

            <div style="background-color: var(--color-bg-alt); padding: 1.5rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
              <h4 style="font-size: 1.15rem; margin-bottom: 1rem;">Dimensions & Frame Specs</h4>
              <table style="width: 100%; font-size: 0.9rem;">
                <tbody>
                  <tr style="border-bottom: 1px solid var(--color-border);">
                    <td style="padding: 0.6rem 0; font-weight: 600;">Dimensions:</td>
                    <td style="padding: 0.6rem 0; color: var(--color-text-muted);">{{ product.dimensions || 'W 240cm x D 95cm x H 85cm' }}</td>
                  </tr>
                  <tr style="border-bottom: 1px solid var(--color-border);">
                    <td style="padding: 0.6rem 0; font-weight: 600;">Frame Material:</td>
                    <td style="padding: 0.6rem 0; color: var(--color-text-muted);">100% Solid Kiln-Dried Hardwood</td>
                  </tr>
                  <tr style="border-bottom: 1px solid var(--color-border);">
                    <td style="padding: 0.6rem 0; font-weight: 600;">Warranty Coverage:</td>
                    <td style="padding: 0.6rem 0; color: var(--color-secondary-dark); font-weight: 700;">5 Years (Structural Wood Frame)</td>
                  </tr>
                  <tr>
                    <td style="padding: 0.6rem 0; font-weight: 600;">Maintenance:</td>
                    <td style="padding: 0.6rem 0; color: var(--color-text-muted);">Wipe clean with damp microfiber cloth</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Customer Reviews Section -->
        <div id="reviews-section" style="border-top: 1px solid var(--color-border); padding-top: 3.5rem; margin-bottom: 4rem;">
          <div class="flex items-center justify-between flex-wrap gap-4" style="margin-bottom: 2rem;">
            <div>
              <span class="section-eyebrow" style="color: var(--color-secondary-dark); font-size: 0.8rem; font-weight: 700; letter-spacing: 0.1em;">AUTHENTIC HOMEOWNER EXPERIENCES</span>
              <h2 style="font-size: 1.85rem; margin-top: 0.25rem; margin-bottom: 0;">Verified Customer Reviews</h2>
            </div>
            <button
              @click="isReviewModalOpen = true"
              class="btn btn-primary"
            >
              <i class="fa-solid fa-pen-to-square"></i> Write a Review
            </button>
          </div>

          <!-- Rating Overview Card -->
          <div style="display: grid; grid-template-columns: 240px 1fr; gap: 3rem; background: var(--color-bg-alt); padding: 2rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border); margin-bottom: 2.5rem; align-items: center;">
            <div class="text-center" style="border-right: 1px solid var(--color-border); padding-right: 2rem;">
              <div style="font-size: 3.5rem; font-weight: 800; font-family: var(--font-heading); color: var(--color-primary); line-height: 1;">
                {{ reviewData.average_rating.toFixed(1) }}
              </div>
              <div style="color: #F59E0B; font-size: 1.25rem; margin: 0.4rem 0;">
                ★★★★★
              </div>
              <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 0;">
                Based on {{ reviewData.total_reviews }} verified orders
              </p>
            </div>

            <!-- Distribution Bars -->
            <div class="flex flex-col gap-2">
              <div
                v-for="stars in [5, 4, 3, 2, 1]"
                :key="stars"
                class="flex items-center gap-3"
                style="font-size: 0.85rem;"
              >
                <span style="width: 50px; font-weight: 600;">{{ stars }} Star</span>
                <div style="flex: 1; height: 8px; background: #E2E8F0; border-radius: 4px; overflow: hidden;">
                  <div
                    :style="{
                      width: `${reviewData.total_reviews > 0 ? ((reviewData.distribution[stars] || 0) / reviewData.total_reviews) * 100 : 0}%`,
                      height: '100%',
                      background: '#F59E0B'
                    }"
                  ></div>
                </div>
                <span style="width: 30px; text-align: right; color: var(--color-text-muted); font-size: 0.8rem;">
                  {{ reviewData.distribution[stars] || 0 }}
                </span>
              </div>
            </div>
          </div>

          <!-- Reviews List -->
          <div v-if="reviewData.reviews.length === 0" class="text-center py-12" style="background: #FAFAFA; border-radius: var(--radius-sm);">
            <i class="fa-regular fa-comment-dots" style="font-size: 2rem; color: var(--color-text-light); margin-bottom: 0.75rem;"></i>
            <p style="color: var(--color-text-muted); margin-bottom: 0;">Be the first verified homeowner to review this Malaysian handcrafted piece!</p>
          </div>

          <div v-else class="flex flex-col gap-4">
            <div
              v-for="rev in reviewData.reviews"
              :key="rev.id"
              style="padding: 1.5rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); background: #FFFFFF;"
            >
              <div class="flex items-center justify-between" style="margin-bottom: 0.5rem;">
                <div class="flex items-center gap-2.5">
                  <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--color-secondary-light); color: var(--color-primary); font-weight: 700; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                    {{ rev.customer_name.charAt(0).toUpperCase() }}
                  </div>
                  <div>
                    <div style="font-weight: 700; font-size: 0.95rem; display: flex; items-center; gap: 0.5rem;">
                      {{ rev.customer_name }}
                      <span v-if="rev.is_verified" style="background: #E8F5E9; color: #2E7D32; font-size: 0.7rem; font-weight: 700; padding: 0.15rem 0.45rem; border-radius: 4px; display: inline-flex; align-items: center; gap: 0.25rem;">
                        <i class="fa-solid fa-check"></i> Verified Buyer
                      </span>
                    </div>
                    <span style="font-size: 0.75rem; color: var(--color-text-light);">
                      {{ formatDate(rev.created_at) }}
                    </span>
                  </div>
                </div>

                <div style="color: #F59E0B; font-size: 0.95rem;">
                  <span v-for="s in 5" :key="s">
                    <i :class="s <= rev.rating ? 'fa-solid fa-star' : 'fa-regular fa-star'"></i>
                  </span>
                </div>
              </div>

              <h4 v-if="rev.title" style="font-size: 1rem; margin-bottom: 0.35rem; font-weight: 600;">
                {{ rev.title }}
              </h4>

              <p style="font-size: 0.9rem; color: var(--color-text-main); line-height: 1.6; margin-bottom: rev.image_url ? '1rem' : '0';">
                {{ rev.comment }}
              </p>

              <!-- Customer Photo if available -->
              <div v-if="rev.image_url" style="margin-top: 0.75rem;">
                <img
                  :src="rev.image_url"
                  alt="Customer setup"
                  style="max-width: 220px; max-height: 160px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--color-border);"
                />
              </div>
            </div>
          </div>
        </div>

        <!-- Related Products -->
        <div v-if="relatedProducts.length > 0" style="border-top: 1px solid var(--color-border); padding-top: 3.5rem;">
          <div class="section-title" style="text-align: left; margin-bottom: 2rem;">
            <span class="section-eyebrow">CURATED RECOMMENDATIONS</span>
            <h2>COMPLEMENTARY PIECES</h2>
          </div>

          <div class="grid grid-cols-4 gap-6">
            <ProductCard
              v-for="rel in relatedProducts"
              :key="rel.id"
              :product="rel"
            />
          </div>
        </div>
      </div>
    </div>

    <!-- Free Swatches & Customization Request Modal -->
    <div
      v-if="isSwatchModalOpen"
      class="modal-backdrop"
      @click.self="isSwatchModalOpen = false"
    >
      <div class="modal-content animate-slide-up" style="max-width: 520px;">
        <div class="flex items-center justify-between" style="margin-bottom: 1.25rem;">
          <div>
            <h3 style="font-size: 1.35rem; margin-bottom: 0.25rem;">Free Fabric Swatches & Bespoke Sizing</h3>
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 0;">
              Handcrafted in Bukit Jelutong, Selangor
            </p>
          </div>
          <button @click="isSwatchModalOpen = false" style="background: none; border: none; font-size: 1.25rem; cursor: pointer; color: var(--color-text-light);">&times;</button>
        </div>

        <form @submit.prevent="submitSwatchRequest" class="flex flex-col gap-3">
          <div>
            <label class="form-label" style="font-size: 0.85rem;">Your Name *</label>
            <input
              type="text"
              v-model="swatchForm.name"
              required
              class="form-control"
              placeholder="e.g. Datin Faridah"
            />
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="form-label" style="font-size: 0.85rem;">WhatsApp / Phone *</label>
              <input
                type="tel"
                v-model="swatchForm.phone"
                required
                class="form-control"
                placeholder="012-3456789"
              />
            </div>
            <div>
              <label class="form-label" style="font-size: 0.85rem;">Email (Optional)</label>
              <input
                type="email"
                v-model="swatchForm.email"
                class="form-control"
                placeholder="name@email.com"
              />
            </div>
          </div>

          <div>
            <label class="form-label" style="font-size: 0.85rem;">Request Type *</label>
            <select v-model="swatchForm.requestType" class="form-control" required>
              <option value="fabric_swatches">Complimentary Fabric & Velvet Swatches (Free Mail)</option>
              <option value="custom_sizing">Custom Length / L-Shape Dimensions Quote</option>
              <option value="high_density_foam">Firmness / High-Density Foam Consultation</option>
              <option value="interior_designer">Interior Designer / Trade Bulk Order</option>
            </select>
          </div>

          <div>
            <label class="form-label" style="font-size: 0.85rem;">Postal Delivery Address (For Swatches)</label>
            <textarea
              v-model="swatchForm.address"
              rows="2"
              class="form-control"
              placeholder="Enter your street address, postcode, and city..."
            ></textarea>
          </div>

          <div>
            <label class="form-label" style="font-size: 0.85rem;">Special Notes or Custom Dimensions</label>
            <textarea
              v-model="swatchForm.notes"
              rows="2"
              class="form-control"
              placeholder="e.g. Prefer neutral beige/bouclé colors, or need 280cm sofa length..."
            ></textarea>
          </div>

          <div class="flex gap-2" style="margin-top: 0.5rem;">
            <button
              type="submit"
              class="btn btn-primary flex-1"
              :disabled="isSubmittingSwatch"
            >
              <i v-if="isSubmittingSwatch" class="fa-solid fa-spinner fa-spin"></i>
              <span v-else><i class="fa-solid fa-paper-plane"></i> Submit Request</span>
            </button>
            <a
              :href="whatsappDetailUrl"
              target="_blank"
              class="btn btn-whatsapp"
              title="Fast response via WhatsApp"
            >
              <i class="fa-brands fa-whatsapp"></i> WhatsApp Direct
            </a>
          </div>
        </form>
      </div>
    </div>

    <!-- Write a Review Modal -->
    <div
      v-if="isReviewModalOpen"
      class="modal-backdrop"
      @click.self="isReviewModalOpen = false"
    >
      <div class="modal-content animate-slide-up" style="max-width: 500px;">
        <div class="flex items-center justify-between" style="margin-bottom: 1.25rem;">
          <div>
            <h3 style="font-size: 1.35rem; margin-bottom: 0.25rem;">Write a Customer Review</h3>
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 0;">
              Reviewing: {{ product?.name }}
            </p>
          </div>
          <button @click="isReviewModalOpen = false" style="background: none; border: none; font-size: 1.25rem; cursor: pointer; color: var(--color-text-light);">&times;</button>
        </div>

        <form @submit.prevent="submitReview" class="flex flex-col gap-3">
          <!-- Star Rating Selector -->
          <div>
            <label class="form-label" style="font-size: 0.85rem;">Overall Rating *</label>
            <div class="flex items-center gap-2" style="font-size: 1.75rem; color: #F59E0B; cursor: pointer;">
              <span
                v-for="star in 5"
                :key="star"
                @click="reviewForm.rating = star"
                @mouseenter="hoverRating = star"
                @mouseleave="hoverRating = 0"
              >
                <i :class="(hoverRating || reviewForm.rating) >= star ? 'fa-solid fa-star' : 'fa-regular fa-star'"></i>
              </span>
              <span style="font-size: 0.85rem; color: var(--color-text-muted); margin-left: 0.5rem; font-weight: 600;">
                {{ reviewForm.rating }} / 5 Stars
              </span>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="form-label" style="font-size: 0.85rem;">Your Name *</label>
              <input
                type="text"
                v-model="reviewForm.customer_name"
                required
                class="form-control"
                placeholder="e.g. Sarah Tan"
              />
            </div>
            <div>
              <label class="form-label" style="font-size: 0.85rem;">Email (Kept Private)</label>
              <input
                type="email"
                v-model="reviewForm.customer_email"
                class="form-control"
                placeholder="sarah@gmail.com"
              />
            </div>
          </div>

          <div>
            <label class="form-label" style="font-size: 0.85rem;">Headline / Summary</label>
            <input
              type="text"
              v-model="reviewForm.title"
              class="form-control"
              placeholder="e.g. Unbelievable comfort and high-end finish!"
            />
          </div>

          <div>
            <label class="form-label" style="font-size: 0.85rem;">Your Detailed Experience * (Min 10 chars)</label>
            <textarea
              v-model="reviewForm.comment"
              required
              rows="3"
              class="form-control"
              placeholder="Describe the fabric quality, wood frame stability, comfort, and delivery experience..."
            ></textarea>
          </div>

          <div>
            <label class="form-label" style="font-size: 0.85rem;">Customer Photo URL (Optional Living Room Shot)</label>
            <input
              type="url"
              v-model="reviewForm.image_url"
              class="form-control"
              placeholder="https://..."
            />
          </div>

          <button
            type="submit"
            class="btn btn-primary"
            style="margin-top: 0.5rem;"
            :disabled="isSubmittingReview"
          >
            <i v-if="isSubmittingReview" class="fa-solid fa-spinner fa-spin"></i>
            <span v-else><i class="fa-solid fa-check"></i> Publish Verified Review</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useCartStore } from '~/stores/cart'
import { useWishlistStore } from '~/stores/wishlist'
import { useToastStore } from '~/stores/toast'
import ProductCard from '~/components/ProductCard.vue'

const config = useRuntimeConfig()
const apiBase = config.public.apiBase
const whatsappDefault = config.public.whatsappDefault
const route = useRoute()
const cartStore = useCartStore()
const wishlistStore = useWishlistStore()
const toast = useToastStore()

const slug = route.params.slug as string
const product = ref<any>(null)
const relatedProducts = ref<any[]>([])
const loading = ref(true)
const activeImage = ref('')
const selectedVariant = ref<any>(null)
const quantity = ref(1)

// Wishlist Status
const isWishlisted = computed(() => {
  return product.value ? wishlistStore.isInWishlist(product.value.id) : false
})

// Reviews State
interface ReviewData {
  average_rating: number
  total_reviews: number
  distribution: Record<number, number>
  reviews: any[]
}

const reviewData = ref<ReviewData>({
  average_rating: 5.0,
  total_reviews: 0,
  distribution: { 5: 0, 4: 0, 3: 0, 2: 0, 1: 0 },
  reviews: []
})

// Modals
const isSwatchModalOpen = ref(false)
const isReviewModalOpen = ref(false)
const isSubmittingSwatch = ref(false)
const isSubmittingReview = ref(false)
const hoverRating = ref(0)

const swatchForm = reactive({
  name: '',
  phone: '',
  email: '',
  requestType: 'fabric_swatches',
  address: '',
  notes: ''
})

const reviewForm = reactive({
  customer_name: '',
  customer_email: '',
  rating: 5,
  title: '',
  comment: '',
  image_url: ''
})

async function loadProduct() {
  loading.value = true
  try {
    const res: any = await $fetch(`${apiBase}/products/${slug}`)
    product.value = res.product
    relatedProducts.value = res.related_products || []

    if (product.value.images && product.value.images.length > 0) {
      activeImage.value = product.value.images[0].image_url
    } else {
      activeImage.value = 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1200&q=80'
    }

    if (product.value.variants && product.value.variants.length > 0) {
      selectedVariant.value = product.value.variants[0]
    }

    // Load reviews
    await loadReviews(product.value.id)

    // Inject Google Rich Snippet JSON-LD & Dispatch GA4 View Item
    const { injectProductSchema } = useSchemaOrg()
    injectProductSchema(product.value, reviewData.value)

    const { $analytics } = useNuxtApp()
    if ($analytics) {
      $analytics.trackViewItem(product.value)
    }
  } catch (e) {
    console.error('Failed to load product', e)
  } finally {
    loading.value = false
  }
}

async function loadReviews(productId: number) {
  try {
    const res: any = await $fetch(`${apiBase}/products/${productId}/reviews`)
    reviewData.value = res
  } catch (e) {
    console.error('Failed to load reviews', e)
  }
}

function calculateTotalPrice() {
  if (!product.value) return 0
  const base = Number(product.value.discount_price ?? product.value.price)
  const variantAdj = selectedVariant.value ? Number(selectedVariant.value.price_adjustment || 0) : 0
  return base + variantAdj
}

function addToCart() {
  const finalProduct = {
    ...product.value,
    discount_price: calculateTotalPrice(),
    price: calculateTotalPrice(),
  }
  const variantLabel = selectedVariant.value ? selectedVariant.value.option : undefined
  cartStore.addItem(finalProduct, variantLabel, quantity.value)

  const { $analytics } = useNuxtApp()
  if ($analytics) {
    $analytics.trackAddToCart(finalProduct, variantLabel, quantity.value)
  }
}


async function submitSwatchRequest() {
  isSubmittingSwatch.value = true
  try {
    const msg = `Request Type: ${swatchForm.requestType}\nAddress: ${swatchForm.address}\nNotes: ${swatchForm.notes}`
    await $fetch(`${apiBase}/inquiries`, {
      method: 'POST',
      body: {
        name: swatchForm.name,
        phone: swatchForm.phone,
        email: swatchForm.email || undefined,
        subject: `Fabric Swatches / Sizing: ${product.value?.name}`,
        message: msg,
        product_id: product.value?.id
      }
    })

    toast.show('Request received! Our factory specialist will dispatch your swatches promptly.', 'success')
    isSwatchModalOpen.value = false
    swatchForm.address = ''
    swatchForm.notes = ''
  } catch (e: any) {
    toast.show(e.data?.message || 'Failed to submit swatch request. Please WhatsApp us directly.', 'error')
  } finally {
    isSubmittingSwatch.value = false
  }
}

async function submitReview() {
  if (!product.value) return
  isSubmittingReview.value = true
  try {
    await $fetch(`${apiBase}/products/${product.value.id}/reviews`, {
      method: 'POST',
      body: {
        customer_name: reviewForm.customer_name,
        customer_email: reviewForm.customer_email || undefined,
        rating: reviewForm.rating,
        title: reviewForm.title || undefined,
        comment: reviewForm.comment,
        image_url: reviewForm.image_url || undefined,
      }
    })

    toast.show('Thank you! Your verified review has been published.', 'success')
    isReviewModalOpen.value = false
    reviewForm.comment = ''
    reviewForm.title = ''
    reviewForm.image_url = ''
    await loadReviews(product.value.id)
  } catch (e: any) {
    toast.show(e.data?.message || 'Failed to publish review. Check all fields.', 'error')
  } finally {
    isSubmittingReview.value = false
  }
}

function formatDate(dateStr: string) {
  if (!dateStr) return ''
  const d = new Date(dateStr)
  return d.toLocaleDateString('en-MY', { day: 'numeric', month: 'short', year: 'numeric' })
}

const whatsappDetailUrl = computed(() => {
  if (!product.value) return ''
  const variantText = selectedVariant.value ? ` (${selectedVariant.value.option})` : ''
  const text = `Hello NMFFurniture, I would like to inquire about customizing the ${product.value.name}${variantText} SKU: ${product.value.sku} priced at RM ${calculateTotalPrice().toFixed(2)}.`
  return `https://wa.me/${whatsappDefault}?text=${encodeURIComponent(text)}`
})

onMounted(() => {
  loadProduct()
})
</script>

<style scoped>
.detail-wishlist-btn {
  position: absolute;
  top: 16px;
  right: 16px;
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(6px);
  border: 1px solid rgba(0, 0, 0, 0.08);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #64748B;
  font-size: 1.2rem;
  transition: all 0.2s ease;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.detail-wishlist-btn:hover {
  background: #FFFFFF;
  color: #E11D48;
  transform: scale(1.08);
}

.detail-wishlist-btn.active {
  color: #E11D48;
  background: #FFF1F2;
  border-color: #FECDD3;
}

.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(4px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 1rem;
}

.modal-content {
  background: #FFFFFF;
  border-radius: var(--radius-sm);
  padding: 2rem;
  width: 100%;
  box-shadow: var(--shadow-lg);
  max-height: 90vh;
  overflow-y: auto;
}

@media (max-width: 900px) {
  div[style*="grid-template-columns: 1.1fr 0.9fr"] {
    grid-template-columns: 1fr !important;
    gap: 2rem !important;
  }
  div[style*="grid-template-columns: 1fr 1fr"] {
    grid-template-columns: 1fr !important;
    gap: 2rem !important;
  }
  div[style*="grid-template-columns: 240px 1fr"] {
    grid-template-columns: 1fr !important;
    gap: 1.5rem !important;
  }
}
</style>


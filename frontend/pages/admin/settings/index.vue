<template>
  <div>
    <!-- Page Header & Action Bar -->
    <div class="flex items-center justify-between flex-wrap gap-4 mb-8">
      <div>
        <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--color-primary); letter-spacing: -0.01em;">
          Website Settings & CMS Control Hub
        </h1>
        <p style="color: var(--color-text-muted); font-size: 0.9rem; margin-top: 0.25rem;">
          Dynamically configure site branding, header announcement, navigation menus, footer concierge, home sections, and tracking scripts.
        </p>
      </div>

      <div class="flex items-center gap-3">
        <NuxtLink to="/" target="_blank" class="btn btn-outline btn-sm">
          <i class="fa-solid fa-arrow-up-right-from-square"></i> Preview Live Store
        </NuxtLink>
        <button
          @click="saveAllSettings"
          class="btn btn-secondary btn-sm"
          :disabled="isSaving"
        >
          <i v-if="isSaving" class="fa-solid fa-circle-notch fa-spin"></i>
          <i v-else class="fa-solid fa-floppy-disk"></i>
          {{ isSaving ? 'Saving Changes...' : 'Save All Settings' }}
        </button>
      </div>
    </div>

    <!-- Feedback Banner -->
    <div v-if="saveSuccessMessage" class="admin-alert success animate-fade-in mb-6">
      <i class="fa-solid fa-circle-check"></i>
      <span>{{ saveSuccessMessage }}</span>
    </div>

    <!-- Tabs Header Navigation -->
    <div class="settings-tabs-header">
      <button
        v-for="t in tabs"
        :key="t.id"
        type="button"
        class="settings-tab-btn"
        :class="{ active: currentTab === t.id }"
        @click="currentTab = t.id"
      >
        <i :class="t.icon"></i>
        <span>{{ t.label }}</span>
      </button>
    </div>

    <!-- Tab 1: General & Branding -->
    <div v-show="currentTab === 'general'" class="settings-card animate-fade-in">
      <h3 class="settings-section-title">
        <i class="fa-solid fa-building"></i> General & Company Branding
      </h3>
      <p class="settings-section-desc">Manage official Malaysian company identity, registration numbers, and headquarters information.</p>

      <div class="grid grid-cols-2 gap-6 mt-6">
        <div class="form-group">
          <label class="form-label">Brand / Site Name</label>
          <input v-model="formSettings.site_name" type="text" class="form-input" placeholder="NMF Furniture" />
          <span class="form-hint">Global brand name used across the store and in browser titles</span>
        </div>

        <div class="form-group">
          <label class="form-label">Brand Tagline</label>
          <input v-model="formSettings.site_tagline" type="text" class="form-input" placeholder="Bespoke Handcrafted Living Furniture Malaysia" />
          <span class="form-hint">Marketing tagline used in footer, hero section, and SEO metadata</span>
        </div>

        <div class="form-group">
          <label class="form-label">Navbar Brand Primary Text</label>
          <input v-model="formSettings.brand_name_primary" type="text" class="form-input" placeholder="NMF" />
          <span class="form-hint">First word of text logo in top navbar</span>
        </div>

        <div class="form-group">
          <label class="form-label">Navbar Brand Accent Text</label>
          <input v-model="formSettings.brand_name_accent" type="text" class="form-input" placeholder="FURNITURE" />
          <span class="form-hint">Highlighted serif word of text logo in top navbar</span>
        </div>

        <div class="form-group">
          <label class="form-label">Navbar Brand Badge</label>
          <input v-model="formSettings.brand_badge" type="text" class="form-input" placeholder="SDN BHD" />
          <span class="form-hint">Small corporate pill badge (e.g. SDN BHD, MALAYSIA, or leave empty)</span>
        </div>

        <div class="form-group">
          <label class="form-label">Official SSM Registration No.</label>
          <input v-model="formSettings.ssm_number" type="text" class="form-input" placeholder="SSM 1400875-P" />
          <span class="form-hint">Official Malaysian government registration seal (shown in footer & trust bar)</span>
        </div>

        <div class="form-group">
          <label class="form-label">Primary Support Email</label>
          <input v-model="formSettings.support_email" type="email" class="form-input" placeholder="concierge@nmffurniture.com" />
        </div>

        <div class="form-group">
          <label class="form-label">Showroom Telephone Hotline</label>
          <input v-model="formSettings.support_phone" type="text" class="form-input" placeholder="+603 7832 9920" />
        </div>

        <div class="form-group">
          <label class="form-label">WhatsApp Contact Number (No '+' or spaces)</label>
          <input v-model="formSettings.support_whatsapp" type="text" class="form-input" placeholder="60192589920" />
          <span class="form-hint">E.g. 60192589920 for Malaysian WhatsApp click-to-chat</span>
        </div>

        <div class="form-group" style="grid-column: 1 / -1;">
          <label class="form-label">Factory & Joinery Facility Address</label>
          <textarea v-model="formSettings.factory_address" rows="3" class="form-input" placeholder="UG41 Komersial Radia, Bukit Jelutong, 40150 Shah Alam, Selangor"></textarea>
        </div>
      </div>
    </div>

    <!-- Tab 2: Header & Announcement Bar -->
    <div v-show="currentTab === 'header'" class="settings-card animate-fade-in">
      <h3 class="settings-section-title">
        <i class="fa-solid fa-bullhorn"></i> Header & Announcement Bar
      </h3>
      <p class="settings-section-desc">Control top promotion marquee, announcement visibility, and sticky header behavior.</p>

      <div class="settings-toggle-row mt-6">
        <div>
          <strong>Enable Top Announcement Bar</strong>
          <p style="font-size: 0.825rem; color: var(--color-text-muted); margin: 0;">Show promotional notification band at the very top of all pages.</p>
        </div>
        <label class="switch">
          <input v-model="formSettings.announcement_enabled" type="checkbox" :true-value="'1'" :false-value="'0'" />
          <span class="slider"></span>
        </label>
      </div>

      <div class="grid grid-cols-2 gap-6 mt-6">
        <div class="form-group">
          <label class="form-label">Announcement Statement 1</label>
          <input v-model="formSettings.announcement_text_1" type="text" class="form-input" placeholder="5-Year Solid Wood Warranty on All Sofas" />
        </div>

        <div class="form-group">
          <label class="form-label">Announcement Statement 2</label>
          <input v-model="formSettings.announcement_text_2" type="text" class="form-input" placeholder="In-House Malaysian Manufacturer" />
        </div>

        <div class="form-group">
          <label class="form-label">Action Link Label</label>
          <input v-model="formSettings.announcement_link_text" type="text" class="form-input" placeholder="Visit Showrooms in Shah Alam & PJ →" />
        </div>

        <div class="form-group">
          <label class="form-label">Action Link URL</label>
          <input v-model="formSettings.announcement_link_url" type="text" class="form-input" placeholder="/our-showroom" />
        </div>
      </div>

      <div class="settings-toggle-row mt-6">
        <div>
          <strong>Sticky Navigation Bar</strong>
          <p style="font-size: 0.825rem; color: var(--color-text-muted); margin: 0;">Header stays fixed at top of screen during scroll with blur backdrop.</p>
        </div>
        <label class="switch">
          <input v-model="formSettings.header_sticky" type="checkbox" :true-value="'1'" :false-value="'0'" />
          <span class="slider"></span>
        </label>
      </div>
    </div>

    <!-- Tab 3: Navigation Menus Manager (Header & Footer Drag/Sort) -->
    <div v-show="currentTab === 'menus'" class="settings-card animate-fade-in">
      <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
          <h3 class="settings-section-title" style="margin-bottom: 0.25rem;">
            <i class="fa-solid fa-compass"></i> Navigation Menus Manager
          </h3>
          <p class="settings-section-desc">Create, reorder, and configure links for Header Navigation and Footer Columns.</p>
        </div>

        <!-- Menu Selector Switcher -->
        <div class="flex items-center gap-2">
          <select v-model="selectedMenuLocation" class="form-input" style="padding: 0.45rem 1rem; font-size: 0.85rem; font-weight: 600;">
            <option value="header_main">Header Main Navigation</option>
            <option value="footer_col_1">Footer Col 1: Collections</option>
            <option value="footer_col_2">Footer Col 2: Experience</option>
          </select>
          <button @click="openAddMenuItemModal(null)" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-plus"></i> Add Link
          </button>
        </div>
      </div>

      <!-- Active Menu Items List -->
      <div class="menu-items-table-wrap mt-6">
        <div v-if="activeMenuItems.length === 0" class="text-center py-12 text-muted">
          <i class="fa-solid fa-folder-open" style="font-size: 2rem; color: var(--color-border); margin-bottom: 0.5rem;"></i>
          <p>No items found in this menu. Click "Add Link" above to create one.</p>
        </div>

        <div v-else class="menu-items-list">
          <div
            v-for="(item, index) in activeMenuItems"
            :key="item.id"
            class="menu-item-row"
          >
            <!-- Sort Handles / Controls -->
            <div class="menu-item-order-controls">
              <button
                @click="moveMenuItem(index, -1)"
                :disabled="index === 0"
                class="menu-sort-btn"
                title="Move item up"
              >
                <i class="fa-solid fa-chevron-up"></i>
              </button>
              <span class="menu-item-index">{{ index + 1 }}</span>
              <button
                @click="moveMenuItem(index, 1)"
                :disabled="index === activeMenuItems.length - 1"
                class="menu-sort-btn"
                title="Move item down"
              >
                <i class="fa-solid fa-chevron-down"></i>
              </button>
            </div>

            <!-- Item Details -->
            <div class="menu-item-content">
              <div class="flex items-center gap-2">
                <i v-if="item.icon" :class="item.icon" style="color: var(--color-secondary-dark); width: 16px;"></i>
                <strong style="color: var(--color-primary); font-size: 0.95rem;">{{ item.title }}</strong>
                <span v-if="item.badge" class="brand-badge">{{ item.badge }}</span>
                <span v-if="item.target === '_blank'" class="badge-wood-warranty" style="font-size: 0.65rem; padding: 0.1rem 0.4rem;">
                  <i class="fa-solid fa-arrow-up-right-from-square"></i> New Tab
                </span>
              </div>
              <div style="font-size: 0.8rem; color: var(--color-text-muted); margin-top: 0.15rem;">
                Path: <code style="background: #F1F3F5; padding: 0.1rem 0.35rem; border-radius: 3px;">{{ item.url }}</code>
              </div>

              <!-- Nested Submenu Preview (if any) -->
              <div v-if="item.children && item.children.length > 0" class="nested-menu-preview mt-3">
                <div class="flex items-center justify-between mb-2">
                  <span style="font-size: 0.72rem; font-weight: 700; color: var(--color-secondary-dark); text-transform: uppercase; letter-spacing: 0.05em;">
                    Dropdown Sub-Items ({{ item.children.length }})
                  </span>
                  <button @click="openAddMenuItemModal(item.id)" class="text-xs" style="color: var(--color-primary); font-weight: 600; cursor: pointer; background: none; border: none;">
                    + Add Sub-Item
                  </button>
                </div>
                <div class="nested-items-grid">
                  <div v-for="sub in item.children" :key="sub.id" class="nested-item-pill">
                    <i v-if="sub.icon" :class="sub.icon"></i>
                    <span>{{ sub.title }}</span>
                    <button @click="deleteMenuItem(sub.id)" class="delete-sub-btn" title="Delete sub-item">
                      <i class="fa-solid fa-xmark"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Item Action Buttons -->
            <div class="menu-item-actions">
              <button
                v-if="selectedMenuLocation === 'header_main' && !item.parent_id"
                @click="openAddMenuItemModal(item.id)"
                class="action-icon-btn"
                title="Add Dropdown Child Item"
              >
                <i class="fa-solid fa-diagram-nested"></i>
              </button>
              <button
                @click="openEditMenuItemModal(item)"
                class="action-icon-btn"
                title="Edit item"
              >
                <i class="fa-solid fa-pen-to-square"></i>
              </button>
              <button
                @click="deleteMenuItem(item.id)"
                class="action-icon-btn danger"
                title="Delete item"
              >
                <i class="fa-solid fa-trash"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tab 4: Footer & Showroom Concierge -->
    <div v-show="currentTab === 'footer'" class="settings-card animate-fade-in">
      <h3 class="settings-section-title">
        <i class="fa-solid fa-landmark"></i> Footer & Showroom Concierge
      </h3>
      <p class="settings-section-desc">Customize footer descriptions, showroom information, operating hours, and copyright statement.</p>

      <div class="grid grid-cols-2 gap-6 mt-6">
        <div class="form-group" style="grid-column: 1 / -1;">
          <label class="form-label">Company About Overview (Column 1)</label>
          <textarea v-model="formSettings.footer_about_text" rows="3" class="form-input"></textarea>
          <span class="form-hint">Displayed under brand logo in the first column of footer</span>
        </div>

        <div class="form-group">
          <label class="form-label">Showroom Title (Column 4)</label>
          <input v-model="formSettings.footer_showroom_title" type="text" class="form-input" placeholder="Shah Alam Showroom" />
        </div>

        <div class="form-group">
          <label class="form-label">Showroom Operating Hours Badge</label>
          <input v-model="formSettings.footer_showroom_hours" type="text" class="form-input" placeholder="Tue – Sun: 10:30 AM – 7:30 PM" />
        </div>

        <div class="form-group" style="grid-column: 1 / -1;">
          <label class="form-label">Showroom Full Address</label>
          <textarea v-model="formSettings.footer_showroom_address" rows="2" class="form-input"></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Footer WhatsApp Button Call to Action</label>
          <input v-model="formSettings.footer_whatsapp_btn_text" type="text" class="form-input" placeholder="Chat with Showroom Concierge" />
        </div>

        <div class="form-group">
          <label class="form-label">Footer Bottom Copyright Text</label>
          <input v-model="formSettings.footer_copyright_text" type="text" class="form-input" placeholder="NMFFURNITURE SDN. BHD. All rights reserved." />
        </div>

        <div class="form-group" style="grid-column: 1 / -1;">
          <label class="form-label">Footer Guarantee Line</label>
          <input v-model="formSettings.footer_guarantee_badge" type="text" class="form-input" placeholder="Handcrafted Luxury Sofas • Solid Wood Frame 5-Year Warranty • Made in Malaysia" />
        </div>
      </div>
    </div>

    <!-- Tab 5: Dynamic Homepage Sections -->
    <div v-show="currentTab === 'homepage'" class="settings-card animate-fade-in">
      <h3 class="settings-section-title">
        <i class="fa-solid fa-house-chimney"></i> Dynamic Homepage Sections
      </h3>
      <p class="settings-section-desc">Manage hero copy, imagery, and call to action buttons without editing code.</p>

      <div class="grid grid-cols-2 gap-6 mt-6">
        <div class="form-group" style="grid-column: 1 / -1;">
          <label class="form-label">Hero Eyebrow Subtitle</label>
          <input v-model="formSettings.hero_eyebrow" type="text" class="form-input" />
        </div>

        <div class="form-group" style="grid-column: 1 / -1;">
          <label class="form-label">Hero Main Title (H1)</label>
          <input v-model="formSettings.hero_title" type="text" class="form-input" />
        </div>

        <div class="form-group" style="grid-column: 1 / -1;">
          <label class="form-label">Hero Description Paragraph</label>
          <textarea v-model="formSettings.hero_subtitle" rows="3" class="form-input"></textarea>
        </div>

        <div class="form-group" style="grid-column: 1 / -1;">
          <label class="form-label">Hero High-Resolution Background Image URL</label>
          <input v-model="formSettings.hero_image_url" type="text" class="form-input" />
        </div>

        <div class="form-group">
          <label class="form-label">Primary Button Label</label>
          <input v-model="formSettings.hero_btn_primary_text" type="text" class="form-input" placeholder="Explore Collections" />
        </div>

        <div class="form-group">
          <label class="form-label">Primary Button Destination URL</label>
          <input v-model="formSettings.hero_btn_primary_link" type="text" class="form-input" placeholder="/shop" />
        </div>

        <div class="form-group">
          <label class="form-label">Secondary Button Label</label>
          <input v-model="formSettings.hero_btn_secondary_text" type="text" class="form-input" placeholder="Visit Our Showrooms" />
        </div>

        <div class="form-group">
          <label class="form-label">Secondary Button Destination URL</label>
          <input v-model="formSettings.hero_btn_secondary_link" type="text" class="form-input" placeholder="/our-showroom" />
        </div>
      </div>
    </div>

    <!-- Tab 6: Hero Carousel & Sliders Manager -->
    <div v-show="currentTab === 'carousel'" class="settings-card animate-fade-in">
      <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
        <div>
          <h3 class="settings-section-title" style="margin-bottom: 0.25rem;">
            <i class="fa-solid fa-images"></i> Hero Carousel & Dynamic Sliders
          </h3>
          <p class="settings-section-desc">
            Configure homepage hero slider, image/video backgrounds, client hunting badges, and single/double action buttons.
          </p>
        </div>

        <div class="flex items-center gap-2">
          <button @click="resetDefaultSlides" class="btn btn-outline btn-sm" title="Reset slides to official luxury Malaysian furniture defaults">
            <i class="fa-solid fa-arrow-rotate-left"></i> Reset Defaults
          </button>
          <button @click="openAddSlideModal" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-plus"></i> Add New Slide
          </button>
        </div>
      </div>

      <!-- Global Carousel Configuration Grid -->
      <div class="carousel-config-box mb-8">
        <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--color-primary); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
          <i class="fa-solid fa-sliders" style="color: var(--color-brand-gold);"></i> Global Carousel Settings
        </h4>

        <div class="grid grid-cols-2 gap-6">
          <div class="settings-toggle-row">
            <div>
              <strong>Enable Carousel Slider</strong>
              <p style="font-size: 0.8rem; color: var(--color-text-muted); margin: 0;">Toggle between dynamic rotating carousel or single static hero image.</p>
            </div>
            <label class="switch">
              <input v-model="formSettings.hero_carousel_enabled" type="checkbox" :true-value="'1'" :false-value="'0'" />
              <span class="slider"></span>
            </label>
          </div>

          <div class="settings-toggle-row">
            <div>
              <strong>Autoplay Rotating Slides</strong>
              <p style="font-size: 0.8rem; color: var(--color-text-muted); margin: 0;">Automatically rotate through slides with progress timer.</p>
            </div>
            <label class="switch">
              <input v-model="formSettings.hero_carousel_autoplay" type="checkbox" :true-value="'1'" :false-value="'0'" />
              <span class="slider"></span>
            </label>
          </div>

          <div class="form-group">
            <label class="form-label">Autoplay Slide Interval (Milliseconds)</label>
            <input v-model.number="formSettings.hero_carousel_interval" type="number" step="1000" min="2000" max="15000" class="form-input" placeholder="6000" />
            <span class="form-hint">Standard recommendation: 6000ms (6 seconds) for optimal reading time</span>
          </div>

          <div class="form-group">
            <label class="form-label">Standard Height Mode</label>
            <select v-model="formSettings.hero_carousel_height" class="form-input">
              <option value="standard">Standard Luxury (Recommended · 640px – 820px)</option>
              <option value="compact">Compact Height (540px – 600px)</option>
              <option value="immersive">Immersive Hero (85vh – 90vh)</option>
            </select>
            <span class="form-hint">Controls responsive viewport framing across mobile and desktop</span>
          </div>

          <div class="form-group">
            <label class="form-label">Slide Transition Effect</label>
            <select v-model="formSettings.hero_carousel_transition" class="form-input">
              <option value="fade">Smooth Cross-Fade (Luxury Elegance)</option>
              <option value="slide">Horizontal Slide (Dynamic Momentum)</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Slides List Management -->
      <div class="slides-management-header flex items-center justify-between mb-4">
        <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--color-primary); margin: 0; display: flex; align-items: center; gap: 0.5rem;">
          <i class="fa-solid fa-layer-group" style="color: var(--color-brand-gold);"></i>
          Active Slides ({{ carouselSlidesList.length }})
        </h4>
        <span style="font-size: 0.8rem; color: var(--color-text-muted);">
          Reorder with arrow buttons. Changes are saved with "Save All Settings".
        </span>
      </div>

      <div v-if="carouselSlidesList.length === 0" class="text-center py-12 text-muted" style="background: #FAF8F5; border: 1px dashed var(--color-border); border-radius: var(--radius-sm);">
        <i class="fa-solid fa-photo-film" style="font-size: 2.2rem; color: var(--color-border); margin-bottom: 0.5rem;"></i>
        <p style="font-size: 0.95rem;">No carousel slides defined yet.</p>
        <button @click="resetDefaultSlides" class="btn btn-secondary btn-sm mt-2">
          Load Realistic Luxury Slides
        </button>
      </div>

      <div v-else class="slides-cards-list">
        <div
          v-for="(slide, sIdx) in carouselSlidesList"
          :key="slide.id || sIdx"
          class="slide-row-card"
          :class="{ 'is-inactive': slide.is_active === false }"
        >
          <!-- Order Controls -->
          <div class="slide-order-controls">
            <button
              @click="moveSlide(sIdx, -1)"
              :disabled="sIdx === 0"
              class="menu-sort-btn"
              title="Move slide up"
            >
              <i class="fa-solid fa-chevron-up"></i>
            </button>
            <span class="slide-index-pill">{{ sIdx + 1 }}</span>
            <button
              @click="moveSlide(sIdx, 1)"
              :disabled="sIdx === carouselSlidesList.length - 1"
              class="menu-sort-btn"
              title="Move slide down"
            >
              <i class="fa-solid fa-chevron-down"></i>
            </button>
          </div>

          <!-- Slide Media Preview Thumbnail -->
          <div class="slide-media-preview">
            <img
              :src="slide.media_type === 'video' ? (slide.video_poster || slide.media_url) : slide.media_url"
              :alt="slide.title"
              class="slide-thumb-img"
              @error="(e: any) => e.target.src = 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=150&q=80'"
            />
            <span v-if="slide.media_type === 'video'" class="slide-media-tag video">
              <i class="fa-solid fa-video"></i> VIDEO
            </span>
            <span v-else class="slide-media-tag image">
              <i class="fa-regular fa-image"></i> IMAGE
            </span>
          </div>

          <!-- Slide Content Summary -->
          <div class="slide-info-body">
            <div class="flex items-center gap-2 flex-wrap mb-1">
              <span v-if="slide.eyebrow" class="slide-eyebrow-badge">{{ slide.eyebrow }}</span>
              <span v-if="slide.badge_text" class="slide-trust-badge">
                <i class="fa-solid fa-crown"></i> {{ slide.badge_text }}
              </span>
              <span class="slide-btn-mode-badge">
                {{ slide.button_mode === 'double' ? 'Double Button' : 'Single Button' }}
              </span>
              <span v-if="slide.is_active === false" class="badge-draft">Draft (Hidden)</span>
            </div>

            <h4 class="slide-card-title">{{ slide.title }}</h4>
            <p class="slide-card-subtitle">{{ slide.subtitle }}</p>

            <div class="slide-card-buttons-preview flex items-center gap-2 mt-2">
              <span class="btn-preview-tag primary">
                <i class="fa-solid fa-circle" style="font-size: 0.45rem;"></i> {{ slide.btn_primary_text || 'Primary Button' }}
                <code style="font-size: 0.7rem; opacity: 0.8; margin-left: 0.25rem;">{{ slide.btn_primary_link }}</code>
              </span>
              <span v-if="slide.button_mode === 'double' && slide.btn_secondary_text" class="btn-preview-tag secondary">
                <i class="fa-solid fa-circle" style="font-size: 0.45rem;"></i> {{ slide.btn_secondary_text }}
                <code style="font-size: 0.7rem; opacity: 0.8; margin-left: 0.25rem;">{{ slide.btn_secondary_link }}</code>
              </span>
            </div>
          </div>

          <!-- Slide Actions -->
          <div class="slide-actions-cell">
            <button
              @click="toggleSlideActive(sIdx)"
              class="action-icon-btn"
              :class="{ active: slide.is_active !== false }"
              :title="slide.is_active === false ? 'Activate Slide' : 'Hide Slide (Draft)'"
            >
              <i :class="slide.is_active === false ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"></i>
            </button>
            <button
              @click="openEditSlideModal(sIdx)"
              class="action-icon-btn"
              title="Edit Slide Details"
            >
              <i class="fa-solid fa-pen-to-square"></i>
            </button>
            <button
              @click="deleteSlide(sIdx)"
              class="action-icon-btn danger"
              title="Delete Slide"
            >
              <i class="fa-solid fa-trash"></i>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Tab 6: SEO, Marketing & Tracking Scripts -->
    <div v-show="currentTab === 'seo'" class="settings-card animate-fade-in">
      <h3 class="settings-section-title">
        <i class="fa-solid fa-chart-pie"></i> SEO, Marketing & Tracking Scripts
      </h3>
      <p class="settings-section-desc">Configure Google Analytics 4, Meta Pixel, and global search engine metadata.</p>

      <div class="grid grid-cols-2 gap-6 mt-6">
        <div class="form-group" style="grid-column: 1 / -1;">
          <label class="form-label">Default SEO Meta Title</label>
          <input v-model="formSettings.seo_meta_title" type="text" class="form-input" />
        </div>

        <div class="form-group" style="grid-column: 1 / -1;">
          <label class="form-label">Default SEO Meta Description</label>
          <textarea v-model="formSettings.seo_meta_description" rows="3" class="form-input"></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Google Analytics 4 (GA4) ID</label>
          <input v-model="formSettings.seo_ga4_id" type="text" class="form-input" placeholder="G-XXXXXXXXXX" />
          <span class="form-hint">E.g. G-NMFFURNITURE for live e-commerce event dispatching</span>
        </div>

        <div class="form-group">
          <label class="form-label">Meta / Facebook Pixel ID</label>
          <input v-model="formSettings.seo_pixel_id" type="text" class="form-input" placeholder="Numeric Pixel ID" />
          <span class="form-hint">For Instagram & Facebook ad conversion tracking</span>
        </div>
      </div>
    </div>

    <!-- Modal Dialog: Add / Edit Menu Item -->
    <div v-if="isMenuItemModalOpen" class="modal-backdrop" @click.self="isMenuItemModalOpen = false">
      <div class="modal-box animate-slide-up">
        <div class="modal-header">
          <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--color-primary); margin: 0;">
            {{ editingMenuItemId ? 'Edit Menu Item' : 'Add New Menu Item' }}
          </h3>
          <button @click="isMenuItemModalOpen = false" class="modal-close-btn">
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>

        <div class="modal-body">
          <div class="form-group mb-4">
            <label class="form-label">Link Title / Label *</label>
            <input v-model="menuItemForm.title" type="text" class="form-input" placeholder="e.g. Custom Sofas" required />
          </div>

          <div class="form-group mb-4">
            <label class="form-label">Destination URL or Route *</label>
            <input v-model="menuItemForm.url" type="text" class="form-input" placeholder="e.g. /shop?category=sofa" required />
          </div>

          <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="form-group">
              <label class="form-label">Icon (FontAwesome Class)</label>
              <input v-model="menuItemForm.icon" type="text" class="form-input" placeholder="fa-solid fa-couch" />
            </div>

            <div class="form-group">
              <label class="form-label">Highlight Badge (Optional)</label>
              <input v-model="menuItemForm.badge" type="text" class="form-input" placeholder="e.g. Popular, Sale, New" />
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="form-group">
              <label class="form-label">Open Link In</label>
              <select v-model="menuItemForm.target" class="form-input">
                <option value="_self">Same Tab (_self)</option>
                <option value="_blank">New Tab (_blank)</option>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label">Active Status</label>
              <select v-model="menuItemForm.is_active" class="form-input">
                <option :value="true">Active (Visible)</option>
                <option :value="false">Draft (Hidden)</option>
              </select>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button @click="isMenuItemModalOpen = false" class="btn btn-outline btn-sm">Cancel</button>
          <button @click="saveMenuItem" class="btn btn-secondary btn-sm" :disabled="isSavingMenuItem">
            <i v-if="isSavingMenuItem" class="fa-solid fa-circle-notch fa-spin"></i>
            {{ isSavingMenuItem ? 'Saving...' : 'Save Item' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Modal Dialog: Add / Edit Carousel Slide -->
    <div v-if="isSlideModalOpen" class="modal-backdrop" @click.self="isSlideModalOpen = false">
      <div class="modal-box modal-box-lg animate-slide-up">
        <div class="modal-header">
          <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--color-primary); margin: 0; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-images" style="color: var(--color-brand-gold);"></i>
            {{ editingSlideIndex !== null ? 'Edit Carousel Slide' : 'Add New Carousel Slide' }}
          </h3>
          <button @click="isSlideModalOpen = false" class="modal-close-btn">
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>

        <div class="modal-body modal-scrollable">
          <!-- Slide Heading & Text -->
          <div class="form-group mb-4">
            <label class="form-label">Slide Caption / Main Title (H1) *</label>
            <input v-model="slideForm.title" type="text" class="form-input" placeholder="e.g. Bespoke Sofas Engineered For A Lifetime" required />
            <span class="form-hint">Main luxurious serif headline shown on the hero banner</span>
          </div>

          <div class="form-group mb-4">
            <label class="form-label">Slide Subtitle / Description Paragraph</label>
            <textarea v-model="slideForm.subtitle" rows="2" class="form-input" placeholder="e.g. Handcrafted with 100% kiln-dried solid hardwood frames, pocket spring suspension, and pet-friendly fabrics."></textarea>
          </div>

          <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="form-group">
              <label class="form-label">Eyebrow / Category Tag</label>
              <input v-model="slideForm.eyebrow" type="text" class="form-input" placeholder="e.g. BUKIT JELUTONG WORKSHOP • DIRECT FACTORY CRAFT" />
            </div>

            <div class="form-group">
              <label class="form-label">Client Hunting Trust Badge</label>
              <input v-model="slideForm.badge_text" type="text" class="form-input" placeholder="e.g. ★ 4.9/5 Verified Rating • 4,800+ Homes Furnished" />
              <span class="form-hint">Floating luxury pill badge for high-conversion social proof</span>
            </div>
          </div>

          <!-- Media Configuration: Image vs Video -->
          <div class="settings-box-panel mb-4">
            <label class="form-label" style="font-weight: 700; margin-bottom: 0.5rem;">Background Media Type</label>
            <div class="flex items-center gap-6 mb-3">
              <label class="radio-option">
                <input type="radio" v-model="slideForm.media_type" value="image" />
                <span class="radio-label"><i class="fa-regular fa-image"></i> High-Resolution Photography (Image)</span>
              </label>
              <label class="radio-option">
                <input type="radio" v-model="slideForm.media_type" value="video" />
                <span class="radio-label"><i class="fa-solid fa-video"></i> Craftsmanship Video (MP4 / WebM)</span>
              </label>
            </div>

            <div class="form-group mb-3">
              <label class="form-label">{{ slideForm.media_type === 'video' ? 'Video File URL (MP4 / WebM)' : 'High-Resolution Image URL' }} *</label>
              <input v-model="slideForm.media_url" type="text" class="form-input" placeholder="https://..." required />
            </div>

            <div v-if="slideForm.media_type === 'video'" class="form-group mb-3">
              <label class="form-label">Video Poster Fallback Image URL</label>
              <input v-model="slideForm.video_poster" type="text" class="form-input" placeholder="https://... (Image shown before video loads)" />
            </div>

            <!-- Quick Presets -->
            <div class="media-presets-wrap mt-2">
              <span style="font-size: 0.75rem; font-weight: 700; color: var(--color-text-muted); text-transform: uppercase;">
                Realistic Media Presets:
              </span>
              <div class="flex items-center gap-2 flex-wrap mt-1">
                <button
                  type="button"
                  @click="slideForm.media_url = 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=2000&q=85'; slideForm.media_type = 'image';"
                  class="preset-chip-btn"
                >
                  <i class="fa-regular fa-image"></i> Emerald Living Room
                </button>
                <button
                  type="button"
                  @click="slideForm.media_url = 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=2000&q=85'; slideForm.media_type = 'image';"
                  class="preset-chip-btn"
                >
                  <i class="fa-regular fa-image"></i> Fabric & Bouclé Swatches
                </button>
                <button
                  type="button"
                  @click="slideForm.media_url = 'https://assets.mixkit.co/videos/preview/mixkit-carpenter-measuring-a-piece-of-wood-in-a-workshop-43867-large.mp4'; slideForm.video_poster = 'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?auto=format&fit=crop&w=2000&q=85'; slideForm.media_type = 'video';"
                  class="preset-chip-btn"
                >
                  <i class="fa-solid fa-video"></i> Carpentry Joinery Video
                </button>
                <button
                  type="button"
                  @click="slideForm.media_url = 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?auto=format&fit=crop&w=2000&q=85'; slideForm.media_type = 'image';"
                  class="preset-chip-btn"
                >
                  <i class="fa-regular fa-image"></i> Curved Penthouse Sofa
                </button>
              </div>
            </div>
          </div>

          <!-- Buttons Configuration: Single or Double Button -->
          <div class="settings-box-panel mb-4">
            <label class="form-label" style="font-weight: 700; margin-bottom: 0.5rem;">Action Buttons Configuration</label>
            <div class="flex items-center gap-6 mb-4">
              <label class="radio-option">
                <input type="radio" v-model="slideForm.button_mode" value="single" />
                <span class="radio-label">Single Button</span>
              </label>
              <label class="radio-option">
                <input type="radio" v-model="slideForm.button_mode" value="double" />
                <span class="radio-label">Double Button (Recommended for High Conversion)</span>
              </label>
            </div>

            <!-- Primary Button Fields -->
            <div class="button-config-card mb-4">
              <div class="button-config-card-header">
                <strong><i class="fa-solid fa-1"></i> Primary Button</strong>
              </div>
              <div class="grid grid-cols-3 gap-3 p-3">
                <div class="form-group">
                  <label class="form-label">Button Text *</label>
                  <input v-model="slideForm.btn_primary_text" type="text" class="form-input" placeholder="Explore Collections" required />
                </div>
                <div class="form-group">
                  <label class="form-label">Destination URL</label>
                  <input v-model="slideForm.btn_primary_link" type="text" class="form-input" placeholder="/shop" />
                </div>
                <div class="form-group">
                  <label class="form-label">Button Style</label>
                  <select v-model="slideForm.btn_primary_style" class="form-input">
                    <option value="royal-gold">Royal Gold Gradient (High Contrast)</option>
                    <option value="wine">Burgundy Wine (Brand Luxury)</option>
                    <option value="glass">Glassmorphism Gold</option>
                    <option value="whatsapp">WhatsApp Direct Green</option>
                    <option value="outline">Outline Gold</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Secondary Button Fields (If Double Button) -->
            <div v-if="slideForm.button_mode === 'double'" class="button-config-card">
              <div class="button-config-card-header flex items-center justify-between">
                <strong><i class="fa-solid fa-2"></i> Secondary Button</strong>
                <div class="flex items-center gap-2">
                  <button
                    type="button"
                    @click="slideForm.btn_secondary_text = 'Visit Our Showrooms'; slideForm.btn_secondary_link = '/our-showroom'; slideForm.btn_secondary_style = 'glass';"
                    class="quick-preset-link"
                  >
                    + Showrooms Preset
                  </button>
                  <button
                    type="button"
                    @click="slideForm.btn_secondary_text = 'WhatsApp Concierge'; slideForm.btn_secondary_link = 'https://wa.me/60192589920?text=Hello%20NMFFurniture'; slideForm.btn_secondary_style = 'glass';"
                    class="quick-preset-link"
                  >
                    + WhatsApp Preset
                  </button>
                </div>
              </div>
              <div class="grid grid-cols-3 gap-3 p-3">
                <div class="form-group">
                  <label class="form-label">Button Text</label>
                  <input v-model="slideForm.btn_secondary_text" type="text" class="form-input" placeholder="Visit Showrooms" />
                </div>
                <div class="form-group">
                  <label class="form-label">Destination URL</label>
                  <input v-model="slideForm.btn_secondary_link" type="text" class="form-input" placeholder="/our-showroom" />
                </div>
                <div class="form-group">
                  <label class="form-label">Button Style</label>
                  <select v-model="slideForm.btn_secondary_style" class="form-input">
                    <option value="glass">Glassmorphism Gold (Recommended)</option>
                    <option value="wine">Burgundy Wine (Brand Luxury)</option>
                    <option value="royal-gold">Royal Gold Gradient</option>
                    <option value="whatsapp">WhatsApp Direct Green</option>
                    <option value="outline">Outline Gold</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <!-- Active Status -->
          <div class="form-group">
            <label class="form-label">Slide Display Status</label>
            <select v-model="slideForm.is_active" class="form-input">
              <option :value="true">Active (Visible on Homepage)</option>
              <option :value="false">Draft / Inactive (Hidden from Homepage)</option>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button @click="isSlideModalOpen = false" class="btn btn-outline btn-sm">Cancel</button>
          <button @click="saveSlide" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-check"></i> {{ editingSlideIndex !== null ? 'Update Slide' : 'Add Slide to Carousel' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'
import { useSettingsStore } from '~/stores/settings'

definePageMeta({
  layout: 'admin',
  middleware: ['auth']
})

const authStore = useAuthStore()
const settingsStore = useSettingsStore()
const config = useRuntimeConfig()
const apiBase = config.public.apiBase || 'http://localhost:8001/api'

// Available Tabs
const tabs = [
  { id: 'general', label: 'General & Branding', icon: 'fa-solid fa-building' },
  { id: 'header', label: 'Header & Marquee', icon: 'fa-solid fa-bullhorn' },
  { id: 'menus', label: 'Navigation Menus', icon: 'fa-solid fa-compass' },
  { id: 'footer', label: 'Footer & Showroom', icon: 'fa-solid fa-landmark' },
  { id: 'homepage', label: 'Homepage Sections', icon: 'fa-solid fa-house-chimney' },
  { id: 'carousel', label: 'Hero Carousel & Sliders', icon: 'fa-solid fa-images' },
  { id: 'seo', label: 'SEO & Tracking', icon: 'fa-solid fa-chart-pie' },
]

const route = useRoute()
const currentTab = ref((route.query.tab as string) || 'general')

watch(() => route.query.tab, (tab) => {
  if (tab && typeof tab === 'string') {
    currentTab.value = tab
  }
})

const isSaving = ref(false)
const saveSuccessMessage = ref('')

// Form Settings Model
const formSettings = reactive<Record<string, any>>({
  site_name: '',
  site_tagline: '',
  ssm_number: '',
  support_phone: '',
  support_whatsapp: '',
  support_email: '',
  factory_address: '',
  announcement_enabled: '1',
  announcement_text_1: '',
  announcement_text_2: '',
  announcement_link_text: '',
  announcement_link_url: '',
  header_sticky: '1',
  footer_about_text: '',
  footer_showroom_title: '',
  footer_showroom_address: '',
  footer_showroom_hours: '',
  footer_whatsapp_btn_text: '',
  footer_copyright_text: '',
  footer_guarantee_badge: '',
  hero_eyebrow: '',
  hero_title: '',
  hero_subtitle: '',
  hero_image_url: '',
  hero_btn_primary_text: '',
  hero_btn_primary_link: '',
  hero_btn_secondary_text: '',
  hero_btn_secondary_link: '',
  hero_carousel_enabled: '1',
  hero_carousel_autoplay: '1',
  hero_carousel_interval: 6000,
  hero_carousel_height: 'standard',
  hero_carousel_transition: 'fade',
  hero_carousel_slides: '',
  seo_meta_title: '',
  seo_meta_description: '',
  seo_ga4_id: '',
  seo_pixel_id: '',
})

// Carousel Slides State & Methods
const carouselSlidesList = ref<any[]>([])
const isSlideModalOpen = ref(false)
const editingSlideIndex = ref<number | null>(null)

const slideForm = reactive({
  id: '',
  media_type: 'image',
  media_url: '',
  video_poster: '',
  eyebrow: '',
  title: '',
  subtitle: '',
  badge_text: '',
  button_mode: 'double',
  btn_primary_text: '',
  btn_primary_link: '',
  btn_primary_style: 'royal-gold',
  btn_secondary_text: '',
  btn_secondary_link: '',
  btn_secondary_style: 'glass',
  is_active: true,
})

function getDefaultSlides() {
  return [
    {
      id: 1,
      media_type: 'image',
      media_url: 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=2000&q=85',
      eyebrow: 'BUKIT JELUTONG WORKSHOP • DIRECT FACTORY CRAFT',
      title: 'Bespoke Sofas Engineered For A Lifetime',
      subtitle: 'Handcrafted with 100% kiln-dried Malaysian solid Meranti hardwood frames, dual-tier pocket springs, and custom sizing to perfectly match your floorplan.',
      badge_text: '★ 4.9/5 Verified Rating • 4,800+ Homes Furnished',
      button_mode: 'double',
      btn_primary_text: 'Explore Bespoke Living',
      btn_primary_link: '/shop?category=sofa',
      btn_primary_style: 'royal-gold',
      btn_secondary_text: 'Visit Our Showrooms',
      btn_secondary_link: '/our-showroom',
      btn_secondary_style: 'glass',
      is_active: true,
    },
    {
      id: 2,
      media_type: 'image',
      media_url: 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=2000&q=85',
      eyebrow: 'PERFORMANCE TEXTILE INNOVATION • SPILL & CLAW PROOF',
      title: '200+ Pet-Friendly & Stain-Resistant Fabrics',
      subtitle: 'Experience luxury French bouclés, claw-resistant velvets, and hydrophobic weaves built for living rooms with energetic pets and growing children.',
      badge_text: 'Complimentary Fabric Swatch Box Delivered Free',
      button_mode: 'double',
      btn_primary_text: 'Request Swatch Box',
      btn_primary_link: 'https://wa.me/60192589920?text=Hello%20NMFFurniture%2C%20I%20would%20like%20to%20request%20your%20complimentary%20200%2B%20fabric%20swatch%20box.',
      btn_primary_style: 'whatsapp',
      btn_secondary_text: 'Browse Fabric Guide',
      btn_secondary_link: '/shop',
      btn_secondary_style: 'glass',
      is_active: true,
    },
    {
      id: 3,
      media_type: 'video',
      media_url: 'https://assets.mixkit.co/videos/preview/mixkit-carpenter-measuring-a-piece-of-wood-in-a-workshop-43867-large.mp4',
      video_poster: 'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?auto=format&fit=crop&w=2000&q=85',
      eyebrow: '100% SOLID MERANTI HARDWOOD • ZERO PARTICLE BOARD',
      title: '5-Year Structural Frame Warranty Guaranteed',
      subtitle: 'Every timber joint is mortise-and-tenon reinforced by veteran Malaysian craftsmen. Factory-direct pricing with zero middleman markups.',
      badge_text: 'Save Up To 40% Direct Factory Pricing',
      button_mode: 'double',
      btn_primary_text: 'Explore Factory Direct',
      btn_primary_link: '/shop',
      btn_primary_style: 'royal-gold',
      btn_secondary_text: 'Our Joinery Heritage',
      btn_secondary_link: '/about',
      btn_secondary_style: 'wine',
      is_active: true,
    },
    {
      id: 4,
      media_type: 'image',
      media_url: 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?auto=format&fit=crop&w=2000&q=85',
      eyebrow: 'BESPOKE SIZING • RESIDENTIAL & VILLA FITOUTS',
      title: 'Tailored To Your Living Room Floorplan',
      subtitle: 'From Mont Kiara penthouses to Setia Eco Park villas, our master design concierge customises length, L-shape orientation, and cushion firmness.',
      badge_text: 'Free 3D Layout & Dimension Consultation',
      button_mode: 'single',
      btn_primary_text: 'Book Showroom Consultation',
      btn_primary_link: '/our-showroom',
      btn_primary_style: 'royal-gold',
      btn_secondary_text: '',
      btn_secondary_link: '',
      btn_secondary_style: 'glass',
      is_active: true,
    },
  ]
}

function syncSlidesToForm() {
  formSettings.hero_carousel_slides = JSON.stringify(carouselSlidesList.value)
}

function openAddSlideModal() {
  editingSlideIndex.value = null
  slideForm.id = 'slide_' + Date.now()
  slideForm.media_type = 'image'
  slideForm.media_url = ''
  slideForm.video_poster = ''
  slideForm.eyebrow = ''
  slideForm.title = ''
  slideForm.subtitle = ''
  slideForm.badge_text = ''
  slideForm.button_mode = 'double'
  slideForm.btn_primary_text = 'Explore Collection'
  slideForm.btn_primary_link = '/shop'
  slideForm.btn_primary_style = 'royal-gold'
  slideForm.btn_secondary_text = 'Visit Showrooms'
  slideForm.btn_secondary_link = '/our-showroom'
  slideForm.btn_secondary_style = 'glass'
  slideForm.is_active = true
  isSlideModalOpen.value = true
}

function openEditSlideModal(index: number) {
  editingSlideIndex.value = index
  const item = carouselSlidesList.value[index]
  if (!item) return
  slideForm.id = item.id || 'slide_' + Date.now()
  slideForm.media_type = item.media_type || 'image'
  slideForm.media_url = item.media_url || ''
  slideForm.video_poster = item.video_poster || ''
  slideForm.eyebrow = item.eyebrow || ''
  slideForm.title = item.title || ''
  slideForm.subtitle = item.subtitle || ''
  slideForm.badge_text = item.badge_text || ''
  slideForm.button_mode = item.button_mode || (item.btn_secondary_text ? 'double' : 'single')
  slideForm.btn_primary_text = item.btn_primary_text || ''
  slideForm.btn_primary_link = item.btn_primary_link || '/shop'
  slideForm.btn_primary_style = item.btn_primary_style || 'royal-gold'
  slideForm.btn_secondary_text = item.btn_secondary_text || ''
  slideForm.btn_secondary_link = item.btn_secondary_link || '/our-showroom'
  slideForm.btn_secondary_style = item.btn_secondary_style || 'glass'
  slideForm.is_active = item.is_active !== false
  isSlideModalOpen.value = true
}

function saveSlide() {
  if (!slideForm.title || !slideForm.media_url) {
    alert('Please provide at least a slide title and media URL.')
    return
  }

  const slidePayload = {
    id: slideForm.id,
    media_type: slideForm.media_type,
    media_url: slideForm.media_url,
    video_poster: slideForm.video_poster,
    eyebrow: slideForm.eyebrow,
    title: slideForm.title,
    subtitle: slideForm.subtitle,
    badge_text: slideForm.badge_text,
    button_mode: slideForm.button_mode,
    btn_primary_text: slideForm.btn_primary_text,
    btn_primary_link: slideForm.btn_primary_link,
    btn_primary_style: slideForm.btn_primary_style,
    btn_secondary_text: slideForm.button_mode === 'double' ? slideForm.btn_secondary_text : '',
    btn_secondary_link: slideForm.button_mode === 'double' ? slideForm.btn_secondary_link : '',
    btn_secondary_style: slideForm.button_mode === 'double' ? slideForm.btn_secondary_style : 'glass',
    is_active: slideForm.is_active,
  }

  if (editingSlideIndex.value !== null) {
    carouselSlidesList.value[editingSlideIndex.value] = slidePayload
  } else {
    carouselSlidesList.value.push(slidePayload)
  }

  syncSlidesToForm()
  isSlideModalOpen.value = false
}

function deleteSlide(index: number) {
  if (confirm('Are you sure you want to delete this slide?')) {
    carouselSlidesList.value.splice(index, 1)
    syncSlidesToForm()
  }
}

function moveSlide(index: number, direction: number) {
  const targetIndex = index + direction
  if (targetIndex < 0 || targetIndex >= carouselSlidesList.value.length) return
  const temp = carouselSlidesList.value[index]
  carouselSlidesList.value[index] = carouselSlidesList.value[targetIndex]
  carouselSlidesList.value[targetIndex] = temp
  syncSlidesToForm()
}

function toggleSlideActive(index: number) {
  const current = carouselSlidesList.value[index].is_active !== false
  carouselSlidesList.value[index].is_active = !current
  syncSlidesToForm()
}

function resetDefaultSlides() {
  if (confirm('Reset carousel slides to default Malaysian luxury craftsmanship set?')) {
    carouselSlidesList.value = getDefaultSlides()
    syncSlidesToForm()
  }
}

// Menus State
const menusList = ref<any[]>([])
const selectedMenuLocation = ref('header_main')

const activeMenu = computed(() => {
  return menusList.value.find(m => m.location === selectedMenuLocation.value)
})

const activeMenuItems = computed(() => {
  return activeMenu.value?.items || []
})

// Menu Item Modal State
const isMenuItemModalOpen = ref(false)
const isSavingMenuItem = ref(false)
const editingMenuItemId = ref<number | null>(null)
const menuItemParentId = ref<number | null>(null)

const menuItemForm = reactive({
  title: '',
  url: '',
  icon: '',
  badge: '',
  target: '_self',
  is_active: true,
})

// Load Settings & Menus from API
async function loadData() {
  try {
    const headers = { Authorization: `Bearer ${authStore.token}` }
    const [settingsRes, menusRes]: [any, any] = await Promise.all([
      $fetch(`${apiBase}/admin/settings`, { headers }),
      $fetch(`${apiBase}/admin/menus`, { headers })
    ])

    if (settingsRes?.settings) {
      settingsRes.settings.forEach((s: any) => {
        formSettings[s.key] = s.value
      })

      // Parse carousel slides
      if (formSettings.hero_carousel_slides) {
        try {
          const parsed = typeof formSettings.hero_carousel_slides === 'string'
            ? JSON.parse(formSettings.hero_carousel_slides)
            : formSettings.hero_carousel_slides
          if (Array.isArray(parsed) && parsed.length > 0) {
            carouselSlidesList.value = parsed
          } else {
            carouselSlidesList.value = getDefaultSlides()
          }
        } catch (e) {
          carouselSlidesList.value = getDefaultSlides()
        }
      } else {
        carouselSlidesList.value = getDefaultSlides()
      }
    }

    if (menusRes) {
      menusList.value = menusRes
    }
  } catch (err) {
    console.error('Failed to load admin settings', err)
  }
}

// Save All Key-Value Settings
async function saveAllSettings() {
  isSaving.value = true
  saveSuccessMessage.value = ''
  try {
    // Sync carousel slides to formSettings JSON string
    formSettings.hero_carousel_slides = JSON.stringify(carouselSlidesList.value)

    const headers = { Authorization: `Bearer ${authStore.token}` }
    await $fetch(`${apiBase}/admin/settings/batch`, {
      method: 'POST',
      headers,
      body: { settings: formSettings }
    })

    // Refresh Pinia store so whole app updates immediately
    await settingsStore.refreshSettings()

    saveSuccessMessage.value = 'Website settings and public cache updated successfully!'
    setTimeout(() => { saveSuccessMessage.value = '' }, 4000)
  } catch (err: any) {
    alert(err?.data?.message || 'Failed to save settings.')
  } finally {
    isSaving.value = false
  }
}

// Menu Items Reordering
async function moveMenuItem(index: number, direction: number) {
  if (!activeMenu.value) return
  const items = [...activeMenu.value.items]
  const targetIndex = index + direction
  if (targetIndex < 0 || targetIndex >= items.length) return

  // Swap
  const temp = items[index]
  items[index] = items[targetIndex]
  items[targetIndex] = temp

  // Update local view
  activeMenu.value.items = items

  // Save order to backend
  const payload = items.map((it, idx) => ({ id: it.id, sort_order: idx + 1 }))
  try {
    const headers = { Authorization: `Bearer ${authStore.token}` }
    await $fetch(`${apiBase}/admin/menus/${activeMenu.value.id}/reorder`, {
      method: 'POST',
      headers,
      body: { items: payload }
    })
    await settingsStore.refreshSettings()
  } catch (e) {
    console.error('Failed to reorder menu items', e)
  }
}

function openAddMenuItemModal(parentId: number | null = null) {
  editingMenuItemId.value = null
  menuItemParentId.value = parentId
  menuItemForm.title = ''
  menuItemForm.url = ''
  menuItemForm.icon = ''
  menuItemForm.badge = ''
  menuItemForm.target = '_self'
  menuItemForm.is_active = true
  isMenuItemModalOpen.value = true
}

function openEditMenuItemModal(item: any) {
  editingMenuItemId.value = item.id
  menuItemParentId.value = item.parent_id
  menuItemForm.title = item.title
  menuItemForm.url = item.url
  menuItemForm.icon = item.icon || ''
  menuItemForm.badge = item.badge || ''
  menuItemForm.target = item.target || '_self'
  menuItemForm.is_active = item.is_active
  isMenuItemModalOpen.value = true
}

async function saveMenuItem() {
  if (!activeMenu.value) return
  if (!menuItemForm.title.trim() || !menuItemForm.url.trim()) {
    alert('Title and URL are required.')
    return
  }

  isSavingMenuItem.value = true
  try {
    const headers = { Authorization: `Bearer ${authStore.token}` }
    if (editingMenuItemId.value) {
      await $fetch(`${apiBase}/admin/menus/items/${editingMenuItemId.value}`, {
        method: 'PUT',
        headers,
        body: menuItemForm
      })
    } else {
      await $fetch(`${apiBase}/admin/menus/${activeMenu.value.id}/items`, {
        method: 'POST',
        headers,
        body: {
          ...menuItemForm,
          parent_id: menuItemParentId.value
        }
      })
    }

    isMenuItemModalOpen.value = false
    await loadData()
    await settingsStore.refreshSettings()
  } catch (err: any) {
    alert(err?.data?.message || 'Failed to save menu item.')
  } finally {
    isSavingMenuItem.value = false
  }
}

async function deleteMenuItem(itemId: number) {
  if (!confirm('Are you sure you want to delete this menu link?')) return
  try {
    const headers = { Authorization: `Bearer ${authStore.token}` }
    await $fetch(`${apiBase}/admin/menus/items/${itemId}`, {
      method: 'DELETE',
      headers
    })
    await loadData()
    await settingsStore.refreshSettings()
  } catch (err: any) {
    alert(err?.data?.message || 'Failed to delete menu item.')
  }
}

onMounted(() => {
  loadData()
})
</script>

<style scoped>
/* Settings Tabs Navigation */
.settings-tabs-header {
  display: flex;
  gap: 0.5rem;
  overflow-x: auto;
  border-bottom: 2px solid var(--color-border);
  margin-bottom: 2rem;
  padding-bottom: 0.25rem;
}

.settings-tab-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.6rem;
  padding: 0.75rem 1.25rem;
  background: transparent;
  border: none;
  font-family: var(--font-sans);
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--color-text-muted);
  cursor: pointer;
  border-bottom: 3px solid transparent;
  margin-bottom: -0.25rem;
  transition: all var(--transition-fast);
  white-space: nowrap;
}

.settings-tab-btn:hover {
  color: var(--color-primary);
}

.settings-tab-btn.active {
  color: var(--color-secondary-dark);
  border-bottom-color: var(--color-secondary);
}

.settings-card {
  background: #FFFFFF;
  border: 1px solid var(--color-border);
  border-radius: var(--radius-sm);
  padding: 2.25rem;
  box-shadow: var(--shadow-sm);
  margin-bottom: 2rem;
}

.settings-section-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--color-primary);
  display: flex;
  align-items: center;
  gap: 0.65rem;
  margin-bottom: 0.4rem;
}

.settings-section-desc {
  font-size: 0.88rem;
  color: var(--color-text-muted);
  margin-bottom: 0;
}

.form-hint {
  display: block;
  font-size: 0.76rem;
  color: var(--color-text-muted);
  margin-top: 0.35rem;
}

/* Switch toggle */
.settings-toggle-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 1.25rem;
  background: #F9F8F6;
  border: 1px solid var(--color-border);
  border-radius: var(--radius-sm);
}

.switch {
  position: relative;
  display: inline-block;
  width: 48px;
  height: 26px;
}

.switch input { opacity: 0; width: 0; height: 0; }

.slider {
  position: absolute;
  cursor: pointer;
  inset: 0;
  background-color: #CBD5E1;
  transition: .3s;
  border-radius: 34px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 20px;
  width: 20px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: .3s;
  border-radius: 50%;
}

input:checked + .slider {
  background-color: var(--color-secondary);
}

input:checked + .slider:before {
  transform: translateX(22px);
}

/* Menu Items List */
.menu-items-table-wrap {
  border: 1px solid var(--color-border);
  border-radius: var(--radius-sm);
  background: #FFFFFF;
}

.menu-items-list {
  display: flex;
  flex-direction: column;
}

.menu-item-row {
  display: flex;
  align-items: flex-start;
  gap: 1.25rem;
  padding: 1.1rem 1.25rem;
  border-bottom: 1px solid var(--color-border);
  transition: background 0.15s ease;
}

.menu-item-row:last-child {
  border-bottom: none;
}

.menu-item-row:hover {
  background: #FAFAF9;
}

.menu-item-order-controls {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.25rem;
  flex-shrink: 0;
  width: 32px;
}

.menu-sort-btn {
  background: transparent;
  border: 1px solid var(--color-border);
  border-radius: 3px;
  width: 26px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--color-text-main);
  cursor: pointer;
  font-size: 0.75rem;
  transition: all var(--transition-fast);
}

.menu-sort-btn:hover:not(:disabled) {
  background: var(--color-primary);
  color: #FFFFFF;
}

.menu-sort-btn:disabled {
  opacity: 0.3;
  cursor: not-allowed;
}

.menu-item-index {
  font-size: 0.72rem;
  font-weight: 700;
  color: var(--color-text-muted);
}

.menu-item-content {
  flex-grow: 1;
}

.menu-item-actions {
  display: flex;
  gap: 0.35rem;
  flex-shrink: 0;
}

.action-icon-btn {
  width: 32px;
  height: 32px;
  border: 1px solid var(--color-border);
  border-radius: var(--radius-sm);
  background: #FFFFFF;
  color: var(--color-primary);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.85rem;
  transition: all var(--transition-fast);
}

.action-icon-btn:hover {
  background: var(--color-bg-alt);
  border-color: var(--color-secondary);
}

.action-icon-btn.danger:hover {
  background: #FFF1F2;
  color: #E11D48;
  border-color: #FECDD3;
}

/* Nested submenus preview */
.nested-menu-preview {
  background: #F9F8F6;
  border: 1px dashed var(--color-border);
  border-radius: var(--radius-sm);
  padding: 0.75rem 1rem;
}

.nested-items-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.nested-item-pill {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  background: #FFFFFF;
  border: 1px solid var(--color-border);
  border-radius: var(--radius-sm);
  padding: 0.25rem 0.6rem;
  font-size: 0.78rem;
  color: var(--color-primary);
}

.delete-sub-btn {
  background: transparent;
  border: none;
  color: #9CA3AF;
  cursor: pointer;
  padding: 0 0.15rem;
  font-size: 0.7rem;
}

.delete-sub-btn:hover {
  color: #DC2626;
}

/* Modal styles */
.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(4px);
  z-index: 500;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1.5rem;
}

.modal-box {
  background: #FFFFFF;
  border-radius: var(--radius-sm);
  box-shadow: var(--shadow-xl);
  width: 100%;
  max-width: 540px;
  overflow: hidden;
}

.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid var(--color-border);
}

.modal-close-btn {
  background: transparent;
  border: none;
  color: var(--color-text-muted);
  cursor: pointer;
  font-size: 1.1rem;
}

.modal-body {
  padding: 1.5rem;
}

.modal-footer {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 0.75rem;
  padding: 1rem 1.5rem;
  background: #F9F8F6;
  border-top: 1px solid var(--color-border);
}

.admin-alert.success {
  background: #F0FDF4;
  border: 1px solid #BBF7D0;
  color: #15803D;
  padding: 0.85rem 1.25rem;
  border-radius: var(--radius-sm);
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-weight: 600;
  font-size: 0.9rem;
}

/* Modal Large & Scrollable */
.modal-box.modal-box-lg {
  max-width: 760px;
  max-height: 90vh;
  display: flex;
  flex-direction: column;
}

.modal-scrollable {
  overflow-y: auto;
  max-height: calc(90vh - 130px);
}

/* Carousel Management Styles */
.carousel-config-box {
  background: #FDFCF9;
  border: 1px solid var(--color-border);
  border-radius: var(--radius-sm);
  padding: 1.5rem;
}

.slides-cards-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.slide-row-card {
  display: flex;
  align-items: center;
  gap: 1.25rem;
  background: #FFFFFF;
  border: 1px solid var(--color-border);
  border-radius: var(--radius-sm);
  padding: 1rem 1.25rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
  transition: all var(--transition-fast);
}

.slide-row-card:hover {
  border-color: var(--color-secondary);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.slide-row-card.is-inactive {
  opacity: 0.65;
  background: #F9FAFB;
}

.slide-order-controls {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.25rem;
  flex-shrink: 0;
}

.slide-index-pill {
  font-size: 0.8rem;
  font-weight: 700;
  color: var(--color-primary);
  width: 22px;
  text-align: center;
}

.slide-media-preview {
  position: relative;
  width: 130px;
  height: 80px;
  border-radius: var(--radius-sm);
  overflow: hidden;
  background: #1A0306;
  flex-shrink: 0;
}

.slide-thumb-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.slide-media-tag {
  position: absolute;
  bottom: 4px;
  left: 4px;
  font-size: 0.62rem;
  font-weight: 700;
  padding: 0.15rem 0.4rem;
  border-radius: 3px;
  color: #FFFFFF;
  letter-spacing: 0.04em;
}

.slide-media-tag.video {
  background: rgba(185, 28, 28, 0.88);
}

.slide-media-tag.image {
  background: rgba(17, 24, 39, 0.85);
}

.slide-info-body {
  flex-grow: 1;
  min-width: 0;
}

.slide-eyebrow-badge {
  font-size: 0.68rem;
  font-weight: 700;
  text-transform: uppercase;
  color: var(--color-secondary-dark);
  letter-spacing: 0.05em;
  background: var(--color-secondary-light);
  padding: 0.15rem 0.5rem;
  border-radius: 3px;
}

.slide-trust-badge {
  font-size: 0.68rem;
  font-weight: 600;
  background: #FFFBEB;
  color: #92400E;
  border: 1px solid #FDE68A;
  padding: 0.15rem 0.5rem;
  border-radius: 3px;
}

.slide-btn-mode-badge {
  font-size: 0.68rem;
  font-weight: 600;
  background: #F3F4F6;
  color: #374151;
  padding: 0.15rem 0.45rem;
  border-radius: 3px;
}

.badge-draft {
  font-size: 0.68rem;
  font-weight: 600;
  background: #FEE2E2;
  color: #991B1B;
  padding: 0.15rem 0.45rem;
  border-radius: 3px;
}

.slide-card-title {
  font-family: var(--font-heading);
  font-size: 1.05rem;
  font-weight: 600;
  color: var(--color-primary);
  margin: 0.25rem 0 0.15rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.slide-card-subtitle {
  font-size: 0.82rem;
  color: var(--color-text-muted);
  margin: 0;
  line-height: 1.4;
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.btn-preview-tag {
  display: inline-flex;
  align-items: center;
  font-size: 0.75rem;
  padding: 0.15rem 0.5rem;
  border-radius: 3px;
}

.btn-preview-tag.primary {
  background: #F7F3EE;
  color: var(--color-secondary-dark);
  border: 1px solid rgba(181, 149, 109, 0.3);
}

.btn-preview-tag.secondary {
  background: #F3F4F6;
  color: #4B5563;
  border: 1px solid #E5E7EB;
}

.slide-actions-cell {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  flex-shrink: 0;
}

.settings-box-panel {
  background: #F9F8F6;
  border: 1px solid var(--color-border);
  border-radius: var(--radius-sm);
  padding: 1.25rem;
}

.radio-option {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  font-size: 0.88rem;
  font-weight: 500;
  color: var(--color-primary);
}

.radio-label {
  display: flex;
  align-items: center;
  gap: 0.4rem;
}

.button-config-card {
  background: #FFFFFF;
  border: 1px solid var(--color-border);
  border-radius: var(--radius-sm);
  overflow: hidden;
}

.button-config-card-header {
  background: #F4F1EA;
  padding: 0.6rem 1rem;
  border-bottom: 1px solid var(--color-border);
  font-size: 0.85rem;
  color: var(--color-primary);
}

.preset-chip-btn {
  background: #FFFFFF;
  border: 1px solid var(--color-border);
  border-radius: 9999px;
  padding: 0.25rem 0.65rem;
  font-size: 0.75rem;
  font-weight: 600;
  color: var(--color-primary);
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  transition: all var(--transition-fast);
}

.preset-chip-btn:hover {
  background: var(--color-secondary-light);
  border-color: var(--color-secondary);
  color: var(--color-secondary-dark);
}

.quick-preset-link {
  background: transparent;
  border: none;
  color: var(--color-secondary-dark);
  font-size: 0.75rem;
  font-weight: 600;
  cursor: pointer;
  padding: 0 0.25rem;
}

.quick-preset-link:hover {
  text-decoration: underline;
}
</style>

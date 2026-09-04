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
  seo_meta_title: '',
  seo_meta_description: '',
  seo_ga4_id: '',
  seo_pixel_id: '',
})

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
</style>

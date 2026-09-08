<?php

namespace Database\Seeders;

use App\Models\NavigationMenu;
use App\Models\NavigationMenuItem;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // 1. Settings Key-Values
        // ==========================================
        $settings = [
            // General & Branding
            [
                'group' => 'general',
                'key' => 'site_name',
                'value' => 'NMF Furniture',
                'type' => 'text',
                'label' => 'Website / Brand Name',
                'description' => 'Main brand name displayed across the website and in titles.',
            ],
            [
                'group' => 'general',
                'key' => 'site_logo',
                'value' => '/images/brand/website_logo_4K.png',
                'type' => 'image',
                'label' => 'Official Brand Logo (4K / SVG)',
                'description' => 'Primary brand logo from official logo kit for navbar and headers.',
            ],
            [
                'group' => 'general',
                'key' => 'brand_name_primary',
                'value' => 'NMF',
                'type' => 'text',
                'label' => 'Navbar Brand Primary Text',
                'description' => 'First word of text logo in top navbar.',
            ],
            [
                'group' => 'general',
                'key' => 'brand_name_accent',
                'value' => 'FURNITURE',
                'type' => 'text',
                'label' => 'Navbar Brand Accent Text',
                'description' => 'Highlighted/accent word of text logo in top navbar.',
            ],
            [
                'group' => 'general',
                'key' => 'brand_badge',
                'value' => 'SDN BHD',
                'type' => 'text',
                'label' => 'Navbar Brand Small Badge',
                'description' => 'Small corporate badge beside logo (e.g. SDN BHD or MALAYSIA).',
            ],
            [
                'group' => 'general',
                'key' => 'site_tagline',
                'value' => 'Bespoke Handcrafted Living Furniture Malaysia',
                'type' => 'text',
                'label' => 'Brand Tagline',
                'description' => 'Brand subtitle used in footer, hero section, and SEO metadata.',
            ],
            [
                'group' => 'general',
                'key' => 'ssm_number',
                'value' => 'SSM 1400875-P',
                'type' => 'text',
                'label' => 'Official SSM Registration Number',
                'description' => 'Malaysian government business registration number.',
            ],
            [
                'group' => 'general',
                'key' => 'support_phone',
                'value' => '+603 7832 9920',
                'type' => 'text',
                'label' => 'Support Telephone',
                'description' => 'Main showroom customer service phone number.',
            ],
            [
                'group' => 'general',
                'key' => 'support_whatsapp',
                'value' => '60192589920',
                'type' => 'text',
                'label' => 'WhatsApp Contact Number (No +)',
                'description' => 'Primary Malaysian mobile number for WhatsApp inquiries.',
            ],
            [
                'group' => 'general',
                'key' => 'support_email',
                'value' => 'concierge@nmffurniture.com',
                'type' => 'text',
                'label' => 'Support Email Address',
                'description' => 'Customer inquiries and order confirmation sender email.',
            ],
            [
                'group' => 'general',
                'key' => 'factory_address',
                'value' => 'UG41 Komersial Radia, Bukit Jelutong, 40150 Shah Alam, Selangor',
                'type' => 'textarea',
                'label' => 'Main Workshop & Factory Address',
                'description' => 'Physical headquarters and joinery facility location.',
            ],

            // Header & Announcement Bar
            [
                'group' => 'header',
                'key' => 'announcement_enabled',
                'value' => '1',
                'type' => 'boolean',
                'label' => 'Enable Announcement Bar',
                'description' => 'Toggle the top promotional announcement bar on or off.',
            ],
            [
                'group' => 'header',
                'key' => 'announcement_text_1',
                'value' => '5-Year Solid Wood Warranty on All Sofas',
                'type' => 'text',
                'label' => 'Announcement Item 1',
                'description' => 'First statement in top announcement bar.',
            ],
            [
                'group' => 'header',
                'key' => 'announcement_text_2',
                'value' => 'In-House Malaysian Manufacturer',
                'type' => 'text',
                'label' => 'Announcement Item 2',
                'description' => 'Second statement in top announcement bar.',
            ],
            [
                'group' => 'header',
                'key' => 'announcement_link_text',
                'value' => 'Visit Showrooms in Shah Alam & PJ →',
                'type' => 'text',
                'label' => 'Announcement Action Link Text',
                'description' => 'Clickable link text at the end of the announcement bar.',
            ],
            [
                'group' => 'header',
                'key' => 'announcement_link_url',
                'value' => '/our-showroom',
                'type' => 'text',
                'label' => 'Announcement Action Link URL',
                'description' => 'Target path or URL for the announcement action link.',
            ],
            [
                'group' => 'header',
                'key' => 'header_sticky',
                'value' => '1',
                'type' => 'boolean',
                'label' => 'Enable Sticky Header Navigation',
                'description' => 'Header remains visible at the top of the viewport when scrolling.',
            ],

            // Footer & Showroom Concierge
            [
                'group' => 'footer',
                'key' => 'footer_about_text',
                'value' => 'NMFFurniture Sdn Bhd is a leading in-house manufacturer of high-quality handcrafted sofas and bespoke living furniture in Malaysia. Every piece is built with a reinforced solid wood frame backed by our 5-Year Wood Warranty.',
                'type' => 'textarea',
                'label' => 'Footer About Description',
                'description' => 'Company craft overview displayed in the first footer column.',
            ],
            [
                'group' => 'footer',
                'key' => 'footer_showroom_title',
                'value' => 'Shah Alam Showroom',
                'type' => 'text',
                'label' => 'Footer Showroom Box Title',
                'description' => 'Title of the showroom contact column in the footer.',
            ],
            [
                'group' => 'footer',
                'key' => 'footer_showroom_address',
                'value' => '2nd Floor, Lobby 3, UG41 Komersial Radia, Persiaran Arked, Bukit Jelutong, 40150 Shah Alam, Selangor.',
                'type' => 'textarea',
                'label' => 'Footer Showroom Address',
                'description' => 'Full address shown in footer showroom box.',
            ],
            [
                'group' => 'footer',
                'key' => 'footer_showroom_hours',
                'value' => 'Tue – Sun: 10:30 AM – 7:30 PM',
                'type' => 'text',
                'label' => 'Footer Showroom Operating Hours',
                'description' => 'Hours displayed in the operating hours badge.',
            ],
            [
                'group' => 'footer',
                'key' => 'footer_whatsapp_btn_text',
                'value' => 'Chat with Showroom Concierge',
                'type' => 'text',
                'label' => 'Footer WhatsApp Button Text',
                'description' => 'Call to action text on the footer contact button.',
            ],
            [
                'group' => 'footer',
                'key' => 'footer_copyright_text',
                'value' => 'NMFFURNITURE SDN. BHD. All rights reserved.',
                'type' => 'text',
                'label' => 'Footer Copyright Statement',
                'description' => 'Copyright notice in the footer bottom bar.',
            ],
            [
                'group' => 'footer',
                'key' => 'footer_guarantee_badge',
                'value' => 'Handcrafted Luxury Sofas • Solid Wood Frame 5-Year Warranty • Made in Malaysia',
                'type' => 'text',
                'label' => 'Footer Guarantee Badge Line',
                'description' => 'Secondary trust line displayed at bottom of footer.',
            ],

            // Social Links
            [
                'group' => 'social',
                'key' => 'social_instagram',
                'value' => 'https://instagram.com/nmffurniture',
                'type' => 'text',
                'label' => 'Instagram URL',
                'description' => 'Direct link to official Instagram profile.',
            ],
            [
                'group' => 'social',
                'key' => 'social_facebook',
                'value' => 'https://facebook.com/nmffurniture',
                'type' => 'text',
                'label' => 'Facebook URL',
                'description' => 'Direct link to official Facebook page.',
            ],
            [
                'group' => 'social',
                'key' => 'social_tiktok',
                'value' => 'https://tiktok.com/@nmffurniture',
                'type' => 'text',
                'label' => 'TikTok URL',
                'description' => 'Direct link to official TikTok account.',
            ],

            // Home Page Dynamic Content
            [
                'group' => 'homepage',
                'key' => 'hero_eyebrow',
                'value' => 'BUKIT JELUTONG WORKSHOP • IN-HOUSE MANUFACTURING',
                'type' => 'text',
                'label' => 'Hero Eyebrow Text',
                'description' => 'Top uppercase subtitle above the hero heading.',
            ],
            [
                'group' => 'homepage',
                'key' => 'hero_title',
                'value' => 'Bespoke Sofas & Handcrafted Living Furniture',
                'type' => 'text',
                'label' => 'Hero Main Title (H1)',
                'description' => 'Primary headline on the homepage hero banner.',
            ],
            [
                'group' => 'homepage',
                'key' => 'hero_subtitle',
                'value' => 'Engineered with reinforced kiln-dried solid hardwood frames, pocket spring suspension, and over 200+ pet-friendly, water-repellent designer fabrics.',
                'type' => 'textarea',
                'label' => 'Hero Subtitle Paragraph',
                'description' => 'Descriptive summary beneath hero headline.',
            ],
            [
                'group' => 'homepage',
                'key' => 'hero_image_url',
                'value' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1920&q=80',
                'type' => 'image',
                'label' => 'Hero Background Image URL',
                'description' => 'High-resolution photography for hero section.',
            ],
            [
                'group' => 'homepage',
                'key' => 'hero_btn_primary_text',
                'value' => 'Explore Collections',
                'type' => 'text',
                'label' => 'Hero Primary Button Text',
                'description' => 'Gold primary button label.',
            ],
            [
                'group' => 'homepage',
                'key' => 'hero_btn_primary_link',
                'value' => '/shop',
                'type' => 'text',
                'label' => 'Hero Primary Button Target URL',
                'description' => 'Destination link for primary button.',
            ],
            [
                'group' => 'homepage',
                'key' => 'hero_btn_secondary_text',
                'value' => 'Visit Our Showrooms',
                'type' => 'text',
                'label' => 'Hero Secondary Button Text',
                'description' => 'Glassmorphic secondary button label.',
            ],
            [
                'group' => 'homepage',
                'key' => 'hero_btn_secondary_link',
                'value' => '/our-showroom',
                'type' => 'text',
                'label' => 'Hero Secondary Button Target URL',
                'description' => 'Destination link for secondary button.',
            ],

            // Hero Carousel Dynamic Settings & Realistic Slides
            [
                'group' => 'homepage',
                'key' => 'hero_carousel_enabled',
                'value' => '1',
                'type' => 'boolean',
                'label' => 'Enable Hero Carousel',
                'description' => 'Toggle between dynamic hero carousel slider and single static hero.',
            ],
            [
                'group' => 'homepage',
                'key' => 'hero_carousel_autoplay',
                'value' => '1',
                'type' => 'boolean',
                'label' => 'Carousel Autoplay',
                'description' => 'Automatically advance slides after interval.',
            ],
            [
                'group' => 'homepage',
                'key' => 'hero_carousel_interval',
                'value' => '6000',
                'type' => 'number',
                'label' => 'Carousel Slide Interval (ms)',
                'description' => 'Duration each slide is visible before rotating (default 6000ms).',
            ],
            [
                'group' => 'homepage',
                'key' => 'hero_carousel_height',
                'value' => 'standard',
                'type' => 'text',
                'label' => 'Carousel Standard Height Mode',
                'description' => 'Height preset: standard (calc(100vh - 130px)), compact (540px), or immersive (85vh).',
            ],
            [
                'group' => 'homepage',
                'key' => 'hero_carousel_transition',
                'value' => 'fade',
                'type' => 'text',
                'label' => 'Carousel Transition Effect',
                'description' => 'Slide animation style: fade or slide.',
            ],
            [
                'group' => 'homepage',
                'key' => 'hero_carousel_slides',
                'value' => json_encode([
                    [
                        'id' => 1,
                        'media_type' => 'image',
                        'media_url' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=2000&q=85',
                        'eyebrow' => 'BUKIT JELUTONG WORKSHOP • IN-HOUSE JOINERY',
                        'title' => 'Bespoke Sofas Engineered For A Lifetime',
                        'subtitle' => 'Handcrafted with 100% kiln-dried Malaysian solid Meranti hardwood frames, dual-tier pocket springs, and custom sizing to perfectly match your floorplan.',
                        'badge_text' => '★ 4.9/5 Verified Rating • 4,800+ Homes Furnished',
                        'button_mode' => 'double',
                        'btn_primary_text' => 'Explore Bespoke Living',
                        'btn_primary_link' => '/shop?category=sofa',
                        'btn_primary_style' => 'royal-gold',
                        'btn_secondary_text' => 'Visit Our Showrooms',
                        'btn_secondary_link' => '/our-showroom',
                        'btn_secondary_style' => 'glass',
                        'is_active' => true,
                    ],
                    [
                        'id' => 2,
                        'media_type' => 'image',
                        'media_url' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=2000&q=85',
                        'eyebrow' => 'PERFORMANCE TEXTILE INNOVATION • SPILL & CLAW PROOF',
                        'title' => '200+ Pet-Friendly & Stain-Resistant Fabrics',
                        'subtitle' => 'Experience luxury French bouclés, claw-resistant velvets, and hydrophobic weaves built for living rooms with energetic pets and growing children.',
                        'badge_text' => 'Complimentary Fabric Swatch Box Delivered Free',
                        'button_mode' => 'double',
                        'btn_primary_text' => 'Request Swatch Box',
                        'btn_primary_link' => 'https://wa.me/60192589920?text=Hello%20NMFFurniture%2C%20I%20would%20like%20to%20request%20your%20complimentary%20200%2B%20fabric%20swatch%20box.',
                        'btn_primary_style' => 'whatsapp',
                        'btn_secondary_text' => 'Browse Fabric Guide',
                        'btn_secondary_link' => '/shop',
                        'btn_secondary_style' => 'glass',
                        'is_active' => true,
                    ],
                    [
                        'id' => 3,
                        'media_type' => 'video',
                        'media_url' => 'https://assets.mixkit.co/videos/preview/mixkit-carpenter-measuring-a-piece-of-wood-in-a-workshop-43867-large.mp4',
                        'video_poster' => 'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?auto=format&fit=crop&w=2000&q=85',
                        'eyebrow' => '100% SOLID MERANTI HARDWOOD • ZERO PARTICLE BOARD',
                        'title' => '5-Year Structural Frame Warranty Guaranteed',
                        'subtitle' => 'Every timber joint is mortise-and-tenon reinforced by veteran Malaysian craftsmen. Factory-direct pricing with zero middleman markups.',
                        'badge_text' => 'Save Up To 40% Direct Factory Pricing',
                        'button_mode' => 'double',
                        'btn_primary_text' => 'Explore Factory Direct',
                        'btn_primary_link' => '/shop',
                        'btn_primary_style' => 'royal-gold',
                        'btn_secondary_text' => 'Our Joinery Heritage',
                        'btn_secondary_link' => '/about',
                        'btn_secondary_style' => 'wine',
                        'is_active' => true,
                    ],
                    [
                        'id' => 4,
                        'media_type' => 'image',
                        'media_url' => 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?auto=format&fit=crop&w=2000&q=85',
                        'eyebrow' => 'BESPOKE SIZING • RESIDENTIAL & VILLA FITOUTS',
                        'title' => 'Tailored To Your Living Room Floorplan',
                        'subtitle' => 'From Mont Kiara penthouses to Setia Eco Park villas, our master design concierge customises length, L-shape orientation, and cushion firmness.',
                        'badge_text' => 'Free 3D Layout & Dimension Consultation',
                        'button_mode' => 'single',
                        'btn_primary_text' => 'Book Showroom Consultation',
                        'btn_primary_link' => '/our-showroom',
                        'btn_primary_style' => 'royal-gold',
                        'btn_secondary_text' => '',
                        'btn_secondary_link' => '',
                        'btn_secondary_style' => 'glass',
                        'is_active' => true,
                    ],
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                'type' => 'json',
                'label' => 'Hero Carousel Slides JSON',
                'description' => 'JSON payload containing all slides, media, captions, and buttons.',
            ],

            // SEO & Tracking
            [
                'group' => 'seo',
                'key' => 'seo_meta_title',
                'value' => 'NMFFurniture | Handcrafted Modern Sofas & Living Furniture Malaysia',
                'type' => 'text',
                'label' => 'Default Browser Title Template',
                'description' => 'Primary title tag used by search engines.',
            ],
            [
                'group' => 'seo',
                'key' => 'seo_meta_description',
                'value' => 'Direct in-house Malaysian manufacturer of bespoke sofas, wing chairs, and luxury bedframes. 5-Year Solid Wood Warranty, 200+ pet-friendly fabrics. Visit our Shah Alam & PJ showrooms.',
                'type' => 'textarea',
                'label' => 'Default Meta Description',
                'description' => 'Google search snippet description.',
            ],
            [
                'group' => 'seo',
                'key' => 'seo_ga4_id',
                'value' => 'G-NMFFURNITURE',
                'type' => 'text',
                'label' => 'Google Analytics 4 Measurement ID',
                'description' => 'Format: G-XXXXXXXXXX for live visitor and e-commerce tracking.',
            ],
            [
                'group' => 'seo',
                'key' => 'seo_pixel_id',
                'value' => '',
                'type' => 'text',
                'label' => 'Meta / Facebook Pixel ID',
                'description' => 'Numeric Pixel ID for Instagram/Facebook ads optimization.',
            ],
        ];

        foreach ($settings as $s) {
            Setting::updateOrCreate(
                ['key' => $s['key']],
                $s
            );
        }

        // ==========================================
        // 2. Navigation Menus & Items
        // ==========================================
        
        // Menu 1: Header Main Menu
        $headerMenu = NavigationMenu::updateOrCreate(
            ['location' => 'header_main'],
            ['title' => 'Header Main Navigation', 'is_active' => true]
        );

        // Header Main Items
        $itemCategory = NavigationMenuItem::updateOrCreate(
            ['navigation_menu_id' => $headerMenu->id, 'title' => 'CATEGORY'],
            ['url' => '/shop', 'icon' => 'fa-solid fa-layer-group', 'sort_order' => 1, 'is_active' => true]
        );

        // Sub-items for Category Dropdown
        $subCategories = [
            ['title' => 'All Products', 'url' => '/shop', 'icon' => 'fa-solid fa-border-all', 'sort_order' => 1],
            ['title' => 'Sofas & Lounges', 'url' => '/shop?category=sofa', 'icon' => 'fa-solid fa-couch', 'sort_order' => 2],
            ['title' => 'Wing Chairs', 'url' => '/shop?category=wing-chair', 'icon' => 'fa-solid fa-chair', 'sort_order' => 3],
            ['title' => 'Coffee Tables', 'url' => '/shop?category=coffee-table', 'icon' => 'fa-solid fa-table', 'sort_order' => 4],
            ['title' => 'Bed Frames', 'url' => '/shop?category=bed-frame', 'icon' => 'fa-solid fa-bed', 'sort_order' => 5],
            ['title' => 'Dining Sets', 'url' => '/shop?category=dining-table', 'icon' => 'fa-solid fa-utensils', 'sort_order' => 6],
        ];

        foreach ($subCategories as $sub) {
            NavigationMenuItem::updateOrCreate(
                ['navigation_menu_id' => $headerMenu->id, 'parent_id' => $itemCategory->id, 'title' => $sub['title']],
                array_merge($sub, ['navigation_menu_id' => $headerMenu->id, 'parent_id' => $itemCategory->id, 'is_active' => true])
            );
        }

        NavigationMenuItem::updateOrCreate(
            ['navigation_menu_id' => $headerMenu->id, 'title' => 'OUR SHOWROOM'],
            ['url' => '/our-showroom', 'icon' => 'fa-solid fa-location-dot', 'sort_order' => 2, 'is_active' => true]
        );

        NavigationMenuItem::updateOrCreate(
            ['navigation_menu_id' => $headerMenu->id, 'title' => 'GALLERY'],
            ['url' => '/gallery', 'icon' => 'fa-solid fa-images', 'sort_order' => 3, 'is_active' => true]
        );

        NavigationMenuItem::updateOrCreate(
            ['navigation_menu_id' => $headerMenu->id, 'title' => 'TRACK ORDER'],
            ['url' => '/track-order', 'icon' => 'fa-solid fa-truck-fast', 'sort_order' => 4, 'is_active' => true]
        );

        // Menu 2: Footer Column 1 (Collections)
        $footerCol1 = NavigationMenu::updateOrCreate(
            ['location' => 'footer_col_1'],
            ['title' => 'Footer Col 1: Collections', 'is_active' => true]
        );

        $col1Items = [
            ['title' => 'Living Sofas', 'url' => '/shop?category=sofa', 'sort_order' => 1],
            ['title' => 'Wing Chairs', 'url' => '/shop?category=wing-chair', 'sort_order' => 2],
            ['title' => 'Coffee Tables', 'url' => '/shop?category=coffee-table', 'sort_order' => 3],
            ['title' => 'Luxury Bedframes', 'url' => '/shop?category=bed-frame', 'sort_order' => 4],
            ['title' => 'Dining Sets', 'url' => '/shop?category=dining-table', 'sort_order' => 5],
            ['title' => 'View All Products', 'url' => '/shop', 'sort_order' => 6],
        ];

        foreach ($col1Items as $item) {
            NavigationMenuItem::updateOrCreate(
                ['navigation_menu_id' => $footerCol1->id, 'title' => $item['title']],
                array_merge($item, ['navigation_menu_id' => $footerCol1->id, 'is_active' => true])
            );
        }

        // Menu 3: Footer Column 2 (Experience)
        $footerCol2 = NavigationMenu::updateOrCreate(
            ['location' => 'footer_col_2'],
            ['title' => 'Footer Col 2: Experience', 'is_active' => true]
        );

        $col2Items = [
            ['title' => 'Showroom Locations', 'url' => '/our-showroom', 'sort_order' => 1],
            ['title' => 'Project Lookbook', 'url' => '/gallery', 'sort_order' => 2],
            ['title' => 'Pet-Friendly Fabrics', 'url' => '/shop', 'sort_order' => 3],
            ['title' => 'Track Live Order', 'url' => '/track-order', 'sort_order' => 4],
            ['title' => 'Account & Past Orders', 'url' => '/account/orders', 'sort_order' => 5],
        ];

        foreach ($col2Items as $item) {
            NavigationMenuItem::updateOrCreate(
                ['navigation_menu_id' => $footerCol2->id, 'title' => $item['title']],
                array_merge($item, ['navigation_menu_id' => $footerCol2->id, 'is_active' => true])
            );
        }
    }
}

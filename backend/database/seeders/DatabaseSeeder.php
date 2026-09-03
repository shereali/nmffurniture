<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\GalleryItem;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Showroom;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Users
        User::firstOrCreate(
            ['email' => 'admin@nmffurniture.com'],
            [
                'name' => 'Admin NMF',
                'role' => 'admin',
                'password' => Hash::make('password123'),
                'phone' => '+60192589920',
                'address' => 'UG41 Komersial Radia, Bukit Jelutong',
                'city' => 'Shah Alam',
                'state' => 'Selangor',
                'postal_code' => '40150',
            ]
        );

        User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Sara Ahmad',
                'role' => 'customer',
                'password' => Hash::make('password123'),
                'phone' => '+60123456789',
                'address' => 'No 15, Jalan Eco Sanctuary 3/1',
                'city' => 'Telok Panglima Garang',
                'state' => 'Selangor',
                'postal_code' => '42500',
            ]
        );

        // 2. Create Categories & Hierarchy
        $catLiving = Category::create([
            'name' => 'Living',
            'slug' => 'living',
            'description' => 'Sophisticated sofas, loungers, and coffee tables handcrafted for modern Malaysian homes.',
            'image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=800&q=80',
            'is_featured' => true,
            'sort_order' => 1,
        ]);

        $catSofa = Category::create([
            'parent_id' => $catLiving->id,
            'name' => 'Sofa',
            'slug' => 'sofa',
            'description' => 'Custom handmade sofas with 5-year solid wood warranty and pet-friendly easy clean fabrics.',
            'image' => 'https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e?auto=format&fit=crop&w=800&q=80',
            'is_featured' => true,
            'sort_order' => 1,
        ]);

        $catWingChair = Category::create([
            'parent_id' => $catLiving->id,
            'name' => 'Wing Chair',
            'slug' => 'wing-chair',
            'description' => 'Signature highback wing chairs designed for ergonomic luxury reading and statement corners.',
            'image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=800&q=80',
            'is_featured' => true,
            'sort_order' => 2,
        ]);

        $catCoffeeTable = Category::create([
            'parent_id' => $catLiving->id,
            'name' => 'Coffee Table',
            'slug' => 'coffee-table',
            'description' => 'Minimalist fluted timber and sintered stone coffee tables.',
            'image' => 'https://images.unsplash.com/photo-1533090161767-e6ffed986c88?auto=format&fit=crop&w=800&q=80',
            'is_featured' => true,
            'sort_order' => 3,
        ]);

        $catArmchair = Category::create([
            'parent_id' => $catLiving->id,
            'name' => 'Armchair',
            'slug' => 'armchair',
            'description' => 'Cozy modern single armchairs in 200+ fabric choices.',
            'image' => 'https://images.unsplash.com/photo-1580481077195-c999335a968a?auto=format&fit=crop&w=800&q=80',
            'is_featured' => true,
            'sort_order' => 4,
        ]);

        $catBedroom = Category::create([
            'name' => 'Bedroom',
            'slug' => 'bedroom',
            'description' => 'Bespoke upholstered bedframes and bedroom benches for restorative sleep.',
            'image' => 'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?auto=format&fit=crop&w=800&q=80',
            'is_featured' => true,
            'sort_order' => 2,
        ]);

        $catBedframe = Category::create([
            'parent_id' => $catBedroom->id,
            'name' => 'Bed Frame',
            'slug' => 'bed-frame',
            'description' => 'Solid hardwood structure upholstered headboards in Queen & King sizes.',
            'image' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=800&q=80',
            'is_featured' => true,
            'sort_order' => 1,
        ]);

        $catBench = Category::create([
            'parent_id' => $catBedroom->id,
            'name' => 'Bench',
            'slug' => 'bench',
            'description' => 'Bed-end and entryway upholstered benches.',
            'image' => 'https://images.unsplash.com/photo-1540574163026-643ea20ade25?auto=format&fit=crop&w=800&q=80',
            'is_featured' => true,
            'sort_order' => 2,
        ]);

        $catDining = Category::create([
            'name' => 'Dining',
            'slug' => 'dining',
            'description' => 'Solid timber dining tables and comfort-cushioned chairs for memorable family gatherings.',
            'image' => 'https://images.unsplash.com/photo-1617806118233-18e1de247200?auto=format&fit=crop&w=800&q=80',
            'is_featured' => true,
            'sort_order' => 3,
        ]);

        $catDiningTable = Category::create([
            'parent_id' => $catDining->id,
            'name' => 'Dining Table',
            'slug' => 'dining-table',
            'description' => 'Solid Malaysian oak & sintered stone 6 to 8-seater dining tables.',
            'image' => 'https://images.unsplash.com/photo-1530018607912-eff2daa1bac4?auto=format&fit=crop&w=800&q=80',
            'is_featured' => true,
            'sort_order' => 1,
        ]);

        $catDiningChair = Category::create([
            'parent_id' => $catDining->id,
            'name' => 'Dining Chair',
            'slug' => 'dining-chair',
            'description' => 'Ergonomic upholstered dining chairs with stain-resistant fabric.',
            'image' => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?auto=format&fit=crop&w=800&q=80',
            'is_featured' => true,
            'sort_order' => 2,
        ]);

        // 3. Products Data
        $products = [
            [
                'category_id' => $catSofa->id,
                'name' => 'English Modern Channel 4 Seater (Premium)',
                'sku' => 'ENMOCH4-P',
                'price' => 3600.00,
                'discount_price' => 3399.00,
                'is_featured' => true,
                'short_description' => 'Signature channel-tufted 4-seater sofa crafted from kiln-dried solid Malaysian hardwood.',
                'description' => 'The English Modern Channel 4-Seater represents timeless British tailoring modernized for contemporary interiors. Built with a high-density resilience foam core, pocketed springs, and a reinforced solid wood chassis backed by our 5-Year Wood Warranty. Finished in our luxurious, ultra-soft textured velvet with over 200 custom shade options available upon request.',
                'dimensions' => 'W 260cm x D 95cm x H 85cm (Seat Height: 45cm)',
                'materials' => 'Solid Malaysian Hardwood Frame, Pocket Spring Suspension, High-Resilience Foam 45D, Water-repellent Velvet Fabric.',
                'images' => [
                    'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?auto=format&fit=crop&w=1200&q=80',
                ],
                'variants' => [
                    ['name' => 'Fabric Grade', 'option' => 'Premium Soft Velvet', 'price_adjustment' => 0, 'color_code' => '#C5A880', 'color_name' => 'Champagne Oat'],
                    ['name' => 'Fabric Grade', 'option' => 'Easy Clean Pet-Friendly', 'price_adjustment' => 400, 'color_code' => '#8C8D8A', 'color_name' => 'Mist Grey'],
                    ['name' => 'Fabric Grade', 'option' => 'Italian Microfiber Boucle', 'price_adjustment' => 650, 'color_code' => '#F4EBE1', 'color_name' => 'Cream Boucle'],
                ]
            ],
            [
                'category_id' => $catSofa->id,
                'name' => 'English Modern Channel 3 Seater (Easy Clean)',
                'sku' => 'ENMOCH3-E',
                'price' => 3500.00,
                'discount_price' => null,
                'is_featured' => true,
                'short_description' => '3-seater pet-friendly sofa equipped with water-resistant & scratch-resistant technology.',
                'description' => 'Engineered specifically for active family living and pet owners. Spills, coffee stains, and pet accidents wipe clean effortlessly with just a damp cloth. Features solid internal wood joints with 5-Year Wood Warranty.',
                'dimensions' => 'W 210cm x D 95cm x H 85cm (Seat Height: 45cm)',
                'materials' => 'Solid Meranti Hardwood, Dual Pocket Spring System, Nano EasyClean Stain-Resistant Fabric.',
                'images' => [
                    'https://images.unsplash.com/photo-1540574163026-643ea20ade25?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e?auto=format&fit=crop&w=1200&q=80',
                ],
                'variants' => [
                    ['name' => 'Color Choice', 'option' => 'Easy Clean Cloud Grey', 'price_adjustment' => 0, 'color_code' => '#D5D5D5', 'color_name' => 'Cloud Grey'],
                    ['name' => 'Color Choice', 'option' => 'Easy Clean Olive Moss', 'price_adjustment' => 0, 'color_code' => '#6A7059', 'color_name' => 'Olive Moss'],
                    ['name' => 'Color Choice', 'option' => 'Easy Clean Midnight Navy', 'price_adjustment' => 0, 'color_code' => '#21303E', 'color_name' => 'Midnight Navy'],
                ]
            ],
            [
                'category_id' => $catSofa->id,
                'name' => 'Elize Curve Organic Form Sofa',
                'sku' => 'ELIZECURV-3',
                'price' => 3450.00,
                'discount_price' => 3190.00,
                'is_featured' => true,
                'short_description' => 'Sculptural curved sofa bringing soothing fluidity and designer architectural presence.',
                'description' => 'The Elize Curve is sculpted to create conversation in open-concept living spaces. Smooth asymmetrical geometry coupled with high-density layered cushioning ensures supportive lounging from every angle.',
                'dimensions' => 'W 240cm x D 110cm x H 80cm',
                'materials' => 'Steam-bent solid hardwood inner frame, 50D resilience latex foam, premium Boucle weave.',
                'images' => [
                    'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1200&q=80',
                ],
                'variants' => [
                    ['name' => 'Texture', 'option' => 'Ivory Boucle', 'price_adjustment' => 0, 'color_code' => '#F8F6F0', 'color_name' => 'Ivory'],
                    ['name' => 'Texture', 'option' => 'Camel Chenille', 'price_adjustment' => 200, 'color_code' => '#C19A6B', 'color_name' => 'Camel'],
                ]
            ],
            [
                'category_id' => $catWingChair->id,
                'name' => 'Signature Highback Wing Chair',
                'sku' => 'WINGCH-01',
                'price' => 1450.00,
                'discount_price' => 1290.00,
                'is_featured' => true,
                'short_description' => 'Grand highback wing chair with lumbar support and solid wooden tapered legs.',
                'description' => 'An iconic reading chair blending regal silhouettes with supreme ergonomics. Perfect beside a fireplace, library shelf, or as a master bedroom accent piece.',
                'dimensions' => 'W 85cm x D 90cm x H 110cm',
                'materials' => 'Solid Oak Legs, High-grade velvet upholstery, pocketed seat coil.',
                'images' => [
                    'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1580481077195-c999335a968a?auto=format&fit=crop&w=1200&q=80',
                ],
                'variants' => [
                    ['name' => 'Color', 'option' => 'Royal Emerald', 'price_adjustment' => 0, 'color_code' => '#184E3A', 'color_name' => 'Emerald Green'],
                    ['name' => 'Color', 'option' => 'Mustard Gold', 'price_adjustment' => 0, 'color_code' => '#D4AF37', 'color_name' => 'Mustard Gold'],
                    ['name' => 'Color', 'option' => 'Charcoal Noir', 'price_adjustment' => 0, 'color_code' => '#2A2A2A', 'color_name' => 'Charcoal'],
                ]
            ],
            [
                'category_id' => $catCoffeeTable->id,
                'name' => 'Fluted Solid Wood & Sintered Stone Coffee Table',
                'sku' => 'CFTBL-FLUT-01',
                'price' => 950.00,
                'discount_price' => 880.00,
                'is_featured' => true,
                'short_description' => 'Dual tier coffee table featuring fluted oak base and heat-resistant marble sintered stone top.',
                'description' => 'Scratch-proof, heat-proof, and stain-resistant sintered stone tabletop paired with vertical fluted natural solid wood craftsmanship.',
                'dimensions' => 'Diameter 80cm x H 45cm',
                'materials' => 'Natural Malaysian Oak, 12mm Sintered Stone Top (Gloss/Matte).',
                'images' => [
                    'https://images.unsplash.com/photo-1533090161767-e6ffed986c88?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1530018607912-eff2daa1bac4?auto=format&fit=crop&w=1200&q=80',
                ],
                'variants' => [
                    ['name' => 'Top Finish', 'option' => 'Calacatta Gold White Marble', 'price_adjustment' => 0, 'color_code' => '#FAFAFA', 'color_name' => 'Calacatta White'],
                    ['name' => 'Top Finish', 'option' => 'Laurent Black Gold Vein', 'price_adjustment' => 80, 'color_code' => '#1A1A1A', 'color_name' => 'Laurent Black'],
                ]
            ],
            [
                'category_id' => $catBedframe->id,
                'name' => 'Adele Luxury Upholstered Bed Frame (King/Queen)',
                'sku' => 'BED-ADELE-01',
                'price' => 2600.00,
                'discount_price' => 2390.00,
                'is_featured' => true,
                'short_description' => 'Grand channel headboard bedframe with silent heavy-duty solid timber slat foundation.',
                'description' => 'The Adele Bedframe brings five-star luxury hotel elegance into your private sanctuary. Solid Malaysian hardwood frame designed for zero-creak durability with a 5-Year Wood Warranty.',
                'dimensions' => 'King: W 195cm x L 215cm x H 135cm | Queen: W 165cm x L 215cm x H 135cm',
                'materials' => 'Kiln-Dried Solid Hardwood structure, High-Density Padded Headboard, Velvet / Linen upholstery.',
                'images' => [
                    'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?auto=format&fit=crop&w=1200&q=80',
                ],
                'variants' => [
                    ['name' => 'Size', 'option' => 'Queen Size (5 Feet)', 'price_adjustment' => 0, 'color_code' => '#EAE6DF', 'color_name' => 'Warm Stone'],
                    ['name' => 'Size', 'option' => 'King Size (6 Feet)', 'price_adjustment' => 400, 'color_code' => '#EAE6DF', 'color_name' => 'Warm Stone'],
                ]
            ],
            [
                'category_id' => $catDiningTable->id,
                'name' => 'Solid Malaysian Oak 6-Seater Dining Table',
                'sku' => 'DINTBL-OAK-06',
                'price' => 2800.00,
                'discount_price' => 2590.00,
                'is_featured' => true,
                'short_description' => 'Solid natural oak dining table with rounded organic edges and chamfered architectural legs.',
                'description' => 'Crafted from sustainable Malaysian kiln-dried solid oak, treated with non-toxic matte protective sealant highlighting the natural wood grain rings.',
                'dimensions' => 'L 180cm x W 90cm x H 76cm',
                'materials' => '100% Solid Malaysian Oak Wood, matte organic oil finish.',
                'images' => [
                    'https://images.unsplash.com/photo-1617806118233-18e1de247200?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1530018607912-eff2daa1bac4?auto=format&fit=crop&w=1200&q=80',
                ],
                'variants' => [
                    ['name' => 'Wood Tone', 'option' => 'Natural Oak', 'price_adjustment' => 0, 'color_code' => '#C8B08B', 'color_name' => 'Natural Oak'],
                    ['name' => 'Wood Tone', 'option' => 'Smoked Walnut', 'price_adjustment' => 150, 'color_code' => '#5C4033', 'color_name' => 'Walnut'],
                ]
            ],
            [
                'category_id' => $catDiningChair->id,
                'name' => 'Ergonomic Upholstered Dining Chair',
                'sku' => 'DINCHR-ERGO-01',
                'price' => 420.00,
                'discount_price' => 380.00,
                'is_featured' => true,
                'short_description' => 'Curved back support dining chair with high-resilience foam and stain-proof fabric.',
                'description' => 'Comfortable enough for hours of dinner conversations. Features seamless upholstery and solid wooden legs matching our dining tables.',
                'dimensions' => 'W 52cm x D 56cm x H 82cm (Seat Height: 46cm)',
                'materials' => 'Solid Oak Legs, Stain-resistant woven fabric, moulded ergonomic backrest.',
                'images' => [
                    'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?auto=format&fit=crop&w=1200&q=80',
                ],
                'variants' => [
                    ['name' => 'Color', 'option' => 'Sand Beige', 'price_adjustment' => 0, 'color_code' => '#E6D7C3', 'color_name' => 'Sand'],
                    ['name' => 'Color', 'option' => 'Charcoal Grey', 'price_adjustment' => 0, 'color_code' => '#4A4A4A', 'color_name' => 'Charcoal'],
                ]
            ],
            [
                'category_id' => $catBench->id,
                'name' => 'Nordic Upholstered Bench',
                'sku' => 'BNCH-NORD-01',
                'price' => 650.00,
                'discount_price' => null,
                'is_featured' => false,
                'short_description' => 'Multifunctional entry and dining bench in soft chenille upholstery.',
                'description' => 'Versatile bench seating designed to complement dining arrangements or fit elegantly at the foot of your bedframe.',
                'dimensions' => 'L 130cm x D 42cm x H 46cm',
                'materials' => 'Solid hardwood frame, High-density cushion, Chenille fabric.',
                'images' => [
                    'https://images.unsplash.com/photo-1540574163026-643ea20ade25?auto=format&fit=crop&w=1200&q=80',
                ],
                'variants' => []
            ],
            [
                'category_id' => $catArmchair->id,
                'name' => 'Alma Curved Lounge Armchair',
                'sku' => 'ALMA-ARM-01',
                'price' => 1280.00,
                'discount_price' => 1150.00,
                'is_featured' => true,
                'short_description' => 'Cocooning curved accent armchair with 360-degree silent swivel base.',
                'description' => 'Sink into luxurious solitude. The Alma curved armchair envelopes you in cloud-like comfort with full 360-degree rotation.',
                'dimensions' => 'W 88cm x D 85cm x H 78cm',
                'materials' => 'Heavy-duty steel swivel bearing, solid wood frame, premium boucle.',
                'images' => [
                    'https://images.unsplash.com/photo-1580481077195-c999335a968a?auto=format&fit=crop&w=1200&q=80',
                ],
                'variants' => [
                    ['name' => 'Fabric', 'option' => 'Poodle Boucle Cream', 'price_adjustment' => 0, 'color_code' => '#F4EBE1', 'color_name' => 'Cream'],
                    ['name' => 'Fabric', 'option' => 'Mocca Velvet', 'price_adjustment' => 100, 'color_code' => '#8B5A2B', 'color_name' => 'Mocca'],
                ]
            ],
        ];

        foreach ($products as $pData) {
            $images = $pData['images'];
            $variants = $pData['variants'] ?? [];
            unset($pData['images'], $pData['variants']);

            $pData['slug'] = Str::slug($pData['name']);
            $product = Product::create($pData);

            foreach ($images as $idx => $img) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $img,
                    'is_primary' => $idx === 0,
                    'sort_order' => $idx,
                ]);
            }

            foreach ($variants as $v) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => $v['name'],
                    'option' => $v['option'],
                    'price_adjustment' => $v['price_adjustment'],
                    'color_code' => $v['color_code'] ?? null,
                    'color_name' => $v['color_name'] ?? null,
                ]);
            }
        }

        // 4. Showrooms Data
        Showroom::create([
            'name' => 'NMFFurniture Shah Alam Flagship Showroom',
            'branch' => 'Bukit Jelutong (Selangor)',
            'address' => '2nd Floor, Lobby 3, UG41 Komersial Radia, Persiaran Arked, Bukit Jelutong',
            'city' => 'Shah Alam',
            'state' => 'Selangor',
            'postal_code' => '40150',
            'phone' => '+603 7832 9920',
            'whatsapp_number' => '60192589920',
            'whatsapp_prefill_text' => 'Hye NMFFurniture Shah Alam, I would like to visit the showroom.',
            'map_embed_url' => 'https://maps.google.com/maps?q=Radia+Bukit+Jelutong+Shah+Alam&t=m&z=14&output=embed',
            'opening_hours' => 'Tue - Sun: 10:30 AM - 7:30 PM (Closed on Monday)',
            'image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=800&q=80',
            'is_active' => true,
        ]);

        Showroom::create([
            'name' => 'NMFFurniture Petaling Jaya Design Studio',
            'branch' => 'Seksyen 13 (Petaling Jaya)',
            'address' => 'No. 18, Jalan Universiti, Seksyen 13',
            'city' => 'Petaling Jaya',
            'state' => 'Selangor',
            'postal_code' => '46200',
            'phone' => '+603 7955 8810',
            'whatsapp_number' => '60192589921',
            'whatsapp_prefill_text' => 'Hye NMFFurniture PJ Studio, I would like to book an appointment.',
            'map_embed_url' => 'https://maps.google.com/maps?q=Seksyen+13+Petaling+Jaya&t=m&z=14&output=embed',
            'opening_hours' => 'Daily: 10:00 AM - 8:00 PM',
            'image' => 'https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e?auto=format&fit=crop&w=800&q=80',
            'is_active' => true,
        ]);

        Showroom::create([
            'name' => 'NMFFurniture Johor Bahru Gallery',
            'branch' => 'Taman Molek (Johor Bahru)',
            'address' => 'Lot 12, Jalan Molek 1/10, Taman Molek',
            'city' => 'Johor Bahru',
            'state' => 'Johor',
            'postal_code' => '81100',
            'phone' => '+607 355 4420',
            'whatsapp_number' => '60192589922',
            'whatsapp_prefill_text' => 'Hye NMFFurniture JB, I would like to check available floor stock.',
            'map_embed_url' => 'https://maps.google.com/maps?q=Taman+Molek+Johor+Bahru&t=m&z=14&output=embed',
            'opening_hours' => 'Daily: 11:00 AM - 8:00 PM',
            'image' => 'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?auto=format&fit=crop&w=800&q=80',
            'is_active' => true,
        ]);

        // 5. Gallery Items Data
        $gallery = [
            ['title' => 'Minimalist Living Room Installation', 'category' => 'living', 'image_url' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=800&q=80', 'description' => 'English Modern Channel in Champagne Velvet', 'client_name' => 'Bukit Jelutong, Shah Alam', 'is_featured' => true],
            ['title' => 'Scandinavian Neutral Sanctuary', 'category' => 'living', 'image_url' => 'https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e?auto=format&fit=crop&w=800&q=80', 'description' => 'Custom 3-Seater Sofa with Fluted Coffee Table', 'client_name' => 'Setia Alam, Selangor', 'is_featured' => true],
            ['title' => 'Master Bedroom Suite Oasis', 'category' => 'bedroom', 'image_url' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=800&q=80', 'description' => 'Adele King Size Upholstered Bedframe in Warm Taupe', 'client_name' => 'Ara Damansara, PJ', 'is_featured' => true],
            ['title' => 'Curved Sectional Living Installation', 'category' => 'living', 'image_url' => 'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?auto=format&fit=crop&w=800&q=80', 'description' => 'Bespoke Curved 4-Seater in French Boucle', 'client_name' => 'Mont Kiara Penthouse, KL', 'is_featured' => true],
            ['title' => 'Modern Organic Residence', 'category' => 'living', 'image_url' => 'https://images.unsplash.com/photo-1540574163026-643ea20ade25?auto=format&fit=crop&w=800&q=80', 'description' => 'Pet-Friendly Velvet Sectional with Solid Meranti Frame', 'client_name' => 'Tropicana Golf Villa, PJ', 'is_featured' => true],
            ['title' => 'Bukit Jelutong Showroom Main Floor', 'category' => 'showroom', 'image_url' => 'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?auto=format&fit=crop&w=800&q=80', 'description' => 'Experience texture swatches and foam firmness live', 'client_name' => 'Radia Bukit Jelutong', 'is_featured' => true],
            ['title' => 'Private Reading Lounge with Wing Chair', 'category' => 'living', 'image_url' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=800&q=80', 'description' => 'High-Back Emerald Velvet Armchair with Brass Legs', 'client_name' => 'Country Heights, Kajang', 'is_featured' => true],
            ['title' => 'Modern Dining Set with Oak Table', 'category' => 'dining', 'image_url' => 'https://images.unsplash.com/photo-1617806118233-18e1de247200?auto=format&fit=crop&w=800&q=80', 'description' => '6-Seater Malaysian Solid Oak table paired with dining chairs', 'client_name' => 'Setia Eco Park, Selangor', 'is_featured' => true],
        ];

        foreach ($gallery as $g) {
            GalleryItem::create($g);
        }
    }
}

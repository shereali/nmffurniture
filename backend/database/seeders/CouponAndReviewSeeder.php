<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Seeder;

class CouponAndReviewSeeder extends Seeder
{
    public function run(): void
    {
        Coupon::firstOrCreate(
            ['code' => 'WELCOME100'],
            [
                'type' => 'fixed',
                'value' => 100.00,
                'min_spend' => 1000.00,
                'usage_limit' => 500,
                'is_active' => true,
            ]
        );

        Coupon::firstOrCreate(
            ['code' => 'NMF10'],
            [
                'type' => 'percentage',
                'value' => 10.00,
                'min_spend' => 2000.00,
                'max_discount' => 500.00,
                'usage_limit' => 200,
                'is_active' => true,
            ]
        );

        Coupon::firstOrCreate(
            ['code' => 'RAYA50'],
            [
                'type' => 'fixed',
                'value' => 50.00,
                'min_spend' => 500.00,
                'usage_limit' => 1000,
                'is_active' => true,
            ]
        );

        $product = Product::first();
        if ($product) {
            Review::firstOrCreate(
                ['product_id' => $product->id, 'customer_name' => 'Datin Faridah Binti Hashim'],
                [
                    'rating' => 5,
                    'title' => 'Exceptional tailoring & 5-year solid frame warranty',
                    'comment' => 'We visited the Bukit Jelutong showroom and customized the English Modern Channel in champagne velvet. The delivery and installation crew were courteous and punctual. The frame feels exceptionally sturdy and the pocketed coils give supreme comfort.',
                    'image_url' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=600&q=80',
                    'is_verified' => true,
                    'is_approved' => true,
                ]
            );

            Review::firstOrCreate(
                ['product_id' => $product->id, 'customer_name' => 'Keith Tan & Melissa'],
                [
                    'rating' => 5,
                    'title' => 'Pet-friendly fabric is a game changer!',
                    'comment' => 'We have two golden retrievers. Spills and muddy paw prints literally wipe clean with just a damp microfiber cloth as promised. Best Malaysian furniture investment we have made.',
                    'image_url' => 'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?auto=format&fit=crop&w=600&q=80',
                    'is_verified' => true,
                    'is_approved' => true,
                ]
            );
        }
    }
}

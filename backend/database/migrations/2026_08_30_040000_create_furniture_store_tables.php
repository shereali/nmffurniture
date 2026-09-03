<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Extend users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('customer')->after('email'); // 'admin', 'customer'
            $table->string('phone')->nullable()->after('role');
            $table->text('address')->nullable()->after('phone');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('postal_code')->nullable()->after('state');
        });

        // Categories table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['parent_id', 'is_featured', 'sort_order']);
        });

        // Products table
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->integer('stock')->default(50);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('warranty_years')->default(5);
            $table->string('dimensions')->nullable();
            $table->text('materials')->nullable();
            $table->json('specifications')->nullable();
            $table->timestamps();

            $table->index(['category_id', 'is_featured', 'is_active', 'price']);
        });

        // Product Images table
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('image_url');
            $table->boolean('is_primary')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['product_id', 'is_primary']);
        });

        // Product Variants / Fabric / Materials table
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('name'); // e.g. "Fabric Type", "Color", "Size"
            $table->string('option'); // e.g. "Premium Velvet", "Easy Clean Pet-Friendly", "3 Seater"
            $table->decimal('price_adjustment', 10, 2)->default(0.00);
            $table->string('color_code')->nullable(); // e.g. "#A37A5C"
            $table->string('color_name')->nullable(); // e.g. "Warm Beige"
            $table->timestamps();

            $table->index(['product_id', 'name']);
        });

        // Orders table
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->text('shipping_address');
            $table->string('city');
            $table->string('state');
            $table->string('postal_code');
            $table->string('country')->default('Malaysia');
            $table->string('payment_method')->default('online_banking');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->enum('order_status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_fee', 10, 2)->default(0.00);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'order_status', 'payment_status', 'created_at']);
        });

        // Order Items table
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('product_name');
            $table->string('product_sku')->nullable();
            $table->string('variant_option')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->decimal('total', 10, 2);
            $table->timestamps();

            $table->index('order_id');
        });

        // Showrooms table
        Schema::create('showrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('branch');
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('postal_code');
            $table->string('phone')->nullable();
            $table->string('whatsapp_number');
            $table->string('whatsapp_prefill_text')->nullable();
            $table->text('map_embed_url')->nullable();
            $table->string('opening_hours')->default('Mon - Sun: 10:00 AM - 8:00 PM');
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Gallery Items table
        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->default('living'); // 'living', 'bedroom', 'showroom', 'celebrity'
            $table->string('image_url');
            $table->text('description')->nullable();
            $table->string('client_name')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index(['category', 'is_featured', 'sort_order']);
        });

        // Inquiries / Custom Lead table
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->enum('status', ['new', 'contacted', 'closed'])->default('new');
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiries');
        Schema::dropIfExists('gallery_items');
        Schema::dropIfExists('showrooms');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'address', 'city', 'state', 'postal_code']);
        });
    }
};

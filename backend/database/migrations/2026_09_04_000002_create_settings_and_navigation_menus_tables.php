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
        // 1. Settings Table
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group', 50)->default('general'); // 'general', 'header', 'footer', 'homepage', 'seo', 'social'
            $table->string('key', 100)->unique();
            $table->longText('value')->nullable();
            $table->string('type', 30)->default('text'); // 'text', 'textarea', 'boolean', 'image', 'json', 'number'
            $table->string('label', 150);
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();

            $table->index(['group', 'is_public']);
        });

        // 2. Navigation Menus Table
        Schema::create('navigation_menus', function (Blueprint $table) {
            $table->id();
            $table->string('location', 50)->unique(); // 'header_main', 'header_categories', 'footer_col_1', 'footer_col_2'
            $table->string('title', 100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Navigation Menu Items Table
        Schema::create('navigation_menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('navigation_menu_id')->constrained('navigation_menus')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('navigation_menu_items')->cascadeOnDelete();
            $table->string('title', 100);
            $table->string('url', 255);
            $table->string('icon', 100)->nullable();
            $table->string('badge', 50)->nullable();
            $table->string('target', 20)->default('_self');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['navigation_menu_id', 'sort_order', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('navigation_menu_items');
        Schema::dropIfExists('navigation_menus');
        Schema::dropIfExists('settings');
    }
};

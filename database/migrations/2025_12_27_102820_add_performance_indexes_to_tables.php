<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index('slug', 'idx_products_slug');
            $table->index(['category_id', 'is_active'], 'idx_products_category_active');
            $table->index(['is_featured', 'is_active'], 'idx_products_featured_active');
            $table->index('created_at', 'idx_products_created_at');

            // Full-text search index
            DB::statement('ALTER TABLE products ADD FULLTEXT INDEX ft_products_search (name, description)');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index('slug', 'idx_categories_slug');
            $table->index(['is_active', 'sort_order'], 'idx_categories_active_sort');

            // Full-text search index
            DB::statement('ALTER TABLE categories ADD FULLTEXT INDEX ft_categories_search (name, description)');
        });

        Schema::table('collections', function (Blueprint $table) {
            $table->index('slug', 'idx_collections_slug');
            $table->index(['is_active', 'is_featured'], 'idx_collections_active_featured');
            $table->index('sort_order', 'idx_collections_sort_order');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index(['user_id', 'status'], 'idx_orders_user_status');
            $table->index('order_number', 'idx_orders_number');
            $table->index('created_at', 'idx_orders_created_at');
            $table->index('status', 'idx_orders_status');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->index('order_id', 'idx_order_items_order');
            $table->index('product_id', 'idx_order_items_product');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->index(['cart_id', 'product_id'], 'idx_cart_items_cart_product');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->index(['product_id', 'is_approved'], 'idx_reviews_product_approved');
            $table->index('user_id', 'idx_reviews_user');
            $table->index('created_at', 'idx_reviews_created_at');
        });

        Schema::table('wishlists', function (Blueprint $table) {
            $table->index('user_id', 'idx_wishlists_user');
            $table->index('product_id', 'idx_wishlists_product');
        });

        Schema::table('inquiries', function (Blueprint $table) {
            $table->index('is_responded', 'idx_inquiries_responded');
            $table->index('created_at', 'idx_inquiries_created_at');
        });

        Schema::table('sliders', function (Blueprint $table) {
            $table->index(['is_active', 'sort_order'], 'idx_sliders_active_sort');
        });

        Schema::table('collection_product', function (Blueprint $table) {
            $table->index('collection_id', 'idx_collection_product_collection');
            $table->index('product_id', 'idx_collection_product_product');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_slug');
            $table->dropIndex('idx_products_category_active');
            $table->dropIndex('idx_products_featured_active');
            $table->dropIndex('idx_products_created_at');
        });
        DB::statement('ALTER TABLE products DROP INDEX ft_products_search');

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('idx_categories_slug');
            $table->dropIndex('idx_categories_active_sort');
        });
        DB::statement('ALTER TABLE categories DROP INDEX ft_categories_search');

        Schema::table('collections', function (Blueprint $table) {
            $table->dropIndex('idx_collections_slug');
            $table->dropIndex('idx_collections_active_featured');
            $table->dropIndex('idx_collections_sort_order');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_user_status');
            $table->dropIndex('idx_orders_number');
            $table->dropIndex('idx_orders_created_at');
            $table->dropIndex('idx_orders_status');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex('idx_order_items_order');
            $table->dropIndex('idx_order_items_product');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropIndex('idx_cart_items_cart_product');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('idx_reviews_product_approved');
            $table->dropIndex('idx_reviews_user');
            $table->dropIndex('idx_reviews_created_at');
        });

        Schema::table('wishlists', function (Blueprint $table) {
            $table->dropIndex('idx_wishlists_user');
            $table->dropIndex('idx_wishlists_product');
        });

        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropIndex('idx_inquiries_responded');
            $table->dropIndex('idx_inquiries_created_at');
        });

        Schema::table('sliders', function (Blueprint $table) {
            $table->dropIndex('idx_sliders_active_sort');
        });

        Schema::table('collection_product', function (Blueprint $table) {
            $table->dropIndex('idx_collection_product_collection');
            $table->dropIndex('idx_collection_product_product');
        });
    }
};

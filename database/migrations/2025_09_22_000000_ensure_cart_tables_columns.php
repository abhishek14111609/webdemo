<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Create carts table if not exists
        if (! Schema::hasTable('carts')) {
            Schema::create('carts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->decimal('subtotal', 10, 2)->default(0);
                $table->decimal('total', 10, 2)->default(0);
                $table->decimal('tax', 10, 2)->default(0);
                $table->decimal('discount', 10, 2)->default(0);
                $table->decimal('coupon_discount', 10, 2)->default(0);
                $table->decimal('shipping_cost', 10, 2)->default(0);
                $table->timestamps();
            });
        } else {
            // Ensure required columns exist
            Schema::table('carts', function (Blueprint $table) {
                if (! Schema::hasColumn('carts', 'subtotal')) {
                    $table->decimal('subtotal', 10, 2)->default(0)->after('user_id');
                }
                if (! Schema::hasColumn('carts', 'total')) {
                    $table->decimal('total', 10, 2)->default(0)->after('subtotal');
                }
                if (! Schema::hasColumn('carts', 'tax')) {
                    $table->decimal('tax', 10, 2)->default(0)->after('total');
                }
                if (! Schema::hasColumn('carts', 'discount')) {
                    $table->decimal('discount', 10, 2)->default(0)->after('tax');
                }
                if (! Schema::hasColumn('carts', 'coupon_discount')) {
                    $table->decimal('coupon_discount', 10, 2)->default(0)->after('discount');
                }
                if (! Schema::hasColumn('carts', 'shipping_cost')) {
                    $table->decimal('shipping_cost', 10, 2)->default(0)->after('coupon_discount');
                }
            });
        }

        // Create cart_items table if not exists
        if (! Schema::hasTable('cart_items')) {
            Schema::create('cart_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cart_id')->constrained('carts')->onDelete('cascade');
                $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
                $table->unsignedInteger('quantity');
                $table->decimal('price', 10, 2);
                $table->decimal('subtotal', 10, 2);
                $table->json('options')->nullable();
                $table->timestamps();
            });
        } else {
            // Ensure required columns exist
            Schema::table('cart_items', function (Blueprint $table) {
                if (! Schema::hasColumn('cart_items', 'quantity')) {
                    $table->unsignedInteger('quantity')->default(1)->after('product_id');
                }
                if (! Schema::hasColumn('cart_items', 'price')) {
                    $table->decimal('price', 10, 2)->default(0)->after('quantity');
                }
                if (! Schema::hasColumn('cart_items', 'subtotal')) {
                    $table->decimal('subtotal', 10, 2)->default(0)->after('price');
                }
                if (! Schema::hasColumn('cart_items', 'options')) {
                    $table->json('options')->nullable()->after('subtotal');
                }
            });
        }
    }

    public function down(): void
    {
        // We won't drop tables in down to avoid accidental data loss in production.
        // Optionally, you can uncomment these lines in development only.
        // Schema::dropIfExists('cart_items');
        // Schema::dropIfExists('carts');
    }
};

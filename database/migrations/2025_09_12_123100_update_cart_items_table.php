<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add missing columns to cart_items table
        Schema::table('cart_items', function (Blueprint $table) {
            if (!Schema::hasColumn('cart_items', 'price')) {
                $table->decimal('price', 10, 2)->after('quantity');
            }
            if (!Schema::hasColumn('cart_items', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->after('price');
            }
            if (!Schema::hasColumn('cart_items', 'options')) {
                $table->json('options')->nullable()->after('subtotal');
            }
        });
    }

    public function down()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $columns = ['price', 'subtotal', 'options'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('cart_items', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

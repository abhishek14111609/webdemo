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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'city')) {
                $table->string('city')->after('address');
            }
            if (!Schema::hasColumn('orders', 'state')) {
                $table->string('state')->after('city');
            }
            if (!Schema::hasColumn('orders', 'zip')) {
                $table->string('zip')->after('state');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['city', 'state', 'zip']);
        });
    }
};
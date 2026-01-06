<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->string('order_id')->nullable(); // Razorpay order ID
        $table->string('payment_id')->nullable(); // Razorpay payment ID
        $table->string('payment_status')->default('pending');
        $table->string('name');
        $table->string('email');
        $table->string('phone');
        $table->text('address');
        $table->string('country');
        $table->string('payment_method');
        $table->decimal('amount', 10, 2);
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
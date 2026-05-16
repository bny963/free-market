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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 購入者
            $table->foreignId('item_id')->constrained()->onDelete('cascade'); // 購入された商品
            $table->string('payment_method'); // 支払い方法 (card / stripe_convenience など)

            // アイテムごとに配送先を独立して紐付けるためのカラム
            $table->string('postal_code');
            $table->string('address');
            $table->string('building')->nullable();

            $table->string('stripe_session_id')->nullable(); // Stripe連携用
            $table->timestamps();
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

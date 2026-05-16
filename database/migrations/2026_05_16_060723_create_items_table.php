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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 出品者ID
            $table->string('name'); // 商品名
            $table->integer('price'); // 価格
            $table->string('brand')->nullable(); // ブランド名（「なし」はnull許容に）
            $table->text('description'); // 商品商品説明
            $table->string('img_url'); // 画像URL
            $table->string('condition'); // コンディション
            $table->boolean('is_sold')->default(false); // 購入済みフラグ
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};

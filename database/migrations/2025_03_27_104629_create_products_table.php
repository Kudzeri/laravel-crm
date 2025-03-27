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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable();
            $table->enum('type', ['simple', 'variable'])->default('simple');
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('on_sale')->default(false);
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('regular_price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->string('currency_code')->default('EUR');
            $table->integer('stock_quantity')->nullable();
            $table->boolean('is_in_stock')->default(true);
            $table->string('permalink')->nullable();
            $table->float('average_rating')->default(0);
            $table->unsignedInteger('review_count')->default(0);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

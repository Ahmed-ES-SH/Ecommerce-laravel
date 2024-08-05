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
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->decimal('discount', 8, 2);
            $table->json('images')->nullable(); // تخزين مصفوفة الصور كـ JSON
            $table->string('category')->nullable(); // التصنيف
            $table->integer('stock')->default(0); // الكمية المتوفرة
            $table->string('sku')->unique(); // رمز SKU
            $table->string('vendor_name')->nullable(); // اسم التاجر
            $table->unsignedBigInteger('vendor_id')->unsigned();
            $table->timestamps();
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade')->onUpdate('cascade');
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

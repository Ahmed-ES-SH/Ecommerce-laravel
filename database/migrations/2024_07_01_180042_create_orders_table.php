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
            $table->string('user_email');
            $table->string('adress');
            $table->string('order_status')->nullable();
            $table->json('order');
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade');
            $table->foreignId('vendor_id')->constrained()->onUpdate('cascade');
            $table->foreignId('category_id')->constrained()->onUpdate('cascade');
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

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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->text('vendor_name');
            $table->text('store_name');
            $table->text('vendor_email');
            $table->integer('vendor_phone');
            $table->text('storeurl')->nullable();
            $table->text('adress');
            $table->longText('storedescription');
            $table->longText('category');
            $table->longText('logo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};

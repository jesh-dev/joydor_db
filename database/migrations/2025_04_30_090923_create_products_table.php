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
            $table->string('product_name');
            $table->string('product_category');
            $table->text('product_desc')->nullable();
            $table->decimal('initial_price', 11, 2, true);
            $table->decimal('selling_price', 11, 2, true);
            $table->decimal('quantity', 11, 2, true)->default(0);
            $table->string('product_image');
            $table->foreignId('vendor_id')->nullable()->constrained
            ('users', 'id')->nullOnDelete();
            $table->softDeletes();
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

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
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id('ID_Reviews');
            $table->integer('ID_Products')->unsigned();
            $table->integer('ID_Customers')->unsigned();
            $table->integer('ID_Orders')->unsigned()->nullable();
            $table->tinyInteger('rating')->unsigned(); // 1-5 stars
            $table->string('title')->nullable();
            $table->text('review')->nullable();
            $table->boolean('is_verified_purchase')->default(false);
            $table->boolean('is_approved')->default(true);
            $table->timestamps();

            // Index for faster queries
            $table->index('ID_Products');
            $table->index('ID_Customers');
            $table->index('rating');
            $table->index(['ID_Products', 'is_approved']);

            // Prevent duplicate reviews per order
            $table->unique(['ID_Products', 'ID_Customers', 'ID_Orders'], 'unique_review_per_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};

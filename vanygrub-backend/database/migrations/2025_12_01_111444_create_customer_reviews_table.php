<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('vany_orders')->onDelete('cascade');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('photo_url');
            $table->text('review_text');
            $table->integer('rating')->default(5);
            $table->string('review_token')->unique();
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index(['is_approved', 'created_at']);
            $table->index('review_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_reviews');
    }
};

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
        Schema::table('customer_reviews', function (Blueprint $table) {
            // Make fields nullable for batch QR generation
            $table->foreignId('order_id')->nullable()->change();
            $table->string('customer_name')->nullable()->change();
            $table->string('customer_email')->nullable()->change();
            $table->string('photo_url')->nullable()->change();
            $table->text('review_text')->nullable()->change();
            $table->integer('rating')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_reviews', function (Blueprint $table) {
            // Revert to non-nullable (be careful with existing data)
            $table->foreignId('order_id')->nullable(false)->change();
            $table->string('customer_name')->nullable(false)->change();
            $table->string('customer_email')->nullable(false)->change();
            $table->string('photo_url')->nullable(false)->change();
            $table->text('review_text')->nullable(false)->change();
            $table->integer('rating')->default(5)->nullable(false)->change();
        });
    }
};

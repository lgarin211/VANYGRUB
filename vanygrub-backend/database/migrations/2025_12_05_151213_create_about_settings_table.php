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
        Schema::create('about_settings', function (Blueprint $table) {
            $table->id();
            $table->string('brand')->unique(); // vny, vanysongket, vanyvilla
            $table->string('title');
            $table->text('subtitle');
            $table->text('description');
            $table->json('hero_data')->nullable(); // Hero section data
            $table->json('history_data')->nullable(); // History section data
            $table->json('philosophy_data')->nullable(); // Philosophy/motif data
            $table->json('quality_data')->nullable(); // Quality & craftsmanship data
            $table->json('vision_mission_data')->nullable(); // Vision & mission data
            $table->json('cta_data')->nullable(); // Call to action data
            $table->json('contact_data')->nullable(); // Contact information
            $table->json('images')->nullable(); // Image paths
            $table->json('colors')->nullable(); // Brand colors
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_settings');
    }
};

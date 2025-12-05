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
        Schema::create('homepage_settings', function (Blueprint $table) {
            $table->id();
            $table->string('section_name')->unique(); // welcome, brands, values, portfolio
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);

            // Welcome Section
            $table->string('welcome_badge')->nullable();
            $table->string('welcome_title')->nullable();
            $table->string('welcome_tagline')->nullable();
            $table->text('welcome_description')->nullable();
            $table->string('welcome_image')->nullable();
            $table->string('highlight_1_number')->nullable();
            $table->string('highlight_1_text')->nullable();
            $table->string('highlight_2_number')->nullable();
            $table->string('highlight_2_text')->nullable();
            $table->string('welcome_button_text')->nullable();
            $table->string('welcome_button_link')->nullable();

            // Brand Section
            $table->string('brand_section_title')->nullable();
            $table->text('brand_section_description')->nullable();
            $table->string('brand_featured_title')->nullable();
            $table->text('brand_featured_description')->nullable();
            $table->string('brand_featured_image')->nullable();
            $table->string('brand_button_text')->nullable();
            $table->string('brand_button_link')->nullable();

            // Values Section (Quality & Heritage)
            $table->string('value_1_number')->nullable();
            $table->string('value_1_title')->nullable();
            $table->text('value_1_description')->nullable();
            $table->string('value_1_image')->nullable();
            $table->string('value_1_button_text')->nullable();
            $table->string('value_1_button_link')->nullable();

            $table->string('value_2_number')->nullable();
            $table->string('value_2_title')->nullable();
            $table->text('value_2_description')->nullable();
            $table->string('value_2_image')->nullable();
            $table->string('value_2_button_text')->nullable();
            $table->string('value_2_button_link')->nullable();

            // Portfolio Section
            $table->string('portfolio_title')->nullable();
            $table->text('portfolio_subtitle')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_settings');
    }
};

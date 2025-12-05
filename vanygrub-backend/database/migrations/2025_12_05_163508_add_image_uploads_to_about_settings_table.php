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
        Schema::table('about_settings', function (Blueprint $table) {
            $table->json('philosophy_images')->nullable()->after('philosophy_data');
            $table->string('hero_image')->nullable()->after('hero_data');
            $table->string('history_image')->nullable()->after('history_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('about_settings', function (Blueprint $table) {
            $table->dropColumn(['philosophy_images', 'hero_image', 'history_image']);
        });
    }
};

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
        Schema::table('vany_products', function (Blueprint $table) {
            $table->longText('detailed_description')->nullable()->after('description');
            $table->string('serial_number')->nullable()->after('sku');
            $table->json('colors')->nullable()->after('dimensions');
            $table->json('sizes')->nullable()->after('colors');
            $table->string('country_origin')->nullable()->after('sizes');
            $table->string('warranty')->nullable()->after('country_origin');
            $table->date('release_date')->nullable()->after('warranty');
            $table->string('main_image')->nullable()->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vany_products', function (Blueprint $table) {
            $table->dropColumn([
                'detailed_description',
                'serial_number',
                'colors',
                'sizes',
                'country_origin',
                'warranty',
                'release_date',
                'main_image'
            ]);
        });
    }
};

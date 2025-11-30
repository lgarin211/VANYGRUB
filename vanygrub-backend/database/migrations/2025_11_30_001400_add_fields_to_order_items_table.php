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
        Schema::table('vany_order_items', function (Blueprint $table) {
            $table->string('size')->nullable()->after('total');
            $table->string('color')->nullable()->after('size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vany_order_items', function (Blueprint $table) {
            $table->dropColumn(['size', 'color']);
        });
    }
};

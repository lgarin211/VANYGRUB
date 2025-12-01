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
        Schema::table('vany_cart_items', function (Blueprint $table) {
            // Add price columns only (other columns already exist)
            if (!Schema::hasColumn('vany_cart_items', 'unit_price')) {
                $table->decimal('unit_price', 10, 2)->nullable()->after('color');
            }
            if (!Schema::hasColumn('vany_cart_items', 'total_price')) {
                $table->decimal('total_price', 10, 2)->nullable()->after('unit_price');
            }

            // Make user_id nullable since we're using session-based cart too
            $table->unsignedBigInteger('user_id')->nullable()->change();

            // Add index for session_id for better performance if session_id exists
            if (Schema::hasColumn('vany_cart_items', 'session_id')) {
                $table->index(['session_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vany_cart_items', function (Blueprint $table) {
            if (Schema::hasColumn('vany_cart_items', 'unit_price')) {
                $table->dropColumn('unit_price');
            }
            if (Schema::hasColumn('vany_cart_items', 'total_price')) {
                $table->dropColumn('total_price');
            }
            if (Schema::hasColumn('vany_cart_items', 'session_id')) {
                $table->dropIndex(['session_id']);
            }
        });
    }
};

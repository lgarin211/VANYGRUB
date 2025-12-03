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
            // Add session_id column with sufficient length for Laravel sessions
            if (!Schema::hasColumn('vany_cart_items', 'session_id')) {
                $table->string('session_id', 500)->nullable()->after('user_id');
                $table->index('session_id');
            }

            // Add color and size columns if they don't exist
            if (!Schema::hasColumn('vany_cart_items', 'color')) {
                $table->string('color', 50)->nullable()->after('quantity');
            }
            if (!Schema::hasColumn('vany_cart_items', 'size')) {
                $table->string('size', 20)->nullable()->after('color');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vany_cart_items', function (Blueprint $table) {
            if (Schema::hasColumn('vany_cart_items', 'session_id')) {
                $table->dropIndex(['session_id']);
                $table->dropColumn('session_id');
            }
            if (Schema::hasColumn('vany_cart_items', 'color')) {
                $table->dropColumn('color');
            }
            if (Schema::hasColumn('vany_cart_items', 'size')) {
                $table->dropColumn('size');
            }
        });
    }
};

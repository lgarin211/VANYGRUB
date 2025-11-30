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
        Schema::table('vany_orders', function (Blueprint $table) {
            $table->string('session_id')->nullable()->after('user_id');
            $table->string('customer_name')->after('session_id');
            $table->string('customer_email')->after('customer_name');
            $table->string('customer_phone')->nullable()->after('customer_email');
            $table->string('shipping_city')->nullable()->after('shipping_address');
            $table->string('shipping_postal_code')->nullable()->after('shipping_city');
            $table->decimal('shipping_cost', 10, 2)->default(0)->after('notes');
            $table->decimal('tax_amount', 10, 2)->default(0)->after('shipping_cost');
            $table->string('order_status')->default('pending')->after('status');
            $table->string('tracking_number')->nullable()->after('order_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vany_orders', function (Blueprint $table) {
            $table->dropColumn([
                'session_id',
                'customer_name',
                'customer_email',
                'customer_phone',
                'shipping_city',
                'shipping_postal_code',
                'shipping_cost',
                'tax_amount',
                'order_status',
                'tracking_number'
            ]);
        });
    }
};

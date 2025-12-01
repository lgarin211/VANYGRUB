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
        Schema::create('site_configs', function (Blueprint $table) {
            $table->id();
            $table->string('group', 100); // meta, hero_section, colors, etc.
            $table->string('key', 100);   // title, description, primary, etc.
            $table->json('value');        // Config value (can be string, array, object)
            $table->enum('type', ['text', 'textarea', 'url', 'email', 'color', 'number', 'json', 'array'])->default('text');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['group', 'key']);
            $table->index(['group', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_configs');
    }
};

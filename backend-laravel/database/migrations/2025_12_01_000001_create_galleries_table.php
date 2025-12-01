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
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_url');
            $table->bigInteger('file_size'); // in bytes
            $table->string('mime_type');
            $table->enum('type', ['image', 'video', 'audio', 'document']);
            $table->string('category')->default('uncategorized');
            $table->string('alt_text')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'category']);
            $table->index(['is_featured', 'sort_order']);
            $table->index('created_at');

            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
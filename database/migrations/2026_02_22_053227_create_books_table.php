<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_ar')->nullable();
            $table->string('author')->nullable();
            $table->string('author_ar')->nullable();
            $table->text('description')->nullable();
            $table->text('description_ar')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('file_type', ['pdf', 'epub', 'doc', 'video', 'audio', 'external'])->default('pdf');
            $table->string('external_url')->nullable();
            $table->string('isbn')->nullable();
            $table->year('published_year')->nullable();
            $table->enum('language', ['arabic', 'english', 'bilingual'])->default('arabic');
            $table->json('tags')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('uploaded_by');
            $table->unsignedInteger('download_count')->default(0);
            $table->unsignedInteger('view_count')->default(0);
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};

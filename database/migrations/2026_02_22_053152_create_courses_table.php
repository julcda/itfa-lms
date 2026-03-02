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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_ar')->nullable();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('description_ar')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('teacher_id');
            $table->string('thumbnail')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->integer('duration_hours')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->integer('order')->default(0);
            $table->string('language', 5)->default('ar');
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};

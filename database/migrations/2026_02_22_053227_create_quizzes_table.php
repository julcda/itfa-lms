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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('lesson_id')->nullable();
            $table->string('title');
            $table->string('title_ar')->nullable();
            $table->text('description')->nullable();
            $table->integer('duration_minutes')->default(30);
            $table->integer('passing_score')->default(60);
            $table->integer('max_attempts')->default(3);
            $table->boolean('is_randomized')->default(false);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamps();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};

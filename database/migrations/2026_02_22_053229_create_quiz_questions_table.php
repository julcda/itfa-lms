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
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_id');
            $table->text('question_text');
            $table->text('question_text_ar')->nullable();
            $table->enum('type', ['multiple_choice', 'true_false', 'short_answer'])->default('multiple_choice');
            $table->json('options')->nullable();
            $table->text('correct_answer');
            $table->text('explanation')->nullable();
            $table->text('explanation_ar')->nullable();
            $table->integer('points')->default(1);
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};

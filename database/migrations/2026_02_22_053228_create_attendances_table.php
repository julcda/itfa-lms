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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('course_id');
            $table->date('session_date');
            $table->string('session_title')->nullable();
            $table->string('session_title_ar')->nullable();
            $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('absent');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('marked_by')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'course_id', 'session_date']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('marked_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};

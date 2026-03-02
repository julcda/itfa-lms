<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('grade_level')->nullable()->after('language');
            $table->string('learning_area')->nullable()->after('grade_level');
            $table->string('quarter', 2)->nullable()->after('learning_area');   // Q1-Q4
            $table->string('school_year', 20)->nullable()->after('quarter');    // e.g. 2025-2026
            $table->string('strand')->nullable()->after('school_year');         // SHS strand
            $table->string('subject_code', 60)->nullable()->after('strand');    // DepEd subject code
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['grade_level', 'learning_area', 'quarter', 'school_year', 'strand', 'subject_code']);
        });
    }
};

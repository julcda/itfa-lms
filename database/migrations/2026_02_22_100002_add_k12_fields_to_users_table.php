<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Learner Reference Number – 12-digit unique DepEd ID
            $table->string('lrn', 12)->nullable()->unique()->after('is_active');
            $table->string('grade_level')->nullable()->after('lrn');
            $table->string('section', 100)->nullable()->after('grade_level');
            $table->string('strand')->nullable()->after('section');
            $table->string('school_year', 20)->nullable()->after('strand');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['lrn', 'grade_level', 'section', 'strand', 'school_year']);
        });
    }
};

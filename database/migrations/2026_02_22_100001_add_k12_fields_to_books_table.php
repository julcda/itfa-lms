<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('grade_level')->nullable()->after('status');
            $table->string('learning_area')->nullable()->after('grade_level');
            $table->string('deped_code', 100)->nullable()->after('learning_area');
            $table->string('edition', 50)->nullable()->after('deped_code');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['grade_level', 'learning_area', 'deped_code', 'edition']);
        });
    }
};

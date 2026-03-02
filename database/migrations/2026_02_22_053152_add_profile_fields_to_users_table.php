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
        Schema::table('users', function (Blueprint $table) {
            $table->string('arabic_name')->nullable()->after('name');
            $table->string('avatar')->nullable()->after('arabic_name');
            $table->string('phone')->nullable()->after('avatar');
            $table->text('bio')->nullable()->after('phone');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('bio');
            $table->date('date_of_birth')->nullable()->after('gender');
            $table->string('locale', 5)->default('en')->after('date_of_birth');
            $table->boolean('is_active')->default(true)->after('locale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['arabic_name', 'avatar', 'phone', 'bio', 'gender', 'date_of_birth', 'locale', 'is_active']);
        });
    }
};

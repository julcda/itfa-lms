<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });

        // Seed default values
        $defaults = [
            ['key' => 'school_name',       'value' => 'Ibn Taimiyah Foundation Academy', 'group' => 'branding'],
            ['key' => 'school_name_ar',    'value' => 'أكاديمية مؤسسة ابن تيمية',          'group' => 'branding'],
            ['key' => 'school_short_name', 'value' => 'ITFA',                              'group' => 'branding'],
            ['key' => 'school_logo',       'value' => null,                                'group' => 'branding'],
            ['key' => 'school_tagline',    'value' => 'Learning Management System',        'group' => 'branding'],
            ['key' => 'school_tagline_ar', 'value' => 'نظام إدارة التعلم',                 'group' => 'branding'],
        ];

        foreach ($defaults as $row) {
            DB::table('settings')->insertOrIgnore($row);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

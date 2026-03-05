<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_collections', function (Blueprint $table) {
            $table->id();

            // Bilingual name
            $table->string('name');
            $table->string('name_ar')->nullable();
            $table->text('description')->nullable();
            $table->string('cover_color')->default('#10b981'); // hex color for visual identity

            // Hierarchy: null = root collection, set = sub-collection
            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('teacher_collections')
                  ->nullOnDelete();

            // Owner (teacher or admin)
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Metadata
            $table->boolean('is_private')->default(false);  // only visible to owner
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('icon')->nullable();             // emoji or icon name
            $table->timestamps();
        });

        // Pivot: collection ↔ material (with position ordering)
        Schema::create('teacher_collection_material', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_collection_id')->constrained('teacher_collections')->cascadeOnDelete();
            $table->foreignId('teacher_material_id')->constrained('teacher_materials')->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['teacher_collection_id', 'teacher_material_id'], 'tcm_collection_material_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_collection_material');
        Schema::dropIfExists('teacher_collections');
    }
};

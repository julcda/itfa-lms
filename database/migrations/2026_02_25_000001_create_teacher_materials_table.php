<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_materials', function (Blueprint $table) {
            $table->id();

            // Bilingual title & description
            $table->string('title');
            $table->string('title_ar')->nullable();
            $table->text('description')->nullable();
            $table->text('description_ar')->nullable();

            // Taxonomy
            $table->string('subject')->nullable();          // e.g. Mathematics, Science
            $table->string('grade_level')->nullable();      // e.g. Grade 7, All Grades
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();

            // Material classification
            $table->enum('material_type', [
                'pdf', 'ppt', 'video', 'audio', 'doc', 'spreadsheet', 'image', 'link', 'other'
            ])->default('pdf');

            // Files
            $table->string('cover_image')->nullable();
            $table->string('file_path')->nullable();
            $table->string('external_url')->nullable();
            $table->string('language')->default('english'); // english / arabic / bilingual / filipino

            // Metadata
            $table->json('tags')->nullable();
            $table->string('source')->nullable();           // author / publisher / source
            $table->year('published_year')->nullable();

            // Visibility & stats
            $table->enum('status', ['active', 'draft'])->default('draft');
            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('download_count')->default(0);

            // Audit
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_materials');
    }
};

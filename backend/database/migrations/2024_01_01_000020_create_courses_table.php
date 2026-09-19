<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('name_ar');
            $table->string('name_en');
            $table->text('desc_ar')->nullable();
            $table->text('desc_en')->nullable();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->decimal('price', 8, 2)->default(0);
            $table->string('level')->nullable();
            $table->string('audience')->nullable();
            $table->string('students_count')->nullable();
            $table->string('duration')->nullable();
            $table->string('sessions_per_week')->nullable();
            $table->string('instructor_ar')->nullable();
            $table->string('instructor_en')->nullable();
            $table->text('instructor_bio_ar')->nullable();
            $table->text('instructor_bio_en')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_juz_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('juz_number'); // 1–30
            $table->string('status')->default('not_started'); // not_started, in_progress, completed, reviewing
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'juz_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_juz_progress');
    }
};

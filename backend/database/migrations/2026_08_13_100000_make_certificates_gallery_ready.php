<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['course_id']);
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->foreignId('student_id')->nullable()->change();
            $table->foreignId('course_id')->nullable()->change();
            $table->boolean('is_published')->default(true)->after('status');
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->foreign('student_id')->references('id')->on('students')->nullOnDelete();
            $table->foreign('course_id')->references('id')->on('courses')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['course_id']);
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn('is_published');
            $table->foreignId('student_id')->nullable(false)->change();
            $table->foreignId('course_id')->nullable(false)->change();
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete();
            $table->foreign('course_id')->references('id')->on('courses')->cascadeOnDelete();
        });
    }
};

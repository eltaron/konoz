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
        Schema::table('student_juz_progress', function (Blueprint $table) {
            $table->date('started_at')->nullable()->after('notes');
            $table->date('target_review_at')->nullable()->after('started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_juz_progress', function (Blueprint $table) {
            $table->dropColumn(['started_at', 'target_review_at']);
        });
    }
};

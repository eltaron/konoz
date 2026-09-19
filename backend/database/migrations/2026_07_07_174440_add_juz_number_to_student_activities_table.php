<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_activities', function (Blueprint $table) {
            $table->tinyInteger('juz_number')->nullable()->after('activity_type');
        });
    }

    public function down(): void
    {
        Schema::table('student_activities', function (Blueprint $table) {
            $table->dropColumn('juz_number');
        });
    }
};

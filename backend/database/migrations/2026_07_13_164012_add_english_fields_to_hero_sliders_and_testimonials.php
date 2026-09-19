<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_sliders', function (Blueprint $table) {
            $table->string('title_en', 255)->nullable()->after('title_ar');
            $table->string('subtitle_en', 255)->nullable()->after('subtitle_ar');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->text('content_en')->nullable()->after('content_ar');
        });
    }

    public function down(): void
    {
        Schema::table('hero_sliders', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'subtitle_en']);
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn('content_en');
        });
    }
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('course_sessions', function (Blueprint $table) {
            $table->string('stream_url', 500)->nullable()->after('quiz_data');
        });
    }

    public function down()
    {
        Schema::table('course_sessions', function (Blueprint $table) {
            $table->dropColumn('stream_url');
        });
    }
};

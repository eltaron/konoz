<?php

use App\Models\Certificate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->string('verification_code', 32)->nullable()->unique()->after('is_published');
        });

        foreach (Certificate::whereNull('verification_code')->get() as $certificate) {
            $certificate->verification_code = Certificate::generateVerificationCode();
            $certificate->save();
        }
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropUnique(['verification_code']);
            $table->dropColumn('verification_code');
        });
    }
};
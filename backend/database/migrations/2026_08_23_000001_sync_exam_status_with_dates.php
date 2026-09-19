<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("UPDATE exams SET status = CASE WHEN date < CURDATE() THEN 'ended' ELSE 'current' END WHERE status IS NULL OR status NOT IN ('current','ended')");
    }

    public function down(): void
    {
        DB::statement("UPDATE exams SET status = CASE WHEN status = 'ended' THEN 'pending' ELSE 'upcoming' END");
    }
};

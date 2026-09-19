<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $socialKeys = [
        'social_facebook',
        'social_twitter',
        'social_instagram',
        'social_whatsapp',
        'social_telegram',
    ];

    public function up(): void
    {
        foreach ($this->socialKeys as $key) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                [
                    'value_ar' => '',
                    'value_en' => '',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', $this->socialKeys)->delete();
    }
};
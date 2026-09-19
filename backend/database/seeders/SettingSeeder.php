<?php
namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value_ar' => 'منصة كُنوز التعليمية', 'value_en' => 'Konoz Al-Quran'],
            ['key' => 'site_desc', 'value_ar' => 'منصة تعليمية متكاملة لتعليم القرآن الكريم والعلوم الشرعية', 'value_en' => 'An integrated educational platform for teaching the Holy Quran and Islamic sciences'],
            ['key' => 'site_keywords', 'value_ar' => 'تعليم القرآن، تحفيظ، علوم شرعية، كنوز', 'value_en' => 'Quran education, memorization, Islamic sciences, Konoz'],
            ['key' => 'contact_email', 'value_ar' => 'info@konoz.com', 'value_en' => 'info@konoz.com'],
            ['key' => 'contact_phone', 'value_ar' => '+201001234567', 'value_en' => '+201001234567'],
            ['key' => 'contact_address', 'value_ar' => 'مصر - القاهرة', 'value_en' => 'Cairo, Egypt'],
            ['key' => 'facebook_url', 'value_ar' => '', 'value_en' => ''],
            ['key' => 'twitter_url', 'value_ar' => '', 'value_en' => ''],
            ['key' => 'instagram_url', 'value_ar' => '', 'value_en' => ''],
            ['key' => 'youtube_url', 'value_ar' => '', 'value_en' => ''],
            ['key' => 'currency', 'value_ar' => 'ج.م', 'value_en' => 'EGP'],
            ['key' => 'timezone', 'value_ar' => 'Africa/Cairo', 'value_en' => 'Africa/Cairo'],
            ['key' => 'locale', 'value_ar' => 'ar', 'value_en' => 'ar'],
        ];
        foreach ($settings as $data) {
            Setting::updateOrCreate(['key' => $data['key']], $data);
        }
    }
}
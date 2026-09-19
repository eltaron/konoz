<?php
namespace Database\Seeders;

use App\Models\Course;
use App\Models\Session;
use Illuminate\Database\Seeder;

class SessionSeeder extends Seeder
{
    public function run(): void
    {
        $course = Course::where('slug', 'hifz-quran')->first();
        if (!$course) return;

        $sessions = [
            ['title_ar' => 'مراجعة سورة البقرة', 'title_en' => 'Surat Al-Baqarah Review', 'date' => '2026-06-15', 'time_from' => '08:00', 'time_to' => '09:30', 'status' => 'completed'],
            ['title_ar' => 'تفسير الآيات ١-٢٠', 'title_en' => 'Tafsir Verses 1-20', 'date' => '2026-06-10', 'time_from' => '08:00', 'time_to' => '09:30', 'status' => 'completed'],
            ['title_ar' => 'أحكام المدود', 'title_en' => 'Rules of Madd', 'date' => '2026-06-08', 'time_from' => '08:00', 'time_to' => '09:30', 'status' => 'completed'],
            ['title_ar' => 'مراجعة التجويد', 'title_en' => 'Tajweed Review', 'date' => '2026-06-03', 'time_from' => '08:00', 'time_to' => '09:30', 'status' => 'completed'],
            ['title_ar' => 'تصحيح التلاوة', 'title_en' => 'Recitation Correction', 'date' => '2026-06-01', 'time_from' => '08:00', 'time_to' => '09:30', 'status' => 'cancelled'],
        ];
        foreach ($sessions as $data) {
            $data['course_id'] = $course->id;
            Session::create($data);
        }
    }
}

<?php
namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseFreeSession;
use Illuminate\Database\Seeder;

class CourseFreeSessionSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::whereIn('slug', ['hifz-quran', 'ahkam-tajweed', 'qiraat-ten'])->get();
        $sessionsData = [
            'hifz-quran' => [
                ['ar' => 'مقدمة في حفظ القرآن', 'en' => 'Introduction to Quran Memorization', 'video' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'desc_ar' => 'تعرفي على منهجيتنا في تحفيظ القرآن', 'desc_en' => 'Learn about our methodology in Quran memorization'],
            ],
            'ahkam-tajweed' => [
                ['ar' => 'مقدمة في التجويد', 'en' => 'Introduction to Tajweed', 'video' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'desc_ar' => 'أساسيات علم التجويد', 'desc_en' => 'Basics of Tajweed science'],
            ],
            'qiraat-ten' => [
                ['ar' => 'مقدمة في القراءات', 'en' => 'Introduction to Qiraat', 'video' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'desc_ar' => 'نظرة عامة على علم القراءات', 'desc_en' => 'Overview of Qiraat science'],
            ],
        ];

        foreach ($courses as $course) {
            if (!isset($sessionsData[$course->slug])) continue;
            foreach ($sessionsData[$course->slug] as $s) {
                CourseFreeSession::create([
                    'course_id' => $course->id,
                    'title_ar' => $s['ar'],
                    'title_en' => $s['en'],
                    'video_url' => $s['video'],
                    'description_ar' => $s['desc_ar'],
                    'description_en' => $s['desc_en'],
                ]);
            }
        }
    }
}

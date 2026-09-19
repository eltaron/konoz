<?php
namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseLesson;
use Illuminate\Database\Seeder;

class CourseLessonSeeder extends Seeder
{
    public function run(): void
    {
        $lessonsData = [
            'hifz-quran' => [
                ['ar' => 'مقدمة في علوم القرآن', 'en' => 'Introduction to Quranic Sciences', 'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
                ['ar' => 'أحكام النون الساكنة والتنوين', 'en' => 'Rules of Noon Sakinah and Tanween', 'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
                ['ar' => 'المدود وأنواعها', 'en' => 'Madd (Elongation) and its Types', 'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
                ['ar' => 'الوقف والابتداء', 'en' => 'Stopping and Starting', 'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
                ['ar' => 'صفات الحروف', 'en' => 'Characteristics of Letters', 'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
                ['ar' => 'مراجعة واختبار شامل', 'en' => 'Review and Comprehensive Exam', 'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
            ],
            'ahkam-tajweed' => [
                ['ar' => 'مخارج الحروف', 'en' => 'Points of Articulation', 'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
                ['ar' => 'صفات الحروف', 'en' => 'Characteristics of Letters', 'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
                ['ar' => 'أحكام النون الساكنة', 'en' => 'Rules of Noon Sakinah', 'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
                ['ar' => 'أحكام الميم الساكنة', 'en' => 'Rules of Meem Sakinah', 'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
                ['ar' => 'المدود', 'en' => 'Madd', 'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
                ['ar' => 'التطبيق العملي', 'en' => 'Practical Application', 'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
            ],
        ];

        foreach ($lessonsData as $slug => $lessons) {
            $course = Course::where('slug', $slug)->first();
            if (!$course || $course->lessons()->exists()) continue;
            foreach ($lessons as $i => $lesson) {
                CourseLesson::create([
                    'course_id' => $course->id,
                    'name_ar' => $lesson['ar'],
                    'name_en' => $lesson['en'],
                    'link' => $lesson['link'],
                    'order' => $i + 1,
                ]);
            }
        }

        $generic = [
            ['ar' => 'الدرس الأول: التعريف بالدورة', 'en' => 'Lesson 1: Course Introduction'],
            ['ar' => 'الدرس الثاني: الأساسيات', 'en' => 'Lesson 2: The Basics'],
            ['ar' => 'الدرس الثالث: التدريب العملي', 'en' => 'Lesson 3: Practical Training'],
            ['ar' => 'الدرس الرابع: التطبيق والمراجعة', 'en' => 'Lesson 4: Application & Review'],
            ['ar' => 'الدرس الخامس: الاختبار النهائي', 'en' => 'Lesson 5: Final Exam'],
        ];

        foreach (Course::all() as $course) {
            if ($course->lessons()->exists()) continue;
            foreach ($generic as $i => $lesson) {
                CourseLesson::create([
                    'course_id' => $course->id,
                    'name_ar' => $lesson['ar'],
                    'name_en' => $lesson['en'],
                    'link' => 'https://www.youtube.com/watch?v=' . substr(md5($course->slug . $i), 0, 11),
                    'order' => $i + 1,
                ]);
            }
        }
    }
}

<?php
namespace Database\Seeders;

use App\Models\Course;
use App\Models\Exam;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        $course = Course::where('slug', 'hifz-quran')->first();
        if (!$course) return;

        $exams = [
            ['title_ar' => 'اختبار التجويد (١)', 'title_en' => 'Tajweed Exam 1', 'date' => '2026-06-10', 'total_students' => 8, 'avg_score' => 92],
            ['title_ar' => 'اختبار الحفظ (٢)', 'title_en' => 'Memorization Exam 2', 'date' => '2026-06-03', 'total_students' => 8, 'avg_score' => 88],
            ['title_ar' => 'اختبار التجويد (٣)', 'title_en' => 'Tajweed Exam 3', 'date' => '2026-05-27', 'total_students' => 8, 'avg_score' => 95],
            ['title_ar' => 'الاختبار النهائي', 'title_en' => 'Final Exam', 'date' => '2026-05-20', 'total_students' => 8, 'avg_score' => 90],
        ];
        foreach ($exams as $data) {
            $data['course_id'] = $course->id;
            Exam::create($data);
        }
    }
}

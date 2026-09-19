<?php
namespace Database\Seeders;

use App\Models\Exam;
use App\Models\ExamQuestion;
use Illuminate\Database\Seeder;

class ExamQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $exam = Exam::first();
        if (!$exam) return;

        $questions = [
            [
                'question_ar' => 'ما هو حكم النون الساكنة إذا جاء بعدها حرف الباء؟',
                'question_en' => 'What is the rule of Noon Sakinah when followed by the letter Baa?',
                'options' => ['الإظهار', 'الإدغام', 'الإقلاب', 'الإخفاء'],
                'correct_answer' => 'الإقلاب',
            ],
            [
                'question_ar' => 'كم أنواع المد؟',
                'question_en' => 'How many types of Madd are there?',
                'options' => ['نوعان', 'ثلاثة', 'أربعة', 'خمسة'],
                'correct_answer' => 'ثلاثة',
            ],
        ];
        foreach ($questions as $i => $q) {
            $q['exam_id'] = $exam->id;
            $q['order'] = $i + 1;
            ExamQuestion::create($q);
        }
    }
}

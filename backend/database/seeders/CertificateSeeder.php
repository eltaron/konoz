<?php
namespace Database\Seeders;

use App\Models\Student;
use App\Models\Course;
use App\Models\Certificate;
use Illuminate\Database\Seeder;

class CertificateSeeder extends Seeder
{
    public function run(): void
    {
        $course = Course::where('slug', 'hifz-quran')->first();
        $students = Student::limit(3)->get();
        if (!$course || $students->isEmpty()) return;

        $certificates = [
            ['student_id' => $students[0]->id, 'course_id' => $course->id, 'title_ar' => 'شهادة إتمام جزء عم', 'title_en' => 'Juz Amma Completion Certificate', 'issued_at' => '2026-06-10', 'status' => 'delivered'],
            ['student_id' => $students[1]->id, 'course_id' => $course->id, 'title_ar' => 'شهادة التجويد النظري', 'title_en' => 'Theoretical Tajweed Certificate', 'issued_at' => '2026-06-03', 'status' => 'delivered'],
        ];
        foreach ($certificates as $data) {
            Certificate::create($data);
        }

        $this->seedGallery();
    }

    /**
     * معرض صور الشهادات بالموقع — يعتمد على الصور القديمة داخل public/images/certificates
     */
    private function seedGallery(): void
    {
        $folders = [
            'أجازات جزرية' => 'إجازة الجزرية',
            'اجازات فتح الرحمن' => 'إجازة فتح الرحمن',
            'اجازات نور بيان' => 'إجازة نور البيان',
            'اطفال ختمواالقران الكريم كاملا' => 'ختم القرآن الكريم كاملاً',
            'العلوم الشرعية' => 'شهادة العلوم الشرعية',
            'جزء عم وتبارك' => 'جزء عم وتبارك',
            'دورة اعداد القارئ' => 'دورة إعداد القارئ',
            'دورة رحلتي اللي الله' => 'دورة رحلتي إلى الله',
            'دورة زهرات كنوز' => 'دورة زهرات كنوز',
            'شهادات اتمام دورات تجويد في دراسة كتاب تيسير الرحمن في تجويد القران' => 'شهادة تجويد — تيسير الرحمن',
            'شهادات اطفال' => 'شهادة أطفال',
            'شهادة المستوي الاول' => 'شهادة المستوى الأول',
        ];

        $base = public_path('images/certificates');
        $maxPerFolder = 3;

        foreach ($folders as $folder => $title) {
            $dir = $base . DIRECTORY_SEPARATOR . $folder;
            if (!is_dir($dir)) {
                continue;
            }
            $files = collect(glob($dir . DIRECTORY_SEPARATOR . '*.jpeg'))->sort();
            if ($files->isEmpty()) {
                continue;
            }
            $files->take($maxPerFolder)->each(function (string $file) use ($folder, $title) {
                $path = 'images/certificates/' . $folder . '/' . basename($file);
                Certificate::updateOrCreate(
                    ['image' => $path],
                    [
                        'student_id' => null,
                        'course_id' => null,
                        'title_ar' => $title,
                        'title_en' => null,
                        'issued_at' => null,
                        'status' => 'delivered',
                        'is_published' => true,
                    ]
                );
            });
        }
    }
}

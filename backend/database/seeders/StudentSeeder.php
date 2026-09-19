<?php
namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            ['name_ar' => 'سارة محمد الأحمد', 'name_en' => 'Sarah Mohammed Al-Ahmed', 'email' => 'sara@email.com', 'phone' => '0555555001', 'age' => 25, 'level' => 'متقدم', 'status' => 'active', 'joined_at' => '2026-02-01'],
            ['name_ar' => 'نورة أحمد السعيد', 'name_en' => 'Noura Ahmed Al-Saeed', 'email' => 'noura@email.com', 'phone' => '0555555002', 'age' => 28, 'level' => 'متوسط', 'status' => 'active', 'joined_at' => '2026-02-01'],
            ['name_ar' => 'مريم خالد العلي', 'name_en' => 'Maryam Khalid Al-Ali', 'email' => 'maryam@email.com', 'phone' => '0555555003', 'age' => 22, 'level' => 'متوسط', 'status' => 'active', 'joined_at' => '2026-02-05'],
            ['name_ar' => 'فاطمة حسن الزهراني', 'name_en' => 'Fatima Hassan Al-Zahrani', 'email' => 'fatima@email.com', 'phone' => '0555555004', 'age' => 30, 'level' => 'متقدم', 'status' => 'suspended', 'joined_at' => '2026-03-01'],
            ['name_ar' => 'أمل عبدالله القحطاني', 'name_en' => 'Amal Abdullah Al-Qahtani', 'email' => 'amal@email.com', 'phone' => '0555555005', 'age' => 26, 'level' => 'مبتدئ', 'status' => 'active', 'joined_at' => '2026-04-01'],
        ];
        foreach ($students as $data) {
            Student::create($data);
        }
    }
}

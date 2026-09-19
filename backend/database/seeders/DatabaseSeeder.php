<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            CourseSeeder::class,
            CourseLessonSeeder::class,
            CourseFreeSessionSeeder::class,
            StudentSeeder::class,
            SessionSeeder::class,
            ExamSeeder::class,
            ExamQuestionSeeder::class,
            CertificateSeeder::class,
            SettingSeeder::class,
            PostSeeder::class,
            SiteContentSeeder::class,
            HeroSliderSeeder::class,
            TestimonialSeeder::class,
            ContactSubmissionSeeder::class,
            SupportTicketSeeder::class,
        ]);
    }
}

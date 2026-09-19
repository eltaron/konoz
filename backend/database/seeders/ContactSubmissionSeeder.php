<?php

namespace Database\Seeders;

use App\Models\ContactSubmission;
use Illuminate\Database\Seeder;

class ContactSubmissionSeeder extends Seeder
{
    public function run(): void
    {
        ContactSubmission::create([
            'name' => 'أسماء محمد',
            'email' => 'asmaa@example.com',
            'phone' => '0555123456',
            'subject' => 'استفسار عن دورات التجويد',
            'message' => 'السلام عليكم، أود الاستفسار عن مواعيد دورات التجويد للمبتدئات وهل هناك حصص تجريبية مجانية؟',
            'is_read' => false,
        ]);

        ContactSubmission::create([
            'name' => 'نورهان علي',
            'email' => 'nourhan@example.com',
            'phone' => '0555987654',
            'subject' => 'التسجيل في دورة القراءات',
            'message' => 'أرغب في التسجيل في دورة القراءات العشر. هل هناك متطلبات معينة للقبول؟ وما هي رسوم الدورة؟',
            'is_read' => true,
        ]);

        ContactSubmission::create([
            'name' => 'سارة خالد',
            'email' => 'sara@example.com',
            'phone' => '0555111222',
            'subject' => 'اقتراح دورة جديدة',
            'message' => 'أقترح إضافة دورة متخصصة في تفسير القرآن الكريم للنساء مع منهج تفصيلي. وشكراً لكم على جهودكم.',
            'is_read' => false,
        ]);
    }
}

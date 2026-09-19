<?php

namespace Database\Seeders;

use App\Models\SupportTicket;
use Illuminate\Database\Seeder;

class SupportTicketSeeder extends Seeder
{
    public function run(): void
    {
        SupportTicket::create([
            'name' => 'مريم أحمد',
            'email' => 'maryam@example.com',
            'subject' => 'مشكلة في تسجيل الدخول',
            'message' => 'لا أستطيع تسجيل الدخول إلى حسابي. تظهر لي رسالة خطأ أن البريد الإلكتروني غير موجود. الرجاء المساعدة.',
            'status' => 'replied',
            'admin_reply' => 'وعليكم السلام ورحمة الله، تم حل المشكلة. برجاء محاولة تسجيل الدخول مرة أخرى باستخدام البريد الإلكتروني المسجل. إذا واجهت أي مشكلة أخرى، لا تتردي في التواصل معنا.',
            'replied_at' => now(),
        ]);

        SupportTicket::create([
            'name' => 'هاجر عمر',
            'email' => 'hajar@example.com',
            'subject' => 'استفسار عن شهادة الإجازة',
            'message' => 'أود الاستفسار عن كيفية الحصول على شهادة الإجازة في رواية حفص. هل هناك اختبارات معينة؟ وما هي المدة المتوقعة للحصول عليها؟',
            'status' => 'open',
            'admin_reply' => null,
            'replied_at' => null,
        ]);

        SupportTicket::create([
            'name' => 'لمى يوسف',
            'email' => 'lama@example.com',
            'subject' => 'تغيير موعد الحصة',
            'message' => 'أريد تغيير موعد الحصة الأسبوعية من يوم الأحد إلى يوم الثلاثاءdue لظروف العمل. هل هذا ممكن؟',
            'status' => 'closed',
            'admin_reply' => 'نعم يمكن تغيير الموعد. تم تعديل الجدول وسيظهر التغيير في لوحة التحكم. تم إعلام المعلمة بالتعديل.',
            'replied_at' => now()->subDays(2),
        ]);

        SupportTicket::create([
            'name' => 'رنا عبدالله',
            'email' => 'rana@example.com',
            'subject' => 'طلب إعادة تفعيل الحساب',
            'message' => 'تم إيقاف حسابي بسبب انقطاعي عن الحصص لمدة شهر بسبب السفر. أرجو إعادة تفعيل الحساب لأتمكن من متابعة الدورة.',
            'status' => 'open',
            'admin_reply' => null,
            'replied_at' => null,
        ]);
    }
}

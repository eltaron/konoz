<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        Testimonial::create([
            'student_name' => 'أسماء محمد',
            'content_ar' => 'الحمد لله الذي وفقني للالتحاق بمنصة منصة كُنوز التعليمية. بفضل الله ثم بفضل معلماتي المتميزات استطعت حفظ جزء عم في شهرين فقط. أسلوبهن رائع ومتابعة فردية ممتازة.',
            'rating' => 5,
            'is_published' => true,
        ]);

        Testimonial::create([
            'student_name' => 'نورة أحمد',
            'content_ar' => 'تجربتي مع منصة كُنوز التعليمية كانت أكثر من رائعة. المنهج المتكامل والمتابعة المستمرة ساعدت ابنتي على تحسين تلاوتها بشكل كبير. أنصح كل أم بهذه المنصة.',
            'rating' => 5,
            'is_published' => true,
        ]);

        Testimonial::create([
            'student_name' => 'سارة خالد',
            'content_ar' => 'كنت أعاني في تعلم التجويد ولكن مع معلمات منصة كُنوز التعليمية أصبحت أقرأ بشكل صحيح. جزاهن الله خيراً على صبرهن وتفانيهن في التعليم.',
            'rating' => 5,
            'is_published' => true,
        ]);

        Testimonial::create([
            'student_name' => 'مريم عبدالله',
            'content_ar' => 'المرونة في المواعيد هي ما جذبني لمنصة كُنوز التعليمية. كأم عاملة كنت أحتاج لجداول مرنة، والحمد لله وجدت ضالتي. جلسات المتابعة الفردية رائعة جداً.',
            'rating' => 4,
            'is_published' => true,
        ]);
    }
}

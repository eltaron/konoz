<?php

namespace Database\Seeders;

use App\Models\HeroSlider;
use Illuminate\Database\Seeder;

class HeroSliderSeeder extends Seeder
{
    public function run(): void
    {
        HeroSlider::create([
            'title_ar' => 'رحلتك مع القرآن تبدأ من هنا',
            'subtitle_ar' => 'منصة تعليمية متكاملة لتحفيظ القرآن الكريم وتجويده بأحدث الأساليب',
            'image' => null,
            'link' => route('departments'),
            'sort_order' => 1,
            'is_published' => true,
        ]);

        HeroSlider::create([
            'title_ar' => 'معلمات مجازات ومعتمدات',
            'subtitle_ar' => 'نخبة من المعلمات الحاصلات على إجازات في القرآن الكريم والقراءات',
            'image' => null,
            'link' => route('departments'),
            'sort_order' => 2,
            'is_published' => true,
        ]);

        HeroSlider::create([
            'title_ar' => 'انضمي إلينا الآن',
            'subtitle_ar' => 'ابدئي رحلتك القرآنية مع نخبة من المعلمات المجازات',
            'image' => null,
            'link' => route('register'),
            'sort_order' => 3,
            'is_published' => true,
        ]);
    }
}

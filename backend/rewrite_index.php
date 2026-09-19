<?php
// Read current file as base to preserve structure
$file = __DIR__ . '/resources/views/pages/public/index.blade.php';
$content = file_get_contents($file);

// UNDO ALL PREVIOUS TEXT REPLACEMENTS first (restore Arabic)
// Then fix encoding, then apply correct replacements

// Step 1: Undo all __() calls in the file (restore to original Arabic/Blade)
$undo_patterns = [
    // Remove __() calls for text that was replaced
    "/\{\{ ?__\('messages\.\w+'\) ?\}\}/u" => function($m) {
        $key = $m[1] ?? '';
        // Map back to original Arabic text based on key
        $map = [
            'messages.page_home' => 'الرئيسية',
            'messages.site_name' => 'منصة كُنوز التعليمية',
            'messages.hero_greeting' => 'اهلاً',
            'messages.hero_brand' => 'بمنصة كُنوز التعليمية',
            'messages.hero_headline_1' => 'هيا بنا نبدأ رحلة',
            'messages.hero_headline_2' => 'مع القرآن الكريم بالتجويد',
            'messages.hero_subtitle' => 'منصة تعليمية متكاملة للنساء والأطفال لتعليم القرآن الكريم.',
            'messages.hero_checklist_1' => 'أساتذة مجازات على أعلى مستوى',
            'messages.hero_checklist_2' => 'بيئة آمنة ومحترمة بالكامل',
            'messages.hero_checklist_3' => 'مواعيد مرنة تناسب جميع التوقيتات',
            'messages.nav_dashboard' => 'لوحة التحكم',
            'messages.nav_login_account' => 'دخول للحساب',
            'messages.stats_students_label' => 'طالبة مسجلة',
            'messages.stats_countries_label' => 'دولة حول العالم',
            'messages.stats_teachers_label' => 'معلمة مجازة',
            'messages.stats_satisfaction_label' => 'رضا العملاء',
            'messages.services_heading' => 'خدماتنا المميزة للجميع',
            'messages.services_subtitle_full' => 'منصة كُنوز التعليمية تقدم مجموعة متكاملة من الخدمات التعليمية المتميزة',
            'messages.services_badge' => 'تسجيل متاح الآن',
            'messages.services_card1_title' => 'الباقة: ابدأي التعلم',
            'messages.services_card1_desc' => 'انضمي إلى آلاف الطالبات حول العالم في رحلتك القرآنية مع أفضل المعلمات.',
            'messages.services_card2_title' => 'حلقات',
            'messages.services_card2_desc' => 'جلسات تفاعلية مباشرة مع معلمات مجازات متخصصات.',
            'messages.services_card3_title' => 'اللغات واللغويات',
            'messages.services_card3_desc' => 'تعليم اللغة العربية لغير الناطقين بها وبرامج تأسيس لغوي شاملة.',
            'messages.services_card4_title' => 'التكنولوجيا والبرمجة',
            'messages.services_card4_desc' => 'تطوير مهارات المستقبل للأطفال في بيئة تربوية محاكية.',
            'messages.services_card5_title' => 'الدعم التربوي',
            'messages.services_card5_desc' => 'جلسات استشارية للأمهات لتطوير أساليب التربية والتعامل.',
            'messages.details' => 'التفاصيل',
            'messages.quiz_heading' => 'اختاري مسارك التعليمي',
            'messages.quiz_subtitle' => 'أجيبي على 3 أسئلة بسيطة لاكتشاف المسار المناسب',
            'messages.quiz_question' => 'من سيكون الطالب؟',
            'messages.quiz_option1' => 'أنا (لسيدة بالغة)',
            'messages.quiz_option1_desc' => 'رحلة حفظ مكثفة للنساء',
            'messages.quiz_option2' => 'ولد / بنت',
            'messages.quiz_option2_desc' => 'برنامج تحفيظ مخصص للأطفال',
            'messages.back' => 'السابق',
            'messages.next_step' => 'الخطوة التالية',
            'messages.champions_heading' => 'نجوم منصة كُنوز التعليمية',
            'messages.champions_subtitle' => 'تعرفي على أوائل الطالبات المتميزات في هذا الشهر',
            'messages.champions_btn' => 'تصفحي لوحة الشرف كاملة',
            'messages.champion1_alt' => 'صورة كرم محمد',
            'messages.champion1_name' => 'كرم محمد',
            'messages.champion1_achievement' => 'حفظ 3 أجزاء',
            'messages.champion2_alt' => 'صورة سارة أحمد',
            'messages.champion2_name' => 'سارة أحمد',
            'messages.champion2_achievement' => 'البرنامج التأسيسي',
            'messages.champion3_alt' => 'صورة هنا محمد',
            'messages.champion3_name' => 'هنا محمد',
            'messages.champion3_achievement' => 'الإجازة القرآنية',
            'messages.champion4_alt' => 'صورة هند أحمد',
            'messages.champion4_name' => 'هند أحمد',
            'messages.champion4_achievement' => 'حلقة التجويد',
            'messages.why_us_heading' => 'لماذا تختارين منصة كُنوز التعليمية؟',
            'messages.why_us_subtitle' => 'لسنا مجرد منصة تعليمية عادية، بل مجتمع متكامل يجمع بين الجودة والخصوصية والمرونة.',
            'messages.why_us_feature1_title' => 'معلمات مجازات',
            'messages.why_us_feature1_desc' => 'تدريس على مستوى عالٍ من الجودة والإتقان.',
            'messages.why_us_feature2_title' => 'خصوصية كاملة',
            'messages.why_us_feature2_desc' => 'نوفر بيئة آمنة ومحترمة للجميع بعيداً عن الأعين.',
            'messages.why_us_card1_title' => 'خصوصية تامة',
            'messages.why_us_card1_desc' => 'بيئة آمنة بعيدة عن الأعين للنساء والأطفال.',
            'messages.why_us_card2_title' => 'متابعة فردية',
            'messages.why_us_card2_desc' => 'خطط تعليمية مخصصة تناسب مستوى كل طالبة.',
            'messages.why_us_card3_title' => 'إتقان وجودة',
            'messages.why_us_card3_desc' => 'أساتذة مجازات على أعلى مستوى من الكفاءة.',
            'messages.why_us_card4_title' => 'مرونة كاملة',
            'messages.why_us_card4_desc' => 'نعمل على مدار الساعة لتوفير أوقات مرنة تناسب الجميع.',
            'messages.calc_heading' => 'احسبي تكلفة اشتراكك',
            'messages.calc_subtitle' => 'اختاري الباقة المناسبة لتحصلي على السعر المثالي لك',
            'messages.calc_category' => 'الفئة',
            'messages.calc_option_kid' => 'طفل',
            'messages.calc_option_women' => 'نساء',
            'messages.calc_option_men' => 'رجال',
            'messages.calc_sessions' => 'عدد الحصص',
            'messages.calc_months' => 'عدد الأشهر الدراسية',
            'messages.calc_months_2' => '2 شهر',
            'messages.calc_months_3' => '3 شهر',
            'messages.calc_months_5' => '5 شهر (الأفضل)',
            'messages.calc_result' => 'الرسوم التقديرية الشهرية',
            'messages.calc_currency' => 'جنيه مصري',
            'messages.calc_disclaimer' => 'تقريباً حسب البيانات المدخلة',
            'messages.calc_promo' => 'وفري أكثر مع الباقات الطويلة!',
            'messages.payment_heading' => 'طرق الدفع المتاحة',
            'messages.payment_subtitle' => 'اختاري طريقة الدفع الأنسب لك',
            'messages.payment_method1' => 'فودافون كاش',
            'messages.payment_method2' => 'إنستا باي',
            'messages.payment_method3' => 'بطاقة ائتمان / بنك',
            'messages.payment_method3_desc' => 'الدفع عبر الإنترنت',
            'messages.cert_gallery_heading' => 'صور الشهادات',
            'messages.cert_gallery_subtitle' => 'تصفحي مجموعة من شهادات التخرج والتقدير',
            'messages.cert1' => 'شهادة تحفيظ أطفال',
            'messages.cert2' => 'شهادة إتمام حفظ',
            'messages.cert3' => 'شهادة تجويد عالية',
            'messages.cert4' => 'برنامج ختمة القرآن',
            'messages.cert5' => 'برامج تحفيظ متنوعة',
            'messages.cert6' => 'إجازة تيسير الرحمن',
            'messages.cert7' => 'دورة عن بُعد',
            'messages.cert8' => 'شهادة المستوى الأول',
            'messages.reviews_heading' => 'آراء الطالبات',
            'messages.reviews_subtitle' => 'تعرفي على تجربة الطالبات مع منصة كُنوز التعليمية',
            'messages.reviews_btn' => 'شاهد كل التقييمات',
            'messages.cta_badge' => 'عرض خاص لفترة محدودة',
            'messages.cta_title_part1' => 'هل أنت مستعدة لـ',
            'messages.cta_title_part2' => 'بدء رحلتك؟',
            'messages.cta_subtitle' => 'انضمي إلى آلاف الطالبات في رحلتهن القرآنية مع منصة كُنوز التعليمية',
            'messages.cta_register' => 'إنشاء حساب جديد',
            'messages.cta_whatsapp_part1' => 'تواصلي معنا عبر',
            'messages.cta_whatsapp_part2' => 'واتساب',
            'messages.close' => 'إغلاق',
            'messages.copy' => 'نسخ',
            'messages.payment_modal_disclaimer' => 'برجاء التحويل بعد التأكيد مع الإدارة',
            'messages.day' => 'يوم',
            'messages.week' => 'أسبوع',
            'messages.month' => 'شهر',
        ];
        // Extract key from match
        preg_match("/__\('messages\.(\w+)'\)/", $m[0], $keyMatch);
        if ($keyMatch && isset($map['messages.' . $keyMatch[1]])) {
            return $map['messages.' . $keyMatch[1]];
        }
        // Keep as-is if no mapping
        return $m[0];
    },
];

// Simpler approach: use str_replace to remove __() calls
$key_to_original = [
    // These will be replaced back to original Arabic
];

// Actually let's just go nuclear: rebuild the file from scratch
// using a more surgical approach - replace specific known lines

// For now, let me just undo the specific bad replacements
$bad_replacements = [
    '{{ __(\'messages.calc_option_women\') }}' => 'نساء',
    '{{ __(\'messages.day\') }}' => 'يوم',
    '{{ __(\'messages.month\') }}' => 'شهر',
    '{{ __(\'messages.hero_checklist_1\') }}' => 'أساتذة مجازات',
    '{{ __(\'messages.quiz_option2\') }}' => 'ولد / بنت',
    '{{ __(\'messages.why_us_feature1_title\') }}' => 'معلمات مجازات',
];
foreach ($bad_replacements as $search => $replace) {
    $content = str_replace($search, $replace, $content);
}

// Also fix the title line that got broken
// Line 3 was: @section('title', ' . __('messages.page_home') . ' | ' . __('messages.site_name') . ')
// Should be: @section('title', __('messages.page_home_title'))
$content = preg_replace(
    "/@section\('title',\s*'\.\s*__\('messages\.\w+'\)\s*\.\s*'\s*\|\s*'\s*\.\s*__\('messages\.\w+'\)\s*\.\s*'\)/",
    "@section('title', __('messages.page_home_title'))",
    $content
);

// Fix the broken meta desc
$content = preg_replace(
    "/@section\('meta_description',\s*'[^']*'\)/",
    "@section('meta_description', __('messages.site_meta_desc'))",
    $content
);

file_put_contents($file, $content);
echo "Fix applied!\n";

$lines = explode("\n", $content);
echo "Line 3: " . $lines[2] . "\n";
echo "Line 4: " . $lines[3] . "\n";
echo "Line 24: " . $lines[23] . "\n";
echo "Line 189: " . $lines[188] . "\n";
echo "Line 217: " . $lines[216] . "\n";

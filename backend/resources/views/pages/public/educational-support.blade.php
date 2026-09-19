@extends('layouts.public')

@section('title', __('messages.page_linguistics_title'))
@section('meta_description', __('messages.page_linguistics_meta'))
@section('meta_robots', 'index, follow')

@section('content')
    @include('pages.public.partials.program-page', [
        'contents' => $contents,
        'prefix' => 'linguistics',
        'heroIcon' => 'fa-solid fa-language',
        'heroImage' => 'hero-sliders/01KZTV3HR52QS93SAZCKRXJ6P7.png',
        'heroBadge' => 'برامج اللغات واللغويات',
        'titleText' => 'linguistics_title',
        'titleDefault' => 'برامج اللغات واللغويات',
        'introText' => 'linguistics_intro',
        'introDefault' => '',
        'introPoints' => [
            ['default' => 'تأسيس لغوي متكامل من الحروف إلى القراءة المستقلة'],
            ['default' => 'مناهج حديثة تناسب الناطقين بالعربية وغير الناطقين بها'],
            ['default' => 'متابعة فردية وتقارير دورية للأهالي'],
        ],
        'stats' => [
            ['key' => 'linguistics_stat1', 'default' => '+500 طالب', 'icon' => 'fa-solid fa-users'],
            ['key' => 'linguistics_stat2', 'default' => '+20 معلمة', 'icon' => 'fa-solid fa-user-graduate'],
            ['key' => 'linguistics_stat3', 'default' => '+1000 جلسة', 'icon' => 'fa-solid fa-chalkboard'],
        ],
        'axesText' => 'linguistics_axes_title',
        'axesDefault' => 'المحاور والخدمات الرئيسية',
        'axesLeadText' => 'linguistics_axes_lead',
        'axesLeadDefault' => 'أربعة محاور متكاملة تُبنى على بعضها لتحقيق التمكين اللغوي الكامل للطفل.',
        'axes' => [
            ['title' => 'linguistics_feat1_title', 'titleDefault' => 'التأسيس القرائي والكتابي', 'desc' => 'linguistics_feat1_desc', 'descDefault' => '', 'icon' => 'fa-solid fa-book-open-reader', 'html' => 'linguistics_feat1_items'],
            ['title' => 'linguistics_feat2_title', 'titleDefault' => 'برامج المحادثة والتحدث المكثفة', 'desc' => 'linguistics_feat2_desc', 'descDefault' => '', 'icon' => 'fa-solid fa-comments', 'html' => 'linguistics_feat2_items'],
            ['title' => 'linguistics_feat3_title', 'titleDefault' => 'تعليم اللغة العربية لغير الناطقين بها', 'desc' => 'linguistics_feat3_desc', 'descDefault' => '', 'icon' => 'fa-solid fa-globe', 'html' => 'linguistics_feat3_items'],
            ['title' => 'linguistics_feat4_title', 'titleDefault' => 'التقييم اللغوي والمتابعة المستمرة', 'desc' => 'linguistics_feat4_desc', 'descDefault' => '', 'icon' => 'fa-solid fa-clipboard-check', 'html' => 'linguistics_feat4_items'],
        ],
        'dividerIcon' => 'fa-solid fa-lightbulb',
        'whyTitle' => 'linguistics_why_title',
        'whyDefault' => 'لماذا تختار قسم اللغات واللغويات لدينا؟',
        'whys' => [
            ['title' => 'linguistics_why_1_title', 'titleDefault' => 'معلمات متخصصات', 'desc' => 'linguistics_why_1_desc', 'descDefault' => '', 'icon' => 'fa-solid fa-chalkboard-user'],
            ['title' => 'linguistics_why_2_title', 'titleDefault' => 'وسائل تعليمية تفاعلية', 'desc' => 'linguistics_why_2_desc', 'descDefault' => '', 'icon' => 'fa-solid fa-gamepad'],
            ['title' => 'linguistics_why_3_title', 'titleDefault' => 'خُطط فردية', 'desc' => 'linguistics_why_3_desc', 'descDefault' => '', 'icon' => 'fa-solid fa-route'],
        ],
    ])
@endsection
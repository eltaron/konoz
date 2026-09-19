@extends('layouts.public')

@section('title', __('messages.page_parenting_title'))
@section('meta_description', __('messages.page_parenting_meta'))
@section('meta_robots', 'index, follow')

@section('content')
    @include('pages.public.partials.program-page', [
        'contents' => $contents,
        'prefix' => 'parenting',
        'heroIcon' => 'fa-solid fa-hands-holding-child',
        'heroImage' => 'hero-sliders/01KZTVB1SRAGJ2M13MX6YZHEJF.png',
        'heroBadge' => 'الدعم التربوي والاستشارات',
        'titleText' => 'parenting_title',
        'titleDefault' => 'الدعم التربوي والاستشارات',
        'introText' => 'parenting_intro',
        'introDefault' => '',
        'introPoints' => [
            ['default' => 'استشارات تربوية فردية مع خبيرات ومختصات'],
            ['default' => 'خطط مخصصة حسب عمر الطفل وطبيعته النفسية'],
            ['default' => 'متابعة مستمرة وأدوات عملية تطبق في المنزل'],
        ],
        'stats' => [
            ['key' => 'parenting_stat1', 'default' => '+300 أسرة', 'icon' => 'fa-solid fa-people-roof'],
            ['key' => 'parenting_stat2', 'default' => '+15 خبيرات', 'icon' => 'fa-solid fa-user-graduate'],
            ['key' => 'parenting_stat3', 'default' => '+200 ورشة', 'icon' => 'fa-solid fa-chalkboard-user'],
        ],
        'axesText' => 'parenting_axes_title',
        'axesDefault' => 'المحاور والخدمات الرئيسية',
        'axesLeadText' => 'parenting_axes_lead',
        'axesLeadDefault' => 'أربعة محاور متكاملة تُبنى على بعضها لتحقيق التربية الواعية والشخصية المتوازنة.',
        'axes' => [
            ['title' => 'parenting_feat1_title', 'titleDefault' => 'جلسات الاستشارات التربوية الفردية', 'desc' => 'parenting_feat1_desc', 'descDefault' => '', 'icon' => 'fa-solid fa-user-doctor', 'html' => 'parenting_feat1_items'],
            ['title' => 'parenting_feat2_title', 'titleDefault' => 'برامج تعديل السلوك وتنمية المهارات', 'desc' => 'parenting_feat2_desc', 'descDefault' => '', 'icon' => 'fa-solid fa-hand-holding-heart', 'html' => 'parenting_feat2_items'],
            ['title' => 'parenting_feat3_title', 'titleDefault' => 'ورش العمل والمحاضرات التوعوية للأمهات', 'desc' => 'parenting_feat3_desc', 'descDefault' => '', 'icon' => 'fa-solid fa-chalkboard-user', 'html' => 'parenting_feat3_items'],
            ['title' => 'parenting_feat4_title', 'titleDefault' => 'خطط الرعاية الشاملة ومتابعة السلوك', 'desc' => 'parenting_feat4_desc', 'descDefault' => '', 'icon' => 'fa-solid fa-heart-pulse', 'html' => 'parenting_feat4_items'],
        ],
        'dividerIcon' => 'fa-solid fa-heart-circle-check',
        'whyTitle' => 'parenting_why_title',
        'whyDefault' => 'لماذا تختار قسم الدعم التربوي لدينا؟',
        'whys' => [
            ['title' => 'parenting_why_1_title', 'titleDefault' => 'خبيرات ومختصات تربويات', 'desc' => 'parenting_why_1_desc', 'descDefault' => '', 'icon' => 'fa-solid fa-graduation-cap'],
            ['title' => 'parenting_why_2_title', 'titleDefault' => 'حلول عملية وليست نظرية', 'desc' => 'parenting_why_2_desc', 'descDefault' => '', 'icon' => 'fa-solid fa-wrench'],
            ['title' => 'parenting_why_3_title', 'titleDefault' => 'سرية وخصوصية تامة', 'desc' => 'parenting_why_3_desc', 'descDefault' => '', 'icon' => 'fa-solid fa-shield-halved'],
        ],
    ])
@endsection
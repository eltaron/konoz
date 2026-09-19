@extends('layouts.public')

@section('title', __('messages.page_coding_title'))
@section('meta_description', __('messages.page_coding_meta'))
@section('meta_robots', 'index, follow')

@section('content')
    @include('pages.public.partials.program-page', [
        'contents' => $contents,
        'prefix' => 'coding',
        'heroIcon' => 'fa-solid fa-laptop-code',
        'heroImage' => 'courses/01KVK77FKH1CN73V2SV77WC4SA.png',
        'heroBadge' => 'التكنولوجيا والبرمجة',
        'titleText' => 'coding_title',
        'titleDefault' => 'التكنولوجيا والبرمجة',
        'introText' => 'coding_intro',
        'introDefault' => '',
        'introPoints' => [
            ['default' => 'تأسيس قوي في التفكير المنطقي والحاسوبي وحل المشكلات'],
            ['default' => 'مشاريع عملية حقيقية يبنيها الطفل ويعرضها بنفسه'],
            ['default' => 'مسارات متدرجة تناسب كل فئة عمرية من ٧ سنوات فأكثر'],
        ],
        'stats' => [
            ['key' => 'coding_stat1', 'default' => '+300 طالب', 'icon' => 'fa-solid fa-users'],
            ['key' => 'coding_stat2', 'default' => '+15 معلمة', 'icon' => 'fa-solid fa-user-graduate'],
            ['key' => 'coding_stat3', 'default' => '+500 جلسة', 'icon' => 'fa-solid fa-chalkboard'],
        ],
        'axesText' => 'coding_axes_title',
        'axesDefault' => 'المحاور والخدمات الرئيسية',
        'axesLeadText' => 'coding_axes_lead',
        'axesLeadDefault' => 'أربعة محاور متكاملة تأخذ طفلكِ من أول خطوة في البرمجة حتى إتقان بناء مشاريعه الخاصة.',
        'axes' => [
            ['title' => 'coding_feat1_title', 'titleDefault' => 'أساسيات البرمجة والتفكير الحاسوبي', 'desc' => 'coding_feat1_desc', 'descDefault' => '', 'icon' => 'fa-solid fa-shapes', 'html' => 'coding_feat1_items'],
            ['title' => 'coding_feat2_title', 'titleDefault' => 'برمجة الألعاب والتطبيقات', 'desc' => 'coding_feat2_desc', 'descDefault' => '', 'icon' => 'fa-solid fa-gamepad', 'html' => 'coding_feat2_items'],
            ['title' => 'coding_feat3_title', 'titleDefault' => 'مشاريع عملية وإبداعية', 'desc' => 'coding_feat3_desc', 'descDefault' => '', 'icon' => 'fa-solid fa-wand-magic-sparkles', 'html' => 'coding_feat3_items'],
            ['title' => 'coding_feat4_title', 'titleDefault' => 'بيئة تعليمية آمنة ومراقبة بالكامل', 'desc' => 'coding_feat4_desc', 'descDefault' => '', 'icon' => 'fa-solid fa-shield-halved', 'html' => 'coding_feat4_items'],
        ],
        'dividerIcon' => 'fa-solid fa-microchip',
        'whyTitle' => 'coding_why_title',
        'whyDefault' => 'لماذا تختار قسم التكنولوجيا والبرمجة لدينا؟',
        'whys' => [
            ['title' => 'coding_why_1_title', 'titleDefault' => 'معلمات متخصصات', 'desc' => 'coding_why_1_desc', 'descDefault' => '', 'icon' => 'fa-solid fa-chalkboard-user'],
            ['title' => 'coding_why_2_title', 'titleDefault' => 'مناهج تفاعلية حديثة', 'desc' => 'coding_why_2_desc', 'descDefault' => '', 'icon' => 'fa-solid fa-puzzle-piece'],
            ['title' => 'coding_why_3_title', 'titleDefault' => 'مسارات متدرجة حسب العمر', 'desc' => 'coding_why_3_desc', 'descDefault' => '', 'icon' => 'fa-solid fa-route'],
        ],
    ])
@endsection
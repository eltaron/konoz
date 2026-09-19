@extends('layouts.public')

@section('title', __('messages.page_terms_title', ['site_name' => __('messages.site_name')]))
@section('meta_description', __('messages.page_terms_meta'))

@section('content')
<div class="page-hero text-center" data-aos="fade-up">
  <div class="hero-orb hero-orb-1"></div>
  <div class="hero-orb hero-orb-2"></div>
  <div class="container">
    <div class="page-hero-icon"><i class="fa-solid fa-file-contract"></i></div>
    <h1 class="fw-bold">{{ __('messages.page_terms') }}</h1>
    <p>{{ session('locale') === 'en' ? 'Please read these terms carefully before using our platform' : 'يرجى قراءة هذه الشروط بعناية قبل استخدام منصتنا' }}</p>
  </div>
</div>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
        @if(session('locale') === 'en')
        <h4 class="fw-bold mb-4" style="color: #0F6D80;">1. Acceptance of Terms</h4>
        <p class="text-secondary mb-4">By registering and using KonoZ Educational Platform, you agree to be bound by these terms and conditions. If you do not agree with any part of these terms, you should not use our services.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">2. Registration & Account</h4>
        <p class="text-secondary mb-4">You must provide accurate and complete information when creating an account. You are responsible for maintaining the confidentiality of your login credentials and for all activities under your account.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">3. Platform Services</h4>
        <p class="text-secondary mb-4">KonoZ offers educational courses in Quran memorization, Tajweed, languages, and other fields. All services are provided "as is" and we reserve the right to modify or discontinue any service without prior notice.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">4. Payment & Fees</h4>
        <p class="text-secondary mb-4">Fees are clearly displayed before enrollment. All payments are non-refundable unless otherwise stated. We reserve the right to change fees with reasonable notice.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">5. Code of Conduct</h4>
        <p class="text-secondary mb-4">Users must maintain a respectful and professional attitude towards teachers and fellow students. Any inappropriate behavior may result in account suspension or termination.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">6. Intellectual Property</h4>
        <p class="text-secondary mb-4">All course materials, content, and resources provided on the platform are the intellectual property of KonoZ Educational Platform and may not be reproduced or distributed without written permission.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">7. Limitation of Liability</h4>
        <p class="text-secondary mb-4">KonoZ Educational Platform shall not be liable for any indirect, incidental, or consequential damages arising from the use or inability to use our services.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">8. Changes to Terms</h4>
        <p class="text-secondary mb-4">We reserve the right to update these terms at any time. Users will be notified of significant changes via email or platform notification.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">9. Contact</h4>
        <p class="text-secondary mb-0">For any questions regarding these terms, please contact us through our <a href="{{ route('contact') }}" style="color: #0F6D80;">contact page</a>.</p>
        @else
        <h4 class="fw-bold mb-4" style="color: #0F6D80;">١. قبول الشروط</h4>
        <p class="text-secondary mb-4">باستخدام منصة كُنوز التعليمية، فإنك توافق على الالتزام بهذه الشروط والأحكام. إذا كنت لا توافق على أي جزء من هذه الشروط، يجب عليك عدم استخدام خدماتنا.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">٢. التسجيل والحساب</h4>
        <p class="text-secondary mb-4">يجب عليك تقديم معلومات دقيقة وكاملة عند إنشاء الحساب. أنت المسؤول عن الحفاظ على سرية بيانات الدخول الخاصة بك وعن جميع الأنشطة التي تتم تحت حسابك.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">٣. خدمات المنصة</h4>
        <p class="text-secondary mb-4">تقدم كُنوز دورات تعليمية في تحفيظ القرآن والتجويد واللغات وغيرها. جميع الخدمات تقدم "كما هي" ونحتفظ بالحق في تعديل أو إيقاف أي خدمة دون إشعار مسبق.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">٤. الدفع والرسوم</h4>
        <p class="text-secondary mb-4">يتم عرض الرسوم بوضوح قبل التسجيل. جميع المدفوعات غير قابلة للاسترداد ما لم ينص على خلاف ذلك. نحتفظ بالحق في تغيير الرسوم مع إشعار معقول.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">٥. قواعد السلوك</h4>
        <p class="text-secondary mb-4">يجب على المستخدمين الحفاظ على موقف محترم ومهني تجاه المعلمات والطالبات الزميلات. أي سلوك غير لائق قد يؤدي إلى تعليق الحساب أو إنهائه.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">٦. الملكية الفكرية</h4>
        <p class="text-secondary mb-4">جميع المواد التعليمية والمحتوى والموارد المقدمة على المنصة هي ملكية فكرية لمنصة كُنوز التعليمية ولا يجوز إعادة إنتاجها أو توزيعها دون إذن كتابي.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">٧. حدود المسؤولية</h4>
        <p class="text-secondary mb-4">منصة كُنوز التعليمية غير مسؤولة عن أي أضرار غير مباشرة أو عرضية أو تبعية تنشأ عن استخدام أو عدم القدرة على استخدام خدماتنا.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">٨. تغيير الشروط</h4>
        <p class="text-secondary mb-4">نحتفظ بالحق في تحديث هذه الشروط في أي وقت. سيتم إشعار المستخدمين بالتغييرات الهامة عبر البريد الإلكتروني أو إشعار المنصة.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">٩. الاتصال بنا</h4>
        <p class="text-secondary mb-0">لأي استفسارات بخصوص هذه الشروط، يرجى التواصل معنا عبر <a href="{{ route('contact') }}" style="color: #0F6D80;">صفحة الاتصال</a>.</p>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

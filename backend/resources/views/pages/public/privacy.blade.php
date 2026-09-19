@extends('layouts.public')

@section('title', __('messages.page_privacy_title', ['site_name' => __('messages.site_name')]))
@section('meta_description', __('messages.page_privacy_meta'))

@section('content')
<div class="page-hero text-center" data-aos="fade-up">
  <div class="hero-orb hero-orb-1"></div>
  <div class="hero-orb hero-orb-2"></div>
  <div class="container">
    <div class="page-hero-icon"><i class="fa-solid fa-shield-halved"></i></div>
    <h1 class="fw-bold">{{ __('messages.page_privacy') }}</h1>
    <p>{{ session('locale') === 'en' ? 'How we protect and handle your personal data' : 'كيف نحمي ونتعامل مع بياناتك الشخصية' }}</p>
  </div>
</div>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
        @if(session('locale') === 'en')
        <h4 class="fw-bold mb-4" style="color: #0F6D80;">1. Information We Collect</h4>
        <p class="text-secondary mb-4">We collect personal information such as your name, email address, phone number, and payment details when you register or use our services. We also collect usage data to improve your experience.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">2. How We Use Your Information</h4>
        <p class="text-secondary mb-4">Your information is used to provide and improve our educational services, process payments, send updates and notifications, and communicate with you regarding your account and courses.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">3. Data Protection</h4>
        <p class="text-secondary mb-4">We implement appropriate security measures to protect your personal data from unauthorized access, alteration, disclosure, or destruction. All payment transactions are encrypted.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">4. Information Sharing</h4>
        <p class="text-secondary mb-4">We do not sell, trade, or rent your personal information to third parties. We may share data with trusted service providers who assist in operating our platform, subject to confidentiality agreements.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">5. Your Rights</h4>
        <p class="text-secondary mb-4">You have the right to access, correct, or delete your personal data at any time. You can manage your data through your account settings or by contacting us directly.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">6. Cookies</h4>
        <p class="text-secondary mb-4">We use cookies and similar tracking technologies to enhance your browsing experience, analyze site traffic, and personalize content. You can control cookie preferences through your browser settings.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">7. Changes to This Policy</h4>
        <p class="text-secondary mb-4">We may update this privacy policy from time to time. We will notify you of any changes by posting the new policy on this page and updating the effective date.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">8. Contact Us</h4>
        <p class="text-secondary mb-0">If you have any questions about this privacy policy, please <a href="{{ route('contact') }}" style="color: #0F6D80;">contact us</a>.</p>
        @else
        <h4 class="fw-bold mb-4" style="color: #0F6D80;">١. المعلومات التي نجمعها</h4>
        <p class="text-secondary mb-4">نجمع المعلومات الشخصية مثل اسمك وبريدك الإلكتروني ورقم هاتفك وتفاصيل الدفع عند التسجيل أو استخدام خدماتنا. كما نجمع بيانات الاستخدام لتحسين تجربتك.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">٢. كيفية استخدام معلوماتك</h4>
        <p class="text-secondary mb-4">تُستخدم معلوماتك لتقديم وتحسين خدماتنا التعليمية ومعالجة المدفوعات وإرسال التحديثات والإشعارات والتواصل معك بخصوص حسابك ودوراتك.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">٣. حماية البيانات</h4>
        <p class="text-secondary mb-4">نطبق إجراءات أمنية مناسبة لحماية بياناتك الشخصية من الوصول غير المصرح به أو التعديل أو الإفصاح أو الإتلاف. جميع معاملات الدفع مشفرة.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">٤. مشاركة المعلومات</h4>
        <p class="text-secondary mb-4">لا نبيع أو نتبادل أو نؤجر معلوماتك الشخصية لأطراف ثالثة. قد نشارك البيانات مع مقدمي الخدمات الموثوق بهم الذين يساعدون في تشغيل منصتنا، مع الالتزام باتفاقيات السرية.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">٥. حقوقك</h4>
        <p class="text-secondary mb-4">لديك الحق في الوصول إلى بياناتك الشخصية أو تصحيحها أو حذفها في أي وقت. يمكنك إدارة بياناتك من خلال إعدادات حسابك أو بالاتصال بنا مباشرة.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">٦. ملفات تعريف الارتباط</h4>
        <p class="text-secondary mb-4">نستخدم ملفات تعريف الارتباط وتقنيات التتبع المماثلة لتحسين تجربة التصفح وتحليل حركة الموقع وتخصيص المحتوى. يمكنك التحكم في تفضيلات ملفات تعريف الارتباط من خلال إعدادات المتصفح.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">٧. تغيير هذه السياسة</h4>
        <p class="text-secondary mb-4">قد نقوم بتحديث سياسة الخصوصية هذه من وقت لآخر. سنخطرك بأي تغييرات عن طريق نشر السياسة الجديدة على هذه الصفحة وتحديث تاريخ السريان.</p>

        <h4 class="fw-bold mb-4" style="color: #0F6D80;">٨. الاتصال بنا</h4>
        <p class="text-secondary mb-0">إذا كانت لديك أي أسئلة حول سياسة الخصوصية هذه، يرجى <a href="{{ route('contact') }}" style="color: #0F6D80;">التواصل معنا</a>.</p>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

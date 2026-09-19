@extends('layouts.public')

@section('title', __('messages.page_reviews_title', ['site_name' => __('messages.site_name')]))
@section('meta_description', __('messages.page_reviews_meta', ['site_name' => __('messages.site_name')]))
@section('meta_robots', 'index, follow')

@section('content')
      <div class="page-hero text-center" data-aos="fade-up"><div class="hero-orb hero-orb-1"></div><div class="hero-orb hero-orb-2"></div><div class="hero-dot hero-dot-1"></div><div class="hero-dot hero-dot-2"></div><div class="hero-dot hero-dot-3"></div>
        <div class="container">
          <div class="page-hero-icon"><i class="fa-solid fa-star"></i></div>
          <h1 class="fw-bold">{{ session('locale', 'ar') === 'en' ? 'Student Reviews' : 'آراء الطالبات' }}</h1>
          <p>{{ session('locale', 'ar') === 'en' ? 'See what students say about ' : 'تعرفي على تجربة الطالبات مع ' }}{{ __('messages.site_name') }}</p>
        </div>
      </div>

      <section class="reviews-section py-5 position-relative overflow-hidden" data-aos="fade-up">
        <div class="container">
          <div class="text-center mb-5">
            <h2 class="section-title">{{ __('messages.reviews_heading') }}</h2>
            <p class="section-subtitle">{{ __('messages.reviews_subtitle') }}</p>
          </div>
          @push('styles')
            <style>
              .rvp-card {
                background: #fff;
                border-radius: 22px;
                padding: 28px 26px;
                height: 100%;
                position: relative;
                overflow: hidden;
                border: 1px solid rgba(15, 109, 128, 0.07);
                box-shadow: 0 16px 44px rgba(15, 109, 128, 0.08);
                transition: all 0.3s;
                display: flex;
                flex-direction: column;
              }
              .rvp-card:hover {
                transform: translateY(-6px);
                box-shadow: 0 22px 56px rgba(15, 109, 128, 0.14);
                border-color: rgba(216, 155, 29, 0.35);
              }
              .rvp-card::before {
                content: '\f10d';
                font-family: 'Font Awesome 6 Free';
                font-weight: 900;
                position: absolute;
                top: -16px;
                right: -12px;
                font-size: 6.5rem;
                color: rgba(15, 109, 128, 0.045);
                transform: scaleX(-1);
                pointer-events: none;
              }
              .rvp-top {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 14px;
              }
              .rvp-quote {
                width: 48px;
                height: 48px;
                border-radius: 14px;
                background: linear-gradient(135deg, #f0b52e, #d89b1d);
                color: #fff;
                font-size: 1.1rem;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 8px 22px rgba(216, 155, 29, 0.3);
              }
              .rvp-stars { display: flex; gap: 3px; color: #f0b52e; font-size: 0.9rem; }
              .rvp-text {
                color: #3a4a52 !important;
                line-height: 2;
                font-size: 0.98rem;
                flex: 1 1 auto;
                position: relative;
                z-index: 1;
              }
              .rvp-meta {
                display: flex;
                align-items: center;
                gap: 12px;
                margin-top: 18px;
                padding-top: 16px;
                border-top: 1px dashed rgba(15, 109, 128, 0.15);
              }
              .rvp-avatar {
                width: 48px;
                height: 48px;
                border-radius: 50%;
                background: linear-gradient(135deg, #0a4a56, #0F6D80);
                color: #fff;
                font-weight: 800;
                display: flex;
                align-items: center;
                justify-content: center;
                border: 3px solid rgba(216, 155, 29, 0.4);
                flex-shrink: 0;
              }
              .rvp-meta-info { display: flex; flex-direction: column; gap: 2px; }
              .rvp-name { font-weight: 800; color: #0F6D80; font-size: 1rem; }
              .rvp-badge {
                font-size: 0.75rem;
                font-weight: 700;
                color: #d89b1d;
                display: inline-flex;
                align-items: center;
                gap: 5px;
              }
            </style>
          @endpush
          <div class="row g-4">
            @forelse ($testimonials as $testimonial)
              <div class="col-md-6 col-lg-4">
                <div class="rvp-card">
                  <div class="rvp-top">
                    <div class="rvp-quote"><i class="fa-solid fa-quote-right"></i></div>
                    <div class="rvp-stars">
                      @for ($i = 1; $i <= 5; $i++)
                        <i class="fa-{{ $i <= $testimonial->rating ? 'solid' : 'regular' }} fa-star"></i>
                      @endfor
                    </div>
                  </div>
                  <p class="rvp-text">{{ $testimonial->content }}</p>
                  <div class="rvp-meta">
                    <div class="rvp-avatar">{{ mb_substr($testimonial->student_name, 0, 2) }}</div>
                    <div class="rvp-meta-info">
                      <span class="rvp-name">{{ $testimonial->student_name }}</span>
                      <span class="rvp-badge"><i class="fa-solid fa-circle-check"></i>
                        {{ __('messages.reviews_verified') }}</span>
                    </div>
                  </div>
                </div>
              </div>
            @empty
              <div class="col-12">
                <div class="text-center text-secondary py-5">
                  <p>{{ __('messages.student_courses_empty') }}</p>
                </div>
              </div>
            @endforelse
          </div>
        </div>
      </section>
@endsection

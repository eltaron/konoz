@extends('layouts.public')

@section('title', __('messages.page_home_title', ['site_name' => __('messages.site_name')]))
@section('meta_description', __('messages.site_meta_desc'))
@section('meta_robots', 'index, follow')

@section('content')
    <section id="hero" class="hero-section d-flex align-items-center hero-fullscreen">
        <div class="hero-orb hero-orb-1"></div>
        <div class="hero-pattern"></div>
        <div class="container position-relative z-1 py-3 py-lg-4">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 order-2 order-lg-1">
                    {{-- <div class="hero-badge mb-3">
                        <span class="hero-badge-dot"></span>
                        {{ __('messages.hero_welcome') }}
                    </div> --}}
                    <h1 class="hero-title mb-3">
                        {{ __('messages.hero_greeting') }}
                        <span class="hero-title-highlight position-relative">{{ __('messages.hero_brand') }}<span
                                class="hero-title-underline"></span></span>..<br />{{ __('messages.hero_headline_1') }}<br />
                        {{ __('messages.hero_headline_2') }}
                    </h1>
                    <p class="text-secondary mb-4" style="max-width: 520px">
                        {{ __('messages.hero_subtitle_full') }}
                    </p>
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        @auth
                            <a href="{{ Auth::user()->role === 'admin' || Auth::user()->role === 'teacher' ? route('teacher.dashboard') : route('student.dashboard') }}"
                                class="btn btn-primary fw-bold btn-hero-primary ">
                                <i class="fa-solid fa-gauge-high ml-2"></i>{{ __('messages.nav_dashboard') }}
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary fw-bold btn-hero-primary ">
                                <i class="fa-solid fa-user ml-2"></i>{{ __('messages.nav_login_account') }}
                            </a>
                        @endauth
                        <a href="{{ route('departments') }}" class="btn btn-outline-primary fw-bold btn-hero-secondary">
                            <i class="fa-solid fa-compass ml-2"></i>{{ __('messages.nav_departments') }}
                        </a>
                    </div>
                    <div class="hero-trust">
                        <div class="hero-trust-avatars">
                            <span class="hero-trust-avatar"
                                style="background: linear-gradient(135deg, #f5bd58, #d89b1d);">أ</span>
                            <span class="hero-trust-avatar"
                                style="background: linear-gradient(135deg, #0c5665, #129aad);">م</span>
                            <span class="hero-trust-avatar"
                                style="background: linear-gradient(135deg, #d89b1d, #0f6d80);">ن</span>
                        </div>
                        <div>
                            <p class="hero-trust-rating mb-1">
                                <i class="fa-solid fa-star"></i> 4.9/5
                            </p>
                            <p class="hero-trust-features mb-0">
                                <span><i
                                        class="fa-solid fa-graduation-cap"></i>{{ __('messages.hero_checklist_1') }}</span>
                                <span><i class="fa-solid fa-shield-halved"></i>{{ __('messages.hero_checklist_2') }}</span>
                                <span><i class="fa-solid fa-clock"></i>{{ __('messages.hero_checklist_3') }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2">
                    <div class="hero-visual">
                        <div class="hero-visual-orb hero-visual-orb-1"></div>
                        <div class="hero-visual-orb hero-visual-orb-2"></div>
                        <div class="hero-visual-ring"></div>

                        <div id="heroCarousel" class="carousel slide carousel-fade hero-carousel position-relative"
                            data-bs-ride="carousel" data-bs-interval="5000">
                            <div class="carousel-inner">
                                @foreach ($slides as $slide)
                                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                        <img alt="{{ $slide->title }}" class="d-block w-100 hero-free-img"
                                            src="{{ $slide->image ? asset('storage/' . $slide->image) : 'https://lh3.googleusercontent.com/aida-public/AB6AXuCsMgNqPPVsVlDTYNyB9w3atrYH84TO_vPfqDAmrrr2MBg5bttx5ekqGGOCCkdnlZ_hYdasrcxImest8jWWr-w6aG56uz0n9aIU5Ro216NchWdrnM27MeMLhYnuWPWMhwJqLsK9dD_t2_dFjTg5cZbzVeUn7JzQ3hwbhStRt1G216m1moabAoihz04BKDqLq-6_cX43mUFYNhYCBxW6X3ja6kQLbIm3R0tEg6N-kfggNT7dJOUJN20zzZZOYVJLOgk26xS487hqr7o' }}" />
                                        <div class="hero-float-chip hero-float-chip-title">
                                            <i class="fa-solid fa-star"></i>
                                            <span>{{ $slide->title }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if (count($slides) > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel"
                                    data-bs-slide="prev">
                                    <i class="fa-solid fa-chevron-right hero-carousel-icon"></i>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel"
                                    data-bs-slide="next">
                                    <i class="fa-solid fa-chevron-left hero-carousel-icon"></i>
                                </button>
                            @endif
                        </div>

                        <div class="hero-float-card">
                            <div class="hero-float-card-icon">
                                <i class="fa-solid fa-venus"></i>
                            </div>
                            <div class="hero-float-card-text">
                                <strong>{{ __('messages.hero_checklist_1') }}</strong>
                                <small>{{ __('messages.hero_welcome') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="py-5 bg-white" data-aos="fade-up">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">
                    {{ __('messages.services_heading') }}
                </h2>
                <p class="section-subtitle">
                    {{ __('messages.services_subtitle_full') }}
                </p>
            </div>
            <div class="row g-4">
                <div class="col-12">
                    <div class="featured-course-card rounded-4 p-4 p-md-5 h-100 position-relative overflow-hidden">
                        <div class="featured-orb featured-orb-1"></div>
                        <div class="featured-orb featured-orb-2"></div>
                        <div class="featured-ring"></div>
                        <i class="fa-solid fa-book-open featured-book"></i>
                        <div
                            class="position-relative d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4">
                            <div class="flex-grow-1" style="max-width: 660px;">
                                <div class="register-badge">
                                    <div class="register-badge-shine"></div>
                                    <i class="fa-solid fa-sparkles"></i>
                                    <span>{{ __('messages.services_badge') }}</span>
                                </div>
                                @if ($latestCourse)
                                    <span class="featured-package">
                                        <i class="fa-solid fa-gem"></i>{{ __('messages.services_card1_title') }}
                                    </span>
                                    <h3 class="fw-bold featured-card-title mb-3">{{ $latestCourse->name }}</h3>
                                    <p class="featured-card-desc mb-4">
                                        {{ $latestCourse->desc ?: __('messages.services_card1_desc') }}
                                    </p>
                                    <div class="d-flex flex-wrap gap-4 mb-4">
                                        @if ($latestCourse->duration)
                                            <div class="featured-stat">
                                                <i class="fa-solid fa-clock"></i>
                                                <div>
                                                    <span class="featured-stat-num">{{ $latestCourse->duration }}</span>
                                                    <span
                                                        class="featured-stat-label">{{ __('messages.services_card1_duration') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($latestCourse->sessions_per_week)
                                            <div class="featured-stat">
                                                <i class="fa-solid fa-video"></i>
                                                <div>
                                                    <span
                                                        class="featured-stat-num">{{ $latestCourse->sessions_per_week }}</span>
                                                    <span
                                                        class="featured-stat-label">{{ __('messages.services_card1_sessions') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="featured-stat">
                                            <i class="fa-solid fa-coins"></i>
                                            <div>
                                                <span
                                                    class="featured-stat-num">{{ $latestCourse->is_free ? __('messages.dept_free') : number_format($latestCourse->price) . ' ج.م' }}</span>
                                                <span class="featured-stat-label">{{ __('messages.dept_monthly') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="featured-package">
                                        <i class="fa-solid fa-gem"></i>{{ __('messages.services_card1_title') }}
                                    </span>
                                    <h3 class="fw-bold featured-card-title mb-3">{{ __('messages.services_card1_title') }}
                                    </h3>
                                    <p class="featured-card-desc mb-4">
                                        {{ __('messages.services_card1_desc') }}
                                    </p>
                                @endif
                            </div>
                            <div class="flex-shrink-0">
                                @if ($latestCourse)
                                    <a href="{{ route('course-details', ['id' => $latestCourse->id]) }}"
                                        class="btn btn-featured fw-bold px-4 py-3 rounded-3">
                                        <i class="fa-solid fa-arrow-left ml-1"></i>{{ __('messages.services_card1_cta') }}
                                    </a>
                                @else
                                    <a href="{{ route('departments') }}"
                                        class="btn btn-featured fw-bold px-4 py-3 rounded-3">
                                        <i class="fa-solid fa-arrow-left ml-1"></i>{{ __('messages.services_card1_cta') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card h-100 d-flex flex-column">
                        <div class="feature-card-icon feature-card-icon-teal">
                            <i class="fa-solid fa-book-open"></i>
                        </div>
                        <h3 class="feature-card-title">{{ __('messages.services_card2_title') }}</h3>
                        <p class="feature-card-desc flex-grow-1">
                            جلسات تفاعلية مباشرة مع {{ __('messages.why_us_feature1_title') }} متخصصات.
                        </p>
                        <a href="{{ route('departments') }}" class="feature-card-btn align-self-start">
                            <i class="fa-solid fa-arrow-left"></i>{{ __('messages.details') }}
                        </a>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card h-100 d-flex flex-column">
                        <div class="feature-card-icon feature-card-icon-purple">
                            <i class="fa-solid fa-language"></i>
                        </div>
                        <h3 class="feature-card-title">{{ __('messages.services_card3_title') }}</h3>
                        <p class="feature-card-desc flex-grow-1">
                            {{ __('messages.services_card3_desc') }}
                        </p>
                        <a href="{{ route('educational-support') }}" class="feature-card-btn align-self-start">
                            <i class="fa-solid fa-arrow-left"></i>{{ __('messages.details') }}
                        </a>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card h-100 d-flex flex-column">
                        <div class="feature-card-icon feature-card-icon-blue">
                            <i class="fa-solid fa-laptop-code"></i>
                        </div>
                        <h3 class="feature-card-title">{{ __('messages.services_card4_title') }}</h3>
                        <p class="feature-card-desc flex-grow-1">
                            {{ __('messages.services_card4_desc') }}
                        </p>
                        <a href="{{ route('coding') }}" class="feature-card-btn align-self-start">
                            <i class="fa-solid fa-arrow-left"></i>{{ __('messages.details') }}
                        </a>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card h-100 d-flex flex-column">
                        <div class="feature-card-icon feature-card-icon-gold">
                            <i class="fa-solid fa-hands-holding-child"></i>
                        </div>
                        <h3 class="feature-card-title">{{ __('messages.services_card5_title') }}</h3>
                        <p class="feature-card-desc flex-grow-1">
                            {{ __('messages.services_card5_desc') }}
                        </p>
                        <a href="{{ route('parenting-support') }}" class="feature-card-btn align-self-start">
                            <i class="fa-solid fa-arrow-left"></i>{{ __('messages.details') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="why-us" class="py-5 bg-light" data-aos="fade-up">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">{{ __('messages.why_us_heading') }}</h2>
                <p class="section-subtitle mb-4">{{ __('messages.why_us_subtitle') }}</p>
                <div class="d-flex flex-column flex-sm-row justify-content-center gap-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="bg-primary bg-opacity-10 p-2 rounded-circle">
                            <i class="fa-solid fa-check text-primary"></i>
                        </div>
                        <div class="text-start">
                            <p class="fw-bold text-primary mb-0">{{ __('messages.why_us_feature1_title') }}</p>
                            <p class="text-secondary mb-0">{{ __('messages.why_us_feature1_desc') }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3">
                        <div class="bg-primary bg-opacity-10 p-2 rounded-circle">
                            <i class="fa-solid fa-check text-primary"></i>
                        </div>
                        <div class="text-start">
                            <p class="fw-bold text-primary mb-0">{{ __('messages.why_us_feature2_title') }}</p>
                            <p class="text-secondary mb-0">{{ __('messages.why_us_feature2_desc') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="0">
                    <div class="bg-white p-4 rounded-4 shadow-sm h-100 text-center border-0 border-4 border-primary">
                        <i class="fa-solid fa-venus text-primary fs-1 mb-3"></i>
                        <h4 class="fw-bold text-heading mb-2">{{ __('messages.why_us_card1_title') }}</h4>
                        <p class="text-secondary mb-0">{{ __('messages.why_us_card1_desc') }}</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="bg-white p-4 rounded-4 shadow-sm h-100 text-center border-0 border-4 border-primary">
                        <i class="fa-solid fa-eye-slash text-primary fs-1 mb-3"></i>
                        <h4 class="fw-bold text-heading mb-2">{{ __('messages.why_us_card2_title') }}</h4>
                        <p class="text-secondary mb-0">{{ __('messages.why_us_card2_desc') }}</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-white p-4 rounded-4 shadow-sm h-100 text-center border-0 border-4 border-primary">
                        <i class="fa-solid fa-chart-simple text-primary fs-1 mb-3"></i>
                        <h4 class="fw-bold text-heading mb-2">{{ __('messages.why_us_card3_title') }}</h4>
                        <p class="text-secondary mb-0">{{ __('messages.why_us_card3_desc') }}</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="bg-white p-4 rounded-4 shadow-sm h-100 text-center border-0 border-4 border-primary">
                        <i class="fa-solid fa-clock text-primary fs-1 mb-3"></i>
                        <h4 class="fw-bold text-heading mb-2">{{ __('messages.why_us_card4_title') }}</h4>
                        <p class="text-secondary mb-0">{{ __('messages.why_us_card4_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{--
      =====================================================
      SECTION: Cost Calculator (commented out)
      =====================================================
      <section id="calculator" class="py-5 bg-white" data-aos="fade-up">
        ...
      </section>
      =====================================================
      --}}

    <!-- Steps Section -->
    <section id="steps" class="py-5 position-relative overflow-hidden"
        style="background: linear-gradient(160deg, #0f6d80 0%, #0b5363 50%, #083a45 100%);" data-aos="fade-up">
        <div class="hero-pattern"></div>
        <div class="container position-relative z-1">
            <div class="text-center mb-5">
                <div class="d-inline-flex align-items-center justify-content-center bg-white text-primary rounded-circle shadow-sm mb-3"
                    style="width: 70px; height: 70px;">
                    <i class="fa-solid fa-route fs-3"></i>
                </div>
                <h2 class="fw-bold text-white mb-1">{{ __('messages.steps_heading') }}</h2>
                <p class="text-white opacity-75">{{ __('messages.steps_subtitle') }}</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="0">
                    <div class="text-center p-4 rounded-4 h-100"
                        style="background: rgba(255,255,255,0.08); backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.12);">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                            style="width: 80px; height: 80px; background: rgba(255,255,255,0.15);">
                            <span class="fw-bold text-white" style="font-size: 1.8rem;">01</span>
                        </div>
                        <h5 class="fw-bold text-white mb-2">{{ __('messages.steps_step1_title') }}</h5>
                        <p class="small text-white opacity-70 mb-0">{{ __('messages.steps_step1_desc') }}</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-center p-4 rounded-4 h-100"
                        style="background: rgba(255,255,255,0.08); backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.12);">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                            style="width: 80px; height: 80px; background: rgba(255,255,255,0.15);">
                            <span class="fw-bold text-white" style="font-size: 1.8rem;">02</span>
                        </div>
                        <h5 class="fw-bold text-white mb-2">{{ __('messages.steps_step2_title') }}</h5>
                        <p class="small text-white opacity-70 mb-0">{{ __('messages.steps_step2_desc') }}</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-center p-4 rounded-4 h-100"
                        style="background: rgba(255,255,255,0.08); backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.12);">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                            style="width: 80px; height: 80px; background: rgba(255,255,255,0.15);">
                            <span class="fw-bold text-white" style="font-size: 1.8rem;">03</span>
                        </div>
                        <h5 class="fw-bold text-white mb-2">{{ __('messages.steps_step3_title') }}</h5>
                        <p class="small text-white opacity-70 mb-0">{{ __('messages.steps_step3_desc') }}</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-center p-4 rounded-4 h-100"
                        style="background: rgba(255,255,255,0.08); backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.12);">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                            style="width: 80px; height: 80px; background: rgba(255,255,255,0.15);">
                            <span class="fw-bold text-white" style="font-size: 1.8rem;">04</span>
                        </div>
                        <h5 class="fw-bold text-white mb-2">{{ __('messages.steps_step4_title') }}</h5>
                        <p class="small text-white opacity-70 mb-0">{{ __('messages.steps_step4_desc') }}</p>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5" data-aos="fade-up">
                <a href="#quiz" class="btn btn-light fw-bold px-5 py-3 rounded-3 shadow-sm"
                    onclick="event.preventDefault(); document.getElementById('quiz').scrollIntoView({behavior:'smooth'});">
                    {{ __('messages.steps_cta') }} <i class="fa-solid fa-arrow-left ml-1"></i>
                </a>
            </div>
        </div>
    </section>

    <section id="quiz" class="py-5 bg-light position-relative overflow-hidden" data-aos="fade-up">
        <div class="container position-relative z-1" style="max-width: 800px">
            <div class="quiz-card bg-white rounded-4 p-4 p-md-5 shadow-lg position-relative">
                <div class="quiz-card-accent position-absolute top-0 start-0 w-100 rounded-top-4"
                    style="height: 6px; background: linear-gradient(90deg, var(--primary), var(--accent));">
                </div>
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mb-3"
                        style="width: 70px; height: 70px">
                        <i class="fa-solid fa-wand-sparkles fs-3"></i>
                    </div>
                    <h2 class="fw-bold text-heading mb-1">{{ __('messages.quiz_heading') }}</h2>
                    <p class="text-secondary">{{ __('messages.quiz_subtitle') }}</p>
                </div>

                <!-- Step Indicators -->
                <div class="d-flex justify-content-center gap-2 mb-4" id="quizSteps">
                    <div class="quiz-step-active"></div>
                    <div class="quiz-step-inactive"></div>
                    <div class="quiz-step-inactive"></div>
                </div>

                <form id="enrollForm" onsubmit="return false;">
                    <!-- Step 1: Gender Selection -->
                    <div id="quiz-step-1" class="quiz-step-content">
                        <h4 class="fw-bold text-heading text-center mb-4">{{ __('messages.enroll_step1_title') }}</h4>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <button type="button"
                                    class="btn quiz-option-btn w-100 text-start p-4 rounded-3 d-flex align-items-center gap-3"
                                    data-gender="woman" onclick="selectGender(this)">
                                    <span class="quiz-check"><i class="fa-solid fa-check"></i></span>
                                    <div
                                        class="quiz-option-icon d-flex align-items-center justify-content-center flex-shrink-0">
                                        <i class="fa-solid fa-person-dress fs-3"></i>
                                    </div>
                                    <div class="text-truncate">
                                        <p class="fw-bold text-heading mb-0 fs-6">{{ __('messages.enroll_gender_woman') }}
                                        </p>
                                    </div>
                                </button>
                            </div>
                            <div class="col-md-4">
                                <button type="button"
                                    class="btn quiz-option-btn w-100 text-start p-4 rounded-3 d-flex align-items-center gap-3"
                                    data-gender="boy" onclick="selectGender(this)">
                                    <span class="quiz-check"><i class="fa-solid fa-check"></i></span>
                                    <div
                                        class="quiz-option-icon d-flex align-items-center justify-content-center flex-shrink-0">
                                        <i class="fa-solid fa-child fs-3"></i>
                                    </div>
                                    <div class="text-truncate">
                                        <p class="fw-bold text-heading mb-0 fs-6">{{ __('messages.enroll_gender_boy') }}
                                        </p>
                                    </div>
                                </button>
                            </div>
                            <div class="col-md-4">
                                <button type="button"
                                    class="btn quiz-option-btn w-100 text-start p-4 rounded-3 d-flex align-items-center gap-3"
                                    data-gender="girl" onclick="selectGender(this)">
                                    <span class="quiz-check"><i class="fa-solid fa-check"></i></span>
                                    <div
                                        class="quiz-option-icon d-flex align-items-center justify-content-center flex-shrink-0">
                                        <i class="fa-solid fa-child-reaching fs-3"></i>
                                    </div>
                                    <div class="text-truncate">
                                        <p class="fw-bold text-heading mb-0 fs-6">{{ __('messages.enroll_gender_girl') }}
                                        </p>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Course Selection -->
                    <div id="quiz-step-2" class="quiz-step-content d-none">
                        <h4 class="fw-bold text-heading text-center mb-2">{{ __('messages.enroll_step2_title') }}</h4>
                        <p class="text-secondary text-center mb-4 small">{{ __('messages.enroll_step2_subtitle') }}</p>
                        <div id="coursesLoading" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status"></div>
                            <p class="text-secondary mt-2 small">{{ __('messages.loading') }}</p>
                        </div>
                        <div id="coursesContainer" class="row g-3 d-none"></div>
                        <div id="coursesEmpty" class="text-center py-4 d-none">
                            <p class="text-secondary">{{ __('messages.student_courses_empty') }}</p>
                        </div>
                    </div>

                    <!-- Step 3: Contact Info -->
                    <div id="quiz-step-3" class="quiz-step-content d-none">
                        <h4 class="fw-bold text-heading text-center mb-2">{{ __('messages.enroll_step3_title') }}</h4>
                        <p class="text-secondary text-center mb-4 small">{{ __('messages.enroll_step3_subtitle') }}</p>
                        <div class="row g-3" style="max-width: 600px; margin: 0 auto;">
                            <div class="col-12">
                                <label
                                    class="small fw-medium text-secondary mb-1">{{ __('messages.enroll_name') }}</label>
                                <input type="text" name="name" id="enrollName"
                                    class="form-control form-control-lg rounded-3"
                                    placeholder="{{ __('messages.enroll_name_placeholder') }}" required />
                            </div>
                            <div class="col-12">
                                <label
                                    class="small fw-medium text-secondary mb-1">{{ __('messages.enroll_phone') }}</label>
                                <input type="tel" name="phone" id="enrollPhone"
                                    class="form-control form-control-lg rounded-3"
                                    placeholder="{{ __('messages.enroll_phone_placeholder') }}" required />
                            </div>
                            <div class="col-12">
                                <label
                                    class="small fw-medium text-secondary mb-1">{{ __('messages.enroll_email') }}</label>
                                <input type="email" name="email" id="enrollEmail"
                                    class="form-control form-control-lg rounded-3"
                                    placeholder="{{ __('messages.enroll_email_placeholder') }}" required />
                            </div>
                            <div class="col-12">
                                <label
                                    class="small fw-medium text-secondary mb-1">{{ __('messages.enroll_password') }}</label>
                                <input type="password" name="password" id="enrollPassword"
                                    class="form-control form-control-lg rounded-3"
                                    placeholder="{{ __('messages.enroll_password_placeholder') }}" required />
                                <small class="text-secondary d-block mt-2">
                                    {{ __('messages.enroll_password_note') }}
                                </small>
                            </div>
                            <div class="col-12 mt-3">
                                <label
                                    class="small fw-medium text-secondary mb-1">{{ __('messages.enroll_receipt') }}</label>
                                <input type="file" name="receipt" id="enrollReceipt"
                                    class="form-control form-control-lg rounded-3"
                                    accept="image/*,.pdf" />
                                <small class="text-secondary d-block mt-2">
                                    {{ __('messages.enroll_receipt_note') }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-4">
                        <button type="button" id="quizBackBtn"
                            class="btn btn-link text-secondary fw-bold text-decoration-none px-0" onclick="prevStep()"
                            style="visibility: hidden;">
                            <i class="fa-solid fa-arrow-right ml-1"></i> {{ __('messages.back') }}
                        </button>
                        <div></div>
                        <button type="button" id="quizNextBtn"
                            class="btn btn-primary fw-bold px-4 py-3 rounded-3 shadow-sm " onclick="nextStep()">
                            {{ __('messages.next_step') }} <i class="fa-solid fa-arrow-left ml-1"></i>
                        </button>
                        <button type="button" id="quizSubmitBtn"
                            class="btn btn-success fw-bold px-4 py-3 rounded-3 shadow-sm  d-none"
                            onclick="submitEnroll()">
                            {{ __('messages.enroll_submit') }} <i class="fa-solid fa-check ml-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            let currentStep = 1;
            let selectedGender = null;
            let paymentMethods = [];
            let selectedCourseId = null;
            let coursesData = [];

            function updateStepUI() {
                document.querySelectorAll('.quiz-step-content').forEach(el => el.classList.add('d-none'));
                document.getElementById('quiz-step-' + currentStep).classList.remove('d-none');

                const steps = document.querySelectorAll('#quizSteps > div');
                steps.forEach((el, i) => {
                    el.className = i + 1 <= currentStep ? 'quiz-step-active' : 'quiz-step-inactive';
                });

                const backBtn = document.getElementById('quizBackBtn');
                backBtn.style.visibility = currentStep === 1 ? 'hidden' : 'visible';

                const nextBtn = document.getElementById('quizNextBtn');
                const submitBtn = document.getElementById('quizSubmitBtn');
                if (currentStep === 3) {
                    nextBtn.classList.add('d-none');
                    submitBtn.classList.remove('d-none');
                } else {
                    nextBtn.classList.remove('d-none');
                    submitBtn.classList.add('d-none');
                }
            }

            function selectGender(btn) {
                document.querySelectorAll('[data-gender]').forEach(el => {
                    el.classList.remove('quiz-option-active');
                });
                btn.classList.add('quiz-option-active');
                selectedGender = btn.dataset.gender;
            }

            function nextStep() {
                if (currentStep === 1 && !selectedGender) {
                    Swal.fire({
                        icon: 'warning',
                        title: '{{ __('messages.enroll_error_required') }}',
                        confirmButtonColor: '#0F6D80'
                    });
                    return;
                }
                if (currentStep === 2 && !selectedCourseId) {
                    Swal.fire({
                        icon: 'warning',
                        title: '{{ __('messages.enroll_error_required') }}',
                        confirmButtonColor: '#0F6D80'
                    });
                    return;
                }
                if (currentStep < 3) {
                    currentStep++;
                    updateStepUI();
                    if (currentStep === 2) loadCourses();
                }
            }

            function prevStep() {
                if (currentStep > 1) {
                    currentStep--;
                    updateStepUI();
                }
            }

            function loadCourses() {
                const loading = document.getElementById('coursesLoading');
                const container = document.getElementById('coursesContainer');
                const empty = document.getElementById('coursesEmpty');
                loading.classList.remove('d-none');
                container.classList.add('d-none');
                empty.classList.add('d-none');

                fetch('{{ route('enroll.courses') }}')
                    .then(r => r.json())
                    .then(res => {
                        const courses = res.courses || res;
                        paymentMethods = res.payment_methods || [];
                        loading.classList.add('d-none');
                        if (!courses.length) {
                            empty.classList.remove('d-none');
                            return;
                        }
                        coursesData = courses;
                        container.innerHTML = courses.map(c => `
              <div class="col-md-6 col-lg-4">
                <div class="enroll-course-card" data-course-id="${c.id}" onclick="selectCourse(this)">
                  <span class="enroll-check"><i class="fa-solid fa-check"></i></span>
                  <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="enroll-course-icon">
                      <i class="fa-solid ${c.icon}"></i>
                    </div>
                    ${c.category ? `<span class="enroll-course-cat">${c.category}</span>` : ''}
                  </div>
                  <p class="enroll-course-name">${c.name}</p>
                  ${c.desc ? `<p class="enroll-course-desc">${c.desc}</p>` : ''}
                  <div class="enroll-course-meta">
                    ${c.level ? `<span><i class="fa-solid fa-signal"></i>${c.level}</span>` : ''}
                    ${c.duration ? `<span><i class="fa-regular fa-clock"></i>${c.duration}</span>` : ''}
                    ${c.sessions_per_week ? `<span><i class="fa-solid fa-video"></i>${c.sessions_per_week} {{ __('messages.enroll_sessions_week') }}</span>` : ''}
                  </div>
                  <div class="enroll-course-foot">
                    <span class="enroll-course-price">${c.is_free ? '<i class="fa-solid fa-gift"></i>{{ __('messages.dept_free') }}' : '<i class="fa-solid fa-coins"></i>' + c.price + ' {{ __('messages.dept_monthly') }}'}</span>
                  </div>
                </div>
              </div>
            `).join('');
                        container.classList.remove('d-none');
                    })
                    .catch(() => {
                        loading.classList.add('d-none');
                        empty.classList.remove('d-none');
                        empty.querySelector('p').textContent = '{{ __('messages.student_courses_empty') }}';
                    });
            }

            function selectCourse(el) {
                document.querySelectorAll('.enroll-course-card').forEach(c => {
                    c.classList.remove('enroll-course-active');
                });
                el.classList.add('enroll-course-active');
                selectedCourseId = el.dataset.courseId;
            }

            function submitEnroll() {
                const name = document.getElementById('enrollName').value.trim();
                const phone = document.getElementById('enrollPhone').value.trim();

                if (!name || !selectedGender || !selectedCourseId) {
                    Swal.fire({
                        icon: 'warning',
                        title: '{{ __('messages.enroll_error_required') }}',
                        confirmButtonColor: '#0F6D80'
                    });
                    return;
                }

                const submitBtn = document.getElementById('quizSubmitBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-1"></span> {{ __('messages.enroll_loading') }}';

                fetch('{{ route('enroll') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            name,
                            phone,
                            gender: selectedGender,
                            course_id: selectedCourseId
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '{{ __('messages.enroll_complete') }}',
                                text: data.message,
                                confirmButtonColor: '#0F6D80',
                                confirmButtonText: '{{ __('messages.teacher_exams_ok') }}'
                            }).then(() => {
                                selectedGender = null;
                                selectedCourseId = null;
                                currentStep = 1;
                                document.getElementById('enrollName').value = '';
                                document.getElementById('enrollPhone').value = '';
                                document.querySelectorAll('[data-gender]').forEach(el => {
                                    el.classList.remove('quiz-option-active');
                                });
                                document.querySelectorAll('.enroll-course-card').forEach(el => {
                                    el.classList.remove('enroll-course-active');
                                });
                                updateStepUI();
                            });
                        } else {
                            throw new Error(data.message || 'Error');
                        }
                    })
.catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __('messages.student_hifdh_save_error') }}',
                            text: err.message,
                            confirmButtonColor: '#0F6D80'
                        });
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '{{ __('messages.enroll_submit') }} <i class="fa-solid fa-check ml-1"></i>';
                    });
            }

            document.addEventListener('DOMContentLoaded', updateStepUI);
        </script>

        <style>
            .enroll-course-card {
                position: relative;
                border: 2px solid #e5e9ea;
                border-radius: 16px;
                padding: 1.15rem;
                background: #fff;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                flex-direction: column;
                height: 100%;
                overflow: hidden;
            }

            .enroll-course-card::before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: linear-gradient(90deg, var(--primary), var(--accent));
                opacity: 0;
                transition: opacity 0.3s;
            }

            .enroll-course-card:hover {
                border-color: var(--primary);
                background: rgba(15, 109, 128, 0.04);
                transform: translateY(-3px);
                box-shadow: 0 10px 26px rgba(15, 109, 128, 0.14);
            }

            .enroll-course-card:hover::before {
                opacity: 1;
            }

            .enroll-course-active {
                border-color: var(--primary) !important;
                background: linear-gradient(135deg, rgba(15, 109, 128, 0.08), rgba(15, 109, 128, 0.02)) !important;
                box-shadow: 0 10px 26px rgba(15, 109, 128, 0.18) !important;
            }

            .enroll-course-active::before {
                opacity: 1;
            }

            .enroll-check {
                position: absolute;
                top: 10px;
                inset-inline-end: 10px;
                width: 26px;
                height: 26px;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--primary), #0b5363);
                color: #fff;
                font-size: 0.7rem;
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                transform: scale(0.5);
                transition: all 0.25s ease;
                box-shadow: 0 4px 10px rgba(15, 109, 128, 0.35);
                z-index: 2;
            }

            .enroll-course-active .enroll-check {
                opacity: 1;
                transform: scale(1);
            }

            .enroll-course-icon {
                width: 52px;
                height: 52px;
                border-radius: 14px;
                background: linear-gradient(135deg, rgba(15, 109, 128, 0.12), rgba(15, 109, 128, 0.05));
                color: var(--primary);
                font-size: 1.35rem;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s;
            }

            .enroll-course-active .enroll-course-icon {
                background: var(--primary);
                color: #fff;
            }

            .enroll-course-cat {
                font-size: 0.7rem;
                font-weight: 700;
                color: var(--accent);
                background: rgba(216, 155, 29, 0.12);
                padding: 0.3rem 0.7rem;
                border-radius: 50px;
                white-space: nowrap;
            }

            .enroll-course-name {
                font-weight: 800;
                font-size: 0.95rem;
                color: #1f2937;
                margin-bottom: 0.35rem;
                line-height: 1.4;
            }

            .enroll-course-desc {
                font-size: 0.78rem;
                color: #6b7a7e;
                line-height: 1.7;
                margin-bottom: 0.9rem;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .enroll-course-meta {
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
                margin-bottom: 0.9rem;
            }

            .enroll-course-meta span {
                font-size: 0.7rem;
                font-weight: 600;
                color: #475569;
                background: #f1f5f7;
                border-radius: 50px;
                padding: 0.3rem 0.65rem;
                display: inline-flex;
                align-items: center;
                gap: 0.35rem;
            }

            .enroll-course-meta span i {
                color: var(--primary);
                font-size: 0.65rem;
            }

            .enroll-course-foot {
                margin-top: auto;
                padding-top: 0.7rem;
                border-top: 1px dashed #e2e8ec;
            }

            .enroll-course-price {
                font-size: 0.82rem;
                font-weight: 800;
                color: #0b5363;
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
            }

            .enroll-course-price i {
                color: var(--accent);
            }

            #quiz .form-control:focus {
                border-color: var(--primary);
                box-shadow: 0 0 0 0.2rem rgba(15, 109, 128, 0.15);
            }

            #quiz .btn-success {
                background: #27ae60;
                border-color: #27ae60;
            }

            #quiz .btn-success:hover {
                background: #219a52;
                border-color: #219a52;
            }
        </style>
    @endpush

    <section id="payment" class="py-5 bg-light" data-aos="fade-up">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">{{ __('messages.payment_heading') }}</h2>
                <p class="section-subtitle">{{ __('messages.payment_subtitle') }}</p>
            </div>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                @forelse($paymentMethods as $method)
                    <div class="payment-item bg-white px-4 py-3 shadow-sm border border-primary border-opacity-10 d-flex align-items-center gap-3"
                        role="button" data-bs-toggle="modal" data-bs-target="#paymentModal"
                        data-payment="{{ $method->identifier ?? $method->id }}">
                        <div class="payment-item-icon">
                            <i class="fa-solid {{ $method->icon ?? 'fa-wallet' }}"></i>
                        </div>
                        <div>
                            <p class="fw-bold text-heading mb-0">{{ $method->name }}</p>
                            <p class="small text-secondary mb-0">{{ $method->details }}</p>
                        </div>
                        <i class="fa-solid fa-chevron-left text-secondary opacity-30 ml-auto"></i>
                    </div>
                @empty
                    <div class="col-12 text-center text-secondary py-3">
                        <p>لا توجد طرق دفع متاحة حالياً</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="champions" class="py-5 bg-white" data-aos="fade-up">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5 gap-4">
                <div>
                    <h2 class="fw-bold text-primary mb-3">{{ __('messages.champions_heading') }}</h2>
                    <p class="text-secondary fs-5">
                        {{ __('messages.champions_subtitle') }}
                    </p>
                </div>
                <a href="{{ route('honor-board') }}" class="btn btn-outline-primary rounded-pill px-4 py-2">
                    {{ __('messages.champions_btn') }}
                </a>
            </div>
            <div class="row g-4">
                @forelse($champions as $index => $champion)
                    <div class="col-md-3" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                        <div
                            class="bg-light rounded-4 p-4 border border-primary border-opacity-10 text-center shadow-sm h-100">
                            <div class="mx-auto mb-3 rounded-circle border border-4 border-primary p-1"
                                style="width: 96px; height: 96px">
                                <img alt="{{ $champion->name }}" class="w-100 h-100 rounded-circle bg-light"
                                    src="{{ $champion->image ? asset('storage/' . $champion->image) : url('/images/default-avatar.png') }}" />
                            </div>
                            <h4 class="fw-bold text-heading mb-1">{{ $champion->name }}</h4>
                            <p class="text-primary fw-bold mb-3">{{ $champion->achievement }}</p>
                            <div class="d-flex justify-content-center">
                                @switch($champion->icon)
                                    @case('star')
                                        <i class="fa-solid fa-star text-accent fs-4"></i>
                                    @break

                                    @case('medal')
                                        <i class="fa-solid fa-medal text-primary fs-4"></i>
                                    @break

                                    @case('music')
                                        <i class="fa-solid fa-music text-primary fs-4"></i>
                                    @break

                                    @case('users')
                                        <i class="fa-solid fa-users text-primary fs-4"></i>
                                    @break

                                    @case('trophy')
                                        <i class="fa-solid fa-trophy text-primary fs-4"></i>
                                    @break

                                    @case('crown')
                                        <i class="fa-solid fa-crown text-primary fs-4"></i>
                                    @break

                                    @default
                                        <i class="fa-solid fa-star text-accent fs-4"></i>
                                @endswitch
                            </div>
                        </div>
                    </div>
                    @empty
                        <div class="col-12 text-center text-secondary py-5">
                            <p>لا يوجد نجوم حتى الآن</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section id="certificates" class="py-5 bg-white position-relative overflow-hidden" data-aos="fade-up">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="section-title">{{ __('messages.cert_gallery_heading') }}</h2>
                    <p class="section-subtitle">{{ __('messages.cert_gallery_subtitle') }}</p>
                </div>
                <script>
                    window.CERT_DATA = @json($certificates->map(fn($c) => ['src' => $c->image_url, 'title' => $c->title])->values());
                </script>
                @push('styles')
                    <style>
                        .cert-grid {
                            display: grid;
                            grid-template-columns: repeat(3, 1fr);
                            gap: 24px;
                        }

                        .cert-grid .cert-gallery-card {
                            transform: none;
                            height: 100%;
                        }

                        .cert-grid .cert-gallery-card:hover {
                            transform: translateY(-6px) !important;
                        }

                        .cert-grid .cert-gallery-media {
                            aspect-ratio: auto;
                            overflow: hidden;
                        }

                        .cert-grid .cert-gallery-media img {
                            object-fit: contain;
                            height: auto;
                            border-radius: 6px;
                        }

                        .cert-grid .cert-more-btn {
                            z-index: 999999999999999999999;
                            position: relative;
                            display: inline-flex;
                            align-items: center;
                            gap: 10px;
                            padding: 14px 32px;
                            border-radius: 60px;
                            font-size: 1.05rem;
                            font-weight: 800;
                            color: #fff;
                            background: linear-gradient(135deg, #0F6D80, #0a4a56);
                            border: none;
                            box-shadow: 0 12px 30px rgba(15, 109, 128, 0.35);
                            overflow: hidden;
                            transition: all .35s ease;
                        }

                        .cert-grid .cert-more-btn::before {
                            content: "";
                            position: absolute;
                            inset: 0;
                            background: linear-gradient(135deg, #148a9f, #0F6D80);
                            opacity: 0;
                            transition: opacity .35s ease;
                        }

                        .cert-grid .cert-more-btn:hover {
                            transform: translateY(-4px);
                            box-shadow: 0 18px 40px rgba(15, 109, 128, 0.45);
                        }

                        .cert-grid .cert-more-btn:hover::before {
                            opacity: 1;
                        }

                        .cert-grid .cert-more-btn:active {
                            transform: translateY(-1px);
                        }

                        .cert-grid .cert-more-btn i {
                            font-size: 1.15em;
                            transition: transform .35s ease;
                        }

                        .cert-grid .cert-more-btn:hover i {
                            transform: translateX(-4px);
                        }

                        @media (max-width: 991.98px) {
                            .cert-grid {
                                grid-template-columns: repeat(2, 1fr);
                            }
                        }

                        @media (max-width: 575.98px) {
                            .cert-grid {
                                grid-template-columns: 1fr;
                                gap: 16px;
                            }
                        }
                        .cert-more-btn {
                            border-radius: 50px;
                            padding: 0.8rem 2.5rem;
                            font-weight: 700;
                            font-size: 1rem;
                            transition: all 0.3s;
                            border: 2px solid var(--pumpkin);
                            color: var(--pumpkin);
                            background: transparent;
                        }
                        .cert-more-btn:hover {
    background: var(--pumpkin);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(216, 139, 18, 0.25);
}

                    </style>
                @endpush
                <div class="cert-grid">
                    @forelse($certificates->take(6) as $certIndex => $cert)
                        <div class="cert-gallery-card" data-bs-toggle="modal" data-bs-target="#certModal"
                            data-slide="{{ $certIndex }}">
                            <span class="cert-gallery-badge">
                                <i class="fa-solid fa-certificate"></i>
                                {{ __('messages.cert_badge') }}
                            </span>
                            <div class="cert-gallery-media">
                                <img alt="{{ $cert->title }}" loading="lazy" src="{{ $cert->image_url }}" />
                            </div>
                            <div class="cert-gallery-caption">
                                <i class="fa-solid fa-star"></i>
                                <span>{{ $cert->title }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-secondary py-5">
                            <p>{{ __('messages.cert_empty') }}</p>
                        </div>
                    @endforelse
                </div>
                @if ($certificates->isNotEmpty())
                    <div class="text-center mt-5">
                        <a href="{{ route('certificates') }}" class="btn cert-more-btn"><i
                                class="fa-solid fa-certificate ml-2"></i>{{ __('messages.cert_all_btn') }}</a>
                    </div>
                @endif
            </div>
        </section>

        <section id="reviews" class="reviews-section py-5 position-relative overflow-hidden" data-aos="fade-up">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="section-title">{{ __('messages.reviews_heading') }}</h2>
                    <p class="section-subtitle">{{ __('messages.reviews_subtitle') }}</p>
                </div>
                @push('styles')
                    <style>
                        
                        /* Coverflow Reviews */
                        .review-carousel {
                            max-width: 1120px;
                            margin: 0 auto;
                            position: relative;
                        }

                        .rv-track {
                            position: relative;
                            min-height: 440px;
                        }

                        .rv-slide {
                            position: absolute;
                            top: 0;
                            left: 50%;
                            width: min(70vw, 520px);
                            opacity: 0;
                            pointer-events: none;
                            transition: transform 0.6s cubic-bezier(0.22, 0.61, 0.36, 1), opacity 0.6s ease;
                            will-change: transform, opacity;
                            z-index: 10;
                        }

                        .rv-slide.rv-active {
                            transform: translateX(-50%) scale(1);
                            opacity: 1;
                            pointer-events: auto;
                            z-index: 30;
                        }

                        .rv-slide.rv-left {
                            transform: translateX(calc(-50% - 215px)) scale(0.78);
                            opacity: 0.45;
                            z-index: 20;
                        }

                        .rv-slide.rv-right {
                            transform: translateX(calc(-50% + 215px)) scale(0.78);
                            opacity: 0.45;
                            z-index: 20;
                        }

                        .rv-slide.rv-far-left {
                            transform: translateX(calc(-50% - 410px)) scale(0.6);
                            opacity: 0;
                            z-index: 10;
                        }

                        .rv-slide.rv-far-right {
                            transform: translateX(calc(-50% + 410px)) scale(0.6);
                            opacity: 0;
                            z-index: 10;
                        }

                        .rv-card {
                            background: #fff;
                            border-radius: 26px;
                            padding: 42px 48px;
                            border: 1px solid rgba(15, 109, 128, 0.07);
                            box-shadow: 0 24px 60px rgba(15, 109, 128, 0.12);
                            position: relative;
                            overflow: hidden;
                            min-height: 300px;
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            justify-content: center;
                            text-align: center;
                        }

                        .rv-card::before {
                            content: '\f10d';
                            font-family: 'Font Awesome 6 Free';
                            font-weight: 900;
                            position: absolute;
                            top: -18px;
                            right: -14px;
                            font-size: 7rem;
                            color: rgba(15, 109, 128, 0.05);
                            pointer-events: none;
                            transform: scaleX(-1);
                        }

                        .rv-card-quote {
                            width: 58px;
                            height: 58px;
                            border-radius: 18px;
                            background: linear-gradient(135deg, #f0b52e, #d89b1d);
                            color: #fff;
                            font-size: 1.3rem;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            box-shadow: 0 10px 26px rgba(216, 155, 29, 0.35);
                            margin-bottom: 16px;
                        }

                        .rv-stars {
                            display: flex;
                            gap: 4px;
                            color: #f0b52e;
                            font-size: 1rem;
                            margin-bottom: 16px;
                        }

                        .rv-text {
                            color: #3a4a52 !important;
                            line-height: 2;
                            font-size: 1.06rem;
                            margin-bottom: 22px;
                            max-width: 620px;
                            text-align: center;
                        }

                        .rv-meta {
                            display: flex;
                            align-items: center;
                            gap: 14px;
                            margin-top: auto;
                        }

                        .rv-avatar {
                            width: 56px;
                            height: 56px;
                            border-radius: 50%;
                            background: linear-gradient(135deg, #0a4a56, #0F6D80);
                            color: #fff;
                            font-weight: 800;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 1.05rem;
                            border: 3px solid rgba(216, 155, 29, 0.45);
                            flex-shrink: 0;
                        }

                        .rv-meta-info {
                            display: flex;
                            flex-direction: column;
                            align-items: flex-start;
                            gap: 2px;
                        }

                        .rv-name {
                            font-weight: 800;
                            color: #0F6D80;
                            font-size: 1.05rem;
                        }

                        .rv-badge {
                            font-size: 0.78rem;
                            font-weight: 700;
                            color: #d89b1d;
                            display: inline-flex;
                            align-items: center;
                            gap: 5px;
                        }

                        .rv-controls {
                            margin-top: 30px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            gap: 20px;
                            flex-wrap: wrap;
                        }

                        .rv-arrow {
                            width: 50px;
                            height: 50px;
                            border-radius: 50%;
                            border: 2px solid rgba(15, 109, 128, 0.15);
                            background: #fff;
                            color: #0F6D80;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 1rem;
                            transition: all 0.3s;
                        }

                        .rv-arrow:hover {
                            background: #0F6D80;
                            border-color: #0F6D80;
                            color: #fff;
                            box-shadow: 0 8px 22px rgba(15, 109, 128, 0.3);
                            transform: translateY(-2px);
                        }

                        @media (max-width: 767.98px) {
                            .rv-slide.rv-left {
                                transform: translateX(calc(-50% - 110px)) scale(0.8);
                            }

                            .rv-slide.rv-right {
                                transform: translateX(calc(-50% + 110px)) scale(0.8);
                            }

                            .rv-slide.rv-far-left {
                                transform: translateX(calc(-50% - 205px)) scale(0.65);
                            }

                            .rv-slide.rv-far-right {
                                transform: translateX(calc(-50% + 205px)) scale(0.65);
                            }
                        }

                        @media (max-width: 575.98px) {
                            .rv-card {
                                padding: 30px 20px;
                                border-radius: 20px;
                                min-height: 340px;
                            }

                            .rv-text {
                                font-size: 0.95rem;
                            }
                        }
                    </style>
                @endpush
                <div class="review-carousel" id="reviewCarousel">
                    <div class="rv-track">
                        @forelse($testimonials as $testimonial)
                            <div class="rv-slide">
                                <div class="rv-card">
                                    <div class="rv-card-quote"><i class="fa-solid fa-quote-right"></i></div>
                                    <div class="rv-stars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fa-{{ $i <= $testimonial->rating ? 'solid' : 'regular' }} fa-star"></i>
                                        @endfor
                                    </div>
                                    <p class="rv-text">{{ $testimonial->content }}</p>
                                    <div class="rv-meta">
                                        <div class="rv-avatar">{{ mb_substr($testimonial->student_name, 0, 2) }}</div>
                                        <div class="rv-meta-info">
                                            <span class="rv-name">{{ $testimonial->student_name }}</span>
                                            <span class="rv-badge"><i class="fa-solid fa-circle-check"></i>
                                                {{ __('messages.reviews_verified') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-secondary py-5 w-100">
                                <p>{{ __('messages.student_courses_empty') }}</p>
                            </div>
                        @endforelse
                    </div>
                    @if ($testimonials->isNotEmpty())
                        <div class="rv-controls">
                            <button type="button" class="rv-arrow" data-car-prev aria-label="السابق"><i
                                    class="fa-solid fa-chevron-right"></i></button>
                            <button type="button" class="rv-arrow" data-car-next aria-label="التالي"><i
                                    class="fa-solid fa-chevron-left"></i></button>
                        </div>
                    @endif
                </div>
                <div class="text-center mt-4">
                    <a href="{{ route('reviews') }}" class="btn review-more-btn"><i
                            class="fa-solid fa-star ml-2"></i>{{ __('messages.reviews_btn') }}</a>
                </div>
            </div>
        </section>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var car = document.getElementById('reviewCarousel');
                    if (!car) return;
                    var track = car.querySelector('.rv-track');
                    var slides = Array.prototype.slice.call(car.querySelectorAll('.rv-slide'));
                    var prevBtn = car.querySelector('[data-car-prev]');
                    var nextBtn = car.querySelector('[data-car-next]');
                    if (!slides.length) return;
                    var n = slides.length;
                    var active = 0;
                    var timer = null;

                    function fit() {
                        var mh = 0;
                        slides.forEach(function(s) {
                            mh = Math.max(mh, s.offsetHeight);
                        });
                        if (mh) track.style.minHeight = mh + 'px';
                    }
                    fit();
                    var rt = null;
                    window.addEventListener('resize', function() {
                        clearTimeout(rt);
                        rt = setTimeout(fit, 150);
                    });

                    function signed(i) {
                        var d = i - active;
                        if (d > n / 2) d -= n;
                        if (d < -n / 2) d += n;
                        return d;
                    }

                    function render() {
                        slides.forEach(function(s, j) {
                            var d = signed(j);
                            s.classList.remove('rv-active', 'rv-left', 'rv-right', 'rv-far-left',
                                'rv-far-right');
                            if (d === 0) s.classList.add('rv-active');
                            else if (d === 1) s.classList.add('rv-right');
                            else if (d === -1) s.classList.add('rv-left');
                            else if (d === 2) s.classList.add('rv-far-right');
                            else if (d === -2) s.classList.add('rv-far-left');
                        });
                    }
                    render();

                    function go(dir) {
                        active = (active + dir + n) % n;
                        render();
                        restart();
                    }

                    if (prevBtn) prevBtn.addEventListener('click', function() {
                        go(1);
                    });
                    if (nextBtn) nextBtn.addEventListener('click', function() {
                        go(-1);
                    });

                    function restart() {
                        if (timer) clearInterval(timer);
                        timer = setInterval(function() {
                            go(-1);
                        }, 6000);
                    }
                    restart();
                    car.addEventListener('mouseenter', function() {
                        if (timer) clearInterval(timer);
                        timer = null;
                    });
                    car.addEventListener('mouseleave', restart);
                });
            </script>
        @endpush

        <section id="cta" class="cta-section position-relative overflow-hidden py-5" data-aos="fade-up">
            <div class="hero-pattern"></div>
            <div class="cta-glow cta-glow-1"></div>
            <div class="cta-glow cta-glow-2"></div>
            <div class="container position-relative z-1 py-3 text-center">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="cta-badge">
                            <i class="fa-solid fa-gem"></i>
                            <span>{{ __('messages.cta_badge') }}</span>
                        </div>
                        <h2 class="cta-title">
                            {{ __('messages.cta_title_part1') }}<span
                                class="text-accent">{{ __('messages.cta_title_part2') }}</span>
                        </h2>
                        <p class="cta-subtitle">
                            {{ __('messages.cta_subtitle_full') }}
                        </p>
                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            <a href="{{ route('register') }}" class="btn cta-btn-primary">
                                <i class="fa-solid fa-user-plus ml-2"></i>{{ __('messages.cta_register') }}
                            </a>
                            <a href="https://wa.me/201001234567" class="btn cta-btn-secondary">
                                <i class="fa-brands fa-whatsapp ml-2"></i>{{ __('messages.cta_whatsapp_part1') }}
                                {{ __('messages.cta_whatsapp_part2') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        </main>
        <!-- Certificates Modal -->
        <div class="modal fade" id="certModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content border-0 cert-modal-content">
                    <button type="button" class="cert-modal-close" data-bs-dismiss="modal"
                        aria-label="{{ __('messages.close') }}">
                        <i class="fa-solid fa-xmark"></i>
                    </button>

                    <!-- Header -->
                    <div class="cert-modal-header">
                        <div class="d-flex align-items-center gap-2">
                            <div class="cert-modal-header-icon">
                                <i class="fa-solid fa-certificate"></i>
                            </div>
                            <p class="cert-modal-title mb-0" id="certModalTitle">شهادة</p>
                        </div>
                        <span class="cert-modal-count" id="certModalCount">1 / 1</span>
                    </div>

                    <!-- Image Stage -->
                    <div class="cert-modal-stage">
                        <button type="button" class="cert-nav cert-nav-prev" id="certNavPrev">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                        <div class="cert-modal-img-wrap">
                            <img id="certModalImg" src="" alt="شهادة" />
                        </div>
                        <button type="button" class="cert-nav cert-nav-next" id="certNavNext">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                    </div>

                    <!-- Thumbnails -->
                    <div class="cert-modal-thumbs" id="certModalThumbs"></div>
                </div>
            </div>
        </div>

        <!-- Payment Modal -->
        <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content rounded-4 p-0 overflow-hidden border-0 shadow-lg">
                    <div class="position-relative"
                        style="
              background: linear-gradient(135deg, var(--primary), #094c5a);
              padding: 2.5rem 1.5rem 1.5rem;
            ">
                        <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3"
                            data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                        <div class="text-center">
                            <div class="d-inline-flex align-items-center justify-content-center bg-white bg-opacity-15 rounded-circle mb-3"
                                id="paymentModalIcon" style="width: 72px; height: 72px; backdrop-filter: blur(4px)">
                                <i class="fa-solid fa-wallet fs-2 text-white"></i>
                            </div>
                            <h5 class="fw-bold text-white mb-1" id="paymentModalTitle"></h5>
                            <p class="small text-white opacity-70 mb-0" id="paymentModalDesc"></p>
                        </div>
                    </div>
                    <div class="p-4 text-center">
                        <div
                            class="bg-light rounded-3 p-3 mb-3 d-flex align-items-center justify-content-between border border-primary border-opacity-10">
                            <span class="fw-bold text-heading direction-ltr" id="paymentModalValue"
                                style="font-size: 1.05rem"></span>
                            <button class="btn btn-primary px-4 rounded-3 d-flex align-items-center gap-2"
                                id="paymentModalCopy">
                                <i class="fa-solid fa-copy"></i>{{ __('messages.copy') }}
                            </button>
                        </div>
                        <p class="small text-secondary opacity-60 mb-0">
                            <i class="fa-solid fa-circle-info ml-1"></i>{{ __('messages.payment_modal_disclaimer') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endsection

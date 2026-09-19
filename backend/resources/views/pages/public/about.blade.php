@extends('layouts.public')

@section('title', __('messages.page_about_title', ['site_name' => __('messages.site_name')]))
@section('meta_description', __('messages.page_about_meta'))
@section('meta_robots', 'index, follow')

@section('content')
      <div class="page-hero text-center" data-aos="fade-up"><div class="hero-orb hero-orb-1"></div><div class="hero-orb hero-orb-2"></div><div class="hero-dot hero-dot-1"></div><div class="hero-dot hero-dot-2"></div><div class="hero-dot hero-dot-3"></div>
        <div class="container">
          <div class="page-hero-icon"><i class="fa-solid fa-circle-info"></i></div>
          <h1 class="fw-bold">{{ $contents['about_title']->value ?? __('messages.page_about') }}</h1>
          <p>{{ __('messages.about_subtitle') }}</p>
        </div>
      </div>

      <div class="container py-5">
        <div class="row justify-content-center">
          <div class="col-lg-10">
            <div class="legal-card" data-aos="fade-up">
              {!! $contents['about_content']->value ?? '' !!}
            </div>
          </div>
        </div>

        <div class="row g-4 mt-4">
          <div class="col-md-6" data-aos="fade-up">
            <div class="legal-card h-100">
              <div class="d-flex align-items-center gap-3 mb-3">
                <span class="d-flex align-items-center justify-content-center rounded-3" style="width: 52px; height: 52px; min-width: 52px; background: rgba(15,109,128,0.06); color: #0F6D80; font-size: 1.3rem;"><i class="fa-solid fa-eye"></i></span>
                <h4 class="fw-bold mb-0" style="color: #0F6D80;">{{ __('messages.about_vision') }}</h4>
              </div>
              <p class="text-secondary opacity-75 lh-lg mb-0">{{ $contents['vision']->value ?? '' }}</p>
            </div>
          </div>
          <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="legal-card h-100">
              <div class="d-flex align-items-center gap-3 mb-3">
                <span class="d-flex align-items-center justify-content-center rounded-3" style="width: 52px; height: 52px; min-width: 52px; background: rgba(15,109,128,0.06); color: #0F6D80; font-size: 1.3rem;"><i class="fa-solid fa-bullseye"></i></span>
                <h4 class="fw-bold mb-0" style="color: #0F6D80;">{{ __('messages.about_mission') }}</h4>
              </div>
              <p class="text-secondary opacity-75 lh-lg mb-0">{{ $contents['mission']->value ?? '' }}</p>
            </div>
          </div>
        </div>

        <div class="row g-4 mt-4" data-aos="fade-up">
          <h3 class="fw-bold text-center mb-4" style="color: #0F6D80;">{{ __('messages.about_why_title') }}</h3>
          <div class="col-md-6 col-lg-3">
            <div class="legal-card h-100 text-center">
              <span class="d-flex align-items-center justify-content-center rounded-3 mx-auto mb-3" style="width: 56px; height: 56px; background: rgba(15,109,128,0.06); color: #0F6D80; font-size: 1.3rem;"><i class="fa-solid fa-shield-halved"></i></span>
              <h5 class="fw-bold small mb-2">{{ $contents['why_us_1_title']->value ?? '' }}</h5>
              <p class="small text-secondary opacity-75 mb-0">{{ $contents['why_us_1_desc']->value ?? '' }}</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-3">
            <div class="legal-card h-100 text-center">
              <span class="d-flex align-items-center justify-content-center rounded-3 mx-auto mb-3" style="width: 56px; height: 56px; background: rgba(15,109,128,0.06); color: #0F6D80; font-size: 1.3rem;"><i class="fa-solid fa-star"></i></span>
              <h5 class="fw-bold small mb-2">{{ $contents['why_us_2_title']->value ?? '' }}</h5>
              <p class="small text-secondary opacity-75 mb-0">{{ $contents['why_us_2_desc']->value ?? '' }}</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-3">
            <div class="legal-card h-100 text-center">
              <span class="d-flex align-items-center justify-content-center rounded-3 mx-auto mb-3" style="width: 56px; height: 56px; background: rgba(15,109,128,0.06); color: #0F6D80; font-size: 1.3rem;"><i class="fa-solid fa-user-graduate"></i></span>
              <h5 class="fw-bold small mb-2">{{ $contents['why_us_3_title']->value ?? '' }}</h5>
              <p class="small text-secondary opacity-75 mb-0">{{ $contents['why_us_3_desc']->value ?? '' }}</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-3">
            <div class="legal-card h-100 text-center">
              <span class="d-flex align-items-center justify-content-center rounded-3 mx-auto mb-3" style="width: 56px; height: 56px; background: rgba(15,109,128,0.06); color: #0F6D80; font-size: 1.3rem;"><i class="fa-solid fa-cubes"></i></span>
              <h5 class="fw-bold small mb-2">{{ $contents['why_us_4_title']->value ?? '' }}</h5>
              <p class="small text-secondary opacity-75 mb-0">{{ $contents['why_us_4_desc']->value ?? '' }}</p>
            </div>
          </div>
        </div>
      </div>
@endsection

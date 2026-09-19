@extends('layouts.public')

@section('title', __('messages.champions_heading') . ' | ' . __('messages.site_name'))
@section('meta_description', __('messages.champions_subtitle'))
@section('meta_robots', 'index, follow')

@section('content')
      <div class="page-hero text-center" data-aos="fade-up"><div class="hero-orb hero-orb-1"></div><div class="hero-orb hero-orb-2"></div><div class="hero-dot hero-dot-1"></div><div class="hero-dot hero-dot-2"></div><div class="hero-dot hero-dot-3"></div>
        <div class="container">
          <div class="page-hero-icon"><i class="fa-solid fa-trophy"></i></div>
          <h1 class="fw-bold">{{ __('messages.champions_heading') }}</h1>
          <p>{{ __('messages.champions_subtitle') }}</p>
        </div>
      </div>

      <div class="container py-5">
        <div class="row g-4">
          @forelse($champions as $champion)
          <div class="col-md-6 col-lg-3" data-aos="fade-up">
            <div class="bg-light rounded-4 p-4 border border-primary border-opacity-10 text-center shadow-sm h-100">
              <div class="mx-auto mb-3 rounded-circle border border-4 border-primary p-1" style="width: 96px; height: 96px">
                <img alt="{{ $champion->name }}" class="w-100 h-100 rounded-circle bg-light" src="{{ $champion->image ? asset('storage/' . $champion->image) : url('/images/default-avatar.png') }}" />
              </div>
              <h4 class="fw-bold text-heading mb-1">{{ $champion->name }}</h4>
              <p class="text-primary fw-bold mb-3">{{ $champion->achievement }}</p>
              <div class="d-flex justify-content-center">
                @switch($champion->icon)
                  @case('star') <i class="fa-solid fa-star text-accent fs-4"></i> @break
                  @case('medal') <i class="fa-solid fa-medal text-primary fs-4"></i> @break
                  @case('music') <i class="fa-solid fa-music text-primary fs-4"></i> @break
                  @case('users') <i class="fa-solid fa-users text-primary fs-4"></i> @break
                  @case('trophy') <i class="fa-solid fa-trophy text-primary fs-4"></i> @break
                  @case('crown') <i class="fa-solid fa-crown text-primary fs-4"></i> @break
                  @default <i class="fa-solid fa-star text-accent fs-4"></i>
                @endswitch
              </div>
            </div>
          </div>
          @empty
          <div class="col-12 text-center py-5">
            <i class="fa-solid fa-star fs-1 text-secondary opacity-25 mb-3 d-block"></i>
            <p class="text-secondary">لا يوجد نجوم حتى الآن</p>
          </div>
          @endforelse
        </div>
      </div>
@endsection

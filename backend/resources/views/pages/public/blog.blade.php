@extends('layouts.public')

@section('title', __('messages.page_blog_title', ['site_name' => __('messages.site_name')]))
@section('meta_description', __('messages.page_blog_meta', ['site_name' => __('messages.site_name')]))
@section('meta_robots', 'index, follow')

@section('content')
      <div class="page-hero text-center" data-aos="fade-up"><div class="hero-orb hero-orb-1"></div><div class="hero-orb hero-orb-2"></div><div class="hero-dot hero-dot-1"></div><div class="hero-dot hero-dot-2"></div><div class="hero-dot hero-dot-3"></div>
        <div class="container">
          <div class="page-hero-icon"><i class="fa-solid fa-newspaper"></i></div>
          <h1 class="fw-bold">{{ __('messages.page_blog') }}</h1>
          <p>{{ __('messages.blog_subtitle') }}</p>
        </div>
      </div>

      <div class="container py-5">
        <div class="row g-4">
          @forelse($posts as $i => $post)
          <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ 100 + ($i % 3) * 100 }}">
            <div class="blog-card">
              <div class="blog-card-img"><img src="{{ asset($post->image ?? 'images/logo.png') }}" alt="{{ $post->title }}" loading="lazy" /></div>
              <div class="blog-card-body">
                <span class="blog-card-tag">{{ $post->tag }}</span>
                <h3 class="blog-card-title">{{ $post->title }}</h3>
                <p class="blog-card-text">{{ Str::limit(strip_tags($post->content), 80) }}</p>
                <a href="{{ route('blog-post', ['slug' => $post->slug]) }}" class="blog-card-link">{{ __('messages.blog_read_more') }} <i class="fa-solid fa-arrow-left"></i></a>
              </div>
            </div>
          </div>
          @empty
          <div class="col-12 text-center py-5">
            <i class="fa-solid fa-newspaper fs-1 text-secondary opacity-25 mb-3 d-block"></i>
            <p class="text-secondary">{{ __('messages.blog_no_posts') }}</p>
          </div>
          @endforelse
        </div>
      </div>
@endsection

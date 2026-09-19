@extends('layouts.public')

@section('title', __('messages.page_certificates_title', ['site_name' => __('messages.site_name')]))
@section('meta_description', __('messages.page_certificates_meta', ['site_name' => __('messages.site_name')]))
@section('meta_robots', 'index, follow')

@section('content')
      <div class="page-hero text-center" data-aos="fade-up"><div class="hero-orb hero-orb-1"></div><div class="hero-orb hero-orb-2"></div><div class="hero-dot hero-dot-1"></div><div class="hero-dot hero-dot-2"></div><div class="hero-dot hero-dot-3"></div>
        <div class="container">
          <div class="page-hero-icon"><i class="fa-solid fa-certificate"></i></div>
          <h1 class="fw-bold">{{ session('locale', 'ar') === 'en' ? 'All Certificates' : 'كل الشهادات' }}</h1>
          <p>{{ session('locale', 'ar') === 'en' ? 'Browse the full gallery of certificates' : 'تصفحي معرض الشهادات الكامل' }}</p>
        </div>
      </div>

      <section class="py-5 position-relative overflow-hidden" data-aos="fade-up">
        <div class="position-absolute top-0 start-0 w-100 h-100 opacity-50 islamic-pattern"></div>
        <div class="container">
          <div class="text-center mb-5">
            <h2 class="section-title">{{ __('messages.cert_gallery_heading') }}</h2>
            <p class="section-subtitle">{{ __('messages.cert_gallery_subtitle') }}</p>
          </div>
          @push('styles')
            <style>
              .cert-grid-all { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
              .cert-grid-all .cert-gallery-card { transform: none; }
              .cert-grid-all .cert-gallery-card:hover { transform: translateY(-6px) !important; }
              .cert-grid-all .cert-gallery-media { aspect-ratio: auto; }
              .cert-grid-all .cert-gallery-media img { object-fit: contain; height: auto; }
              @media (max-width: 991.98px) { .cert-grid-all { grid-template-columns: repeat(2, 1fr); } }
              @media (max-width: 575.98px) { .cert-grid-all { grid-template-columns: 1fr; gap: 16px; } }

              .cert-lightbox {
                position: fixed;
                inset: 0;
                z-index: 1900;
                background: rgba(6, 40, 47, 0.94);
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.3s, visibility 0.3s;
              }
              .cert-lightbox.cert-lb-open { opacity: 1; visibility: visible; }
              .cert-lightbox-frame {
                background: #fff;
                border-radius: 18px;
                padding: 18px;
                max-width: min(94vw, 900px);
                width: 100%;
                max-height: 94vh;
                display: flex;
                flex-direction: column;
              }
              .cert-lb-top {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
                padding-bottom: 12px;
              }
              .cert-lb-title {
                margin: 0;
                font-weight: 800;
                font-size: 1.05rem;
                color: #0b5363;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
              }
              .cert-lb-count { font-size: 0.8rem; font-weight: 700; color: #d89b1d; white-space: nowrap; }
              .cert-lb-close {
                border: none;
                background: transparent;
                font-size: 1.2rem;
                color: #0b5363;
                line-height: 1;
                padding: 4px 8px;
              }
              .cert-lb-stage {
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
                flex: 1 1 auto;
                min-height: 0;
              }
              .cert-lb-stage img {
                max-width: 100%;
                max-height: 72vh;
                border-radius: 10px;
                display: block;
              }
              .cert-lb-nav {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                width: 42px;
                height: 42px;
                border-radius: 50%;
                border: none;
                background: rgba(255, 255, 255, 0.16);
                color: #fff;
                font-size: 1rem;
                z-index: 2;
                transition: all 0.25s;
              }
              .cert-lb-nav:hover { background: #d89b1d; color: #083a45; }
              .cert-lb-prev { inset-inline-start: 8px; }
              .cert-lb-next { inset-inline-end: 8px; }
              @media (max-width: 575.98px) {
                .cert-lb-nav { width: 34px; height: 34px; font-size: 0.8rem; }
              }
            </style>
          @endpush
          <script>
            window.CERT_DATA = @json($certificates->map(fn($c) => ['src' => $c->image_url, 'title' => $c->title])->values());
          </script>
          <div class="cert-grid-all" id="certAllGrid">
            @forelse($certificates as $cert)
              <div class="cert-gallery-card" data-cert-index="{{ $loop->index }}">
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

          <div class="cert-lightbox" id="certLightbox" aria-hidden="true">
            <div class="cert-lightbox-frame">
              <div class="cert-lb-top">
                <p class="cert-lb-title" id="certLbTitle">شهادة</p>
                <span class="cert-lb-count" id="certLbCount"></span>
                <button type="button" class="cert-lb-close" id="certLbClose" aria-label="{{ __('messages.close') }}">
                  <i class="fa-solid fa-xmark"></i>
                </button>
              </div>
              <div class="cert-lb-stage">
                <button type="button" class="cert-lb-nav cert-lb-prev" id="certLbPrev" aria-label="previous">
                  <i class="fa-solid fa-chevron-right"></i>
                </button>
                <img id="certLbImg" src="" alt="شهادة" />
                <button type="button" class="cert-lb-nav cert-lb-next" id="certLbNext" aria-label="next">
                  <i class="fa-solid fa-chevron-left"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>

      @push('scripts')
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            var grid = document.getElementById('certAllGrid');
            var lb = document.getElementById('certLightbox');
            if (!grid || !lb || !window.CERT_DATA || !window.CERT_DATA.length) return;
            var data = window.CERT_DATA;
            var n = data.length;
            var idx = 0;
            var img = document.getElementById('certLbImg');
            var title = document.getElementById('certLbTitle');
            var count = document.getElementById('certLbCount');

            function show(i) {
              idx = (i + n) % n;
              img.src = data[idx].src;
              img.alt = data[idx].title;
              title.textContent = data[idx].title;
              count.textContent = (idx + 1) + ' / ' + n;
            }
            function open(i) {
              show(i);
              lb.classList.add('cert-lb-open');
              document.body.style.overflow = 'hidden';
            }
            function close() {
              lb.classList.remove('cert-lb-open');
              document.body.style.overflow = '';
            }

            grid.querySelectorAll('[data-cert-index]').forEach(function(card) {
              card.addEventListener('click', function() {
                open(parseInt(card.dataset.certIndex, 10));
              });
            });
            document.getElementById('certLbClose').addEventListener('click', close);
            document.getElementById('certLbPrev').addEventListener('click', function() { show(idx - 1); });
            document.getElementById('certLbNext').addEventListener('click', function() { show(idx + 1); });
            lb.addEventListener('click', function(e) {
              if (e.target === lb) close();
            });
            document.addEventListener('keydown', function(e) {
              if (!lb.classList.contains('cert-lb-open')) return;
              if (e.key === 'Escape') close();
              else if (e.key === 'ArrowLeft') show(idx + 1);
              else if (e.key === 'ArrowRight') show(idx - 1);
            });
          });
        </script>
      @endpush
@endsection
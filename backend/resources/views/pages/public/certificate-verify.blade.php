@extends('layouts.public')

@section('title', __('messages.certificate_verify_title') . ' | ' . __('messages.site_name'))
@section('meta_description', __('messages.certificate_verify_meta'))
@section('meta_robots', 'noindex, nofollow')

@push('styles')
<style>
      :root { --cv-teal: #0F6D80; --cv-teal-dark: #0a4a56; --cv-teal-mid: #157a8c; --cv-gold: #b98a2b; --cv-gold-light: #e8c66a; --cv-ink: #23303a; --cv-muted: #6b7a7e; }

      .cv-toolbar { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 10px; margin-bottom: 24px; }
      .cv-btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; border-radius: 10px; font-size: .9rem; font-weight: 600; cursor: pointer; text-decoration: none; border: none; font-family: inherit; }
      .cv-btn-print { background: linear-gradient(135deg, var(--cv-teal), var(--cv-teal-mid)); color: #fff; }
      .cv-btn-print:hover { color: #fff; }
      .cv-btn-ghost { background: #fff; color: var(--cv-teal); border: 1.5px solid rgba(15,109,128,0.25); }

      /* ===== Status banner ===== */
      .cv-banner { display: flex; flex-wrap: wrap; align-items: center; gap: 10px; justify-content: center; background: rgba(15,109,128,0.05); border: 1px solid rgba(15,109,128,0.12); border-radius: 14px; padding: 12px 22px; margin: 0 auto 28px; max-width: 720px; }
      .cv-banner.ok { background: rgba(15,109,128,0.06); border-color: rgba(15,109,128,0.2); }
      .cv-banner.bad { background: rgba(220,53,69,0.05); border-color: rgba(220,53,69,0.18); }
      .cv-banner .dot { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: .95rem; }
      .cv-banner.ok .dot { background: linear-gradient(135deg, var(--cv-teal), var(--cv-teal-mid)); }
      .cv-banner.bad .dot { background: linear-gradient(135deg, #dc3545, #e05a6d); }
      .cv-banner .msg { font-weight: 700; color: var(--cv-ink); font-size: .95rem; }
      .cv-banner .code-chip { font-weight: 800; letter-spacing: 1px; color: var(--cv-teal); background: #fff; border: 1px solid rgba(15,109,128,0.2); border-radius: 20px; padding: 4px 14px; font-size: .82rem; direction: ltr; }

      /* ===== Certificate sheet ===== */
      .cert-sheet { background: #fff; border-radius: 20px; position: relative; overflow: hidden; }
      .cert-frame { position: relative; padding: 46px 46px 30px; }
      .cert-frame::before, .cert-frame::after { content: ""; position: absolute; pointer-events: none; }
      .cert-frame::before { inset: 12px; border: 2px solid rgba(185,138,43,0.55); border-radius: 14px; }
      .cert-frame::after { inset: 17px; border: 1px solid rgba(15,109,128,0.28); border-radius: 11px; }
      .cert-corner { position: absolute; color: rgba(185,138,43,0.7); font-size: 1.15rem; z-index: 2; }
      .cert-corner.tl { top: 24px; right: 24px; }
      .cert-corner.tr { top: 24px; left: 24px; transform: scaleX(-1); }
      .cert-corner.bl { bottom: 42px; right: 24px; transform: scaleY(-1); }
      .cert-corner.br { bottom: 42px; left: 24px; transform: scale(-1); }
      .cert-watermark { position: absolute; top: 50%; right: 50%; transform: translate(50%, -50%); font-size: 13rem; color: rgba(15,109,128,0.045); z-index: 0; }
      .cert-inner { position: relative; z-index: 1; }

      .cert-logo { width: 84px; height: 84px; border-radius: 50%; object-fit: contain; margin: 0 auto 10px; display: block; background: #fff; border: 2px solid rgba(185,138,43,0.45); padding: 8px; box-shadow: 0 6px 18px rgba(15,109,128,0.12); }
      .cert-company { text-align: center; font-weight: 700; font-size: 1.02rem; color: var(--cv-teal-dark); }
      .cert-company-sub { text-align: center; font-size: .72rem; color: var(--cv-muted); letter-spacing: .5px; }
      .cert-heading { text-align: center; font-size: 3rem; font-weight: 900; margin: 18px 0 6px; background: linear-gradient(135deg, var(--cv-gold), #d8b051 45%, var(--cv-gold)); -webkit-background-clip: text; background-clip: text; color: transparent; line-height: 1.1; }
      .cert-heading-row { display: flex; align-items: center; justify-content: center; gap: 14px; }
      .cert-heading-row i { color: var(--cv-gold); font-size: 1.1rem; }
      .cert-heading-row .line { flex: 0 0 90px; height: 2px; background: linear-gradient(90deg, transparent, rgba(185,138,43,0.8)); }
      .cert-heading-row .line:last-child { transform: scaleX(-1); }

      .cert-body { text-align: center; font-size: 1rem; color: var(--cv-ink); margin: 22px auto 0; max-width: 640px; line-height: 2; }
      .cert-name { text-align: center; font-size: 2.2rem; font-weight: 900; color: var(--cv-teal); margin: 6px 0 2px; }
      .cert-title { text-align: center; font-size: 1.18rem; font-weight: 800; color: var(--cv-teal-dark); margin: 14px auto 6px; max-width: 640px; line-height: 1.8; }

      .cert-meta { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; max-width: 700px; margin: 30px auto 0; }
      .cert-meta-item { border: 1px solid rgba(15,109,128,0.12); background: rgba(15,109,128,0.03); border-radius: 10px; padding: 10px 12px; text-align: center; }
      .cert-meta-item b { display: block; font-size: .95rem; color: var(--cv-ink); font-weight: 800; }
      .cert-meta-item span { display: block; font-size: .72rem; color: var(--cv-muted); margin-bottom: 4px; font-weight: 600; }

      .cert-signs { display: flex; justify-content: space-around; max-width: 620px; margin: 46px auto 0; }
      .cert-sign { text-align: center; min-width: 170px; }
      .cert-sign .seal { width: 64px; height: 64px; border-radius: 50%; border: 2px solid rgba(185,138,43,0.7); color: var(--cv-gold); display: flex; align-items: center; justify-content: center; font-size: 1.6rem; margin: 0 auto 10px; background: rgba(185,138,43,0.06); }
      .cert-sign .rev { border-top: 1.5px dashed rgba(107,122,126,0.6); padding-top: 8px; font-size: .82rem; font-weight: 700; color: var(--cv-ink); }

      /* ===== Image certificate ===== */
      .cert-image-wrap { border: 2px solid rgba(185,138,43,0.5); border-radius: 16px; padding: 10px; background: #fff; box-shadow: 0 14px 40px rgba(15,109,128,0.12); }
      .cert-image-wrap img { max-width: 100%; max-height: 78vh; border-radius: 10px; display: block; margin: 0 auto; }

      /* ===== Search box (not found) ===== */
      .cv-search { max-width: 560px; margin: 0 auto; background: #fff; border: 1px solid rgba(15,109,128,0.12); border-radius: 16px; padding: 26px; box-shadow: 0 8px 26px rgba(15,109,128,0.08); }
      .cv-search h5 { font-weight: 800; color: var(--cv-ink); }
      .cv-search input { direction: ltr; text-align: center; letter-spacing: 1px; }
      .cert-container { max-width: 880px; margin: 0 auto; }

      @media (max-width: 768px) {
        .cert-frame { padding: 30px 18px 20px; }
        .cert-heading { font-size: 2.1rem; }
        .cert-name { font-size: 1.6rem; }
        .cert-meta { grid-template-columns: 1fr; }
        .cert-meta-item { width: 100%; }
      }

      @media print {
        body, .public-page { background: #fff !important; }
        .cv-toolbar, .cv-banner, .page-hero, .cv-search { display: none !important; }
        .cert-container { max-width: 100%; }
        .cert-sheet, .cert-frame { box-shadow: none; border-radius: 0; }
        .cert-frame { padding: 26px; }
        .cert-watermark, .cert-corner { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        @page { size: A4 landscape; margin: 8mm; }
      }
  </style>
@endpush

@section('content')
      <div class="page-hero text-center" data-aos="fade-up"><div class="hero-orb hero-orb-1"></div><div class="hero-orb hero-orb-2"></div><div class="hero-dot hero-dot-1"></div><div class="hero-dot hero-dot-2"></div><div class="hero-dot hero-dot-3"></div>
        <div class="container">
          <div class="page-hero-icon"><i class="fa-solid fa-certificate"></i></div>
          <h1 class="fw-bold">{{ __('messages.certificate_verify_title') }}</h1>
          <p>{{ __('messages.certificate_verify_meta') }}</p>
        </div>
      </div>

      <div class="container py-5">
        <div class="cert-container">

          @if($certificate)
          <div class="cv-toolbar">
            <button class="cv-btn cv-btn-print" onclick="window.print()"><i class="fa-solid fa-print"></i>{{ __('messages.certificate_verify_print') }}</button>
            <a class="cv-btn cv-btn-ghost" href="#" onclick="document.getElementById('verifyAnother').scrollIntoView({behavior:'smooth'});return false;"><i class="fa-solid fa-magnifying-glass"></i>{{ __('messages.certificate_verify_another') }}</a>
          </div>

          <div class="cv-banner ok">
            <div class="dot"><i class="fa-solid fa-check"></i></div>
            <div class="msg">{{ __('messages.certificate_verify_valid') }}</div>
            <span class="code-chip">{{ $certificate->verification_code }}</span>
          </div>

          @if($certificate->image_url)
          <div class="cert-image-wrap">
            <img src="{{ $certificate->image_url }}" alt="{{ $certificate->title }}">
          </div>
          <div class="cert-meta" style="margin:26px auto 0;">
            <div class="cert-meta-item"><span>{{ __('messages.certificate_verify_student_label') }}</span><b>{{ $certificate->student?->name_ar ?? '—' }}</b></div>
            <div class="cert-meta-item"><span>{{ __('messages.certificate_verify_course_label') }}</span><b>{{ $certificate->course?->name ?? '—' }}</b></div>
            <div class="cert-meta-item"><span>{{ __('messages.certificate_verify_date_label') }}</span><b>{{ $certificate->issued_at ? \Carbon\Carbon::parse($certificate->issued_at)->format('Y/m/d') : '—' }}</b></div>
          </div>
          @else
          <div class="cert-sheet">
            <div class="cert-frame">
              <i class="fa-solid fa-bahai cert-corner tl"></i>
              <i class="fa-solid fa-bahai cert-corner tr"></i>
              <i class="fa-solid fa-bahai cert-corner bl"></i>
              <i class="fa-solid fa-bahai cert-corner br"></i>
              <div class="cert-watermark"><i class="fa-solid fa-certificate"></i></div>
              <div class="cert-inner">
                <img src="{{ asset('images/logo.png') }}" alt="{{ __('messages.site_name') }}" class="cert-logo">
                <div class="cert-company">{{ __('messages.site_name') }}</div>
                <div class="cert-company-sub">{{ __('messages.certificate_verify_hereby') }}</div>

                <div class="cert-heading-row"><span class="line"></span><i class="fa-solid fa-star"></i><span class="line"></span></div>
                <div class="cert-heading">{{ __('messages.certificate_verify_badge') }}</div>

                <div class="cert-body">
                  {!! __('messages.certificate_verify_body', ['company' => '<b>' . e(__('messages.site_name')) . '</b>', 'student' => '<b>' . e($certificate->student?->name_ar ?? '—') . '</b>']) !!}
                </div>
                <div class="cert-title">{{ $certificate->title }}</div>
                <div class="cert-body" style="margin-top:4px;">{{ __('messages.certificate_verify_cert_phrase') }}</div>

                <div class="cert-meta">
                  <div class="cert-meta-item"><span>{{ __('messages.certificate_verify_course_label') }}</span><b>{{ $certificate->course?->name ?? '—' }}</b></div>
                  <div class="cert-meta-item"><span>{{ __('messages.certificate_verify_date_label') }}</span><b>{{ $certificate->issued_at ? \Carbon\Carbon::parse($certificate->issued_at)->format('Y/m/d') : '—' }}</b></div>
                  <div class="cert-meta-item"><span>{{ __('messages.certificate_verify_code_label') }}</span><b style="direction:ltr;letter-spacing:1px;">{{ $certificate->verification_code }}</b></div>
                </div>

                <div class="cert-signs">
                  <div class="cert-sign">
                    <div class="seal"><i class="fa-solid fa-award"></i></div>
                    <div class="rev">{{ __('messages.certificate_verify_sign_name') }}</div>
                  </div>
                  <div class="cert-sign">
                    <div class="seal"><i class="fa-solid fa-circle-check" style="color:#198754;border-color:#198754;"></i></div>
                    <div class="rev">{{ __('messages.certificate_verify_code_label') }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endif
          @else
          <div class="cv-banner bad">
            <div class="dot"><i class="fa-solid fa-xmark"></i></div>
            <div class="msg">{{ __('messages.certificate_verify_not_found') }}</div>
          </div>
          @endif

          <div id="verifyAnother" class="cv-search mt-5" data-aos="fade-up">
            <h5 class="mb-1 text-center">{{ __('messages.certificate_verify_another') }}</h5>
            <div class="d-flex gap-2 mt-3">
              <input type="text" id="verifyCodeInput" class="form-control" placeholder="{{ __('messages.certificate_verify_code_placeholder') }}">
              <button class="cv-btn cv-btn-print" onclick="verifyCode()"><i class="fa-solid fa-magnifying-glass"></i>{{ __('messages.certificate_verify_check') }}</button>
            </div>
          </div>

        </div>
      </div>
@endsection

@push('scripts')
<script>
function verifyCode() {
  var code = document.getElementById('verifyCodeInput').value.trim();
  if (!code) return;
  window.location.href = '/' + encodeURIComponent(code);
}
</script>
@endpush
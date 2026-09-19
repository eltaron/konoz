@extends('layouts.student')

@section('title', __('messages.student_certificates_title'))

@section('meta_description', __('messages.student_meta_desc', ['site_name' => __('messages.site_name')]))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard/dashboard-certificates.css') }}" />
<style>
.badge-progress-ring circle { fill: none; stroke-width: 3; }
</style>
@endpush

@section('content')
<h1 class="fw-bold mb-1" style="color: #0F6D80; font-size: 1.6rem;" data-aos="fade-up">{{ __('messages.student_certificates_title') }}</h1>
      <p class="text-secondary opacity-75 mb-4" data-aos="fade-up">{{ __('messages.student_certificates_subtitle') }}</p>

      <!-- Stats -->
      <div class="row g-3 mb-4" data-aos="fade-up">
        <div class="col-4">
          <div class="dash-card text-center">
            <h3 class="fw-bold mb-0" style="color: #0F6D80;">{{ $stats->delivered ?? $certificates->where('status','delivered')->count() }}</h3>
            <small class="text-secondary opacity-75">{{ __('messages.student_certificates_delivered') }}</small>
          </div>
        </div>
        <div class="col-4">
          <div class="dash-card text-center">
            <h3 class="fw-bold mb-0" style="color: var(--pumpkin);">{{ $stats->total ?? $certificates->count() }}</h3>
            <small class="text-secondary opacity-75">{{ __('messages.student_certificates_certs') }}</small>
          </div>
        </div>
        <div class="col-4">
          <div class="dash-card text-center">
            <h3 class="fw-bold mb-0" style="color: #0F6D80;">{{ $stats->pending ?? $certificates->where('status','pending')->count() }}</h3>
            <small class="text-secondary opacity-75">{{ __('messages.student_certificates_pending') }}</small>
          </div>
        </div>
      </div>

      <!-- Certificates Cards -->
      <div class="row g-3">
        @forelse($certificates as $cert)
        <div class="col-md-6 mb-3">
          <div class="cert-card h-100">
            <div class="d-flex align-items-start gap-3">
              <i class="fa-solid fa-certificate fa-2x" style="color:#0F6D80;"></i>
              <div class="flex-grow-1">
                <h6 class="fw-bold mb-1">{{ $cert->title }}</h6>
                <p class="small text-secondary opacity-75 mb-1">{{ $cert->course->name ?? '' }}</p>
                <span class="small text-secondary opacity-50">
                  <i class="fa-regular fa-calendar ml-1"></i>
                  {{ $cert->issued_at ? \Carbon\Carbon::parse($cert->issued_at)->format('Y/m/d') : '----' }}
                </span>
              </div>
              <span class="teacher-badge {{ $cert->status === 'delivered' ? 'teacher-badge-success' : 'teacher-badge-warning' }}">
                {{ $cert->status === 'delivered' ? __('messages.student_certificates_status_delivered') : __('messages.student_certificates_status_pending') }}
              </span>
            </div>
          </div>
        </div>
        @empty
        <div class="col-12">
          <p class="text-center text-secondary opacity-75 my-5">{{ __('messages.student_certificates_empty') }}</p>
        </div>
        @endforelse
      </div>

      <!-- Badges -->
      <div class="dash-card mt-4" data-aos="fade-up">
        <h5 class="fw-bold mb-4" style="color: #0F6D80;"><i class="fa-solid fa-medal me-2"></i>{{ __('messages.student_certificates_badges_title') }}</h5>

        @php
          $badges = [
            (object)[
              'title' => __('messages.badge_persistent_hafidh'), 'icon' => 'fa-fire', 'color' => '#e74c3c', 'bg' => '#fef2f2',
              'desc' => __('messages.badge_persistent_hafidh_desc'), 'earned' => ($streakDays ?? 0) >= 30,
              'progress' => min(($streakDays ?? 0) / 30 * 100, 100), 'current' => ($streakDays ?? 0), 'target' => 30,
            ],
            (object)[
              'title' => __('messages.badge_recitation_star'), 'icon' => 'fa-star', 'color' => '#f39c12', 'bg' => '#fffbeb',
              'desc' => __('messages.badge_recitation_star_desc'), 'earned' => ($certificates->where('status','delivered')->count() ?? 0) >= 1,
              'progress' => min(($certificates->where('status','delivered')->count() ?? 0) / 1 * 100, 100),
              'current' => ($certificates->where('status','delivered')->count() ?? 0), 'target' => 1,
            ],
            (object)[
              'title' => __('messages.badge_excellence'), 'icon' => 'fa-crown', 'color' => '#0F6D80', 'bg' => '#f0f7f9',
              'desc' => __('messages.badge_excellence_desc'), 'earned' => false,
              'progress' => 0, 'current' => 0, 'target' => 1,
            ],
            (object)[
              'title' => __('messages.badge_diligence'), 'icon' => 'fa-book-open', 'color' => '#8e44ad', 'bg' => '#f8f4fc',
              'desc' => __('messages.badge_diligence_desc'), 'earned' => ($totalMinutes ?? 0) >= 6000,
              'progress' => min(($totalMinutes ?? 0) / 6000 * 100, 100), 'current' => intval(($totalMinutes ?? 0) / 60), 'target' => 100,
            ],
            (object)[
              'title' => __('messages.badge_first_khatmah'), 'icon' => 'fa-check-double', 'color' => '#27ae60', 'bg' => '#f0fdf4',
              'desc' => __('messages.badge_first_khatmah_desc'), 'earned' => ($completedJuz ?? 0) >= 30,
              'progress' => min(($completedJuz ?? 0) / 30 * 100, 100), 'current' => ($completedJuz ?? 0), 'target' => 30,
            ],
            (object)[
              'title' => __('messages.badge_participation'), 'icon' => 'fa-users', 'color' => '#2980b9', 'bg' => '#eff6ff',
              'desc' => __('messages.badge_participation_desc'), 'earned' => false,
              'progress' => 0, 'current' => 0, 'target' => 10,
            ],
          ];
          $earnedCount = collect($badges)->where('earned', true)->count();
        @endphp

        <div class="d-flex align-items-center gap-2 mb-3">
          <span class="small text-secondary opacity-75">
            <span class="fw-bold" style="color:#27ae60;">{{ $earnedCount }}</span> / {{ count($badges) }} {{ __('messages.student_certificates_badge_earned') }}
          </span>
          <div class="flex-grow-1" style="max-width:160px;">
            <div style="height:4px;background:rgba(0,0,0,0.04);border-radius:4px;overflow:hidden;">
              <div style="width:{{ ($earnedCount/count($badges))*100 }}%;height:100%;background:#27ae60;border-radius:4px;transition:width 0.6s;"></div>
            </div>
          </div>
        </div>

        <div class="row g-3">
          @foreach($badges as $badge)
          @php
            $isEarned = $badge->earned;
            $progress = $isEarned ? 100 : $badge->progress;
            $circumference = 2 * pi() * 34;
            $offset = $circumference - ($progress / 100) * $circumference;
          @endphp
          <div class="col-6 col-md-4 col-lg-3">
            <div class="badge-card {{ $isEarned ? 'badge-card-earned' : 'badge-card-locked' }}" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
              <div class="badge-ribbon" style="background:{{ $badge->color }};"></div>

              <div class="badge-card-icon" style="background:{{ $badge->color }}15;color:{{ $badge->color }};">
                @if(!$isEarned && $progress > 0)
                <div class="badge-progress-ring">
                  <svg viewBox="0 0 80 80">
                    <circle class="badge-progress-bg" cx="40" cy="40" r="34"/>
                    <circle class="badge-progress-fg" cx="40" cy="40" r="34"
                      stroke="{{ $badge->color }}" stroke-dasharray="{{ $circumference }}"
                      stroke-dashoffset="{{ $offset }}" style="transition:stroke-dashoffset 0.8s ease;"/>
                  </svg>
                </div>
                @endif
                <i class="fa-solid {{ $badge->icon }}" style="position:relative;z-index:1;"></i>
                @if($isEarned)
                <span class="badge-earned-mark"><i class="fa-solid fa-check"></i></span>
                @endif
              </div>

              @if(!$isEarned)
              <div class="badge-lock-overlay">
                <i class="fa-solid fa-lock"></i>
              </div>
              @endif

              <div class="badge-card-title" style="color:{{ $isEarned ? $badge->color : '#8a9a9e' }};">
                {{ $badge->title }}
              </div>
              <span class="badge-card-desc">{{ $badge->desc }}</span>

              @if($isEarned)
              <span class="badge-card-status badge-status-earned">
                <i class="fa-solid fa-check me-1"></i>{{ __('messages.student_certificates_badge_status_earned') }}
              </span>
              @elseif($progress > 0)
              <span class="badge-card-status badge-status-progress">
                {{ number_format($progress, 0) }}{{ __('messages.student_certificates_badge_progress') }}
              </span>
              <div class="badge-progress-track">
                <div class="badge-progress-fill" style="width:{{ $progress }}%;background:{{ $badge->color }};"></div>
              </div>
              @else
              <span class="badge-card-status badge-status-locked">
                <i class="fa-solid fa-lock me-1"></i>{{ __('messages.student_certificates_badge_locked') }}
              </span>
              @endif
            </div>
          </div>
          @endforeach
        </div>
      </div>

      <!-- Request Certificate -->
@endsection

@push('scripts')
<script>
      AOS.init({ duration: 600, once: true });
</script>
@endpush

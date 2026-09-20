@extends('layouts.student')

@section('title', __('messages.student_title', ['site_name' => __('messages.site_name')]))

@section('meta_description', __('messages.student_meta_desc', ['site_name' => __('messages.site_name')]))

@section('content')
<div class="d-flex flex-column flex-md-row align-items-start align-items-md-end justify-content-between gap-3 mb-4">
  <div>
    <h1 class="fw-bold" style="color: #0F6D80; font-size: 1.8rem;">{{ __('messages.dash_welcome', ['name' => $student->name]) }}</h1>
    <p class="text-secondary opacity-75 mb-0">{{ __('messages.dash_subtitle') }}</p>
  </div>
  <div class="d-flex align-items-center gap-3 px-3 py-2 rounded-3" style="background: rgba(245,189,88,0.06); border: 1px solid rgba(245,189,88,0.12);">
    <span class="d-flex align-items-center justify-content-center rounded-2" style="width: 40px; height: 40px; background: #F5BD58; color: #5f4100;"><i class="fa-solid fa-star"></i></span>
    <div>
      <small class="text-secondary opacity-75">{{ __('messages.dash_completed_parts') }}</small>
      <p class="fw-bold mb-0" style="color: var(--pumpkin);">{{ $completedJuzCount }}/30</p>
    </div>
  </div>
</div>

@if(session('success'))
<div class="alert alert-success d-flex align-items-center gap-2 rounded-3 py-3 px-4 mb-4" style="border: none; background: rgba(25,135,84,0.06); color: #198754;">
  <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="alert alert-danger d-flex align-items-center gap-2 rounded-3 py-3 px-4 mb-4" style="border: none; background: rgba(220,53,69,0.06); color: #dc3545;">
  <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
</div>
@endif

@if(isset($pendingEnrollments) && $pendingEnrollments->count() > 0)
<div class="dash-card mb-4" style="border-right: 4px solid #F5BD58;" data-aos="fade-up">
  <div class="d-flex align-items-center gap-2 mb-3">
    <i class="fa-solid fa-hourglass-half" style="color: #F5BD58;"></i>
    <h5 class="fw-bold mb-0" style="color: #0F6D80; font-size: 0.95rem;">{{ __('messages.dash_pending_title') }}</h5>
  </div>
  @foreach($pendingEnrollments as $req)
  <div class="d-flex align-items-center justify-content-between py-2 px-3 rounded-2" style="background: rgba(245,189,88,0.04); margin-bottom: 6px;">
    <span class="fw-medium small" style="color: #5f4100;">{{ $req->course->name ?? '' }}</span>
    <span class="dash-badge dash-badge-warning">{{ __('messages.dash_pending_badge') }}</span>
  </div>
  @endforeach
</div>
@endif

<div class="row g-3 mb-4" data-aos="fade-up">
  <div class="col-6 col-md-3">
    <div class="dash-card text-center py-3">
      <div class="d-flex align-items-center justify-content-center gap-2 mb-1">
        <i class="fa-regular fa-clock" style="color: #0F6D80;"></i>
        <h3 class="fw-bold mb-0" style="color: #0F6D80;">{{ $todayMinutes }}</h3>
      </div>
      <small class="text-secondary opacity-75">{{ __('messages.dash_today_minutes') }}</small>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="dash-card text-center py-3">
      <div class="d-flex align-items-center justify-content-center gap-2 mb-1">
        <i class="fa-regular fa-calendar-check" style="color: var(--pumpkin);"></i>
        <h3 class="fw-bold mb-0" style="color: var(--pumpkin);">{{ $streakDays }}</h3>
      </div>
      <small class="text-secondary opacity-75">{{ __('messages.dash_streak_days') }}</small>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="dash-card text-center py-3">
      <div class="d-flex align-items-center justify-content-center gap-2 mb-1">
        <i class="fa-solid fa-book-quran" style="color: #27ae60;"></i>
        <h3 class="fw-bold mb-0" style="color: #27ae60;">{{ $completedJuzCount }}</h3>
      </div>
      <small class="text-secondary opacity-75">{{ __('messages.dash_completed_juz') }}</small>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="dash-card text-center py-3">
      <div class="d-flex align-items-center justify-content-center gap-2 mb-1">
        <i class="fa-solid fa-file-pen" style="color: #8e44ad;"></i>
        <h3 class="fw-bold mb-0" style="color: #8e44ad;">{{ $stats->exams_upcoming ?? 0 }}</h3>
      </div>
      <small class="text-secondary opacity-75">{{ __('messages.dash_upcoming_exams') }}</small>
    </div>
  </div>
</div>

<div class="row g-3 mb-4" data-aos="fade-up">
  <div class="col-md-7">
    <div class="dash-card">
      <h6 class="fw-bold mb-0" style="color: #0F6D80;">{{ __('messages.dash_weekly_activity') }}</h6>
      <small class="text-secondary opacity-50 mb-3 d-block">{{ __('messages.dash_last_7_days') }}</small>
      <div class="chart-container" style="position: relative; height: 200px;">
        <canvas id="weeklyChart"></canvas>
      </div>
    </div>
  </div>
  <div class="col-md-5">
    <div class="dash-card">
      <h6 class="fw-bold mb-0" style="color: #0F6D80;">{{ __('messages.dash_hifdh_progress') }}</h6>
      <small class="text-secondary opacity-50 mb-3 d-block">{{ __('messages.dash_juz_remaining') }}</small>
      <div class="chart-container" style="position: relative; height: 200px;">
        <canvas id="juzChart"></canvas>
      </div>
    </div>
  </div>
</div>

@if($upcomingSessions->isNotEmpty())
<div class="dash-card mb-4" data-aos="fade-up">
  <h6 class="fw-bold mb-3" style="color: #0F6D80;"><i class="fa-regular fa-calendar me-2"></i>{{ __('messages.dash_upcoming_sessions') }}</h6>
  <div class="row g-2">
    @foreach($upcomingSessions as $s)
    <div class="col-md-4">
      <div class="d-flex align-items-center gap-2 p-3 rounded-3" style="background:rgba(15,109,128,0.03);border:1px solid rgba(15,109,128,0.06);">
        <div class="text-center flex-shrink-0" style="width:48px;">
          <div class="fw-bold small" style="color:#0F6D80;line-height:1.1;">{{ \Carbon\Carbon::parse($s->date)->format('d') }}</div>
          <small class="text-secondary opacity-50" style="font-size:0.55rem;">{{ \Carbon\Carbon::parse($s->date)->locale(session('locale', 'ar'))->format('D') }}</small>
        </div>
        <div class="min-width-0">
          <div class="small fw-bold" style="color:#0F6D80;">{{ $s->course->name ?? '' }}</div>
          <small class="text-secondary opacity-75" style="font-size:0.65rem;">
            <i class="fa-regular fa-clock ml-1"></i>{{ \Carbon\Carbon::parse($s->start_time ?? '00:00')->format('g:i A') }}
          </small>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endif

@if($myCourses->count() > 0)
<h5 class="fw-bold mb-3" style="color: #0F6D80;">{{ __('messages.dash_my_courses') }}</h5>
<div class="row g-3 mb-4">
  @foreach($myCourses as $course)
  <div class="col-md-6">
    <div class="dash-card h-100" data-aos="fade-up">
      <div class="d-flex align-items-start gap-3">
        <div class="rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(15,109,128,0.06); color: #0F6D80; font-size: 1.2rem;">
          <i class="fa-solid fa-book-quran"></i>
        </div>
        <div class="flex-grow-1 min-width-0">
          <h6 class="fw-bold mb-1" style="color: #0F6D80;">{{ $course->name }}</h6>
          @if($course->instructor_name)
          <p class="small text-secondary opacity-75 mb-1"><i class="fa-regular fa-user me-1"></i>{{ $course->instructor_name }}</p>
          @endif
          @if($course->desc)
          <p class="small text-secondary opacity-50 mb-2">{{ \Illuminate\Support\Str::limit($course->desc, 80) }}</p>
          @endif
          <div class="d-flex align-items-center gap-2 flex-wrap">
            @if($course->level)
            <span class="dash-badge dash-badge-info" style="font-size:0.7rem;">{{ $course->level }}</span>
            @endif
            @if($course->duration)
            <span class="dash-badge dash-badge-info" style="font-size:0.7rem;">{{ $course->duration }}</span>
            @endif
          </div>
        </div>
      </div>
      <hr class="my-2" style="border-color: rgba(15,109,128,0.04);" />
      <div class="d-flex align-items-center justify-content-between">
        <small class="text-secondary opacity-75">
          <i class="fa-regular fa-clock me-1"></i>
          {{ $course->lessons_count ?? $course->sessions_count ?? 0 }} {{ __('messages.dash_lecture') }}
        </small>
        <a href="{{ route('student.course-detail', $course) }}" class="dash-btn dash-btn-primary" style="font-size:0.78rem;padding:5px 14px;">
          <i class="fa-solid fa-arrow-left ml-1"></i>{{ __('messages.dash_view_details') }}
        </a>
      </div>
    </div>
  </div>
  @endforeach
</div>
@else
<div class="dash-card text-center py-5 mb-4">
  <i class="fa-solid fa-graduation-cap" style="font-size: 3rem; color: #d0d9db; margin-bottom: 16px;"></i>
  <h5 class="fw-bold mb-2" style="color: #0F6D80;">{{ __('messages.dash_welcome_no_courses') }}</h5>
  <p class="text-secondary opacity-75 mb-3">{{ __('messages.dash_no_courses_text') }}</p>
  <a href="{{ route('student.courses') }}" class="dash-btn dash-btn-primary">{{ __('messages.dash_browse_courses') }}</a>
</div>
@endif

@if($availableCourses->count() > 0 && $myCourses->count() > 0)
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 mt-2">
  <h5 class="fw-bold mb-0" style="color: #0F6D80;"><i class="fa-solid fa-layer-group me-2" style="color: #F5BD58;"></i>{{ __('messages.dash_other_courses') }}</h5>
  <a href="{{ route('student.courses') }}" class="small fw-semibold" style="color: #0F6D80;">{{ __('messages.dash_browse_courses') }} <i class="fa-solid fa-arrow-left ml-1"></i></a>
</div>
<div class="row g-3 mb-4">
  @foreach($availableCourses as $course)
  <div class="col-md-4">
    <div class="dash-card h-100 d-flex flex-column" data-aos="fade-up">
      <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
        <div class="rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(245,189,88,0.12); color: #B57E10; font-size: 1.1rem;">
          <i class="fa-solid {{ $course->icon ?: 'fa-book-quran' }}"></i>
        </div>
        @if($course->is_free)
        <span class="dash-badge dash-badge-success" style="font-size:0.68rem;">{{ __('messages.dash_course_free') }}</span>
        @endif
      </div>
      <h6 class="fw-bold mb-1" style="color: #0F6D80;">{{ $course->name }}</h6>
      @if($course->instructor_name)
      <p class="small text-secondary opacity-75 mb-1"><i class="fa-regular fa-user me-1"></i>{{ __('messages.dash_course_instructor') }}: {{ $course->instructor_name }}</p>
      @endif
      @if($course->desc)
      <p class="small text-secondary opacity-50 mb-2 flex-grow-1">{{ \Illuminate\Support\Str::limit($course->desc, 80) }}</p>
      @endif
      <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
        @if($course->level)
        <span class="dash-badge dash-badge-info" style="font-size:0.7rem;"><i class="fa-solid fa-layer-group me-1"></i>{{ $course->level }}</span>
        @endif
        @if($course->duration)
        <span class="dash-badge dash-badge-info" style="font-size:0.7rem;"><i class="fa-regular fa-clock me-1"></i>{{ $course->duration }}</span>
        @endif
        @if($course->sessions_per_week)
        <span class="dash-badge dash-badge-info" style="font-size:0.7rem;"><i class="fa-regular fa-calendar me-1"></i>{{ $course->sessions_per_week }} {{ __('messages.dash_session_week') }}</span>
        @endif
      </div>
      <hr class="my-0" style="border-color: rgba(15,109,128,0.04);" />
      <div class="d-flex align-items-center justify-content-between pt-3">
        <a href="{{ route('student.course-detail', $course) }}" class="small fw-semibold" style="color: #0F6D80;"><i class="fa-solid fa-arrow-left ml-1"></i>{{ __('messages.dash_view_details') }}</a>
        <form method="POST" action="{{ route('student.courses.enroll', $course) }}">
          @csrf
          <button class="dash-btn dash-btn-primary" style="font-size:0.75rem;padding:5px 14px;" type="submit"><i class="fa-solid fa-user-plus ml-1"></i>{{ __('messages.dash_request_enroll') }}</button>
        </form>
      </div>
    </div>
  </div>
  @endforeach
</div>
@endif
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
(function() {
  function initCharts() {
    var weeklyEl = document.getElementById('weeklyChart');
    if (weeklyEl) {
      new Chart(weeklyEl, {
        type: 'bar',
        data: {
          labels: @json($weekLabels),
          datasets: [{
            label: '{{ __('messages.dash_chart_minutes') }}',
            data: @json($weekData),
            backgroundColor: 'rgba(15,109,128,0.15)',
            borderColor: '#0F6D80',
            borderWidth: 2,
            borderRadius: 6,
            borderSkipped: false,
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: {
            y: { beginAtZero: true, ticks: { stepSize: 10, font: { size: 10 } }, grid: { color: 'rgba(0,0,0,0.03)' } },
            x: { grid: { display: false }, ticks: { font: { size: 10 } } }
          }
        }
      });
    }

    var juzCtx = document.getElementById('juzChart');
    if (juzCtx) {
      var done = {{ $completedJuzCount }};
      var remaining = 30 - done - {{ $inProgressJuzCount }};
      var inProgress = {{ $inProgressJuzCount }};
      new Chart(juzCtx, {
        type: 'doughnut',
        data: {
          labels: ['{{ __('messages.dash_chart_completed') }}', '{{ __('messages.dash_chart_in_progress') }}', '{{ __('messages.dash_chart_remaining') }}'],
          datasets: [{
            data: [done, inProgress, remaining],
            backgroundColor: ['#27ae60', '#0F6D80', '#e0e3e4'],
            borderWidth: 0,
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '65%',
          plugins: {
            legend: {
              position: 'bottom',
              labels: { boxWidth: 10, padding: 8, font: { size: 10 }, usePointStyle: true }
            }
          }
        }
      });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() { initCharts(); });
  } else {
    initCharts();
  }
})();
</script>
@endpush

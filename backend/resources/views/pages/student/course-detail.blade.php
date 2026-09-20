@extends('layouts.student')

@section('title', $course->name . ' | ' . __('messages.site_name'))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard/dashboard-course-detail.css') }}" />
@endpush

@section('content')
<div class="mb-3">
  <a href="{{ route('student.dashboard') }}" class="text-decoration-none small fw-semibold" style="color:#0F6D80;"><i class="fa-solid fa-arrow-right ml-1"></i>{{ __('messages.student_back_dashboard') }}</a>
</div>

{{-- ==== Hero ==== --}}
<div class="dash-card cd-hero mb-4 p-4 p-md-5" data-aos="fade-up">
  <div class="d-flex justify-content-end mb-2">
    <span class="cd-hero-enrolled"><i class="fa-solid fa-circle-check"></i>{{ __('messages.student_course_enrolled') }}</span>
  </div>
  <div class="d-flex flex-column flex-md-row align-items-md-center gap-4">
    <div class="cd-hero-icon mx-auto mx-md-0"><i class="fa-solid {{ $course->icon ?: 'fa-book-quran' }}"></i></div>
    <div class="flex-grow-1 text-center text-md-start">
      <h3 class="cd-hero-title mb-1">{{ $course->name }}</h3>
      @if($course->name_en)
      <div class="cd-hero-subtitle mb-3">{{ $course->name_en }}</div>
      @endif
      <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-2">
        @if($course->instructor_name)
        <span class="cd-meta-chip"><i class="fa-regular fa-user"></i>{{ $course->instructor_name }}</span>
        @endif
        @if($course->level)
        <span class="cd-meta-chip"><i class="fa-solid fa-layer-group"></i>{{ __('messages.student_course_level') }}: {{ $course->level }}</span>
        @endif
        @if($course->audience)
        <span class="cd-meta-chip"><i class="fa-solid fa-user-graduate"></i>{{ __('messages.student_course_audience') }}: {{ $course->audience }}</span>
        @endif
        @if($course->duration)
        <span class="cd-meta-chip"><i class="fa-regular fa-clock"></i>{{ $course->duration }}</span>
        @endif
        @if($course->sessions_per_week)
        <span class="cd-meta-chip"><i class="fa-regular fa-calendar"></i>{{ $course->sessions_per_week }} {{ __('messages.dash_session_week') }}</span>
        @endif
      </div>
    </div>
  </div>

  @if($course->desc)
  <div class="cd-hero-desc mt-4">{{ $course->desc }}</div>
  @endif

  <div class="row g-2 g-md-3 mt-1">
    <div class="col-3">
      <div class="cd-stat">
        <i class="fa-solid fa-list"></i>
        <div class="cd-stat-number">{{ $course->lessons->count() }}</div>
        <div class="cd-stat-label">{{ __('messages.student_courses_total_lessons') }}</div>
      </div>
    </div>
    <div class="col-3">
      <div class="cd-stat">
        <i class="fa-regular fa-calendar"></i>
        <div class="cd-stat-number">{{ $course->sessions->count() }}</div>
        <div class="cd-stat-label">{{ __('messages.student_courses_total_sessions') }}</div>
      </div>
    </div>
    <div class="col-3">
      <div class="cd-stat">
        <i class="fa-solid fa-file-pen"></i>
        <div class="cd-stat-number">{{ $course->exams->count() }}</div>
        <div class="cd-stat-label">{{ __('messages.student_courses_available_exams') }}</div>
      </div>
    </div>
    <div class="col-3">
      <div class="cd-stat">
        <i class="fa-solid fa-certificate"></i>
        <div class="cd-stat-number">{{ $course->certificates->count() }}</div>
        <div class="cd-stat-label">{{ __('messages.student_certificate') }}</div>
      </div>
    </div>
  </div>
</div>

{{-- ==== Tabs ==== --}}
<div class="cd-tabs mb-4" data-aos="fade-up">
  <a href="{{ route('student.course-detail', $course) }}" class="cd-tab {{ $activeTab === 'overview' ? 'active' : '' }}"><i class="fa-solid fa-info-circle"></i>{{ __('messages.student_tab_overview') }}</a>
  <a href="{{ route('student.course-detail', $course) }}?tab=lessons" class="cd-tab {{ $activeTab === 'lessons' ? 'active' : '' }}"><i class="fa-solid fa-list"></i>{{ __('messages.student_tab_lessons') }}</a>
  <a href="{{ route('student.course-detail', $course) }}?tab=sessions" class="cd-tab {{ $activeTab === 'sessions' ? 'active' : '' }}"><i class="fa-regular fa-calendar"></i>{{ __('messages.student_tab_sessions') }}</a>
  <a href="{{ route('student.course-detail', $course) }}?tab=exams" class="cd-tab {{ $activeTab === 'exams' ? 'active' : '' }}"><i class="fa-solid fa-file-pen"></i>{{ __('messages.student_tab_exams') }}</a>
  <a href="{{ route('student.course-detail', $course) }}?tab=certificates" class="cd-tab {{ $activeTab === 'certificates' ? 'active' : '' }}"><i class="fa-solid fa-certificate"></i>{{ __('messages.student_tab_certificates') }}</a>
</div>

{{-- ==== Tab content ==== --}}
@if($activeTab === 'overview')
  @include('pages.student.course-tabs.overview')
@elseif($activeTab === 'lessons')
  @include('pages.student.course-tabs.lessons')
@elseif($activeTab === 'sessions')
  @include('pages.student.course-tabs.sessions')
@elseif($activeTab === 'exams')
  @include('pages.student.course-tabs.exams')
@elseif($activeTab === 'certificates')
  @include('pages.student.course-tabs.certificates')
@endif
@endsection
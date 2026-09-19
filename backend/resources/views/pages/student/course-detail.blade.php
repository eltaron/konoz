@extends('layouts.student')

@section('title', $course->name . ' | ' . __('messages.site_name'))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard/dashboard-courses.css') }}" />
<style>
  .detail-stat { background: #fff; border-radius: 12px; padding: 16px; box-shadow: 0 4px 20px rgba(15,109,128,0.04); border: 1px solid rgba(15,109,128,0.04); text-align: center; }
  .detail-stat-number { font-size: 1.4rem; font-weight: 800; color: #0F6D80; margin-bottom: 2px; }
  .detail-stat-label { font-size: 0.72rem; color: #6b7a7e; }
  .lesson-item { display: flex; align-items: center; gap: 12px; padding: 10px 14px; background: #f7fafb; border-radius: 10px; margin-bottom: 6px; }
  .lesson-item:last-child { margin-bottom: 0; }
  .lesson-order { width: 26px; height: 26px; border-radius: 50%; background: rgba(15,109,128,0.06); color: #0F6D80; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700; flex-shrink: 0; }
  .session-item { display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; background: #f7fafb; border-radius: 10px; margin-bottom: 6px; }
  .session-item:last-child { margin-bottom: 0; }
  .course-tabs { display: flex; gap: 4px; overflow-x: auto; padding-bottom: 2px; }
  .course-tab { padding: 10px 20px; border-radius: 10px; font-size: 0.85rem; font-weight: 600; color: #6b7a7e; text-decoration: none; white-space: nowrap; transition: 0.15s; background: transparent; border: none; }
  .course-tab:hover { background: rgba(15,109,128,0.04); color: #0F6D80; }
  .course-tab.active { background: rgba(15,109,128,0.08); color: #0F6D80; }
  .exam-item { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #f7fafb; border-radius: 10px; margin-bottom: 6px; }
  .cert-item-grid { display: flex; align-items: center; gap: 12px; padding: 10px 14px; background: #f7fafb; border-radius: 10px; margin-bottom: 6px; }
</style>
@endpush

@section('content')
<div class="mb-3">
  <a href="{{ route('student.dashboard') }}" class="text-decoration-none small" style="color:#0F6D80;"><i class="fa-solid fa-arrow-right ml-1"></i>{{ __('messages.student_back_dashboard') }}</a>
</div>

{{-- Course Header --}}
<div class="dash-card mb-3" style="background: linear-gradient(135deg, #0a4a56 0%, #0F6D80 50%, #1a8a9c 100%); border: none; color: #fff;" data-aos="fade-up">
  <div class="row g-3 align-items-center">
    <div class="col-md-8">
      <div class="d-flex align-items-center gap-3">
        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 64px; height: 64px; background: rgba(255,255,255,0.1); font-size: 1.5rem;">
          <i class="fa-solid fa-book-quran"></i>
        </div>
        <div>
          <h3 class="fw-bold mb-1">{{ $course->name }}</h3>
          @if($course->instructor_name)
          <span class="opacity-75 small"><i class="fa-regular fa-user me-1"></i>{{ $course->instructor_name }}</span>
          @endif
        </div>
      </div>
      @if($course->desc)
      <p class="opacity-75 small mt-3 mb-0">{{ $course->desc }}</p>
      @endif
    </div>
    <div class="col-md-4">
      <div class="row g-2">
        <div class="col-4">
          <div class="detail-stat" style="background:rgba(255,255,255,0.08);">
            <div class="detail-stat-number text-white">{{ $course->lessons->count() }}</div>
            <div class="detail-stat-label text-white-50">{{ __('messages.student_lesson') }}</div>
          </div>
        </div>
        <div class="col-4">
          <div class="detail-stat" style="background:rgba(255,255,255,0.08);">
            <div class="detail-stat-number text-white">{{ $course->sessions->count() }}</div>
            <div class="detail-stat-label text-white-50">{{ __('messages.student_session') }}</div>
          </div>
        </div>
        <div class="col-4">
          <div class="detail-stat" style="background:rgba(255,255,255,0.08);">
            <div class="detail-stat-number text-white">{{ $course->exams->count() }}</div>
            <div class="detail-stat-label text-white-50">{{ __('messages.student_exam') }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Tabs --}}
<div class="course-tabs mb-4" data-aos="fade-up">
  <a href="{{ route('student.course-detail', $course) }}" class="course-tab {{ $activeTab === 'overview' ? 'active' : '' }}"><i class="fa-solid fa-info-circle me-1"></i> {{ __('messages.student_tab_overview') }}</a>
  <a href="{{ route('student.course-detail', $course) }}?tab=lessons" class="course-tab {{ $activeTab === 'lessons' ? 'active' : '' }}"><i class="fa-solid fa-list me-1"></i> {{ __('messages.student_tab_lessons') }}</a>
  <a href="{{ route('student.course-detail', $course) }}?tab=sessions" class="course-tab {{ $activeTab === 'sessions' ? 'active' : '' }}"><i class="fa-regular fa-calendar me-1"></i> {{ __('messages.student_tab_sessions') }}</a>
  <a href="{{ route('student.course-detail', $course) }}?tab=exams" class="course-tab {{ $activeTab === 'exams' ? 'active' : '' }}"><i class="fa-solid fa-file-pen me-1"></i> {{ __('messages.student_tab_exams') }}</a>
  <a href="{{ route('student.course-detail', $course) }}?tab=certificates" class="course-tab {{ $activeTab === 'certificates' ? 'active' : '' }}"><i class="fa-solid fa-certificate me-1"></i> {{ __('messages.student_tab_certificates') }}</a>
</div>

{{-- Tab Content --}}
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

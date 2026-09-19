@extends('layouts.student')

@section('title', $lesson->name . ' | ' . $course->name . ' | ' . __('messages.site_name'))

@section('content')
<div class="mb-3">
  <a href="{{ route('student.course-detail', $course) }}?tab=lessons" class="text-decoration-none small" style="color:#0F6D80;">
    <i class="fa-solid fa-arrow-right ml-1"></i>{{ __('messages.student_back_lessons') }}
  </a>
</div>

<div class="dash-card" data-aos="fade-up">
  <div class="d-flex align-items-center gap-3 mb-3">
    <span class="d-flex align-items-center justify-content-center rounded-2" style="width: 48px; height: 48px; background: rgba(15,109,128,0.06); color: #0F6D80; font-size: 1.2rem;">
      <i class="fa-solid fa-book-quran"></i>
    </span>
    <div>
      <h4 class="fw-bold mb-1" style="color: #0F6D80;">{{ $lesson->name }}</h4>
      <small class="text-secondary opacity-75">{{ $course->name }} — {{ __('messages.student_tab_lessons') }} {{ $lesson->order ?? $lesson->id }}</small>
    </div>
  </div>
  <hr style="border-color: rgba(15,109,128,0.06);">
  @if(!empty($lesson->link))
  <div class="text-center py-4">
    <a href="{{ $lesson->link }}" target="_blank" rel="noopener" class="btn btn-primary btn-lg px-5 rounded-3 fw-bold" style="background:#0F6D80;border-color:#0F6D80;">
      <i class="fa-solid fa-play ml-2"></i>{{ __('messages.student_open_lesson') }}
    </a>
    <p class="small text-secondary opacity-60 mt-3 mb-0"><i class="fa-solid fa-arrow-up-right-from-square ml-1"></i>{{ $lesson->link }}</p>
  </div>
  @else
  <div class="text-center py-5 text-secondary opacity-50">
    <i class="fa-solid fa-file-lines" style="font-size: 3rem; display: block; margin-bottom: 12px;"></i>
    <p>{{ __('messages.student_lesson_content_pending') }}</p>
  </div>
  @endif
</div>
@endsection

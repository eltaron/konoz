@extends('layouts.student')

@section('title', __('messages.student_mycourses_title'))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard/dashboard-courses.css') }}" />
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h4 class="fw-bold mb-1" style="color: #0F6D80;">{{ __('messages.student_mycourses_title') }}</h4>
    <p class="text-secondary opacity-75 mb-0 small">{{ __('messages.student_mycourses_subtitle') }}</p>
  </div>
</div>

@forelse($myCourses as $course)
<div class="dash-card mb-3">
  <div class="d-flex align-items-start gap-3">
    <div class="rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background: rgba(15,109,128,0.06); color: #0F6D80; font-size: 1.3rem;">
      <i class="fa-solid fa-book-quran"></i>
    </div>
    <div class="flex-grow-1">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
          <h6 class="fw-bold mb-1">{{ $course->name }} @if($course->name_en)<small class="text-secondary opacity-50">({{ $course->name_en }})</small>@endif</h6>
          <p class="small text-secondary opacity-75 mb-1">{{ $course->desc ? \Illuminate\Support\Str::limit($course->desc, 120) : '' }}</p>
          <div class="d-flex align-items-center gap-3 small text-secondary opacity-50">
            @if($course->level)<span><i class="fa-solid fa-layer-group"></i> {{ $course->level }}</span>@endif
            @if($course->duration)<span><i class="fa-regular fa-clock"></i> {{ $course->duration }}</span>@endif
          </div>
        </div>
        <span class="dash-badge dash-badge-success">{{ __('messages.student_mycourses_enrolled') }}</span>
      </div>
    </div>
  </div>
</div>
@empty
<div class="dash-card text-center py-5">
  <i class="fa-solid fa-graduation-cap" style="font-size: 2.5rem; color: #d0d9db; margin-bottom: 12px;"></i>
  <p class="text-secondary opacity-75 mb-2">{{ __('messages.student_mycourses_empty') }}</p>
  <a href="{{ route('student.courses') }}" class="dash-btn dash-btn-primary">{{ __('messages.student_mycourses_browse') }}</a>
</div>
@endforelse
@endsection

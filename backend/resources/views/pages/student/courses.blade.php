@extends('layouts.student')

@section('title', __('messages.student_courses_title'))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard/dashboard-courses.css') }}" />
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h4 class="fw-bold mb-1" style="color: #0F6D80;">{{ __('messages.student_courses_title') }}</h4>
    <p class="text-secondary opacity-75 mb-0 small">{{ __('messages.student_courses_subtitle') }}</p>
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
@if(session('info'))
<div class="alert alert-info d-flex align-items-center gap-2 rounded-3 py-3 px-4 mb-4" style="border: none; background: rgba(15,109,128,0.06); color: #0F6D80;">
  <i class="fa-solid fa-circle-info"></i> {{ session('info') }}
</div>
@endif

@forelse($availableCourses as $course)
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
        @php
          $isEnrolled = $student->courses->contains($course->id);
          $pendingReq = \App\Models\EnrollmentRequest::where('student_id', $student->id)->where('course_id', $course->id)->first();
        @endphp
        @if($isEnrolled)
          <span class="dash-badge dash-badge-success">{{ __('messages.student_courses_enrolled') }}</span>
        @elseif($pendingReq && $pendingReq->status === 'pending')
          <span class="dash-badge dash-badge-warning">{{ __('messages.student_courses_pending') }}</span>
        @elseif($pendingReq && $pendingReq->status === 'rejected')
          <form method="POST" action="{{ route('student.courses.enroll', $course) }}">
            @csrf
            <button class="dash-btn dash-btn-outline" type="submit">{{ __('messages.student_courses_reapply') }}</button>
          </form>
        @else
          <form method="POST" action="{{ route('student.courses.enroll', $course) }}">
            @csrf
            <button class="dash-btn dash-btn-primary" type="submit">{{ __('messages.student_courses_enroll_btn') }}</button>
          </form>
        @endif
      </div>
    </div>
  </div>
</div>
@empty
<div class="dash-card text-center py-5">
  <i class="fa-solid fa-book-open" style="font-size: 2.5rem; color: #d0d9db; margin-bottom: 12px;"></i>
  <p class="text-secondary opacity-75">{{ __('messages.student_courses_empty') }}</p>
</div>
@endforelse
@endsection

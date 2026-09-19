@extends('layouts.student')

@section('title', $certificate->title . ' | ' . __('messages.site_name'))

@section('content')
<div class="mb-3">
  <a href="{{ route('student.certificates') }}" class="text-decoration-none small" style="color:#0F6D80;">
    <i class="fa-solid fa-arrow-right ml-1"></i>{{ __('messages.student_back_certificates') }}
  </a>
</div>

<div class="dash-card text-center py-5" data-aos="fade-up" style="max-width: 500px; margin: 0 auto;">
  <i class="fa-solid fa-certificate" style="font-size: 4rem; color: #0F6D80; margin-bottom: 16px;"></i>
  <h4 class="fw-bold mb-1" style="color: #0F6D80;">{{ $certificate->title }}</h4>
  @if($certificate->course)
  <p class="text-secondary opacity-75 mb-1">{{ $certificate->course->name }}</p>
  @endif
  <p class="small text-secondary opacity-50 mb-3">
    <i class="fa-regular fa-calendar ml-1"></i>
    {{ $certificate->issued_at ? \Carbon\Carbon::parse($certificate->issued_at)->format('Y/m/d') : '—' }}
  </p>
  <span class="dash-badge {{ $certificate->status === 'delivered' ? 'dash-badge-success' : 'dash-badge-warning' }}" style="font-size:0.8rem;">
    {{ $certificate->status === 'delivered' ? __('messages.student_cert_status_received') : __('messages.student_cert_status_preparing') }}
  </span>
</div>
@endsection

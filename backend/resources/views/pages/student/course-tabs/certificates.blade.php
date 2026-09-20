<div class="cd-card" data-aos="fade-up">
  <div class="cd-card-header">
    <h5 class="cd-card-title"><span class="cd-card-title-icon"><i class="fa-solid fa-certificate"></i></span>{{ __('messages.student_tab_certificates') }}</h5>
    <span class="cd-count-badge">{{ $course->certificates->count() }} {{ __('messages.student_certificate') }}</span>
  </div>
  <div class="cd-list">
    @forelse($course->certificates as $cert)
    <a href="{{ route('student.certificate.show', $cert) }}">
      <div class="cd-item">
        <span class="cd-item-icon">
          @if($cert->status === 'delivered')
          <i class="fa-solid fa-circle-check" style="color: #27ae60;"></i>
          @else
          <i class="fa-solid fa-clock"></i>
          @endif
        </span>
        <div class="flex-grow-1">
          <div class="cd-item-title">{{ $cert->title ?? __('messages.student_certificate') }}</div>
          <div class="cd-item-sub">{{ $cert->issued_at ? \Carbon\Carbon::parse($cert->issued_at)->format('Y/m/d') : '—' }}</div>
        </div>
        <span class="dash-badge {{ $cert->status === 'delivered' ? 'dash-badge-success' : 'dash-badge-warning' }}" style="font-size:0.65rem;">
          {{ $cert->status === 'delivered' ? __('messages.student_certificates_status_issued') : __('messages.student_certificates_status_pending') }}
        </span>
      </div>
    </a>
    @empty
    <div class="cd-empty">
      <i class="fa-solid fa-certificate"></i>
      <p>{{ __('messages.student_cert_course_empty') }}</p>
    </div>
    @endforelse
  </div>
</div>
<div class="dash-card" data-aos="fade-up">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h5 class="fw-bold mb-0" style="color: #0F6D80;"><i class="fa-solid fa-certificate me-2"></i>{{ __('messages.student_tab_certificates') }}</h5>
    <span class="dash-badge dash-badge-info">{{ $course->certificates->count() }} {{ __('messages.student_certificate') }}</span>
  </div>
  @forelse($course->certificates as $cert)
  <a href="{{ route('student.certificate.show', $cert) }}" class="text-decoration-none">
  <div class="cert-item-grid">
    <span class="d-flex align-items-center justify-content-center rounded-2" style="width: 40px; height: 40px; background: rgba(15,109,128,0.06); color: #0F6D80;">
      @if($cert->status === 'delivered')
      <i class="fa-solid fa-check-circle" style="color: #27ae60;"></i>
      @else
      <i class="fa-solid fa-clock"></i>
      @endif
    </span>
    <div class="flex-grow-1">
      <h6 class="fw-bold small mb-0" style="color:#1f2937;">{{ $cert->title ?? __('messages.student_certificate') }}</h6>
      <small class="text-secondary opacity-75">{{ $cert->issued_at ? \Carbon\Carbon::parse($cert->issued_at)->format('Y/m/d') : '—' }}</small>
    </div>
    <span class="dash-badge {{ $cert->status === 'delivered' ? 'dash-badge-success' : 'dash-badge-warning' }}" style="font-size:0.65rem;">
      {{ $cert->status === 'delivered' ? __('messages.student_certificates_status_issued') : __('messages.student_certificates_status_pending') }}
    </span>
  </div>
  </a>
  @empty
  <p class="text-center text-secondary opacity-75 small py-4">{{ __('messages.student_cert_course_empty') }}</p>
  @endforelse
</div>

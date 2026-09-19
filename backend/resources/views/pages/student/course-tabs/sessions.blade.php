<div class="row g-4">
  <div class="col-lg-6">
    <div class="dash-card h-100" data-aos="fade-up">
      <h5 class="fw-bold mb-3" style="color: #0F6D80;"><i class="fa-regular fa-clock me-2"></i>{{ __('messages.student_schedule_upcoming_heading') }}</h5>
      @forelse($upcomingSessions as $session)
      <a href="{{ route('student.live') }}" class="text-decoration-none">
      <div class="session-item">
        <div>
          <span class="fw-medium small" style="color:#1f2937;">{{ $session->title ?? __('messages.student_schedule_session') }}</span>
          <div class="small text-secondary opacity-75">
            {{ \Carbon\Carbon::parse($session->date)->format('Y/m/d') }}
            @if($session->time_from) - {{ \Carbon\Carbon::parse($session->time_from)->format('g:i A') }} @endif
          </div>
        </div>
        <span class="dash-badge dash-badge-success" style="font-size:0.65rem;">{{ __('messages.student_schedule_upcoming_badge') }}</span>
      </div>
      </a>
      @empty
      <p class="text-center text-secondary opacity-75 small py-4">{{ __('messages.student_schedule_no_upcoming') }}</p>
      @endforelse
    </div>
  </div>
  <div class="col-lg-6">
    <div class="dash-card h-100" data-aos="fade-up">
      <h5 class="fw-bold mb-3" style="color: #6b7a7e;"><i class="fa-regular fa-circle-check me-2"></i>{{ __('messages.student_schedule_past_heading') }}</h5>
      @forelse($pastSessions as $session)
      <a href="{{ route('student.live') }}" class="text-decoration-none">
      <div class="session-item">
        <div>
          <span class="fw-medium small" style="color:#1f2937;">{{ $session->title ?? __('messages.student_schedule_session') }}</span>
          <div class="small text-secondary opacity-75">{{ \Carbon\Carbon::parse($session->date)->format('Y/m/d') }}</div>
        </div>
        <span class="dash-badge dash-badge-info" style="font-size:0.65rem;">{{ __('messages.student_schedule_past_heading') }}</span>
      </div>
      </a>
      @empty
      <p class="text-center text-secondary opacity-75 small py-4">{{ __('messages.student_schedule_no_past') }}</p>
      @endforelse
    </div>
  </div>
</div>

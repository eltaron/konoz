<div class="row g-4">
  <div class="col-lg-6">
    <div class="cd-card h-100" data-aos="fade-up">
      <div class="cd-card-header">
        <h5 class="cd-card-title"><span class="cd-card-title-icon"><i class="fa-regular fa-calendar"></i></span>{{ __('messages.student_schedule_upcoming_heading') }}</h5>
        <span class="cd-count-badge">{{ $upcomingSessions->count() }}</span>
      </div>
      <div class="cd-list">
        @forelse($upcomingSessions as $session)
        <a href="{{ route('student.live') }}">
          <div class="cd-item">
            <div class="cd-date-chip">
              <div class="cd-day">{{ \Carbon\Carbon::parse($session->date)->format('d') }}</div>
              <div class="cd-mon">{{ \Carbon\Carbon::parse($session->date)->locale(session('locale', 'ar'))->isoFormat('MMM') }}</div>
            </div>
            <div class="flex-grow-1">
              <div class="cd-item-title">{{ $session->title ?? __('messages.student_schedule_session') }}</div>
              <div class="cd-item-sub">
                {{ \Carbon\Carbon::parse($session->date)->format('Y/m/d') }}
                @if($session->time_from) <i class="fa-regular fa-clock me-1 ms-1"></i>{{ \Carbon\Carbon::parse($session->time_from)->format('g:i A') }} @endif
              </div>
            </div>
            <span class="dash-badge dash-badge-success" style="font-size:0.65rem;"><i class="fa-solid fa-video me-1"></i>{{ __('messages.student_course_join_session') }}</span>
          </div>
        </a>
        @empty
        <div class="cd-empty">
          <i class="fa-regular fa-calendar-xmark"></i>
          <p>{{ __('messages.student_schedule_no_upcoming') }}</p>
        </div>
        @endforelse
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="cd-card h-100" data-aos="fade-up">
      <div class="cd-card-header">
        <h5 class="cd-card-title"><span class="cd-card-title-icon"><i class="fa-regular fa-circle-check"></i></span>{{ __('messages.student_schedule_past_heading') }}</h5>
        <span class="cd-count-badge">{{ $pastSessions->count() }}</span>
      </div>
      <div class="cd-list">
        @forelse($pastSessions as $session)
        <a href="{{ route('student.live') }}">
          <div class="cd-item">
            <div class="cd-date-chip">
              <div class="cd-day">{{ \Carbon\Carbon::parse($session->date)->format('d') }}</div>
              <div class="cd-mon">{{ \Carbon\Carbon::parse($session->date)->locale(session('locale', 'ar'))->isoFormat('MMM') }}</div>
            </div>
            <div class="flex-grow-1">
              <div class="cd-item-title">{{ $session->title ?? __('messages.student_schedule_session') }}</div>
              <div class="cd-item-sub">{{ \Carbon\Carbon::parse($session->date)->format('Y/m/d') }}</div>
            </div>
            <span class="dash-badge dash-badge-info" style="font-size:0.65rem;">{{ __('messages.student_schedule_past_heading') }}</span>
          </div>
        </a>
        @empty
        <div class="cd-empty">
          <i class="fa-regular fa-calendar-check"></i>
          <p>{{ __('messages.student_schedule_no_past') }}</p>
        </div>
        @endforelse
      </div>
    </div>
  </div>
</div>
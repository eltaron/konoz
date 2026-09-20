<div class="row g-4">
  {{-- Curriculum --}}
  <div class="col-lg-6">
    <div class="cd-card h-100" data-aos="fade-up">
      <div class="cd-card-header">
        <h5 class="cd-card-title"><span class="cd-card-title-icon"><i class="fa-solid fa-list"></i></span>{{ __('messages.student_course_program') }}</h5>
        <span class="cd-count-badge">{{ $course->lessons->count() }} {{ __('messages.student_lesson') }}</span>
      </div>
      <div class="cd-list">
        @forelse($course->lessons as $lesson)
        <a href="{{ route('student.lesson.show', $lesson) }}">
          <div class="cd-item">
            <span class="cd-order">{{ $lesson->order ?? $loop->iteration }}</span>
            <span class="flex-grow-1 cd-item-title">{{ $lesson->name }}</span>
            <i class="fa-solid fa-chevron-down cd-item-arrow"></i>
          </div>
        </a>
        @empty
        <div class="cd-empty">
          <i class="fa-solid fa-book-open"></i>
          <p>{{ __('messages.student_cert_course_empty') }}</p>
        </div>
        @endforelse
      </div>
    </div>
  </div>

  {{-- Upcoming Sessions --}}
  <div class="col-lg-6">
    <div class="cd-card h-100" data-aos="fade-up">
      <div class="cd-card-header">
        <h5 class="cd-card-title"><span class="cd-card-title-icon"><i class="fa-regular fa-calendar"></i></span>{{ __('messages.student_schedule_upcoming_heading') }}</h5>
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
                <i class="fa-regular fa-clock me-1"></i>{{ \Carbon\Carbon::parse($session->time_from ?? '00:00')->format('g:i A') }}
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

      @if($pastSessions->count() > 0)
      <hr class="cd-hr" />
      <div class="cd-sub-heading"><i class="fa-regular fa-circle-check"></i>{{ __('messages.student_schedule_past_heading') }}</div>
      <div class="cd-list">
        @foreach($pastSessions as $session)
        <a href="{{ route('student.live') }}">
          <div class="cd-item" style="opacity:0.65;">
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
        @endforeach
      </div>
      @endif
    </div>
  </div>
</div>

{{-- Exams Overview --}}
<div class="cd-card mt-4" data-aos="fade-up">
  <div class="cd-card-header">
    <h5 class="cd-card-title"><span class="cd-card-title-icon"><i class="fa-solid fa-file-pen"></i></span>{{ __('messages.student_tab_exams') }}</h5>
    <span class="cd-count-badge">{{ $course->exams->count() }}</span>
  </div>
  <div class="cd-list">
    @forelse($course->exams as $exam)
    @php $myResult = $examResults[$exam->id] ?? null; @endphp
    <a href="{{ route('student.exam.show', $exam) }}">
      <div class="cd-item">
        <span class="cd-item-icon"><i class="fa-solid fa-pen-to-square"></i></span>
        <div class="flex-grow-1">
          <div class="cd-item-title">{{ $exam->title }}</div>
          <div class="cd-item-sub">{{ $exam->date ? \Carbon\Carbon::parse($exam->date)->format('Y/m/d') : '' }}</div>
        </div>
        @if($myResult)
        <span class="cd-result {{ $myResult->score >= 75 ? 'good' : ($myResult->score >= 50 ? 'mid' : 'low') }}">{{ $myResult->score }}%</span>
        @else
        <span class="dash-badge dash-badge-warning" style="font-size:0.65rem;">{{ __('messages.student_exams_not_taken') }}</span>
        @endif
      </div>
    </a>
    @empty
    <div class="cd-empty">
      <i class="fa-solid fa-file-circle-exclamation"></i>
      <p>{{ __('messages.student_exams_no_past') }}</p>
    </div>
    @endforelse
  </div>
</div>
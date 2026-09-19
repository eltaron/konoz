<div class="row g-4">
  {{-- Lessons --}}
  <div class="col-lg-6">
    <div class="dash-card h-100" data-aos="fade-up">
      <h5 class="fw-bold mb-3" style="color: #0F6D80;"><i class="fa-solid fa-list me-2"></i>{{ __('messages.student_tab_lessons') }}</h5>
      @forelse($course->lessons as $lesson)
      <a href="{{ route('student.lesson.show', $lesson) }}" class="text-decoration-none">
      <div class="lesson-item">
        <span class="lesson-order">{{ $lesson->order ?? $loop->iteration }}</span>
        <span class="fw-medium small" style="color:#1f2937;">{{ $lesson->name }}</span>
      </div>
      </a>
      @empty
      <p class="text-center text-secondary opacity-75 small py-3">{{ __('messages.student_cert_course_empty') }}</p>
      @endforelse
    </div>
  </div>

  {{-- Upcoming Sessions --}}
  <div class="col-lg-6">
    <div class="dash-card h-100" data-aos="fade-up">
      <h5 class="fw-bold mb-3" style="color: #0F6D80;"><i class="fa-regular fa-calendar me-2"></i>{{ __('messages.student_schedule_upcoming_heading') }}</h5>
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
      <p class="text-center text-secondary opacity-75 small py-3">{{ __('messages.student_schedule_no_upcoming') }}</p>
      @endforelse

      @if($pastSessions->count() > 0)
      <hr class="my-3" style="border-color: rgba(15,109,128,0.04);" />
      <h6 class="fw-bold mb-2 small" style="color: #6b7a7e;">{{ __('messages.student_schedule_past_heading') }}</h6>
      @foreach($pastSessions as $session)
      <a href="{{ route('student.live') }}" class="text-decoration-none">
      <div class="session-item" style="opacity:0.7;">
        <div>
          <span class="fw-medium small" style="color:#1f2937;">{{ $session->title ?? __('messages.student_schedule_session') }}</span>
          <div class="small text-secondary opacity-75">{{ \Carbon\Carbon::parse($session->date)->format('Y/m/d') }}</div>
        </div>
        <span class="dash-badge dash-badge-info" style="font-size:0.65rem;">{{ __('messages.student_schedule_past_heading') }}</span>
      </div>
      </a>
      @endforeach
      @endif
    </div>
  </div>
</div>

{{-- Exams Overview --}}
<div class="dash-card mt-4" data-aos="fade-up">
  <h5 class="fw-bold mb-3" style="color: #0F6D80;"><i class="fa-solid fa-file-pen me-2"></i>{{ __('messages.student_tab_exams') }}</h5>
  @forelse($course->exams as $exam)
  @php $myResult = $examResults[$exam->id] ?? null; @endphp
  <a href="{{ route('student.exam.show', $exam) }}" class="text-decoration-none">
  <div class="exam-item mb-2">
    <div class="d-flex align-items-center gap-3">
      <span class="d-flex align-items-center justify-content-center rounded-2" style="width: 40px; height: 40px; background: rgba(15,109,128,0.06); color: #0F6D80;"><i class="fa-solid fa-pen-to-square"></i></span>
      <div>
        <h6 class="fw-bold small mb-0" style="color:#1f2937;">{{ $exam->title }}</h6>
        <small class="text-secondary opacity-75">{{ $exam->date ? \Carbon\Carbon::parse($exam->date)->format('Y/m/d') : '' }}</small>
      </div>
    </div>
    @if($myResult)
    <span class="fw-bold small {{ $myResult->score >= 75 ? 'text-success' : ($myResult->score >= 50 ? 'text-warning' : 'text-danger') }}">{{ $myResult->score }}%</span>
    @else
    <span class="dash-badge dash-badge-warning">{{ __('messages.student_exams_not_taken') }}</span>
    @endif
  </div>
  </a>
  @empty
  <p class="text-center text-secondary opacity-75 small py-3">{{ __('messages.student_exams_no_past') }}</p>
  @endforelse
</div>

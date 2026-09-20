<div class="cd-card" data-aos="fade-up">
  <div class="cd-card-header">
    <h5 class="cd-card-title"><span class="cd-card-title-icon"><i class="fa-solid fa-file-pen"></i></span>{{ __('messages.student_tab_exams') }}</h5>
    @php $availableExams = $course->exams->filter(fn($e) => !$e->date || \Carbon\Carbon::parse($e->date)->gte(today())); @endphp
    <span class="cd-count-badge">{{ $availableExams->count() }} {{ __('messages.student_courses_available_exams') }}</span>
  </div>
  <div class="cd-list">
    @forelse($availableExams as $exam)
    @php $myResult = $examResults[$exam->id] ?? null; @endphp
    <a href="{{ route('student.exam.show', $exam) }}">
      <div class="cd-item">
        <span class="cd-item-icon"><i class="fa-solid fa-pen-to-square"></i></span>
        <div class="flex-grow-1">
          <div class="cd-item-title">{{ $exam->title }}</div>
          <div class="cd-item-sub">{{ $exam->date ? \Carbon\Carbon::parse($exam->date)->format('Y/m/d') : '—' }}</div>
        </div>
        <div class="text-center">
          @if($myResult)
          <span class="cd-result {{ $myResult->score >= 75 ? 'good' : ($myResult->score >= 50 ? 'mid' : 'low') }}">{{ $myResult->score }}%</span>
          <small class="d-block cd-item-sub" style="font-size:0.6rem;">{{ __('messages.student_exams_result') }}</small>
          @else
          <span class="dash-badge dash-badge-warning" style="font-size:0.68rem;">{{ __('messages.student_exams_not_taken') }}</span>
          @endif
        </div>
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
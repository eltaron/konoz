<div class="dash-card" data-aos="fade-up">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h5 class="fw-bold mb-0" style="color: #0F6D80;"><i class="fa-solid fa-file-pen me-2"></i>{{ __('messages.student_tab_exams') }}</h5>
    @php $availableExams = $course->exams->filter(fn($e) => !$e->date || \Carbon\Carbon::parse($e->date)->gte(today())); @endphp
    <span class="dash-badge dash-badge-info">{{ $availableExams->count() }} {{ __('messages.student_exam') }}</span>
  </div>
  @forelse($availableExams as $exam)
  @php $myResult = $examResults[$exam->id] ?? null; @endphp
  <a href="{{ route('student.exam.show', $exam) }}" class="text-decoration-none">
  <div class="exam-item">
    <div class="d-flex align-items-center gap-3">
      <span class="d-flex align-items-center justify-content-center rounded-2" style="width: 40px; height: 40px; background: rgba(15,109,128,0.06); color: #0F6D80;"><i class="fa-solid fa-pen-to-square"></i></span>
      <div>
        <h6 class="fw-bold small mb-0" style="color:#1f2937;">{{ $exam->title }}</h6>
        <small class="text-secondary opacity-75">{{ $exam->date ? \Carbon\Carbon::parse($exam->date)->format('Y/m/d') : '—' }}</small>
      </div>
    </div>
    <div class="text-center">
      @if($myResult)
      <span class="fw-bold {{ $myResult->score >= 75 ? 'text-success' : ($myResult->score >= 50 ? 'text-warning' : 'text-danger') }}">{{ $myResult->score }}%</span>
      <small class="d-block text-secondary opacity-50" style="font-size:0.6rem;">{{ __('messages.student_exams_result') }}</small>
      @else
      <span class="dash-badge dash-badge-warning" style="font-size:0.7rem;">{{ __('messages.student_exams_not_taken') }}</span>
      @endif
    </div>
  </div>
  </a>
  @empty
  <p class="text-center text-secondary opacity-75 small py-4">{{ __('messages.student_exams_no_past') }}</p>
  @endforelse
</div>

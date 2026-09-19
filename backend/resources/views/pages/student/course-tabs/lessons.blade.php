<div class="dash-card" data-aos="fade-up">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h5 class="fw-bold mb-0" style="color: #0F6D80;"><i class="fa-solid fa-list me-2"></i>{{ __('messages.student_tab_lessons') }}</h5>
    <span class="dash-badge dash-badge-info">{{ $course->lessons->count() }} {{ __('messages.student_lesson') }}</span>
  </div>
  @forelse($course->lessons as $lesson)
  <a href="{{ $lesson->link ?: route('student.lesson.show', $lesson) }}" target="{{ $lesson->link ? '_blank' : '_self' }}" rel="noopener" class="text-decoration-none">
  <div class="lesson-item">
    <span class="lesson-order">{{ $lesson->order ?? $loop->iteration }}</span>
    <span class="fw-medium small" style="color:#1f2937;">{{ $lesson->name }}</span>
    @if($lesson->link)
    <span class="mr-auto"><i class="fa-solid fa-arrow-up-right-from-square" style="color:#0F6D80;"></i></span>
    @endif
  </div>
  </a>
  @empty
  <p class="text-center text-secondary opacity-75 small py-4">{{ __('messages.student_cert_course_empty') }}</p>
  @endforelse
</div>

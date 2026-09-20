<div class="cd-card" data-aos="fade-up">
  <div class="cd-card-header">
    <h5 class="cd-card-title"><span class="cd-card-title-icon"><i class="fa-solid fa-list"></i></span>{{ __('messages.student_tab_lessons') }}</h5>
    <span class="cd-count-badge">{{ $course->lessons->count() }} {{ __('messages.student_lesson') }}</span>
  </div>
  <div class="cd-list">
    @forelse($course->lessons->sortBy('order ?? id') as $lesson)
    <a href="{{ route('student.lesson.show', $lesson) }}" rel="noopener">
      <div class="cd-item">
        <span class="cd-order">{{ $lesson->order ?? $loop->iteration }}</span>
        <span class="flex-grow-1 cd-item-title">{{ $lesson->name }}</span>
        @if($lesson->youtube_embed)
        <i class="fa-solid fa-circle-play cd-item-arrow"></i>
        @elseif($lesson->link)
        <i class="fa-solid fa-arrow-up-right-from-square cd-item-arrow"></i>
        @else
        <i class="fa-solid fa-chevron-down cd-item-arrow"></i>
        @endif
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
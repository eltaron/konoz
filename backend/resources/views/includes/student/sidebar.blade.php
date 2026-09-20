<aside class="dashboard-sidebar">
  <div class="sidebar-hero">
    <div class="sidebar-avatar-wrap">
      <img src="{{ $student->avatar_url ?? asset('images/logo.png') }}" alt="{{ $student->name ?? __('messages.student_student_female') }}" class="sidebar-avatar" />
      <span class="sidebar-avatar-badge" title="{{ __('messages.student_badge_distinguished') }}"><i class="fa-solid fa-award"></i></span>
    </div>
    <h6 class="sidebar-hero-name">{{ $student->name ?? __('messages.student_student_female') }}</h6>
    <div>
      <span class="sidebar-hero-role"><i class="fa-solid fa-layer-group"></i>@if($student->level){{ __('messages.student_level') }} {{ $student->level }}@else{{ __('messages.student_hafidh') }}@endif</span>
    </div>
  </div>
  <nav class="p-3 d-flex flex-column gap-1 flex-grow-1 sidebar-scroll">
    <a href="{{ route('student.dashboard') }}" class="sidebar-link {{ Request::routeIs('student.dashboard') ? 'active' : '' }}"><span class="sidebar-link-icon"><i class="fa-solid fa-school"></i></span><span>{{ __('messages.student_dashboard') }}</span></a>
    <a href="{{ route('student.courses') }}" class="sidebar-link {{ Request::routeIs('student.courses') ? 'active' : '' }}"><span class="sidebar-link-icon"><i class="fa-solid fa-book-open"></i></span><span>{{ __('messages.student_available_courses') }}</span></a>
    <a href="{{ route('student.hifdh-journey') }}" class="sidebar-link {{ Request::routeIs('student.hifdh-journey') ? 'active' : '' }}"><span class="sidebar-link-icon"><i class="fa-solid fa-chart-line"></i></span><span>{{ __('messages.student_hifdh_journey') }}</span></a>

    @if(isset($student) && $student->courses->count() > 0)
    <div class="sidebar-section-label mt-3 mb-1 px-2">
      <small class="fw-bold">{{ __('messages.student_my_courses') }}</small>
    </div>
    @foreach($student->courses as $course)
    @php
      $isCourseRoute = Request::routeIs('student.course-detail') && request()->route('course')?->id === $course->id;
    @endphp
    <div class="sidebar-course-item">
      <a href="{{ route('student.course-detail', $course) }}"
         class="sidebar-link d-flex align-items-center justify-content-between {{ $isCourseRoute ? 'active' : '' }}"
         data-bs-toggle="collapse"
         data-bs-target="#courseMenu{{ $course->id }}"
         aria-expanded="{{ $isCourseRoute ? 'true' : 'false' }}">
        <span class="d-flex align-items-center gap-2"><span class="sidebar-link-icon"><i class="fa-solid fa-book-quran"></i></span><span>{{ Str::limit($course->name, 18) }}</span></span>
        <i class="fa-solid fa-chevron-down sidebar-chevron {{ $isCourseRoute ? 'rotated' : '' }}" style="font-size: 0.6rem; transition: transform 0.2s;"></i>
      </a>
      <div class="collapse {{ $isCourseRoute ? 'show' : '' }}" id="courseMenu{{ $course->id }}">
        <div class="sidebar-subnav py-1">
          <a href="{{ route('student.course-detail', $course) }}?tab=lessons" class="sidebar-sub-link {{ $isCourseRoute && request('tab') === 'lessons' ? 'active' : '' }}">{{ __('messages.student_lessons') }}</a>
          <a href="{{ route('student.course-detail', $course) }}?tab=sessions" class="sidebar-sub-link {{ $isCourseRoute && request('tab') === 'sessions' ? 'active' : '' }}">{{ __('messages.student_sessions') }}</a>
          <a href="{{ route('student.course-detail', $course) }}?tab=exams" class="sidebar-sub-link {{ $isCourseRoute && request('tab') === 'exams' ? 'active' : '' }}">{{ __('messages.student_exams') }}</a>
          <a href="{{ route('student.course-detail', $course) }}?tab=certificates" class="sidebar-sub-link {{ $isCourseRoute && request('tab') === 'certificates' ? 'active' : '' }}">{{ __('messages.student_certificates') }}</a>
        </div>
      </div>
    </div>
    @endforeach
    @endif

    <div class="sidebar-section-label mt-3 mb-1 px-2">
      <small class="fw-bold">{{ __('messages.student_general') }}</small>
    </div>
    <a href="{{ route('student.messages') }}" class="sidebar-link {{ Request::routeIs('student.messages') ? 'active' : '' }}"><span class="sidebar-link-icon"><i class="fa-solid fa-envelope"></i></span><span>{{ __('messages.student_messages') }}</span>@if(($unreadMessagesCount ?? 0) > 0)<span class="sidebar-badge">{{ $unreadMessagesCount }}</span>@endif</a>
    <a href="{{ route('student.certificates') }}" class="sidebar-link {{ Request::routeIs('student.certificates') && !request('course_id') ? 'active' : '' }}"><span class="sidebar-link-icon"><i class="fa-solid fa-certificate"></i></span><span>{{ __('messages.student_certificates') }}</span></a>
    <a href="{{ route('student.settings') }}" class="sidebar-link {{ Request::routeIs('student.settings') ? 'active' : '' }}"><span class="sidebar-link-icon"><i class="fa-solid fa-sliders"></i></span><span>{{ __('messages.student_settings') }}</span></a>
  </nav>
  <div class="p-3">
    <a href="{{ route('student.live') }}" class="btn w-100 rounded-3 py-3 fw-bold d-flex align-items-center justify-content-center gap-2 sidebar-live-btn">
      <span class="sidebar-live-pulse"></span> <i class="fa-solid fa-video"></i> {{ __('messages.student_live') }}
    </a>
  </div>
</aside>

<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileDashMenu" style="width: 280px;">
  <div class="offcanvas-header border-bottom" style="border-color: rgba(15,109,128,0.06);">
    <img src="{{ asset('images/logo.png') }}" alt="{{ __('messages.site_name') }}" style="height: 36px;" />
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" style="margin: calc(-.5 * var(--bs-offcanvas-padding-y)) auto calc(-.5 * var(--bs-offcanvas-padding-x)) calc(-.5 * var(--bs-offcanvas-padding-y));"></button>
  </div>
  <div class="offcanvas-body p-0">
    <div class="offcanvas-hero">
      <img src="{{ $student->avatar_url ?? asset('images/logo.png') }}" alt="{{ $student->name ?? __('messages.student_student_female') }}" class="offcanvas-avatar" />
      <h6 class="offcanvas-hero-name">{{ $student->name ?? __('messages.student_student_female') }}</h6>
      <small class="offcanvas-hero-role"><i class="fa-solid fa-layer-group"></i> @if($student->level){{ __('messages.student_level') }} {{ $student->level }}@else{{ __('messages.student_hafidh') }}@endif</small>
    </div>
    <nav class="p-3 d-flex flex-column gap-1">
      <a href="{{ route('student.dashboard') }}" class="sidebar-link {{ Request::routeIs('student.dashboard') ? 'active' : '' }}"><span class="sidebar-link-icon"><i class="fa-solid fa-school"></i></span><span>{{ __('messages.student_dashboard') }}</span></a>
      <a href="{{ route('student.courses') }}" class="sidebar-link {{ Request::routeIs('student.courses') ? 'active' : '' }}"><span class="sidebar-link-icon"><i class="fa-solid fa-book-open"></i></span><span>{{ __('messages.student_available_courses') }}</span></a>
      <a href="{{ route('student.hifdh-journey') }}" class="sidebar-link {{ Request::routeIs('student.hifdh-journey') ? 'active' : '' }}"><span class="sidebar-link-icon"><i class="fa-solid fa-chart-line"></i></span><span>{{ __('messages.student_hifdh_journey') }}</span></a>

      @if(isset($student) && $student->courses->count() > 0)
      <div class="sidebar-section-label mt-3 mb-1 px-2">
        <small class="fw-bold">{{ __('messages.student_my_courses_mobile') }}</small>
      </div>
      @foreach($student->courses as $course)
      @php
        $isCourseRoute = Request::routeIs('student.course-detail') && request()->route('course')?->id === $course->id;
      @endphp
      <div class="sidebar-course-item">
        <a href="{{ route('student.course-detail', $course) }}"
           class="sidebar-link d-flex align-items-center justify-content-between {{ $isCourseRoute ? 'active' : '' }}"
           data-bs-toggle="collapse"
           data-bs-target="#mobileCourseMenu{{ $course->id }}">
          <span class="d-flex align-items-center gap-2"><span class="sidebar-link-icon"><i class="fa-solid fa-book-quran"></i></span><span>{{ Str::limit($course->name, 18) }}</span></span>
          <i class="fa-solid fa-chevron-down sidebar-chevron" style="font-size: 0.6rem; transition: transform 0.2s;"></i>
        </a>
        <div class="collapse" id="mobileCourseMenu{{ $course->id }}">
          <div class="sidebar-subnav py-1">
            <a href="{{ route('student.course-detail', $course) }}?tab=lessons" class="sidebar-sub-link">{{ __('messages.student_lessons') }}</a>
            <a href="{{ route('student.course-detail', $course) }}?tab=sessions" class="sidebar-sub-link">{{ __('messages.student_sessions') }}</a>
            <a href="{{ route('student.course-detail', $course) }}?tab=exams" class="sidebar-sub-link">{{ __('messages.student_exams') }}</a>
            <a href="{{ route('student.course-detail', $course) }}?tab=certificates" class="sidebar-sub-link">{{ __('messages.student_certificates') }}</a>
          </div>
        </div>
      </div>
      @endforeach
      @endif

      <div class="sidebar-section-label mt-3 mb-1 px-2">
        <small class="fw-bold">{{ __('messages.student_general') }}</small>
      </div>

      <a href="{{ route('student.messages') }}" class="sidebar-link {{ Request::routeIs('student.messages') ? 'active' : '' }}"><span class="sidebar-link-icon"><i class="fa-solid fa-envelope"></i></span><span>{{ __('messages.student_messages') }}</span>@if(($unreadMessagesCount ?? 0) > 0)<span class="sidebar-badge">{{ $unreadMessagesCount }}</span>@endif</a>
      <a href="{{ route('student.certificates') }}" class="sidebar-link {{ Request::routeIs('student.certificates') ? 'active' : '' }}"><span class="sidebar-link-icon"><i class="fa-solid fa-certificate"></i></span><span>{{ __('messages.student_certificates') }}</span></a>
      <a href="{{ route('student.settings') }}" class="sidebar-link {{ Request::routeIs('student.settings') ? 'active' : '' }}"><span class="sidebar-link-icon"><i class="fa-solid fa-sliders"></i></span><span>{{ __('messages.student_settings') }}</span></a>
    </nav>
    <div class="p-3 mt-2">
      <a href="{{ route('student.live') }}" class="btn w-100 rounded-3 py-3 fw-bold d-flex align-items-center justify-content-center gap-2 sidebar-live-btn"><span class="sidebar-live-pulse"></span><i class="fa-solid fa-video"></i> {{ __('messages.student_live') }}</a>
    </div>
  </div>
</div>

<style>
.sidebar-scroll { overflow-y: auto; max-height: calc(100vh - 220px); }
.sidebar-scroll::-webkit-scrollbar { width: 3px; }
.sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(15,109,128,0.1); border-radius: 10px; }
.sidebar-link[aria-expanded="true"] .sidebar-chevron, .sidebar-chevron.rotated { transform: rotate(180deg); }
</style>

<script>
document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function(el) {
  el.addEventListener('click', function() {
    var chevron = this.querySelector('.sidebar-chevron');
    if (chevron) chevron.classList.toggle('rotated');
  });
});
</script>
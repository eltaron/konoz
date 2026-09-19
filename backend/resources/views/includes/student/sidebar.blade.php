<aside class="dashboard-sidebar">
  <div class="text-center py-4 border-bottom" style="border-color: rgba(15,109,128,0.04) !important;">
    <div class="position-relative d-inline-block mb-2">
      <img src="{{ asset('images/logo.png') }}" alt="{{ $student->name ?? __('messages.student_student_female') }}" class="rounded-circle border-2" style="width: 80px; height: 80px; object-fit: cover; border-color: #0F6D80 !important;" />
      <span class="position-absolute bottom-0 start-0 badge rounded-3 px-2 py-1 small fw-bold" style="background: #F5BD58; color: #5f4100; font-size: 0.6rem; transform: translate(-4px, 4px);">{{ __('messages.student_badge_distinguished') }}</span>
    </div>
    <h6 class="fw-bold mb-0" style="color: #0F6D80;">{{ $student->name ?? __('messages.student_student_female') }}</h6>
    <small class="text-secondary opacity-75">@if($student->level){{ __('messages.student_level') }} {{ $student->level }}@else{{ __('messages.student_hafidh') }}@endif</small>
  </div>
  <nav class="p-3 d-flex flex-column gap-1 flex-grow-1 sidebar-scroll">
    <a href="{{ route('student.dashboard') }}" class="sidebar-link {{ Request::routeIs('student.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-school" style="width: 20px;"></i> {{ __('messages.student_dashboard') }}</a>
    <a href="{{ route('student.courses') }}" class="sidebar-link {{ Request::routeIs('student.courses') ? 'active' : '' }}"><i class="fa-solid fa-book-open" style="width: 20px;"></i> {{ __('messages.student_available_courses') }}</a>
    <a href="{{ route('student.hifdh-journey') }}" class="sidebar-link {{ Request::routeIs('student.hifdh-journey') ? 'active' : '' }}"><i class="fa-solid fa-chart-line" style="width: 20px;"></i> {{ __('messages.student_hifdh_journey') }}</a>

    @if(isset($student) && $student->courses->count() > 0)
    <div class="sidebar-section-label mt-2 mb-1 px-2">
      <small class="fw-bold" style="color: #6b7a7e; font-size: 0.65rem;">{{ __('messages.student_my_courses') }}</small>
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
        <span><i class="fa-solid fa-book-quran" style="width: 20px;"></i> {{ Str::limit($course->name, 20) }}</span>
        <i class="fa-solid fa-chevron-down sidebar-chevron {{ $isCourseRoute ? 'rotated' : '' }}" style="font-size: 0.6rem; transition: transform 0.2s;"></i>
      </a>
      <div class="collapse {{ $isCourseRoute ? 'show' : '' }}" id="courseMenu{{ $course->id }}">
        <div class="sidebar-subnav py-1">
          <a href="{{ route('student.course-detail', $course) }}?tab=lessons" class="sidebar-sub-link {{ $isCourseRoute && request('tab') === 'lessons' ? 'active' : '' }}">
            <i class="fa-solid fa-list" style="width: 18px;"></i> {{ __('messages.student_lessons') }}
          </a>
          <a href="{{ route('student.course-detail', $course) }}?tab=sessions" class="sidebar-sub-link {{ $isCourseRoute && request('tab') === 'sessions' ? 'active' : '' }}">
            <i class="fa-regular fa-calendar" style="width: 18px;"></i> {{ __('messages.student_sessions') }}
          </a>
          <a href="{{ route('student.course-detail', $course) }}?tab=exams" class="sidebar-sub-link {{ $isCourseRoute && request('tab') === 'exams' ? 'active' : '' }}">
            <i class="fa-solid fa-file-pen" style="width: 18px;"></i> {{ __('messages.student_exams') }}
          </a>
          <a href="{{ route('student.course-detail', $course) }}?tab=certificates" class="sidebar-sub-link {{ $isCourseRoute && request('tab') === 'certificates' ? 'active' : '' }}">
            <i class="fa-solid fa-certificate" style="width: 18px;"></i> {{ __('messages.student_certificates') }}
          </a>
        </div>
      </div>
    </div>
    @endforeach
    @endif

    <div class="sidebar-section-label mt-3 mb-1 px-2">
      <small class="fw-bold" style="color: #6b7a7e; font-size: 0.65rem;">{{ __('messages.student_general') }}</small>
    </div>
    <a href="{{ route('student.messages') }}" class="sidebar-link {{ Request::routeIs('student.messages') ? 'active' : '' }}"><i class="fa-solid fa-envelope" style="width: 20px;"></i> {{ __('messages.student_messages') }}</a>
    <a href="{{ route('student.certificates') }}" class="sidebar-link {{ Request::routeIs('student.certificates') && !request('course_id') ? 'active' : '' }}"><i class="fa-solid fa-certificate" style="width: 20px;"></i> {{ __('messages.student_certificates') }}</a>
    <a href="{{ route('student.settings') }}" class="sidebar-link {{ Request::routeIs('student.settings') ? 'active' : '' }}"><i class="fa-solid fa-sliders" style="width: 20px;"></i> {{ __('messages.student_settings') }}</a>
  </nav>
  <div class="p-3">
    <a href="{{ route('student.live') }}" class="btn w-100 rounded-3 py-3 fw-bold d-flex align-items-center justify-content-center gap-2" style="background: #0F6D80; color: #fff;">
      <i class="fa-solid fa-video"></i> {{ __('messages.student_live') }}
    </a>
  </div>
</aside>

<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileDashMenu" style="width: 280px;">
  <div class="offcanvas-header border-bottom" style="border-color: rgba(15,109,128,0.06);">
    <img src="{{ asset('images/logo.png') }}" alt="{{ __('messages.site_name') }}" style="height: 36px;" />
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" style="margin: calc(-.5 * var(--bs-offcanvas-padding-y)) auto calc(-.5 * var(--bs-offcanvas-padding-x)) calc(-.5 * var(--bs-offcanvas-padding-y));"></button>
  </div>
  <div class="offcanvas-body p-0">
    <div class="text-center py-4" style="background: #f7fafb;">
      <img src="{{ asset('images/logo.png') }}" alt="{{ $student->name ?? __('messages.student_student_female') }}" class="rounded-circle border-2 mb-2" style="width: 72px; height: 72px; object-fit: cover; border-color: #0F6D80 !important;" />
    <h6 class="fw-bold mb-0" style="color: #0F6D80;">{{ $student->name ?? __('messages.student_student_female') }}</h6>
      <small class="text-secondary opacity-75">@if($student->level){{ __('messages.student_level') }} {{ $student->level }}@else{{ __('messages.student_hafidh') }}@endif</small>
    </div>
    <nav class="p-3 d-flex flex-column gap-1">
      <a href="{{ route('student.dashboard') }}" class="sidebar-link {{ Request::routeIs('student.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-school" style="width: 20px;"></i> {{ __('messages.student_dashboard') }}</a>
      <a href="{{ route('student.courses') }}" class="sidebar-link {{ Request::routeIs('student.courses') ? 'active' : '' }}"><i class="fa-solid fa-book-open" style="width: 20px;"></i> {{ __('messages.student_available_courses') }}</a>
      <a href="{{ route('student.hifdh-journey') }}" class="sidebar-link {{ Request::routeIs('student.hifdh-journey') ? 'active' : '' }}"><i class="fa-solid fa-chart-line" style="width: 20px;"></i> {{ __('messages.student_hifdh_journey') }}</a>

      @if(isset($student) && $student->courses->count() > 0)
      <div class="sidebar-section-label mt-2 mb-1 px-2">
        <small class="fw-bold" style="color: #6b7a7e; font-size: 0.65rem;">{{ __('messages.student_my_courses_mobile') }}</small>
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
          <span><i class="fa-solid fa-book-quran" style="width: 20px;"></i> {{ Str::limit($course->name, 18) }}</span>
          <i class="fa-solid fa-chevron-down sidebar-chevron" style="font-size: 0.6rem; transition: transform 0.2s;"></i>
        </a>
        <div class="collapse" id="mobileCourseMenu{{ $course->id }}">
          <div class="sidebar-subnav py-1">
            <a href="{{ route('student.course-detail', $course) }}?tab=lessons" class="sidebar-sub-link"> {{ __('messages.student_lessons') }}</a>
            <a href="{{ route('student.course-detail', $course) }}?tab=sessions" class="sidebar-sub-link"> {{ __('messages.student_sessions') }}</a>
            <a href="{{ route('student.course-detail', $course) }}?tab=exams" class="sidebar-sub-link"> {{ __('messages.student_exams') }}</a>
            <a href="{{ route('student.course-detail', $course) }}?tab=certificates" class="sidebar-sub-link"> {{ __('messages.student_certificates') }}</a>
          </div>
        </div>
      </div>
      @endforeach
      @endif

      <div class="sidebar-section-label mt-3 mb-1 px-2">
        <small class="fw-bold" style="color: #6b7a7e; font-size: 0.65rem;">{{ __('messages.student_general') }}</small>
      </div>

      <a href="{{ route('student.certificates') }}" class="sidebar-link {{ Request::routeIs('student.certificates') ? 'active' : '' }}"><i class="fa-solid fa-certificate" style="width: 20px;"></i> {{ __('messages.student_certificates') }}</a>
      <a href="{{ route('student.settings') }}" class="sidebar-link {{ Request::routeIs('student.settings') ? 'active' : '' }}"><i class="fa-solid fa-sliders" style="width: 20px;"></i> {{ __('messages.student_settings') }}</a>
    </nav>
    <div class="p-3 mt-2">
      <a href="{{ route('student.live') }}" class="btn w-100 rounded-3 py-3 fw-bold d-flex align-items-center justify-content-center gap-2" style="background: #0F6D80; color: #fff;"><i class="fa-solid fa-video"></i> {{ __('messages.student_live') }}</a>
    </div>
  </div>
</div>

<style>
.sidebar-scroll { overflow-y: auto; max-height: calc(100vh - 200px); }
.sidebar-scroll::-webkit-scrollbar { width: 3px; }
.sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(15,109,128,0.1); border-radius: 10px; }
.sidebar-section-label { letter-spacing: 0.5px; }
.sidebar-course-item { margin-bottom: 1px; }
.sidebar-subnav { padding-inline-start: 28px !important; }
.sidebar-sub-link { display: flex; align-items: center; gap: 8px; padding: 6px 12px; border-radius: 8px; font-size: 0.78rem; color: #4a5a5e; text-decoration: none; transition: 0.15s; }
.sidebar-sub-link:hover { background: rgba(15,109,128,0.04); color: #0F6D80; }
.sidebar-sub-link.active { background: rgba(15,109,128,0.06); color: #0F6D80; font-weight: 600; }
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

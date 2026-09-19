<nav class="bottom-mobile-nav">
  <a href="{{ route('student.dashboard') }}" class="mobile-nav-item {{ Request::routeIs('student.dashboard') ? 'active' : '' }}"><span class="mobile-nav-icon"><i class="fa-solid fa-school"></i></span><span class="mobile-nav-label">{{ __('messages.student_dashboard') }}</span></a>
  <a href="{{ route('student.courses') }}" class="mobile-nav-item {{ Request::routeIs('student.courses', 'student.my-courses', 'student.course-detail') ? 'active' : '' }}"><span class="mobile-nav-icon"><i class="fa-solid fa-book-open"></i></span><span class="mobile-nav-label">{{ __('messages.student_available_courses') }}</span></a>
  <a href="{{ route('student.my-courses') }}" class="mobile-nav-item {{ Request::routeIs('student.my-courses') ? 'active' : '' }}"><span class="mobile-nav-icon"><i class="fa-solid fa-graduation-cap"></i></span><span class="mobile-nav-label">{{ __('messages.student_my_courses') }}</span></a>
  <a href="{{ route('student.hifdh-journey') }}" class="mobile-nav-item {{ Request::routeIs('student.hifdh-journey') ? 'active' : '' }}"><span class="mobile-nav-icon"><i class="fa-solid fa-chart-line"></i></span><span class="mobile-nav-label">{{ __('messages.student_hifdh_journey') }}</span></a>
  <a href="{{ route('student.settings') }}" class="mobile-nav-item {{ Request::routeIs('student.settings') ? 'active' : '' }}"><span class="mobile-nav-icon"><i class="fa-solid fa-sliders"></i></span><span class="mobile-nav-label">{{ __('messages.student_settings') }}</span></a>
</nav>


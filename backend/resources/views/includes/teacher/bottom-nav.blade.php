<nav class="bottom-mobile-nav">
  <a class="mobile-nav-item {{ Request::routeIs('teacher.dashboard') ? 'active' : '' }}" href="{{ route('teacher.dashboard') }}"><span class="mobile-nav-icon"><i class="fa-solid fa-chart-pie"></i></span><span class="mobile-nav-label">{{ __('messages.teacher_bottom_home') }}</span></a>
  <a class="mobile-nav-item {{ Request::routeIs('teacher.courses') ? 'active' : '' }}" href="{{ route('teacher.courses') }}"><span class="mobile-nav-icon"><i class="fa-solid fa-book-open"></i></span><span class="mobile-nav-label">{{ __('messages.teacher_bottom_courses') }}</span></a>
  <a class="mobile-nav-item {{ Request::routeIs('teacher.students') ? 'active' : '' }}" href="{{ route('teacher.students') }}"><span class="mobile-nav-icon"><i class="fa-solid fa-users"></i></span><span class="mobile-nav-label">{{ __('messages.teacher_bottom_students') }}</span></a>
  <a class="mobile-nav-item {{ Request::routeIs('teacher.sessions') ? 'active' : '' }}" href="{{ route('teacher.sessions') }}"><span class="mobile-nav-icon"><i class="fa-solid fa-video"></i></span><span class="mobile-nav-label">{{ __('messages.teacher_bottom_sessions') }}</span></a>
  <a class="mobile-nav-item {{ Request::routeIs('teacher.exams') ? 'active' : '' }}" href="{{ route('teacher.exams') }}"><span class="mobile-nav-icon"><i class="fa-solid fa-pen-to-square"></i></span><span class="mobile-nav-label">{{ __('messages.teacher_bottom_exams') }}</span></a>
  <a class="mobile-nav-item {{ Request::routeIs('teacher.certificates') ? 'active' : '' }}" href="{{ route('teacher.certificates') }}"><span class="mobile-nav-icon"><i class="fa-solid fa-certificate"></i></span><span class="mobile-nav-label">{{ __('messages.teacher_bottom_certificates') }}</span></a>
</nav>


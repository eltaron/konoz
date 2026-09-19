<aside class="teacher-sidebar p-3">
  <div class="d-flex align-items-center gap-2 mb-4 px-2 pt-2">
    <img src="{{ asset('images/logo.png') }}" alt="{{ __('messages.site_name') }}" class="teacher-sidebar-logo">
    <span class="sidebar-brand fw-bold small text-decoration-none">{{ __('messages.site_name') }}</span>
  </div>

  <span class="sidebar-section-label px-2 mb-2 d-block">{{ __('messages.teacher_main_menu') }}</span>
  <nav class="d-flex flex-column gap-1 flex-grow-1">
    <a class="teacher-sidebar-link {{ Request::routeIs('teacher.dashboard') ? 'active' : '' }}" href="{{ route('teacher.dashboard') }}"><i class="fa-solid fa-chart-pie" style="width:20px;"></i>{{ __('messages.teacher_dashboard') }}</a>
    <a class="teacher-sidebar-link {{ Request::routeIs('teacher.courses') ? 'active' : '' }}" href="{{ route('teacher.courses') }}"><i class="fa-solid fa-book-open" style="width:20px;"></i>{{ __('messages.teacher_courses') }}</a>
    <a class="teacher-sidebar-link {{ Request::routeIs('teacher.students') ? 'active' : '' }}" href="{{ route('teacher.students') }}"><i class="fa-solid fa-users" style="width:20px;"></i>{{ __('messages.teacher_students') }}</a>
    <a class="teacher-sidebar-link {{ Request::routeIs('teacher.sessions') ? 'active' : '' }}" href="{{ route('teacher.sessions') }}"><i class="fa-solid fa-video" style="width:20px;"></i>{{ __('messages.teacher_sessions') }}</a>
    <a class="teacher-sidebar-link {{ Request::routeIs('teacher.schedule') ? 'active' : '' }}" href="{{ route('teacher.schedule') }}"><i class="fa-solid fa-calendar-days" style="width:20px;"></i>{{ __('messages.teacher_schedule') }}</a>
    <a class="teacher-sidebar-link {{ Request::routeIs('teacher.messages') ? 'active' : '' }}" href="{{ route('teacher.messages') }}"><i class="fa-solid fa-envelope" style="width:20px;"></i>{{ __('messages.teacher_messages') }}</a>
    <a class="teacher-sidebar-link {{ Request::routeIs('teacher.exams') ? 'active' : '' }}" href="{{ route('teacher.exams') }}"><i class="fa-solid fa-pen-to-square" style="width:20px;"></i>{{ __('messages.teacher_exams') }}</a>
    <a class="teacher-sidebar-link {{ Request::routeIs('teacher.certificates') ? 'active' : '' }}" href="{{ route('teacher.certificates') }}"><i class="fa-solid fa-certificate" style="width:20px;"></i>{{ __('messages.teacher_certificates') }}</a>
  </nav>

  <div class="sidebar-divider border-top pt-3 mt-3">
    <span class="sidebar-section-label px-2 mb-2 d-block">{{ __('messages.teacher_menu_manage') }}</span>
    <div class="d-flex flex-column gap-1">
      <a class="teacher-sidebar-link {{ Request::routeIs('teacher.reports') ? 'active' : '' }}" href="{{ route('teacher.reports') }}"><i class="fa-solid fa-chart-simple" style="width:20px;"></i>{{ __('messages.teacher_reports') }}</a>
      <a class="teacher-sidebar-link {{ Request::routeIs('teacher.settings') ? 'active' : '' }}" href="{{ route('teacher.settings') }}"><i class="fa-solid fa-gear" style="width:20px;"></i>{{ __('messages.teacher_settings') }}</a>
      <form method="POST" action="{{ route('logout') }}" style="display:inline;">
        @csrf
        <button type="submit" class="teacher-sidebar-link logout" style="border:none;background:none;width:100%;text-align:right;cursor:pointer;"><i class="fa-solid fa-arrow-right-from-bracket" style="width:20px;"></i>{{ __('messages.teacher_logout') }}</button>
      </form>
    </div>
  </div>
</aside>

<div class="offcanvas offcanvas-start teacher-offcanvas" tabindex="-1" id="teacherOffcanvas">
  <div class="offcanvas-header">
    <img src="{{ asset('images/logo.png') }}" alt="{{ __('messages.site_name') }}" style="height:36px;">
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <div class="offcanvas-profile">
      <img src="{{ asset('images/logo.png') }}" alt="{{ __('messages.teacher_female') }}" class="offcanvas-avatar">
      <h6 class="offcanvas-name">{{ Auth::user()->name ?? __('messages.teacher_female') }}</h6>
      <small class="offcanvas-role">{{ __('messages.teacher_quran_teacher') }}</small>
    </div>
    <div class="p-3">
      <span class="sidebar-section-label d-block mb-2" style="color:#0F6D80;font-size:0.7rem;letter-spacing:1px;font-weight:600;">{{ __('messages.teacher_main_menu') }}</span>
      <nav class="d-flex flex-column gap-1">
        <a class="teacher-sidebar-link {{ Request::routeIs('teacher.dashboard') ? 'active' : '' }}" href="{{ route('teacher.dashboard') }}"><i class="fa-solid fa-chart-pie" style="width:20px;"></i>{{ __('messages.teacher_dashboard') }}</a>
        <a class="teacher-sidebar-link {{ Request::routeIs('teacher.courses') ? 'active' : '' }}" href="{{ route('teacher.courses') }}"><i class="fa-solid fa-book-open" style="width:20px;"></i>{{ __('messages.teacher_courses') }}</a>
        <a class="teacher-sidebar-link {{ Request::routeIs('teacher.students') ? 'active' : '' }}" href="{{ route('teacher.students') }}"><i class="fa-solid fa-users" style="width:20px;"></i>{{ __('messages.teacher_students') }}</a>
        <a class="teacher-sidebar-link {{ Request::routeIs('teacher.sessions') ? 'active' : '' }}" href="{{ route('teacher.sessions') }}"><i class="fa-solid fa-video" style="width:20px;"></i>{{ __('messages.teacher_sessions') }}</a>
        <a class="teacher-sidebar-link {{ Request::routeIs('teacher.exams') ? 'active' : '' }}" href="{{ route('teacher.exams') }}"><i class="fa-solid fa-pen-to-square" style="width:20px;"></i>{{ __('messages.teacher_exams') }}</a>
        <a class="teacher-sidebar-link {{ Request::routeIs('teacher.certificates') ? 'active' : '' }}" href="{{ route('teacher.certificates') }}"><i class="fa-solid fa-certificate" style="width:20px;"></i>{{ __('messages.teacher_certificates') }}</a>
      </nav>
      <span class="sidebar-section-label d-block mb-2 mt-3" style="color:#0F6D80;font-size:0.7rem;letter-spacing:1px;font-weight:600;">{{ __('messages.teacher_menu_manage') }}</span>
      <div class="d-flex flex-column gap-1">
        <a href="{{ route('teacher.settings') }}" class="teacher-sidebar-link"><i class="fa-solid fa-gear" style="width:20px;"></i>{{ __('messages.teacher_settings') }}</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
          @csrf
          <button type="submit" class="teacher-sidebar-link logout" style="border:none;background:none;width:100%;text-align:right;cursor:pointer;"><i class="fa-solid fa-arrow-right-from-bracket" style="width:20px;"></i>{{ __('messages.teacher_logout') }}</button>
        </form>
      </div>
    </div>
  </div>
</div>
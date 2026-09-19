<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu">
  <div class="offcanvas-header">
    <div class="d-flex align-items-center gap-2">
      <img alt="{{ __('messages.site_logo_alt') }}" src="{{ asset('images/logo.png') }}" class="header-logo" style="height:40px;" />
      
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="{{ __('messages.nav_close') }}"></button>
  </div>
  <div class="offcanvas-body">
    <nav class="d-flex flex-column gap-1">
      <a class="mobile-nav-{{ Request::routeIs('home') ? 'active' : 'link' }}" href="{{ route('home') }}">{{ __('messages.nav_home') }}</a>
      <a class="mobile-nav-{{ Request::routeIs('departments') ? 'active' : 'link' }}" href="{{ route('departments') }}">{{ __('messages.nav_departments') }}</a>
      <a class="mobile-nav-sublink" href="#"><i class="fa-solid fa-language"></i>{{ __('messages.nav_sublink_languages') }}</a>
      <a class="mobile-nav-sublink" href="#"><i class="fa-solid fa-laptop-code"></i>{{ __('messages.nav_sublink_technology') }}</a>
      <a class="mobile-nav-sublink" href="#"><i class="fa-solid fa-hands-holding-child"></i>{{ __('messages.nav_sublink_support') }}</a>
      <a class="mobile-nav-{{ Request::routeIs('blog') ? 'active' : 'link' }}" href="{{ route('blog') }}">{{ __('messages.nav_blog') }}</a>
      <a class="mobile-nav-{{ Request::routeIs('about') ? 'active' : 'link' }}" href="{{ route('about') }}">{{ __('messages.nav_about') }}</a>
      <a class="mobile-nav-{{ Request::routeIs('reviews') ? 'active' : 'link' }}" href="{{ route('reviews') }}">{{ __('messages.nav_reviews') }}</a>
      <a class="mobile-nav-{{ Request::routeIs('contact') ? 'active' : 'link' }}" href="{{ route('contact') }}">{{ __('messages.nav_contact') }}</a>
      <a class="mobile-nav-link" href="{{ route('lang.switch', session('locale') === 'en' ? 'ar' : 'en') }}"><i class="fa-solid fa-globe"></i> {{ session('locale') === 'en' ? __('messages.nav_lang_arabic') : __('messages.nav_lang_english') }}</a>
    </nav>
    <hr class="my-3" />
    <a href="tel:+201001234567" class="btn btn-outline-primary w-100 rounded-3 py-3 mb-2"><i class="fa-solid fa-phone ml-2"></i>{{ __('messages.nav_call_us') }}</a>
    @auth
    <a href="{{ Auth::user()->role === 'admin' || Auth::user()->role === 'teacher' ? route('teacher.dashboard') : route('student.dashboard') }}" class="btn btn-primary w-100 rounded-3 py-3 btn-custom"><i class="fa-solid fa-gauge-high ml-2"></i>{{ __('messages.nav_dashboard') }}</a>
    <form method="POST" action="{{ route('logout') }}" style="display:inline;width:100%;">
        @csrf
        <button type="submit" class="btn btn-outline-secondary w-100 rounded-3 py-3 mt-2" style="background:transparent;color:#3f484b;border:1.5px solid #d0d9db;"><i class="fa-solid fa-right-from-bracket ml-2"></i>{{ __('messages.nav_logout_full') }}</button>
    </form>
    @else
    <a href="{{ route('login') }}" class="btn btn-primary w-100 rounded-3 py-3 btn-custom">{{ __('messages.nav_login_account') }}</a>
    @endauth
  </div>
</div>

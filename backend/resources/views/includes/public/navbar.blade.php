<header class="sticky-top header-nav">
    <div class="container d-flex align-items-center justify-content-between" style="height: 72px">
        <div class="d-flex align-items-center gap-2">
            <button class="btn d-lg-none mobile-toggle" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#mobileMenu" aria-label="{{ __('messages.nav_menu') }}"><i class="fa-solid fa-bars"></i></button>
            <a href="{{ route('home') }}" class="d-flex align-items-center gap-2 text-decoration-none"><img
                    alt="{{ __('messages.site_logo_alt') }}" src="{{ asset('images/logo.png') }}" class="header-logo" /></a>
            <div class="header-brand-sep d-none d-lg-block"></div>
            <nav class="d-none d-lg-flex header-nav-links">
                <a class="nav-link-{{ Request::routeIs('home') ? 'active' : 'custom' }}"
                    href="{{ route('home') }}">{{ __('messages.nav_home') }}</a>
                <a class="nav-link-{{ Request::routeIs('departments') ? 'active' : 'custom' }}"
                    href="{{ route('departments') }}">{{ __('messages.nav_departments') }}</a>
                <a class="nav-link-{{ Request::routeIs('blog') ? 'active' : 'custom' }}"
                    href="{{ route('blog') }}">{{ __('messages.nav_blog') }}</a>
                <a class="nav-link-{{ Request::routeIs('about') ? 'active' : 'custom' }}" href="{{ route('about') }}">{{ __('messages.nav_about') }}</a>
                <a class="nav-link-{{ Request::routeIs('reviews') ? 'active' : 'custom' }}"
                    href="{{ route('reviews') }}">{{ __('messages.nav_reviews') }}</a>
                <a class="nav-link-{{ Request::routeIs('contact') ? 'active' : 'custom' }}"
                    href="{{ route('contact') }}">{{ __('messages.nav_contact') }}</a>
                <a class="nav-lang-link" href="{{ route('lang.switch', session('locale') === 'en' ? 'ar' : 'en') }}" data-lang-switch><i
                        class="fa-solid fa-globe"></i> {{ session('locale') === 'en' ? __('messages.nav_lang_arabic') : __('messages.nav_lang_english') }}</a>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="tel:+201001234567" class="header-phone-btn d-none d-sm-flex" aria-label="{{ __('messages.nav_call_us') }}"><i
                    class="fa-solid fa-phone"></i></a>
            @auth
            <a href="{{ Auth::user()->role === 'admin' || Auth::user()->role === 'teacher' ? route('teacher.dashboard') : route('student.dashboard') }}" class="btn btn-primary fw-bold btn-header-cta"><i class="fa-solid fa-gauge-high ml-1 d-none d-sm-inline"></i>{{ __('messages.nav_dashboard') }}</a>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-outline-secondary fw-bold btn-header-cta" style="border:none;background:transparent;color:#3f484b;"><i class="fa-solid fa-right-from-bracket ml-1 d-none d-sm-inline"></i>{{ __('messages.nav_logout') }}</button>
            </form>
            @else
            <a href="{{ route('login') }}" class="btn btn-primary fw-bold btn-header-cta"><i
                    class="fa-solid fa-user ml-1 d-none d-sm-inline"></i>{{ __('messages.nav_login') }}</a>
            @endauth
        </div>
    </div>
</header>

@include('includes.public.offcanvas')

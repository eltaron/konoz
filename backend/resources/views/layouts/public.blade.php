<!doctype html>
<html dir="{{ session('locale', 'ar') === 'ar' ? 'rtl' : 'ltr' }}" lang="{{ session('locale', 'ar') }}">
<head>
  @include('includes.public.head')
</head>
<body class="public-page">
  @include('includes.public.loader')
  @include('includes.public.navbar')

  <main>
    @yield('content')
  </main>

  @include('includes.public.footer')

  <a id="support-fab" class="support-fab" href="{{ route('contact') }}"><i class="fa-solid fa-headset"></i></a>

  @include('includes.public.scripts')
</body>
</html>


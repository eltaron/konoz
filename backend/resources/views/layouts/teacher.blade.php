<!doctype html>
<html dir="{{ session('locale', 'ar') === 'ar' ? 'rtl' : 'ltr' }}" lang="{{ session('locale', 'ar') }}">
<head>
  @include('includes.teacher.head')
</head>
<body>
  @include('includes.teacher.sidebar')

  @include('includes.teacher.header')

  @include('includes.teacher.bottom-nav')

  <main class="teacher-main">
    @yield('content')
  </main>

  @include('includes.teacher.scripts')
</body>
</html>


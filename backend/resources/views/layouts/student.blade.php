<!doctype html>
<html dir="{{ session('locale', 'ar') === 'ar' ? 'rtl' : 'ltr' }}" lang="{{ session('locale', 'ar') }}">
<head>
  @include('includes.student.head')
</head>
<body>
  @include('includes.student.header')

  @include('includes.student.sidebar')

  <main class="dashboard-main px-3 px-md-4">
    @yield('content')
  </main>

  @include('includes.student.bottom-nav')

  @include('includes.student.scripts')
</body>
</html>


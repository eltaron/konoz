<!doctype html>
<html dir="{{ session('locale', 'ar') === 'ar' ? 'rtl' : 'ltr' }}" lang="{{ session('locale', 'ar') }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ __('messages.auth_login_title', ['site_name' => __('messages.site_name')]) }}</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
  <link href="{{ session('locale', 'ar') === 'ar' ? 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css' : 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' }}" rel="stylesheet" id="bootstrap-css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
  <link href="{{ asset('css/direction.css') }}" rel="stylesheet">
  <style>
    * { font-family: 'Tajawal', sans-serif; }
    body { background: linear-gradient(135deg, #0a4d5a 0%, #0F6D80 30%, #1a8a9e 70%, #d89b1d 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
    .auth-card { background: #fff; border-radius: 20px; padding: 40px; width: 100%; max-width: 450px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
    .auth-logo { width: 80px; height: 80px; object-fit: contain; margin-bottom: 10px; }
    .auth-title { color: #0F6D80; font-weight: 800; font-size: 1.5rem; margin-bottom: 5px; }
    .auth-subtitle { color: #6c757d; font-size: 0.9rem; margin-bottom: 25px; }
    .form-control { border-radius: 12px; padding: 12px 16px; border: 2px solid #e9ecef; font-size: 0.95rem; }
    .form-control:focus { border-color: #0F6D80; box-shadow: 0 0 0 3px rgba(15,109,128,0.15); }
    .btn-auth { background: linear-gradient(135deg, #0F6D80, #1a8a9e); color: #fff; border: none; border-radius: 12px; padding: 12px; font-weight: 700; font-size: 1rem; width: 100%; transition: all 0.3s; }
    .btn-auth:hover { background: linear-gradient(135deg, #0a4d5a, #0F6D80); color: #fff; transform: translateY(-2px); box-shadow: 0 8px 25px rgba(15,109,128,0.4); }
    .auth-link { color: #0F6D80; text-decoration: none; font-weight: 500; }
    .auth-link:hover { color: #d89b1d; }
    .divider { display: flex; align-items: center; gap: 15px; margin: 20px 0; color: #000000; font-weight: bold; }
    .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #e9ecef; }
    .social-btn { border: 2px solid #e9ecef; border-radius: 12px; padding: 10px; display: flex; align-items: center; justify-content: center; gap: 8px; color: #495057; text-decoration: none; transition: all 0.3s; font-weight: 500; }
    .social-btn:hover { border-color: #0F6D80; color: #0F6D80; background: rgba(15,109,128,0.05); }
    .alert-danger { border-radius: 12px; font-size: 0.9rem; }
    .form-check-input:checked { background-color: #0F6D80; border-color: #0F6D80; }
    .form-check-input[type=checkbox] { border-radius: .25em; border-color: #000000; }
    @media (max-width: 480px) { .auth-card { padding: 25px 20px; } }
  </style>
</head>
<body>
  <div class="auth-card">
    <div class="text-center mb-2">
      <img src="{{ asset('images/logo.png') }}" alt="{{ __('messages.site_name') }}" class="auth-logo" onerror="this.style.display='none'">
      <h1 class="auth-title">{{ __('messages.site_name') }}</h1>
      <p class="auth-subtitle">{{ __('messages.auth_login_heading') }}</p>
    </div>

    @if(isset($errors) && $errors->any())
    <div class="alert alert-danger">
      <i class="fa-solid fa-circle-exclamation ml-1"></i>
      @foreach($errors->all() as $error) {{ $error }} @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="form-floating mb-3">
        <input type="email" name="email" class="form-control" id="email" placeholder="{{ __('messages.auth_email') }}" value="{{ old('email') }}" required autofocus>
        <label for="email"><i class="fa-regular fa-envelope ml-1"></i>{{ __('messages.auth_email') }}</label>
      </div>
      <div class="form-floating mb-3">
        <input type="password" name="password" class="form-control" id="password" placeholder="{{ __('messages.auth_password') }}" required>
        <label for="password"><i class="fa-solid fa-lock ml-1"></i>{{ __('messages.auth_password') }}</label>
      </div>
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
          <label class="form-check-label" for="remember">{{ __('messages.auth_remember') }}</label>
        </div>
        <a href="{{ route('password.request') }}" class="auth-link small">{{ __('messages.auth_forgot_password') }}</a>
      </div>
      <button type="submit" class="btn-auth"><i class="fa-solid fa-arrow-left ml-1"></i>{{ __('messages.auth_login_btn') }}</button>
    </form>

    <div class="divider">{{ session('locale', 'ar') === 'en' ? 'OR' : 'أو' }}</div>

    <a href="#" class="social-btn mb-3" onclick="event.preventDefault();alert('{{ session('locale', 'ar') === 'en' ? 'Coming soon' : 'سيتم تفعيل هذه الميزة قريباً' }}')">
      <i class="fa-brands fa-google" style="color:#DB4437;"></i>{{ session('locale', 'ar') === 'en' ? 'Sign in with Google' : 'تسجيل الدخول بواسطة Google' }}
    </a>

    <p class="text-center mt-3 mb-0 small">
      {{ __('messages.auth_no_account') }}
      <a href="{{ route('register') }}" class="auth-link">{{ __('messages.auth_register') }}</a>
    </p>

    <div class="text-center mt-3">
      <a href="{{ route('home') }}" class="auth-link small"><i class="fa-solid fa-arrow-right ml-1"></i>{{ __('messages.nav_home') }}</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{{ asset('js/direction.js') }}"></script>
  @if(session('status'))
  <script>Swal.fire({ icon: 'success', title: '{{ session('locale', 'ar') === 'en' ? 'Done!' : 'تم!' }}', text: '{{ session('status') }}', confirmButtonColor: '#0F6D80' });</script>
  @endif
  @if(session('error'))
  <script>Swal.fire({ icon: 'error', title: '{{ session('locale', 'ar') === 'en' ? 'Error' : 'خطأ' }}', text: '{{ session('error') }}', confirmButtonColor: '#0F6D80' });</script>
  @endif
</body>
</html>

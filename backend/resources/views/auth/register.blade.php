<!doctype html>
<html dir="{{ session('locale', 'ar') === 'ar' ? 'rtl' : 'ltr' }}" lang="{{ session('locale', 'ar') }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ __('messages.auth_register_title', ['site_name' => __('messages.site_name')]) }}</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
  <link href="{{ session('locale', 'ar') === 'ar' ? 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css' : 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' }}" rel="stylesheet" id="bootstrap-css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
  <link href="{{ asset('css/direction.css') }}" rel="stylesheet">
  <style>
    * { font-family: 'Tajawal', sans-serif; }
    body { background: linear-gradient(135deg, #0a4d5a 0%, #0F6D80 30%, #1a8a9e 70%, #d89b1d 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
    .auth-card { background: #fff; border-radius: 20px; padding: 40px; width: 100%; max-width: 500px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
    .auth-logo { width: 80px; height: 80px; object-fit: contain; margin-bottom: 10px; }
    .auth-title { color: #0F6D80; font-weight: 800; font-size: 1.5rem; margin-bottom: 5px; }
    .auth-subtitle { color: #6c757d; font-size: 0.9rem; margin-bottom: 25px; }
    .form-control { border-radius: 12px; padding: 12px 16px; border: 2px solid #e9ecef; font-size: 0.95rem; }
    .form-control:focus { border-color: #0F6D80; box-shadow: 0 0 0 3px rgba(15,109,128,0.15); }
    .btn-auth { background: linear-gradient(135deg, #0F6D80, #1a8a9e); color: #fff; border: none; border-radius: 12px; padding: 12px; font-weight: 700; font-size: 1rem; width: 100%; transition: all 0.3s; }
    .btn-auth:hover { background: linear-gradient(135deg, #0a4d5a, #0F6D80); color: #fff; transform: translateY(-2px); box-shadow: 0 8px 25px rgba(15,109,128,0.4); }
    .auth-link { color: #0F6D80; text-decoration: none; font-weight: 500; }
    .auth-link:hover { color: #d89b1d; }
    .alert-danger { border-radius: 12px; font-size: 0.9rem; }
    .divider { display: flex; align-items: center; gap: 15px; margin: 20px 0; color: #000000; font-weight: bold; }
    .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #e9ecef; }
    .social-btn { border: 2px solid #e9ecef; border-radius: 12px; padding: 10px; display: flex; align-items: center; justify-content: center; gap: 8px; color: #495057; text-decoration: none; transition: all 0.3s; font-weight: 500; }
    .social-btn:hover { border-color: #0F6D80; color: #0F6D80; background: rgba(15,109,128,0.05); }
    .form-check-input:checked { background-color: #0F6D80; border-color: #0F6D80; }
    .password-hint { font-size: 0.8rem; color: #6c757d; margin-top: 5px; }
    @media (max-width: 480px) { .auth-card { padding: 25px 20px; } }
  </style>
</head>
<body>
  <div class="auth-card">
    <div class="text-center mb-2">
      <img src="{{ asset('images/logo.png') }}" alt="{{ __('messages.site_name') }}" class="auth-logo" onerror="this.style.display='none'">
      <h1 class="auth-title">{{ __('messages.site_name') }}</h1>
      <p class="auth-subtitle">{{ __('messages.auth_register_heading') }}</p>
    </div>

    @if(isset($errors) && $errors->any())
    <div class="alert alert-danger">
      <i class="fa-solid fa-circle-exclamation ml-1"></i>
      @foreach($errors->all() as $error) {{ $error }} @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
      @csrf
      <div class="form-floating mb-3">
        <input type="text" name="name" class="form-control" id="name" placeholder="{{ __('messages.auth_name') }}" value="{{ old('name') }}" required autofocus>
        <label for="name"><i class="fa-regular fa-user ml-1"></i>{{ __('messages.auth_name') }}</label>
      </div>
      <div class="form-floating mb-3">
        <input type="email" name="email" class="form-control" id="email" placeholder="{{ __('messages.auth_email') }}" value="{{ old('email') }}" required>
        <label for="email"><i class="fa-regular fa-envelope ml-1"></i>{{ __('messages.auth_email') }}</label>
      </div>
      <div class="form-floating mb-3">
        <input type="password" name="password" class="form-control" id="password" placeholder="{{ __('messages.auth_password') }}" required>
        <label for="password"><i class="fa-solid fa-lock ml-1"></i>{{ __('messages.auth_password') }}</label>
        <div class="password-hint">{{ session('locale', 'ar') === 'en' ? 'Must be at least 8 characters' : 'يجب أن تكون 8 أحرف على الأقل' }}</div>
      </div>
      <div class="form-floating mb-4">
        <input type="password" name="password_confirmation" class="form-control" id="password-confirm" placeholder="{{ __('messages.auth_confirm_password') }}" required>
        <label for="password-confirm"><i class="fa-solid fa-lock ml-1"></i>{{ __('messages.auth_confirm_password') }}</label>
      </div>
      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="agree" required>
        <label class="form-check-label small" for="agree">{{ session('locale', 'ar') === 'en' ? 'I agree to the' : 'أوافق على' }} <a href="{{ route('terms') }}" class="auth-link">{{ session('locale', 'ar') === 'en' ? 'Terms & Conditions' : 'الشروط والأحكام' }}</a> {{ session('locale', 'ar') === 'en' ? 'and' : 'و' }} <a href="{{ route('privacy') }}" class="auth-link">{{ session('locale', 'ar') === 'en' ? 'Privacy Policy' : 'سياسة الخصوصية' }}</a></label>
      </div>
      <button type="submit" class="btn-auth">{{ __('messages.auth_register_btn') }}</button>
    </form>

    <div class="divider">{{ session('locale', 'ar') === 'en' ? 'OR' : 'أو' }}</div>

    <a href="{{ route('auth.google.redirect') }}" class="social-btn mb-3">
      <i class="fa-brands fa-google" style="color:#DB4437;"></i>{{ session('locale', 'ar') === 'en' ? 'Sign up with Google' : 'إنشاء حساب بواسطة Google' }}
    </a>

    <p class="text-center mt-3 mb-0 small">
      {{ __('messages.auth_have_account') }}
      <a href="{{ route('login') }}" class="auth-link">{{ __('messages.auth_login_heading') }}</a>
    </p>

    <div class="text-center mt-3">
      <a href="{{ route('home') }}" class="auth-link small"><i class="fa-solid fa-arrow-right ml-1"></i>{{ __('messages.nav_home') }}</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{{ asset('js/direction.js') }}"></script>
  <script>
    document.querySelector('form')?.addEventListener('submit', function(e) {
      var pass = document.getElementById('password');
      var confirm = document.getElementById('password-confirm');
      if (pass.value !== confirm.value) {
        e.preventDefault();
        Swal.fire({ icon: 'error', title: '{{ session('locale', 'ar') === 'en' ? 'Error' : 'خطأ' }}', text: '{{ session('locale', 'ar') === 'en' ? 'Password and confirmation do not match' : 'كلمة المرور وتأكيدها غير متطابقين' }}', confirmButtonColor: '#0F6D80' });
      }
    });
  </script>
</body>
</html>

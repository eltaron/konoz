<!doctype html>
<html dir="{{ session('locale', 'ar') === 'ar' ? 'rtl' : 'ltr' }}" lang="{{ session('locale', 'ar') }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ __('messages.auth_reset_password_title', ['site_name' => __('messages.site_name')]) }}</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
  <link href="{{ session('locale', 'ar') === 'ar' ? 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css' : 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' }}" rel="stylesheet" id="bootstrap-css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
  <link href="{{ asset('css/direction.css') }}" rel="stylesheet">
  <style>
    * { font-family: 'Tajawal', sans-serif; }
    body { background: linear-gradient(135deg, #0a4d5a 0%, #0F6D80 30%, #1a8a9e 70%, #d89b1d 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
    .auth-card { background: #fff; border-radius: 20px; padding: 40px; width: 100%; max-width: 450px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
    .auth-logo { width: 70px; height: 70px; object-fit: contain; margin-bottom: 10px; }
    .auth-title { color: #0F6D80; font-weight: 800; font-size: 1.3rem; margin-bottom: 5px; }
    .auth-subtitle { color: #6c757d; font-size: 0.9rem; margin-bottom: 25px; }
    .form-control { border-radius: 12px; padding: 12px 16px; border: 2px solid #e9ecef; font-size: 0.95rem; }
    .form-control:focus { border-color: #0F6D80; box-shadow: 0 0 0 3px rgba(15,109,128,0.15); }
    .btn-auth { background: linear-gradient(135deg, #0F6D80, #1a8a9e); color: #fff; border: none; border-radius: 12px; padding: 12px; font-weight: 700; font-size: 1rem; width: 100%; transition: all 0.3s; }
    .btn-auth:hover { background: linear-gradient(135deg, #0a4d5a, #0F6D80); color: #fff; transform: translateY(-2px); box-shadow: 0 8px 25px rgba(15,109,128,0.4); }
    .auth-link { color: #0F6D80; text-decoration: none; font-weight: 500; }
    .auth-link:hover { color: #d89b1d; }
    .alert-danger { border-radius: 12px; }
    @media (max-width: 480px) { .auth-card { padding: 25px 20px; } }
  </style>
</head>
<body>
  <div class="auth-card">
    <div class="text-center mb-2">
      <img src="{{ asset('images/logo.png') }}" alt="{{ __('messages.site_name') }}" class="auth-logo" onerror="this.style.display='none'">
      <h1 class="auth-title">{{ __('messages.auth_reset_password_heading') }}</h1>
      <p class="auth-subtitle">{{ session('locale', 'ar') === 'en' ? 'Enter your new password' : 'أدخل كلمة المرور الجديدة' }}</p>
    </div>

    @if(isset($errors) && $errors->any())
    <div class="alert alert-danger">
      <i class="fa-solid fa-circle-exclamation ml-1"></i>
      @foreach($errors->all() as $error) {{ $error }} @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">
      <div class="form-floating mb-3">
        <input type="email" name="email" class="form-control" id="email" placeholder="{{ __('messages.auth_email') }}" value="{{ $email ?? old('email') }}" required readonly>
        <label for="email"><i class="fa-regular fa-envelope ml-1"></i>{{ __('messages.auth_email') }}</label>
      </div>
      <div class="form-floating mb-3">
        <input type="password" name="password" class="form-control" id="password" placeholder="{{ session('locale', 'ar') === 'en' ? 'New Password' : 'كلمة المرور الجديدة' }}" required>
        <label for="password"><i class="fa-solid fa-lock ml-1"></i>{{ session('locale', 'ar') === 'en' ? 'New Password' : 'كلمة المرور الجديدة' }}</label>
      </div>
      <div class="form-floating mb-4">
        <input type="password" name="password_confirmation" class="form-control" id="password-confirm" placeholder="{{ __('messages.auth_confirm_password') }}" required>
        <label for="password-confirm"><i class="fa-solid fa-lock ml-1"></i>{{ __('messages.auth_confirm_password') }}</label>
      </div>
      <button type="submit" class="btn-auth"><i class="fa-solid fa-check ml-1"></i>{{ __('messages.auth_reset_password_btn') }}</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{{ asset('js/direction.js') }}"></script>
</body>
</html>

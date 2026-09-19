<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="@yield('error_meta_description')">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon/favicon.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <title>@yield('error_title') - منصة كُنوز التعليمية</title>
    <style>
        * { font-family: 'Tajawal', 'Segoe UI', Tahoma, Arial, sans-serif; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(ellipse at top, rgba(15, 109, 128, 0.18), transparent 55%),
                radial-gradient(ellipse at bottom left, rgba(216, 155, 29, 0.16), transparent 55%),
                linear-gradient(160deg, #f8fafb 0%, #eef4f6 100%);
            padding: 24px;
            margin: 0;
        }

        .error-card {
            max-width: 560px;
            width: 100%;
            background: #ffffff;
            border: 1px solid rgba(15, 109, 128, 0.12);
            border-radius: 20px;
            box-shadow: 0 24px 60px rgba(15, 109, 128, 0.16);
            padding: 40px 32px 32px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .error-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            height: 6px;
            background: linear-gradient(90deg, #0f6d80, #d89b1d, #0f6d80);
        }

        .error-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 22px;
        }

        .error-logo img {
            width: 48px;
            height: 48px;
            object-fit: contain;
        }

        .error-logo span {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f6d80;
        }

        .error-code {
            font-size: 6.5rem;
            line-height: 1;
            font-weight: 800;
            color: #0f6d80;
            letter-spacing: 2px;
            margin-bottom: 8px;
        }

        .error-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 74px;
            height: 74px;
            border-radius: 22px;
            background: rgba(216, 155, 29, 0.14);
            color: #d89b1d;
            font-size: 2rem;
            margin-bottom: 14px;
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #17384a;
            margin-bottom: 10px;
        }

        .error-message {
            font-size: 0.98rem;
            color: #5a6a72;
            line-height: 1.9;
            max-width: 420px;
            margin: 0 auto 8px;
        }

        .error-details {
            text-align: right;
            margin: 18px auto 0;
            max-width: 460px;
            background: #f7fafb;
            border: 1px dashed rgba(15, 109, 128, 0.25);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.78rem;
            color: #0f6d80;
            word-break: break-word;
        }

        .error-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-top: 26px;
        }

        .btn-primary-brand {
            background: #0f6d80;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            padding: 11px 26px;
            transition: background 0.2s ease;
            text-decoration: none;
        }

        .btn-primary-brand:hover {
            background: #0c5665;
            color: #fff;
        }

        .btn-outline-brand {
            background: transparent;
            color: #0f6d80;
            border: 1.5px solid #0f6d80;
            border-radius: 10px;
            font-weight: 700;
            padding: 10px 24px;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-outline-brand:hover {
            background: rgba(15, 109, 128, 0.08);
            color: #0f6d80;
        }

        .error-footer {
            margin-top: 24px;
            font-size: 0.78rem;
            color: #94a7af;
        }

        @media (max-width: 480px) {
            .error-code { font-size: 5rem; }
            .error-card { padding: 30px 20px 24px; }
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-logo">
            <img src="{{ asset('images/logo.png') }}" alt="شعار منصة كُنوز">
            <span>منصة كُنوز التعليمية</span>
        </div>

        @yield('error_icon')

        <div class="error-code">@yield('error_code')</div>
        <h1 class="error-title">@yield('error_heading')</h1>
        <p class="error-message">@yield('error_message')</p>

        @yield('error_technical')

        <div class="error-actions">
            @yield('error_actions')
        </div>

        <div class="error-footer">
            &copy; {{ date('Y') }} منصة كُنوز التعليمية - جميع الحقوق محفوظة
        </div>
    </div>
</body>
</html>
<!doctype html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject ?? 'كنوز القرآن' }}</title>
</head>
<body style="margin:0; padding:0; background:#f2f7f9; font-family:'Segoe UI', Tahoma, Arial, sans-serif;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f2f7f9; padding:24px 12px;">
    <tr>
        <td align="center">
            <table role="presentation" width="620" cellpadding="0" cellspacing="0" style="max-width:620px; width:100%; background:#ffffff; border-radius:16px; overflow:hidden; border:1px solid #e3eef1; box-shadow:0 6px 24px rgba(15,109,128,0.10);">

                {{-- Header --}}
                <tr>
                    <td style="background:linear-gradient(135deg,#0a4d5a 0%,#0F6D80 55%,#1a8a9e 100%); padding:28px 32px; text-align:center;">
                        @php
                            try {
                                $emailLogo = $message->embed(public_path('images/logo.png'));
                            } catch (\Throwable $e) {
                                $emailLogo = asset('images/logo.png');
                            }
                        @endphp
                        <img src="{{ $emailLogo }}" alt="كنوز القرآن" width="72" height="72" style="width:72px; height:72px; border-radius:14px; border:3px solid rgba(255,255,255,0.25); background:#ffffff; object-fit:contain;">
                        <h1 style="margin:10px 0 0; color:#ffffff; font-size:22px; font-weight:800;">{{ $siteName ?? 'منصة كُنوز التعليمية' }}</h1>
                    </td>
                </tr>

                {{-- Body --}}
                <tr>
                    <td style="padding:36px 36px 24px; color:#2c3e50; font-size:15px; line-height:1.9;">
                        @if(isset($greeting))
                            <p style="margin:0 0 18px; font-size:17px; font-weight:700; color:#0F6D80;">{{ $greeting }}</p>
                        @endif

                        @yield('content')

                        @if(!empty($ctaUrl))
                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin:26px auto 6px;">
                                <tr>
                                    <td style="border-radius:50px;">
                                        <a href="{{ $ctaUrl }}" style="display:inline-block; background:linear-gradient(135deg,#0F6D80,#1a8a9e); color:#ffffff; text-decoration:none; padding:12px 38px; border-radius:50px; font-size:15px; font-weight:700; box-shadow:0 6px 16px rgba(15,109,128,0.30);">
                                            {{ $ctaLabel ?? 'اضغط هنا' }}
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        @endif

                        <p style="margin:28px 0 0; color:#7f8c8d; font-size:13px; line-height:1.8;">
                            مع أطيب التمنيات،<br>
                            <strong style="color:#0F6D80;">{{ $siteName ?? 'منصة كُنوز التعليمية' }}</strong>
                        </p>
                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td style="background:#fafdfe; border-top:1px solid #e3eef1; padding:20px 32px; text-align:center; color:#9aa7ad; font-size:12px; line-height:2;">
                        <a href="{{ $siteUrl ?? '#' }}" style="color:#0F6D80; text-decoration:none; font-weight:700;">www.konozplatform.com</a><br>
                        جميع الحقوق محفوظة © {{ date('Y') }} — منصة كُنوز التعليمية
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
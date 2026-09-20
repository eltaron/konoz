@extends('emails.layout')

@section('content')
<p style="margin:0 0 16px;">
    مرحباً <strong>{{ $userName }}</strong>،
</p>
<p style="margin:0 0 16px;">
    استلمنا طلباً لإعادة تعيين كلمة مرور حسابك على <strong>منصة كُنوز التعليمية</strong>.
</p>
<p style="margin:0 0 16px;">
    اضغطي الزر أدناه لاختيار كلمة مرور جديدة. رابط إعادة التعيين صالح لمدة <strong>60 دقيقة</strong> فقط.
</p>
<p style="margin:0 0 4px; color:#7f8c8d; font-size:13px;">
    إذا لم تطلبي ذلك، يمكنك تجاهل هذه الرسالة — ستبقى كلمة مرورك الحالية كما هي.
</p>
@endsection
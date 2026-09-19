@extends('errors.layout')

@section('error_title', 'غير مصرح')
@section('error_meta_description', 'يجب تسجيل الدخول للوصول إلى هذه الصفحة')

@section('error_icon')
    <div class="error-icon"><i class="fa-solid fa-lock"></i></div>
@endsection

@section('error_code', '401')

@section('error_heading', 'غير مصرح')

@section('error_message')
    يجب تسجيل الدخول أولاً للوصول إلى هذه الصفحة.
    سجّل دخولك ثم حاول مرة أخرى.
@endsection

@section('error_technical')
    @if (config('app.debug') && isset($exception))
        <div class="error-details"><b>تفاصيل تقنية:</b> {{ $exception->getMessage() }}</div>
    @endif
@endsection

@section('error_actions')
    <a href="{{ route('login') }}" class="btn-primary-brand"><i class="fa-solid fa-right-to-bracket"></i> تسجيل الدخول</a>
    <a href="{{ url('/') }}" class="btn-outline-brand"><i class="fa-solid fa-house"></i> العودة إلى الرئيسية</a>
@endsection
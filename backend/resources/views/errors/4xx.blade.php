@extends('errors.layout')

@section('error_title', 'خطأ في الطلب')
@section('error_meta_description', 'تعذر معالجة الطلب')

@section('error_icon')
    <div class="error-icon"><i class="fa-solid fa-circle-exclamation"></i></div>
@endsection

@section('error_code')
    @if (isset($exception) && method_exists($exception, 'getStatusCode'))
        {{ $exception->getStatusCode() }}
    @else
        4xx
    @endif
@endsection

@section('error_heading', 'تعذر معالجة الطلب')

@section('error_message')
    تعذر التعرف على هذا الطلب أو معالجته.
    تحقق من الرابط وحاول مرة أخرى.
@endsection

@section('error_technical')
    @if (config('app.debug') && isset($exception))
        <div class="error-details"><b>تفاصيل تقنية:</b> {{ $exception->getMessage() }}</div>
    @endif
@endsection

@section('error_actions')
    <a href="{{ url('/') }}" class="btn-primary-brand"><i class="fa-solid fa-house"></i> العودة إلى الرئيسية</a>
    <a href="javascript:history.back()" class="btn-outline-brand"><i class="fa-solid fa-arrow-right"></i> الرجوع للخلف</a>
@endsection
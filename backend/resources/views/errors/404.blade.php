@extends('errors.layout')

@section('error_title', 'الصفحة غير موجودة')
@section('error_meta_description', 'الصفحة التي تبحث عنها غير موجودة')

@section('error_icon')
    <div class="error-icon"><i class="fa-solid fa-map-location-dot"></i></div>
@endsection

@section('error_code', '404')

@section('error_heading', 'الصفحة غير موجودة')

@section('error_message')
    عذراً، الصفحة التي تبحث عنها غير موجودة أو تم نقلها أو حذفها.
    تأكد من صحة الرابط أو عد إلى الصفحة الرئيسية.
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
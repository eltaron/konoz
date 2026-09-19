@extends('errors.layout')

@section('error_title', 'الخدمة غير متاحة')
@section('error_meta_description', 'المنصة غير متاحة حالياً')

@section('error_icon')
    <div class="error-icon"><i class="fa-solid fa-gears"></i></div>
@endsection

@section('error_code', '503')

@section('error_heading', 'الخدمة غير متاحة')

@section('error_message')
    نعمل حالياً على صيانة المنصة وتحسينها.
    عُد إلينا بعد قليل، ونعتذر عن أي إزعاج.
@endsection

@section('error_technical')
    @if (config('app.debug') && isset($exception))
        <div class="error-details"><b>تفاصيل تقنية:</b> {{ $exception->getMessage() }}</div>
    @endif
@endsection

@section('error_actions')
    <a href="javascript:window.location.reload()" class="btn-primary-brand">
        <i class="fa-solid fa-rotate"></i> المحاولة مرة أخرى
    </a>
    <a href="{{ url('/') }}" class="btn-outline-brand"><i class="fa-solid fa-house"></i> العودة إلى الرئيسية</a>
@endsection
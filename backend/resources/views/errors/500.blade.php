@extends('errors.layout')

@section('error_title', 'خطأ في الخادم')
@section('error_meta_description', 'حدث خطأ غير متوقع في الخادم')

@section('error_icon')
    <div class="error-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
@endsection

@section('error_code', '500')

@section('error_heading', 'خطأ في الخادم')

@section('error_message')
    عذراً، حدث خطأ غير متوقع أثناء معالجة طلبك.
    حاول مرة أخرى بعد قليل، وإذا استمرت المشكلة تواصل مع الدعم الفني.
@endsection

@section('error_technical')
    @if (config('app.debug') && isset($exception))
        <div class="error-details">
            <b>تفاصيل تقنية:</b> {{ $exception->getMessage() }}
            @if ($exception->getFile())
                <br><small>{{ basename($exception->getFile()) }} : {{ $exception->getLine() }}</small>
            @endif
        </div>
    @endif
@endsection

@section('error_actions')
    <a href="javascript:window.location.reload()" class="btn-primary-brand">
        <i class="fa-solid fa-rotate"></i> إعادة المحاولة
    </a>
    <a href="{{ url('/') }}" class="btn-outline-brand"><i class="fa-solid fa-house"></i> العودة إلى الرئيسية</a>
@endsection
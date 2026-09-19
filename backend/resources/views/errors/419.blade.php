@extends('errors.layout')

@section('error_title', 'انتهت صلاحية الجلسة')
@section('error_meta_description', 'انتهت صلاحية الجلسة، أعد تحميل الصفحة وحاول مرة أخرى')

@section('error_icon')
    <div class="error-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
@endsection

@section('error_code', '419')

@section('error_heading', 'انتهت صلاحية الجلسة')

@section('error_message')
    انتهت مدة الجلسة بسبب انتهاء الوقت أو عدم النشاط.
    أعد تحميل الصفحة وحاول مرة أخرى.
@endsection

@section('error_technical')
    @if (config('app.debug') && isset($exception))
        <div class="error-details"><b>تفاصيل تقنية:</b> {{ $exception->getMessage() }}</div>
    @endif
@endsection

@section('error_actions')
    <a href="javascript:window.location.reload()" class="btn-primary-brand">
        <i class="fa-solid fa-rotate"></i> إعادة تحميل الصفحة
    </a>
    <a href="{{ url('/') }}" class="btn-outline-brand"><i class="fa-solid fa-house"></i> العودة إلى الرئيسية</a>
@endsection
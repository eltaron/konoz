@extends('errors.layout')

@section('error_title', 'ممنوع الوصول')
@section('error_meta_description', 'ليس لديك صلاحية للوصول إلى هذه الصفحة')

@section('error_icon')
    <div class="error-icon"><i class="fa-solid fa-hand"></i></div>
@endsection

@section('error_code', '403')

@section('error_heading', 'ممنوع الوصول')

@section('error_message')
    ليس لديك صلاحية كافية للوصول إلى هذه الصفحة.
    إذا كنت تعتقد أن هذا خطأ، تواصل مع الدعم الفني.
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
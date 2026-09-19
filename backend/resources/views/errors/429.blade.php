@extends('errors.layout')

@section('error_title', 'طلبات كثيرة')
@section('error_meta_description', 'تجاوزت الحد الأقصى من الطلبات، حاول لاحقاً')

@section('error_icon')
    <div class="error-icon"><i class="fa-solid fa-gauge-high"></i></div>
@endsection

@section('error_code', '429')

@section('error_heading', 'طلبات كثيرة جداً')

@section('error_message')
    أرسلت الكثير من الطلبات خلال فترة قصيرة.
    انتظر قليلاً ثم حاول مرة أخرى.
@endsection

@section('error_technical')
    @if (config('app.debug') && isset($exception))
        <div class="error-details"><b>تفاصيل تقنية:</b> {{ $exception->getMessage() }}</div>
    @endif
@endsection

@section('error_actions')
    <a href="{{ url()->previous() ?? '/' }}" class="btn-primary-brand"><i class="fa-solid fa-arrow-right"></i> العودة للصفحة السابقة</a>
    <a href="{{ url('/') }}" class="btn-outline-brand"><i class="fa-solid fa-house"></i> العودة إلى الرئيسية</a>
@endsection
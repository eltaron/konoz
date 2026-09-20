@extends('layouts.student')

@section('title', __('messages.student_settings') . ' | ' . __('messages.site_name'))

@section('meta_description', __('messages.student_meta_desc', ['site_name' => __('messages.site_name')]))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard/dashboard-settings.css') }}">
@endpush

@section('content')
    <div class="st-hero" data-aos="fade-down">
        <div class="st-hero-bg"></div>
        <div class="st-hero-content">
            <div class="st-hero-icon"><i class="fa-solid fa-gear"></i></div>
            <h1 class="st-hero-title">{{ __('messages.student_settings') }}</h1>
            <p class="st-hero-subtitle">{{ __('messages.student_settings_subtitle') }}</p>
            <div class="st-lang-toggle">
                <label class="st-lang-label">{{ __('messages.student_language') }}</label>
                <select class="st-lang-select"
                    onchange="if(this.value!=='{{ session('locale', 'ar') }}'){window.location.href='{{ url('lang') }}/'+this.value;}">
                    <option value="ar" {{ session('locale', 'ar') === 'ar' ? 'selected' : '' }}>
                        {{ __('messages.nav_lang_arabic') }}</option>
                    <option value="en" {{ session('locale', 'ar') === 'en' ? 'selected' : '' }}>
                        {{ __('messages.nav_lang_english') }}</option>
                </select>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('student.settings.update') }}" id="settingsForm" enctype="multipart/form-data"
        class="st-form">
        @csrf

        <div data-aos="fade-up">
            <div class="st-main">
                <div class="st-card st-card-profile">
                    <div class="st-card-header">
                        <div class="st-card-icon"><i class="fa-solid fa-user"></i></div>
                        <h3 class="st-card-title">{{ __('messages.student_profile_details') }}</h3>
                    </div>

                    <div class="st-avatar-section">
                        <div class="st-avatar-wrapper">
                            <img id="avatarPreview" src="{{ $student->avatar_url ?? asset('images/logo.png') }}"
                                alt="{{ __('messages.student_profile') }}" class="st-avatar-img" />
                            <label class="st-avatar-overlay" title="{{ __('messages.student_upload_photo') }}">
                                <input type="file" id="avatarInput" name="avatar"
                                    accept="image/png,image/jpeg,image/webp" hidden />
                                <i class="fa-solid fa-camera"></i>
                            </label>
                        </div>
                        <p class="st-avatar-hint">{{ __('messages.student_avatar_note') }}</p>
                        <small class="st-avatar-hint-small">{{ __('messages.student_photo_hint') }}</small>
                    </div>

                    <div class="st-fields">
                        <div class="st-row">
                            <div class="st-field">
                                <label>{{ __('messages.auth_name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name_ar" class="st-input" value="{{ $student->name_ar }}"
                                    required />
                                @error('name_ar')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="st-field">
                                <label>{{ __('messages.student_name_en') }}</label>
                                <input type="text" name="name_en" class="st-input"
                                    value="{{ $student->name_en ?? '' }}" />
                            </div>
                        </div>
                        <div class="st-row">
                            <div class="st-field">
                                <label>{{ __('messages.auth_email') }} <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="st-input" value="{{ $student->email }}"
                                    required />
                                @error('email')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="st-field">
                                <label>{{ __('messages.auth_phone') }}</label>
                                <input type="tel" name="phone" class="st-input"
                                    value="{{ $student->phone ?? '' }}" />
                            </div>
                        </div>
                        <div class="st-row">
                            <div class="st-field">
                                <label>{{ __('messages.student_level') }}</label>
                                <select name="level" class="st-select">
                                    <option value="مبتدئ" {{ ($student->level ?? '') == 'مبتدئ' ? 'selected' : '' }}>
                                        {{ session('locale', 'ar') === 'en' ? 'Beginner' : 'مبتدئ' }}</option>
                                    <option value="متوسط" {{ ($student->level ?? '') == 'متوسط' ? 'selected' : '' }}>
                                        {{ session('locale', 'ar') === 'en' ? 'Intermediate' : 'متوسط' }}</option>
                                    <option value="متقدم" {{ ($student->level ?? '') == 'متقدم' ? 'selected' : '' }}>
                                        {{ session('locale', 'ar') === 'en' ? 'Advanced' : 'متقدم' }}</option>
                                </select>
                            </div>
                            <div class="st-field">
                                <label>{{ __('messages.student_gender') }}</label>
                                <select name="gender" class="st-select">
                                    <option value="">{{ __('messages.student_select_gender') }}</option>
                                    <option value="woman" {{ ($student->gender ?? '') == 'woman' ? 'selected' : '' }}>
                                        {{ __('messages.enroll_gender_woman') }}</option>
                                    <option value="girl" {{ ($student->gender ?? '') == 'girl' ? 'selected' : '' }}>
                                        {{ __('messages.enroll_gender_girl') }}</option>
                                    <option value="boy" {{ ($student->gender ?? '') == 'boy' ? 'selected' : '' }}>
                                        {{ __('messages.enroll_gender_boy') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="st-row">
                            <div class="st-field">
                                <label>{{ __('messages.student_age') }}</label>
                                <input type="number" name="age" class="st-input" min="3" max="100"
                                    value="{{ $student->age ?? '' }}" />
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="st-btn-primary">
                        <i class="fa-solid fa-check me-1"></i>{{ __('messages.student_save_changes') }}
                    </button>
                </div>

                <div class="st-card st-card-password">
                    <div class="st-card-header">
                        <div class="st-card-icon"><i class="fa-solid fa-lock"></i></div>
                        <h3 class="st-card-title">{{ __('messages.student_change_password') }}</h3>
                    </div>
                    <p class="st-password-hint">{{ __('messages.student_password_hint') }}</p>
                    <div class="st-fields">
                        <div class="st-row">
                            <div class="st-field">
                                <label>{{ __('messages.student_current_password') }}</label>
                                <input type="password" name="current_password" class="st-input" placeholder="••••••••"
                                    autocomplete="current-password" />
                            </div>
                        </div>
                        <div class="st-row">
                            <div class="st-field">
                                <label>{{ __('messages.student_new_password') }}</label>
                                <input type="password" name="new_password" class="st-input" placeholder="••••••••"
                                    autocomplete="new-password" />
                                @error('new_password')
                                    <span class="st-error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="st-field">
                                <label>{{ __('messages.auth_confirm_password') }}</label>
                                <input type="password" name="new_password_confirmation" class="st-input"
                                    placeholder="••••••••" autocomplete="new-password" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        @if (session('success'))
            <div class="st-toast" id="successToast">{{ session('success') }}</div>
        @endif
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const avatarInput = document.getElementById('avatarInput');
            const avatarPreview = document.getElementById('avatarPreview');
            if (avatarInput && avatarPreview) {
                avatarInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(ev) {
                            avatarPreview.src = ev.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            const successToast = document.getElementById('successToast');
            if (successToast) {
                setTimeout(() => {
                    successToast.style.opacity = '0';
                    successToast.style.transform = 'translateY(-10px)';
                    setTimeout(() => successToast.remove(), 400);
                }, 3500);
            }

            AOS.init({
                duration: 600,
                once: true
            });
        });
    </script>
@endpush

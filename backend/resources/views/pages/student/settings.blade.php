@extends('layouts.student')

@section('title', __('messages.student_settings') . ' | ' . __('messages.site_name'))

@section('meta_description', __('messages.student_meta_desc', ['site_name' => __('messages.site_name')]))

@section('content')
<h1 class="fw-bold mb-4" style="color: #0F6D80; font-size: 1.6rem;" data-aos="fade-up">{{ __('messages.student_settings') }}</h1>

      <form method="POST" action="{{ route('student.settings.update') }}" id="settingsForm">
      @csrf
      <div class="row g-4">
        <div class="col-lg-8">
          @if(session('success'))
          <div class="alert alert-success py-2 small rounded-3">{{ session('success') }}</div>
          @endif
          <div class="dash-card mb-4" data-aos="fade-up">
            <h5 class="fw-bold mb-3" style="color: #0F6D80;"><i class="fa-regular fa-user me-2"></i>{{ __('messages.student_profile') }}</h5>
            <div class="row g-3">
              <div class="col-md-4 text-center">
                <img src="{{ asset('images/logo.png') }}" alt="{{ __('messages.student_profile') }}" class="rounded-circle border-3 mb-2" style="width: 100px; height: 100px; object-fit: cover; border-color: #0F6D80 !important;" />
                <div><button type="button" class="btn btn-sm rounded-3 px-3 py-1 fw-medium" style="background: rgba(15,109,128,0.04); border: 1px solid rgba(15,109,128,0.08);" onclick="Swal.fire({icon:'info',title:'{{ __('messages.student_change_photo') }}',text:'{{ __('messages.student_feature_development') }}',confirmButtonColor:'#0F6D80',confirmButtonText:'{{ __('messages.student_exam_details_ok') }}'})"><i class="fa-solid fa-camera me-1"></i>{{ __('messages.student_change') }}</button></div>
              </div>
              <div class="col-md-8">
                <div class="row g-3">
                  <div class="col-sm-6">
                    <label class="small fw-medium text-secondary mb-1">{{ __('messages.auth_name') }}</label>
                    <input type="text" name="name_ar" class="form-control form-control-custom" value="{{ $student->name_ar }}" required />
                    @error('name_ar') <span class="text-danger small">{{ $message }}</span> @enderror
                  </div>
                  <div class="col-sm-6">
                    <label class="small fw-medium text-secondary mb-1">{{ __('messages.auth_email') }}</label>
                    <input type="email" name="email" class="form-control form-control-custom" value="{{ $student->email }}" required />
                    @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                  </div>
                  <div class="col-sm-6">
                    <label class="small fw-medium text-secondary mb-1">{{ __('messages.auth_phone') }}</label>
                    <input type="tel" name="phone" class="form-control form-control-custom" value="{{ $student->phone ?? '' }}" />
                  </div>
                  <div class="col-sm-6">
                    <label class="small fw-medium text-secondary mb-1">{{ __('messages.student_level') }}</label>
                    <select name="level" class="form-select form-control-custom">
                      <option value="مبتدئ" {{ ($student->level ?? '') == 'مبتدئ' ? 'selected' : '' }}>{{ session('locale', 'ar') === 'en' ? 'Beginner' : 'مبتدئ' }}</option>
                      <option value="متوسط" {{ ($student->level ?? '') == 'متوسط' ? 'selected' : '' }}>{{ session('locale', 'ar') === 'en' ? 'Intermediate' : 'متوسط' }}</option>
                      <option value="متقدم" {{ ($student->level ?? '') == 'متقدم' ? 'selected' : '' }}>{{ session('locale', 'ar') === 'en' ? 'Advanced' : 'متقدم' }}</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-3 text-start">
              <button type="submit" class="btn rounded-3 px-4 py-2 fw-medium" style="background: #0F6D80; color: #fff;"><i class="fa-solid fa-check ml-1"></i>{{ __('messages.student_save_changes') }}</button>
            </div>
          </div>

          <div class="dash-card mb-4" data-aos="fade-up">
            <h5 class="fw-bold mb-3" style="color: #0F6D80;"><i class="fa-solid fa-lock me-2"></i>{{ __('messages.student_change_password') }}</h5>
            <div class="row g-3">
              <div class="col-sm-4">
                <label class="small fw-medium text-secondary mb-1">{{ __('messages.student_current_password') }}</label>
                <input type="password" name="current_password" class="form-control form-control-custom" placeholder="••••••••" />
              </div>
              <div class="col-sm-4">
                <label class="small fw-medium text-secondary mb-1">{{ __('messages.student_new_password') }}</label>
                <input type="password" name="new_password" class="form-control form-control-custom" placeholder="••••••••" />
              </div>
              <div class="col-sm-4">
                <label class="small fw-medium text-secondary mb-1">{{ __('messages.auth_confirm_password') }}</label>
                <input type="password" name="new_password_confirmation" class="form-control form-control-custom" placeholder="••••••••" />
              </div>
            </div>
          </div>

          <div class="dash-card mb-4" data-aos="fade-up">
            <h5 class="fw-bold mb-3" style="color: #0F6D80;"><i class="fa-regular fa-bell me-2"></i>{{ __('messages.student_notifications') }}</h5>
            <div class="d-flex flex-column gap-3" id="notifSettings"></div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="dash-card mb-4" data-aos="fade-up">
            <h5 class="fw-bold mb-3" style="color: #0F6D80;"><i class="fa-solid fa-sliders me-2"></i>{{ __('messages.student_preferences') }}</h5>
            <div class="d-flex flex-column gap-3">
              <div>
                <label class="small fw-medium text-secondary mb-1">{{ __('messages.student_language') }}</label>
                <select name="locale" class="form-select form-control-custom" onchange="if(this.value!=='{{ session('locale', 'ar') }}'){window.location.href='{{ url('lang') }}/'+this.value;}">
                  <option value="ar" {{ session('locale', 'ar') === 'ar' ? 'selected' : '' }}>{{ __('messages.nav_lang_arabic') }}</option>
                  <option value="en" {{ session('locale', 'ar') === 'en' ? 'selected' : '' }}>{{ __('messages.nav_lang_english') }}</option>
                </select>
              </div>
            </div>
          </div>

          <div class="dash-card" data-aos="fade-up">
            <h5 class="fw-bold mb-3" style="color: #0F6D80;"><i class="fa-solid fa-shield me-2"></i>{{ __('messages.student_account_actions') }}</h5>
            <div class="d-flex flex-column gap-2">
              <button type="button" class="btn w-100 rounded-3 py-2 small fw-medium" style="background: rgba(15,109,128,0.04); border: 1px solid rgba(15,109,128,0.08);" onclick="Swal.fire({icon:'info',title:'{{ __('messages.student_export_data_title') }}',confirmButtonColor:'#0F6D80',confirmButtonText:'{{ __('messages.student_exam_details_ok') }}'})"><i class="fa-solid fa-download me-2"></i>{{ __('messages.student_export_data') }}</button>
              <button type="button" class="btn w-100 rounded-3 py-2 small fw-medium" style="background: rgba(231,76,60,0.04); border: 1px solid rgba(231,76,60,0.08); color: #e74c3c;" onclick="Swal.fire({icon:'warning',title:'{{ __('messages.student_delete_account_title') }}',text:'{{ __('messages.student_delete_account_text') }}',confirmButtonColor:'#e74c3c',confirmButtonText:'{{ __('messages.student_delete_confirm') }}',showCancelButton:true,cancelButtonText:'{{ __('messages.student_cancel') }}'})"><i class="fa-solid fa-trash-can me-2"></i>{{ __('messages.student_delete_account') }}</button>
            </div>
          </div>
        </div>
      </div>
      </form>
@endsection

@push('scripts')
<script>
const notifs = [
        { label: '{{ __("messages.student_notif_upcoming") }}', val: true },
        { label: '{{ __("messages.student_notif_exam_results") }}', val: true },
        { label: '{{ __("messages.student_notif_daily_reminder") }}', val: false },
        { label: '{{ __("messages.student_notif_certificates") }}', val: true },
        { label: '{{ __("messages.student_notif_newsletter") }}', val: false },
      ];
      document.getElementById('notifSettings').innerHTML = notifs.map(n => `
        <div class="d-flex align-items-center justify-content-between">
          <span class="small">${n.label}</span>
          <div class="toggle-switch ${n.val ? 'on' : 'off'}" onclick="this.classList.toggle('on'); this.classList.toggle('off');"></div>
        </div>
      `).join('');

      AOS.init({ duration: 600, once: true });
</script>
@endpush

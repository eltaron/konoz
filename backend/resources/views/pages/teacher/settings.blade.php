@extends('layouts.teacher')

@section('title', __('messages.teacher_settings') . ' | ' . __('messages.site_name'))
@section('meta_description', __('messages.teacher_settings_meta'))
@section('header_greeting', __('messages.teacher_header_greeting', ['name' => $user->name ?? __('messages.teacher_female')]))
@section('page_title', __('messages.teacher_settings_title'))

@push('styles')
<style>
  .settings-tab { padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 0.85rem; color: #5a666a; transition: all 0.2s; cursor: pointer; border: none; background: transparent; text-align: start; display: flex; align-items: center; gap: 10px; }
  .settings-tab:hover { background: rgba(15,109,128,0.04); color: #0F6D80; }
  .settings-tab.active { background: rgba(15,109,128,0.06); color: #0F6D80; }
  .settings-section { display: none; }
  .settings-section.active { display: block; }
  .setting-row { padding: 14px 0; border-bottom: 1px solid rgba(15,109,128,0.04); }
  .setting-row:last-child { border-bottom: none; }
  .setting-label { font-size: 0.85rem; color: #3f484b; font-weight: 600; }
  .setting-desc { font-size: 0.78rem; color: #6b7a7e; }
</style>
@endpush

@section('content')
  <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
    <div>
      <h1 class="fw-bold fs-4 mb-0" style="color:#1f2937;">{{ __('messages.teacher_settings_title') }}</h1>
      <span style="font-size:0.82rem;color:#6b7a7e;">{{ __('messages.teacher_settings_subtitle') }}</span>
    </div>
  </div>

  @if(session('success'))
  <div class="alert alert-success py-2 small rounded-3 mb-3">{{ session('success') }}</div>
  @endif

  <form method="POST" action="{{ route('teacher.settings.update') }}">
  @csrf
  <div class="row g-4">
    <div class="col-12 col-md-3">
      <div class="teacher-card">
        <div class="d-flex flex-column gap-1">
          <button type="button" class="settings-tab active" onclick="switchTab('profile',this)"><i class="fa-regular fa-user"></i>{{ __('messages.teacher_settings_tab_profile') }}</button>
          <button type="button" class="settings-tab" onclick="switchTab('notifications',this)"><i class="fa-solid fa-bell"></i>{{ __('messages.teacher_settings_tab_notifications') }}</button>
          <button type="button" class="settings-tab" onclick="switchTab('account',this)"><i class="fa-solid fa-lock"></i>{{ __('messages.teacher_settings_tab_account') }}</button>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-9">

      <div class="teacher-card settings-section active" id="secProfile">
        <h6 class="fw-bold mb-4" style="color:#1f2937;"><i class="fa-regular fa-user me-2" style="color:#0F6D80;"></i>{{ __('messages.teacher_settings_profile') }}</h6>
        <div class="d-flex align-items-center gap-3 mb-4">
          <img src="{{ asset('images/logo.png') }}" alt="{{ __('messages.teacher_settings_profile') }}" style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:2px solid rgba(15,109,128,0.08);" />
          <div>
            <h6 class="fw-bold mb-1" style="color:#1f2937;">{{ $user->name ?? __('messages.teacher_female') }}</h6>
            <span class="small" style="color:#6b7a7e;">{{ __('messages.teacher_settings_profile_teacher') }}</span>
          </div>
        </div>
        <div class="setting-row">
          <div class="row g-2 align-items-center">
            <div class="col-12 col-md-3"><span class="setting-label">{{ __('messages.teacher_settings_full_name') }}</span></div>
            <div class="col-12 col-md-6">
              <input name="name" class="form-control teacher-input" value="{{ $user->name ?? '' }}" required />
              @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="col-12 col-md-3 text-md-start"></div>
          </div>
        </div>
        <div class="setting-row">
          <div class="row g-2 align-items-center">
            <div class="col-12 col-md-3"><span class="setting-label">{{ __('messages.teacher_settings_email') }}</span></div>
            <div class="col-12 col-md-6">
              <input name="email" class="form-control teacher-input" value="{{ $user->email ?? '' }}" required />
              @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="col-12 col-md-3 text-md-start"></div>
          </div>
        </div>
        <div class="setting-row">
          <div class="row g-2 align-items-center">
            <div class="col-12 col-md-3"><span class="setting-label">{{ __('messages.teacher_settings_new_password') }}</span></div>
            <div class="col-12 col-md-6">
              <input name="password" type="password" class="form-control teacher-input" placeholder="{{ __('messages.teacher_settings_new_password_placeholder') }}" />
              @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
          </div>
        </div>
        <div class="setting-row">
          <div class="row g-2 align-items-center">
            <div class="col-12 col-md-3"><span class="setting-label">{{ __('messages.teacher_settings_confirm_password') }}</span></div>
            <div class="col-12 col-md-6">
              <input name="password_confirmation" type="password" class="form-control teacher-input" placeholder="{{ __('messages.teacher_settings_confirm_password_placeholder') }}" />
            </div>
          </div>
        </div>
        <div class="mt-3 text-start">
          <button type="submit" class="btn action-btn" style="background:#0F6D80;color:#fff;padding:8px 28px;"><i class="fa-solid fa-check ml-1"></i>{{ __('messages.teacher_settings_save') }}</button>
        </div>
      </div>

      <div class="teacher-card settings-section" id="secNotifications">
        <h6 class="fw-bold mb-4" style="color:#1f2937;"><i class="fa-solid fa-bell me-2" style="color:#0F6D80;"></i>{{ __('messages.teacher_settings_tab_notifications') }}</h6>
        <div class="setting-row">
          <div class="d-flex justify-content-between align-items-center">
            <div><span class="setting-label">{{ __('messages.teacher_settings_notif_new_reg') }}</span><div class="setting-desc">{{ __('messages.teacher_settings_notif_new_reg_desc') }}</div></div>
            <div class="form-check form-switch mb-0"><input class="form-check-input" type="checkbox" role="switch" checked style="width:44px;height:22px;cursor:pointer;" /></div>
          </div>
        </div>
        <div class="setting-row">
          <div class="d-flex justify-content-between align-items-center">
            <div><span class="setting-label">{{ __('messages.teacher_settings_notif_exams') }}</span><div class="setting-desc">{{ __('messages.teacher_settings_notif_exams_desc') }}</div></div>
            <div class="form-check form-switch mb-0"><input class="form-check-input" type="checkbox" role="switch" checked style="width:44px;height:22px;cursor:pointer;" /></div>
          </div>
        </div>
        <div class="setting-row">
          <div class="d-flex justify-content-between align-items-center">
            <div><span class="setting-label">{{ __('messages.teacher_settings_notif_sessions') }}</span><div class="setting-desc">{{ __('messages.teacher_settings_notif_sessions_desc') }}</div></div>
            <div class="form-check form-switch mb-0"><input class="form-check-input" type="checkbox" role="switch" checked style="width:44px;height:22px;cursor:pointer;" /></div>
          </div>
        </div>
        <div class="setting-row">
          <div class="d-flex justify-content-between align-items-center">
            <div><span class="setting-label">{{ __('messages.teacher_settings_notif_certs') }}</span><div class="setting-desc">{{ __('messages.teacher_settings_notif_certs_desc') }}</div></div>
            <div class="form-check form-switch mb-0"><input class="form-check-input" type="checkbox" role="switch" checked style="width:44px;height:22px;cursor:pointer;" /></div>
          </div>
        </div>
      </div>

      <div class="teacher-card settings-section" id="secAccount">
        <h6 class="fw-bold mb-4" style="color:#1f2937;"><i class="fa-solid fa-lock me-2" style="color:#0F6D80;"></i>{{ __('messages.teacher_settings_account_security') }}</h6>
        <p class="small text-secondary opacity-75">{{ __('messages.teacher_settings_account_desc') }}</p>
        <hr />
        <div class="d-flex justify-content-between align-items-center">
          <div><span class="setting-label" style="color:#dc3545;">{{ __('messages.teacher_settings_delete_account') }}</span><div class="setting-desc">{{ __('messages.teacher_settings_delete_account_desc') }}</div></div>
          <button type="button" class="btn action-btn" style="background:rgba(220,53,69,0.06);color:#dc3545;padding:6px 18px;" onclick="Swal.fire({icon:'warning',title:'{{ __('messages.teacher_settings_delete_confirm_title') }}',text:'{{ __('messages.teacher_settings_delete_confirm_text') }}',showCancelButton:true,confirmButtonColor:'#dc3545',confirmButtonText:'{{ __('messages.teacher_settings_delete_confirm_yes') }}',cancelButtonText:'{{ __('messages.teacher_settings_delete_confirm_cancel') }}'})"><i class="fa-solid fa-trash-can me-1"></i>{{ __('messages.teacher_settings_delete_account_btn') }}</button>
        </div>
      </div>

    </div>
  </div>
  </form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function switchTab(tab, btn) {
  document.querySelectorAll('.settings-tab').forEach(function(t){ t.classList.remove('active'); });
  btn.classList.add('active');
  document.querySelectorAll('.settings-section').forEach(function(s){ s.classList.remove('active'); });
  var target = document.getElementById('sec'+tab.charAt(0).toUpperCase()+tab.slice(1));
  if (target) target.classList.add('active');
}
</script>
@endpush
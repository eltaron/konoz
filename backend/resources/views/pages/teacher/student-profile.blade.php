@extends('layouts.teacher')

@section('title', __('messages.teacher_student_profile') . ' | ' . __('messages.site_name'))
@section('meta_description', __('messages.teacher_student_profile_meta'))
@section('header_greeting', __('messages.teacher_header_greeting', ['name' => __('messages.teacher_female')]))
@section('page_title', $student->name ?? __('messages.teacher_student_profile'))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard/dashboard-hifdh.css') }}" />
<style>
  .profile-header { background: linear-gradient(135deg, #0F6D80 0%, #083f4b 100%); border-radius: 20px; padding: 30px; color: #fff; position: relative; overflow: hidden; }
  .profile-header::after { content: ''; position: absolute; width: 200px; height: 200px; border-radius: 50%; background: rgba(255,255,255,0.03); top: -60px; left: -60px; }
  .profile-header::before { content: ''; position: absolute; width: 120px; height: 120px; border-radius: 50%; background: rgba(255,255,255,0.03); bottom: -30px; right: -30px; }
  .profile-avatar { width: 80px; height: 80px; border-radius: 50%; border: 3px solid rgba(255,255,255,0.2); object-fit: cover; }
  .profile-stat { background: rgba(255,255,255,0.08); border-radius: 12px; padding: 16px; backdrop-filter: blur(4px); }
  .profile-stat-number { font-size: 1.6rem; font-weight: 800; }
  .profile-stat-label { font-size: 0.75rem; opacity: 0.7; }
  .info-label { font-size: 0.78rem; color: #6b7a7e; font-weight: 600; }
  .info-value { font-size: 0.92rem; color: #1f2937; font-weight: 600; }
  .timeline-item { position: relative; padding-right: 24px; padding-bottom: 20px; }
  .timeline-item::before { content: ''; position: absolute; right: 6px; top: 4px; width: 8px; height: 8px; border-radius: 50%; background: #0F6D80; }
  .timeline-item::after { content: ''; position: absolute; right: 9.5px; top: 16px; width: 1px; height: calc(100% - 16px); background: rgba(15,109,128,0.1); }
  .timeline-item:last-child::after { display: none; }
  .grade-bar { height: 6px; border-radius: 3px; background: rgba(15,109,128,0.06); }
  .grade-bar-fill { height: 100%; border-radius: 3px; background: linear-gradient(90deg, #0F6D80, #198754); }
  .contact-btn { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.15); color: #fff; text-decoration: none; transition: all 0.2s; }
  .contact-btn:hover { background: rgba(255,255,255,0.1); color: #fff; }
  .juz-update-btn { padding: 2px 8px; font-size: 0.65rem; border-radius: 6px; border: none; cursor: pointer; transition: all 0.2s; }
  .juz-update-btn:hover { opacity: 0.8; }
</style>
@endpush

@section('content')
  <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
    <div>
      <a href="{{ route('teacher.students') }}" class="text-decoration-none" style="color:#0F6D80;font-size:0.85rem;"><i class="fa-solid fa-arrow-right me-1"></i>{{ __('messages.teacher_student_profile_back') }}</a>
      <h1 class="fw-bold fs-4 mb-0 mt-1" style="color:#1f2937;">{{ $student->name ?? __('messages.teacher_student_profile') }}</h1>
    </div>
  </div>

  <div class="profile-header mb-4">
    <div class="row g-4 align-items-center position-relative" style="z-index:1;">
      <div class="col-auto">
        <img src="{{ $student->avatar ?? asset('images/logo.png') }}" alt="{{ __('messages.teacher_student_profile') }}" class="profile-avatar" />
      </div>
      <div class="col">
        <div class="d-flex flex-wrap align-items-center gap-3">
          <div>
            <h2 class="fw-bold mb-1 fs-4">{{ $student->name ?? __('messages.teacher_student_profile_student') }}</h2>
            <span class="opacity-75" style="font-size:0.85rem;">
              @if(($student->courses ?? collect())->count() > 0)
                {{ __('messages.teacher_student_profile_in_courses', ['courses' => $student->courses->pluck('name_ar')->implode('، ')]) }}
              @else
                {{ __('messages.teacher_student_profile_not_enrolled') }}
              @endif
            </span>
          </div>
          <span class="teacher-badge teacher-badge-success" style="background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.1);">
            <span class="status-dot" style="background:#fff;"></span>{{ ($student->status ?? '') === 'active' ? __('messages.teacher_student_profile_active') : __('messages.teacher_student_profile_inactive') }}
          </span>
        </div>
        <div class="d-flex flex-wrap gap-3 mt-2">
          @if($student->email ?? false)
          <span style="font-size:0.82rem;opacity:0.7;"><i class="fa-regular fa-envelope me-1"></i>{{ $student->email }}</span>
          @endif
          @if($student->joined_at ?? false)
          <span style="font-size:0.82rem;opacity:0.7;"><i class="fa-regular fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($student->joined_at)->format('Y/m/d') }}</span>
          @endif
        </div>
      </div>
      <div class="col-12 col-md-auto">
        <div class="row g-2">
          <div class="col-4 col-md-12">
            <div class="profile-stat text-center">
              <div class="profile-stat-number">{{ $stats['attendance_rate'] ?? 0 }}%</div>
              <div class="profile-stat-label">{{ __('messages.teacher_student_profile_attendance') }}</div>
            </div>
          </div>
          <div class="col-4 col-md-12">
            <div class="profile-stat text-center">
              <div class="profile-stat-number">{{ $stats['completed_juz'] ?? 0 }}/30</div>
              <div class="profile-stat-label">{{ __('messages.teacher_student_profile_memorized') }}</div>
            </div>
          </div>
          <div class="col-4 col-md-12">
            <div class="profile-stat text-center">
              <div class="profile-stat-number">{{ $stats['total_sessions'] ?? 0 }}</div>
              <div class="profile-stat-label">{{ __('messages.teacher_student_profile_session') }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-12 col-lg-4">
      <div class="teacher-card">
        <h6 class="fw-bold mb-3" style="color:#1f2937;"><i class="fa-regular fa-circle-user me-2" style="color:#0F6D80;"></i>{{ __('messages.teacher_student_profile_info') }}</h6>
        <div class="d-flex justify-content-between py-2 border-bottom" style="border-color:rgba(15,109,128,0.04)!important;">
          <span class="info-label">{{ __('messages.teacher_student_profile_full_name') }}</span>
          <span class="info-value">{{ $student->name ?? '—' }}</span>
        </div>
        @if($student->name_en ?? false)
        <div class="d-flex justify-content-between py-2 border-bottom" style="border-color:rgba(15,109,128,0.04)!important;">
          <span class="info-label">{{ __('messages.teacher_student_profile_name_en') }}</span>
          <span class="info-value">{{ $student->name_en }}</span>
        </div>
        @endif
        <div class="d-flex justify-content-between py-2 border-bottom" style="border-color:rgba(15,109,128,0.04)!important;">
          <span class="info-label">{{ __('messages.teacher_student_profile_email') }}</span>
          <span class="info-value" style="font-size:0.82rem;">{{ $student->email ?? '—' }}</span>
        </div>
        @if($student->phone ?? false)
        <div class="d-flex justify-content-between py-2 border-bottom" style="border-color:rgba(15,109,128,0.04)!important;">
          <span class="info-label">{{ __('messages.teacher_student_profile_phone') }}</span>
          <span class="info-value">{{ $student->phone }}</span>
        </div>
        @endif
        <div class="d-flex justify-content-between py-2 border-bottom" style="border-color:rgba(15,109,128,0.04)!important;">
          <span class="info-label">{{ __('messages.teacher_student_profile_level') }}</span>
          <span><span class="teacher-badge teacher-badge-info">{{ $student->level ?? '—' }}</span></span>
        </div>
        <div class="d-flex justify-content-between py-2">
          <span class="info-label">{{ __('messages.teacher_student_profile_reg_date') }}</span>
          <span class="info-value" style="font-size:0.82rem;">{{ $student->joined_at ? \Carbon\Carbon::parse($student->joined_at)->format('Y/m/d') : ($student->created_at ? $student->created_at->format('Y/m/d') : '—') }}</span>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-8">
      <div class="teacher-card mb-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <h6 class="fw-bold mb-0" style="color:#1f2937;"><i class="fa-solid fa-book-quran me-2" style="color:#0F6D80;"></i>{{ __('messages.teacher_student_profile_hifdh') }}</h6>
          <span class="small text-secondary">{{ __('messages.teacher_student_profile_completed_juz', ['completed' => $stats['completed_juz'] ?? 0, 'total' => 30]) }}</span>
        </div>
        <div class="row g-2">
          @forelse($juzProgress ?? [] as $jp)
          <div class="col-2 col-md-1">
            <div class="position-relative">
              @if($jp->status === 'completed')
              <div class="juz-box completed text-center p-1" style="font-size:0.6rem;padding:4px 2px !important;">
                <div class="juz-num">{{ $jp->juz_number }}</div>
                <small><i class="fa-solid fa-check"></i></small>
              </div>
              @elseif($jp->status === 'reviewing')
              <div class="juz-box reviewing text-center p-1" style="font-size:0.6rem;padding:4px 2px !important;">
                <div class="juz-num">{{ $jp->juz_number }}</div>
                <small><i class="fa-solid fa-rotate"></i></small>
              </div>
              @elseif($jp->status === 'in_progress')
              <div class="juz-box current text-center p-1" style="font-size:0.6rem;padding:4px 2px !important;">
                <div class="juz-num">{{ $jp->juz_number }}</div>
                <small><i class="fa-solid fa-book-open"></i></small>
              </div>
              @else
              <div class="juz-box locked text-center p-1" style="font-size:0.6rem;padding:4px 2px !important;">
                <div class="juz-num">{{ $jp->juz_number }}</div>
                <small><i class="fa-solid fa-lock"></i></small>
              </div>
              @endif
              <div class="position-absolute top-0 start-0" style="transform:translate(-2px,-2px);">
                <button class="juz-update-btn btn btn-light p-0" style="width:14px;height:14px;font-size:0.5rem;border-radius:50%;background:rgba(255,255,255,0.9);" onclick="openJuzStatusModal({{ $jp->juz_number }}, '{{ $jp->status }}')" title="{{ __('messages.teacher_student_profile_update_status') }}"><i class="fa-solid fa-pen"></i></button>
              </div>
            </div>
          </div>
          @empty
          <div class="col-12"><p class="text-center text-secondary opacity-75 small py-3">{{ __('messages.teacher_student_profile_no_data') }}</p></div>
          @endforelse
        </div>
      </div>

      <div class="teacher-card mb-3">
        <h6 class="fw-bold mb-3" style="color:#1f2937;"><i class="fa-solid fa-chart-simple me-2" style="color:#0F6D80;"></i>{{ __('messages.teacher_student_profile_scores') }}</h6>
        <div class="row g-3">
          @forelse($examResults ?? [] as $result)
          <div class="col-12 col-sm-6">
            <div class="p-3 rounded-3" style="background:rgba(15,109,128,0.03);">
              <div class="d-flex justify-content-between mb-1">
                <span style="font-size:0.82rem;color:#3f484b;">{{ $result->exam->title ?? __('messages.teacher_exam_details') }}</span>
                <span class="fw-bold" style="font-size:0.85rem;color:{{ $result->score >= 75 ? '#198754' : ($result->score >= 50 ? '#f39c12' : '#dc3545') }};">{{ $result->score }}%</span>
              </div>
              <div class="grade-bar"><div class="grade-bar-fill" style="width:{{ $result->score }}%;"></div></div>
            </div>
          </div>
          @empty
          <div class="col-12"><p class="text-center text-secondary opacity-75 small py-3">{{ __('messages.teacher_student_profile_no_exams') }}</p></div>
          @endforelse
        </div>
      </div>

      <div class="teacher-card mb-3">
        <h6 class="fw-bold mb-3" style="color:#1f2937;"><i class="fa-solid fa-clock-rotate-left me-2" style="color:#0F6D80;"></i>{{ __('messages.teacher_student_profile_recent_sessions') }}</h6>
        <div>
          @forelse($recentSessions ?? [] as $att)
          <div class="timeline-item">
            <div class="d-flex justify-content-between">
              <div>
                <span class="fw-semibold" style="font-size:0.9rem;color:#1f2937;">{{ $att->session->course->name ?? __('messages.teacher_schedule') }}</span><br>
                <span style="font-size:0.8rem;color:#6b7a7e;">{{ \Carbon\Carbon::parse($att->session->date)->format('Y/m/d') }} - {{ $att->session->time_from ?? '' }}</span>
              </div>
              <span class="teacher-badge {{ ($att->status ?? '') === 'present' ? 'teacher-badge-success' : 'teacher-badge-danger' }}" style="font-size:0.7rem;">{{ ($att->status ?? '') === 'present' ? __('messages.teacher_student_profile_attended') : __('messages.teacher_student_profile_absent') }}</span>
            </div>
          </div>
          @empty
          <p class="text-center text-secondary opacity-75 small py-3">{{ __('messages.teacher_student_profile_no_sessions') }}</p>
          @endforelse
        </div>
      </div>

      <div class="teacher-card">
        <h6 class="fw-bold mb-3" style="color:#1f2937;"><i class="fa-solid fa-chart-line me-2" style="color:#0F6D80;"></i>{{ __('messages.teacher_student_profile_activity') }}</h6>
        @forelse($recentActivities ?? [] as $act)
        <div class="d-flex align-items-center justify-content-between py-2 border-bottom" style="border-color:rgba(15,109,128,0.03)!important;">
          <div>
            <span class="fw-medium small">{{ $act->activity_type }}</span>
            @if($act->duration_minutes ?? false)
            <span class="text-secondary opacity-75 small me-2">{{ $act->duration_minutes }} {{ __('messages.teacher_student_profile_min') }}</span>
            @endif
          </div>
          <small class="text-secondary opacity-75">{{ $act->date ? \Carbon\Carbon::parse($act->date)->format('Y/m/d') : '' }}</small>
        </div>
        @empty
        <p class="text-center text-secondary opacity-75 small py-3">{{ __('messages.teacher_student_profile_no_activity') }}</p>
        @endforelse
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
@if(session('success'))
Swal.fire({ icon:'success', title:'{{ __('messages.teacher_student_profile_success_title') }}', text:'{{ session('success') }}', confirmButtonColor:'#0F6D80' });
@endif

function openJuzStatusModal(juzNumber, currentStatus) {
  var statuses = [
    { value: 'not_started', label: '{{ __('messages.teacher_student_profile_not_started') }}', icon: 'fa-lock', color: '#6c757d' },
    { value: 'in_progress', label: '{{ __('messages.teacher_student_profile_in_progress') }}', icon: 'fa-book-open', color: '#f0b429' },
    { value: 'completed', label: '{{ __('messages.teacher_student_profile_completed') }}', icon: 'fa-check', color: '#198754' },
    { value: 'reviewing', label: '{{ __('messages.teacher_student_profile_reviewing') }}', icon: 'fa-rotate', color: '#0F6D80' }
  ];
  var buttonsHtml = statuses.map(function(s) {
    return '<button type="button" class="juz-status-option' + (s.value === currentStatus ? ' selected' : '') + '" data-status="' + s.value + '" style="width:100%;display:flex;align-items:center;gap:10px;padding:9px 12px;margin-bottom:8px;border-radius:10px;border:1.5px solid ' + (s.value === currentStatus ? s.color : 'rgba(15,109,128,0.12)') + ';background:' + (s.value === currentStatus ? s.color + '14' : '#fff') + ';cursor:pointer;text-align:right;font-size:0.85rem;font-weight:600;color:#1f2937;" onmouseover="this.style.borderColor=\'' + s.color + '\'" onmouseout="this.style.borderColor=\'' + (s.value === currentStatus ? s.color : 'rgba(15,109,128,0.12)') + '\'">' +
      '<i class="fa-solid ' + s.icon + '" style="color:' + s.color + ';width:18px;"></i>' + s.label +
      (s.value === currentStatus ? ' <i class="fa-solid fa-circle-dot ms-auto" style="color:' + s.color + ';font-size:0.7rem;"></i>' : '') +
      '</button>';
  }).join('');
  var html = '<div class="text-end">' +
    '<p class="small text-secondary mb-3" style="margin-top:-8px;">{{ __('messages.teacher_student_profile_juz_subtitle') }}</p>' + buttonsHtml + '</div>';
  Swal.fire({
    title: '<span style="font-size:1.05rem;">{{ __('messages.teacher_student_profile_juz_title') }} <span style="color:#0F6D80;">' + juzNumber + '</span></span>',
    html: html,
    showCancelButton: true, showConfirmButton: false,
    cancelButtonText: '{{ __('messages.teacher_sessions_cancel') }}', cancelButtonColor: '#6c757d',
    didOpen: function() {
      document.querySelectorAll('.juz-status-option').forEach(function(btn) {
        btn.addEventListener('click', function() {
          var status = btn.dataset.status;
          fetch('{{ url('teacher/student/'.$student->id.'/juz') }}/' + juzNumber, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify({ status: status })
          }).then(function(r) {
            if (!r.ok) throw new Error();
            Swal.fire({ icon: 'success', title: '{{ __('messages.teacher_student_profile_success_title') }}', text: '{{ __('messages.teacher_student_profile_juz_updated') }}', confirmButtonColor: '#0F6D80', timer: 1200 });
            setTimeout(function() { location.reload(); }, 1300);
          }).catch(function() {
            Swal.showValidationMessage('{{ __('messages.teacher_student_profile_juz_error') }}');
          });
        });
      });
    },
    allowOutsideClick: false
  });
}
</script>
@endpush
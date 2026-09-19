@extends('layouts.teacher')

@section('title', __('messages.teacher_sessions') . ' | ' . __('messages.site_name'))
@section('meta_description', __('messages.teacher_sessions_meta'))
@section('header_greeting', __('messages.teacher_header_greeting', ['name' => __('messages.teacher_female')]))
@section('page_title', __('messages.teacher_sessions_title'))

@push('styles')
<style>
      :root { --ts-teal: #0F6D80; --ts-teal-dark: #0a4a56; --ts-teal-mid: #157a8c; --ts-gold: #d89b1d; --ts-ink: #1f2937; --ts-muted: #6b7a7e; }

      /* ===== Page header ===== */
      .td-page-title { font-size: 1.35rem; font-weight: 800; color: var(--ts-ink); margin: 0; display: flex; align-items: center; gap: 12px; }
      .td-page-title i { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.05rem; background: linear-gradient(135deg, rgba(15,109,128,0.1), rgba(15,109,128,0.04)); color: var(--ts-teal); }

      /* ===== Buttons ===== */
      .btn-teacher-primary { background: linear-gradient(135deg, var(--ts-teal), var(--ts-teal-mid)); color: #fff; border: none; border-radius: 9px; font-weight: 600; font-size: .78rem; padding: 7px 14px; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
      .btn-teacher-primary:hover { filter: brightness(1.08); color: #fff; }

      /* ===== Cards ===== */
      .td-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 20px rgba(15,109,128,0.05); border: 1px solid rgba(15,109,128,0.06); }

      /* ===== Stat cards ===== */
      .td-stat { background: #fff; border-radius: 16px; padding: 18px 20px; display: flex; align-items: center; gap: 14px; box-shadow: 0 4px 20px rgba(15,109,128,0.05); border: 1px solid rgba(15,109,128,0.06); height: 100%; transition: transform 0.2s ease, box-shadow 0.2s ease; position: relative; overflow: hidden; }
      .td-stat::after { content: ''; position: absolute; top: 0; bottom: 0; right: 0; width: 3px; background: linear-gradient(180deg, var(--ts-teal), var(--ts-gold)); }
      .td-stat:hover { transform: translateY(-3px); box-shadow: 0 10px 28px rgba(15,109,128,0.12); }
      .td-stat-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; background: linear-gradient(135deg, rgba(15,109,128,0.1), rgba(15,109,128,0.04)); color: var(--ts-teal); }
      .td-stat-value { font-size: 1.5rem; font-weight: 800; color: var(--ts-teal); line-height: 1.1; }
      .td-stat-label { font-size: .82rem; color: var(--ts-muted); font-weight: 500; margin: 2px 0 0; }

      /* ===== Badges ===== */
      .td-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 14px; border-radius: 20px; font-size: .76rem; font-weight: 700; white-space: nowrap; }
      .td-badge-strong { background: linear-gradient(135deg, var(--ts-teal), var(--ts-teal-mid)); color: #fff; }
      .td-badge-mid { background: rgba(15, 109, 128, 0.12); color: var(--ts-teal); }
      .td-badge-soft { background: rgba(216, 155, 29, 0.14); color: #b57f0e; }
      .td-badge-danger { background: rgba(220, 53, 69, 0.12); color: #dc3545; }

      /* ===== Table ===== */
      .td-table thead th { border-bottom: 1px solid rgba(15,109,128,0.1); background: #f6fafb; color: #45606b; font-size: .72rem; font-weight: 700; white-space: nowrap; }
      .td-table tbody td { border-bottom: 1px dashed rgba(15,109,128,0.08); vertical-align: middle; font-size: .86rem; }
      .td-table tbody tr:last-child td { border-bottom: none; }
      .td-table tbody tr:hover { background: rgba(15,109,128,0.025); }
      .td-cell-title { font-weight: 700; color: var(--ts-ink); }
      .td-cell-course { font-weight: 600; color: var(--ts-teal); }

      /* ===== Stream link ===== */
      .session-link { display: inline-flex; align-items: center; gap: 6px; color: var(--ts-teal); background: rgba(15,109,128,0.07); border: 1px solid rgba(15,109,128,0.12); border-radius: 8px; padding: 4px 10px; font-size: .76rem; font-weight: 600; text-decoration: none; transition: all 0.15s; }
      .session-link:hover { background: var(--ts-teal); color: #fff; }

      /* ===== Row actions ===== */
      .td-icon-btn { width: 32px; height: 32px; border-radius: 9px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid rgba(15,109,128,0.14); color: var(--ts-teal); transition: all 0.15s; font-size: .78rem; background: #fff; }
      .td-icon-btn:hover { background: var(--ts-teal); color: #fff; }
      .td-icon-btn.danger { color: #dc3545; border-color: rgba(220,53,69,0.2); }
      .td-icon-btn.danger:hover { background: #dc3545; color: #fff; }

      .td-empty-icon { font-size: 2.8rem; color: rgba(15,109,128,0.15); }
  </style>
@endpush

@section('content')
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <h1 class="td-page-title"><i class="fa-solid fa-video"></i>{{ __('messages.teacher_sessions_title') }}</h1>
    <button class="btn-teacher-primary" onclick="showAddSessionModal()"><i class="fa-solid fa-plus me-1"></i>{{ __('messages.teacher_sessions_add') }}</button>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-video"></i></div>
        <div><p class="td-stat-value mb-0">{{ count($sessions) }}</p><p class="td-stat-label mb-0">{{ __('messages.teacher_sessions_total') }}</p></div>
      </div>
    </div>
    <div class="col-6 col-md-4">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-calendar-day"></i></div>
        <div><p class="td-stat-value mb-0">{{ $stats['today'] }}</p><p class="td-stat-label mb-0">{{ __('messages.teacher_sessions_today') }}</p></div>
      </div>
    </div>
    <div class="col-6 col-md-4">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-calendar"></i></div>
        <div><p class="td-stat-value mb-0">{{ $stats['this_week'] }}</p><p class="td-stat-label mb-0">{{ __('messages.teacher_sessions_this_week') }}</p></div>
      </div>
    </div>
    <div class="col-6 col-md-4">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-check-circle"></i></div>
        <div><p class="td-stat-value mb-0">{{ $stats['completed'] }}</p><p class="td-stat-label mb-0">{{ __('messages.teacher_sessions_completed') }}</p></div>
      </div>
    </div>
    <div class="col-6 col-md-4">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-clock"></i></div>
        <div><p class="td-stat-value mb-0">{{ $stats['upcoming'] }}</p><p class="td-stat-label mb-0">{{ __('messages.teacher_sessions_upcoming') }}</p></div>
      </div>
    </div>
    <div class="col-6 col-md-4">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-ban"></i></div>
        <div><p class="td-stat-value mb-0">{{ $stats['cancelled'] }}</p><p class="td-stat-label mb-0">{{ __('messages.teacher_sessions_cancelled') }}</p></div>
      </div>
    </div>
  </div>

  <div class="td-card overflow-hidden">
    <div class="table-responsive">
      <table class="table td-table table-borderless mb-0">
        <thead>
          <tr><th class="px-3 py-3">{{ __('messages.teacher_date') }}</th><th class="px-3 py-3">{{ __('messages.teacher_time') }}</th><th class="px-3 py-3">{{ __('messages.teacher_course') }}</th><th class="px-3 py-3">{{ __('messages.teacher_title') }}</th><th class="px-3 py-3">{{ __('messages.teacher_session_stream_url') }}</th><th class="px-3 py-3">{{ __('messages.teacher_status') }}</th><th class="px-3 py-3" style="text-align:center;width:110px;">{{ __('messages.teacher_actions') }}</th></tr>
        </thead>
        <tbody>
          @forelse($sessions as $s)
          <tr id="session-{{ $s->id }}">
            <td dir="ltr" class="px-3">{{ $s->date ? \Carbon\Carbon::parse($s->date)->format('Y-m-d') : '—' }}</td>
            <td dir="ltr" class="px-3 text-start">{{ $s->time_from ?? '—' }}{{ $s->time_to ? ' - ' . $s->time_to : '' }}</td>
            <td class="td-cell-course px-3">{{ $s->course->name ?? '—' }}</td>
            <td class="td-cell-title px-3">{{ $s->title ?? '—' }}</td>
            <td class="px-3">@if($s->stream_url)<a href="{{ $s->stream_url }}" target="_blank" rel="noopener" class="session-link"><i class="fa-solid fa-video me-1"></i>{{ __('messages.teacher_session_stream_url') }}</a>@else<span class="text-secondary opacity-50">—</span>@endif</td>
            <td class="px-3">
              @php
                $statusMap = ['completed'=>'td-badge-strong','in_progress'=>'td-badge-mid','upcoming'=>'td-badge-soft','cancelled'=>'td-badge-danger','scheduled'=>'td-badge-soft'];
                $statusLabels = ['completed'=>__('messages.teacher_sessions_badge_completed'),'in_progress'=>__('messages.teacher_sessions_badge_in_progress'),'upcoming'=>__('messages.teacher_sessions_badge_upcoming'),'cancelled'=>__('messages.teacher_sessions_badge_cancelled'),'scheduled'=>__('messages.teacher_sessions_badge_scheduled')];
                $sStatus = $s->status ?? 'scheduled';
              @endphp
              <span class="td-badge {{ $statusMap[$sStatus] ?? 'td-badge-soft' }}">{{ $statusLabels[$sStatus] ?? __('messages.teacher_sessions_badge_scheduled') }}</span>
            </td>
            <td class="px-3">
              <div class="d-flex align-items-center justify-content-center gap-1">
                <button class="td-icon-btn" onclick="showEditSessionModal({{ $s->id }}, {{ $s->course_id }}, '{{ addslashes($s->title ?? '') }}', '{{ addslashes($s->title_en ?? '') }}', '{{ $s->date ?? '' }}', '{{ $s->time_from ?? '' }}', '{{ $s->time_to ?? '' }}', '{{ $s->status ?? 'scheduled' }}', '{{ addslashes($s->stream_url ?? '') }}')" title="{{ __('messages.teacher_sessions_edit') }}"><i class="fa-solid fa-pen"></i></button>
                <button class="td-icon-btn danger" onclick="deleteSession({{ $s->id }})" title="{{ __('messages.teacher_sessions_delete') }}"><i class="fa-solid fa-trash-can"></i></button>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center py-5">
              <i class="fa-solid fa-video td-empty-icon mb-3 d-block"></i>
              <p class="text-secondary mb-0">{{ __('messages.teacher_sessions_empty') }}</p>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function getCoursesList() {
  @php
    $coursesList = auth()->user()->role === 'admin'
      ? \App\Models\Course::orderBy('name_ar')->pluck('name_ar', 'id')
      : \App\Models\Course::where('user_id', auth()->id())->orderBy('name_ar')->pluck('name_ar', 'id');
  @endphp
  return @json($coursesList);
}

function showAddSessionModal(preselectCourseId) {
  var courses = getCoursesList();
  var courseOptions = '';
  for (var id in courses) { courseOptions += '<option value="' + id + '"' + (preselectCourseId && id == preselectCourseId ? ' selected' : '') + '>' + courses[id] + '</option>'; }
  var formHtml = '<form id="crudForm" class="text-end">' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_sessions_add_course_label') }}</label><select name="course_id" class="form-select teacher-input" required>' + courseOptions + '</select></div>' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_sessions_add_title_label') }}</label><input name="title_ar" class="form-control teacher-input" placeholder="{{ __('messages.teacher_sessions_add_title_placeholder') }}" required></div>' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_sessions_add_title_en') }}</label><input name="title_en" class="form-control teacher-input" dir="ltr" placeholder="{{ __('messages.teacher_sessions_add_title_en_placeholder') }}"></div>' +
    '<div class="row g-2 mb-3"><div class="col-6"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_sessions_add_date') }}</label><input name="date" class="form-control teacher-input" type="date" required></div><div class="col-6"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_sessions_add_time') }}</label><input name="time_from" class="form-control teacher-input" type="time" required></div></div>' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_sessions_add_time_to') }}</label><input name="time_to" class="form-control teacher-input" type="time"></div>' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_sessions_add_status') }}</label><select name="status" class="form-select teacher-input"><option value="upcoming">{{ __('messages.teacher_sessions_add_status_upcoming') }}</option><option value="in_progress">{{ __('messages.teacher_sessions_add_status_in_progress') }}</option><option value="completed">{{ __('messages.teacher_sessions_add_status_completed') }}</option><option value="cancelled">{{ __('messages.teacher_sessions_add_status_cancelled') }}</option></select></div>' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_sessions_add_url') }}</label><input name="stream_url" class="form-control teacher-input" dir="ltr" placeholder="{{ __('messages.teacher_session_placeholder_url') }}"></div>' +
    '</form>';
  Swal.fire({
    title: '{{ __('messages.teacher_sessions_add_title') }}', html: formHtml, showCancelButton: true, focusConfirm: false,
    confirmButtonColor: '#0F6D80', cancelButtonColor: '#6c757d',
    confirmButtonText: '<i class="fa-solid fa-check ml-1"></i>{{ __('messages.teacher_sessions_save') }}', cancelButtonText: '{{ __('messages.teacher_sessions_cancel') }}',
    preConfirm: function() {
      var form = document.getElementById('crudForm');
      var data = {};
      new FormData(form).forEach(function(v, k) { data[k] = v; });
      return fetch('{{ route("teacher.sessions.store") }}', {
        method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      }).then(function(r) {
        if (!r.ok) return r.json().then(function(e) { throw new Error(e.message || '{{ __('messages.teacher_sessions_save_error') }}'); });
        return r.json();
      }).then(function(result) {
        if (result.status === 'created') {
          Swal.fire({ icon: 'success', title: '{{ __('messages.teacher_sessions_ok') }}', text: '{{ __('messages.teacher_sessions_created') }}', confirmButtonColor: '#0F6D80', confirmButtonText: '{{ __('messages.teacher_sessions_ok') }}', timer: 1500 });
          setTimeout(function() { location.reload(); }, 1600);
        } else {
          throw new Error(result.message || '{{ __('messages.teacher_sessions_error') }}');
        }
      }).catch(function(e) {
        Swal.showValidationMessage(e.message);
      });
    },
    allowOutsideClick: false
  });
}

function showEditSessionModal(id, courseId, title, titleEn, date, timeFrom, timeTo, status, streamUrl) {
  var courses = getCoursesList();
  var courseOptions = '';
  for (var cid in courses) {
    courseOptions += '<option value="' + cid + '"' + (cid == courseId ? ' selected' : '') + '>' + courses[cid] + '</option>';
  }
  streamUrl = streamUrl || '';
  var statusOptions = '<option value="upcoming"' + (status === 'upcoming' ? ' selected' : '') + '>{{ __('messages.teacher_sessions_add_status_upcoming') }}</option>' +
    '<option value="in_progress"' + (status === 'in_progress' ? ' selected' : '') + '>{{ __('messages.teacher_sessions_add_status_in_progress') }}</option>' +
    '<option value="completed"' + (status === 'completed' ? ' selected' : '') + '>{{ __('messages.teacher_sessions_add_status_completed') }}</option>' +
    '<option value="cancelled"' + (status === 'cancelled' ? ' selected' : '') + '>{{ __('messages.teacher_sessions_add_status_cancelled') }}</option>';
  var formHtml = '<form id="crudForm" class="text-end">' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_sessions_add_course_label') }}</label><select name="course_id" class="form-select teacher-input" required>' + courseOptions + '</select></div>' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_sessions_add_title_label') }}</label><input name="title_ar" class="form-control teacher-input" value="' + title + '" required></div>' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_sessions_add_title_en') }}</label><input name="title_en" class="form-control teacher-input" dir="ltr" value="' + titleEn + '" placeholder="{{ __('messages.teacher_sessions_add_title_en_placeholder') }}"></div>' +
    '<div class="row g-2 mb-3"><div class="col-6"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_sessions_add_date') }}</label><input name="date" class="form-control teacher-input" type="date" value="' + date + '" required></div><div class="col-6"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_sessions_add_time') }}</label><input name="time_from" class="form-control teacher-input" type="time" value="' + timeFrom + '" required></div></div>' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_sessions_add_time_to') }}</label><input name="time_to" class="form-control teacher-input" type="time" value="' + timeTo + '"></div>' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_sessions_add_status') }}</label><select name="status" class="form-select teacher-input">' + statusOptions + '</select></div>' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_sessions_add_url') }}</label><input name="stream_url" class="form-control teacher-input" dir="ltr" value="' + streamUrl + '" placeholder="{{ __('messages.teacher_session_placeholder_url') }}"></div>' +
    '</form>';
  Swal.fire({
    title: '{{ __('messages.teacher_sessions_edit_title') }}', html: formHtml, showCancelButton: true, focusConfirm: false,
    confirmButtonColor: '#0F6D80', cancelButtonColor: '#6c757d',
    confirmButtonText: '<i class="fa-solid fa-check ml-1"></i>{{ __('messages.teacher_sessions_save_changes') }}', cancelButtonText: '{{ __('messages.teacher_sessions_cancel') }}',
    preConfirm: function() {
      var form = document.getElementById('crudForm');
      var data = {};
      new FormData(form).forEach(function(v, k) { data[k] = v; });
      return fetch('{{ route("teacher.sessions.update", "PLACEHOLDER") }}'.replace('PLACEHOLDER', id), {
        method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      }).then(function(r) {
        if (!r.ok) return r.json().then(function(e) { throw new Error(e.message || '{{ __('messages.teacher_sessions_save_error') }}'); });
        return r.json();
      }).then(function(result) {
        if (result.status === 'saved') {
          Swal.fire({ icon: 'success', title: '{{ __('messages.teacher_sessions_ok') }}', text: '{{ __('messages.teacher_sessions_updated') }}', confirmButtonColor: '#0F6D80', confirmButtonText: '{{ __('messages.teacher_sessions_ok') }}', timer: 1500 });
          setTimeout(function() { location.reload(); }, 1600);
        } else {
          throw new Error(result.message || '{{ __('messages.teacher_sessions_error') }}');
        }
      }).catch(function(e) {
        Swal.showValidationMessage(e.message);
      });
    },
    allowOutsideClick: false
  });
}

function deleteSession(id) {
  Swal.fire({
    title: '{{ __('messages.teacher_sessions_delete_title') }}', text: '{{ __('messages.teacher_sessions_delete_text') }}', icon: 'warning', showCancelButton: true,
    confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d',
    confirmButtonText: '{{ __('messages.teacher_sessions_delete_confirm') }}', cancelButtonText: '{{ __('messages.teacher_sessions_cancel') }}'
  }).then(function(r) {
    if (!r.isConfirmed) return;
    fetch('{{ route("teacher.sessions.delete", "PLACEHOLDER") }}'.replace('PLACEHOLDER', id), {
      method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    }).then(function(res) {
      if (!res.ok) return res.json().then(function(e) { throw new Error(e.message || '{{ __('messages.teacher_sessions_error') }}'); });
      return res.json();
    }).then(function(result) {
      if (result.status === 'deleted') {
        Swal.fire({ icon: 'success', title: '{{ __('messages.teacher_sessions_delete_title') }}', text: '{{ __('messages.teacher_sessions_deleted') }}', confirmButtonColor: '#0F6D80', confirmButtonText: '{{ __('messages.teacher_sessions_ok') }}', timer: 1500 });
        var el = document.getElementById('session-' + id);
        if (el) el.remove();
      }
    }).catch(function(e) { Swal.fire({ icon: 'error', title: '{{ __('messages.teacher_sessions_error') }}', text: e.message, confirmButtonColor: '#0F6D80' }); });
  });
}

(function() {
  var params = new URLSearchParams(window.location.search);
  var cid = params.get('course_id');
  if (params.get('add') === '1' && cid) {
    showAddSessionModal(cid);
  }
})();
</script>
@endpush
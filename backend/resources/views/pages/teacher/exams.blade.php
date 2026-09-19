@extends('layouts.teacher')

@section('title', __('messages.teacher_exams') . ' | ' . __('messages.site_name'))
@section('meta_description', __('messages.teacher_exams_meta'))
@section('header_greeting', __('messages.teacher_header_greeting', ['name' => __('messages.teacher_female')]))
@section('page_title', __('messages.teacher_exams_title'))

@push('styles')
<style>
      :root { --te-teal: #0F6D80; --te-teal-dark: #0a4a56; --te-teal-mid: #157a8c; --te-gold: #d89b1d; --te-ink: #1f2937; --te-muted: #6b7a7e; }

      /* ===== Page header ===== */
      .td-page-title { font-size: 1.35rem; font-weight: 800; color: var(--te-ink); margin: 0; display: flex; align-items: center; gap: 12px; }
      .td-page-title i { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.05rem; background: linear-gradient(135deg, rgba(15,109,128,0.1), rgba(15,109,128,0.04)); color: var(--te-teal); }

      /* ===== Buttons ===== */
      .btn-teacher-primary { background: linear-gradient(135deg, var(--te-teal), var(--te-teal-mid)); color: #fff; border: none; border-radius: 9px; font-weight: 600; font-size: .78rem; padding: 7px 14px; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
      .btn-teacher-primary:hover { filter: brightness(1.08); color: #fff; }
      .btn-teacher-outline { border: 1.5px solid rgba(15,109,128,0.18); color: var(--te-teal); background: #fff; border-radius: 9px; font-weight: 600; font-size: .78rem; padding: 7px 14px; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
      .btn-teacher-outline:hover { background: rgba(15,109,128,0.05); border-color: var(--te-teal); }

      /* ===== Stat cards ===== */
      .td-stat { background: #fff; border-radius: 16px; padding: 18px 20px; display: flex; align-items: center; gap: 14px; box-shadow: 0 4px 20px rgba(15,109,128,0.05); border: 1px solid rgba(15,109,128,0.06); height: 100%; transition: transform 0.2s ease, box-shadow 0.2s ease; position: relative; overflow: hidden; }
      .td-stat::after { content: ''; position: absolute; top: 0; bottom: 0; right: 0; width: 3px; background: linear-gradient(180deg, var(--te-teal), var(--te-gold)); }
      .td-stat:hover { transform: translateY(-3px); box-shadow: 0 10px 28px rgba(15,109,128,0.12); }
      .td-stat-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; background: linear-gradient(135deg, rgba(15,109,128,0.1), rgba(15,109,128,0.04)); color: var(--te-teal); }
      .td-stat-value { font-size: 1.5rem; font-weight: 800; color: var(--te-teal); line-height: 1.1; }
      .td-stat-label { font-size: .82rem; color: var(--te-muted); font-weight: 500; margin: 2px 0 0; }

      /* ===== Badges ===== */
      .td-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 14px; border-radius: 20px; font-size: .76rem; font-weight: 700; white-space: nowrap; }
      .td-badge-strong { background: linear-gradient(135deg, var(--te-teal), var(--te-teal-mid)); color: #fff; }
      .td-badge-mid { background: rgba(15, 109, 128, 0.12); color: var(--te-teal); }
      .td-badge-soft { background: rgba(216, 155, 29, 0.14); color: #b57f0e; }

      /* ===== Filter tabs ===== */
      .exam-tab { display: inline-flex; align-items: center; gap: 6px; cursor: pointer; padding: 8px 20px; border-radius: 20px; font-weight: 600; font-size: .82rem; border: 1.5px solid rgba(15,109,128,0.12); color: #5a666a; transition: all 0.2s; background: #fff; }
      .exam-tab i { color: var(--te-teal); }
      .exam-tab.active { background: linear-gradient(135deg, var(--te-teal), var(--te-teal-mid)); color: #fff; border-color: var(--te-teal); box-shadow: 0 4px 14px rgba(15,109,128,0.28); }
      .exam-tab.active i { color: var(--te-gold); }
      .exam-tab:hover:not(.active) { border-color: var(--te-teal); color: var(--te-teal); }

      /* ===== Exam cards ===== */
      .exam-card { background: linear-gradient(135deg, #fff 0%, rgba(15,109,128,0.02) 100%); border-radius: 16px; padding: 20px; border: 1px solid rgba(15,109,128,0.06); box-shadow: 0 4px 20px rgba(15,109,128,0.03); transition: transform .25s, box-shadow .25s; }
      .exam-card:hover { transform: translateY(-4px); box-shadow: 0 10px 26px rgba(15,109,128,0.13); }
      .exam-icon { width: 44px; height: 44px; min-width: 44px; border-radius: 12px; background: linear-gradient(135deg, rgba(15,109,128,0.1), rgba(15,109,128,0.04)); color: var(--te-teal); display: flex; align-items: center; justify-content: center; font-size: 1.15rem; }
      .exam-meta { display: inline-flex; align-items: center; gap: 6px; font-size: 0.78rem; color: var(--te-muted); }
      .exam-meta i { color: var(--te-teal); font-size: 0.78rem; }
      .exam-card-title { font-weight: 800; color: var(--te-ink); margin-bottom: 4px; }
      .exam-score-box { text-align: center; background: rgba(15,109,128,0.05); border: 1px solid rgba(15,109,128,0.08); border-radius: 12px; padding: 8px 14px; min-width: 74px; }
      .exam-score-box .score-num { font-size: 1.35rem; font-weight: 800; color: var(--te-teal); line-height: 1.1; }
      .exam-score-box .score-label { font-size: 0.68rem; color: var(--te-muted); }

      /* ===== Row actions ===== */
      .td-icon-btn { width: 32px; height: 32px; border-radius: 9px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid rgba(15,109,128,0.14); color: var(--te-teal); transition: all 0.15s; font-size: .78rem; background: #fff; flex-shrink: 0; }
      .td-icon-btn:hover { background: var(--te-teal); color: #fff; }
      .td-icon-btn.danger { color: #dc3545; border-color: rgba(220,53,69,0.2); }
      .td-icon-btn.danger:hover { background: #dc3545; color: #fff; }

      .td-empty-icon { font-size: 2.8rem; color: rgba(15,109,128,0.15); }
  </style>
@endpush

@section('content')
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <h1 class="td-page-title"><i class="fa-solid fa-pen-to-square"></i>{{ __('messages.teacher_exams_title') }}</h1>
    <div class="d-flex gap-2 flex-wrap">
      <a href="{{ route('teacher.exam-results') }}" class="btn-teacher-outline text-decoration-none d-inline-flex align-items-center"><i class="fa-solid fa-chart-simple me-1"></i>{{ __('messages.teacher_exams_results') }}</a>
      <button class="btn-teacher-primary" onclick="showAddExamModal()"><i class="fa-solid fa-plus me-1"></i>{{ __('messages.teacher_exams_add') }}</button>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-pen-square"></i></div>
        <div><p class="td-stat-value mb-0">{{ $stats['total'] }}</p><p class="td-stat-label mb-0">{{ __('messages.teacher_exams_total') }}</p></div>
      </div>
    </div>
    <div class="col-6 col-md-4">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-calendar"></i></div>
        <div><p class="td-stat-value mb-0">{{ $stats['current'] }}</p><p class="td-stat-label mb-0">{{ __('messages.teacher_exams_status_current') }}</p></div>
      </div>
    </div>
    <div class="col-6 col-md-4">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-circle-check"></i></div>
        <div><p class="td-stat-value mb-0">{{ $stats['ended'] }}</p><p class="td-stat-label mb-0">{{ __('messages.teacher_exams_status_ended') }}</p></div>
      </div>
    </div>
  </div>

  <div class="d-flex flex-wrap gap-2 mb-4">
    <button class="exam-tab active" onclick="filterExams(this,'all')"><i class="fa-solid fa-layer-group"></i>{{ __('messages.teacher_exams_filter_all') }}</button>
    <button class="exam-tab" onclick="filterExams(this,'current')"><i class="fa-solid fa-circle-play"></i>{{ __('messages.teacher_exams_filter_current') }}</button>
    <button class="exam-tab" onclick="filterExams(this,'ended')"><i class="fa-solid fa-circle-check"></i>{{ __('messages.teacher_exams_filter_ended') }}</button>
  </div>

  <div class="row g-3" id="examCards">
    @forelse($exams as $e)
    @php
      $eStatus = in_array($e->status, ['current', 'ended'], true) ? $e->status : (($e->date && \Carbon\Carbon::parse($e->date)->lt(today())) ? 'ended' : 'current');
      $badgeClass = ['current'=>'td-badge-mid','ended'=>'td-badge-strong'];
      $statusLabel = ['current'=>__('messages.teacher_exams_status_current'),'ended'=>__('messages.teacher_exams_status_ended')];
    @endphp
    <div class="col-md-6 col-lg-4 exam-col" data-status="{{ $eStatus }}" id="exam-{{ $e->id }}">
      <div class="exam-card h-100 d-flex flex-column">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <span class="exam-icon"><i class="fa-solid fa-pen-square"></i></span>
          <span class="td-badge {{ $badgeClass[$eStatus] ?? 'td-badge-mid' }}">{{ $statusLabel[$eStatus] ?? '' }}</span>
        </div>
        <h6 class="exam-card-title mb-1">{{ $e->title }}</h6>
        <span class="exam-meta"><i class="fa-solid fa-book-open"></i>{{ $e->course->name ?? '—' }}</span>
        <div class="d-flex align-items-center justify-content-between gap-2 mt-3 pt-3" style="border-top:1px dashed rgba(15,109,128,0.12);">
          <div class="d-flex flex-column gap-1">
            <span class="exam-meta"><i class="fa-regular fa-calendar"></i>{{ $e->date ? \Carbon\Carbon::parse($e->date)->format('Y-m-d') : '—' }}</span>
            <span class="exam-meta"><i class="fa-regular fa-user"></i>{{ $e->total_students ?? 0 }} {{ __('messages.teacher_exams_student') }}</span>
          </div>
          <div class="exam-score-box">
            <div class="score-num">{{ $e->avg_score ?? '—' }}</div>
            <div class="score-label">{{ __('messages.teacher_exams_avg_label') }}</div>
          </div>
        </div>
        <div class="d-flex gap-2 mt-3 exam-actions">
          <a href="{{ route('teacher.exam-details', $e->id) }}" class="btn-teacher-primary text-decoration-none" style="flex:1;"><i class="fa-solid fa-eye me-1"></i>{{ __('messages.teacher_exams_details') }}</a>
          <button class="td-icon-btn" onclick="showEditExamModal({{ $e->id }}, {{ $e->course_id }}, '{{ addslashes($e->title ?? '') }}', '{{ $e->date ?? '' }}', '{{ $eStatus }}')" title="{{ __('messages.teacher_courses_edit') }}"><i class="fa-solid fa-pen"></i></button>
          <button class="td-icon-btn" id="exam-toggle-btn-{{ $e->id }}" onclick="toggleExamStatus({{ $e->id }}, '{{ $eStatus }}')" title="{{ __('messages.teacher_exams_toggle_title') }}"><i class="fa-solid fa-right-left"></i></button>
          <button class="td-icon-btn danger" onclick="deleteExam({{ $e->id }})" title="{{ __('messages.teacher_courses_delete') }}"><i class="fa-solid fa-trash-can"></i></button>
        </div>
      </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
      <i class="fa-solid fa-pen-to-square td-empty-icon mb-3 d-block"></i>
      <p class="text-secondary">{{ __('messages.teacher_exams_empty') }}</p>
    </div>
    @endforelse
  </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function filterExams(el, status) {
  document.querySelectorAll('.exam-tab').forEach(function(t) { t.classList.remove('active'); });
  el.classList.add('active');
  document.querySelectorAll('.exam-col').forEach(function(c) {
    c.style.display = (status === 'all' || c.dataset.status === status) ? '' : 'none';
  });
}

function getCoursesList() {
  @php
    $coursesList = auth()->user()->role === 'admin'
      ? \App\Models\Course::orderBy('name_ar')->pluck('name_ar', 'id')
      : \App\Models\Course::where('user_id', auth()->id())->orderBy('name_ar')->pluck('name_ar', 'id');
  @endphp
  return @json($coursesList);
}

function showAddExamModal(preselectCourseId) {
  var courses = getCoursesList();
  var courseOptions = '';
  for (var id in courses) { courseOptions += '<option value="' + id + '"' + (preselectCourseId && id == preselectCourseId ? ' selected' : '') + '>' + courses[id] + '</option>'; }
  var formHtml = '<form id="crudForm" class="text-end">' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_exams_add_name') }}</label><input name="title_ar" class="form-control teacher-input" placeholder="{{ __('messages.teacher_exams_add_name_placeholder') }}" required></div>' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_exams_add_course') }}</label><select name="course_id" class="form-select teacher-input" required>' + courseOptions + '</select></div>' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_exams_add_date') }}</label><input name="date" class="form-control teacher-input" type="date" required></div>' +
    '</form>';
  Swal.fire({
    title: '{{ __('messages.teacher_exams_add_title') }}', html: formHtml, showCancelButton: true, focusConfirm: false,
    confirmButtonColor: '#0F6D80', cancelButtonColor: '#6c757d',
    confirmButtonText: '<i class="fa-solid fa-check ml-1"></i>{{ __('messages.teacher_exams_save') }}', cancelButtonText: '{{ __('messages.teacher_exams_cancel') }}',
    preConfirm: function() {
      var form = document.getElementById('crudForm');
      var data = {};
      new FormData(form).forEach(function(v, k) { data[k] = v; });
      return fetch('{{ route("teacher.exams.store") }}', {
        method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      }).then(function(r) {
        if (!r.ok) return r.json().then(function(e) { throw new Error(e.message || '{{ __('messages.teacher_exams_save_error') }}'); });
        return r.json();
      }).then(function(result) {
        if (result.status === 'created') {
          Swal.fire({ icon: 'success', title: '{{ __('messages.teacher_exams_ok') }}', text: '{{ __('messages.teacher_exams_created') }}', confirmButtonColor: '#0F6D80', confirmButtonText: '{{ __('messages.teacher_exams_ok') }}', timer: 1500 });
          setTimeout(function() { location.reload(); }, 1600);
        } else {
          throw new Error(result.message || '{{ __('messages.teacher_exams_error') }}');
        }
      }).catch(function(e) {
        Swal.showValidationMessage(e.message);
      });
    },
    allowOutsideClick: false
  });
}

function showEditExamModal(id, courseId, title, date, status) {
  var courses = getCoursesList();
  var courseOptions = '';
  for (var cid in courses) {
    courseOptions += '<option value="' + cid + '"' + (cid == courseId ? ' selected' : '') + '>' + courses[cid] + '</option>';
  }  var formHtml = '<form id="crudForm" class="text-end">' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_exams_add_name') }}</label><input name="title_ar" class="form-control teacher-input" value="' + title + '" required></div>' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_exams_add_course') }}</label><select name="course_id" class="form-select teacher-input" required>' + courseOptions + '</select></div>' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_exams_add_date') }}</label><input name="date" class="form-control teacher-input" type="date" value="' + date + '" required></div>' +
    '</form>';
  Swal.fire({
    title: '{{ __('messages.teacher_exams_edit_title') }}', html: formHtml, showCancelButton: true, focusConfirm: false,
    confirmButtonColor: '#0F6D80', cancelButtonColor: '#6c757d',
    confirmButtonText: '<i class="fa-solid fa-check ml-1"></i>{{ __('messages.teacher_exams_save_changes') }}', cancelButtonText: '{{ __('messages.teacher_exams_cancel') }}',
    preConfirm: function() {
      var form = document.getElementById('crudForm');
      var data = {};
      new FormData(form).forEach(function(v, k) { data[k] = v; });
      return fetch('{{ route("teacher.exams.update", "PLACEHOLDER") }}'.replace('PLACEHOLDER', id), {
        method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      }).then(function(r) {
        if (!r.ok) return r.json().then(function(e) { throw new Error(e.message || '{{ __('messages.teacher_exams_save_error') }}'); });
        return r.json();
      }).then(function(result) {
        if (result.status === 'saved') {
          Swal.fire({ icon: 'success', title: '{{ __('messages.teacher_exams_ok') }}', text: '{{ __('messages.teacher_exams_updated') }}', confirmButtonColor: '#0F6D80', confirmButtonText: '{{ __('messages.teacher_exams_ok') }}', timer: 1500 });
          setTimeout(function() { location.reload(); }, 1600);
        } else {
          throw new Error(result.message || '{{ __('messages.teacher_exams_error') }}');
        }
      }).catch(function(e) {
        Swal.showValidationMessage(e.message);
      });
    },
    allowOutsideClick: false
  });
}

function toggleExamStatus(id, currentStatus) {
  var newStatus = currentStatus === 'current' ? 'ended' : 'current';
  var newText = newStatus === 'current' ? '{{ __('messages.teacher_exams_status_current') }}' : '{{ __('messages.teacher_exams_status_ended') }}';
  Swal.fire({
    title: '{{ __('messages.teacher_exams_toggle_title') }}',
    text: '{{ __('messages.teacher_exams_toggle_text') }} "' + newText + '"',
    icon: 'question', showCancelButton: true,
    confirmButtonColor: '#0F6D80', cancelButtonColor: '#6c757d',
    confirmButtonText: '{{ __('messages.teacher_exams_save') }}', cancelButtonText: '{{ __('messages.teacher_exams_cancel') }}'
  }).then(function(r) {
    if (!r.isConfirmed) return;
    fetch('{{ route("teacher.exams.toggle-status", "PLACEHOLDER") }}'.replace('PLACEHOLDER', id), {
      method: 'PATCH', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    }).then(function(res) {
      if (!res.ok) return res.json().then(function(e) { throw new Error(e.message || '{{ __('messages.teacher_exams_error') }}'); });
      return res.json();
    }).then(function(result) {
      if (result.status === 'toggled') {
        Swal.fire({ icon: 'success', title: '{{ __('messages.teacher_exams_ok') }}', text: newText, confirmButtonColor: '#0F6D80', confirmButtonText: '{{ __('messages.teacher_exams_ok') }}', timer: 1200 });
        setTimeout(function() { location.reload(); }, 1300);
      }
    }).catch(function(e) { Swal.fire({ icon: 'error', title: '{{ __('messages.teacher_exams_error') }}', text: e.message, confirmButtonColor: '#0F6D80' }); });
  });
}

function deleteExam(id) {
  Swal.fire({
    title: '{{ __('messages.teacher_exams_delete_title') }}', text: '{{ __('messages.teacher_exams_delete_text') }}', icon: 'warning', showCancelButton: true,
    confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d',
    confirmButtonText: '{{ __('messages.teacher_exams_delete_confirm') }}', cancelButtonText: '{{ __('messages.teacher_exams_cancel') }}'
  }).then(function(r) {
    if (!r.isConfirmed) return;
    fetch('{{ route("teacher.exams.delete", "PLACEHOLDER") }}'.replace('PLACEHOLDER', id), {
      method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    }).then(function(res) {
      if (!res.ok) return res.json().then(function(e) { throw new Error(e.message || '{{ __('messages.teacher_exams_error') }}'); });
      return res.json();
    }).then(function(result) {
      if (result.status === 'deleted') {
        Swal.fire({ icon: 'success', title: '{{ __('messages.teacher_exams_delete_title') }}', text: '{{ __('messages.teacher_exams_deleted') }}', confirmButtonColor: '#0F6D80', confirmButtonText: '{{ __('messages.teacher_exams_ok') }}', timer: 1500 });
        var el = document.getElementById('exam-' + id);
        if (el) el.remove();
      }
    }).catch(function(e) { Swal.fire({ icon: 'error', title: '{{ __('messages.teacher_exams_error') }}', text: e.message, confirmButtonColor: '#0F6D80' }); });
  });
}

(function() {
  var params = new URLSearchParams(window.location.search);
  var courseId = params.get('course_id');
  if (params.get('add') === '1' || courseId) {
    showAddExamModal(courseId);
  }
})();
</script>
@endpush
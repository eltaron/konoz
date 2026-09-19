@extends('layouts.teacher')

@section('title', __('messages.teacher_courses') . ' | ' . __('messages.site_name'))
@section('page_title', __('messages.teacher_courses_title'))

@push('styles')
<style>
      :root { --tc-teal: #0F6D80; --tc-teal-dark: #0a4a56; --tc-gold: #d89b1d; }

      .td-page-title { font-size: 1.35rem; font-weight: 800; color: #1f2937; margin: 0; display: flex; align-items: center; gap: 12px; }
      .td-page-title i { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.05rem; background: linear-gradient(135deg, rgba(15,109,128,0.1), rgba(15,109,128,0.04)); color: #0F6D80; }

      /* ===== Stat cards (unified identity) ===== */
      .td-stat { background: #fff; border-radius: 16px; padding: 18px 20px; display: flex; align-items: center; gap: 14px; box-shadow: 0 4px 20px rgba(15,109,128,0.05); border: 1px solid rgba(15,109,128,0.06); height: 100%; transition: transform 0.2s ease, box-shadow 0.2s ease; position: relative; overflow: hidden; }
      .td-stat::after { content: ''; position: absolute; top: 0; bottom: 0; right: 0; width: 3px; background: linear-gradient(180deg, #0F6D80, #d89b1d); }
      .td-stat:hover { transform: translateY(-3px); box-shadow: 0 10px 28px rgba(15,109,128,0.12); }
      .td-stat-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; background: linear-gradient(135deg, rgba(15,109,128,0.1), rgba(15,109,128,0.04)); color: #0F6D80; }
      .td-stat-value { font-size: 1.5rem; font-weight: 800; color: #0F6D80; line-height: 1.1; }
      .td-stat-label { font-size: .82rem; color: #6b7a7e; font-weight: 500; margin: 2px 0 0; }

      /* ===== Badges ===== */
      .td-badge { display: inline-flex; align-items: center; padding: 5px 14px; border-radius: 20px; font-size: .78rem; font-weight: 700; }
      .td-badge-strong { background: linear-gradient(135deg, #0F6D80, #157a8c); color: #fff; }
      .td-badge-mid { background: rgba(15, 109, 128, 0.12); color: #0F6D80; }
      .td-badge-soft { background: rgba(216, 155, 29, 0.14); color: #b57f0e; }

      /* ===== Course cards ===== */
      .course-card { background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(15,109,128,0.05); border: 1px solid rgba(15,109,128,0.06); transition: all 0.3s; height: 100%; display: flex; flex-direction: column; }
      .course-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(15,109,128,0.1); }
      .course-cover { position: relative; height: 110px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #0a4a56, #0F6D80 55%, #157a8c); }
      .course-cover::after { content: ''; position: absolute; inset: 0; background-image: url("data:image/svg+xml,%3Csvg width='72' height='72' viewBox='0 0 72 72' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M72 0L0 72M36 0L0 36M72 36L36 72' stroke='%23ffffff' stroke-opacity='0.06' fill='none'/%3E%3C/svg%3E"); pointer-events: none; }
      .course-cover > i { font-size: 2.2rem; color: #fff; opacity: 0.95; }
      .course-status { position: absolute; top: 12px; inset-inline-start: 12px; }
      .course-level { position: absolute; bottom: 10px; inset-inline-end: 12px; background: rgba(255,255,255,0.92); }
      .course-card-body { padding: 14px 16px 16px; flex: 1; }
      .course-card-title { font-size: 1.02rem; font-weight: 800; color: #1f2937; margin-bottom: 8px; }
      .course-meta { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 10px; }
      .course-chip { display: inline-flex; align-items: center; gap: 6px; font-size: .76rem; font-weight: 600; color: #3f484b; background: rgba(15,109,128,0.05); border: 1px solid rgba(15,109,128,0.06); padding: 4px 10px; border-radius: 8px; }
      .course-chip i { color: #0F6D80; font-size: .72rem; }
      .course-price { display: flex; align-items: center; gap: 6px; font-size: .85rem; font-weight: 800; color: #b57f0e; margin-bottom: 12px; }
      .course-price i { color: #d89b1d; }
      .course-divider { border-top: 1px dashed rgba(15,109,128,0.12); margin: 0 0 12px; }
      .course-actions { display: flex; gap: 6px; flex-wrap: wrap; }
      .btn-teacher-primary { background: linear-gradient(135deg, #0F6D80, #157a8c); color: #fff; border: none; border-radius: 9px; font-weight: 600; font-size: .74rem; padding: 6px 12px; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
      .btn-teacher-primary:hover { filter: brightness(1.08); color: #fff; }
      .btn-teacher-outline { border: 1.5px solid rgba(15,109,128,0.18); color: #0F6D80; background: #fff; border-radius: 9px; font-weight: 600; font-size: .74rem; padding: 6px 12px; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; }
      .btn-teacher-outline:hover { background: rgba(15,109,128,0.05); border-color: #0F6D80; }
      .btn-teacher-danger { border: 1.5px solid rgba(220,53,69,0.18); color: #dc3545; background: #fff; border-radius: 9px; font-weight: 600; font-size: .74rem; padding: 6px 12px; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; }
      .btn-teacher-danger:hover { background: rgba(220,53,69,0.06); border-color: #dc3545; }
      .course-empty { font-size: 3rem; color: rgba(15,109,128,0.15); }
      @media (max-width: 575.98px) { .course-cover { height: 88px; } }
  </style>
@endpush

@section('content')
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <h1 class="td-page-title"><i class="fa-solid fa-book-open"></i>{{ __('messages.teacher_courses_title') }}</h1>
    <button class="btn btn-teacher-primary" onclick="showAddCourseModal()"><i class="fa-solid fa-plus me-1"></i>{{ __('messages.teacher_courses_add') }}</button>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-layer-group"></i></div>
        <div><p class="td-stat-value mb-0">{{ $totalCourses }}</p><p class="td-stat-label mb-0">{{ __('messages.teacher_courses_total') }}</p></div>
      </div>
    </div>
    <div class="col-6 col-md-4">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-play"></i></div>
        <div><p class="td-stat-value mb-0">{{ $activeCourses }}</p><p class="td-stat-label mb-0">{{ __('messages.teacher_courses_active') }}</p></div>
      </div>
    </div>
    <div class="col-6 col-md-4">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-check-double"></i></div>
        <div><p class="td-stat-value mb-0">{{ $completedCourses }}</p><p class="td-stat-label mb-0">{{ __('messages.teacher_courses_completed') }}</p></div>
      </div>
    </div>
  </div>

  <div class="row g-3" id="coursesGrid">
    @forelse($courses as $course)
    @php($courseJson = $course->only(['name_ar', 'name_en', 'level', 'price', 'duration', 'audience', 'instructor_ar', 'desc_ar', 'is_active', 'sessions_per_week']))
    <div class="col-12 col-sm-6 col-lg-4" id="course-{{ $course->id }}">
      <div class="course-card">
        <div class="course-cover">
          <i class="fa-solid fa-graduation-cap"></i>
          <span class="td-badge course-status {{ $course->is_active ? 'td-badge-strong' : 'td-badge-soft' }}">{{ $course->is_active ? __('messages.teacher_courses_active_badge') : __('messages.teacher_courses_completed_badge') }}</span>
          @if($course->level)<span class="td-badge td-badge-mid course-level"><i class="fa-solid fa-signal me-1"></i>{{ $course->level }}</span>@endif
        </div>
        <div class="course-card-body">
          <h5 class="course-card-title">{{ $course->name }}</h5>
          <div class="course-meta">
            <span class="course-chip"><i class="fa-solid fa-user-graduate"></i>{{ $course->students_count ?? 0 }} {{ __('messages.teacher_courses_students') }}</span>
            <span class="course-chip"><i class="fa-solid fa-calendar-days"></i>{{ $course->sessions_per_week ?? '—' }} {{ __('messages.teacher_courses_sessions_week') }}</span>
            @if($course->price !== null)<span class="course-chip"><i class="fa-solid fa-sack-dollar"></i>$ {{ number_format($course->price, 2) }}</span>@endif
            @if($course->duration)<span class="course-chip"><i class="fa-regular fa-clock"></i>{{ $course->duration }}</span>@endif
          </div>
          <hr class="course-divider" />
          <div class="course-actions">
            <a href="{{ route('teacher.course-details', $course->id) }}" class="btn-teacher-primary"><i class="fa-solid fa-circle-info me-1"></i>{{ __('messages.teacher_courses_details') }}</a>
            <button class="btn-teacher-outline" onclick="showEditCourseModal(this)" data-id="{{ $course->id }}" data-course='@json($courseJson)'><i class="fa-solid fa-pen me-1"></i>{{ __('messages.teacher_courses_edit') }}</button>
            <button class="btn-teacher-danger" onclick="deleteCourse({{ $course->id }})"><i class="fa-solid fa-trash-can me-1"></i>{{ __('messages.teacher_courses_delete') }}</button>
          </div>
        </div>
      </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
      <i class="fa-solid fa-book-open course-empty mb-3 d-block"></i>
      <p class="text-secondary">{{ __('messages.teacher_courses_empty') }}</p>
    </div>
    @endforelse
  </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function teacherEsc(s) {
  return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

function courseFormHtml(v) {
  v = v || {};
  function val(k) { return v[k] != null ? teacherEsc(v[k]) : ''; }
  var bgn = '{{ __('messages.teacher_students_beginner') }}';
  var itm = '{{ __('messages.teacher_students_intermediate') }}';
  var adv = '{{ __('messages.teacher_students_advanced') }}';
  var level = v.level || '';
  var levels = '<option value="">' + '{{ __('messages.teacher_courses_add_level_choose') }}' + '</option>' +
    '<option value="' + bgn + '"' + (level === bgn ? ' selected' : '') + '>' + bgn + '</option>' +
    '<option value="' + itm + '"' + (level === itm ? ' selected' : '') + '>' + itm + '</option>' +
    '<option value="' + adv + '"' + (level === adv ? ' selected' : '') + '>' + adv + '</option>';
  var checked = v.is_active === false ? '' : ' checked';
  return '<form id="crudForm" class="text-end">' +
    '<div class="row g-3">' +
      '<div class="col-md-6"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_courses_add_name') }}</label><input name="name_ar" class="form-control teacher-input" value="' + val('name_ar') + '" placeholder="{{ __('messages.teacher_courses_add_name_placeholder') }}" required></div>' +
      '<div class="col-md-6"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_courses_add_name_en') }}</label><input name="name_en" class="form-control teacher-input" value="' + val('name_en') + '" placeholder="{{ __('messages.teacher_courses_add_name_en_placeholder') }}"></div>' +
      '<div class="col-md-6"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_courses_add_level') }}</label><select name="level" class="form-select teacher-input">' + levels + '</select></div>' +
      '<div class="col-md-6"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_courses_add_price') }}</label><input name="price" type="number" step="0.01" min="0" class="form-control teacher-input" value="' + val('price') + '" placeholder="{{ __('messages.teacher_courses_add_price_placeholder') }}"></div>' +
      '<div class="col-md-6"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_courses_add_sessions') }}</label><input name="sessions_per_week" type="number" class="form-control teacher-input" value="' + val('sessions_per_week') + '" placeholder="{{ __('messages.teacher_courses_add_sessions_placeholder') }}" min="1" max="7"></div>' +
      '<div class="col-md-6"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_courses_add_duration') }}</label><input name="duration" class="form-control teacher-input" value="' + val('duration') + '" placeholder="{{ __('messages.teacher_courses_add_duration_placeholder') }}"></div>' +
      '<div class="col-md-6"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_courses_add_audience') }}</label><input name="audience" class="form-control teacher-input" value="' + val('audience') + '" placeholder="{{ __('messages.teacher_courses_add_audience_placeholder') }}"></div>' +
      '<div class="col-md-6"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_courses_add_instructor') }}</label><input name="instructor_ar" class="form-control teacher-input" value="' + val('instructor_ar') + '" placeholder="{{ __('messages.teacher_courses_add_instructor_placeholder') }}"></div>' +
      '<div class="col-12"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_courses_add_desc') }}</label><textarea name="desc_ar" class="form-control teacher-input" rows="3" placeholder="{{ __('messages.teacher_courses_add_desc_placeholder') }}">' + val('desc_ar') + '</textarea></div>' +
    '</div>' +
    '<div class="form-check mt-3"><input name="is_active" class="form-check-input" type="checkbox" value="1" id="activeCheck"' + checked + '><label class="form-check-label" for="activeCheck">{{ __('messages.teacher_courses_add_active') }}</label></div>' +
    '</form>';
}

function submitCourseForm(url) {
  var form = document.getElementById('crudForm');
  var data = {};
  new FormData(form).forEach(function(v, k) { data[k] = v; });
  return fetch(url, {
    method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  }).then(function(r) {
    if (!r.ok) return r.json().then(function(e) { throw new Error(e.message || '{{ __('messages.teacher_courses_save_error') }}'); });
    return r.json();
  });
}

function showAddCourseModal() {
  var formHtml = courseFormHtml(null);
  Swal.fire({
    title: '{{ __('messages.teacher_courses_add_title') }}', html: formHtml, width: '720px', showCancelButton: true, focusConfirm: false,
    confirmButtonColor: '#0F6D80', cancelButtonColor: '#6c757d',
    confirmButtonText: '<i class="fa-solid fa-check ml-1"></i>{{ __('messages.teacher_courses_save') }}', cancelButtonText: '{{ __('messages.teacher_courses_cancel') }}',
    preConfirm: function() {
      return submitCourseForm('{{ route("teacher.courses.store") }}').then(function(result) {
        if (result.status === 'created') {
          Swal.fire({ icon: 'success', title: '{{ __('messages.teacher_sessions_ok') }}', text: '{{ __('messages.teacher_courses_created') }}', confirmButtonColor: '#0F6D80', confirmButtonText: '{{ __('messages.teacher_sessions_ok') }}', timer: 1500 });
          setTimeout(function() { location.reload(); }, 1600);
        } else {
          throw new Error(result.message || '{{ __('messages.teacher_courses_error') }}');
        }
      }).catch(function(e) {
        Swal.showValidationMessage(e.message);
      });
    },
    allowOutsideClick: false
  });
}

function showEditCourseModal(btn) {
  var id = btn.getAttribute('data-id');
  var c = {};
  try { c = JSON.parse(btn.getAttribute('data-course')); } catch (e) { c = {}; }
  var formHtml = courseFormHtml(c);
  Swal.fire({
    title: '{{ __('messages.teacher_courses_edit_title') }}', html: formHtml, width: '720px', showCancelButton: true, focusConfirm: false,
    confirmButtonColor: '#0F6D80', cancelButtonColor: '#6c757d',
    confirmButtonText: '<i class="fa-solid fa-check ml-1"></i>{{ __('messages.teacher_courses_save_changes') }}', cancelButtonText: '{{ __('messages.teacher_courses_cancel') }}',
    preConfirm: function() {
      return submitCourseForm('{{ route("teacher.courses.update", "PLACEHOLDER") }}'.replace('PLACEHOLDER', id)).then(function(result) {
        if (result.status === 'saved') {
          Swal.fire({ icon: 'success', title: '{{ __('messages.teacher_sessions_ok') }}', text: '{{ __('messages.teacher_courses_updated') }}', confirmButtonColor: '#0F6D80', confirmButtonText: '{{ __('messages.teacher_sessions_ok') }}', timer: 1500 });
          setTimeout(function() { location.reload(); }, 1600);
        } else {
          throw new Error(result.message || '{{ __('messages.teacher_courses_error') }}');
        }
      }).catch(function(e) {
        Swal.showValidationMessage(e.message);
      });
    },
    allowOutsideClick: false
  });
}

function deleteCourse(id) {
  Swal.fire({
    title: '{{ __('messages.teacher_courses_delete_title') }}', text: '{{ __('messages.teacher_courses_delete_text') }}', icon: 'warning', showCancelButton: true,
    confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d',
    confirmButtonText: '{{ __('messages.teacher_courses_delete_confirm') }}', cancelButtonText: '{{ __('messages.teacher_courses_cancel') }}'
  }).then(function(r) {
    if (!r.isConfirmed) return;
    fetch('{{ route("teacher.courses.delete", "PLACEHOLDER") }}'.replace('PLACEHOLDER', id), {
      method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    }).then(function(res) {
      if (!res.ok) return res.json().then(function(e) { throw new Error(e.message || '{{ __('messages.teacher_courses_error') }}'); });
      return res.json();
    }).then(function(result) {
      if (result.status === 'deleted') {
        Swal.fire({ icon: 'success', title: '{{ __('messages.teacher_courses_delete_title') }}', text: '{{ __('messages.teacher_courses_deleted') }}', confirmButtonColor: '#0F6D80', confirmButtonText: '{{ __('messages.teacher_sessions_ok') }}', timer: 1500 });
        var el = document.getElementById('course-' + id);
        if (el) el.remove();
      }
    }).catch(function(e) { Swal.fire({ icon: 'error', title: '{{ __('messages.teacher_courses_error') }}', text: e.message, confirmButtonColor: '#0F6D80' }); });
  });
}
</script>
@endpush
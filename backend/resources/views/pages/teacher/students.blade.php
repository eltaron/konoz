@extends('layouts.teacher')

@section('title', __('messages.teacher_students') . ' | ' . __('messages.site_name'))
@section('meta_description', __('messages.teacher_students_meta'))
@section('header_greeting', __('messages.teacher_header_greeting', ['name' => __('messages.teacher_female')]))
@section('page_title', __('messages.teacher_students_title'))

@push('styles')
<style>
      :root { --ts-teal: #0F6D80; --ts-teal-dark: #0a4a56; --ts-teal-mid: #157a8c; --ts-gold: #d89b1d; --ts-gold-light: #f0b429; --ts-ink: #1f2937; --ts-muted: #6b7a7e; }

      /* ===== Page header ===== */
      .td-page-title { font-size: 1.35rem; font-weight: 800; color: var(--ts-ink); margin: 0; display: flex; align-items: center; gap: 12px; }
      .td-page-title i { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.05rem; background: linear-gradient(135deg, rgba(15,109,128,0.1), rgba(15,109,128,0.04)); color: var(--ts-teal); }

      /* ===== Buttons ===== */
      .btn-teacher-primary { background: linear-gradient(135deg, var(--ts-teal), var(--ts-teal-mid)); color: #fff; border: none; border-radius: 9px; font-weight: 600; font-size: .78rem; padding: 7px 14px; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
      .btn-teacher-primary:hover { filter: brightness(1.08); color: #fff; }
      .btn-teacher-outline { border: 1.5px solid rgba(15,109,128,0.18); color: var(--ts-teal); background: #fff; border-radius: 9px; font-weight: 600; font-size: .78rem; padding: 7px 14px; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; }
      .btn-teacher-outline:hover { background: rgba(15,109,128,0.05); border-color: var(--ts-teal); }

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

      /* ===== Avatars (teal/gold family) ===== */
      .student-avatar { width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 0.9rem; flex-shrink: 0; }
      .student-avatar.av-0 { background: linear-gradient(135deg, #0F6D80, #157a8c); }
      .student-avatar.av-1 { background: linear-gradient(135deg, #0a4a56, #0F6D80); }
      .student-avatar.av-2 { background: linear-gradient(135deg, #b57f0e, #d89b1d); }
      .student-avatar.av-3 { background: linear-gradient(135deg, #0b7285, #22b8cf); }
      .student-avatar.av-4 { background: linear-gradient(135deg, #147a8c, #1f9bb0); }
      .student-avatar.av-5 { background: linear-gradient(135deg, #a8781a, #f0b429); }

      /* ===== Chips & progress ===== */
      .course-chip { background: rgba(15,109,128,0.07); color: var(--ts-teal); border-radius: 20px; padding: 2px 10px; font-size: 0.68rem; font-weight: 600; white-space: nowrap; }
      .td-results-chip { background: rgba(15,109,128,0.06); color: var(--ts-teal); border-radius: 20px; padding: 4px 14px; font-size: .78rem; font-weight: 700; display: inline-flex; align-items: center; gap: 7px; }
      .juz-bar { height: 6px; border-radius: 3px; background: rgba(15,109,128,0.1); overflow: hidden; min-width: 60px; flex-grow: 1; max-width: 90px; }
      .juz-bar > div { height: 100%; border-radius: 3px; background: linear-gradient(90deg, #0F6D80, #d89b1d); }
      .att-progress { height: 5px; max-width: 70px; flex-grow: 1; border-radius: 3px; background: rgba(15,109,128,0.08); overflow: hidden; }
      .att-progress > div { height: 100%; border-radius: 3px; }

      /* ===== Table ===== */
      .td-table thead th { border-bottom: 1px solid rgba(15,109,128,0.1); background: #f6fafb; color: #45606b; font-size: .72rem; font-weight: 700; white-space: nowrap; }
      .td-table tbody td { border-bottom: 1px dashed rgba(15,109,128,0.08); vertical-align: middle; }
      .td-table tbody tr:last-child td { border-bottom: none; }
      .td-table tbody tr:hover { background: rgba(15,109,128,0.025); }
      .td-name-link { color: var(--ts-ink); font-weight: 700; font-size: .88rem; text-decoration: none; }
      .td-name-link:hover { color: var(--ts-teal); }

      /* ===== Filter inputs ===== */
      .td-filter-input { border-radius: 10px; border: 1.5px solid rgba(15,109,128,0.12); padding: 8px 14px; font-size: .88rem; }
      .td-filter-input:focus { border-color: var(--ts-teal); box-shadow: 0 0 0 3px rgba(15,109,128,0.08); }
      .td-select-filter { border-radius: 10px; border: 1.5px solid rgba(15,109,128,0.12); padding: 8px 14px; font-size: .88rem; }
      .td-select-filter:focus { border-color: var(--ts-teal); box-shadow: 0 0 0 3px rgba(15,109,128,0.08); }
      .td-filter-label { font-size: .78rem; font-weight: 600; color: #3f484b; margin-bottom: 5px; }

      /* ===== Row actions ===== */
      .td-icon-btn { width: 32px; height: 32px; border-radius: 9px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid rgba(15,109,128,0.14); color: var(--ts-teal); transition: all 0.15s; text-decoration: none; font-size: .78rem; background: #fff; }
      .td-icon-btn:hover { background: var(--ts-teal); color: #fff; }
      .td-icon-btn.gold { color: #a8781a; border-color: rgba(216,155,29,0.28); }
      .td-icon-btn.gold:hover { background: var(--ts-gold); color: #fff; }
      .td-icon-btn.disabled { opacity: .4; pointer-events: none; }

      .td-empty-icon { font-size: 2.8rem; color: rgba(15,109,128,0.15); }
  </style>
@endpush

@section('content')
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <h1 class="td-page-title"><i class="fa-solid fa-user-graduate"></i>{{ __('messages.teacher_students_title') }}</h1>
    @if($courses->count())
    <button class="btn-teacher-primary" onclick="showAddStudentModal()"><i class="fa-solid fa-user-plus me-1"></i>{{ __('messages.teacher_students_add') }}</button>
    @endif
  </div>

  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-users"></i></div>
        <div><p class="td-stat-value mb-0">{{ $stats['total'] }}</p><p class="td-stat-label mb-0">{{ __('messages.teacher_students_total') }}</p></div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-circle-check"></i></div>
        <div><p class="td-stat-value mb-0">{{ $stats['active'] }}</p><p class="td-stat-label mb-0">{{ __('messages.teacher_students_active') }}</p></div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-star"></i></div>
        <div><p class="td-stat-value mb-0">{{ $stats['new_this_month'] }}</p><p class="td-stat-label mb-0">{{ __('messages.teacher_students_new') }}</p></div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-check-double"></i></div>
        <div><p class="td-stat-value mb-0">{{ $stats['completed'] }}</p><p class="td-stat-label mb-0">{{ __('messages.teacher_students_completed') }}</p></div>
      </div>
    </div>
  </div>

  <div class="td-card p-3 mb-4">
    <div class="row g-2 align-items-end">
      <div class="col-12 col-md-5">
        <label class="form-label td-filter-label">{{ __('messages.teacher_students_search_label') }}</label>
        <div class="position-relative">
          <i class="fa-solid fa-search position-absolute top-50 translate-middle-y" style="right:14px;color:#bec8cc;"></i>
          <input type="text" class="form-control td-filter-input" style="padding-right:38px;" placeholder="{{ __('messages.teacher_students_search') }}" oninput="filterStudents()" id="searchInput" />
        </div>
      </div>
      <div class="col-6 col-md-3">
        <label class="form-label td-filter-label">{{ __('messages.teacher_students_course_label') }}</label>
        <select class="form-select td-select-filter" id="courseFilter" onchange="filterStudents()">
          <option value="">{{ __('messages.teacher_students_all_courses') }}</option>
          @foreach($courses as $c)
          <option value="{{ $c->id }}">{{ $c->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-6 col-md-3">
        <label class="form-label td-filter-label">{{ __('messages.teacher_students_status_label') }}</label>
        <select class="form-select td-select-filter" id="statusFilter" onchange="filterStudents()">
          <option value="">{{ __('messages.teacher_students_all_status') }}</option>
          <option value="active">{{ __('messages.teacher_students_status_active') }}</option>
          <option value="pending">{{ __('messages.teacher_students_status_pending') }}</option>
          <option value="completed">{{ __('messages.teacher_students_status_completed_s') }}</option>
        </select>
      </div>
      <div class="col-12 col-md-1 d-grid">
        <button class="btn-teacher-outline" onclick="resetFilters()" title="{{ __('messages.teacher_students_reset_filters') }}"><i class="fa-solid fa-rotate-left"></i></button>
      </div>
    </div>
  </div>

  <div class="d-flex align-items-center justify-content-between mb-2 px-1">
    <span class="td-results-chip"><i class="fa-solid fa-user-graduate"></i><span id="visibleCount">{{ $students->count() }}</span> {{ __('messages.teacher_students_visible') }}</span>
  </div>

  <div class="td-card overflow-hidden">
    <div class="table-responsive">
      <table class="table td-table mb-0 align-middle" id="studentsTable">
        <thead>
          <tr>
            <th class="px-3 py-3" style="width:50px;">#</th>
            <th class="px-3 py-3">{{ __('messages.teacher_students_table_name') }}</th>
            <th class="px-3 py-3">{{ __('messages.teacher_students_table_courses') }}</th>
            <th class="px-3 py-3">{{ __('messages.teacher_students_table_memorized') }}</th>
            <th class="px-3 py-3">{{ __('messages.teacher_students_table_attendance') }}</th>
            <th class="px-3 py-3">{{ __('messages.teacher_students_table_avg') }}</th>
            <th class="px-3 py-3">{{ __('messages.teacher_students_table_status') }}</th>
            <th class="px-3 py-3" style="width:110px;text-align:center;">{{ __('messages.teacher_students_table_actions') }}</th>
          </tr>
        </thead>
        <tbody>
          @forelse($students as $s)
          @php
            $status = $s->status ?? 'active';
            $statusLabels = ['active' => __('messages.teacher_students_status_active'), 'pending' => __('messages.teacher_students_status_pending'), 'completed' => __('messages.teacher_students_status_completed_s')];
            $statusClasses = ['active' => 'td-badge-strong', 'pending' => 'td-badge-soft', 'completed' => 'td-badge-mid'];
            $avg = $s->exams_avg_score;
            $avgClass = $avg !== null && $avg >= 90 ? 'td-badge-strong' : ($avg !== null && $avg >= 70 ? 'td-badge-mid' : 'td-badge-soft');
            $juzPct = round((($s->completed_juz ?? 0) / 30) * 100);
            $attPct = $s->attendance_rate ?? 0;
            $attGrad = $attPct >= 75 ? 'linear-gradient(90deg,#0F6D80,#157a8c)' : ($attPct >= 50 ? 'linear-gradient(90deg,#d89b1d,#f0b429)' : 'linear-gradient(90deg,#dc3545,#e35d6a)');
          @endphp
          <tr data-course-ids="{{ collect($s->course_list ?? [])->pluck('id')->implode(',') }}" data-status="{{ $status }}">
            <td class="px-3 text-secondary small">{{ $loop->iteration }}</td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <a href="{{ route('teacher.student-profile', $s->id) }}" class="text-decoration-none">
                  <div class="student-avatar av-{{ $s->id % 6 }}">{{ mb_substr($s->name ?? '؟', 0, 1) }}</div>
                </a>
                <div>
                  <a href="{{ route('teacher.student-profile', $s->id) }}" class="td-name-link d-block">{{ $s->name ?? __('messages.teacher_students_student') }}</a>
                  <span class="small text-secondary opacity-75" dir="ltr">{{ $s->phone ?? $s->email ?? '—' }}</span>
                </div>
              </div>
            </td>
            <td>
              <div class="d-flex flex-wrap gap-1" style="max-width:170px;">
                @forelse(array_slice(($s->course_list ?? [])->all(), 0, 2) as $c)
                <span class="course-chip">{{ $c['name'] }}</span>
                @empty
                <span class="text-secondary small">—</span>
                @endforelse
                @if(count($s->course_list ?? []) > 2)
                <span class="course-chip" title="{{ $s->teacher_courses }}">+{{ count($s->course_list) - 2 }}</span>
                @endif
              </div>
            </td>
            <td>
              <div class="d-flex align-items-center gap-2 small">
                <span class="fw-bold" style="color:var(--ts-teal);">{{ $s->completed_juz ?? 0 }}<span class="text-secondary fw-normal">/30</span></span>
                <div class="juz-bar"><div style="width:{{ $juzPct }}%"></div></div>
                @if(($s->in_progress_juz ?? 0) > 0)
                <span title="{{ __('messages.teacher_students_in_progress') }}" style="color:#b57f0e;"><i class="fa-solid fa-book-open"></i> {{ $s->in_progress_juz }}</span>
                @endif
              </div>
            </td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <span class="small fw-medium">{{ number_format($attPct) }}%</span>
                <div class="att-progress"><div style="width:{{ $attPct }}%;background:{{ $attGrad }};"></div></div>
              </div>
            </td>
            <td>
              @if($avg !== null)
              <span class="td-badge {{ $avgClass }}">{{ number_format($avg, 1) }}</span>
              @else
              <span class="text-secondary small">—</span>
              @endif
            </td>
            <td><span class="td-badge {{ $statusClasses[$status] ?? 'td-badge-strong' }}">{{ $statusLabels[$status] ?? __('messages.teacher_students_status_active') }}</span></td>
            <td>
              <div class="d-flex align-items-center justify-content-center gap-1">
                <a href="{{ route('teacher.student-profile', $s->id) }}" class="td-icon-btn" title="{{ __('messages.teacher_students_view') }}"><i class="fa-solid fa-eye"></i></a>
                @if($s->user_id && $s->user)
                <a href="{{ route('teacher.messages') }}?to={{ $s->user->id }}" class="td-icon-btn gold" title="{{ __('messages.teacher_students_message') }}"><i class="fa-solid fa-envelope"></i></a>
                @else
                <span class="td-icon-btn gold disabled" title="{{ __('messages.teacher_students_no_account') }}"><i class="fa-solid fa-envelope"></i></span>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center py-5">
              <i class="fa-solid fa-user-graduate td-empty-icon mb-3 d-block"></i>
              <p class="text-secondary mb-0">{{ __('messages.teacher_students_empty') }}</p>
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
function toggleMobileNav() {
  const sidebar = document.querySelector(".teacher-sidebar");
  sidebar.style.display = (sidebar.style.display === "flex") ? "none" : "flex";
}
function filterStudents() {
  const query = document.getElementById("searchInput").value.trim().toLowerCase();
  const course = document.getElementById("courseFilter").value;
  const status = document.getElementById("statusFilter").value;
  let visible = 0;
  document.querySelectorAll("#studentsTable tbody tr[data-status]").forEach(row => {
    const nameCell = row.cells[1]?.textContent?.trim().toLowerCase() || "";
    const ids = (row.dataset.courseIds || "").split(",");
    const matchName = !query || nameCell.includes(query);
    const matchCourse = !course || ids.includes(course);
    const matchStatus = !status || row.dataset.status === status;
    const show = matchName && matchCourse && matchStatus;
    row.style.display = show ? "" : "none";
    if (show) visible++;
  });
  document.getElementById("visibleCount").textContent = visible;
}
function resetFilters() {
  document.getElementById("searchInput").value = "";
  document.getElementById("courseFilter").value = "";
  document.getElementById("statusFilter").value = "";
  filterStudents();
}

var enrollableStudents = @json($enrollableStudents->map(fn ($st) => ['id' => $st->id, 'name' => $st->name_ar, 'phone' => $st->phone]));

function showAddStudentModal() {
  var courseOptions = '';
  @foreach($courses as $c)
  courseOptions += '<option value="{{ $c->id }}">{{ addslashes($c->name) }}</option>';
  @endforeach
  var studentOptions = '<option value="">-- {{ __('messages.teacher_students_add_pick_student') }} --</option>';
  enrollableStudents.forEach(function(st) {
    studentOptions += '<option value="' + st.id + '">' + st.name + (st.phone ? ' — ' + st.phone : '') + '</option>';
  });
  var formHtml = '<form id="crudForm" class="text-end">' +
    '<div class="mb-3 d-flex justify-content-start gap-4">' +
    '<div class="form-check"><input class="form-check-input" type="radio" name="mode" id="modeNew" value="new" checked onchange="toggleMode()"><label class="form-check-label small fw-medium" for="modeNew">{{ __('messages.teacher_students_add_mode_new') }}</label></div>' +
    '<div class="form-check"><input class="form-check-input" type="radio" name="mode" id="modeExisting" value="existing" onchange="toggleMode()"><label class="form-check-label small fw-medium" for="modeExisting">{{ __('messages.teacher_students_add_mode_existing') }}</label></div>' +
    '</div>' +
    '<div id="newFields">' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_students_add_name_label') }}</label><input name="name_ar" id="fieldName" class="form-control teacher-input" placeholder="{{ __('messages.teacher_students_add_name_placeholder') }}"></div>' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_students_add_phone_label') }}</label><input name="phone" id="fieldPhone" class="form-control teacher-input" dir="ltr"></div>' +
    '</div>' +
    '<div id="existingFields" style="display:none;">' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_students_add_mode_existing') }}</label><select name="student_id" id="fieldStudent" class="form-select teacher-input" disabled>' + studentOptions + '</select></div>' +
    '</div>' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_students_add_course_label') }}</label><select name="course_id" class="form-select teacher-input" required>' + courseOptions + '</select></div>' +
    '</form>';
  Swal.fire({
    title: '{{ __('messages.teacher_students_add_title') }}', html: formHtml, showCancelButton: true, focusConfirm: false,
    confirmButtonColor: '#0F6D80', cancelButtonColor: '#6c757d',
    confirmButtonText: '<i class="fa-solid fa-check ml-1"></i>{{ __('messages.teacher_sessions_save') }}', cancelButtonText: '{{ __('messages.teacher_sessions_cancel') }}',
    didOpen: function() { toggleMode(); },
    preConfirm: function() {
      var form = document.getElementById('crudForm');
      var mode = form.querySelector('input[name="mode"]:checked').value;
      var data = { course_id: form.course_id.value };
      if (mode === 'new') {
        if (!form.name_ar.value.trim()) { Swal.showValidationMessage('{{ __('messages.teacher_students_add_name_required') }}'); return false; }
        data.name_ar = form.name_ar.value.trim();
        if (form.phone.value.trim()) data.phone = form.phone.value.trim();
      } else {
        if (!form.student_id.value) { Swal.showValidationMessage('{{ __('messages.teacher_students_add_pick_required') }}'); return false; }
        data.student_id = form.student_id.value;
      }
      return fetch('{{ route("teacher.students.enroll") }}', {
        method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      }).then(function(r) {
        if (!r.ok) return r.json().then(function(e) { throw new Error(e.message || '{{ __('messages.teacher_students_enroll_error') }}'); });
        return r.json();
      }).then(function(result) {
        if (result.status === 'enrolled') {
          Swal.fire({ icon: 'success', title: '{{ __('messages.teacher_sessions_ok') }}', text: '{{ __('messages.teacher_students_enroll_success') }}', confirmButtonColor: '#0F6D80', confirmButtonText: '{{ __('messages.teacher_sessions_ok') }}', timer: 1500 });
          setTimeout(function() { location.reload(); }, 1600);
        } else {
          throw new Error(result.message || '{{ __('messages.teacher_students_enroll_error') }}');
        }
      }).catch(function(e) {
        Swal.showValidationMessage(e.message);
      });
    },
    allowOutsideClick: false
  });
}
function toggleMode() {
  var isNew = document.getElementById('modeNew').checked;
  document.getElementById('newFields').style.display = isNew ? '' : 'none';
  document.getElementById('existingFields').style.display = isNew ? 'none' : '';
  document.getElementById('fieldStudent').disabled = !isNew;
  document.getElementById('fieldName').disabled = !isNew;
  document.getElementById('fieldPhone').disabled = !isNew;
}
</script>
@endpush
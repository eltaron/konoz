@extends('layouts.teacher')

@section('title', __('messages.teacher_schedule') . ' | ' . __('messages.site_name'))
@section('meta_description', __('messages.teacher_schedule_meta'))
@section('header_greeting', __('messages.teacher_header_greeting', ['name' => __('messages.teacher_female')]))
@section('page_title', __('messages.teacher_schedule_title'))

@push('styles')
<style>
      :root { --ts-teal: #0F6D80; --ts-teal-dark: #0a4a56; --ts-teal-mid: #157a8c; --ts-gold: #d89b1d; --ts-gold-light: #f0b429; --ts-ink: #1f2937; --ts-muted: #6b7a7e; }

      /* ===== Page header ===== */
      .td-page-title { font-size: 1.35rem; font-weight: 800; color: var(--ts-ink); margin: 0; display: flex; align-items: center; gap: 12px; }
      .td-page-title i { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.05rem; background: linear-gradient(135deg, rgba(15,109,128,0.1), rgba(15,109,128,0.04)); color: var(--ts-teal); }

      /* ===== Buttons ===== */
      .btn-teacher-primary { background: linear-gradient(135deg, var(--ts-teal), var(--ts-teal-mid)); color: #fff; border: none; border-radius: 9px; font-weight: 600; font-size: .78rem; padding: 7px 14px; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; }
      .btn-teacher-primary:hover { filter: brightness(1.08); color: #fff; }

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
      .td-badge-sm { padding: 3px 9px; font-size: .64rem; }

      /* ===== Week navigation ===== */
      .week-nav-wrap { display: flex; align-items: center; justify-content: center; gap: 14px; margin-bottom: 18px; }
      .week-nav-btn { width: 36px; height: 36px; border-radius: 10px; border: 1px solid rgba(15,109,128,0.14); background: #fff; color: var(--ts-teal); display: flex; align-items: center; justify-content: center; transition: all 0.2s; cursor: pointer; }
      .week-nav-btn:hover { background: var(--ts-teal); color: #fff; }
      .week-display { font-weight: 700; color: var(--ts-teal-dark); font-size: .92rem; background: rgba(15,109,128,0.06); border: 1px solid rgba(15,109,128,0.1); border-radius: 20px; padding: 7px 20px; display: inline-flex; align-items: center; gap: 8px; }
      .week-display i { color: var(--ts-gold); }

      /* ===== Days grid ===== */
      .day-column { background: #fff; border-radius: 14px; border: 1px solid rgba(15,109,128,0.06); padding: 12px; min-height: 180px; box-shadow: 0 3px 14px rgba(15,109,128,0.03); }
      .day-column.today { border-color: var(--ts-teal); background: linear-gradient(180deg, rgba(15,109,128,0.025), #fff); }
      .day-header { display: flex; align-items: center; justify-content: center; gap: 6px; font-weight: 700; font-size: .82rem; color: var(--ts-ink); text-align: center; padding-bottom: 8px; border-bottom: 1px dashed rgba(15,109,128,0.1); margin-bottom: 10px; }
      .day-header.today { color: var(--ts-teal); }
      .day-header.today::after { content: ''; width: 7px; height: 7px; border-radius: 50%; background: var(--ts-gold); }
      .schedule-session { background: #fff; border: 1px solid rgba(15,109,128,0.08); border-inline-start: 3px solid var(--ts-teal); border-radius: 9px; padding: 8px 10px; margin-bottom: 6px; transition: all 0.15s; }
      .schedule-session:hover { box-shadow: 0 4px 14px rgba(15,109,128,0.1); transform: translateY(-1px); }
      .schedule-session .ss-title { font-weight: 700; color: var(--ts-ink); font-size: .78rem; }
      .schedule-session .ss-time { color: var(--ts-muted); font-size: .7rem; display: flex; align-items: center; gap: 5px; }
      .schedule-session .ss-time i { color: var(--ts-teal); font-size: .66rem; }
      .td-empty-col { font-size: .74rem; color: #adb5bd; text-align: center; padding-top: 14px; }

      /* ===== Table ===== */
      .td-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 20px rgba(15,109,128,0.05); border: 1px solid rgba(15,109,128,0.06); }
      .td-table thead th { border-bottom: 1px solid rgba(15,109,128,0.1); background: #f6fafb; color: #45606b; font-size: .72rem; font-weight: 700; white-space: nowrap; }
      .td-table tbody td { border-bottom: 1px dashed rgba(15,109,128,0.08); vertical-align: middle; font-size: .86rem; }
      .td-table tbody tr:last-child td { border-bottom: none; }
      .td-table tbody tr:hover { background: rgba(15,109,128,0.025); }
      .td-section-title { display: flex; align-items: center; gap: 10px; font-size: .92rem; font-weight: 800; color: var(--ts-ink); margin: 0 0 4px; padding: 16px 20px 0; }
      .td-section-title i { width: 30px; height: 30px; border-radius: 9px; display: inline-flex; align-items: center; justify-content: center; font-size: .8rem; background: linear-gradient(135deg, rgba(15,109,128,0.1), rgba(15,109,128,0.04)); color: var(--ts-teal); }
      .td-cell-title { font-weight: 700; color: var(--ts-ink); }
  </style>
@endpush

@section('content')
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <h1 class="td-page-title"><i class="fa-solid fa-calendar-week"></i>{{ __('messages.teacher_schedule_title') }}</h1>
    <button class="btn-teacher-primary" onclick="addSession()"><i class="fa-solid fa-plus me-1"></i>{{ __('messages.teacher_schedule_add') }}</button>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-video"></i></div>
        <div><p class="td-stat-value mb-0">{{ $totalSessions }}</p><p class="td-stat-label mb-0">{{ __('messages.teacher_schedule_total') }}</p></div>
      </div>
    </div>
    <div class="col-6 col-md-4">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-calendar-week"></i></div>
        <div><p class="td-stat-value mb-0">{{ $thisWeek }}</p><p class="td-stat-label mb-0">{{ __('messages.teacher_schedule_this_week') }}</p></div>
      </div>
    </div>
    <div class="col-6 col-md-4">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-clock"></i></div>
        <div><p class="td-stat-value mb-0">{{ $upcomingCount }}</p><p class="td-stat-label mb-0">{{ __('messages.teacher_schedule_upcoming') }}</p></div>
      </div>
    </div>
  </div>

  <div class="week-nav-wrap">
    <button class="week-nav-btn" onclick="prevWeek()" title="{{ __('messages.teacher_schedule_prev_week') }}"><i class="fa-solid fa-chevron-right"></i></button>
    <span class="week-display" dir="ltr"><i class="fa-regular fa-calendar"></i>{{ $currentWeekStart }} - {{ $currentWeekEnd }}</span>
    <button class="week-nav-btn" onclick="nextWeek()" title="{{ __('messages.teacher_schedule_next_week') }}"><i class="fa-solid fa-chevron-left"></i></button>
  </div>

  <div class="row g-2 mb-4 d-none d-md-flex">
    @foreach($weekDayNames as $i => $dayName)
    @php
      $date = \Carbon\Carbon::parse($currentWeekStart)->addDays($i)->format('Y-m-d');
      $isToday = $date === now()->format('Y-m-d');
    @endphp
    <div class="col day-column @if($isToday) today @endif">
      <div class="day-header @if($isToday) today @endif">{{ $dayName }}</div>
      @forelse($weekSessions[$date] ?? [] as $s)
      <div class="schedule-session">
        <div class="ss-title">{{ $s->title }}</div>
        <div class="ss-time"><i class="fa-regular fa-clock"></i>{{ \Carbon\Carbon::parse($s->time_from)->format('g:i A') . ' - ' . \Carbon\Carbon::parse($s->time_to)->format('g:i A') }}</div>
        @php
          $badge = match($s->status) { 'completed' => 'td-badge-strong', 'in_progress' => 'td-badge-mid', 'cancelled' => 'td-badge-danger', default => 'td-badge-soft' };
          $label = match($s->status) { 'completed' => __('messages.teacher_schedule_badge_completed'), 'in_progress' => __('messages.teacher_schedule_badge_in_progress'), 'cancelled' => __('messages.teacher_schedule_badge_cancelled'), default => __('messages.teacher_schedule_badge_upcoming') };
        @endphp
        <span class="td-badge td-badge-sm {{ $badge }}">{{ $label }}</span>
      </div>
      @empty
      <div class="td-empty-col">{{ __('messages.teacher_schedule_no_sessions') }}</div>
      @endforelse
    </div>
    @endforeach
  </div>

  <div class="td-card overflow-hidden">
    <h6 class="td-section-title"><i class="fa-solid fa-list"></i>{{ __('messages.teacher_schedule_all_sessions') }}</h6>
    <div class="table-responsive">
      <table class="table td-table table-borderless mb-0">
        <thead>
          <tr><th class="px-3 py-3">{{ __('messages.teacher_schedule_table_day') }}</th><th class="px-3 py-3">{{ __('messages.teacher_schedule_table_date') }}</th><th class="px-3 py-3">{{ __('messages.teacher_schedule_table_time') }}</th><th class="px-3 py-3">{{ __('messages.teacher_schedule_table_title') }}</th><th class="px-3 py-3">{{ __('messages.teacher_schedule_table_group') }}</th><th class="px-3 py-3">{{ __('messages.teacher_schedule_table_status') }}</th></tr>
        </thead>
        <tbody>
          @forelse($sessions as $s)
          @php
            $badge = match($s->status) { 'completed' => 'td-badge-strong', 'in_progress' => 'td-badge-mid', 'cancelled' => 'td-badge-danger', default => 'td-badge-soft' };
            $label = match($s->status) { 'completed' => __('messages.teacher_schedule_badge_completed'), 'in_progress' => __('messages.teacher_schedule_badge_in_progress'), 'cancelled' => __('messages.teacher_schedule_badge_cancelled'), default => __('messages.teacher_schedule_badge_upcoming') };
          @endphp
          <tr>
            <td class="px-3">{{ __('messages.' . strtolower(\Carbon\Carbon::parse($s->date)->englishDayOfWeek)) ?? '' }}</td>
            <td dir="ltr" class="px-3 text-start">{{ $s->date }}</td>
            <td dir="ltr" class="px-3 text-start">{{ \Carbon\Carbon::parse($s->time_from)->format('g:i A') }}</td>
            <td class="td-cell-title px-3">{{ $s->title }}</td>
            <td class="px-3" style="color:var(--ts-teal);font-weight:600;">{{ $s->course->name ?? '—' }}</td>
            <td class="px-3"><span class="td-badge {{ $badge }}">{{ $label }}</span></td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center py-5">
              <i class="fa-solid fa-calendar-week td-empty-icon mb-3 d-block" style="font-size:2.8rem;color:rgba(15,109,128,0.15);"></i>
              <p class="text-secondary mb-0">{{ __('messages.teacher_schedule_no_sessions') }}</p>
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
function navigateWeek(weekStart) {
  window.location.href = '{{ route("teacher.schedule") }}?week=' + weekStart;
}
function prevWeek() {
  navigateWeek('{{ $prevWeekStart }}');
}
function nextWeek() {
  navigateWeek('{{ $nextWeekStart }}');
}
function addSession() {
  showAddSessionModal();
}

function getCoursesList() {
  @php
    $coursesList = auth()->user()->role === 'admin'
      ? \App\Models\Course::orderBy('name_ar')->pluck('name_ar', 'id')
      : \App\Models\Course::where('user_id', auth()->id())->orderBy('name_ar')->pluck('name_ar', 'id');
  @endphp
  return @json($coursesList);
}

function showAddSessionModal() {
  var courses = getCoursesList();
  var courseOptions = '';
  for (var id in courses) { courseOptions += '<option value="' + id + '">' + courses[id] + '</option>'; }
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
</script>
@endpush
@extends('layouts.student')

@section('title', __('messages.student_hifdh_title') . ' | ' . __('messages.site_name'))

@section('meta_description', __('messages.student_hifdh_subtitle'))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard/dashboard-hifdh.css') }}" />
<style>
.juz-card { background:#fff; border:1px solid rgba(15,109,128,0.06); border-radius:12px; padding:10px 6px; text-align:center; cursor:pointer; transition:0.2s; position:relative; overflow:hidden; }
.juz-card:hover { border-color:rgba(15,109,128,0.15); box-shadow:0 2px 12px rgba(15,109,128,0.06); transform:translateY(-1px); }
.juz-card-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:4px; }
.juz-card-num { font-size:1rem; font-weight:800; color:#1f2937; }
.juz-card-icon { font-size:0.7rem; }
.juz-card-date { display:block; font-size:0.58rem; line-height:1.6; text-align:center; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.juz-not_started { background:#fafbfc; }
.juz-not_started .juz-card-num { color:#bec8cc; }
.juz-in_progress { border-color:rgba(15,109,128,0.15); background:rgba(15,109,128,0.02); }
.juz-in_progress::before { content:''; position:absolute; top:0; left:0; width:3px; height:100%; background:#0F6D80; border-radius:0 0 3px 0; }
.juz-reviewing { border-color:rgba(245,189,88,0.2); background:rgba(245,189,88,0.03); }
.juz-reviewing::before { content:''; position:absolute; top:0; left:0; width:3px; height:100%; background:var(--pumpkin); border-radius:0 0 3px 0; }
.juz-completed { border-color:rgba(39,174,96,0.15); background:rgba(39,174,96,0.02); }
.juz-completed::before { content:''; position:absolute; top:0; left:0; width:3px; height:100%; background:#27ae60; border-radius:0 0 3px 0; }
.juz-card.overdue { border-color:rgba(220,53,69,0.2); background:rgba(220,53,69,0.02); }
.juz-card.soon { border-color:rgba(255,193,7,0.2); }
</style>
@endpush

@section('content')
<h1 class="fw-bold mb-1" style="color: #0F6D80; font-size: 1.6rem;" data-aos="fade-up">{{ __('messages.student_hifdh_title') }}</h1>
<p class="text-secondary opacity-75 mb-4" data-aos="fade-up">{{ __('messages.student_hifdh_subtitle') }}</p>

<!-- Stats -->
<div class="row g-3 mb-4" data-aos="fade-up">
  <div class="col-6 col-md-3">
    <div class="dash-card text-center">
      <i class="fa-solid fa-book-quran fs-4 mb-1" style="color: #0F6D80;"></i>
      <h3 class="fw-bold mb-0" style="color: #0F6D80;">{{ $completedJuz }}/30</h3>
      <small class="text-secondary opacity-75">{{ __('messages.student_hifdh_completed_parts') }}</small>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="dash-card text-center">
      <i class="fa-regular fa-clock fs-4 mb-1" style="color: var(--pumpkin);"></i>
      <h3 class="fw-bold mb-0" style="color: var(--pumpkin);">{{ $todayMinutes ?? 0 }}</h3>
      <small class="text-secondary opacity-75">{{ __('messages.student_hifdh_today_minutes') }}</small>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="dash-card text-center">
      <i class="fa-regular fa-calendar-check fs-4 mb-1" style="color: #0F6D80;"></i>
      <h3 class="fw-bold mb-0" style="color: #0F6D80;">{{ $streakDays }}</h3>
      <small class="text-secondary opacity-75">{{ __('messages.student_hifdh_streak_days') }}</small>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="dash-card text-center">
      <i class="fa-solid fa-rotate fs-4 mb-1" style="color: var(--pumpkin);"></i>
      <h3 class="fw-bold mb-0" style="color: var(--pumpkin);">{{ $activityCounts->sum() ?? 0 }}</h3>
      <small class="text-secondary opacity-75">{{ __('messages.student_hifdh_weekly_activity') }}</small>
    </div>
  </div>
</div>

<div class="row g-4">
    <!-- Main Column: Juz Map + Weekly Log -->
    <div class="col-lg-8">
    <div class="dash-card" data-aos="fade-up">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="fw-bold mb-0" style="color: #0F6D80;">{{ __('messages.student_hifdh_juz_map') }}</h5>
        <div class="d-flex align-items-center gap-2 small">
          <span class="d-flex align-items-center gap-1"><span class="rounded-1 d-inline-block" style="width:10px;height:10px;background:#27ae60;"></span> {{ __('messages.student_hifdh_completed') }}</span>
          <span class="d-flex align-items-center gap-1"><span class="rounded-1 d-inline-block" style="width:10px;height:10px;background:var(--pumpkin);"></span> {{ __('messages.student_hifdh_reviewing') }}</span>
          <span class="d-flex align-items-center gap-1"><span class="rounded-1 d-inline-block" style="width:10px;height:10px;background:#0F6D80;"></span> {{ __('messages.student_hifdh_in_progress') }}</span>
          <span class="d-flex align-items-center gap-1"><span class="rounded-1 d-inline-block" style="width:10px;height:10px;background:#d0d9db;"></span> {{ __('messages.student_hifdh_not_started') }}</span>
        </div>
      </div>
      <div class="row g-2">
        @foreach($juzProgress as $jp)
        @php
          $daysUntilReview = $jp->target_review_at ? now()->startOfDay()->diffInDays($jp->target_review_at, false) : null;
          $reviewClass = $daysUntilReview !== null ? ($daysUntilReview < 0 ? 'overdue' : ($daysUntilReview <= 3 ? 'soon' : '')) : '';
        @endphp
        <div class="col-4 col-md-2">
          <div class="juz-card juz-{{ $jp->status }} {{ $reviewClass }}" onclick="openJuzModal({{ $jp->id }}, {{ $jp->juz_number }}, '{{ $jp->status }}', '{{ $jp->started_at?->format('Y-m-d') }}', '{{ $jp->target_review_at?->format('Y-m-d') }}', '{{ addslashes($jp->notes ?? '') }}')" data-bs-toggle="tooltip" title="{{ __('messages.student_click_adjust') }}">
            <div class="juz-card-header">
              <span class="juz-card-num">{{ $jp->juz_number }}</span>
              <span class="juz-card-icon">
                @if($jp->status === 'completed') <i class="fa-solid fa-check" style="color:#27ae60;"></i>
                @elseif($jp->status === 'reviewing') <i class="fa-solid fa-rotate" style="color:var(--pumpkin);"></i>
                @elseif($jp->status === 'in_progress') <i class="fa-solid fa-book-open" style="color:#0F6D80;"></i>
                @else <i class="fa-solid fa-lock" style="color:#bec8cc;"></i>
                @endif
              </span>
            </div>
            @if($jp->started_at)
            <span class="juz-card-date" style="color:#0F6D80;">
              <i class="fa-regular fa-calendar-plus" style="font-size:0.55rem;"></i>
              {{ $jp->started_at->format('d/m') }}
            </span>
            @endif
            @if($jp->target_review_at)
            <span class="juz-card-date {{ $daysUntilReview < 0 ? 'text-danger' : ($daysUntilReview <= 3 ? 'text-warning' : '') }}" style="font-size:0.58rem;">
              <i class="fa-regular fa-clock"></i>
              @if($daysUntilReview < 0) {{ __('messages.student_overdue') }} {{ abs($daysUntilReview) }}{{ __('messages.student_days') }}
              @else {{ __('messages.student_after') }} {{ $daysUntilReview }}{{ __('messages.student_days') }}
              @endif
            </span>
            @endif
          </div>
        </div>
        @endforeach
      </div>
    </div>

    <!-- Weekly Activity Log -->
    <div class="dash-card mt-4" data-aos="fade-up">
      <h5 class="fw-bold mb-3" style="color: #0F6D80;">
        {{ __('messages.student_hifdh_daily_log') }}
        <small class="text-secondary opacity-50">({{ $todayActivities->count() }} {{ __('messages.student_hifdh_activities_today') }})</small>
      </h5>

      {{-- Weekly Table --}}
      <div class="table-responsive">
        <table class="table align-middle mb-0" style="font-size:0.85rem;">
          <thead>
            <tr style="color:#0F6D80;border-bottom:2px solid rgba(15,109,128,0.1);">
              <th class="fw-bold ps-0" style="width:90px;">{{ __('messages.student_hifdh_day') }}</th>
              <th class="fw-bold">{{ __('messages.student_hifdh_activities_col') }}</th>
              <th class="fw-bold text-center" style="width:70px;">{{ __('messages.student_hifdh_parts_col') }}</th>
              <th class="fw-bold text-center" style="width:60px;">{{ __('messages.student_hifdh_duration_col') }}</th>
              <th class="fw-bold text-center" style="width:40px;">{{ __('messages.student_hifdh_status_col') }}</th>
            </tr>
          </thead>
          <tbody>
          @php
            $todayIdx = (now()->dayOfWeek + 1) % 7;
            $weekDays = [__('messages.saturday'), __('messages.sunday'), __('messages.monday'), __('messages.tuesday'), __('messages.wednesday'), __('messages.thursday'), __('messages.friday')];
          @endphp
          @foreach($weekDays as $i => $day)
          @php
            $date = now()->startOfWeek(Carbon\Carbon::SATURDAY)->addDays($i)->toDateString();
            $dayActs = $activityDates[$date] ?? collect();
            $isToday = $i === $todayIdx;
            $isFuture = $i > $todayIdx;
            $totalMin = $dayActs->sum('duration_minutes');
            $juzList = $dayActs->pluck('juz_number')->filter()->unique()->sort()->values();
          @endphp
          <tr class="{{ $isToday ? 'table-active' : '' }}" style="{{ $isToday ? 'background:rgba(15,109,128,0.04);' : '' }}{{ $isFuture ? 'opacity:0.35;' : '' }}">
            <td class="ps-0">
              <span class="fw-bold small">{{ $day }}</span>
              <br><small class="text-secondary opacity-50" style="font-size:0.6rem;">{{ \Carbon\Carbon::parse($date)->format('d/m') }}</small>
            </td>
            <td>
              @if($dayActs->isEmpty() && !$isFuture)
              <span class="small text-secondary opacity-50" onclick="logActivityForDay('{{ $date }}','{{ $day }}')" style="cursor:pointer;">— {{ __('messages.student_hifdh_click_log') }}</span>
              @elseif($isFuture)
              <span class="small text-secondary opacity-25">{{ __('messages.student_hifdh_not_yet') }}</span>
              @else
              <div class="d-flex flex-wrap gap-1">
                @foreach($dayActs as $act)
                <span class="d-inline-flex align-items-center gap-1 small px-2 py-1 rounded-2" style="background:rgba(15,109,128,0.06);color:#0F6D80;font-size:0.7rem;">
                  <i class="fa-solid {{ $act->activity_type === __('messages.activity_memorize') ? 'fa-book-quran' : ($act->activity_type === __('messages.activity_review') ? 'fa-rotate' : ($act->activity_type === __('messages.activity_recite') ? 'fa-microphone' : ($act->activity_type === __('messages.activity_exam') ? 'fa-file-pen' : ($act->activity_type === __('messages.activity_listen') ? 'fa-headphones' : 'fa-circle')))) }}"></i>
                  {{ $act->activity_type }}
                  @if($act->juz_number)<span style="font-size:0.6rem;">{{ $act->juz_number }}</span>@endif
                </span>
                @endforeach
              </div>
              @endif
            </td>
            <td class="text-center">
              @if($juzList->isNotEmpty())
              <span class="small fw-medium" style="color:#0F6D80;">{{ $juzList->implode('، ') }}</span>
              @else
              <span class="small text-secondary opacity-25">—</span>
              @endif
            </td>
            <td class="text-center fw-medium" style="color:#0F6D80;">
              @if($totalMin > 0){{ $totalMin }}د @else <span class="text-secondary opacity-25">—</span> @endif
            </td>
            <td class="text-center">
              @if($isFuture)
              <span class="small text-secondary opacity-25"><i class="fa-regular fa-clock"></i></span>
              @elseif($dayActs->isNotEmpty())
              <span style="color:#27ae60;"><i class="fa-solid fa-check"></i></span>
              @else
              <span class="small text-secondary opacity-25" onclick="logActivityForDay('{{ $date }}','{{ $day }}')" style="cursor:pointer;"><i class="fa fa-plus-circle"></i></span>
              @endif
            </td>
          </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Side: Streak + Goals -->
  <div class="col-lg-4">
    <div class="dash-card mb-4" data-aos="fade-up">
      <h5 class="fw-bold mb-3" style="color: #0F6D80;">{{ __('messages.student_hifdh_streak_title') }} <small class="text-secondary opacity-50">({{ $streakDays }} {{ __('messages.student_hifdh_day') }})</small></h5>
      <div class="d-flex flex-wrap gap-2 justify-content-center" id="streakCalendar"></div>
    </div>

    <div class="dash-card mb-4" data-aos="fade-up">
      <h5 class="fw-bold mb-3" style="color: #0F6D80;">{{ __('messages.student_hifdh_week_stats') }}</h5>
      <div id="weekStats">
        @php
          $typeLabels = [__('messages.activity_memorize') => '#0F6D80', __('messages.activity_review') => '#f39c12', __('messages.activity_recite') => '#27ae60', __('messages.activity_exam') => '#e74c3c', __('messages.activity_listen') => '#8e44ad'];
          $total = $activityCounts->sum();
        @endphp
        @if($total === 0)
        <p class="small text-secondary opacity-75 text-center py-3">{{ __('messages.student_hifdh_no_achievements') }}</p>
        @else
        @foreach($typeLabels as $type => $color)
        @if(($activityCounts[$type] ?? 0) > 0)
        <div class="d-flex align-items-center gap-2 mb-2 small">
          <i class="fa-solid {{ $type === __('messages.activity_memorize') ? 'fa-book-quran' : ($type === __('messages.activity_review') ? 'fa-rotate' : ($type === __('messages.activity_recite') ? 'fa-microphone' : ($type === __('messages.activity_exam') ? 'fa-file-pen' : ($type === __('messages.activity_listen') ? 'fa-headphones' : 'fa-circle')))) }}" style="color:{{ $color }};width:16px;"></i>
          <span class="flex-grow-1">{{ $type }}</span>
          <span class="fw-bold" style="color:{{ $color }};">{{ $activityCounts[$type] }}</span>
        </div>
        @endif
        @endforeach
        @if($weeklyMinutes ?? false)
        <hr class="my-2"/>
        <div class="d-flex justify-content-between small"><span>{{ __('messages.student_hifdh_total_minutes') }}</span><span class="fw-bold" style="color:#0F6D80;">{{ $weeklyMinutes }} د</span></div>
        @endif
        @endif
      </div>
    </div>

    <div class="dash-card" data-aos="fade-up">
      <h5 class="fw-bold mb-3" style="color: #0F6D80;">{{ __('messages.student_hifdh_weekly_goals') }}</h5>
      @php
        $hifdhType = __('messages.activity_memorize');
        $reviewType = __('messages.activity_review');
        $examType = __('messages.activity_exam');
        $hifdhCount = $activityCounts[$hifdhType] ?? 0;
        $reviewCount = $activityCounts[$reviewType] ?? 0;
        $testCount = min($activityCounts[$examType] ?? 0, 1);
      @endphp
      <div class="d-flex flex-column gap-3">
        <div>
          <div class="d-flex justify-content-between small mb-1"><span>{{ __('messages.student_hifdh_new_memorization') }}</span><span>{{ $hifdhCount }}/5</span></div>
          <div class="progress" style="height: 6px; background: #e0e3e4;"><div class="progress-bar rounded-3" style="width: {{ min(($hifdhCount/5)*100, 100) }}%; background: #0F6D80;"></div></div>
        </div>
        <div>
          <div class="d-flex justify-content-between small mb-1"><span>{{ __('messages.student_hifdh_review') }}</span><span>{{ $reviewCount }}/3</span></div>
          <div class="progress" style="height: 6px; background: #e0e3e4;"><div class="progress-bar rounded-3" style="width: {{ min(($reviewCount/3)*100, 100) }}%; background: var(--pumpkin);"></div></div>
        </div>
        <div>
          <div class="d-flex justify-content-between small mb-1"><span>{{ __('messages.student_hifdh_test') }}</span><span>{{ $testCount }}/1</span></div>
          <div class="progress" style="height: 6px; background: #e0e3e4;"><div class="progress-bar rounded-3" style="width: {{ $testCount * 100 }}%; background: #0F6D80;"></div></div>
        </div>
      </div>
    </div>

    <!-- Quick Log Button -->
    <div class="dash-card" data-aos="fade-up">
      <h5 class="fw-bold mb-3" style="color: #0F6D80;">{{ __('messages.student_hifdh_quick_log') }}</h5>
      <div class="d-flex flex-column gap-2">
        <button class="btn w-100 rounded-3 py-2" style="background:#0F6D80;color:#fff;" onclick="logQuickActivity('{{ __('messages.activity_memorize') }}', 30)"><i class="fa-solid fa-book-quran ml-1"></i>{{ __('messages.student_hifdh_memorize_30') }}</button>
        <button class="btn w-100 rounded-3 py-2" style="background:var(--pumpkin);color:#5f4100;" onclick="logQuickActivity('{{ __('messages.activity_review') }}', 20)"><i class="fa-solid fa-rotate ml-1"></i>{{ __('messages.student_hifdh_review_20') }}</button>
        <button class="btn w-100 rounded-3 py-2" style="background:rgba(39,174,96,0.1);color:#27ae60;border:1px solid rgba(39,174,96,0.2);" onclick="logQuickActivity('{{ __('messages.activity_recite') }}', 15)"><i class="fa-solid fa-microphone ml-1"></i>{{ __('messages.student_hifdh_recite_15') }}</button>
      </div>
    </div>
  </div>
</div>

<form id="activityForm" method="POST" style="display:none;">@csrf</form>

{{-- Juz Modal --}}
<div class="modal fade" id="juzModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0" style="border-radius:16px;">
      <div class="modal-header border-0 pb-0">
        <h5 class="fw-bold" style="color:#0F6D80;" id="juzModalTitle">{{ __('messages.student_juz') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body px-4 pb-3">
        <form id="juzForm">
          <input type="hidden" id="juzId" value="">
          <div class="mb-3">
            <label class="small fw-bold mb-1 text-secondary">{{ __('messages.student_juz_modal_status') }}</label>
            <select id="juzStatus" class="form-select form-select-sm" style="border-radius:10px;">
              <option value="not_started">{{ __('messages.student_juz_not_started') }}</option>
              <option value="in_progress">{{ __('messages.student_juz_in_progress') }}</option>
              <option value="reviewing">{{ __('messages.student_juz_reviewing') }}</option>
              <option value="completed">{{ __('messages.student_juz_completed') }}</option>
            </select>
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="small fw-bold mb-1 text-secondary">{{ __('messages.student_juz_start_date') }}</label>
              <input type="date" id="juzStart" class="form-control form-control-sm" style="border-radius:10px;">
            </div>
            <div class="col-6">
              <label class="small fw-bold mb-1 text-secondary">{{ __('messages.student_juz_review_date') }}</label>
              <input type="date" id="juzReview" class="form-control form-control-sm" style="border-radius:10px;">
            </div>
          </div>
          <div class="mb-3">
            <label class="small fw-bold mb-1 text-secondary">{{ __('messages.student_juz_notes') }}</label>
            <textarea id="juzNotes" class="form-control form-control-sm" rows="2" style="border-radius:10px;" placeholder="{{ __('messages.student_juz_notes_placeholder') }}"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button class="btn px-4 py-2 fw-bold" style="background:#0F6D80;color:#fff;border-radius:10px;" onclick="saveJuzDates()">{{ __('messages.student_juz_save') }}</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const activityDates = @json($activityDates->keys());
const activityCounts = @json($activityCounts);
const weekDays = ['{{ __("messages.saturday") }}','{{ __("messages.sunday") }}','{{ __("messages.monday") }}','{{ __("messages.tuesday") }}','{{ __("messages.wednesday") }}','{{ __("messages.thursday") }}','{{ __("messages.friday") }}'];
const dayShort = ['{{ __("messages.sat_short") }}','{{ __("messages.sun_short") }}','{{ __("messages.mon_short") }}','{{ __("messages.tue_short") }}','{{ __("messages.wed_short") }}','{{ __("messages.thu_short") }}','{{ __("messages.fri_short") }}'];
const locale = '{{ app()->getLocale() }}';

// Activity type constants matching the translation keys
const actTypes = {
  memorize: '{{ __("messages.activity_memorize") }}',
  review: '{{ __("messages.activity_review") }}',
  recite: '{{ __("messages.activity_recite") }}',
  exam: '{{ __("messages.activity_exam") }}',
  listen: '{{ __("messages.activity_listen") }}',
};

function renderStreak() {
  const cal = document.getElementById('streakCalendar');
  const today = new Date();
  const todayIdx = (today.getDay() + 1) % 7;
  cal.innerHTML = weekDays.map(function(d, i) {
    const isDone = activityDates.some(function(ds) {
      const dt = new Date(ds + 'T00:00:00');
      const diff = Math.round((today - dt) / 86400000);
      return diff % 7 === (todayIdx - i + 7) % 7;
    });
    const cls = isDone ? 'done' : (i === todayIdx ? 'today' : 'missed');
    return '<div class="streak-day ' + cls + '" title="' + d + '">' + (cls === 'done' ? '✓' : dayShort[i]) + '</div>';
  }).join('');
}

function logActivityForDay(dateStr, dayName) {
  var juzOptions = Array.from({length:30}, function(_,i) {
    return '<option value="'+(i+1)+'">{{ __("messages.student_juz") }} '+(i+1)+'</option>';
  }).join('');

  Swal.fire({
    title: '{{ __("messages.hifdh_log_title") }} ' + dayName,
    html: '<p class="small text-secondary opacity-75">{{ __("messages.hifdh_select_activity") }}</p><div class="mb-2"><input type="number" id="swalDuration" class="form-control form-control-sm" placeholder="{{ __("messages.hifdh_duration_placeholder") }}" min="1" /></div><div class="mb-2" id="juzPickerWrap" style="display:none;"><select id="swalJuz" class="form-select form-select-sm"><option value="">{{ __("messages.hifdh_select_juz") }}</option>' + juzOptions + '</select></div>',
    input: 'select',
    inputOptions: {},
    inputPlaceholder: '{{ __("messages.hifdh_select_prompt") }}',
    showCancelButton: true,
    confirmButtonColor: '#0F6D80',
    confirmButtonText: '{{ __("messages.hifdh_save_success") }}',
    cancelButtonText: '{{ __("messages.student_cancel") }}',
    reverseButtons: true,
    inputOptions: {},
    preConfirm: function(v) {
      if (locale === 'ar') {
        var optMap = { memorize: actTypes.memorize, review: actTypes.review, recite: actTypes.recite, exam: actTypes.exam, listen: actTypes.listen };
        var found = Object.values(optMap).find(function(o) { return o === v; });
        if (!found) { Swal.showValidationMessage('{{ __("messages.hifdh_validation_select") }}'); return false; }
        return v;
      } else {
        var optMap = { memorize: actTypes.memorize, review: actTypes.review, recite: actTypes.recite, exam: actTypes.exam, listen: actTypes.listen };
        var found = Object.values(optMap).find(function(o) { return o === v; });
        if (!found) { Swal.showValidationMessage('{{ __("messages.hifdh_validation_select") }}'); return false; }
        return v;
      }
    },
    didOpen: function() {
      var sel = Swal.getInput();
      var selectOptions = {};
      selectOptions[actTypes.memorize] = '{{ __("messages.hifdh_activity_option_memorize") }}';
      selectOptions[actTypes.review] = '{{ __("messages.hifdh_activity_option_review") }}';
      selectOptions[actTypes.recite] = '{{ __("messages.hifdh_activity_option_recite") }}';
      selectOptions[actTypes.exam] = '{{ __("messages.hifdh_activity_option_exam") }}';
      selectOptions[actTypes.listen] = '{{ __("messages.hifdh_activity_option_listen") }}';
      if (sel) {
        sel.innerHTML = '<option value="">' + '{{ __("messages.hifdh_select_prompt") }}' + '</option>' +
          Object.keys(selectOptions).map(function(k) { return '<option value="' + k + '">' + selectOptions[k] + '</option>'; }).join('');
        sel.addEventListener('change', function() {
          document.getElementById('juzPickerWrap').style.display = (this.value === actTypes.memorize || this.value === actTypes.review) ? 'block' : 'none';
        });
      }
    }
  }).then(function(r) {
    if (r.isConfirmed) {
      var duration = document.getElementById('swalDuration') ? document.getElementById('swalDuration').value : '';
      var juz = document.getElementById('swalJuz') ? document.getElementById('swalJuz').value : '';
      saveActivity(dateStr, r.value, duration, juz);
    }
  });
}

function logQuickActivity(type, minutes) {
  const todayStr = new Date().toISOString().slice(0, 10);
  if (type === actTypes.memorize || type === actTypes.review) {
    var juzOptions = Array.from({length:30}, function(_,i) {
      return '<option value="'+(i+1)+'">{{ __("messages.student_juz") }} '+(i+1)+'</option>';
    }).join('');
    Swal.fire({
      title: '{{ __("messages.hifdh_which_juz") }}',
      html: '<select id="swalJuzQuick" class="form-select"><option value="">{{ __("messages.hifdh_select_juz_short") }}</option>' + juzOptions + '</select>',
      showCancelButton: true,
      confirmButtonColor: '#0F6D80',
      confirmButtonText: '{{ __("messages.hifdh_save_success") }}',
      cancelButtonText: '{{ __("messages.student_cancel") }}',
      reverseButtons: true,
      preConfirm: function() {
        var juz = document.getElementById('swalJuzQuick').value;
        if (!juz) { Swal.showValidationMessage('{{ __("messages.hifdh_validation_select_juz") }}'); return false; }
        return juz;
      }
    }).then(function(r) {
      if (r.isConfirmed) saveActivity(todayStr, type, minutes, r.value);
    });
  } else {
    saveActivity(todayStr, type, minutes);
  }
}

function saveActivity(dateStr, type, minutes, juzNumber) {
  const f = document.getElementById('activityForm');
  const formData = new FormData();
  formData.append('_token', '{{ csrf_token() }}');
  formData.append('date', dateStr);
  formData.append('activity_type', type);
  formData.append('duration_minutes', minutes || '');
  formData.append('juz_number', juzNumber || '');
  fetch('{{ route("student.activity.log") }}', {
    method: 'POST',
    headers: { 'Accept': 'application/json' },
    body: formData
  }).then(function(r) {
    if (!r.ok) throw new Error('{{ __("messages.hifdh_save_error") }}');
    return r.json();
  }).then(function(d) {
    if (d.status === 'ok') {
      Swal.fire({ icon: 'success', title: '{{ __("messages.hifdh_saved") }}', timer: 1500, showConfirmButton: false });
      setTimeout(function() { location.reload(); }, 1500);
    }
  }).catch(function(e) {
    Swal.fire({ icon: 'error', title: '{{ __("messages.hifdh_save_error_title") }}', text: '{{ __("messages.hifdh_save_error") }}' });
  });
}

function openJuzModal(id, num, status, startedAt, reviewAt, notes) {
  document.getElementById('juzId').value = id;
  document.getElementById('juzModalTitle').textContent = '{{ __("messages.student_juz") }} ' + num;
  document.getElementById('juzStatus').value = status;
  document.getElementById('juzStart').value = startedAt || '';
  document.getElementById('juzReview').value = reviewAt || '';
  document.getElementById('juzNotes').value = notes || '';
  new bootstrap.Modal(document.getElementById('juzModal')).show();
}

function saveJuzDates() {
  const id = document.getElementById('juzId').value;
  const formData = new FormData();
  formData.append('_token', '{{ csrf_token() }}');
  formData.append('started_at', document.getElementById('juzStart').value || '');
  formData.append('target_review_at', document.getElementById('juzReview').value || '');
  formData.append('status', document.getElementById('juzStatus').value);
  formData.append('notes', document.getElementById('juzNotes').value);
  fetch('{{ route("student.juz.update", "JUZ_ID") }}'.replace('JUZ_ID', id), {
    method: 'POST',
    headers: { 'Accept': 'application/json' },
    body: formData
  }).then(function(r) {
    if (!r.ok) throw new Error('{{ __("messages.hifdh_save_error") }}');
    return r.json();
  }).then(function(d) {
    if (d.status === 'ok') {
      bootstrap.Modal.getInstance(document.getElementById('juzModal')).hide();
      Swal.fire({ icon: 'success', title: '{{ __("messages.hifdh_saved") }}', timer: 1200, showConfirmButton: false });
      setTimeout(function() { location.reload(); }, 1200);
    }
  }).catch(function(e) {
    Swal.fire({ icon: 'error', title: '{{ __("messages.hifdh_save_error_title") }}', text: '{{ __("messages.hifdh_save_error") }}' });
  });
}

renderStreak();
AOS.init({ duration: 600, once: true });
</script>
@endpush

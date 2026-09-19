@extends('layouts.teacher')

@section('title', __('messages.teacher_course_details') . ' | ' . __('messages.site_name'))
@section('meta_description', __('messages.teacher_course_details_meta'))
@section('header_greeting', __('messages.teacher_header_greeting', ['name' => __('messages.teacher_female')]))
@section('page_title', __('messages.teacher_course_details_title'))

@push('styles')
<style>
      :root {
        --cd-teal: #0F6D80;
        --cd-teal-dark: #0a4a56;
        --cd-teal-mid: #157a8c;
        --cd-gold: #d89b1d;
        --cd-gold-light: #ffd166;
        --cd-ink: #1f2937;
        --cd-muted: #6b7a7e;
      }

      /* ===== Page header ===== */
      .cd-back { display: inline-flex; align-items: center; gap: 8px; color: #0F6D80; font-size: .84rem; font-weight: 600; text-decoration: none; margin-bottom: 6px; }
      .cd-back:hover { color: #0a4a56; }
      .cd-page-title { font-size: 1.35rem; font-weight: 800; color: var(--cd-ink); margin: 0; display: flex; align-items: center; gap: 12px; }
      .cd-page-title i { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.05rem; background: linear-gradient(135deg, rgba(15,109,128,0.1), rgba(15,109,128,0.04)); color: #0F6D80; }

      .cd-btn { display: inline-flex; align-items: center; gap: 8px; border: none; border-radius: 10px; font-weight: 600; font-size: .84rem; padding: 9px 18px; transition: all .2s; text-decoration: none; }
      .cd-btn-primary { background: linear-gradient(135deg, #0F6D80, #157a8c); color: #fff !important; }
      .cd-btn-primary:hover { filter: brightness(1.1); color: #fff !important; }
      .cd-btn-ghost { background: rgba(15,109,128,0.06); color: #0F6D80; border: none; }
      .cd-btn-ghost:hover { background: rgba(15,109,128,0.12); color: #0F6D80; }

      /* ===== Hero ===== */
      .cd-hero { position: relative; border-radius: 20px; overflow: hidden; background: linear-gradient(135deg, #0a4a56 0%, #0F6D80 55%, #157a8c 100%); color: #fff; box-shadow: 0 12px 34px rgba(10,74,86,0.22); }
      .cd-hero::after { content: ''; position: absolute; inset: 0; background-image: url("data:image/svg+xml,%3Csvg width='72' height='72' viewBox='0 0 72 72' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M72 0L0 72M36 0L0 36M72 36L36 72' stroke='%23ffffff' stroke-opacity='0.05' fill='none'/%3E%3C/svg%3E"); pointer-events: none; }
      .cd-hero-deco { position: absolute; width: 200px; height: 200px; left: -60px; bottom: -90px; background: rgba(216,155,29,0.16); border-radius: 32px; transform: rotate(24deg); pointer-events: none; }
      .cd-hero-inner { position: relative; z-index: 1; padding: 26px 28px; }
      .cd-hero-icon { width: 62px; height: 62px; border-radius: 16px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: var(--cd-gold-light); background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.18); }
      .cd-hero-name { font-size: 1.3rem; font-weight: 800; color: #fff; margin: 0 0 8px; }
      .cd-pill { display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.18); padding: 4px 12px; border-radius: 20px; font-size: .76rem; font-weight: 600; color: #fff; }
      .cd-pill i { color: var(--cd-gold-light); }
      .cd-hero-stat { min-width: 96px; text-align: center; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.16); border-radius: 14px; padding: 12px 14px; }
      .cd-hero-stat b { display: block; font-size: 1.35rem; font-weight: 800; color: var(--cd-gold-light); line-height: 1.15; }
      .cd-hero-stat span { font-size: .7rem; color: rgba(255,255,255,0.82); }

      /* ===== Cards ===== */
      .cd-card { background: #fff; border-radius: 16px; border: 1px solid rgba(15,109,128,0.07); box-shadow: 0 4px 20px rgba(15,109,128,0.04); padding: 18px 20px; height: 100%; }
      .cd-card-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 14px; }
      .cd-card-title { display: flex; align-items: center; gap: 10px; font-size: .93rem; font-weight: 800; color: var(--cd-ink); margin: 0; }
      .cd-card-title i { width: 32px; height: 32px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: .85rem; background: linear-gradient(135deg, rgba(15,109,128,0.1), rgba(15,109,128,0.04)); color: #0F6D80; }
      .cd-count { font-size: .74rem; font-weight: 700; color: #0F6D80; background: rgba(15,109,128,0.08); border-radius: 20px; padding: 3px 11px; }

      /* ===== Badges ===== */
      .cd-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 20px; font-size: .72rem; font-weight: 700; white-space: nowrap; }
      .cd-badge-teal { background: linear-gradient(135deg, #0F6D80, #157a8c); color: #fff; }
      .cd-badge-teal-soft { background: rgba(15,109,128,0.12); color: #0F6D80; }
      .cd-badge-gold { background: rgba(216,155,29,0.15); color: #a8781a; }
      .cd-badge-red { background: rgba(220,53,69,0.1); color: #dc3545; }

      /* ===== Tabs ===== */
      .cd-tabs { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px; }
      .cd-tab { display: inline-flex; align-items: center; gap: 8px; padding: 9px 16px; border-radius: 10px; font-size: .84rem; font-weight: 600; color: #5c6b72; background: #f2f6f7; border: 1px solid transparent; cursor: pointer; transition: all .2s; user-select: none; text-decoration: none; }
      .cd-tab:hover { background: rgba(15,109,128,0.08); color: #0F6D80; }
      .cd-tab.active { background: linear-gradient(135deg, #0F6D80, #157a8c); color: #fff; box-shadow: 0 4px 14px rgba(15,109,128,0.28); }
      .cd-tab.active i { color: var(--cd-gold-light); }
      .tab-content { display: none; }
      .tab-content.show { display: block; }

      /* ===== Rows ===== */
      .cd-row { display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px dashed rgba(15,109,128,0.1); }
      .cd-row:last-child { border-bottom: none; }
      .cd-avatar { width: 38px; height: 38px; border-radius: 50%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: .85rem; text-decoration: none; background: linear-gradient(135deg, #0F6D80, #157a8c); }
      .cd-link-name { font-weight: 700; color: var(--cd-ink); font-size: .9rem; text-decoration: none; }
      .cd-link-name:hover { color: #0F6D80; }
      .cd-sub { font-size: .76rem; color: var(--cd-muted); }
      .cd-num { width: 32px; height: 32px; border-radius: 9px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .8rem; color: #0F6D80; background: rgba(15,109,128,0.08); }
      .cd-icon-btn { width: 30px; height: 30px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; font-size: .74rem; color: #0F6D80; background: #fff; border: 1px solid rgba(15,109,128,0.16); transition: all .15s; text-decoration: none; flex-shrink: 0; }
      .cd-icon-btn:hover { background: #0F6D80; color: #fff; }
      .cd-icon-btn.gold { color: #a8781a; border-color: rgba(216,155,29,0.28); }
      .cd-icon-btn.gold:hover { background: #d89b1d; color: #fff; }
      .cd-icon-btn.red { color: #dc3545; border-color: rgba(220,53,69,0.2); }
      .cd-icon-btn.red:hover { background: #dc3545; color: #fff; }
      .cd-actions { display: flex; align-items: center; gap: 6px; flex-shrink: 0; }

      /* ===== Info grid ===== */
      .cd-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
      .cd-info-item { background: linear-gradient(135deg, rgba(15,109,128,0.04), rgba(15,109,128,0.015)); border: 1px solid rgba(15,109,128,0.08); border-radius: 10px; padding: 10px 12px; min-width: 0; }
      .cd-info-item.wide { grid-column: 1 / -1; }
      .cd-info-label { display: block; font-size: .66rem; color: var(--cd-muted); font-weight: 600; margin-bottom: 4px; }
      .cd-info-val { display: block; font-size: .82rem; color: var(--cd-ink); font-weight: 700; overflow-wrap: anywhere; }

      /* ===== Quick actions (horizontal) ===== */
      .cd-qa-bar { background: linear-gradient(135deg, rgba(15,109,128,0.06), rgba(216,155,29,0.05)); border: 1px solid rgba(15,109,128,0.09); border-radius: 16px; padding: 14px 18px; }
      .cd-qa-title { display: inline-flex; align-items: center; gap: 8px; font-size: .82rem; font-weight: 800; color: var(--cd-ink); margin: 0 0 10px; }
      .cd-qa-title i { width: 28px; height: 28px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #0F6D80, #157a8c); color: var(--cd-gold-light); font-size: .72rem; }
      .cd-qa { display: inline-flex; align-items: center; gap: 7px; padding: 8px 14px; border-radius: 10px; background: #fff; border: 1px solid rgba(15,109,128,0.14); color: #0F6D80; font-size: .78rem; font-weight: 600; text-decoration: none; transition: all .15s; cursor: pointer; }
      .cd-qa:hover { background: #0F6D80; color: #fff; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(15,109,128,0.22); }
      .cd-qa.red { color: #dc3545; border-color: rgba(220,53,69,0.22); background: #fff; }
      .cd-qa.red:hover { background: #dc3545; color: #fff; box-shadow: 0 6px 16px rgba(220,53,69,0.2); }

      .cd-empty { display: flex; flex-direction: column; align-items: center; gap: 10px; padding: 26px 0; color: #96a3a8; }
      .cd-empty i { font-size: 1rem; opacity: .4; }
      .cd-viewall { display: block; text-align: center; margin-top: 14px; color: #0F6D80; font-size: .84rem; font-weight: 700; text-decoration: none; }
      .cd-viewall:hover { color: #0a4a56; }
  </style>
@endpush

@section('content')
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
      <a href="{{ route('teacher.courses') }}" class="cd-back"><i class="fa-solid fa-arrow-right"></i>{{ __('messages.teacher_course_details_back') }}</a>
      <h1 class="cd-page-title"><i class="fa-solid fa-graduation-cap"></i>{{ __('messages.teacher_course_details_title') }}</h1>
    </div>
    <button class="cd-btn cd-btn-primary" onclick="showEditCourseModal()"><i class="fa-solid fa-pen"></i>{{ __('messages.teacher_courses_edit') }}</button>
  </div>

  {{-- ===== Hero ===== --}}
  <section class="cd-hero mb-4">
    <span class="cd-hero-deco" aria-hidden="true"></span>
    <div class="cd-hero-inner">
      <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-4">
        <div class="d-flex align-items-center gap-3 flex-grow-1">
          <span class="cd-hero-icon"><i class="fa-solid fa-book-open"></i></span>
          <div>
            <h2 class="cd-hero-name">{{ $course->name ?? __('messages.teacher_course_details_title') }}</h2>
            <div class="d-flex flex-wrap gap-2">
              @if($course->level)<span class="cd-pill"><i class="fa-solid fa-signal"></i>{{ $course->level }}</span>@endif
              <span class="cd-pill"><i class="fa-solid fa-circle-check"></i>{{ $course->is_active ? __('messages.teacher_course_details_active') : __('messages.teacher_course_details_completed') }}</span>
              @if($course->duration)<span class="cd-pill"><i class="fa-regular fa-clock"></i>{{ $course->duration }}</span>@endif
            </div>
            <div class="d-flex flex-wrap gap-2 mt-2" style="font-size:.8rem;color:rgba(255,255,255,.8);">
              @if($course->days)<span><i class="fa-regular fa-calendar ms-1"></i>{{ $course->days }}{{ $course->time_from ? ' · ' . $course->time_from . ' - ' . $course->time_to : '' }}</span>@endif
              @if($course->start_date)<span><i class="fa-regular fa-calendar-check ms-1"></i>{{ __('messages.teacher_course_details_start_date') }}: {{ $course->start_date }}</span>@endif
            </div>
          </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
          <div class="cd-hero-stat"><b>{{ $course->students_count }}</b><span>{{ __('messages.teacher_course_details_students') }}</span></div>
          <div class="cd-hero-stat"><b>{{ $course->sessions_count }}</b><span>{{ __('messages.teacher_course_details_sessions') }}</span></div>
          <div class="cd-hero-stat"><b>{{ $course->exams_count }}</b><span>{{ __('messages.teacher_course_details_exams') }}</span></div>
        </div>
      </div>
    </div>
  </section>

  {{-- ===== Quick actions (horizontal, under hero) ===== --}}
  <section class="cd-qa-bar mb-4">
    <div class="cd-qa-title"><i class="fa-solid fa-bolt"></i>{{ __('messages.teacher_course_details_quick_actions') }}</div>
    <div class="d-flex flex-wrap gap-2">
      <button class="cd-qa" onclick="showAddLessonModal();"><i class="fa-solid fa-book-open"></i>{{ __('messages.teacher_course_details_add_lesson') }}</button>
      <a class="cd-qa" href="{{ route('teacher.sessions') }}?course_id={{ $course->id }}&add=1"><i class="fa-solid fa-video"></i>{{ __('messages.teacher_course_details_add_session') }}</a>
      <a class="cd-qa" href="{{ route('teacher.exams') }}?course_id={{ $course->id }}&add=1"><i class="fa-solid fa-pen-to-square"></i>{{ __('messages.teacher_course_details_add_exam') }}</a>
      <button class="cd-qa" onclick="showAddStudentModal();"><i class="fa-solid fa-user-plus"></i>{{ __('messages.teacher_course_details_add_student') }}</button>
      <button class="cd-qa red" onclick="deleteCourse();"><i class="fa-solid fa-trash-can"></i>{{ __('messages.teacher_course_details_delete_course') }}</button>
    </div>
  </section>

  <div class="row g-4">
    {{-- ===== Side column ===== --}}
    <div class="col-12 col-lg-4 order-lg-1">
      <div class="cd-card">
        <div class="cd-card-head">
          <h6 class="cd-card-title"><i class="fa-solid fa-circle-info"></i>{{ __('messages.teacher_course_details_info') }}</h6>
        </div>
        <div class="cd-info-grid">
        @foreach([
          'name' => $course->name ?? '—',
          'level' => $course->level ?? '—',
          'duration' => $course->duration ?? '—',
          'days' => $course->days ?? '—',
          'time' => ($course->time_from ?? '') . ' - ' . ($course->time_to ?? ''),
          'start_date' => $course->start_date ?? '—',
          'status' => $course->is_active ? __('messages.teacher_course_details_active') : __('messages.teacher_course_details_completed'),
        ] as $key => $val)
        <div class="cd-info-item {{ $key === 'name' || $key === 'days' || $key === 'time' ? 'wide' : '' }}">
          <span class="cd-info-label">{{ __('messages.teacher_course_details_' . $key) }}</span>
          @if($key === 'status')
          <span class="cd-badge" style="font-size:.7rem;{{ $course->is_active ? 'background:linear-gradient(135deg,#0F6D80,#157a8c);color:#fff;' : 'background:rgba(216,155,29,0.15);color:#a8781a;' }}">{{ $val }}</span>
          @elseif($key === 'level')
          <span class="cd-badge" style="font-size:.7rem;background:rgba(15,109,128,0.12);color:#0F6D80;">{{ $val }}</span>
          @else
          <span class="cd-info-val">{{ $val }}</span>
          @endif
        </div>
        @endforeach
        </div>
      </div>
    </div>

    {{-- ===== Main column ===== --}}
    <div class="col-12 col-lg-8 order-lg-0">
      <div class="cd-tabs">
        <a class="cd-tab active" onclick="switchTab(event, 'studentsTab')"><i class="fa-solid fa-users"></i>{{ __('messages.teacher_course_details_students_tab') }}</a>
        <a class="cd-tab" onclick="switchTab(event, 'lessonsTab')"><i class="fa-solid fa-book-open"></i>{{ __('messages.teacher_course_details_lessons_tab') }}</a>
        <a class="cd-tab" onclick="switchTab(event, 'sessionsTab')"><i class="fa-solid fa-video"></i>{{ __('messages.teacher_course_details_sessions_tab') }}</a>
        <a class="cd-tab" onclick="switchTab(event, 'examsTab')"><i class="fa-solid fa-pen-to-square"></i>{{ __('messages.teacher_course_details_exams_tab') }}</a>
        <a class="cd-tab" onclick="switchTab(event, 'certsTab')"><i class="fa-solid fa-certificate"></i>{{ __('messages.teacher_course_details_certs_tab') }}</a>
      </div>

      <div class="tab-content show" id="studentsTab">
        <div class="cd-card">
          <div class="cd-card-head">
            <h6 class="cd-card-title"><i class="fa-solid fa-users"></i>{{ __('messages.teacher_course_details_registered_students') }} <span class="cd-count">{{ $course->students_count }}</span></h6>
            <button class="cd-btn cd-btn-ghost" onclick="showAddStudentModal();"><i class="fa-solid fa-plus"></i>{{ __('messages.teacher_course_details_add') }}</button>
          </div>
          <div>
            @forelse($course->students as $student)
            <div class="cd-row" id="student-row-{{ $student->id }}">
              <a href="{{ route('teacher.student-profile', $student->id) }}" class="cd-avatar">{{ mb_substr($student->name ?? '؟', 0, 1) }}</a>
              <div class="flex-grow-1">
                <a href="{{ route('teacher.student-profile', $student->id) }}" class="cd-link-name">{{ $student->name ?? __('messages.teacher_students_student') }}</a>
                <span class="cd-sub d-block" dir="ltr">{{ $student->phone ?? $student->email ?? '' }}</span>
              </div>
              <span class="cd-badge {{ ($student->status ?? 'active') === 'active' ? 'cd-badge-teal' : 'cd-badge-gold' }}">{{ ($student->status ?? 'active') === 'active' ? __('messages.teacher_course_details_active') : __('messages.teacher_students_status_pending') }}</span>
              <div class="cd-actions">
                @if($student->user_id && $student->user)
                <a href="{{ route('teacher.messages') }}?to={{ $student->user->id }}" class="cd-icon-btn gold" title="{{ __('messages.teacher_students_message') }}"><i class="fa-solid fa-envelope"></i></a>
                @else
                <span class="cd-icon-btn gold disabled opacity-50" title="{{ __('messages.teacher_students_no_account') }}"><i class="fa-solid fa-envelope"></i></span>
                @endif
                <button class="cd-icon-btn red" title="{{ __('messages.teacher_course_details_remove_student') }}" onclick="removeStudent({{ $student->id }}, '{{ addslashes($student->name ?? '') }}');"><i class="fa-solid fa-user-minus"></i></button>
              </div>
            </div>
            @empty
            <div class="cd-empty">
              <i class="fa-solid fa-user-graduate"></i>
              <p class="mb-0">{{ __('messages.teacher_dash_no_students') }}</p>
              <button class="cd-btn cd-btn-primary" onclick="showAddStudentModal();"><i class="fa-solid fa-user-plus"></i>{{ __('messages.teacher_course_details_add_student') }}</button>
            </div>
            @endforelse
          </div>
          @if($course->students_count)
          <a href="{{ route('teacher.students') }}" class="cd-viewall">{{ __('messages.teacher_course_details_view_all_students') }} <i class="fa-solid fa-arrow-left"></i></a>
          @endif
        </div>
      </div>

      <div class="tab-content" id="lessonsTab">
        <div class="cd-card">
          <div class="cd-card-head">
            <h6 class="cd-card-title"><i class="fa-solid fa-book-open"></i>{{ __('messages.teacher_course_details_lessons') }} <span class="cd-count">{{ $course->lessons->count() }}</span></h6>
            <button class="cd-btn cd-btn-ghost" onclick="showAddLessonModal();"><i class="fa-solid fa-plus"></i>{{ __('messages.teacher_course_details_add_lesson_btn') }}</button>
          </div>
          <div id="lessonsList">
            @forelse($course->lessons->sortBy('order') as $lesson)
            @php
              $lessonJson = $lesson->only(['name_ar', 'name_en', 'order', 'link']);
            @endphp
            <div class="cd-row" id="lesson-{{ $lesson->id }}">
              <span class="cd-num">{{ $lesson->order ?? $loop->iteration }}</span>
              <div class="flex-grow-1">
                <span class="cd-link-name">{{ $lesson->name }}</span>
                @if($lesson->name_en)<span class="cd-sub d-block">{{ $lesson->name_en }}</span>@endif
              </div>
              <div class="cd-actions">
                @if($lesson->link)
                <a href="{{ $lesson->link }}" target="_blank" rel="noopener" class="cd-icon-btn" title="{{ __('messages.teacher_lessons_open') }}"><i class="fa-solid fa-play"></i></a>
                @endif
                <button class="cd-icon-btn" title="{{ __('messages.teacher_courses_edit') }}" onclick="showEditLessonModal(this)" data-id="{{ $lesson->id }}" data-lesson='@json($lessonJson)'><i class="fa-solid fa-pen"></i></button>
                <button class="cd-icon-btn red" title="{{ __('messages.teacher_courses_delete') }}" onclick="deleteLesson({{ $lesson->id }})"><i class="fa-solid fa-trash-can"></i></button>
              </div>
            </div>
            @empty
            <div class="cd-empty">
              <i class="fa-solid fa-book-open"></i>
              <p class="mb-0">{{ __('messages.teacher_lessons_empty') }}</p>
            </div>
            @endforelse
          </div>
        </div>
      </div>

      <div class="tab-content" id="sessionsTab">
        <div class="cd-card">
          <div class="cd-card-head">
            <h6 class="cd-card-title"><i class="fa-solid fa-video"></i>{{ __('messages.teacher_course_details_course_sessions') }} <span class="cd-count">{{ $course->sessions_count }}</span></h6>
            <a href="{{ route('teacher.sessions') }}?course_id={{ $course->id }}&add=1" class="cd-btn cd-btn-ghost"><i class="fa-solid fa-plus"></i>{{ __('messages.teacher_course_details_add_session_btn') }}</a>
          </div>
          <div>
            @php
              $sessionBadges = ['completed' => 'cd-badge-teal', 'in_progress' => 'cd-badge-teal-soft', 'cancelled' => 'cd-badge-red', 'upcoming' => 'cd-badge-gold'];
              $sessionBadgeLabels = ['completed' => __('messages.teacher_schedule_badge_completed'), 'in_progress' => __('messages.teacher_schedule_badge_in_progress'), 'cancelled' => __('messages.teacher_schedule_badge_cancelled'), 'upcoming' => __('messages.teacher_schedule_badge_upcoming')];
            @endphp
            @forelse($course->sessions->sortByDesc('date') as $session)
            @php
              $sStatus = $session->status ?? 'upcoming';
            @endphp
            <div class="cd-row">
              <span class="cd-num"><i class="fa-solid fa-video"></i></span>
              <div class="flex-grow-1">
                <span class="cd-link-name">{{ $session->title }}</span>
                <span class="cd-sub d-block"><i class="fa-regular fa-clock me-1"></i>{{ $session->date }} · {{ \Carbon\Carbon::parse($session->time_from)->format('g:i A') }}{{ $session->time_to ? ' - ' . \Carbon\Carbon::parse($session->time_to)->format('g:i A') : '' }}</span>
              </div>
              <span class="cd-badge {{ $sessionBadges[$sStatus] ?? 'cd-badge-gold' }}">{{ $sessionBadgeLabels[$sStatus] ?? __('messages.teacher_schedule_badge_upcoming') }}</span>
            </div>
            @empty
            <div class="cd-empty">
              <i class="fa-solid fa-video"></i>
              <p class="mb-0">{{ __('messages.teacher_sessions_empty') }}</p>
            </div>
            @endforelse
          </div>
          @if($course->sessions_count)
          <a href="{{ route('teacher.sessions') }}" class="cd-viewall">{{ __('messages.teacher_course_details_view_all_sessions') }} <i class="fa-solid fa-arrow-left"></i></a>
          @endif
        </div>
      </div>

      <div class="tab-content" id="examsTab">
        <div class="cd-card">
          <div class="cd-card-head">
            <h6 class="cd-card-title"><i class="fa-solid fa-pen-to-square"></i>{{ __('messages.teacher_course_details_course_exams') }} <span class="cd-count">{{ $course->exams_count }}</span></h6>
            <a href="{{ route('teacher.exams') }}?course_id={{ $course->id }}&add=1" class="cd-btn cd-btn-ghost"><i class="fa-solid fa-plus"></i>{{ __('messages.teacher_course_details_add_exam_btn') }}</a>
          </div>
          <div>
            @forelse($course->exams->sortByDesc('date') as $exam)
            <div class="cd-row">
              <span class="cd-num"><i class="fa-solid fa-pen-to-square"></i></span>
              <div class="flex-grow-1">
                <a href="{{ route('teacher.exam-details', $exam->id) }}" class="cd-link-name">{{ $exam->title }}</a>
                <span class="cd-sub d-block">{{ $exam->date }} · {{ $exam->total_students ?? 0 }} {{ __('messages.teacher_course_details_students') }}</span>
              </div>
              <span class="cd-badge cd-badge-teal-soft" style="font-size:.85rem;">{{ $exam->avg_score ?? '—' }}%</span>
            </div>
            @empty
            <div class="cd-empty">
              <i class="fa-solid fa-pen-to-square"></i>
              <p class="mb-0">{{ __('messages.teacher_exams_empty') }}</p>
            </div>
            @endforelse
          </div>
          @if($course->exams_count)
          <a href="{{ route('teacher.exams') }}" class="cd-viewall">{{ __('messages.teacher_course_details_view_all_exams') }} <i class="fa-solid fa-arrow-left"></i></a>
          @endif
        </div>
      </div>

      <div class="tab-content" id="certsTab">
        <div class="cd-card">
          <div class="cd-card-head">
            <h6 class="cd-card-title"><i class="fa-solid fa-certificate"></i>{{ __('messages.teacher_course_details_issued_certs') }} <span class="cd-count">{{ $course->certificates_count }}</span></h6>
            <a href="{{ route('teacher.certificates') }}" class="cd-btn cd-btn-ghost"><i class="fa-solid fa-plus"></i>{{ __('messages.teacher_course_details_issue_cert_btn') }}</a>
          </div>
          <div>
            @forelse($course->certificates->sortByDesc('created_at') as $cert)
            <div class="cd-row">
              <span class="cd-num" style="color:#a8781a;background:rgba(216,155,29,0.12);"><i class="fa-solid fa-certificate"></i></span>
              <div class="flex-grow-1">
                <span class="cd-link-name">{{ $cert->title }}</span>
                <span class="cd-sub d-block">{{ $cert->student?->name ?? '—' }} · {{ $cert->issued_at ? \Carbon\Carbon::parse($cert->issued_at)->format('Y/m/d') : '' }}</span>
              </div>
              <span class="cd-badge {{ $cert->status === 'delivered' ? 'cd-badge-teal' : 'cd-badge-gold' }}">{{ $cert->status === 'delivered' ? __('messages.teacher_course_details_delivered') : __('messages.teacher_course_details_pending_issue') }}</span>
            </div>
            @empty
            <div class="cd-empty">
              <i class="fa-solid fa-certificate"></i>
              <p class="mb-0">{{ __('messages.teacher_certificates_empty') }}</p>
            </div>
            @endforelse
          </div>
          @if($course->certificates_count)
          <a href="{{ route('teacher.certificates') }}" class="cd-viewall">{{ __('messages.teacher_course_details_manage_certs') }} <i class="fa-solid fa-arrow-left"></i></a>
          @endif
        </div>
      </div>
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
function switchTab(event, tabId) {
  event.preventDefault();
  document.querySelectorAll('.cd-tab').forEach(function(t) { t.classList.remove('active'); });
  document.querySelectorAll('.tab-content').forEach(function(t) { t.classList.remove('show'); });
  event.currentTarget.classList.add('active');
  document.getElementById(tabId).classList.add('show');
}

@php
  $courseDataJson = $course->only(['id', 'name_ar', 'name_en', 'desc_ar', 'level', 'price', 'sessions_per_week', 'is_active', 'instructor_ar', 'audience', 'duration']);
  $enrollList = $enrollableStudents->map(fn ($st) => ['id' => $st->id, 'name' => $st->name_ar, 'phone' => $st->phone]);
@endphp
var courseData = @json($courseDataJson);
var enrollableStudents = @json($enrollList);

// ========== Edit Course (real) ==========
function esc(s) {
  return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}
function courseFormHtml(v) {
  v = v || {};
  function val(k) { return v[k] != null ? esc(v[k]) : ''; }
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
      '<div class="col-md-6"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_courses_add_name') }}</label><input name="name_ar" class="form-control teacher-input" value="' + val('name_ar') + '" required></div>' +
      '<div class="col-md-6"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_courses_add_name_en') }}</label><input name="name_en" class="form-control teacher-input" value="' + val('name_en') + '"></div>' +
      '<div class="col-md-6"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_courses_add_level') }}</label><select name="level" class="form-select teacher-input">' + levels + '</select></div>' +
      '<div class="col-md-6"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_courses_add_price') }}</label><input name="price" type="number" step="0.01" min="0" class="form-control teacher-input" value="' + val('price') + '"></div>' +
      '<div class="col-md-6"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_courses_add_sessions') }}</label><input name="sessions_per_week" type="number" class="form-control teacher-input" value="' + val('sessions_per_week') + '" min="1" max="7"></div>' +
      '<div class="col-md-6"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_courses_add_duration') }}</label><input name="duration" class="form-control teacher-input" value="' + val('duration') + '"></div>' +
      '<div class="col-md-6"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_courses_add_audience') }}</label><input name="audience" class="form-control teacher-input" value="' + val('audience') + '"></div>' +
      '<div class="col-md-6"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_courses_add_instructor') }}</label><input name="instructor_ar" class="form-control teacher-input" value="' + val('instructor_ar') + '"></div>' +
      '<div class="col-12"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_courses_add_desc') }}</label><textarea name="desc_ar" class="form-control teacher-input" rows="3">' + val('desc_ar') + '</textarea></div>' +
    '</div>' +
    '<div class="form-check mt-3"><input name="is_active" class="form-check-input" type="checkbox" value="1" id="activeCheck"' + checked + '><label class="form-check-label" for="activeCheck">{{ __('messages.teacher_courses_add_active') }}</label></div>' +
    '</form>';
}
function showEditCourseModal() {
  Swal.fire({
    title: '{{ __('messages.teacher_courses_edit_title') }}', html: courseFormHtml(courseData), width: '720px', showCancelButton: true, focusConfirm: false,
    confirmButtonColor: '#0F6D80', cancelButtonColor: '#6c757d',
    confirmButtonText: '<i class="fa-solid fa-check ml-1"></i>{{ __('messages.teacher_courses_save_changes') }}', cancelButtonText: '{{ __('messages.teacher_course_details_cancel') }}',
    preConfirm: function() {
      var form = document.getElementById('crudForm');
      var data = {};
      new FormData(form).forEach(function(v, k) { data[k] = v; });
      return fetch('{{ route("teacher.courses.update", $course->id) }}', {
        method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      }).then(function(r) {
        if (!r.ok) return r.json().then(function(e) { throw new Error(e.message || '{{ __('messages.teacher_courses_save_error') }}'); });
        return r.json();
      }).then(function(result) {
        if (result.status === 'saved') {
          Swal.fire({ icon: 'success', title: '{{ __('messages.teacher_sessions_ok') }}', text: '{{ __('messages.teacher_courses_updated') }}', confirmButtonColor: '#0F6D80', confirmButtonText: '{{ __('messages.teacher_sessions_ok') }}', timer: 1500 });
          setTimeout(function() { location.reload(); }, 1600);
        } else {
          throw new Error(result.message || '{{ __('messages.teacher_courses_error') }}');
        }
      }).catch(function(e) { Swal.showValidationMessage(e.message); });
    },
    allowOutsideClick: false
  });
}

// ========== Delete Course (real) ==========
function deleteCourse() {
  Swal.fire({
    title: '{{ __('messages.teacher_course_details_delete_course') }}', text: '{{ __('messages.teacher_course_details_delete_text') }}', icon: 'warning', showCancelButton: true,
    confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d',
    confirmButtonText: '{{ __('messages.teacher_course_details_delete_confirm') }}', cancelButtonText: '{{ __('messages.teacher_course_details_cancel') }}'
  }).then(function(r) {
    if (!r.isConfirmed) return;
    fetch('{{ route('teacher.courses.delete', $course->id) }}', {
      method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    }).then(function(res) {
      if (!res.ok) return res.json().then(function(e) { throw new Error(e.message || '{{ __('messages.teacher_courses_error') }}'); });
      return res.json();
    }).then(function(result) {
      if (result.status === 'deleted') {
        Swal.fire({ icon: 'success', title: '{{ __('messages.teacher_sessions_ok') }}', text: '{{ __('messages.teacher_course_details_course_deleted') }}', confirmButtonColor: '#0F6D80', timer: 1400 });
        setTimeout(function() { window.location.href = '{{ route('teacher.courses') }}'; }, 1500);
      }
    }).catch(function(e) { Swal.fire({ icon: 'error', title: '{{ __('messages.teacher_courses_error') }}', text: e.message, confirmButtonColor: '#0F6D80' }); });
  });
}

// ========== Add Student (real) ==========
function toggleStudentMode() {
  var isNew = document.getElementById('modeNew').checked;
  document.getElementById('newFields').style.display = isNew ? '' : 'none';
  document.getElementById('existingFields').style.display = isNew ? 'none' : '';
  document.getElementById('fieldStudent').disabled = !isNew;
  document.getElementById('fieldName').disabled = !isNew;
  document.getElementById('fieldPhone').disabled = !isNew;
}
function showAddStudentModal() {
  if (!enrollableStudents.length && !document.querySelector('[name="mode"]')) {
    // still allow creating a brand-new student even if list empty
  }
  var studentOptions = '<option value="">-- {{ __('messages.teacher_students_add_pick_student') }} --</option>';
  enrollableStudents.forEach(function(st) {
    studentOptions += '<option value="' + st.id + '">' + esc(st.name) + (st.phone ? ' — ' + st.phone : '') + '</option>';
  });
  var formHtml = '<form id="crudForm" class="text-end">' +
    '<div class="mb-3 d-flex justify-content-start gap-4">' +
    '<div class="form-check"><input class="form-check-input" type="radio" name="mode" id="modeNew" value="new" checked onchange="toggleStudentMode()"><label class="form-check-label small fw-medium" for="modeNew">{{ __('messages.teacher_students_add_mode_new') }}</label></div>' +
    '<div class="form-check"><input class="form-check-input" type="radio" name="mode" id="modeExisting" value="existing" onchange="toggleStudentMode()"><label class="form-check-label small fw-medium" for="modeExisting">{{ __('messages.teacher_students_add_mode_existing') }}</label></div>' +
    '</div>' +
    '<div id="newFields">' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_students_add_name_label') }}</label><input name="name_ar" id="fieldName" class="form-control teacher-input" placeholder="{{ __('messages.teacher_students_add_name_placeholder') }}"></div>' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_students_add_phone_label') }}</label><input name="phone" id="fieldPhone" class="form-control teacher-input" dir="ltr"></div>' +
    '</div>' +
    '<div id="existingFields" style="display:none;">' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_students_add_mode_existing') }}</label><select name="student_id" id="fieldStudent" class="form-select teacher-input" disabled>' + studentOptions + '</select></div>' +
    '</div>' +
    '</form>';
  Swal.fire({
    title: '{{ __('messages.teacher_course_details_add_student_modal_title') }}', html: formHtml, showCancelButton: true, focusConfirm: false,
    confirmButtonColor: '#0F6D80', cancelButtonColor: '#6c757d',
    confirmButtonText: '<i class="fa-solid fa-check ml-1"></i>{{ __('messages.teacher_course_details_add') }}', cancelButtonText: '{{ __('messages.teacher_course_details_cancel') }}',
    didOpen: function() { toggleStudentMode(); },
    preConfirm: function() {
      var form = document.getElementById('crudForm');
      var mode = form.querySelector('input[name="mode"]:checked').value;
      var data = { course_id: {{ $course->id }} };
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
      }).catch(function(e) { Swal.showValidationMessage(e.message); });
    },
    allowOutsideClick: false
  });
}
function removeStudent(studentId, name) {
  Swal.fire({
    title: '{{ __('messages.teacher_course_details_remove_student') }}',
    text: '{{ __('messages.teacher_course_details_remove_text') }}'.replace(':name', name),
    icon: 'warning', showCancelButton: true,
    confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d',
    confirmButtonText: '{{ __('messages.teacher_course_details_remove_confirm') }}', cancelButtonText: '{{ __('messages.teacher_course_details_cancel') }}'
  }).then(function(r) {
    if (!r.isConfirmed) return;
    fetch('{{ url('teacher/courses') }}/{{ $course->id }}/students/' + studentId, {
      method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    }).then(function(res) {
      if (!res.ok) return res.json().then(function(e) { throw new Error(e.message || '{{ __('messages.teacher_students_enroll_error') }}'); });
      return res.json();
    }).then(function(result) {
      if (result.status === 'detached') {
        var row = document.getElementById('student-row-' + studentId);
        if (row) row.remove();
        Swal.fire({ icon: 'success', title: '{{ __('messages.teacher_sessions_ok') }}', text: '{{ __('messages.teacher_course_details_student_removed') }}', confirmButtonColor: '#0F6D80', timer: 1300 });
      }
    }).catch(function(e) { Swal.fire({ icon: 'error', title: '{{ __('messages.teacher_courses_error') }}', text: e.message, confirmButtonColor: '#0F6D80' }); });
  });
}

// ========== Lessons ==========
function lessonEsc(s) {
  return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}
function lessonFormHtml(v) {
  v = v || {};
  function val(k) { return v[k] != null ? lessonEsc(v[k]) : ''; }
  return '<form id="lessonForm" class="text-end">' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_lessons_name') }}</label><input name="name_ar" class="form-control teacher-input" value="' + val('name_ar') + '" placeholder="{{ __('messages.teacher_lessons_name_placeholder') }}" required></div>' +
    '<div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_lessons_link') }}</label><input name="link" dir="ltr" class="form-control teacher-input text-start" value="' + val('link') + '" placeholder="{{ __('messages.teacher_lessons_link_placeholder') }}"></div>' +
    '</form>';
}
function submitLessonForm(url) {
  var form = document.getElementById('lessonForm');
  var data = {};
  new FormData(form).forEach(function(v, k) { data[k] = v; });
  data.course_id = {{ $course->id }};
  return fetch(url, {
    method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  }).then(function(r) {
    if (!r.ok) return r.json().then(function(e) { throw new Error(e.message || '{{ __('messages.teacher_courses_save_error') }}'); });
    return r.json();
  });
}
function showAddLessonModal() {
  Swal.fire({
    title: '{{ __('messages.teacher_lessons_add_title') }}', html: lessonFormHtml(null), width: '520px', showCancelButton: true, focusConfirm: false,
    confirmButtonColor: '#0F6D80', cancelButtonColor: '#6c757d',
    confirmButtonText: '<i class="fa-solid fa-check ml-1"></i>{{ __('messages.teacher_courses_save') }}', cancelButtonText: '{{ __('messages.teacher_courses_cancel') }}',
    preConfirm: function() {
      return submitLessonForm('{{ route("teacher.lessons.store") }}').then(function(result) {
        if (result.status === 'created') {
          Swal.fire({ icon: 'success', title: '{{ __('messages.teacher_sessions_ok') }}', text: '{{ __('messages.teacher_lessons_created') }}', confirmButtonColor: '#0F6D80', confirmButtonText: '{{ __('messages.teacher_sessions_ok') }}', timer: 1500 });
          setTimeout(function() { location.reload(); }, 1600);
        } else {
          throw new Error(result.message || '{{ __('messages.teacher_courses_error') }}');
        }
      }).catch(function(e) { Swal.showValidationMessage(e.message); });
    },
    allowOutsideClick: false
  });
}
function showEditLessonModal(btn) {
  var id = btn.getAttribute('data-id');
  var l = {};
  try { l = JSON.parse(btn.getAttribute('data-lesson')); } catch (e) { l = {}; }
  Swal.fire({
    title: '{{ __('messages.teacher_lessons_edit_title') }}', html: lessonFormHtml(l), width: '520px', showCancelButton: true, focusConfirm: false,
    confirmButtonColor: '#0F6D80', cancelButtonColor: '#6c757d',
    confirmButtonText: '<i class="fa-solid fa-check ml-1"></i>{{ __('messages.teacher_courses_save_changes') }}', cancelButtonText: '{{ __('messages.teacher_course_details_cancel') }}',
    preConfirm: function() {
      return submitLessonForm('{{ route("teacher.lessons.update", "PLACEHOLDER") }}'.replace('PLACEHOLDER', id)).then(function(result) {
        if (result.status === 'saved') {
          Swal.fire({ icon: 'success', title: '{{ __('messages.teacher_sessions_ok') }}', text: '{{ __('messages.teacher_lessons_updated') }}', confirmButtonColor: '#0F6D80', confirmButtonText: '{{ __('messages.teacher_sessions_ok') }}', timer: 1500 });
          setTimeout(function() { location.reload(); }, 1600);
        } else {
          throw new Error(result.message || '{{ __('messages.teacher_courses_error') }}');
        }
      }).catch(function(e) { Swal.showValidationMessage(e.message); });
    },
    allowOutsideClick: false
  });
}
function deleteLesson(id) {
  Swal.fire({
    title: '{{ __('messages.teacher_lessons_delete_title') }}', text: '{{ __('messages.teacher_lessons_delete_text') }}', icon: 'warning', showCancelButton: true,
    confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d',
    confirmButtonText: '{{ __('messages.teacher_lessons_delete_confirm') }}', cancelButtonText: '{{ __('messages.teacher_course_details_cancel') }}'
  }).then(function(r) {
    if (!r.isConfirmed) return;
    fetch('{{ route("teacher.lessons.delete", "PLACEHOLDER") }}'.replace('PLACEHOLDER', id), {
      method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    }).then(function(res) {
      if (!res.ok) return res.json().then(function(e) { throw new Error(e.message || '{{ __('messages.teacher_courses_error') }}'); });
      return res.json();
    }).then(function(result) {
      if (result.status === 'deleted') {
        Swal.fire({ icon: 'success', title: '{{ __('messages.teacher_lessons_delete_title') }}', text: '{{ __('messages.teacher_lessons_deleted') }}', confirmButtonColor: '#0F6D80', confirmButtonText: '{{ __('messages.teacher_sessions_ok') }}', timer: 1500 });
        var el = document.getElementById('lesson-' + id);
        if (el) el.remove();
      }
    }).catch(function(e) { Swal.fire({ icon: 'error', title: '{{ __('messages.teacher_courses_error') }}', text: e.message, confirmButtonColor: '#0F6D80' }); });
  });
}
</script>
@endpush

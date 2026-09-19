@extends('layouts.teacher')

@section('title', __('messages.teacher_reports') . ' | ' . __('messages.site_name'))
@section('meta_description', __('messages.teacher_reports_meta'))
@section('header_greeting', __('messages.teacher_header_greeting', ['name' => __('messages.teacher_female')]))
@section('page_title', __('messages.teacher_reports_title'))

@push('styles')
<style>
      :root { --rp-teal: #0F6D80; --rp-teal-dark: #0a4a56; --rp-teal-mid: #157a8c; --rp-gold: #d89b1d; --rp-ink: #1f2937; --rp-muted: #6b7a7e; }

      .td-page-title { font-size: 1.35rem; font-weight: 800; color: var(--rp-ink); margin: 0; display: flex; align-items: center; gap: 12px; }
      .td-page-title i { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.05rem; background: linear-gradient(135deg, rgba(15,109,128,0.1), rgba(15,109,128,0.04)); color: var(--rp-teal); }

      .btn-teacher-primary { background: linear-gradient(135deg, var(--rp-teal), var(--rp-teal-mid)); color: #fff; border: none; border-radius: 9px; font-weight: 600; font-size: .78rem; padding: 8px 16px; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
      .btn-teacher-primary:hover { filter: brightness(1.08); color: #fff; }
      .btn-teacher-outline { border: 1.5px solid rgba(15,109,128,0.18); color: var(--rp-teal); background: #fff; border-radius: 9px; font-weight: 600; font-size: .78rem; padding: 8px 16px; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
      .btn-teacher-outline:hover { background: rgba(15,109,128,0.05); border-color: var(--rp-teal); color: var(--rp-teal); }

      .td-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 14px; border-radius: 20px; font-size: .76rem; font-weight: 700; white-space: nowrap; }
      .td-badge-strong { background: linear-gradient(135deg, var(--rp-teal), var(--rp-teal-mid)); color: #fff; }
      .td-badge-mid { background: rgba(15, 109, 128, 0.12); color: var(--rp-teal); }
      .td-badge-soft { background: rgba(216, 155, 29, 0.14); color: #b57f0e; }
      .td-badge-sm { padding: 4px 12px; font-size: .7rem; }

      /* ===== Stats ===== */
      .td-stat { background: #fff; border-radius: 14px; border: 1px solid rgba(15,109,128,0.06); box-shadow: 0 3px 14px rgba(15,109,128,0.04); padding: 14px 16px; display: flex; align-items: center; gap: 12px; height: 100%; }
      .td-stat-icon { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.05rem; flex-shrink: 0; background: linear-gradient(135deg, rgba(15,109,128,0.12), rgba(15,109,128,0.05)); color: var(--rp-teal); }
      .td-stat-num { font-size: 1.25rem; font-weight: 800; color: var(--rp-ink); margin: 0; line-height: 1.1; }
      .td-stat-label { font-size: .74rem; font-weight: 600; color: var(--rp-muted); margin: 0; }

      /* ===== Sections ===== */
      .td-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 20px rgba(15,109,128,0.05); border: 1px solid rgba(15,109,128,0.06); overflow: hidden; }
      .td-section-title { display: flex; align-items: center; gap: 10px; font-weight: 800; font-size: .98rem; color: var(--rp-ink); margin: 0; padding: 16px 20px; border-bottom: 1px solid rgba(15,109,128,0.06); }
      .td-section-title i { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: .9rem; background: linear-gradient(135deg, rgba(15,109,128,0.1), rgba(15,109,128,0.04)); color: var(--rp-teal); }
      .td-table { width: 100%; border-collapse: collapse; }
      .td-table thead th { background: rgba(15,109,128,0.035); color: var(--rp-muted); font-size: .74rem; font-weight: 700; text-align: right; padding: 11px 18px; border-bottom: 1px solid rgba(15,109,128,0.08); white-space: nowrap; }
      .td-table tbody td { padding: 13px 18px; border-bottom: 1px solid rgba(15,109,128,0.05); vertical-align: middle; font-size: .86rem; color: var(--rp-ink); }
      .td-table tbody tr:last-child td { border-bottom: none; }
      .td-table tbody tr { transition: background .15s; }
      .td-table tbody tr:hover { background: rgba(15,109,128,0.02); }
      .td-cell-muted { color: var(--rp-muted); font-size: .84rem; }
      .td-cell-strong { font-weight: 800; color: var(--rp-teal); font-size: .95rem; }

      /* ===== Avatar ===== */
      .td-avatar { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: .8rem; color: #fff; flex-shrink: 0; }
      .td-avatar.c1 { background: linear-gradient(135deg, #0F6D80, #157a8c); }
      .td-avatar.c2 { background: linear-gradient(135deg, #0a4a56, #0F6D80); }
      .td-avatar.c3 { background: linear-gradient(135deg, #d89b1d, #b57f0e); }
      .td-avatar.c4 { background: linear-gradient(135deg, #157a8c, #4aa3b5); }

      .td-empty-cell { text-align: center; color: var(--rp-muted); padding: 28px 0 !important; }
  </style>
@endpush

@section('content')
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <h1 class="td-page-title"><i class="fa-solid fa-chart-pie"></i>{{ __('messages.teacher_reports_title') }}</h1>
    <a href="{{ route('teacher.reports.print') }}" target="_blank" class="btn-teacher-outline"><i class="fa-solid fa-print me-1"></i>{{ __('messages.teacher_reports_export') }}</a>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-user-graduate"></i></div>
        <div>
          <p class="td-stat-num">{{ $stats['students'] ?? 0 }}</p>
          <p class="td-stat-label">{{ __('messages.teacher_reports_total_students') }}</p>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-clapperboard"></i></div>
        <div>
          <p class="td-stat-num">{{ $stats['sessions'] ?? 0 }}</p>
          <p class="td-stat-label">{{ __('messages.teacher_reports_total_sessions') }}</p>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-pen-to-square"></i></div>
        <div>
          <p class="td-stat-num">{{ $stats['exams'] ?? 0 }}</p>
          <p class="td-stat-label">{{ __('messages.teacher_reports_total_exams') }}</p>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-percent"></i></div>
        <div>
          <p class="td-stat-num">{{ $stats['completion_rate'] ?? '0%' }}</p>
          <p class="td-stat-label">{{ __('messages.teacher_reports_completion_rate') }}</p>
        </div>
      </div>
    </div>
  </div>

  <div class="td-card mb-4">
    <h6 class="td-section-title"><i class="fa-solid fa-users"></i>{{ __('messages.teacher_reports_student_reports') }}</h6>
    <div class="table-responsive">
      <table class="td-table">
        <thead>
          <tr>
            <th>{{ __('messages.teacher_reports_table_student') }}</th>
            <th>{{ __('messages.teacher_reports_table_course') }}</th>
            <th>{{ __('messages.teacher_reports_table_attendance') }}</th>
            <th>{{ __('messages.teacher_reports_table_exam_avg') }}</th>
            <th>{{ __('messages.teacher_reports_table_certificates') }}</th>
            <th>{{ __('messages.teacher_reports_table_status') }}</th>
          </tr>
        </thead>
        <tbody>
          @forelse($students ?? [] as $s)
          @php
            $avg = $s->exams_avg_score ?? $s->examResults_avg_score ?? 0;
            $badge = $avg >= 85 ? 'td-badge-strong' : ($avg >= 70 ? 'td-badge-mid' : 'td-badge-soft');
            $label = $avg >= 85 ? __('messages.teacher_reports_excellent') : ($avg >= 70 ? __('messages.teacher_reports_very_good') : __('messages.teacher_reports_average'));
            $avatarClass = ['c1', 'c2', 'c3', 'c4'][(int) $s->id % 4];
          @endphp
          <tr>
            <td>
              <div class="d-flex align-items-center gap-2">
                <span class="td-avatar {{ $avatarClass }}">{{ mb_substr($s->user->name ?? '—', 0, 1) }}</span>
                <span class="fw-bold">{{ $s->user->name ?? __('messages.teacher_certificates_student') }}</span>
              </div>
            </td>
            <td class="td-cell-muted">{{ $s->teacher_course_titles ? $s->teacher_course_titles->implode('، ') : '—' }}</td>
            <td>{{ $s->attendance_rate ?? $s->sessions_attended ?? 0 }}%</td>
            <td class="td-cell-strong">{{ $avg }}%</td>
            <td>{{ $s->certificates_count ?? 0 }}</td>
            <td><span class="td-badge {{ $badge }} td-badge-sm">{{ $label }}</span></td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="td-empty-cell">{{ __('messages.teacher_dash_no_students') }}</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-12">
      <div class="td-card h-100">
        <h6 class="td-section-title"><i class="fa-solid fa-calendar-week"></i>{{ __('messages.teacher_reports_session_stats') }}</h6>
        <div class="table-responsive">
          <table class="td-table">
            <thead>
              <tr>
                <th>{{ __('messages.teacher_reports_table_month') }}</th>
                <th>{{ __('messages.teacher_reports_table_held') }}</th>
                <th>{{ __('messages.teacher_reports_table_attended') }}</th>
                <th>{{ __('messages.teacher_reports_table_absent') }}</th>
                <th>{{ __('messages.teacher_reports_table_attendance_rate') }}</th>
              </tr>
            </thead>
            <tbody>
              @forelse($sessionStats ?? [] as $ss)
              <tr>
                <td class="fw-bold">{{ $ss->month ?? '—' }}</td>
                <td>{{ $ss->total ?? 0 }}</td>
                <td>{{ $ss->attended ?? 0 }}</td>
                <td>{{ ($ss->total ?? 0) - ($ss->attended ?? 0) }}</td>
                <td class="td-cell-strong">{{ $ss->attendance_rate ?? 0 }}%</td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="td-empty-cell">{{ __('messages.teacher_reports_no_data') }}</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-12">
      <div class="td-card h-100">
        <h6 class="td-section-title"><i class="fa-solid fa-chart-bar"></i>{{ __('messages.teacher_reports_exam_performance') }}</h6>
        <div class="table-responsive">
          <table class="td-table">
            <thead>
              <tr>
                <th>{{ __('messages.teacher_reports_table_exam') }}</th>
                <th>{{ __('messages.teacher_reports_table_avg_score') }}</th>
                <th>{{ __('messages.teacher_reports_table_max_score') }}</th>
                <th>{{ __('messages.teacher_reports_table_min_score') }}</th>
              </tr>
            </thead>
            <tbody>
              @forelse($examStats ?? [] as $es)
              <tr>
                <td class="fw-bold">{{ $es->exam_title ?? $es->title ?? '—' }}</td>
                <td class="td-cell-strong">{{ $es->avg_score ?? 0 }}</td>
                <td>{{ $es->max_score ?? 0 }}</td>
                <td>{{ $es->min_score ?? 0 }}</td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="td-empty-cell">{{ __('messages.teacher_reports_no_data') }}</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
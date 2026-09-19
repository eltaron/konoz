<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ __('messages.teacher_report_print_title') }} - {{ __('messages.site_name') }}</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Segoe UI', Tahoma, Arial, sans-serif; color: #1f2937; background: #f3f4f6; padding: 24px; }
  .sheet { max-width: 900px; margin: 0 auto; background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); }
  .print-head { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px solid #0F6D80; padding-bottom: 18px; margin-bottom: 22px; }
  .brand { display: flex; align-items: center; gap: 12px; }
  .brand img { width: 52px; height: 52px; object-fit: contain; }
  .brand-name { font-size: 1.25rem; font-weight: 700; color: #0F6D80; }
  .brand-sub { font-size: 0.78rem; color: #6b7a7e; }
  .print-date { font-size: 0.8rem; color: #6b7a7e; text-align: left; }
  h1.report-title { font-size: 1.15rem; color: #0F6D80; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
  .report-meta { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px 24px; background: rgba(15,109,128,0.04); border-radius: 8px; padding: 14px 18px; margin-bottom: 22px; font-size: 0.88rem; }
  .report-meta b { color: #0F6D80; }
  h2.section-title { font-size: 0.98rem; color: #0F6D80; margin: 26px 0 10px; padding-bottom: 8px; border-bottom: 2px solid rgba(15,109,128,0.15); }
  h2.section-title:first-of-type { margin-top: 8px; }
  .stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 6px; }
  .stat-box { border: 1px solid rgba(15,109,128,0.14); border-radius: 10px; padding: 14px 12px; text-align: center; background: rgba(15,109,128,0.03); }
  .stat-box .num { font-size: 1.55rem; font-weight: 800; color: #0F6D80; line-height: 1.1; }
  .stat-box .lbl { font-size: 0.76rem; color: #6b7a7e; margin-top: 4px; }
  table.results { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
  table.results th, table.results td { border: 1px solid #d7e0e3; padding: 9px 12px; text-align: right; }
  table.results th { background: #0F6D80; color: #fff; font-weight: 600; white-space: nowrap; }
  table.results tr:nth-child(even) td { background: rgba(15,109,128,0.03); }
  .score-cell { font-weight: 700; color: #0F6D80; }
  .empty-state { text-align: center; color: #6b7a7e; padding: 26px 0; font-size: 0.9rem; }
  .sign-row { display: flex; justify-content: space-between; margin-top: 48px; font-size: 0.85rem; color: #374151; }
  .sign-row span { border-top: 1px dashed #9ca3af; padding-top: 6px; min-width: 160px; text-align: center; }
  .toolbar { max-width: 900px; margin: 0 auto 16px; display: flex; gap: 10px; }
  .toolbar a, .toolbar button { display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; border-radius: 8px; font-size: 0.9rem; font-weight: 600; cursor: pointer; text-decoration: none; border: none; font-family: inherit; }
  .btn-print { background: #0F6D80; color: #fff; }
  .btn-back { background: #fff; color: #0F6D80; border: 1.5px solid #0F6D80 !important; }
  @media print {
    body { background: #fff; padding: 0; }
    .sheet { box-shadow: none; border-radius: 0; max-width: none; padding: 12mm; }
    .toolbar { display: none; }
    table.results th { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    table.results tr:nth-child(even) td, .stat-box, .report-meta { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    @page { size: A4; margin: 10mm; }
  }
</style>
</head>
<body>
  <div class="toolbar">
    <button class="btn-print" onclick="window.print()"><i>&#128424;</i> {{ __('messages.teacher_report_print_btn') }}</button>
    <a class="btn-back" href="{{ route('teacher.reports') }}">{{ __('messages.teacher_reports') }}</a>
  </div>

  <div class="sheet" dir="rtl">
    <div class="print-head">
      <div class="brand">
        <img src="{{ asset('images/logo.png') }}" alt="">
        <div>
          <div class="brand-name">{{ __('messages.site_name') }}</div>
          <div class="brand-sub">{{ __('messages.teacher_report_print_subtitle') }}</div>
        </div>
      </div>
      <div class="print-date">
        {{ __('messages.teacher_report_print_date') }}<br>
        <b>{{ now()->format('Y/m/d') }}</b>
      </div>
    </div>

    <h1 class="report-title"><i>&#128200;</i> {{ __('messages.teacher_report_print_title') }}</h1>

    <div class="report-meta">
      <div><b>{{ __('messages.teacher_report_print_generated_for') }}:</b> {{ auth()->user()->name ?? '—' }}</div>
      <div><b>{{ __('messages.teacher_report_print_students_count') }}:</b> {{ $stats['students'] ?? 0 }}</div>
      <div><b>{{ __('messages.teacher_report_print_courses_count') }}:</b> {{ $stats['courses'] ?? 0 }}</div>
      <div><b>{{ __('messages.teacher_reports_total_exams') }}:</b> {{ $stats['exams'] ?? 0 }}</div>
    </div>

    <div class="stats-row">
      <div class="stat-box">
        <div class="num">{{ $stats['students'] ?? 0 }}</div>
        <div class="lbl">{{ __('messages.teacher_reports_total_students') }}</div>
      </div>
      <div class="stat-box">
        <div class="num">{{ $stats['sessions'] ?? 0 }}</div>
        <div class="lbl">{{ __('messages.teacher_reports_total_sessions') }}</div>
      </div>
      <div class="stat-box">
        <div class="num">{{ $stats['exams'] ?? 0 }}</div>
        <div class="lbl">{{ __('messages.teacher_reports_total_exams') }}</div>
      </div>
      <div class="stat-box">
        <div class="num">{{ $stats['completion_rate'] ?? '0%' }}</div>
        <div class="lbl">{{ __('messages.teacher_reports_completion_rate') }}</div>
      </div>
    </div>

    <h2 class="section-title">{{ __('messages.teacher_reports_student_reports') }}</h2>
    @if(count($students ?? []) > 0)
    <table class="results">
      <thead>
        <tr>
          <th style="width:38px;">#</th>
          <th>{{ __('messages.teacher_reports_table_student') }}</th>
          <th>{{ __('messages.teacher_reports_table_course') }}</th>
          <th>{{ __('messages.teacher_reports_table_attendance') }}</th>
          <th>{{ __('messages.teacher_reports_table_exam_avg') }}</th>
          <th>{{ __('messages.teacher_reports_table_certificates') }}</th>
          <th>{{ __('messages.teacher_reports_table_status') }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($students as $i => $s)
        @php
          $avg = $s->exams_avg_score ?? $s->examResults_avg_score ?? 0;
          $label = $avg >= 85 ? __('messages.teacher_reports_excellent') : ($avg >= 70 ? __('messages.teacher_reports_very_good') : __('messages.teacher_reports_average'));
        @endphp
        <tr>
          <td>{{ $i + 1 }}</td>
          <td>{{ $s->user->name ?? __('messages.teacher_certificates_student') }}</td>
          <td>{{ $s->teacher_course_titles ? $s->teacher_course_titles->implode('، ') : '—' }}</td>
          <td>{{ $s->attendance_rate ?? $s->sessions_attended ?? 0 }}%</td>
          <td class="score-cell">{{ $avg }}%</td>
          <td>{{ $s->certificates_count ?? 0 }}</td>
          <td>{{ $label }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @else
    <div class="empty-state">{{ __('messages.teacher_dash_no_students') }}</div>
    @endif

    <h2 class="section-title">{{ __('messages.teacher_reports_session_stats') }}</h2>
    @if(count($sessionStats ?? []) > 0)
    <table class="results">
      <thead>
        <tr>
          <th style="width:38px;">#</th>
          <th>{{ __('messages.teacher_reports_table_month') }}</th>
          <th>{{ __('messages.teacher_reports_table_held') }}</th>
          <th>{{ __('messages.teacher_reports_table_attended') }}</th>
          <th>{{ __('messages.teacher_reports_table_absent') }}</th>
          <th>{{ __('messages.teacher_reports_table_attendance_rate') }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($sessionStats as $i => $ss)
        <tr>
          <td>{{ $i + 1 }}</td>
          <td>{{ $ss->month ?? '—' }}</td>
          <td>{{ $ss->total ?? 0 }}</td>
          <td>{{ $ss->attended ?? 0 }}</td>
          <td>{{ ($ss->total ?? 0) - ($ss->attended ?? 0) }}</td>
          <td class="score-cell">{{ $ss->attendance_rate ?? 0 }}%</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @else
    <div class="empty-state">{{ __('messages.teacher_reports_no_data') }}</div>
    @endif

    <h2 class="section-title">{{ __('messages.teacher_reports_exam_performance') }}</h2>
    @if(count($examStats ?? []) > 0)
    <table class="results">
      <thead>
        <tr>
          <th style="width:38px;">#</th>
          <th>{{ __('messages.teacher_reports_table_exam') }}</th>
          <th>{{ __('messages.teacher_reports_table_avg_score') }}</th>
          <th>{{ __('messages.teacher_reports_table_max_score') }}</th>
          <th>{{ __('messages.teacher_reports_table_min_score') }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($examStats as $i => $es)
        <tr>
          <td>{{ $i + 1 }}</td>
          <td>{{ $es->exam_title ?? $es->title ?? '—' }}</td>
          <td class="score-cell">{{ $es->avg_score ?? 0 }}</td>
          <td>{{ $es->max_score ?? 0 }}</td>
          <td>{{ $es->min_score ?? 0 }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @else
    <div class="empty-state">{{ __('messages.teacher_reports_no_data') }}</div>
    @endif

    <div class="sign-row">
      <span>{{ __('messages.teacher_exam_results_sign_teacher') }}</span>
      <span>{{ __('messages.teacher_exam_results_sign_admin') }}</span>
    </div>
  </div>
</body>
</html>
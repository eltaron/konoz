<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ __('messages.teacher_exam_results_print_title') }} - {{ $exam->title_ar }}</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Segoe UI', Tahoma, Arial, sans-serif; color: #1f2937; background: #f3f4f6; padding: 24px; }
  .sheet { max-width: 800px; margin: 0 auto; background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); }
  .print-head { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px solid #0F6D80; padding-bottom: 18px; margin-bottom: 22px; }
  .brand { display: flex; align-items: center; gap: 12px; }
  .brand img { width: 52px; height: 52px; object-fit: contain; }
  .brand-name { font-size: 1.25rem; font-weight: 700; color: #0F6D80; }
  .brand-sub { font-size: 0.78rem; color: #6b7a7e; }
  .print-date { font-size: 0.8rem; color: #6b7a7e; text-align: left; }
  h1.report-title { font-size: 1.15rem; color: #0F6D80; margin-bottom: 14px; }
  .exam-meta { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px 24px; background: rgba(15,109,128,0.04); border-radius: 8px; padding: 14px 18px; margin-bottom: 20px; font-size: 0.88rem; }
  .exam-meta b { color: #0F6D80; }
  table.results { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
  table.results th, table.results td { border: 1px solid #d7e0e3; padding: 9px 12px; text-align: right; }
  table.results th { background: #0F6D80; color: #fff; font-weight: 600; }
  table.results tr:nth-child(even) td { background: rgba(15,109,128,0.03); }
  .score-cell { font-weight: 700; color: #0F6D80; }
  .empty-state { text-align: center; color: #6b7a7e; padding: 32px 0; font-size: 0.9rem; }
  .sign-row { display: flex; justify-content: space-between; margin-top: 48px; font-size: 0.85rem; color: #374151; }
  .sign-row span { border-top: 1px dashed #9ca3af; padding-top: 6px; min-width: 160px; text-align: center; }
  .toolbar { max-width: 800px; margin: 0 auto 16px; display: flex; gap: 10px; }
  .toolbar a, .toolbar button { display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; border-radius: 8px; font-size: 0.9rem; font-weight: 600; cursor: pointer; text-decoration: none; border: none; font-family: inherit; }
  .btn-print { background: #0F6D80; color: #fff; }
  .btn-back { background: #fff; color: #0F6D80; border: 1.5px solid #0F6D80 !important; }
  @media print {
    body { background: #fff; padding: 0; }
    .sheet { box-shadow: none; border-radius: 0; max-width: none; padding: 12mm; }
    .toolbar { display: none; }
    table.results th { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    table.results tr:nth-child(even) td, .exam-meta { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    @page { size: A4; margin: 10mm; }
  }
</style>
</head>
<body>
  <div class="toolbar">
    <button class="btn-print" onclick="window.print()"><i>&#128424;</i> {{ __('messages.teacher_exam_results_print_btn') }}</button>
    <a class="btn-back" href="{{ route('teacher.exam-results', $exam->id) }}">{{ __('messages.teacher_exam_results_back') }}</a>
  </div>

  <div class="sheet" dir="rtl">
    <div class="print-head">
      <div class="brand">
        <img src="{{ asset('images/logo.png') }}" alt="">
        <div>
          <div class="brand-name">{{ __('messages.site_name') }}</div>
          <div class="brand-sub">{{ __('messages.teacher_exam_results_print_subtitle') }}</div>
        </div>
      </div>
      <div class="print-date">
        {{ __('messages.teacher_exam_results_print_date') }}<br>
        <b>{{ now()->format('Y/m/d') }}</b>
      </div>
    </div>

    <h1 class="report-title">{{ __('messages.teacher_exam_results_print_title') }}</h1>

    <div class="exam-meta">
      <div><b>{{ __('messages.teacher_exam_results_table_exam') }}:</b> {{ $exam->title_ar }}</div>
      <div><b>{{ __('messages.teacher_exam_results_table_course') }}:</b> {{ $exam->course->name_ar ?? '—' }}</div>
      <div><b>{{ __('messages.teacher_exam_results_print_exam_date') }}:</b> {{ \Carbon\Carbon::parse($exam->date)->format('Y/m/d') }}</div>
      <div><b>{{ __('messages.teacher_exam_results_students') }}:</b> {{ $results->count() }}</div>
    </div>

    @if($results->count())
    <table class="results">
      <thead>
        <tr>
          <th style="width:40px;">#</th>
          <th>{{ __('messages.teacher_exam_results_table_student') }}</th>
          <th>{{ __('messages.teacher_exam_results_table_date') }}</th>
          <th>{{ __('messages.teacher_exam_results_print_correct') }}</th>
          <th>{{ __('messages.teacher_exam_results_table_score') }}</th>
          <th>{{ __('messages.teacher_exam_results_table_grade') }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($results as $i => $r)
        @php
          $gradeKey = $r->score >= 90 ? 'excellent' : ($r->score >= 80 ? 'very_good' : 'good');
        @endphp
        <tr>
          <td>{{ $i + 1 }}</td>
          <td>{{ $r->student->name_ar ?? '—' }}</td>
          <td>{{ $r->submitted_at?->format('Y/m/d') ?? '—' }}</td>
          <td>{{ $r->correct_count ?? '—' }} / {{ $r->total_questions ?? '—' }}</td>
          <td class="score-cell">{{ $r->score ?? '—' }}</td>
          <td>{{ __("messages.teacher_exam_results_grade_{$gradeKey}") }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @else
    <div class="empty-state">{{ __('messages.teacher_exam_results_empty') }}</div>
    @endif

    <div class="sign-row">
      <span>{{ __('messages.teacher_exam_results_sign_teacher') }}</span>
      <span>{{ __('messages.teacher_exam_results_sign_admin') }}</span>
    </div>
  </div>
</body>
</html>

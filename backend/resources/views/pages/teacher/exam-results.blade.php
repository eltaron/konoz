@extends('layouts.teacher')

@section('title', __('messages.teacher_exam_results') . ' | ' . __('messages.site_name'))
@section('meta_description', __('messages.teacher_exam_results_meta'))
@section('header_greeting', __('messages.teacher_header_greeting', ['name' => __('messages.teacher_female')]))
@section('page_title', __('messages.teacher_exam_results_title'))

@push('styles')
<style>
      :root { --er-teal: #0F6D80; --er-teal-dark: #0a4a56; --er-teal-mid: #157a8c; --er-gold: #d89b1d; --er-ink: #1f2937; --er-muted: #6b7a7e; }

      .td-page-title { font-size: 1.35rem; font-weight: 800; color: var(--er-ink); margin: 0; display: flex; align-items: center; gap: 12px; }
      .td-page-title i { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.05rem; background: linear-gradient(135deg, rgba(15,109,128,0.1), rgba(15,109,128,0.04)); color: var(--er-teal); }
      .td-back { display: inline-flex; align-items: center; gap: 8px; color: var(--er-teal); font-size: .84rem; font-weight: 600; text-decoration: none; margin-bottom: 6px; }
      .td-back:hover { color: var(--er-teal-dark); text-decoration: none; }

      .btn-teacher-primary { background: linear-gradient(135deg, var(--er-teal), var(--er-teal-mid)); color: #fff; border: none; border-radius: 9px; font-weight: 600; font-size: .78rem; padding: 7px 14px; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
      .btn-teacher-primary:hover { filter: brightness(1.08); color: #fff; text-decoration: none; }
      .btn-teacher-outline { border: 1.5px solid rgba(15,109,128,0.18); color: var(--er-teal); background: #fff; border-radius: 9px; font-weight: 600; font-size: .78rem; padding: 7px 14px; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
      .btn-teacher-outline:hover { background: rgba(15,109,128,0.05); border-color: var(--er-teal); color: var(--er-teal); }

      .td-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 14px; border-radius: 20px; font-size: .76rem; font-weight: 700; white-space: nowrap; }
      .td-badge-strong { background: linear-gradient(135deg, var(--er-teal), var(--er-teal-mid)); color: #fff; }
      .td-badge-mid { background: rgba(15, 109, 128, 0.12); color: var(--er-teal); }
      .td-badge-soft { background: rgba(216, 155, 29, 0.14); color: #b57f0e; }
      .td-badge-sm { padding: 4px 12px; font-size: .7rem; }
      .td-badge .grade-dot { width: 6px; height: 6px; border-radius: 50%; display: inline-block; }

      /* ===== Stats ===== */
      .td-stat { background: #fff; border-radius: 14px; border: 1px solid rgba(15,109,128,0.06); box-shadow: 0 3px 14px rgba(15,109,128,0.04); padding: 14px 16px; display: flex; align-items: center; gap: 12px; height: 100%; }
      .td-stat-icon { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.05rem; flex-shrink: 0; background: linear-gradient(135deg, rgba(15,109,128,0.12), rgba(15,109,128,0.05)); color: var(--er-teal); }
      .td-stat-num { font-size: 1.25rem; font-weight: 800; color: var(--er-ink); margin: 0; line-height: 1.1; }
      .td-stat-label { font-size: .74rem; font-weight: 600; color: var(--er-muted); margin: 0; }

      /* ===== Exam context chips ===== */
      .td-chip { display: inline-flex; align-items: center; gap: 6px; font-size: .74rem; font-weight: 700; color: var(--er-teal); background: rgba(15,109,128,0.08); border-radius: 20px; padding: 3px 11px; }
      .td-chip i { font-size: .72rem; }

      /* ===== Table ===== */
      .td-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 20px rgba(15,109,128,0.05); border: 1px solid rgba(15,109,128,0.06); overflow: hidden; }
      .td-table { width: 100%; border-collapse: collapse; }
      .td-table thead th { background: rgba(15,109,128,0.04); color: var(--er-muted); font-size: .74rem; font-weight: 700; text-align: right; padding: 12px 18px; border-bottom: 1px solid rgba(15,109,128,0.08); white-space: nowrap; }
      .td-table tbody td { padding: 14px 18px; border-bottom: 1px solid rgba(15,109,128,0.05); vertical-align: middle; }
      .td-table tbody tr:last-child td { border-bottom: none; }
      .td-table tbody tr { transition: background .15s; }
      .td-table tbody tr:hover { background: rgba(15,109,128,0.02); }
      .td-score { font-weight: 800; color: var(--er-teal); font-size: 1.05rem; }
      .td-score-max { font-size: .72rem; color: var(--er-muted); opacity: .75; font-weight: 600; }
      .td-cell-muted { color: var(--er-muted); font-size: .84rem; }
      .td-student-name { font-weight: 700; font-size: .9rem; color: var(--er-ink); }

      /* ===== Avatar ===== */
      .td-avatar { width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: .82rem; color: #fff; flex-shrink: 0; }
      .td-avatar.c1 { background: linear-gradient(135deg, #0F6D80, #157a8c); }
      .td-avatar.c2 { background: linear-gradient(135deg, #0a4a56, #0F6D80); }
      .td-avatar.c3 { background: linear-gradient(135deg, #d89b1d, #b57f0e); }
      .td-avatar.c4 { background: linear-gradient(135deg, #157a8c, #4aa3b5); }

      .td-empty-icon { font-size: 2.8rem; color: rgba(15,109,128,0.15); }
  </style>
@endpush

@section('content')
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
      <a href="{{ route('teacher.exams') }}" class="td-back"><i class="fa-solid fa-arrow-right me-1"></i>{{ __('messages.teacher_exam_results_back') }}</a>
      <h1 class="td-page-title mb-1"><i class="fa-solid fa-chart-simple"></i>{{ __('messages.teacher_exam_results_title') }}</h1>
      @if($exam)
      <div class="d-flex flex-wrap align-items-center gap-2 mt-1">
        <span class="fw-semibold" style="font-size:0.9rem;color:var(--er-teal);"><i class="fa-solid fa-file-pen me-1"></i>{{ $exam->title_ar }}</span>
        @if($exam->course)
        <span class="td-badge td-badge-mid td-badge-sm">{{ $exam->course->name_ar }}</span>
        @endif
        <span class="td-cell-muted">{{ \Carbon\Carbon::parse($exam->date)->format('Y/m/d') }}</span>
      </div>
      @endif
    </div>
    <a href="{{ route('teacher.exam-results.print', $exam->id) }}" target="_blank" class="btn-teacher-primary"><i class="fa-solid fa-download me-1"></i>{{ __('messages.teacher_exam_results_export') }}</a>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-pen-to-square"></i></div>
        <div>
          <p class="td-stat-num">{{ $stats['total'] ?? 0 }}</p>
          <p class="td-stat-label">{{ __('messages.teacher_exam_results_total') }}</p>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-circle-check"></i></div>
        <div>
          <p class="td-stat-num">{{ $stats['corrected'] ?? 0 }}</p>
          <p class="td-stat-label">{{ __('messages.teacher_exam_results_corrected') }}</p>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-hourglass-half"></i></div>
        <div>
          <p class="td-stat-num">{{ $stats['pending'] ?? 0 }}</p>
          <p class="td-stat-label">{{ __('messages.teacher_exam_results_pending') }}</p>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-users"></i></div>
        <div>
          <p class="td-stat-num">{{ $stats['students'] ?? 0 }}</p>
          <p class="td-stat-label">{{ __('messages.teacher_exam_results_students') }}</p>
        </div>
      </div>
    </div>
  </div>

  <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
    <span class="td-chip"><i class="fa-solid fa-arrow-down-wide-short"></i>{{ __('messages.teacher_exam_results_sorted_by') }}</span>
  </div>

  <div class="td-card">
    <div class="table-responsive">
      <table class="td-table">
        <thead>
          <tr>
            <th>{{ __('messages.teacher_exam_results_table_student') }}</th>
            <th>{{ __('messages.teacher_exam_results_table_date') }}</th>
            <th>{{ __('messages.teacher_exam_results_table_score') }}</th>
            <th>{{ __('messages.teacher_exam_results_table_grade') }}</th>
          </tr>
        </thead>
        <tbody>
          @forelse($results ?? [] as $r)
          @php
            $gradeKey = $r->score >= 90 ? 'excellent' : ($r->score >= 80 ? 'very_good' : 'good');
            $gradeMap = ['excellent'=>__('messages.teacher_exam_results_grade_excellent'),'very_good'=>__('messages.teacher_exam_results_grade_very_good'),'good'=>__('messages.teacher_exam_results_grade_good')];
            $gradeClass = $gradeKey === 'excellent' ? 'td-badge-strong' : ($gradeKey === 'very_good' ? 'td-badge-mid' : 'td-badge-soft');
            $gradeDotColor = $gradeKey === 'excellent' ? '#fff' : ($gradeKey === 'very_good' ? '#0F6D80' : '#f59e0b');
            $avatarClass = ['c1', 'c2', 'c3', 'c4'][(int) $r->student_id % 4];
          @endphp
          <tr>
            <td>
              <div class="d-flex align-items-center gap-2">
                <span class="td-avatar {{ $avatarClass }}">{{ mb_substr($r->student->name_ar ?? '—', 0, 1) }}</span>
                <span class="td-student-name">{{ $r->student->name_ar ?? '—' }}</span>
              </div>
            </td>
            <td class="td-cell-muted">{{ $r->submitted_at?->format('Y/m/d') ?? '—' }}</td>
            <td><span class="td-score">{{ $r->score ?? '—' }}</span><span class="td-score-max"> / {{ $r->total_questions * 10 ?? 100 }}</span></td>
            <td><span class="td-badge {{ $gradeClass }} td-badge-sm"><span class="grade-dot" style="background:{{ $gradeDotColor }};"></span>{{ $gradeMap[$gradeKey] }}</span></td>
          </tr>
          @empty
          <tr>
            <td colspan="4">
              <div class="text-center py-5">
                <i class="fa-regular fa-chart-bar td-empty-icon mb-3 d-block"></i>
                <p class="text-secondary mb-0">{{ __('messages.teacher_exam_results_empty') }}</p>
              </div>
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
@endpush
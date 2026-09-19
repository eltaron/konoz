@extends('layouts.teacher')

@section('title', __('messages.teacher_exam_details') . ' | ' . __('messages.site_name'))
@section('meta_description', __('messages.teacher_exam_details_meta'))
@section('header_greeting', __('messages.teacher_header_greeting', ['name' => __('messages.teacher_female')]))
@section('page_title', $exam->title ?? __('messages.teacher_exam_details'))

@push('styles')
<style>
      :root { --ed-teal: #0F6D80; --ed-teal-dark: #0a4a56; --ed-teal-mid: #157a8c; --ed-gold: #d89b1d; --ed-gold-light: #ffd166; --ed-ink: #1f2937; --ed-muted: #6b7a7e; }

      /* ===== Page header ===== */
      .ed-back { display: inline-flex; align-items: center; gap: 8px; color: var(--ed-teal); font-size: .84rem; font-weight: 600; text-decoration: none; margin-bottom: 6px; }
      .ed-back:hover { color: var(--ed-teal-dark); }
      .ed-page-title { font-size: 1.35rem; font-weight: 800; color: var(--ed-ink); margin: 0; display: flex; align-items: center; gap: 12px; }
      .ed-page-title i { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.05rem; background: linear-gradient(135deg, rgba(15,109,128,0.1), rgba(15,109,128,0.04)); color: var(--ed-teal); }

      /* ===== Buttons ===== */
      .btn-teacher-primary { background: linear-gradient(135deg, var(--ed-teal), var(--ed-teal-mid)); color: #fff; border: none; border-radius: 9px; font-weight: 600; font-size: .78rem; padding: 7px 14px; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
      .btn-teacher-primary:hover { filter: brightness(1.08); color: #fff; }
      .btn-teacher-outline { border: 1.5px solid rgba(15,109,128,0.18); color: var(--ed-teal); background: #fff; border-radius: 9px; font-weight: 600; font-size: .78rem; padding: 7px 14px; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
      .btn-teacher-outline:hover { background: rgba(15,109,128,0.05); border-color: var(--ed-teal); }

      /* ===== Badges ===== */
      .td-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 14px; border-radius: 20px; font-size: .76rem; font-weight: 700; white-space: nowrap; }
      .td-badge-strong { background: linear-gradient(135deg, var(--ed-teal), var(--ed-teal-mid)); color: #fff; }
      .td-badge-mid { background: rgba(15, 109, 128, 0.12); color: var(--ed-teal); }
      .td-badge-soft { background: rgba(216, 155, 29, 0.14); color: #b57f0e; }
      .td-badge-sm { padding: 3px 10px; font-size: .66rem; }

      /* ===== Cards ===== */
      .ed-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 20px rgba(15,109,128,0.05); border: 1px solid rgba(15,109,128,0.06); padding: 18px 20px; height: 100%; }

      /* ===== Info grid ===== */
      .ed-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
      .ed-info-item { background: linear-gradient(135deg, rgba(15,109,128,0.04), rgba(15,109,128,0.015)); border: 1px solid rgba(15,109,128,0.08); border-radius: 10px; padding: 10px 12px; min-width: 0; }
      .ed-info-item.wide { grid-column: 1 / -1; }
      .ed-info-label { display: block; font-size: .66rem; color: var(--ed-muted); font-weight: 600; margin-bottom: 4px; }
      .ed-info-val { display: block; font-size: .85rem; color: var(--ed-ink); font-weight: 700; overflow-wrap: anywhere; }

      /* ===== Score panel ===== */
      .ed-score-card { position: relative; overflow: hidden; border-radius: 16px; background: linear-gradient(135deg, var(--ed-teal-dark) 0%, var(--ed-teal) 55%, var(--ed-teal-mid) 100%); box-shadow: 0 12px 28px rgba(10,74,86,0.22); display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; min-height: 170px; }
      .ed-score-num { font-size: 2.6rem; font-weight: 800; color: var(--ed-gold-light); line-height: 1; }
      .ed-score-max { font-size: 1.1rem; color: rgba(255,255,255,0.7); font-weight: 600; }
      .ed-score-note { margin-top: 8px; font-size: .85rem; font-weight: 600; color: #fff; display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.18); padding: 4px 14px; border-radius: 20px; }
      .ed-score-note i { color: var(--ed-gold-light); }

      /* ===== Questions ===== */
      .question-card { background: #fff; border-radius: 14px; padding: 20px; border: 1px solid rgba(15,109,128,0.06); box-shadow: 0 2px 12px rgba(15,109,128,0.04); }
      .question-number { width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, var(--ed-teal), var(--ed-teal-mid)); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.8rem; flex-shrink: 0; }
      .q-option { padding: 10px 14px; border-radius: 10px; background: rgba(15,109,128,0.02); border: 1px solid rgba(15,109,128,0.06); font-size: 0.85rem; color: var(--ed-ink); }
      .q-option.correct { background: rgba(15,109,128,0.06); border-color: rgba(15,109,128,0.15); color: var(--ed-teal); font-weight: 600; }
      .q-option .fa-check { color: var(--ed-teal); }
      .add-q-btn { border-radius: 12px; border: 2px dashed rgba(15,109,128,0.2); background: transparent; color: var(--ed-teal); font-weight: 600; font-size: 0.85rem; padding: 12px; transition: all 0.2s; }
      .add-q-btn:hover { border-color: var(--ed-teal); background: rgba(15,109,128,0.03); }
      .td-count-chip { display: inline-flex; align-items: center; gap: 6px; font-size: .74rem; font-weight: 700; color: var(--ed-teal); background: rgba(15,109,128,0.08); border-radius: 20px; padding: 3px 11px; }

      .td-empty-icon { font-size: 2.8rem; color: rgba(15,109,128,0.15); }
  </style>
@endpush

@section('content')
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
      <a href="{{ route('teacher.exams') }}" class="ed-back"><i class="fa-solid fa-arrow-right me-1"></i>{{ __('messages.teacher_exam_details_back') }}</a>
      <h1 class="ed-page-title"><i class="fa-solid fa-pen-to-square"></i>{{ $exam->title ?? __('messages.teacher_exam_details') }}</h1>
    </div>
    <div class="d-flex gap-2 flex-wrap">
      <a href="{{ route('teacher.exam-results', $exam->id) }}" class="btn-teacher-outline text-decoration-none d-inline-flex align-items-center"><i class="fa-solid fa-chart-simple me-1"></i>{{ __('messages.teacher_exam_details_view_results') }}</a>
      <button class="btn-teacher-primary" onclick="showAddQuestionModal()"><i class="fa-solid fa-plus me-1"></i>{{ __('messages.teacher_exam_details_add_question') }}</button>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-12 col-md-8">
      <div class="ed-card">
        <h6 class="fw-bold mb-3" style="color:var(--ed-ink);"><i class="fa-regular fa-file-lines me-2" style="color:var(--ed-teal);"></i>{{ __('messages.teacher_exam_details_info') }}</h6>
        <div class="ed-info-grid">
          <div class="ed-info-item">
            <span class="ed-info-label">{{ __('messages.teacher_exam_details_course') }}</span>
            <span class="ed-info-val">{{ $exam->course->name ?? '—' }}</span>
          </div>
          <div class="ed-info-item">
            <span class="ed-info-label">{{ __('messages.teacher_exam_details_student') }}</span>
            <span class="ed-info-val">{{ $exam->student_name ?? '—' }}</span>
          </div>
          <div class="ed-info-item">
            <span class="ed-info-label">{{ __('messages.teacher_exam_details_date') }}</span>
            <span class="ed-info-val">{{ $exam->date ? \Carbon\Carbon::parse($exam->date)->format('Y-m-d') : '—' }}</span>
          </div>
          <div class="ed-info-item">
            <span class="ed-info-label">{{ __('messages.teacher_exam_details_status') }}</span>
            <span class="td-badge td-badge-strong">{{ __('messages.teacher_exams_status_corrected') }}</span>
          </div>
          <div class="ed-info-item">
            <span class="ed-info-label">{{ __('messages.teacher_exam_details_questions_count') }}</span>
            <span class="ed-info-val">{{ count($questions ?? []) }} {{ __('messages.teacher_exam_details_questions') }}</span>
          </div>
          <div class="ed-info-item">
            <span class="ed-info-label">{{ __('messages.teacher_exam_details_max_score') }}</span>
            <span class="ed-info-val">{{ $exam->max_score ?? 100 }}</span>
          </div>
          <div class="ed-info-item wide">
            <span class="ed-info-label">{{ __('messages.teacher_exam_details_student_score') }}</span>
            <span class="ed-info-val" style="color:var(--ed-teal);">{{ $exam->student_score ?? '—' }}</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="ed-score-card">
        <span class="ed-score-num">{{ $exam->student_score ?? '—' }}<span class="ed-score-max"> / {{ $exam->max_score ?? 100 }}</span></span>
        <span class="ed-score-note"><i class="fa-solid fa-star"></i>{{ __('messages.teacher_exam_details_excellent') }}</span>
      </div>
    </div>
  </div>

  <div class="d-flex align-items-center justify-content-between mb-3">
    <h5 class="fw-bold mb-0" style="color:var(--ed-ink);">{{ __('messages.teacher_exam_details_questions') }}</h5>
    <span class="td-count-chip"><i class="fa-solid fa-list-ol"></i>{{ __('messages.teacher_exam_details_total_questions', ['count' => count($questions ?? [])]) }}</span>
  </div>

  @forelse($questions ?? [] as $i => $q)
  <div class="question-card mb-3">
    <div class="d-flex align-items-start gap-3">
      <div class="question-number">{{ $i + 1 }}</div>
      <div class="flex-grow-1">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
          <span class="fw-semibold" style="color:var(--ed-ink);font-size:0.9rem;">{{ $q->question }}</span>
          <span class="td-badge td-badge-mid td-badge-sm">{{ __('messages.teacher_exam_details_points') }} {{ $i + 1 }}</span>
        </div>
        <div class="d-flex flex-column gap-1">
          @foreach(is_array($q->options) ? $q->options : [] as $oi => $opt)
          <div class="q-option @if((int) ($q->correct_answer ?? -1) === $oi) correct @endif">
            @if((int) ($q->correct_answer ?? -1) === $oi)<i class="fa-solid fa-check me-2"></i>@else<span class="me-2" style="display:inline-block;width:16px;"></span>@endif{{ is_string($opt) ? $opt : json_encode($opt, JSON_UNESCAPED_UNICODE) }}
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
  @empty
  <div class="text-center py-5">
    <i class="fa-regular fa-file-lines td-empty-icon mb-3 d-block"></i>
    <p class="text-secondary">{{ __('messages.teacher_exam_details_no_questions') }}</p>
  </div>
  @endforelse

  <button class="add-q-btn w-100" onclick="showAddQuestionModal()">
    <i class="fa-solid fa-plus me-2"></i>{{ __('messages.teacher_exam_details_add_question_modal') }}
  </button>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function showAddQuestionModal() {
  Swal.fire({
    title: '{{ __('messages.teacher_exam_details_add_question_modal') }}',
    html: '<div class="text-end">' +
      '<div class="mb-3"><input id="qText" class="teacher-input form-control" placeholder="{{ __('messages.teacher_exam_details_question_placeholder') }}" /></div>' +
      '<div class="mb-3"><select id="qCorrect" class="form-select teacher-input">' +
        '<option value="0">{{ __('messages.teacher_exam_details_option1_placeholder') }}</option>' +
        '<option value="1">{{ __('messages.teacher_exam_details_option2_placeholder') }}</option>' +
        '<option value="2">{{ __('messages.teacher_exam_details_option3_placeholder') }}</option>' +
        '<option value="3">{{ __('messages.teacher_exam_details_option4_placeholder') }}</option>' +
      '</select></div>' +
      '<div class="mb-2"><input id="qOpt1" class="teacher-input form-control" placeholder="{{ __('messages.teacher_exam_details_option1_placeholder') }}" style="border-color:rgba(15,109,128,0.35);" /></div>' +
      '<div class="mb-2"><input id="qOpt2" class="teacher-input form-control" placeholder="{{ __('messages.teacher_exam_details_option2_placeholder') }}" /></div>' +
      '<div class="mb-2"><input id="qOpt3" class="teacher-input form-control" placeholder="{{ __('messages.teacher_exam_details_option3_placeholder') }}" /></div>' +
      '<div class="mb-0"><input id="qOpt4" class="teacher-input form-control" placeholder="{{ __('messages.teacher_exam_details_option4_placeholder') }}" /></div>' +
      '</div>',
    confirmButtonText: '{{ __('messages.teacher_exam_details_add_btn') }}',
    confirmButtonColor: '#0F6D80',
    showCancelButton: true,
    cancelButtonText: '{{ __('messages.teacher_exam_details_cancel') }}',
    preConfirm: function() {
      var q = document.getElementById('qText').value.trim();
      if (!q) { Swal.showValidationMessage('{{ __('messages.teacher_exams_error') }}'); return false; }
      var opts = [1, 2, 3, 4].map(function(n) { return document.getElementById('qOpt' + n).value.trim(); });
      return fetch('{{ route("teacher.questions.store") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify({ exam_id: {{ $exam->id ?? 0 }}, question_ar: q, correct_answer: parseInt(document.getElementById('qCorrect').value, 10), options: opts })
      }).then(function(r) {
        return r.json().then(function(result) {
          if (!r.ok) throw new Error(result.message || '{{ __('messages.teacher_exams_error') }}');
          return result;
        });
      }).then(function(result) {
        if (result.status !== 'created') throw new Error('{{ __('messages.teacher_exams_error') }}');
        return result;
      }).then(function() {
        Swal.fire({ icon: 'success', title: '{{ __('messages.teacher_exam_details_added_title') }}', text: '{{ __('messages.teacher_exam_details_added') }}', confirmButtonColor: '#0F6D80' });
        setTimeout(function() { location.reload(); }, 1200);
      }).catch(function(e) { Swal.showValidationMessage(e.message); });
    }
  });
}
</script>
@endpush
@extends('layouts.student')

@section('title', __('messages.student_live_page_title'))

@section('meta_description', __('messages.student_live_subtitle'))

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4" data-aos="fade-up">
        <div>
          <h1 class="fw-bold mb-1" style="color: #0F6D80; font-size: 1.6rem;">{{ __('messages.student_live_page_title') }}</h1>
          <p class="text-secondary opacity-75 mb-0">{{ __('messages.student_live_subtitle') }}</p>
        </div>
      </div>

      <!-- Stats -->
      <div class="row g-3 mb-4" data-aos="fade-up">
        <div class="col-4 col-md-3">
          <div class="dash-card text-center">
            <h3 class="fw-bold mb-0" style="color: #0F6D80;" id="statTotal">{{ $sessions->count() }}</h3>
            <small class="text-secondary opacity-75">{{ __('messages.student_live_total') }}</small>
          </div>
        </div>
        <div class="col-4 col-md-3">
          <div class="dash-card text-center">
            <h3 class="fw-bold mb-0" style="color: #27ae60;" id="statAttended">{{ $attendance->count() }}</h3>
            <small class="text-secondary opacity-75">{{ __('messages.student_live_attended') }}</small>
          </div>
        </div>
        <div class="col-4 col-md-3">
          <div class="dash-card text-center">
            <h3 class="fw-bold mb-0" style="color: var(--pumpkin);" id="statPending">{{ $sessions->where('date', '>=', now()->toDateString())->count() }}</h3>
            <small class="text-secondary opacity-75">{{ __('messages.student_live_pending') }}</small>
          </div>
        </div>
        <div class="col-4 col-md-3">
          <div class="dash-card text-center">
            <h3 class="fw-bold mb-0" style="color: #8e44ad;" id="statQuizCount">{{ $sessions->filter(fn($s) => $s->quiz_data && isset($s->quiz_data['question']))->count() }}</h3>
            <small class="text-secondary opacity-75">{{ __('messages.student_live_quizzes') }}</small>
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="d-flex gap-2 mb-4 flex-wrap" data-aos="fade-up">
        <button class="btn rounded-3 px-3 py-1 small fw-medium live-filter active" data-filter="all" style="background:#0F6D80;color:#fff;border:none;">{{ __('messages.student_live_filter_all') }}</button>
        <button class="btn rounded-3 px-3 py-1 small fw-medium live-filter" data-filter="upcoming" style="background:rgba(15,109,128,0.06);color:#3f484b;border:1px solid rgba(15,109,128,0.1);">{{ __('messages.student_live_filter_upcoming') }}</button>
        <button class="btn rounded-3 px-3 py-1 small fw-medium live-filter" data-filter="attended" style="background:rgba(15,109,128,0.06);color:#3f484b;border:1px solid rgba(15,109,128,0.1);">{{ __('messages.student_live_filter_attended') }}</button>
        <button class="btn rounded-3 px-3 py-1 small fw-medium live-filter" data-filter="missed" style="background:rgba(15,109,128,0.06);color:#3f484b;border:1px solid rgba(15,109,128,0.1);">{{ __('messages.student_live_filter_missed') }}</button>
        <button class="btn rounded-3 px-3 py-1 small fw-medium live-filter" data-filter="quiz" style="background:rgba(15,109,128,0.06);color:#3f484b;border:1px solid rgba(15,109,128,0.1);">{{ __('messages.student_live_filter_quiz') }}</button>
      </div>

      <!-- Sessions List -->
      <div class="dash-card" data-aos="fade-up">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <h5 class="fw-bold mb-0" style="color: #0F6D80;"><i class="fa-solid fa-video me-2"></i>{{ __('messages.student_live_all_circles') }}</h5>
          <span class="small text-secondary opacity-75" id="sessionsCount"></span>
        </div>
        <div class="row g-3" id="sessionsList">
      @forelse($sessions as $session)
      @php
        $sStatus = $session->status ?? 'upcoming';
        $attended = isset($attendance[$session->id]);
        $hasQuiz = $session->quiz_data && isset($session->quiz_data['question']);
        $statusLabels = ['completed' => __('messages.student_live_status_completed'), 'upcoming' => __('messages.student_live_status_upcoming'), 'in_progress' => __('messages.student_live_status_in_progress'), 'cancelled' => __('messages.student_live_status_cancelled'), 'missed' => __('messages.student_live_status_missed')];
        $badgeMap = ['completed' => 'teacher-badge-success', 'upcoming' => 'teacher-badge-info', 'in_progress' => 'teacher-badge-warning', 'cancelled' => 'teacher-badge-danger', 'missed' => 'teacher-badge-danger'];
        $displayStatus = $attended ? 'completed' : $sStatus;
      @endphp
      <div class="col-md-6 col-lg-4 session-card-wrapper" data-status="{{ $attended ? 'attended' : ($sStatus === 'upcoming' ? 'upcoming' : 'missed') }}" data-has-quiz="{{ $hasQuiz ? '1' : '0' }}">
        <div class="session-card h-100 position-relative overflow-hidden">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <h6 class="fw-bold mb-1">{{ $session->title ?? $session->course->name ?? __('messages.student_schedule_session') }}</h6>
              <small class="text-secondary opacity-75">{{ $session->course->instructor_name ?? '' }}</small>
              <div class="mt-1">
                <span class="small text-secondary opacity-50 d-block">
                  <i class="fa-regular fa-clock ml-1"></i>
                  {{ $session->date ? \Carbon\Carbon::parse($session->date)->format('Y/m/d') : '' }}
                  {{ $session->time_from ? \Carbon\Carbon::parse($session->time_from)->format('g:i A') : '' }}
                </span>
              </div>
            </div>
            <span class="teacher-badge {{ $badgeMap[$displayStatus] ?? 'teacher-badge-info' }}" style="font-size:0.7rem;">
              {{ $statusLabels[$displayStatus] ?? __('messages.student_live_status_upcoming') }}
            </span>
          </div>
          @if($attended && isset($attendance[$session->id]) && $attendance[$session->id]->quiz_score !== null)
          <div class="mt-2 small">
            <span class="fw-bold {{ $attendance[$session->id]->quiz_score >= 75 ? 'text-success' : 'text-warning' }}">
              {{ __('messages.student_live_quiz_score') }}: {{ $attendance[$session->id]->quiz_score }}/{{ $attendance[$session->id]->quiz_total }}
            </span>
          </div>
          @endif
          <div class="mt-2 d-flex gap-2 flex-wrap">
            @if($session->stream_url)
            <button class="btn btn-sm" style="background:#27ae60;color:#fff;border-radius:8px;" onclick="joinLive({{ $session->id }}, '{{ addslashes($session->stream_url) }}')">
              <i class="fa-solid fa-video ml-1"></i>{{ __('messages.student_live_join') }}
            </button>
            @endif
            @if(!$attended && $sStatus === 'upcoming')
            <button class="btn btn-sm" style="background:#0F6D80;color:#fff;border-radius:8px;" onclick="markAttendance({{ $session->id }})">
              <i class="fa-solid fa-check ml-1"></i>{{ __('messages.student_live_mark_attendance') }}
            </button>
            @endif
            @if($attended)
            <button class="btn btn-sm" style="background:rgba(231,76,60,0.06);color:#e74c3c;border:1px solid rgba(231,76,60,0.1);border-radius:8px;" onclick="undoAttendance({{ $session->id }})">
              <i class="fa-solid fa-xmark ml-1"></i>{{ __('messages.student_live_undo') }}
            </button>
            @endif
            @if(($session->has_quiz ?? false) && $attended)
            <button class="btn btn-sm" style="background:rgba(15,109,128,0.06);color:#0F6D80;border:1px solid rgba(15,109,128,0.1);border-radius:8px;" onclick="showSessionQuiz({{ $session->id }})">
              <i class="fa-solid fa-pen-to-square ml-1"></i>{{ __('messages.student_live_take_quiz') }}
            </button>
            @endif
          </div>
        </div>
      </div>
      @empty
      <p class="text-center text-secondary opacity-75 my-5">{{ __('messages.student_live_no_sessions') }}</p>
      @endforelse
      </div>
      </div>

      <!-- Quiz Modal Area -->
      <div class="dash-card mt-4" style="display:none;" id="quizArea">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <h5 class="fw-bold mb-0" style="color: #0F6D80;" id="quizTitle"></h5>
          <button class="btn btn-sm p-1" style="color:#e74c3c;" onclick="closeQuiz()"><i class="fa-solid fa-xmark fs-5"></i></button>
        </div>
        <div id="quizBody"></div>
      </div>

      <form id="attendanceForm" method="POST" style="display:none;">@csrf</form>
      <form id="quizForm" method="POST" style="display:none;">@csrf</form>
@endsection

@push('scripts')
<script>
      const csrfToken = '{{ csrf_token() }}';
      let currentFilter = 'all';
      const sessionQuizzes = @json($sessionQuizzes);

      // Translation strings for JS
      const i18n = {
        showing: '{{ __("messages.student_live_showing") }}',
        of: '{{ __("messages.student_live_of") }}',
        noQuiz: '{{ __("messages.student_live_no_quiz") }}',
        quizCorrect: '{{ __("messages.student_live_quiz_correct") }}',
        quizWrong: '{{ __("messages.student_live_quiz_wrong") }}',
        attendanceSuccess: '{{ __("messages.student_live_attendance_success") }}',
        attendanceDone: '{{ __("messages.student_live_attendance_done") }}',
        attendanceOk: '{{ __("messages.student_live_attendance_ok") }}',
        undoTitle: '{{ __("messages.student_live_undo_title") }}',
        undoConfirm: '{{ __("messages.student_live_undo_confirm") }}',
        undoKeep: '{{ __("messages.student_live_undo_keep") }}',
      };

      function renderAll() {
        const cards = document.querySelectorAll('#sessionsList .session-card-wrapper');
        let visibleCount = 0;
        cards.forEach(card => {
          const status = card.dataset.status;
          const hasQuiz = card.dataset.hasQuiz === '1';
          let show = false;
          if (currentFilter === 'all') {
            show = true;
          } else if (currentFilter === 'upcoming') {
            show = status === 'upcoming';
          } else if (currentFilter === 'attended') {
            show = status === 'attended';
          } else if (currentFilter === 'missed') {
            show = status === 'missed';
          } else if (currentFilter === 'quiz') {
            show = hasQuiz;
          }
          card.style.display = show ? '' : 'none';
          if (show) visibleCount++;
        });
        document.getElementById('sessionsCount').textContent = i18n.showing + ' ' + visibleCount + ' ' + i18n.of + ' {{ $sessions->count() }}';
      }

      function markAttendance(id) {
        fetch('{{ route("student.live.attendance") }}', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
          body: JSON.stringify({ session_id: id })
        }).then(function(r) { return r.json(); }).then(function(d) {
          if (d.status === 'ok') {
            Swal.fire({ icon: 'success', title: i18n.attendanceSuccess + ' ✅', text: i18n.attendanceDone, confirmButtonColor: '#0F6D80', confirmButtonText: i18n.attendanceOk, timer: 1500, showConfirmButton: false });
            setTimeout(function() { location.reload(); }, 1500);
          }
        });
      }

      function undoAttendance(id) {
        Swal.fire({ title: i18n.undoTitle, icon: 'warning', showCancelButton: true, confirmButtonColor: '#e74c3c', confirmButtonText: i18n.undoConfirm, cancelButtonText: i18n.undoKeep, reverseButtons: true
        }).then(function(r) {
          if (r.isConfirmed) {
            fetch('{{ route("student.live.attendance") }}', {
              method: 'POST',
              headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
              body: JSON.stringify({ session_id: id, delete: true })
            }).then(function(r) { return r.json(); }).then(function(d) {
              location.reload();
            });
          }
        });
      }

      function showSessionQuiz(id) {
        const quiz = sessionQuizzes[id];
        if (!quiz) { Swal.fire({ icon: 'info', title: i18n.noQuiz, confirmButtonColor: '#0F6D80' }); return; }
        const area = document.getElementById('quizArea');
        area.style.display = 'block';
        document.getElementById('quizTitle').textContent = '📝 ' + quiz.title;
        document.getElementById('quizBody').innerHTML = `
          <p class="fw-bold mb-3" style="color:#0F6D80;">${quiz.question}</p>
          <div class="d-flex flex-column gap-2" id="quizOptions">${quiz.options.map(function(opt, i) { return \`
            <button class="btn w-100 text-start rounded-3 py-2 quiz-opt-btn" data-idx="\${i}" style="border:1.5px solid rgba(15,109,128,0.1);background:#f7fafb;" onclick="selectQuizOption(${id},\${i},${quiz.correct})">
              <span class="d-inline-flex align-items-center justify-content-center rounded-3 me-2" style="width:28px;height:28px;background:rgba(15,109,128,0.06);color:#0F6D80;font-weight:700;font-size:0.8rem;">\${String.fromCharCode(65+i)}</span> \${opt}
            </button>\`; }).join('')}</div>
          <div id="quizFeedback" class="mt-3"></div>`;
        area.scrollIntoView({ behavior: 'smooth' });
      }

      function selectQuizOption(sessionId, selected, correctIdx) {
        const btns = document.querySelectorAll('.quiz-opt-btn');
        btns.forEach(function(b, i) {
          b.style.borderColor = i === correctIdx ? '#27ae60' : (i === selected && selected !== correctIdx ? '#e74c3c' : 'rgba(15,109,128,0.1)');
          b.style.background = i === correctIdx ? 'rgba(39,174,96,0.06)' : (i === selected && selected !== correctIdx ? 'rgba(231,76,60,0.06)' : '#f7fafb');
        });
        const isCorrect = selected === correctIdx;
        const score = isCorrect ? 1 : 0;
        document.getElementById('quizFeedback').innerHTML = isCorrect
          ? '<div class="alert py-2 small fw-bold" style="background:rgba(39,174,96,0.1);color:#27ae60;border:none;border-radius:10px;">' + i18n.quizCorrect + '</div>'
          : '<div class="alert py-2 small fw-bold" style="background:rgba(231,76,60,0.08);color:#e74c3c;border:none;border-radius:10px;">' + i18n.quizWrong + '</div>';
        fetch('{{ route("student.live.quiz") }}', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
          body: JSON.stringify({ session_id: sessionId, score: score, total: 1, answers: [selected] })
        });
      }

      function closeQuiz() { document.getElementById('quizArea').style.display = 'none'; }

      function joinLive(sessionId, url) {
        fetch('{{ route("student.live.attendance") }}', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
          body: JSON.stringify({ session_id: sessionId })
        }).then(function(r) { return r.json(); }).then(function(d) {
          if (d.status === 'ok') {
            window.open(url, '_blank');
          }
        }).catch(function(e) { console.error(e); window.open(url, '_blank'); });
      }

      document.querySelectorAll('.live-filter').forEach(function(btn) {
        btn.addEventListener('click', function() {
          document.querySelectorAll('.live-filter').forEach(function(b) {
            b.style.background = 'rgba(15,109,128,0.06)'; b.style.color = '#3f484b'; b.style.border = '1px solid rgba(15,109,128,0.1)';
          });
          this.style.background = '#0F6D80'; this.style.color = '#fff'; this.style.border = 'none';
          currentFilter = this.dataset.filter;
          renderAll();
        });
      });

      renderAll();
      AOS.init({ duration: 600, once: true });
</script>
@endpush

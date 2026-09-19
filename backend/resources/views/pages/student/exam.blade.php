<!doctype html>
<html dir="{{ session('locale', 'ar') === 'ar' ? 'rtl' : 'ltr' }}" lang="{{ session('locale', 'ar') }}">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ __('messages.student_exam_page_title', ['site_name' => __('messages.site_name')]) }}</title>
    <meta name="description" content="{{ __('messages.student_exams_subtitle') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet" />
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    <style>
      body { font-family: 'Tajawal', sans-serif; background: #f7fafb; overflow-x: hidden; }
      .exam-container { max-width: 900px; margin: 0 auto; padding: 100px 16px 40px; }
      .q-nav-btn { width: 40px; height: 40px; border-radius: 10px; border: 1.5px solid rgba(15,109,128,0.08); background: transparent; font-size: 0.8rem; font-weight: 500; transition: all 0.2s; }
      .q-nav-btn.answered { background: #0F6D80; color: #fff; border-color: #0F6D80; }
      .q-nav-btn.current { border-color: #0F6D80; box-shadow: 0 0 0 3px rgba(15,109,128,0.1); font-weight: 700; }
      .q-nav-btn:hover { border-color: #0F6D80; }
      .option-item { padding: 12px 16px; border-radius: 12px; border: 1.5px solid rgba(15,109,128,0.06); cursor: pointer; transition: all 0.2s; background: #fff; }
      .option-item:hover { border-color: rgba(15,109,128,0.2); background: rgba(15,109,128,0.02); }
      .option-item.selected { border-color: #0F6D80; background: rgba(15,109,128,0.04); }
      .option-item input[type="radio"] { display: none; }
      .option-item .radio-circle { width: 20px; height: 20px; min-width: 20px; border-radius: 50%; border: 2px solid rgba(15,109,128,0.15); display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; }
      .option-item.selected .radio-circle { border-color: #0F6D80; background: #0F6D80; color: #fff; }
      .exam-timer { background: rgba(231,76,60,0.06); color: #e74c3c; padding: 8px 16px; border-radius: 10px; font-weight: 700; }
    </style>
  </head>
  <body>
    <div class="exam-container">
      <div class="d-flex align-items-center justify-content-between mb-4">
        <a href="{{ route('student.exams') }}" class="btn rounded-3 px-3 py-2" style="border: 1.5px solid rgba(15,109,128,0.08);"><i class="fa-solid fa-arrow-right me-1"></i>{{ __('messages.student_exam_back') }}</a>
        <div class="d-flex align-items-center gap-3">
          <h2 id="examTitle" class="small text-secondary opacity-75">{{ $exam->title ?? __('messages.student_exam') }}</h2>
          <span class="exam-timer small"><i class="fa-regular fa-clock me-1"></i><span id="timerDisplay">45:00</span></span>
        </div>
      </div>

      <div class="d-flex align-items-center gap-2 mb-3">
        <div class="flex-grow-1" style="height: 6px; background: #e0e3e4; border-radius: 10px; overflow: hidden;">
          <div id="progressBar" class="h-100" style="width: 0%; background: #0F6D80; border-radius: 10px; transition: width 0.5s;"></div>
        </div>
        <span class="small fw-bold" style="color: #0F6D80;"><span id="qCounter">1</span>/<span id="qTotal">30</span></span>
      </div>

      <div class="d-flex flex-wrap gap-2 mb-4" id="qNav"></div>

      <div class="card border-0 shadow-sm rounded-4 p-4 mb-3">
        <p class="small text-secondary opacity-50 mb-2">{{ __('messages.student_exam_question') }} <span id="qNumDisplay">1</span></p>
        <h5 class="fw-bold mb-4" id="qText" style="color: #0F6D80;"></h5>
        <div class="d-flex flex-column gap-2" id="qOptions"></div>
      </div>

      <div class="d-flex align-items-center justify-content-between gap-3">
        <button class="btn rounded-3 px-4 py-2" id="prevBtn" style="border: 1.5px solid rgba(15,109,128,0.08);" onclick="prevQ()"><i class="fa-solid fa-chevron-right me-1"></i>{{ __('messages.student_exam_previous') }}</button>
        <button class="btn rounded-3 px-4 py-2" id="nextBtn" style="background: #0F6D80; color: #fff;" onclick="nextQ()">{{ __('messages.student_exam_next') }}<i class="fa-solid fa-chevron-left ml-1"></i></button>
        <button class="btn rounded-3 px-4 py-2 d-none" id="submitBtn" style="background: #27ae60; color: #fff;" onclick="submitExam()"><i class="fa-solid fa-check ml-1"></i>{{ __('messages.student_exam_submit') }}</button>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      const examsData = @json($examsData);
      const questions = (examsData[0]?.questions ?? []);
      const examId = examsData[0]?.id ?? null;
      const csrfToken = '{{ csrf_token() }}';
      const locale = '{{ session('locale', 'ar') }}';

      // Translation strings for JS
      const i18n = {
        examName: '{{ __('messages.student_exam') }}',
        unansweredWarning: '{{ __('messages.student_exam_unanswered_warning') }}',
        unansweredText: function(count) { return '{{ __('messages.student_exam_unanswered_text', ['count' => 'CNT']) }}'.replace('CNT', count); },
        confirmSubmit: '{{ __('messages.student_exam_confirm_submit') }}',
        review: '{{ __('messages.student_exam_review') }}',
        gradeExcellent: '{{ __('messages.student_exam_grade_excellent') }}',
        gradeVeryGood: '{{ __('messages.student_exam_grade_very_good') }}',
        gradeGood: '{{ __('messages.student_exam_grade_good') }}',
        gradeAcceptable: '{{ __('messages.student_exam_grade_acceptable') }}',
        gradeWeak: '{{ __('messages.student_exam_grade_weak') }}',
        correctAnswers: '{{ __('messages.student_exam_correct') }}',
        showDetails: '{{ __('messages.student_exam_show_details') }}',
        detailsTitle: '{{ __('messages.student_exam_details_title') }}',
        detailsOk: '{{ __('messages.student_exam_details_ok') }}',
        correctPrefix: '{{ __('messages.student_exam_correct_prefix') }}',
        wrongPrefix: '{{ __('messages.student_exam_wrong_prefix') }}',
        noQuestions: '{{ __('messages.student_exam_no_questions') }}',
        readyTitle: '{{ __('messages.student_exam_ready') }}',
        readyText: '{{ __('messages.student_exam_ready_text') }}',
        start: '{{ __('messages.student_exam_start') }}',
        later: '{{ __('messages.student_exam_later') }}',
        started: '{{ __('messages.student_exam_started') }}',
        emojiExcellent: '{{ __('messages.student_exam_grade_excellent_emoji') }}',
        emojiVeryGood: '{{ __('messages.student_exam_grade_very_good_emoji') }}',
        emojiGood: '{{ __('messages.student_exam_grade_good_emoji') }}',
        emojiAcceptable: '{{ __('messages.student_exam_grade_acceptable_emoji') }}',
        questionS: '{{ __('messages.student_exam_question_s') }}',
      };

      let answers = new Array(questions.length).fill(null);
      let currentQ = 0;
      let totalSeconds = 45 * 60;
      let timerInterval = null;

      function renderNav() {
        const nav = document.getElementById('qNav');
        nav.innerHTML = questions.map((_, i) => `
          <button class="q-nav-btn ${answers[i] !== null ? 'answered' : ''} ${i === currentQ ? 'current' : ''}" onclick="goQ(${i})">${i + 1}</button>
        `).join('');
      }

      function renderQ() {
        const q = questions[currentQ];
        document.getElementById('qNumDisplay').textContent = currentQ + 1;
        document.getElementById('qText').textContent = q.q;
        document.getElementById('qCounter').textContent = currentQ + 1;
        document.getElementById('qTotal').textContent = questions.length;

        const opts = document.getElementById('qOptions');
        opts.innerHTML = q.opts.map((o, i) => o ? `
          <label class="option-item d-flex align-items-center gap-3 ${answers[currentQ] === i ? 'selected' : ''}">
            <input type="radio" name="q" value="${i}" ${answers[currentQ] === i ? 'checked' : ''} onchange="selectAns(${i})" />
            <span class="radio-circle">${answers[currentQ] === i ? '<i class="fa-solid fa-check" style="font-size: 0.6rem;"></i>' : ''}</span>
            <span>${o}</span>
          </label>
        ` : '').join('');

        const progress = ((currentQ + 1) / questions.length) * 100;
        document.getElementById('progressBar').style.width = progress + '%';

        document.getElementById('prevBtn').style.visibility = currentQ === 0 ? 'hidden' : 'visible';
        const isLast = currentQ === questions.length - 1;
        document.getElementById('nextBtn').classList.toggle('d-none', isLast);
        document.getElementById('submitBtn').classList.toggle('d-none', !isLast);

        renderNav();
      }

      function selectAns(i) {
        answers[currentQ] = i;
        document.querySelectorAll('.option-item').forEach((el, idx) => {
          el.classList.toggle('selected', idx === i);
          const circle = el.querySelector('.radio-circle');
          circle.innerHTML = idx === i ? '<i class="fa-solid fa-check" style="font-size: 0.6rem;"></i>' : '';
        });
        renderNav();
      }

      function nextQ() { if (currentQ < questions.length - 1) { currentQ++; renderQ(); } }
      function prevQ() { if (currentQ > 0) { currentQ--; renderQ(); } }
      function goQ(i) { currentQ = i; renderQ(); }

      function startTimer() {
        timerInterval = setInterval(() => {
          totalSeconds--;
          const m = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
          const s = String(totalSeconds % 60).padStart(2, '0');
          document.getElementById('timerDisplay').textContent = m + ':' + s;
          if (totalSeconds <= 300) document.querySelector('.exam-timer').style.background = 'rgba(231,76,60,0.12)';
          if (totalSeconds <= 0) { clearInterval(timerInterval); submitExam(); }
        }, 1000);
      }

      function submitExam() {
        clearInterval(timerInterval);
        const answered = answers.filter(a => a !== null).length;
        const unanswered = questions.length - answered;
        if (unanswered > 0) {
          Swal.fire({
            icon: 'warning',
            title: i18n.unansweredWarning,
            text: i18n.unansweredText(unanswered),
            showCancelButton: true,
            confirmButtonColor: '#0F6D80',
            confirmButtonText: i18n.confirmSubmit,
            cancelButtonText: i18n.review
          }).then(r => { if (r.isConfirmed) calcResult(); });
        } else { calcResult(); }
      }

      function calcResult() {
        const correct = questions.filter((q, i) => answers[i] === q.ans).length;
        const total = questions.length;
        const pct = Math.round((correct / total) * 100);
        let grade = pct >= 90 ? i18n.gradeExcellent : pct >= 75 ? i18n.gradeVeryGood : pct >= 60 ? i18n.gradeGood : pct >= 50 ? i18n.gradeAcceptable : i18n.gradeWeak;
        let icon = pct >= 75 ? 'success' : pct >= 50 ? 'warning' : 'error';
        let emoji = pct >= 90 ? i18n.emojiExcellent : pct >= 75 ? i18n.emojiVeryGood : pct >= 60 ? i18n.emojiGood : i18n.emojiAcceptable;
        Swal.fire({
          icon, title: emoji + ' ' + i18n.examName + ': ' + grade,
          html: '<div style="text-align: center;">' +
            '<div style="font-size: 3rem; font-weight: 800; color: ' + (pct >= 75 ? '#0F6D80' : pct >= 50 ? '#e67e22' : '#e74c3c') + ';">' + pct + '%</div>' +
            '<p>' + correct + ' / ' + total + ' ' + i18n.correctAnswers + '</p>' +
            '<div style="height: 8px; background: #e0e3e4; border-radius: 10px; overflow: hidden; margin: 8px 0;">' +
              '<div style="width: ' + pct + '%; height: 100%; background: ' + (pct >= 75 ? '#0F6D80' : pct >= 50 ? '#e67e22' : '#e74c3c') + '; border-radius: 10px;"></div>' +
            '</div>' +
          '</div>',
          confirmButtonColor: '#0F6D80',
          confirmButtonText: i18n.showDetails
        }).then(() => {
          showDetails(correct, total, pct);
        });
        saveResult(examId, answers, pct, correct, total);
      }

      function saveResult(eId, ans, score, correct, total) {
        fetch('{{ route("student.exam.submit", "EXAM_ID") }}'.replace('EXAM_ID', eId), {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
          body: JSON.stringify({ answers: ans, score: score, correct_count: correct, total_questions: total })
        }).catch(function(e) { console.error('Save error:', e); });
      }

      function showDetails(correct, total, pct) {
        let html = '<div class="text-end" style="max-height: 400px; overflow-y: auto;">';
        questions.forEach((q, i) => {
          const isCorrect = answers[i] === q.ans;
          html += '<div style="padding: 8px; border-radius: 8px; background: ' + (isCorrect ? 'rgba(39,174,96,0.04)' : 'rgba(231,76,60,0.04)') + '; margin-bottom: 6px; border-right: 3px solid ' + (isCorrect ? '#27ae60' : '#e74c3c') + ';">';
          html += '<small><strong>' + i18n.questionS + (i+1) + ':</strong> ' + q.q + '</small><br />';
          html += '<small>' + (isCorrect ? i18n.correctPrefix : i18n.wrongPrefix + ' ' + (q.opts[q.ans] || '')) + '</small>';
          html += '</div>';
        });
        html += '</div>';
        Swal.fire({ title: i18n.detailsTitle, html: html, confirmButtonColor: '#0F6D80', confirmButtonText: i18n.detailsOk });
      }

      if (questions.length === 0) {
        document.querySelector('.exam-container').innerHTML = '<div class="text-center py-5"><i class="fa-solid fa-pen-to-square fs-1 text-secondary opacity-25 mb-3 d-block"></i><p class="text-secondary">' + i18n.noQuestions + '</p><a href="{{ route("student.exams") }}" class="btn" style="background:#0F6D80;color:#fff;">' + i18n.examName + '</a></div>';
      } else {
        renderQ();
        renderNav();
        startTimer();
      }
    </script>
  </body>
</html>

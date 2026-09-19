@extends('layouts.student')

@section('title', __('messages.student_exams_title'))

@section('meta_description', __('messages.student_exams_subtitle'))

@section('content')
<h1 class="fw-bold mb-1" style="color: #0F6D80; font-size: 1.6rem;" data-aos="fade-up">{{ __('messages.student_exams_title') }}</h1>
      <p class="text-secondary opacity-75 mb-4" data-aos="fade-up">{{ __('messages.student_exams_subtitle') }}</p>

      <!-- Stats -->
      <div class="row g-3 mb-4" data-aos="fade-up">
        <div class="col-4 col-md-3">
          <div class="dash-card text-center">
            <h3 class="fw-bold mb-0" style="color: #0F6D80;">{{ $stats->total ?? 0 }}</h3>
            <small class="text-secondary opacity-75">{{ __('messages.student_exams_total') }}</small>
          </div>
        </div>
        <div class="col-4 col-md-3">
          <div class="dash-card text-center">
            <h3 class="fw-bold mb-0" style="color: var(--pumpkin);">{{ $stats->upcoming ?? 0 }}</h3>
            <small class="text-secondary opacity-75">{{ __('messages.student_exams_remaining') }}</small>
          </div>
        </div>
        <div class="col-4 col-md-3 d-md-block d-none">
          <div class="dash-card text-center">
            <h3 class="fw-bold mb-0" style="color: #0F6D80;">{{ number_format($stats->avg_score ?? 0, 1) }}%</h3>
            <small class="text-secondary opacity-75">{{ __('messages.student_exams_avg_score') }}</small>
          </div>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-lg-7">
          <!-- Current exams -->
          <div class="dash-card mb-4" data-aos="fade-up">
            <h5 class="fw-bold mb-3" style="color: #0F6D80;"><i class="fa-regular fa-clock me-2"></i>{{ __('messages.student_exams_upcoming') }}</h5>
            <div class="d-flex flex-column gap-2">
              @forelse($exams as $exam)
              <a href="{{ route('student.exam.show', $exam) }}" class="text-decoration-none exam-card d-block h-100" style="cursor:pointer;">
                <div class="d-flex justify-content-between align-items-start">
                  <div>
                    <h6 class="fw-bold mb-1" style="color: #1f2937;">{{ $exam->title }}</h6>
                    <span class="small text-secondary opacity-75 d-block">{{ $exam->course->name ?? '' }}</span>
                    <span class="small text-secondary opacity-50">{{ $exam->date ? \Carbon\Carbon::parse($exam->date)->format('Y/m/d') : '----' }}</span>
                  </div>
                  <span class="teacher-badge teacher-badge-warning">{{ __('messages.student_exams_upcoming_badge') }}</span>
                </div>
              </a>
              @empty
              <p class="text-center text-secondary opacity-75 my-3">{{ __('messages.student_exams_no_upcoming') }}</p>
              @endforelse
            </div>
          </div>
        </div>
        <div class="col-lg-5">
          <!-- Categories -->
          <div class="dash-card mb-4" data-aos="fade-up">
            <h5 class="fw-bold mb-3" style="color: #0F6D80;"><i class="fa-solid fa-layer-group me-2"></i>{{ __('messages.student_exams_categories') }}</h5>
            <div class="row g-2">
              @php $categories = $exams->pluck('course.name_ar')->unique()->filter(); @endphp
              @forelse($categories as $cat)
              <div class="col-4 col-md-3">
                <div class="p-2 rounded-2 text-center small fw-medium d-flex justify-content-between align-items-center" style="background: rgba(15,109,128,0.04); border: 1px solid rgba(15,109,128,0.06); cursor: default;">
                  <span>{{ $cat }}</span>
                  <span class="badge" style="background: rgba(15,109,128,0.12); color: #0F6D80;">{{ $exams->where('course.name_ar', $cat)->count() }}</span>
                </div>
              </div>
              @empty
              <p class="text-center text-secondary opacity-75 my-3">{{ __('messages.student_exams_no_categories') }}</p>
              @endforelse
            </div>
          </div>
          <!-- Performance Chart (simple bars) -->
          <div class="dash-card" data-aos="fade-up">
            <h5 class="fw-bold mb-3" style="color: #0F6D80;"><i class="fa-solid fa-chart-simple me-2"></i>{{ __('messages.student_exams_performance') }}</h5>
            <div id="perfChart"></div>
          </div>
        </div>
      </div>
@endsection

@push('scripts')
<script>
      const perfData = @json($perfData);
      const perfLabels = perfData.length ? perfData.map(function(d) { return d.label; }) : ['{{ __('messages.student_exams_no_results') }}'];
      const perfValues = perfData.length ? perfData.map(function(d) { return d.score; }) : [0];

      document.getElementById('perfChart').innerHTML = perfLabels.map((l, i) => `
        <div class="d-flex align-items-center gap-2 mb-2">
          <span class="small" style="width: 60px; font-size: 0.7rem;">${l}</span>
          <div class="flex-grow-1" style="height: 18px; background: #e0e3e4; border-radius: 10px; overflow: hidden;">
            <div style="width: ${perfValues[i]}%; height: 100%; background: ${perfValues[i] >= 90 ? '#27ae60' : perfValues[i] >= 75 ? '#0F6D80' : '#e67e22'}; border-radius: 10px; transition: width 1s;"></div>
          </div>
          <span class="small fw-bold" style="width: 30px; text-align: left; font-size: 0.7rem; color: #0F6D80;">${perfValues[i]}%</span>
        </div>
      `).join('');

      function startExam(examId, examUrl) {
        Swal.fire({
          title: '{{ __('messages.student_exam_ready') }}',
          text: '{{ __('messages.student_exam_ready_text') }}',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#0F6D80',
          confirmButtonText: '{{ __('messages.student_exam_start') }}',
          cancelButtonText: '{{ __('messages.student_exam_later') }}'
        }).then(r => {
          if (r.isConfirmed) {
            Swal.fire({ icon: 'success', title: '{{ __('messages.student_exam_started') }}', timer: 1500, showConfirmButton: false });
            setTimeout(function() { window.location.href = examUrl; }, 1500);
          }
        });
      }

      AOS.init({ duration: 600, once: true });
</script>
@endpush

@extends('layouts.student')

@section('title', __('messages.student_treasure_page_title'))

@section('meta_description', __('messages.student_treasure_subtitle'))

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4" data-aos="fade-up">
        <div>
          <h1 class="fw-bold mb-1" style="color: #0F6D80; font-size: 1.6rem;">{{ __('messages.student_treasure_page_title') }}</h1>
          <p class="text-secondary opacity-75 mb-0">{{ __('messages.student_treasure_subtitle') }}</p>
        </div>
        <a href="{{ route('student.dashboard') }}" class="btn rounded-3 px-3 py-2" style="border: 1.5px solid rgba(15,109,128,0.08);"><i class="fa-solid fa-arrow-right me-1"></i>{{ __('messages.student_treasure_back') }}</a>
      </div>

      <!-- Stats -->
      <div class="row g-3 mb-4" data-aos="fade-up">
        <div class="col-4 col-md-2">
          <div class="dash-card text-center p-3">
            <h4 class="fw-bold mb-0" style="color: #0F6D80;">{{ $juzCount }}</h4>
            <small class="text-secondary opacity-75">{{ __('messages.student_treasure_completed') }}</small>
          </div>
        </div>
        <div class="col-4 col-md-2">
          <div class="dash-card text-center p-3">
            <h4 class="fw-bold mb-0" style="color: var(--pumpkin);">{{ $juzCount < $totalJuz ? 1 : 0 }}</h4>
            <small class="text-secondary opacity-75">{{ __('messages.student_treasure_in_progress') }}</small>
          </div>
        </div>
        <div class="col-4 col-md-2">
          <div class="dash-card text-center p-3">
            <h4 class="fw-bold mb-0" style="color: #e74c3c;">{{ $totalJuz - $juzCount }}</h4>
            <small class="text-secondary opacity-75">{{ __('messages.student_treasure_remaining') }}</small>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="dash-card text-center p-3">
            <h4 class="fw-bold mb-0" style="color: #0F6D80;">{{ $pct }}%</h4>
            <small class="text-secondary opacity-75">{{ __('messages.student_treasure_overall') }}</small>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="dash-card text-center p-3">
            <h4 class="fw-bold mb-0" style="color: var(--pumpkin);">{{ $juzCount }}</h4>
            <small class="text-secondary opacity-75">{{ __('messages.student_treasure_juz_recorded') }}</small>
          </div>
        </div>
      </div>

      <!-- Master progress bar -->
      <div class="dash-card mb-4" data-aos="fade-up">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="fw-bold small" style="color: #0F6D80;">{{ __('messages.student_treasure_progress') }}</span>
          <span class="small fw-bold" style="color: #0F6D80;">{{ $juzCount }}/{{ $totalJuz }}</span>
        </div>
        <div style="height: 12px; background: #e0e3e4; border-radius: 10px; overflow: hidden;">
          <div style="width: {{ $pct }}%; height: 100%; background: linear-gradient(to left, #F5BD58, #0F6D80); border-radius: 10px; position: relative; overflow-hidden;">
            <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent); animation: shimmer 2s infinite;"></div>
          </div>
        </div>
      </div>

      <!-- Juz grid with surah names -->
      <div class="dash-card" data-aos="fade-up">
        <div class="row g-3" id="juzMap"></div>
      </div>
@endsection

@push('scripts')
<script>
const completedJuz = {{ $juzCount }};
const juzSurahs = [
  'الفاتحة — البقرة', 'البقرة (١٤٢—٢٥٢)', 'البقرة (٢٥٣—٢٨٦) + آل عمران',
  'آل عمران (١—٩٣)', 'النساء (١—٢٤)', 'النساء (٢٥—١٧٦)',
  'المائدة', 'الأنعام', 'الأعراف',
  'الأنفال + التوبة', 'التوبة (٩٣—١٢٩) + يونس', 'هود + يوسف',
  'يوسف (١—١١١) + الرعد + إبراهيم', 'الحجر — النحل', 'الإسراء (١—١١١) + الكهف',
  'مريم — طه', 'الأنبياء — الحج', 'المؤمنون — الفرقان',
  'الشعراء — النمل', 'القصص — العنكبوت', 'الروم — السجدة',
  'الأحزاب — سبأ', 'فاطر — يس', 'الصافات — الزمر',
  'غافر — فصلت', 'الشورى — الزخرف — الدخان', 'الجاثية — الحديد',
  'المجادلة — التحريم', 'تبارك — المرسلات', 'النبأ — الناس',
];

document.getElementById('juzMap').innerHTML = juzSurahs.map(function(surah, i) {
  const n = i + 1;
  const sta = n <= completedJuz ? 'done' : (n === completedJuz + 1 ? 'current' : 'locked');
  const cls = sta === 'done' ? 'completed' : sta === 'current' ? 'current' : 'locked';
  return '<div class="col-6 col-md-2">' +
    '<div class="map-juz ' + cls + '">' +
    '<div class="juz-icon">' + (sta === 'done' ? '<i class="fa-solid fa-check"></i>' : sta === 'current' ? '<i class="fa-solid fa-book-open"></i>' : '<i class="fa-solid fa-lock"></i>') + '</div>' +
    '<div class="fw-bold small" style="color: #0F6D80;">{{ __('messages.student_juz') }} ' + n + '</div>' +
    '<span class="surah-tag">' + surah + '</span>' +
    '</div></div>';
}).join('');

AOS.init({ duration: 600, once: true });
</script>
@endpush

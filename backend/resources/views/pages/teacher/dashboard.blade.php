@extends('layouts.teacher')

@section('title', __('messages.teacher_title', ['site_name' => __('messages.site_name')]))
@section('meta_description', __('messages.teacher_dash_meta'))
@section('header_greeting', __('messages.teacher_header_greeting', ['name' => $user->name ?? __('messages.teacher_female')]))
@section('page_title', __('messages.teacher_dashboard'))

@push('styles')
<style>
      :root {
        --td-teal: #0F6D80;
        --td-teal-dark: #0a4a56;
        --td-gold: #d89b1d;
      }

      /* ===== Welcome banner ===== */
      .td-banner { position: relative; border-radius: 20px; overflow: hidden; background: linear-gradient(135deg, #0a4a56 0%, #0F6D80 55%, #157a8c 100%); padding: 28px 32px; color: #fff; box-shadow: 0 12px 34px rgba(10,74,86,0.22); }
      .td-banner::after { content: ''; position: absolute; inset: 0; background-image: url("data:image/svg+xml,%3Csvg width='72' height='72' viewBox='0 0 72 72' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M72 0L0 72M36 0L0 36M72 36L36 72' stroke='%23ffffff' stroke-opacity='0.05' fill='none'/%3E%3C/svg%3E"); pointer-events: none; }
      .td-banner-deco { position: absolute; width: 220px; height: 220px; left: -60px; bottom: -80px; background: rgba(216,155,29,0.18); border-radius: 32px; transform: rotate(24deg); pointer-events: none; }
      .td-banner-inner { position: relative; z-index: 1; display: flex; align-items: center; justify-content: space-between; gap: 24px; flex-wrap: wrap; }
      .td-banner-date { font-size: .82rem; color: rgba(255,255,255,.75); margin-bottom: 6px; }
      .td-banner-title { font-size: 1.4rem; font-weight: 800; margin-bottom: 8px; color: #fff; }
      .td-banner-sub { font-size: .92rem; color: rgba(255,255,255,.78); margin: 0; max-width: 520px; line-height: 1.8; }
      .td-banner-chips { display: flex; flex-direction: column; gap: 10px; }
      .td-chip { display: inline-flex; align-items: center; gap: 10px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.16); backdrop-filter: blur(8px); border-radius: 50px; padding: 8px 18px; font-size: .85rem; font-weight: 600; color: #fff; }
      .td-chip i { width: 18px; text-align: center; }

      /* ===== Stat cards ===== */
      .td-stat { background: #fff; border-radius: 16px; padding: 18px 20px; display: flex; align-items: center; gap: 14px; box-shadow: 0 4px 20px rgba(15,109,128,0.05); border: 1px solid rgba(15,109,128,0.06); height: 100%; transition: transform 0.2s ease, box-shadow 0.2s ease; position: relative; overflow: hidden; }
      .td-stat::after { content: ''; position: absolute; top: 0; bottom: 0; right: 0; width: 3px; background: linear-gradient(180deg, #0F6D80, #d89b1d); }
      .td-stat:hover { transform: translateY(-3px); box-shadow: 0 10px 28px rgba(15,109,128,0.12); }
      .td-stat-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; background: linear-gradient(135deg, rgba(15,109,128,0.1), rgba(15,109,128,0.04)); color: #0F6D80; }
      .td-stat-value { font-size: 1.5rem; font-weight: 800; color: #0F6D80; line-height: 1.1; }
      .td-stat-label { font-size: .82rem; color: #6b7a7e; font-weight: 500; margin: 2px 0 0; }

      /* ===== Quick actions ===== */
      .td-actions { background: linear-gradient(135deg, rgba(15,109,128,0.04), rgba(216,155,29,0.05)); border: 1px solid rgba(15,109,128,0.08); border-radius: 16px; padding: 18px; height: 100%; }
      .td-quick { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; text-align: center; background: #fff; border: 1.5px solid rgba(15,109,128,0.08); border-radius: 14px; padding: 16px 10px; transition: all 0.2s; cursor: pointer; height: 100%; }
      .td-quick:hover { border-color: #0F6D80; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(15,109,128,0.1); }
      .td-quick-icon { width: 40px; height: 40px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 1rem; background: linear-gradient(135deg, rgba(15,109,128,0.1), rgba(15,109,128,0.04)); color: #0F6D80; }
      .td-quick span { font-size: .82rem; font-weight: 700; color: #3f484b; }

      /* ===== Section ===== */
      .td-section-title { font-size: 1.05rem; font-weight: 800; color: #1f2937; margin: 0 0 16px; display: flex; align-items: center; gap: 10px; }
      .td-section-title i { color: #0F6D80; }

      /* ===== Lists ===== */
      .td-list-item { display: flex; align-items: center; gap: 12px; padding: 13px 0; border-bottom: 1px dashed rgba(15,109,128,0.1); }
      .td-list-item:last-child { border-bottom: none; padding-bottom: 0; }
      .td-item-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
      .td-item-time { font-size: .76rem; color: #aab7bc; white-space: nowrap; }
      .td-time-block { min-width: 44px; text-align: center; background: #fff; border: 1.5px solid rgba(15,109,128,0.08); border-radius: 12px; padding: 6px 8px; }

      .chart-container { position: relative; height: 230px; }

      .td-badge { display: inline-flex; align-items: center; padding: 5px 14px; border-radius: 20px; font-size: .78rem; font-weight: 700; }
      .td-badge-strong { background: linear-gradient(135deg, #0F6D80, #157a8c); color: #fff; }
      .td-badge-mid { background: rgba(15, 109, 128, 0.12); color: #0F6D80; }
      .td-badge-soft { background: rgba(216, 155, 29, 0.14); color: #b57f0e; }

      @media (max-width: 991.98px) { .td-banner-chips { flex-direction: row; flex-wrap: wrap; } }
      @media (max-width: 575.98px) { .td-banner { padding: 22px 20px; } .td-banner-title { font-size: 1.15rem; } }
    </style>
@endpush

@section('content')
      {{-- ===== Welcome banner ===== --}}
      <section class="td-banner mb-4">
        <span class="td-banner-deco" aria-hidden="true"></span>
        <div class="td-banner-inner">
          <div>
            <p class="td-banner-date"><i class="fa-regular fa-calendar me-2"></i>{{ now()->translatedFormat('l d F Y') }}</p>
            <h4 class="td-banner-title">{{ __('messages.teacher_dash_welcome', ['name' => $user->name ?? __('messages.teacher_female')]) }}</h4>
            <p class="td-banner-sub">{{ __('messages.teacher_dash_welcome_sub') }}</p>
          </div>
          <div class="td-banner-chips">
            <span class="td-chip"><i class="fa-solid fa-video" style="color:#ffd166;"></i>{{ $stats->sessions_week }} — {{ __('messages.teacher_dash_sessions_week') }}</span>
            <span class="td-chip"><i class="fa-solid fa-users" style="color:#ffd166;"></i>{{ $stats->students }} — {{ __('messages.teacher_dash_students') }}</span>
            <span class="td-chip"><i class="fa-solid fa-pen-to-square" style="color:#ffd166;"></i>{{ $stats->exams_upcoming }} — {{ __('messages.teacher_dash_exams_upcoming') }}</span>
          </div>
        </div>
      </section>

      {{-- ===== Stats ===== --}}
      <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
          <div class="td-stat">
            <div class="td-stat-icon"><i class="fa-solid fa-book-open"></i></div>
            <div>
              <p class="td-stat-value mb-0">{{ $stats->courses }}</p>
              <p class="td-stat-label mb-0">{{ __('messages.teacher_dash_courses') }}</p>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="td-stat">
            <div class="td-stat-icon"><i class="fa-solid fa-users"></i></div>
            <div>
              <p class="td-stat-value mb-0">{{ $stats->students }}</p>
              <p class="td-stat-label mb-0">{{ __('messages.teacher_dash_students') }}</p>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="td-stat">
            <div class="td-stat-icon"><i class="fa-solid fa-video"></i></div>
            <div>
              <p class="td-stat-value mb-0">{{ $stats->sessions_week }}</p>
              <p class="td-stat-label mb-0">{{ __('messages.teacher_dash_sessions_week') }}</p>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="td-stat">
            <div class="td-stat-icon"><i class="fa-solid fa-pen-to-square"></i></div>
            <div>
              <p class="td-stat-value mb-0">{{ $stats->exams_upcoming }}</p>
              <p class="td-stat-label mb-0">{{ __('messages.teacher_dash_exams_upcoming') }}</p>
            </div>
          </div>
        </div>
      </div>

      {{-- ===== Quick actions + Weekly chart ===== --}}
      <div class="row g-3 mb-4">
        <div class="col-lg-4">
          <div class="td-actions">
            <h6 class="td-section-title"><i class="fa-solid fa-bolt"></i>{{ __('messages.teacher_dash_quick_actions') }}</h6>
            <div class="row g-2">
              <div class="col-6">
                <div class="td-quick" onclick="quickAction('addSession')">
                  <span class="td-quick-icon"><i class="fa-solid fa-video"></i></span>
                  <span>{{ __('messages.teacher_dash_add_session') }}</span>
                </div>
              </div>
              <div class="col-6">
                <div class="td-quick" onclick="quickAction('addExam')">
                  <span class="td-quick-icon"><i class="fa-solid fa-pen-to-square"></i></span>
                  <span>{{ __('messages.teacher_dash_add_exam') }}</span>
                </div>
              </div>
              <div class="col-6">
                <div class="td-quick" onclick="quickAction('addStudent')">
                  <span class="td-quick-icon"><i class="fa-solid fa-user-plus"></i></span>
                  <span>{{ __('messages.teacher_dash_add_student') }}</span>
                </div>
              </div>
              <div class="col-6">
                <div class="td-quick" onclick="quickAction('issueCert')">
                  <span class="td-quick-icon"><i class="fa-solid fa-certificate"></i></span>
                  <span>{{ __('messages.teacher_dash_issue_cert') }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-8">
          <div class="teacher-card h-100">
            <h6 class="td-section-title"><i class="fa-solid fa-chart-column"></i>{{ __('messages.teacher_dash_weekly_activity') }}</h6>
            <div class="chart-container">
              <canvas id="sessionsChart"></canvas>
            </div>
          </div>
        </div>
      </div>

      {{-- ===== Activities + Upcoming sessions ===== --}}
      <div class="row g-3 mb-4">
        <div class="col-lg-6">
          <div class="teacher-card h-100">
            <h6 class="td-section-title"><i class="fa-solid fa-clock-rotate-left"></i>{{ __('messages.teacher_dash_recent_activities') }}</h6>
            <div id="recentActivity">
              @forelse($recentActivities as $act)
              <div class="td-list-item">
                <span class="td-item-dot" style="background: #0F6D80;"></span>
                <div class="flex-grow-1"><span class="small">{{ $act->text }}</span></div>
                <span class="td-item-time">{{ $act->time }}</span>
              </div>
              @empty
              <div class="text-center py-4 text-secondary">{{ __('messages.teacher_dash_no_activities') }}</div>
              @endforelse
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="teacher-card h-100">
            <h6 class="td-section-title"><i class="fa-solid fa-calendar-check"></i>{{ __('messages.teacher_dash_upcoming_sessions') }}</h6>
            <div id="upcomingSessions">
              @forelse($upcomingSessions as $session)
              <div class="td-list-item">
                <div class="td-time-block">
                  <span class="fw-bold fs-6 d-block" style="color:#0F6D80;">{{ \Carbon\Carbon::parse($session->time_from)->format('h') }}</span>
                  <span class="small text-secondary opacity-50" style="font-size:0.62rem;">{{ \Carbon\Carbon::parse($session->time_from)->format('A') === 'AM' ? __('messages.teacher_dash_am') : __('messages.teacher_dash_pm') }}</span>
                </div>
                <div class="flex-grow-1">
                  <span class="fw-bold small">{{ $session->title }}</span>
                  <span class="small text-secondary opacity-75 d-block">{{ $session->course->name ?? '' }} • {{ \Carbon\Carbon::parse($session->date)->translatedFormat('l g:i A') }}</span>
                </div>
                @php
                  $diff = \Carbon\Carbon::parse($session->date)->diffInDays(now());
                  $badgeClass = $diff == 0 ? 'td-badge-strong' : ($diff == 1 ? 'td-badge-mid' : 'td-badge-soft');
                  $badgeText = $diff == 0 ? __('messages.teacher_dash_today') : ($diff == 1 ? __('messages.teacher_dash_tomorrow') : __('messages.teacher_dash_soon'));
                @endphp
                <span class="td-badge {{ $badgeClass }}">{{ $badgeText }}</span>
              </div>
              @empty
              <div class="text-center py-4 text-secondary">{{ __('messages.teacher_dash_no_upcoming') }}</div>
              @endforelse
            </div>
          </div>
        </div>
      </div>

      {{-- ===== Student performance ===== --}}
      <div class="teacher-card">
        <h6 class="td-section-title"><i class="fa-solid fa-ranking-star"></i>{{ __('messages.teacher_dash_student_performance') }}</h6>
        <div class="table-responsive">
          <table class="table teacher-table table-borderless mb-0">
            <thead>
              <tr><th>{{ __('messages.teacher_dash_table_num') }}</th><th>{{ __('messages.teacher_dash_table_name') }}</th><th>{{ __('messages.teacher_dash_table_course') }}</th><th>{{ __('messages.teacher_dash_table_attendance') }}</th><th>{{ __('messages.teacher_dash_table_avg') }}</th><th>{{ __('messages.teacher_dash_table_status') }}</th></tr>
            </thead>
            <tbody>
              @forelse($students as $i => $student)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td class="fw-bold">{{ $student->name }}</td>
                <td>{{ $student->course }}</td>
                <td>{{ $student->attendance }}</td>
                <td>{{ $student->avg_score }}</td>
                <td>
                  @php
                    $statusClass = $student->status === __('messages.teacher_reports_excellent') ? 'td-badge-strong' : ($student->status === __('messages.teacher_reports_very_good') ? 'td-badge-mid' : 'td-badge-soft');
                  @endphp
                  <span class="td-badge {{ $statusClass }}">{{ $student->status }}</span>
                </td>
              </tr>
              @empty
              <tr><td colspan="6" class="text-center text-secondary py-3">{{ __('messages.teacher_dash_no_students') }}</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
      document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('sessionsChart');
        if (ctx) {
          new Chart(ctx, {
            type: 'bar',
            data: {
              labels: @json($chartLabels),
              datasets: [{
                label: '{{ __('messages.teacher_dash_chart_sessions') }}',
                data: @json($chartData),
                backgroundColor: 'rgba(15,109,128,0.15)',
                borderColor: '#0F6D80',
                borderWidth: 2,
                borderRadius: 6,
                tension: 0.3
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: { legend: { display: false } },
              scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(15,109,128,0.04)' }, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
              }
            }
          });
        }
      });
      function quickAction(type) {
        var configs = {
          addSession: { icon: 'info', title: '{{ __('messages.teacher_dash_go_add_session_title') }}', text: '{{ __('messages.teacher_dash_go_add_session_text') }}', confirmText: '{{ __('messages.teacher_dash_go') }}', url: '{{ route("teacher.sessions") }}' },
          addExam: { icon: 'info', title: '{{ __('messages.teacher_dash_go_add_exam_title') }}', text: '{{ __('messages.teacher_dash_go_add_exam_text') }}', confirmText: '{{ __('messages.teacher_dash_go') }}', url: '{{ route("teacher.exams") }}' },
          addStudent: { icon: 'info', title: '{{ __('messages.teacher_dash_go_add_student_title') }}', text: '{{ __('messages.teacher_dash_go_add_student_text') }}', confirmText: '{{ __('messages.teacher_dash_go') }}', url: '{{ route("teacher.students") }}' },
          issueCert: { icon: 'info', title: '{{ __('messages.teacher_dash_go_issue_cert_title') }}', text: '{{ __('messages.teacher_dash_go_issue_cert_text') }}', confirmText: '{{ __('messages.teacher_dash_go') }}', url: '{{ route("teacher.certificates") }}' }
        };
        var c = configs[type];
        Swal.fire({ icon: c.icon, title: c.title, text: c.text, confirmButtonColor: '#0F6D80', confirmButtonText: c.confirmText, cancelButtonText: '{{ __('messages.teacher_dash_cancel') }}', showCancelButton: true }).then(function(r) { if (r.isConfirmed) window.location.href = c.url; });
      }
      function toggleMobileNav() {
        var sidebar = document.querySelector('.teacher-sidebar');
        sidebar.style.display = sidebar.style.display === 'flex' ? 'none' : 'flex';
      }
    </script>
@endpush
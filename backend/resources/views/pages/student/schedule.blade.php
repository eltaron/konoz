@extends('layouts.student')

@section('title', __('messages.student_schedule_page_title'))

@section('meta_description', __('messages.student_schedule_meta', ['site_name' => __('messages.site_name')]))

@push('styles')
<style>
.session-card { border-radius: 14px; padding: 20px; background: #fff; border: 1px solid rgba(15,109,128,0.06); transition: 0.2s; }
      .session-card:hover { border-color: rgba(15,109,128,0.12); box-shadow: 0 2px 12px rgba(15,109,128,0.04); }
      .badge-attended { background: rgba(40,167,69,0.1); color: #28a745; }
      .badge-missed { background: rgba(231,76,60,0.1); color: #e74c3c; }
      .badge-upcoming { background: rgba(15,109,128,0.08); color: #0F6D80; }
      .stat-icon { width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; border-radius: 12px; }
</style>
@endpush

@section('content')
<h1 class="fw-bold mb-4" style="color: #0F6D80; font-size: 1.6rem;" data-aos="fade-up">{{ __('messages.student_schedule_page_title') }}</h1>

      <div class="row g-3 mb-4" data-aos="fade-up">
        <div class="col-md-4">
          <div class="dash-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background: rgba(15,109,128,0.06);"><i class="fa-solid fa-calendar-days" style="color: #0F6D80;"></i></div>
            <div>
              <small class="text-secondary opacity-75">{{ __('messages.student_schedule_total') }}</small>
              <h3 class="fw-bold mb-0" style="color: #0F6D80;">{{ $stats->total ?? 0 }}</h3>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="dash-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background: rgba(245,189,88,0.08);"><i class="fa-solid fa-clock" style="color: var(--pumpkin);"></i></div>
            <div>
              <small class="text-secondary opacity-75">{{ __('messages.student_schedule_this_week') }}</small>
              <h3 class="fw-bold mb-0" style="color: var(--pumpkin);">{{ $stats->this_week ?? 0 }}</h3>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="dash-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background: rgba(40,167,69,0.06);"><i class="fa-solid fa-arrow-up" style="color: #28a745;"></i></div>
            <div>
              <small class="text-secondary opacity-75">{{ __('messages.student_schedule_upcoming') }}</small>
              <h3 class="fw-bold mb-0" style="color: #28a745;">{{ $stats->upcoming ?? 0 }}</h3>
            </div>
          </div>
        </div>
      </div>

      <h5 class="fw-bold mb-3" style="color: #0F6D80;" data-aos="fade-up">{{ __('messages.student_schedule_upcoming_heading') }}</h5>
      <div class="row g-3 mb-4">
        @forelse($upcoming as $session)
        <div class="col-md-6 col-lg-4" data-aos="fade-up">
          <div class="session-card h-100">
            <div class="d-flex align-items-start justify-content-between mb-3">
              <span class="badge badge-upcoming rounded-3 px-3 py-2 fw-medium">{{ $session->status === 'completed' ? __('messages.student_schedule_completed') : ($session->status === 'cancelled' ? __('messages.student_schedule_cancelled') : __('messages.student_schedule_upcoming_badge')) }}</span>
              <span class="d-flex align-items-center justify-content-center rounded-2 flex-shrink-0" style="width: 44px; height: 44px; background: rgba(15,109,128,0.06);"><i class="fa-solid fa-chalkboard-user" style="color: #0F6D80;"></i></span>
            </div>
            <h6 class="fw-bold mb-2" style="color: #0F6D80;">{{ $session->title ?? $session->course->name ?? __('messages.student_schedule_session') }}</h6>
            <div class="d-flex flex-column gap-1 small text-secondary opacity-75 mb-0">
              <span><i class="fa-regular fa-user me-1"></i>{{ $session->course->instructor_name ?? '' }}</span>
              <span><i class="fa-regular fa-calendar me-1"></i>{{ $session->date ? \Carbon\Carbon::parse($session->date)->format('Y/m/d') : '' }}</span>
              <span><i class="fa-regular fa-clock me-1"></i>{{ $session->time_from ? \Carbon\Carbon::parse($session->time_from)->format('g:i A') : '' }}</span>
            </div>
          </div>
        </div>
        @empty
        <p class="text-center text-secondary opacity-75 my-3">{{ __('messages.student_schedule_no_upcoming') }}</p>
        @endforelse
      </div>

      <h5 class="fw-bold mb-3" style="color: #0F6D80;" data-aos="fade-up">{{ __('messages.student_schedule_past_heading') }}</h5>
      <div class="dash-card p-0" data-aos="fade-up">
        <div class="d-flex flex-column">
          @forelse($past as $session)
          <div class="session-row d-flex align-items-center gap-3 px-4 py-3 border-bottom" style="border-color: rgba(15,109,128,0.04) !important;">
            <span class="d-flex align-items-center justify-content-center rounded-2 flex-shrink-0" style="width: 40px; height: 40px; background: rgba(15,109,128,0.06);"><i class="fa-solid fa-book-quran" style="color: #0F6D80; font-size: 0.9rem;"></i></span>
            <div class="flex-grow-1">
              <span class="fw-bold small d-block" style="color: #0F6D80;">{{ $session->title ?? $session->course->name ?? __('messages.student_schedule_session') }}</span>
              <small class="text-secondary opacity-75">{{ $session->course->instructor_name ?? '' }} • {{ $session->date ? \Carbon\Carbon::parse($session->date)->format('Y/m/d') : '' }} • {{ $session->time_from ? \Carbon\Carbon::parse($session->time_from)->format('g:i A') : '' }}</small>
            </div>
            <span class="badge rounded-3 px-3 py-2 fw-medium {{ $session->status === 'completed' ? 'badge-attended' : ($session->status === 'cancelled' ? 'badge-missed' : 'badge-upcoming') }}">{{ $session->status === 'completed' ? __('messages.student_schedule_attended') : ($session->status === 'cancelled' ? __('messages.student_schedule_cancelled') : __('messages.student_schedule_missed')) }}</span>
          </div>
          @empty
          <p class="text-center text-secondary opacity-75 my-3">{{ __('messages.student_schedule_no_past') }}</p>
          @endforelse
        </div>
      </div>
@endsection

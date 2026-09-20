@extends('layouts.student')

@section('title', $lesson->name . ' | ' . $course->name . ' | ' . __('messages.site_name'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard/dashboard-lessons.css') }}">
@endpush

@section('content')
    <div class="lst-hero" data-aos="fade-down">
        <div class="lst-hero-bg"></div>
        <div class="lst-hero-content">
            <a href="{{ route('student.course-detail', $course) }}?tab=lessons" class="lst-back">
                <i class="fa-solid fa-arrow-right"></i> {{ __('messages.student_back_lessons') }}
            </a>
            <div class="lst-hero-body">
                <span class="lst-order-badge">{{ $lesson->order ?? $lesson->id }}</span>
                <h1 class="lst-title">{{ $lesson->name }}</h1>
                <p class="lst-subtitle">{{ $course->name }} — {{ __('messages.student_tab_lessons') }}
                    {{ $lesson->order ?? $lesson->id }} {{ __('messages.student_lesson_of') }}
                    {{ $course->lessons->count() }}</p>
            </div>
        </div>
    </div>

    <div class="lst-grid" data-aos="fade-up">
        <div class="lst-main">
            <div class="lst-player-card">
                @if ($lesson->youtube_embed)
                    <div class="lst-video-wrap">
                        <iframe src="{{ $lesson->youtube_embed }}" title="{{ $lesson->name }}"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen loading="lazy" class="lst-iframe"></iframe>
                    </div>
                @elseif($lesson->link)
                    <div class="lst-room-card">
                        <div class="lst-room-icon"><i class="fa-solid fa-video"></i></div>
                        <h3 class="lst-room-title">{{ __('messages.student_lesson_not_video') }}</h3>
                        <p class="lst-room-desc">{{ $lesson->link }}</p>
                        <a href="{{ $lesson->link }}" target="_blank" rel="noopener" class="lst-room-btn">
                            <i class="fa-solid fa-external-link me-1"></i>{{ __('messages.student_lesson_source_link') }}
                        </a>
                    </div>
                @else
                    <div class="lst-room-card lst-room-empty">
                        <div class="lst-room-icon"><i class="fa-solid fa-file-lines"></i></div>
                        <h3 class="lst-room-title">{{ __('messages.student_lesson_content_pending') }}</h3>
                    </div>
                @endif
            </div>

            @if ($lesson->youtube_embed)
                <div class="lst-cta">
                    <button class="lst-cta-btn" id="playBtn" data-src="{{ $lesson->youtube_embed }}">
                        <i class="fa-solid fa-play me-1"></i>{{ __('messages.student_lesson_start') }}
                    </button>
                </div>
            @endif
        </div>

        <div class="lst-side">
            <div class="lst-steps-card">
                <div class="lst-steps-header">
                    <div class="lst-steps-icon"><i class="fa-solid fa-list-ol"></i></div>
                    <h3 class="lst-steps-title">{{ __('messages.student_lesson_steps') }}</h3>
                </div>
                <div class="lst-steps-list">
                    @foreach ($course->lessons->sortBy('order ?? id') as $idx => $l)
                        @php
                            $cur = $l->id === $lesson->id;
                            $done = $l->order < ($lesson->order ?? $lesson->id);
                            $embed = $l->youtube_embed;
                            $route = route('student.lesson.show', $l);
                        @endphp
                        <a href="{{ $route }}"
                            class="lst-step {{ $cur ? 'lst-step-active' : '' }} {{ $done ? 'lst-step-done' : '' }}">
                            <span class="lst-step-num">
                                @if ($done)
                                    <i class="fa-solid fa-check"></i>
                                @elseif($cur)
                                    <i class="fa-solid fa-play"></i>
                                @else
                                    {{ $l->order ?? $loop->iteration }}
                                @endif
                            </span>
                            <span class="lst-step-name">{{ $l->name }}</span>
                            @if ($embed)
                                <i class="fa-solid fa-circle-play lst-step-arrow"></i>
                            @elseif($l->link)
                                <i class="fa-solid fa-arrow-up-right-from-square lst-step-arrow"></i>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="lst-nav-card">
                @php
                    $sorted = $course->lessons->sortBy('order ?? id')->values();
                    $curIdx = $sorted->search(fn($l) => $l->id === $lesson->id);
                    $prev = $curIdx > 0 ? $sorted[$curIdx - 1] : null;
                    $next = $curIdx < $sorted->count() - 1 ? $sorted[$curIdx + 1] : null;
                @endphp
                <div class="lst-nav-row">
                    @if ($prev)
                        <a href="{{ route('student.lesson.show', $prev) }}" class="lst-nav-btn lst-nav-prev">
                            <i class="fa-solid fa-chevron-right me-1"></i> {{ __('messages.student_lesson_prev') }}
                        </a>
                    @else
                        <span></span>
                    @endif
                    @if ($next)
                        <a href="{{ route('student.lesson.show', $next) }}" class="lst-nav-btn lst-nav-next">
                            {{ __('messages.student_lesson_next') }} <i class="fa-solid fa-chevron-left ms-1"></i>
                        </a>
                    @else
                        <span></span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

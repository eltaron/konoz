@extends('layouts.student')

@section('title', __('messages.student_courses_title'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard/dashboard-courses.css') }}" />
    <style>
        .modal-header .btn-close {
            margin: calc(-.5 * var(--bs-modal-header-padding-y)) auto calc(-.5 * var(--bs-modal-header-padding-x)) calc(-.5 * var(--bs-modal-header-padding-y));
        }

        .modal-title {
            color: #fff;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: #0F6D80;">{{ __('messages.student_courses_title') }}</h4>
            <p class="text-secondary opacity-75 mb-0 small">{{ __('messages.student_courses_subtitle') }}</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 rounded-3 py-3 px-4 mb-4"
            style="border: none; background: rgba(25,135,84,0.06); color: #198754;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger d-flex align-items-center gap-2 rounded-3 py-3 px-4 mb-4"
            style="border: none; background: rgba(220,53,69,0.06); color: #dc3545;">
            <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
        </div>
    @endif
    @if (session('info'))
        <div class="alert alert-info d-flex align-items-center gap-2 rounded-3 py-3 px-4 mb-4"
            style="border: none; background: rgba(15,109,128,0.06); color: #0F6D80;">
            <i class="fa-solid fa-circle-info"></i> {{ session('info') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger d-flex align-items-center gap-2 rounded-3 py-3 px-4 mb-4"
            style="border: none; background: rgba(220,53,69,0.06); color: #dc3545;">
            <i class="fa-solid fa-circle-exclamation"></i>
            <div>{{ $errors->first() }}</div>
        </div>
    @endif

    @forelse($availableCourses as $course)
        @php
            $isEnrolled = $student->courses->contains($course->id);
            $pendingReq = \App\Models\EnrollmentRequest::where('student_id', $student->id)
                ->where('course_id', $course->id)
                ->first();
            $isPaid = !$course->is_free && $course->price > 0;
        @endphp
        <div class="dash-card mb-3">
            <div class="d-flex align-items-start gap-3">
                <div class="rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center"
                    style="width: 56px; height: 56px; background: rgba(15,109,128,0.06); color: #0F6D80; font-size: 1.3rem;">
                    <i class="fa-solid {{ $course->icon ?: 'fa-book-quran' }}"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <h6 class="fw-bold mb-1">{{ $course->name }}</h6>
                                @if ($course->name_en)
                                    <small class="text-secondary opacity-50">({{ $course->name_en }})</small>
                                @endif
                                @if ($isPaid)
                                    <span class="dash-badge dash-badge-warning" style="font-size:0.68rem;"><i
                                            class="fa-solid fa-money-bill-wave"></i>
                                        {{ number_format((float) $course->price) }}</span>
                                @else
                                    <span class="dash-badge dash-badge-success" style="font-size:0.68rem;"><i
                                            class="fa-solid fa-gift"></i> {{ __('messages.student_course_free') }}</span>
                                @endif
                            </div>
                            <p class="small text-secondary opacity-75 mb-1">
                                {{ $course->desc ? \Illuminate\Support\Str::limit($course->desc, 120) : '' }}</p>
                            <div class="d-flex align-items-center gap-3 small text-secondary opacity-50">
                                @if ($course->level)
                                    <span><i class="fa-solid fa-layer-group"></i> {{ $course->level }}</span>
                                @endif
                                @if ($course->duration)
                                    <span><i class="fa-regular fa-clock"></i> {{ $course->duration }}</span>
                                @endif
                            </div>
                        </div>
                        @if ($isEnrolled)
                            <span
                                class="dash-badge dash-badge-success">{{ __('messages.student_courses_enrolled') }}</span>
                        @elseif($pendingReq && $pendingReq->status === 'pending')
                            <span class="dash-badge dash-badge-warning">{{ __('messages.student_courses_pending') }}</span>
                        @else
                            <button type="button"
                                class="dash-btn {{ $pendingReq && $pendingReq->status === 'rejected' ? 'dash-btn-outline' : 'dash-btn-primary' }}"
                                data-bs-toggle="modal" data-bs-target="#enrollModal"
                                data-action="{{ route('student.courses.enroll', $course) }}"
                                data-course-name="{{ $course->name }}" data-is-free="{{ $isPaid ? '0' : '1' }}"
                                data-price="{{ number_format((float) $course->price) }}">
                                <i
                                    class="fa-solid fa-file-circle-check me-1"></i>{{ $pendingReq && $pendingReq->status === 'rejected' ? __('messages.student_courses_reapply') : __('messages.student_courses_enroll_btn') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="dash-card text-center py-5">
            <i class="fa-solid fa-book-open" style="font-size: 2.5rem; color: #d0d9db; margin-bottom: 12px;"></i>
            <p class="text-secondary opacity-75">{{ __('messages.student_courses_empty') }}</p>
        </div>
    @endforelse
@endsection

@push('scripts')
    <div class="modal fade cd-enroll-modal" id="enrollModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title"><span class="cd-em-icon"><i
                                    class="fa-solid fa-file-circle-check"></i></span>{{ __('messages.student_course_reg_title') }}
                        </h5>
                        <div class="modal-subtitle mt-1">{{ __('messages.student_course_reg_subtitle') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="" id="enrollModalForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="cd-enroll-course">
                            <span class="ce-name" id="cdEnrollCourseName"></span>
                            <span class="cd-enroll-price" id="cdEnrollPrice" hidden></span>
                            <span class="cd-enroll-free" id="cdEnrollFree"
                                hidden>{{ __('messages.student_course_free') }}</span>
                        </div>

                        <div id="cdEnrollPaySection">
                            @if ($paymentMethods->count() > 0)
                                <div class="cd-sec-label"><i
                                        class="fa-solid fa-building-columns"></i>{{ __('messages.student_course_transfer_methods') }}
                                </div>
                                <div class="cd-pay-methods">
                                    @foreach ($paymentMethods as $method)
                                        <label class="cd-pay-method">
                                            <input type="radio" name="payment_method_id" value="{{ $method->id }}" />
                                            <span class="cd-pay-icon"><i
                                                    class="fa-solid {{ $method->icon ?: 'fa-building-columns' }}"></i></span>
                                            <span class="flex-grow-1">
                                                <span class="cd-pay-name d-block">{{ $method->name }}</span>
                                                @if ($method->details)
                                                    <small class="cd-pay-details">{{ $method->details }}</small>
                                                @endif
                                            </span>
                                            <span class="cd-pay-check"><i class="fa-solid fa-circle-check"></i></span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-warning rounded-3 py-3 mb-3 small" style="border: none;">
                                    <i
                                        class="fa-solid fa-triangle-exclamation me-1"></i>{{ __('messages.student_course_methods_unavailable') }}
                                </div>
                            @endif

                            <div class="cd-sec-label"><i
                                    class="fa-solid fa-receipt"></i>{{ __('messages.student_course_receipt') }}</div>
                            <div class="cd-receipt-zone" id="cdReceiptZone">
                                <input type="file" name="receipt" id="cdReceiptInput" accept="image/*,.pdf" />
                                <div class="rz-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                                <div class="rz-title" id="cdReceiptTitle">{{ __('messages.student_course_receipt') }}
                                </div>
                                <div class="rz-hint">{{ __('messages.student_course_receipt_hint') }}</div>
                            </div>
                        </div>

                        <div class="cd-enroll-free-note" id="cdEnrollFreeNote" hidden>
                            <i class="fa-solid fa-gift"></i>
                            <span>{{ __('messages.student_course_reg_free_note') }}</span>
                        </div>

                        <div class="cd-enroll-note">
                            <label class="cd-sec-label"><i
                                    class="fa-regular fa-note-sticky"></i>{{ __('messages.student_course_note_optional') }}</label>
                            <textarea id="cdEnrollNote" name="receipt_note" rows="2"
                                placeholder="{{ __('messages.student_course_note_optional') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="cd-modal-btn" id="cdEnrollSubmit"><i
                                class="fa-solid fa-paper-plane me-1"></i>{{ __('messages.student_course_submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const modal = document.getElementById('enrollModal');
            if (!modal) return;
            const form = document.getElementById('enrollModalForm');
            const courseNameEl = document.getElementById('cdEnrollCourseName');
            const priceEl = document.getElementById('cdEnrollPrice');
            const freeEl = document.getElementById('cdEnrollFree');
            const paySectionEl = document.getElementById('cdEnrollPaySection');
            const freeNoteEl = document.getElementById('cdEnrollFreeNote');
            const receiptZone = document.getElementById('cdReceiptZone');
            const receiptTitle = document.getElementById('cdReceiptTitle');
            const defaultReceiptTitle = receiptTitle.textContent;

            modal.addEventListener('show.bs.modal', function(e) {
                const btn = e.relatedTarget;
                if (!btn) return;
                form.reset();
                form.setAttribute('action', btn.getAttribute('data-action'));
                courseNameEl.textContent = btn.getAttribute('data-course-name');

                const isFree = btn.getAttribute('data-is-free') === '1';
                const price = btn.getAttribute('data-price');

                if (isFree) {
                    priceEl.hidden = true;
                    freeEl.hidden = false;
                    paySectionEl.style.display = 'none';
                    freeNoteEl.hidden = false;
                } else {
                    priceEl.hidden = false;
                    priceEl.textContent = price;
                    freeEl.hidden = true;
                    paySectionEl.style.display = '';
                    freeNoteEl.hidden = true;
                    const radios = form.querySelectorAll('input[name="payment_method_id"]');
                    if (radios.length) radios[0].checked = true;
                }

                receiptZone.classList.remove('has-file');
                receiptTitle.textContent = defaultReceiptTitle;
            });

            const receiptInput = document.getElementById('cdReceiptInput');
            receiptInput.addEventListener('change', function() {
                if (this.files && this.files.length) {
                    receiptZone.classList.add('has-file');
                    receiptTitle.textContent = this.files[0].name;
                } else {
                    receiptZone.classList.remove('has-file');
                    receiptTitle.textContent = defaultReceiptTitle;
                }
            });
        })();
    </script>
@endpush

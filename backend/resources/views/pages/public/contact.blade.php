@extends('layouts.public')

@section('title', __('messages.page_contact_title', ['site_name' => __('messages.site_name')]))
@section('meta_description', __('messages.page_contact_meta', ['site_name' => __('messages.site_name')]))
@section('meta_robots', 'index, follow')

@section('content')
    <div class="page-hero text-center" data-aos="fade-up">
        <div class="hero-orb hero-orb-1"></div>
        <div class="hero-orb hero-orb-2"></div>
        <div class="hero-dot hero-dot-1"></div>
        <div class="hero-dot hero-dot-2"></div>
        <div class="hero-dot hero-dot-3"></div>
        <div class="container">
            <div class="page-hero-icon"><i class="fa-solid fa-headset"></i></div>
            <h1 class="fw-bold">{{ __('messages.nav_contact') }}</h1>
            <p>{{ __('messages.contact_subtitle') }}</p>
        </div>
    </div>

    <div class="container py-5">
        @if (session('success'))
            <div class="alert alert-success rounded-3 border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-check-circle ml-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="row g-5 justify-content-center">
            <div class="col-lg-7" data-aos="fade-up">
                <div class="legal-card">
                    <h4 class="fw-bold mb-4" style="color: #0F6D80;"><i
                            class="fa-regular fa-pen-to-square ml-2"></i>{{ __('messages.contact_form_heading') }}</h4>
                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">{{ __('messages.contact_name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control rounded-3" required
                                    placeholder="{{ __('messages.contact_name_ph') }}"
                                    value="{{ old('name') }}" />
                                @error('name')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">{{ __('messages.contact_email') }} <span
                                        class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control rounded-3" required
                                    placeholder="{{ __('messages.contact_email_ph') }}"
                                    value="{{ old('email') }}" />
                                @error('email')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">{{ __('messages.contact_phone') }}</label>
                                <input type="tel" name="phone" class="form-control rounded-3"
                                    placeholder="{{ __('messages.contact_phone_ph') }}"
                                    value="{{ old('phone') }}" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">{{ __('messages.contact_subject') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="subject" class="form-control rounded-3" required
                                    placeholder="{{ __('messages.contact_subject_ph') }}"
                                    value="{{ old('subject') }}" />
                                @error('subject')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">{{ __('messages.contact_message') }} <span
                                        class="text-danger">*</span></label>
                                <textarea name="message" class="form-control rounded-3" rows="5" required
                                    placeholder="{{ __('messages.contact_message_ph') }}">{{ old('message') }}</textarea>
                                @error('message')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary px-5 py-3 rounded-3 fw-bold"><i
                                        class="fa-regular fa-paper-plane ml-2"></i>{{ __('messages.contact_send') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="d-flex flex-column gap-4">
                    <div class="contact-info-card text-center p-4 p-md-5">
                        <div class="contact-icon mx-auto mb-3">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <h5 class="fw-bold mb-2" style="color: #0F6D80; font-size: 1.1rem;">{{ __('messages.contact_info_email') }}</h5>
                        <a href="mailto:info@konoz.com" class="text-decoration-none d-block" style="font-size: 1.15rem; color: #333; font-weight: 500;">info@konoz.com</a>
                    </div>
                    <div class="contact-info-card text-center p-4 p-md-5">
                        <div class="contact-icon mx-auto mb-3">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <h5 class="fw-bold mb-2" style="color: #0F6D80; font-size: 1.1rem;">{{ __('messages.contact_info_phone') }}</h5>
                        <a href="tel:+201001234567" class="text-decoration-none d-block" style="font-size: 1.15rem; color: #333; font-weight: 500;">+20 100 123 4567</a>
                    </div>
                    <div class="contact-info-card text-center p-4 p-md-5">
                        <div class="contact-icon mx-auto mb-3">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <h5 class="fw-bold mb-2" style="color: #0F6D80; font-size: 1.1rem;">{{ __('messages.contact_info_address') }}</h5>
                        <span class="d-block" style="font-size: 1.15rem; color: #333; font-weight: 500; line-height: 1.7;">مصر - القاهرة</span>
                    </div>
                </div>
                <style>
                    .contact-info-card {
                        background: #fff;
                        border: 1px solid rgba(15, 109, 128, 0.08);
                        border-radius: 16px;
                        transition: all 0.3s ease;
                        box-shadow: 0 4px 20px rgba(15, 109, 128, 0.06);
                    }
                    .contact-info-card:hover {
                        border-color: #0F6D80;
                        box-shadow: 0 8px 32px rgba(15, 109, 128, 0.12);
                        transform: translateY(-2px);
                    }
                    .contact-icon {
                        width: 64px;
                        height: 64px;
                        border-radius: 14px;
                        background: linear-gradient(135deg, rgba(15,109,128,0.1), rgba(15,109,128,0.05));
                        color: #0F6D80;
                        font-size: 1.75rem;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }
                    .contact-info-card a:hover {
                        color: #0F6D80;
                    }
                </style>
            </div>
        </div>
    </div>
@endsection

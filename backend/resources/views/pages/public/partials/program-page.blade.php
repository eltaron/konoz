@php
    $_text = fn($key, $default = '') => $key ? ($contents[$key]?->value ?? $default) : $default;
    $_html = fn($key, $default = '') => $key ? ($contents[$key]?->value ?? $default) : $default;
    $hasStats = !empty($stats);
    $p = $prefix ?? '';
@endphp

<style>
    /* ===== Hero ===== */
    .pp-hero {
        position: relative;
        background: linear-gradient(155deg, #0a4a56 0%, #0F6D80 35%, #157a8c 100%);
        overflow: hidden;
        padding: 76px 0 54px;
    }
    .pp-hero-bg {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.22;
        pointer-events: none;
    }
    .pp-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(10, 74, 86, 0.55) 0%, rgba(15, 109, 128, 0.35) 100%);
        pointer-events: none;
        z-index: 1;
    }
    .pp-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg width='72' height='72' viewBox='0 0 72 72' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M72 0L0 72M36 0L0 36M72 36L36 72' stroke='%23ffffff' stroke-opacity='0.05' fill='none'/%3E%3C/svg%3E");
        pointer-events: none;
    }

    .pp-hero-content { position: relative; z-index: 2; }
    .pp-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(8px);
        color: #fff;
        border-radius: 50px;
        padding: 8px 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 22px;
    }
    .pp-hero-badge i { color: #d89b1d; }
    .pp-hero-title { font-size: clamp(2rem, 4.5vw, 3rem); font-weight: 800; color: #fff; line-height: 1.25; text-shadow: 0 4px 30px rgba(0,0,0,0.35); }

    .pp-stats {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 16px;
        margin-top: 26px;
    }
    .pp-stat {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(10px);
        border-radius: 18px;
        padding: 16px 26px;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 170px;
    }
    .pp-stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        background: linear-gradient(135deg, rgba(216,155,29,0.3), rgba(216,155,29,0.1));
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: #d89b1d;
        flex-shrink: 0;
    }
    .pp-stat-value { font-size: 1.3rem; font-weight: 800; color: #fff; line-height: 1.1; }

    /* ===== Sections ===== */
    .pp-section-tag {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, rgba(15,109,128,0.1), rgba(216,155,29,0.1));
        color: #0F6D80;
        border-radius: 50px;
        padding: 7px 18px;
        font-size: 0.82rem;
        font-weight: 700;
        margin-bottom: 14px;
    }
    .pp-section-title { font-size: clamp(1.7rem, 3.2vw, 2.3rem); font-weight: 800; color: #0F6D80; margin-bottom: 12px; }
    .pp-section-lead { color: #5a6a72; line-height: 1.95; max-width: 720px; margin: 0 auto; }

    /* ===== ماذا نُقدم ===== */
    .pp-intro-wrap {
        background: linear-gradient(150deg, #0a4a56 0%, #0F6D80 48%, #11788d 100%);
        border-radius: 28px;
        padding: 54px 56px;
        position: relative;
        overflow: hidden;
        color: #fff;
        box-shadow: 0 24px 60px rgba(10, 74, 86, 0.26);
    }
    .pp-intro-wrap::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M60 0L0 60M30 0L0 30M60 30L30 60' stroke='%23ffffff' stroke-opacity='0.05' fill='none'/%3E%3C/svg%3E");
        pointer-events: none;
        border-radius: 28px;
    }
    .pp-intro-orb { position: absolute; border-radius: 50%; pointer-events: none; }
    .pp-intro-orb-1 { width: 340px; height: 340px; top: -130px; right: -90px; background: radial-gradient(circle, rgba(216, 155, 29, 0.34), transparent 68%); }
    .pp-intro-orb-2 { width: 280px; height: 280px; bottom: -120px; left: -80px; background: radial-gradient(circle, rgba(255, 255, 255, 0.09), transparent 70%); }
    .pp-intro-inner { position: relative; z-index: 1; }
    .pp-intro-tag {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.22);
        backdrop-filter: blur(8px);
        color: #fff; border-radius: 50px; padding: 8px 20px;
        font-size: 0.82rem; font-weight: 700; margin-bottom: 20px;
    }
    .pp-intro-tag i { color: #f0b52e; }
    .pp-intro-title {
        font-size: clamp(2rem, 4vw, 2.8rem); font-weight: 900; color: #fff;
        margin-bottom: 20px; display: flex; align-items: center; gap: 16px;
    }
    .pp-intro-title-line {
        width: 58px; height: 5px; border-radius: 5px; flex-shrink: 0;
        background: linear-gradient(90deg, #f0b52e, #d89b1d);
        box-shadow: 0 0 20px rgba(216, 155, 29, 0.5);
    }
    .pp-intro-text { color: rgba(255, 255, 255, 0.9) !important; line-height: 2.05; font-size: 1.02rem; max-width: 560px; text-align: justify; }
    .pp-intro-points { list-style: none; padding: 0; margin: 0; display: grid; gap: 14px; height: 100%; align-content: space-evenly; }
    .pp-intro-points li {
        position: relative;
        display: flex; align-items: center; justify-content: flex-end; gap: 16px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.16);
        backdrop-filter: blur(10px);
        border-radius: 18px;
        padding: 18px 22px;
        color: #fff; line-height: 1.8; font-size: 0.98rem;
        transition: all 0.3s ease;
    }
    .pp-intro-points li::before {
        content: ''; position: absolute; right: 0; top: 0; bottom: 0; width: 4px;
        background: linear-gradient(180deg, #f0b52e, #d89b1d);
        border-radius: 4px; opacity: 0; transition: opacity 0.3s ease;
    }
    .pp-intro-points li:hover {
        background: rgba(255, 255, 255, 0.17);
        transform: translateY(-4px);
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.2);
    }
    .pp-intro-points li:hover::before { opacity: 1; }
    .pp-intro-point-num {
        width: 48px; height: 48px; border-radius: 14px; flex-shrink: 0;
        background: linear-gradient(135deg, #f0b52e, #d89b1d);
        color: #fff; font-weight: 800; font-size: 1.05rem;
        display: inline-flex; align-items: center; justify-content: center;
        box-shadow: 0 8px 22px rgba(216, 155, 29, 0.35);
    }
    .pp-intro-point-text { flex: 1 1 auto; min-width: 0; }
    .pp-intro-point-check { margin: 0; color: #f0b52e; font-size: 1.15rem; flex: 0 0 auto; }

    /* Feature cards */
    .pp-card {
        background: #fff;
        border: 1px solid rgba(15, 109, 128, 0.06);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(15, 109, 128, 0.07);
        transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .pp-card:hover {
        border-color: #0F6D80;
        box-shadow: 0 20px 50px rgba(15, 109, 128, 0.14);
        transform: translateY(-6px);
    }
    .pp-card-header {
        background: linear-gradient(135deg, rgba(15,109,128,0.03), transparent);
        border-bottom: 1px solid rgba(15, 109, 128, 0.05);
        padding: 26px 28px 18px;
    }
    .pp-icon-badge {
        width: 62px;
        height: 62px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 14px;
    }
    .pp-icon-badge.teal { background: linear-gradient(135deg, rgba(15,109,128,0.1), rgba(15,109,128,0.04)); color: #0F6D80; }
    .pp-icon-badge.gold { background: linear-gradient(135deg, rgba(216,155,29,0.15), rgba(216,155,29,0.06)); color: #d89b1d; }
    .pp-card-title { font-size: 1.2rem; font-weight: 800; color: #0F6D80; margin: 0; line-height: 1.5; }
    .pp-card-num {
        position: absolute;
        top: 16px;
        left: 20px;
        font-size: 2.6rem;
        font-weight: 900;
        color: rgba(15, 109, 128, 0.07);
        line-height: 1;
        pointer-events: none;
    }
    .pp-card-body { padding: 18px 28px 26px; display: flex; flex-direction: column; gap: 14px; flex-grow: 1; }
    .pp-card-desc { color: #5a6a72; line-height: 1.95; font-size: 0.95rem; margin: 0; }
    .pp-card-body ul { list-style: none; padding: 0; margin: 0; display: grid; gap: 12px; }
    .pp-card-body li {
        position: relative;
        padding: 13px 46px 13px 16px;
        background: linear-gradient(135deg, rgba(15, 109, 128, 0.05), rgba(216, 155, 29, 0.04));
        border: 1px solid rgba(15, 109, 128, 0.09);
        border-radius: 14px;
        color: #4a5a62;
        font-size: 0.9rem;
        line-height: 1.8;
        transition: all 0.25s ease;
    }
    .pp-card-body li::before {
        content: '\f00c';
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        color: #fff;
        font-size: 0.7rem;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0F6D80, #1a8a9e);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        top: 14px;
        right: 12px;
        box-shadow: 0 4px 12px rgba(15, 109, 128, 0.28);
    }
    .pp-card-body li strong { display: block; color: #0F6D80; font-size: 0.95rem; font-weight: 800; margin-bottom: 3px; line-height: 1.5; }
    .pp-card-body li:hover { border-color: rgba(15, 109, 128, 0.35); background: #fff; box-shadow: 0 10px 24px rgba(15, 109, 128, 0.1); transform: translateY(-2px); }

    /* Divider */
    .pp-divider { display: flex; align-items: center; gap: 24px; margin: 60px 0; }
    .pp-divider::before, .pp-divider::after { content: ''; flex: 1; height: 1px; background: linear-gradient(90deg, transparent, rgba(15,109,128,0.15), transparent); }
    .pp-divider-icon {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(15,109,128,0.08), rgba(216,155,29,0.12));
        border: 1px solid rgba(15, 109, 128, 0.08);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #d89b1d;
        font-size: 1.3rem;
    }

    /* Why us */
    .pp-why-card {
        background: #fff;
        border: 1px solid rgba(15, 109, 128, 0.06);
        border-radius: 20px;
        padding: 30px 26px;
        height: 100%;
        text-align: center;
        box-shadow: 0 8px 32px rgba(15, 109, 128, 0.07);
        transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        position: relative;
        overflow: hidden;
    }
    .pp-why-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        height: 4px;
        background: linear-gradient(90deg, #0F6D80, #d89b1d);
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    .pp-why-card:hover { border-color: #d89b1d; box-shadow: 0 20px 50px rgba(216, 155, 29, 0.14); transform: translateY(-6px); }
    .pp-why-card:hover::before { opacity: 1; }
    .pp-why-icon {
        width: 72px;
        height: 72px;
        border-radius: 20px;
        margin: 0 auto 18px;
        background: linear-gradient(135deg, rgba(216,155,29,0.18), rgba(15,109,128,0.08));
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.7rem;
        color: #0F6D80;
    }
    .pp-why-title { font-size: 1.18rem; font-weight: 800; color: #0F6D80; margin-bottom: 12px; }
    .pp-why-desc { color: #5a6a72; font-size: 0.95rem; line-height: 1.9; margin: 0; }

    /* CTA strip */
    .pp-cta {
        background: linear-gradient(135deg, #0a4a56 0%, #0F6D80 60%, #157a8c 100%);
        border-radius: 24px;
        padding: 40px 36px;
        position: relative;
        overflow: hidden;
        text-align: center;
    }
    .pp-cta-content { position: relative; z-index: 1; }
    .pp-cta-icon { font-size: 2.2rem; color: #d89b1d; margin-bottom: 10px; }
    .pp-cta-title { color: #fff; font-size: clamp(1.4rem, 2.8vw, 1.9rem); font-weight: 800; margin-bottom: 22px; }
    .pp-cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #d89b1d;
        color: #fff !important;
        border-radius: 50px;
        padding: 13px 34px;
        font-weight: 700;
        font-size: 1rem;
        text-decoration: none;
        box-shadow: 0 10px 30px rgba(216, 155, 29, 0.35);
        transition: all 0.3s ease;
    }
    .pp-cta-btn:hover { background: #f0b52e; transform: translateY(-2px); box-shadow: 0 14px 36px rgba(216, 155, 29, 0.45); }

    @media (max-width: 991.98px) {
        .pp-hero { padding: 70px 0 50px; }
        .pp-intro-wrap { padding: 40px 30px; }
    }
    @media (max-width: 575.98px) {
        .pp-hero { padding: 50px 0 38px; }
        .pp-stat { min-width: 140px; padding: 13px 16px; }
        .pp-stat-value { font-size: 1.1rem; }
        .pp-cta { padding: 30px 20px; }
        .pp-intro-wrap { padding: 32px 20px; border-radius: 20px; }
        .pp-intro-point-num { width: 42px; height: 42px; font-size: 0.95rem; }
        .pp-intro-points li { padding: 15px 16px; gap: 12px; }
    }
</style>

{{-- ===== Hero ===== --}}
<section class="pp-hero" aria-labelledby="pp-hero-title">
    <img src="{{ asset($heroImage) }}" alt="" class="pp-hero-bg" loading="lazy" aria-hidden="true" onerror="this.remove()" />

    <div class="container pp-hero-content">
        <div class="text-center" data-aos="fade-up">
            <span class="pp-hero-badge"><i class="{{ $heroIcon }}"></i> {{ $_text($p.'_hero_badge', $heroBadge) }}</span>
            <h1 class="pp-hero-title" id="pp-hero-title">{{ $_text($titleText, $titleDefault) }}</h1>

            @if ($hasStats)
                <div class="pp-stats">
                    @foreach ($stats as $stat)
                        <div class="pp-stat">
                            <div class="pp-stat-icon"><i class="{{ $stat['icon'] }}"></i></div>
                            <div>
                                <div class="pp-stat-value">{{ $_text($stat['key'], $stat['default']) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>

<div class="container py-5">

    {{-- ===== ماذا نُقدم؟ ===== --}}
    <div class="pp-intro-wrap mb-5" data-aos="fade-up">
        <div class="pp-intro-orb pp-intro-orb-1" aria-hidden="true"></div>
        <div class="pp-intro-orb pp-intro-orb-2" aria-hidden="true"></div>
        <div class="row g-4 g-lg-5 align-items-stretch pp-intro-inner">
            <div class="col-lg-6 d-flex flex-column justify-content-center">
                <span class="pp-intro-tag"><i class="fa-solid fa-circle-info"></i> {{ $_text($p.'_intro_tag', 'نبذة عن البرنامج') }}</span>
                <h2 class="pp-intro-title">
                    <span class="pp-intro-title-line" aria-hidden="true"></span>
                    {{ $_text($p.'_intro_heading', 'ماذا نُقدم؟') }}
                </h2>
                <p class="pp-intro-text mb-0">{{ $_text($introText, $introDefault) }}</p>
            </div>
            <div class="col-lg-6 d-flex">
                <ul class="pp-intro-points w-100">
                    @foreach ($introPoints as $idx => $point)
                        <li>
                            <span class="pp-intro-point-num">{{ sprintf('%02d', $idx + 1) }}</span>
                            <span class="pp-intro-point-text">{{ $_text($p.'_intro_point_'.($idx + 1), $point['default']) }}</span>
                            <i class="fa-solid fa-circle-check pp-intro-point-check" aria-hidden="true"></i>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    {{-- ===== المحاور والخدمات ===== --}}
    <div class="text-center mb-5" data-aos="fade-up">
        <span class="pp-section-tag"><i class="fa-solid fa-layer-group"></i> {{ $_text($p.'_axes_tag', 'المحاور والخدمات') }}</span>
        <h2 class="pp-section-title">{{ $_text($axesText, $axesDefault) }}</h2>
        <p class="pp-section-lead mb-0">{{ $_text($axesLeadText, $axesLeadDefault) }}</p>
    </div>

    <div class="row g-4" data-aos="fade-up">
        @foreach ($axes as $axis)
            <div class="col-md-6">
                <article class="pp-card h-100">
                    <div class="pp-card-num" aria-hidden="true">{{ sprintf('%02d', $loop->iteration) }}</div>
                    <header class="pp-card-header position-relative">
                        <div class="pp-icon-badge {{ $loop->iteration % 2 ? 'teal' : 'gold' }}">
                            <i class="{{ $axis['icon'] }}"></i>
                        </div>
                        <h3 class="pp-card-title">{{ $_text($axis['title'], $axis['titleDefault']) }}</h3>
                    </header>
                    <div class="pp-card-body">
                        <p class="pp-card-desc">{{ $_text($axis['desc'], $axis['descDefault']) }}</p>
                        {!! $_html($axis['html'], '') !!}
                    </div>
                </article>
            </div>
        @endforeach
    </div>

    <div class="pp-divider" aria-hidden="true">
        <div class="pp-divider-icon"><i class="{{ $dividerIcon }}"></i></div>
    </div>

    {{-- ===== لماذا تختارنا ===== --}}
    <div class="text-center mb-5" data-aos="fade-up">
        <span class="pp-section-tag"><i class="fa-solid fa-star"></i> {{ $_text($p.'_why_tag', 'لماذا نحن') }}</span>
        <h2 class="pp-section-title">{{ $_text($whyTitle, $whyDefault) }}</h2>
    </div>

    <div class="row g-4">
        @foreach ($whys as $why)
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                <div class="pp-why-card">
                    <div class="pp-why-icon"><i class="{{ $why['icon'] }}"></i></div>
                    <h3 class="pp-why-title">{{ $_text($why['title'], $why['titleDefault']) }}</h3>
                    <p class="pp-why-desc">{{ $_text($why['desc'], $why['descDefault']) }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ===== CTA ===== --}}
    <div class="mt-5" data-aos="fade-up">
        <div class="pp-cta">
            <div class="pp-cta-content">
                <div class="pp-cta-icon"><i class="{{ $heroIcon }}"></i></div>
                <h2 class="pp-cta-title">{{ $_text($p.'_cta_title', 'ابدئي رحلة طفلكِ الآن') }}</h2>
                <a href="{{ route('register') }}" class="pp-cta-btn">
                    <i class="fa-solid fa-paper-plane"></i> {{ $_text($p.'_cta_btn', 'سجلي الآن') }}
                </a>
            </div>
        </div>
    </div>
</div>
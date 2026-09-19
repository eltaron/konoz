<meta charset="utf-8" />
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<title>@yield('title', __('messages.teacher_title', ['site_name' => __('messages.site_name')]))</title>
<meta name="description" content="@yield('meta_description', __('messages.teacher_meta_desc'))" />
<link
    href="{{ session('locale', 'ar') === 'ar' ? 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css' : 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' }}"
    rel="stylesheet" id="bootstrap-css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet" />
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
<link href="{{ asset('css/style.css') }}" rel="stylesheet" />
<link href="{{ asset('css/teacher/teacher-base.css') }}" rel="stylesheet" />
<link href="{{ asset('css/direction.css') }}" rel="stylesheet" />

<style>
    .stat-number {
        font-size: 1.8rem;
        font-weight: 800;
        color: #0F6D80;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #6b7a7e;
        font-weight: 500;
    }

    .activity-item {
        padding: 12px 0;
        border-bottom: 1px solid rgba(15, 109, 128, 0.04);
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .chart-container {
        position: relative;
        height: 200px;
    }

    .quick-action-card {
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        transition: all 0.2s;
        cursor: pointer;
        border: 1.5px dashed rgba(15, 109, 128, 0.1);
        background: #fff;
    }

    .quick-action-card:hover {
        border-color: #0F6D80;
        background: rgba(15, 109, 128, 0.02);
        transform: translateY(-2px);
    }
</style>
@stack('styles')

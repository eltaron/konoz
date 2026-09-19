<meta charset="utf-8" />
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<title>@yield('title', __('messages.student_title', ['site_name' => __('messages.site_name')]))</title>
<meta name="description" content="@yield('meta_description', __('messages.student_meta_desc', ['site_name' => __('messages.site_name')]))" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet" />
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
<link href="{{ asset('css/style.css') }}" rel="stylesheet" />
<link href="{{ asset('css/dashboard/dashboard-base.css') }}" rel="stylesheet" />
<link href="{{ asset('css/dashboard/dashboard-index.css') }}" rel="stylesheet" />
<link href="{{ asset('css/direction.css') }}" rel="stylesheet" />

@stack('styles')

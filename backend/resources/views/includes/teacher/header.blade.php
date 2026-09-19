<header class="teacher-header">
  <div>
    <button class="hamburger-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#teacherOffcanvas" aria-label="{{ __('messages.teacher_notifications') }}"><i class="fa-solid fa-bars"></i></button>
    <h6 class="d-inline header-greeting d-none d-sm-inline">{{ __('messages.teacher_header_greeting', ['name' => $teacherName ?? (Auth::user()->name ?? __('messages.teacher_female'))]) }}</h6>
    @if($hasPageTitle ?? true)
    <span class="d-none d-sm-inline header-sep">|</span>
    <span class="d-none d-sm-inline header-page-title">@yield('page_title', __('messages.teacher_dashboard'))</span>
    @endif
  </div>
  <div class="d-flex align-items-center gap-2">
    <span class="small header-last-login d-none d-md-inline">{{ __('messages.teacher_header_last_login', ['time' => $lastLoginText ?? __('messages.teacher_header_last_login_time')]) }}</span>
    <div class="dropdown">
      <a href="#" class="teacher-header-icon position-relative" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('messages.teacher_header_notifications') }}">
        <i class="fa-solid fa-bell"></i>
        @if(($unreadNotificationsCount ?? 0) > 0)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background:#dc3545;font-size:0.55rem;min-width:16px;height:16px;display:flex;align-items:center;justify-content:center;">{{ $unreadNotificationsCount > 9 ? '9+' : $unreadNotificationsCount }}</span>
        @else
        <span class="teacher-badge-dot"></span>
        @endif
      </a>
      <div class="dropdown-menu dropdown-menu-end rounded-3 shadow-sm border-0" style="min-width:300px;padding:0;">
        <div class="px-3 py-2 fw-bold border-bottom d-flex align-items-center justify-content-between" style="color:#1f2937;font-size:0.9rem;">
          <span>{{ __('messages.teacher_header_notifications') }}</span>
          @if(($unreadNotificationsCount ?? 0) > 0)
          <form method="POST" action="{{ route('teacher.notifications.mark-all-read') }}" style="display:inline;">
            @csrf
            <button class="btn p-0 small fw-semibold" style="background:none;border:none;font-size:0.7rem;color:#0F6D80;">{{ __('messages.teacher_notifications_mark_all') }}</button>
          </form>
          @endif
        </div>
        <div style="max-height:280px;overflow-y:auto;">
          @forelse(($notifications ?? collect())->take(5) as $notif)
          @php
            $dotClass = $notif->type === 'new_registration' ? 'text-success' : ($notif->type === 'message' ? 'text-primary' : 'text-warning');
          @endphp
          @if($notif->type === 'message')
          <a class="dropdown-item px-3 py-2" href="{{ $notif->url ?? '#' }}" data-url="{{ $notif->url ?? '#' }}" style="white-space:normal;cursor:pointer;" onclick="handleMessageNotif(event, this);">
          @else
          <a class="dropdown-item px-3 py-2" href="{{ $notif->url ?? '#' }}" style="white-space:normal;">
          @endif
            <div class="d-flex gap-2 align-items-start">
              <div class="mt-1"><i class="fa-solid fa-circle {{ $dotClass }}" style="font-size:0.5rem;"></i></div>
              <div><div class="fw-semibold" style="font-size:0.85rem;color:#1f2937;">{{ $notif->title }}</div><div style="font-size:0.78rem;color:#6b7a7e;">{{ $notif->body }}</div>@if($notif->created_at)<div style="font-size:0.7rem;color:#aab7bc;">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</div>@endif</div>
            </div>
          </a>
          @empty
          <div class="text-center py-3 text-secondary opacity-50 small">{{ __('messages.teacher_notifications_empty') }}</div>
          @endforelse
        </div>
        <a class="dropdown-item text-center py-2 border-top fw-semibold" href="{{ route('teacher.notifications') }}" style="font-size:0.85rem;color:#0F6D80;">{{ __('messages.teacher_header_view_all') }}</a>
      </div>
    </div>
    <div class="dropdown">
      <a href="#" class="teacher-header-icon" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('messages.teacher_header_settings') }}">
        <i class="fa-solid fa-gear"></i>
      </a>
      <ul class="dropdown-menu dropdown-menu-end rounded-3 shadow-sm border-0" style="min-width:200px;padding:8px;">
        <li><a class="dropdown-item rounded-2 py-2" href="{{ route('teacher.settings') }}"><i class="fa-regular fa-user me-2 text-primary opacity-75"></i>{{ __('messages.teacher_header_profile') }}</a></li>
        <li><a class="dropdown-item rounded-2 py-2" href="{{ route('teacher.settings') }}"><i class="fa-solid fa-gear me-2 text-primary opacity-75"></i>{{ __('messages.teacher_header_advanced') }}</a></li>
        <li><hr class="dropdown-divider" /></li>
        <li>
          <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" class="dropdown-item rounded-2 py-2 text-danger" style="border:none;background:none;width:100%;text-align:right;"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i>{{ __('messages.teacher_header_logout') }}</button>
          </form>
        </li>
      </ul>
    </div>
    <div class="dropdown">
      <a href="#" class="d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('messages.teacher_header_profile') }}">
        <img src="{{ asset('images/logo.png') }}" alt="{{ $teacherName ?? __('messages.teacher_female') }}" class="teacher-avatar" />
      </a>
      <ul class="dropdown-menu dropdown-menu-end rounded-3 shadow-sm border-0" style="min-width:200px;padding:8px;">
        <li class="px-3 py-2">
          <p class="fw-bold small mb-0" style="color:#0F6D80;">{{ $teacherName ?? (Auth::user()->name ?? __('messages.teacher_female')) }}</p>
          <small class="text-secondary opacity-75">{{ Auth::user()->email ?? '' }}</small>
        </li>
        <li><hr class="dropdown-divider" /></li>
        <li><a class="dropdown-item rounded-2 py-2" href="{{ route('teacher.settings') }}"><i class="fa-regular fa-user me-2 text-primary opacity-75"></i>{{ __('messages.teacher_header_profile') }}</a></li>
        <li><a class="dropdown-item rounded-2 py-2" href="{{ route('teacher.notifications') }}"><i class="fa-solid fa-bell me-2 text-primary opacity-75"></i>{{ __('messages.teacher_header_notifications') }}</a></li>
        <li><hr class="dropdown-divider" /></li>
        <li>
          <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" class="dropdown-item rounded-2 py-2 text-danger" style="border:none;background:none;width:100%;text-align:right;"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i>{{ __('messages.teacher_header_logout') }}</button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</header>
<script>
function handleMessageNotif(e, el) {
  e.preventDefault();
  fetch('{{ route("teacher.notifications.mark-all-read") }}', {
    method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
  }).catch(function() {}).finally(function() {
    window.location.href = el.getAttribute('data-url');
  });
}
document.addEventListener("submit", function (e) {
  if (e.target.getAttribute("action") && e.target.getAttribute("action").includes("mark-all-read")) {
    e.preventDefault();
    var form = e.target;
    fetch(form.action, { method: "POST", headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}", "Accept": "application/json" }, body: new URLSearchParams(new FormData(form)) })
      .then(function (r) { return r.json(); })
      .then(function (result) {
        if (result.status === "ok") {
          var badge = form.closest(".dropdown-menu").querySelector(".badge");
          if (badge) badge.remove();
        }
      });
  }
});
</script>


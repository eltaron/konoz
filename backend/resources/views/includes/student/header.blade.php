<header class="dash-header">
  <div class="d-flex align-items-center gap-2 d-md-none">
    <button class="btn p-2 text-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileDashMenu"><i class="fa-solid fa-bars fs-5"></i></button>
  </div>
  <div class="d-flex align-items-center gap-2 flex-grow-1 justify-content-between">
    <div class="d-flex align-items-center gap-3">
      <a href="{{ route('home') }}"><img src="{{ asset('images/logo.png') }}" alt="{{ __('messages.site_name') }}" style="height: 40px;" /></a>
      
    </div>
    <div class="d-flex align-items-center gap-2 search-desktop">
      <div class="d-flex align-items-center gap-2 px-3 py-1 rounded-3" style="background: #f1f4f5;">
        <i class="fa-solid fa-search text-secondary opacity-50 small"></i>
        <input type="text" class="dash-search" placeholder="{{ __('messages.student_search') }}" id="globalSearch" onkeydown="if(event.key==='Enter' && this.value.trim()) window.location.href='{{ route('student.courses') }}?q='+encodeURIComponent(this.value.trim());" />
      </div>
    </div>
    <div class="d-flex align-items-center gap-2">
      <div class="dropdown">
        <button class="btn p-2 text-primary position-relative" style="background: transparent; border: none;" data-bs-toggle="dropdown">
          <i class="fa-regular fa-bell fs-6"></i>
          @php $unreadCount = $unreadNotifications->count() ?? 0; @endphp
          @if($unreadCount > 0)
          <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill" style="background: var(--pumpkin); font-size: 0.55rem; min-width: 16px; height: 16px; display: flex; align-items: center; justify-content: center;">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
          @endif
        </button>
        <div class="dropdown-menu dropdown-menu-end shadow-sm rounded-3 p-2" style="width: 280px; border: none;">
          <div class="d-flex align-items-center justify-content-between px-3 py-2">
            <h6 class="small fw-bold mb-0" style="color: #0F6D80;">{{ __('messages.student_notifications') }}</h6>
            @if($unreadCount > 0)
            <form method="POST" action="{{ route('student.notifications.read-all') }}" style="display:inline;">
              @csrf
              <button class="btn p-0 small text-primary" style="background:none;border:none;font-size:0.7rem;">{{ __('messages.student_mark_read') }}</button>
            </form>
            @endif
          </div>
          @forelse($notifications->take(5) as $notif)
          <a class="dropdown-item small rounded-2 py-2 d-flex align-items-start gap-2 {{ $notif->read_at ? '' : 'fw-bold' }}" href="{{ $notif->url ?? '#' }}" style="white-space:normal;">
            <span>{{ $notif->icon ?? '📌' }}</span>
            <span>{{ $notif->body ?? $notif->data['message'] ?? $notif->title ?? '' }}</span>
          </a>
          @empty
          <div class="text-center py-3 text-secondary opacity-50 small">{{ __('messages.student_no_notifications') }}</div>
          @endforelse
        </div>
      </div>
      <div class="dropdown">
        <button class="btn p-2 text-primary" style="background: transparent; border: none;" data-bs-toggle="dropdown"><i class="fa-solid fa-gear fs-6"></i></button>
        <div class="dropdown-menu dropdown-menu-end shadow-sm rounded-3 p-2" style="border: none;">
          <a class="dropdown-item small rounded-2 py-2" href="{{ route('student.settings') }}"><i class="fa-solid fa-sliders me-2" style="width: 16px;"></i>{{ __('messages.student_settings') }}</a>
          <a class="dropdown-item small rounded-2 py-2" href="{{ route('student.settings') }}#profile"><i class="fa-solid fa-user me-2" style="width: 16px;"></i>{{ __('messages.student_profile') }}</a>
          <hr class="my-1" />
          <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" class="dropdown-item small rounded-2 py-2 text-danger" style="border:none;background:none;width:100%;text-align:right;"><i class="fa-solid fa-right-from-bracket me-2" style="width: 16px;"></i>{{ __('messages.nav_logout_full') }}</button>
          </form>
        </div>
      </div>
      <div class="dropdown">
        <a href="#" class="text-decoration-none" data-bs-toggle="dropdown">
          <img src="{{ $student->avatar_url ?? asset('images/logo.png') }}" alt="{{ $student->name ?? __('messages.student_student_female') }}" class="rounded-circle border-2" style="width: 40px; height: 40px; object-fit: cover; border-color: #F5BD58 !important;" />
        </a>
        <div class="dropdown-menu dropdown-menu-end shadow-sm rounded-3 p-2" style="border: none;">
          <div class="px-3 py-2">
            <p class="fw-bold small mb-0" style="color: #0F6D80;">{{ $student->name ?? __('messages.student_student_female') }}</p>
            <small class="text-secondary opacity-75">{{ $student->email ?? '' }}</small>
          </div>
          <hr class="my-1" />
          <a class="dropdown-item small rounded-2 py-2" href="{{ route('student.settings') }}"><i class="fa-solid fa-user me-2" style="width: 16px;"></i>{{ __('messages.student_profile') }}</a>
          <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" class="dropdown-item small rounded-2 py-2 text-danger" style="border:none;background:none;width:100%;text-align:right;"><i class="fa-solid fa-right-from-bracket me-2" style="width: 16px;"></i>{{ __('messages.nav_logout_full') }}</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</header>

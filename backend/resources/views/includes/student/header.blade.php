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
    <div class="d-flex align-items-center gap-1">
      <div class="dropdown">
        <button type="button" class="dropdown-toggle-btn position-relative" data-bs-toggle="dropdown" aria-label="{{ __('messages.student_notifications') }}" title="{{ __('messages.student_notifications') }}">
          <i class="fa-regular fa-bell"></i>
          @php $unreadCount = $unreadNotifications->count() ?? 0; @endphp
          @if($unreadCount > 0)
          <span class="notif-dot">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
          @endif
        </button>
        <div class="dropdown-menu dropdown-menu-end notif-menu">
          <div class="dropdown-menu-head">
            <span class="notif-head-icon"><i class="fa-solid fa-bell"></i></span>
            <h6>{{ __('messages.student_notifications') }}</h6>
            @if($unreadCount > 0)
            <form method="POST" action="{{ route('student.notifications.read-all') }}" style="display:inline;">
              @csrf
              <button type="submit" class="mark-all">{{ __('messages.student_mark_read') }}</button>
            </form>
            @endif
          </div>
          @forelse($notifications->take(5) as $notif)
          <a class="dropdown-item notif-item {{ $notif->read_at ? '' : 'fw-bold' }}" href="{{ $notif->url ?? '#' }}">
            <span class="notif-icon">{{ $notif->icon ?? '📌' }}</span>
            <span class="notif-text">{{ $notif->body ?? $notif->data['message'] ?? $notif->title ?? '' }}</span>
            @if(!$notif->read_at)
            <span class="notif-unread-dot"></span>
            @endif
          </a>
          @empty
          <div class="text-center py-4 text-secondary opacity-50 small">{{ __('messages.student_no_notifications') }}</div>
          @endforelse
        </div>
      </div>
      <div class="dropdown">
        <button type="button" class="dropdown-toggle-btn" data-bs-toggle="dropdown" aria-label="{{ __('messages.student_settings') }}" title="{{ __('messages.student_settings') }}">
          <i class="fa-solid fa-gear"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
          <a class="dropdown-item" href="{{ route('student.settings') }}"><span class="di-icon"><i class="fa-solid fa-sliders"></i></span>{{ __('messages.student_settings') }}</a>
          <a class="dropdown-item" href="{{ route('student.settings') }}#profile"><span class="di-icon"><i class="fa-solid fa-user"></i></span>{{ __('messages.student_profile') }}</a>
          <hr class="dropdown-divider" />
          <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" class="dropdown-item text-danger" style="border:none;background:none;width:100%;"><span class="di-icon"><i class="fa-solid fa-right-from-bracket"></i></span>{{ __('messages.nav_logout_full') }}</button>
          </form>
        </div>
      </div>
      <div class="dropdown">
        <a href="#" class="profile-avatar-link" data-bs-toggle="dropdown" title="{{ $student->name ?? __('messages.student_student_female') }}">
          <img src="{{ $student->avatar_url ?? asset('images/logo.png') }}" alt="{{ $student->name ?? __('messages.student_student_female') }}" class="rounded-circle border-2" style="width: 40px; height: 40px; object-fit: cover; border-color: #F5BD58 !important;" />
        </a>
        <div class="dropdown-menu dropdown-menu-end profile-menu">
          <div class="profile-menu-head">
            <img src="{{ $student->avatar_url ?? asset('images/logo.png') }}" alt="{{ $student->name ?? __('messages.student_student_female') }}" />
            <div class="pm-text">
              <p class="pm-name mb-0">{{ $student->name ?? __('messages.student_student_female') }}</p>
              <small class="pm-email">{{ $student->email ?? '' }}</small>
            </div>
          </div>
          <hr class="dropdown-divider" />
          <a class="dropdown-item" href="{{ route('student.settings') }}"><span class="di-icon"><i class="fa-solid fa-user"></i></span>{{ __('messages.student_profile') }}</a>
          <hr class="dropdown-divider" />
          <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" class="dropdown-item text-danger" style="border:none;background:none;width:100%;"><span class="di-icon"><i class="fa-solid fa-right-from-bracket"></i></span>{{ __('messages.nav_logout_full') }}</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</header>
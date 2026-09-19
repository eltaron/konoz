<footer class="footer-section">
  <div class="footer-glow footer-glow-1"></div>
  <div class="footer-glow footer-glow-2"></div>
  <div class="container">
    <div class="footer-inner text-center">
      <a href="{{ route('home') }}" class="footer-brand">
        <img src="{{ asset('images/logo.png') }}" alt="{{ __('messages.site_name') }}" class="footer-logo" />
      </a>
      <p class="footer-desc mx-auto">{{ __('messages.site_desc') }}</p>

      <div class="footer-socials justify-content-center">
        @foreach ([
            'social_facebook' => ['icon' => 'fa-facebook-f', 'label' => __('messages.footer_social_facebook')],
            'social_twitter' => ['icon' => 'fa-x-twitter', 'label' => __('messages.footer_social_twitter')],
            'social_instagram' => ['icon' => 'fa-instagram', 'label' => __('messages.footer_social_instagram')],
            'social_whatsapp' => ['icon' => 'fa-whatsapp', 'label' => __('messages.footer_social_whatsapp')],
            'social_telegram' => ['icon' => 'fa-telegram', 'label' => __('messages.footer_social_telegram')],
        ] as $key => $social)
          @if (!empty($socialLinks[$key]))
            <a href="{{ $socialLinks[$key] }}" target="_blank" rel="noopener" class="footer-social"
              aria-label="{{ $social['label'] }}"><i class="fa-brands {{ $social['icon'] }}"></i></a>
          @endif
        @endforeach
      </div>

      <nav class="footer-nav justify-content-center" aria-label="{{ __('messages.footer_quick_links') }}">
        <a href="{{ route('privacy') }}">{{ __('messages.page_privacy') }}</a>
        <a href="{{ route('terms') }}">{{ __('messages.page_terms') }}</a>
        <a href="{{ route('parenting-support') }}">{{ __('messages.nav_sublink_support') }}</a>
      </nav>

      <p class="footer-copy">{{ __('messages.copyright') }} &copy; {{ date('Y') }} {{ __('messages.site_name') }}</p>
    </div>
  </div>
</footer>

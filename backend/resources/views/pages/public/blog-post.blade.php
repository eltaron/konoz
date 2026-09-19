@extends('layouts.public')

@section('title', __('messages.blog_post_title', ['title' => $post->title, 'site_name' => __('messages.site_name')]))
@section('meta_description', __('messages.blog_post_meta', ['title' => $post->title]))
@section('meta_robots', 'index, follow')

@section('content')
      <div class="page-hero text-center" data-aos="fade-up"><div class="hero-orb hero-orb-1"></div><div class="hero-orb hero-orb-2"></div><div class="hero-dot hero-dot-1"></div><div class="hero-dot hero-dot-2"></div><div class="hero-dot hero-dot-3"></div>
        <div class="container">
          <div class="page-hero-icon"><i class="fa-solid fa-book-open"></i></div>
          <h1 class="fw-bold">{{ __('messages.blog_post_heading') }}</h1>
          <p>{{ __('messages.blog_post_subtitle') }}</p>
        </div>
      </div>

      <div class="container py-5">
        <div class="row justify-content-center">
          <div class="col-lg-10">
            <article class="legal-card" data-aos="fade-up">
              <div class="text-center mb-4">
                <span class="blog-card-tag" style="display: inline-block; margin-bottom: 0.8rem;">{{ $post->tag }}</span>
                <h1 class="fw-bold text-heading mb-3" style="font-size: 1.8rem;">{{ $post->title }}</h1>
                <div class="d-flex align-items-center justify-content-center gap-3 text-secondary small opacity-75 mb-4">
                  <span><i class="fa-regular fa-calendar ml-1"></i>{{ $post->created_at->format('Y/m/d') }}</span>
                  <span><i class="fa-regular fa-clock ml-1"></i>{{ $post->read_time }} {{ __('messages.blog_post_min_read') }}</span>
                </div>
                <div class="mb-4">
                  <img src="{{ asset($post->image ?? 'images/logo.png') }}" alt="{{ $post->title }}" style="width:100%;max-height:360px;object-fit:cover;border-radius:16px;" loading="lazy" />
                </div>
              </div>
              <div class="post-content">{!! $post->content !!}</div>
              <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 pt-4 mt-4 border-top border-primary border-opacity-10">
                <div class="d-flex align-items-center gap-2">
                  <span class="small text-secondary opacity-75">{{ __('messages.blog_post_share') }}</span>
                  <a href="#" class="social-icon" onclick="return sharePost(event, 'facebook')" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                  <a href="#" class="social-icon" onclick="return sharePost(event, 'whatsapp')" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                  <a href="#" class="social-icon" onclick="return sharePost(event, 'telegram')" aria-label="Telegram"><i class="fa-brands fa-telegram"></i></a>
                  <a href="#" class="social-icon" onclick="return sharePost(event, 'x')" aria-label="X"><i class="fa-brands fa-x-twitter"></i></a>
                  <a href="#" class="social-icon" onclick="return sharePost(event, 'copy')" aria-label="Copy link"><i class="fa-regular fa-copy"></i></a>
                </div>
                <a href="{{ route('blog') }}" class="btn btn-outline-primary rounded-3 px-4 py-2"><i class="fa-solid fa-arrow-right ml-2"></i>{{ __('messages.blog_post_back') }}</a>
              </div>
            </article>
          </div>
        </div>
      </div>
@endsection

@push('scripts')
<script>
function sharePost(e, platform) {
  if (e) e.preventDefault();
  var url = window.location.href;
  var text = @json($post->title);
  switch (platform) {
    case "facebook": window.open("https://www.facebook.com/sharer/sharer.php?u=" + encodeURIComponent(url), "_blank", "width=600,height=500"); break;
    case "whatsapp": window.open("https://api.whatsapp.com/send?text=" + encodeURIComponent(text + " " + url), "_blank"); break;
    case "telegram": window.open("https://t.me/share/url?url=" + encodeURIComponent(url) + "&text=" + encodeURIComponent(text), "_blank", "width=600,height=500"); break;
    case "x": window.open("https://twitter.com/intent/tweet?url=" + encodeURIComponent(url) + "&text=" + encodeURIComponent(text), "_blank", "width=600,height=500"); break;
    case "copy":
      var done = function() { Swal.fire({ icon: "success", title: @json(__('messages.blog_post_copied_title')), text: @json(__('messages.blog_post_copied_text')), timer: 2000, showConfirmButton: false }); };
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url).then(done);
      } else {
        var ta = document.createElement("textarea");
        ta.value = url; document.body.appendChild(ta); ta.select();
        document.execCommand("copy"); document.body.removeChild(ta); done();
      }
      break;
  }
  return false;
}
</script>
@endpush

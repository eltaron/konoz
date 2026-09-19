(function () {
  var locale = document.documentElement.getAttribute('lang') || 'ar';
  var dir = locale === 'ar' ? 'rtl' : 'ltr';
  document.documentElement.setAttribute('dir', dir);

  function switchLanguage(locale) {
    var dir = locale === 'ar' ? 'rtl' : 'ltr';
    document.documentElement.setAttribute('dir', dir);
    document.documentElement.setAttribute('lang', locale);

    var link = document.querySelector('[data-lang-switch]');
    if (link) {
      var segments = link.getAttribute('href').split('/');
      segments[segments.length - 1] = locale === 'ar' ? 'en' : 'ar';
      link.setAttribute('href', segments.join('/'));
      link.innerHTML = locale === 'ar' ? 'English' : 'العربية';
    }
  }

  document.addEventListener('click', function (e) {
    var target = e.target.closest('[data-lang-switch]');
    if (target) {
      e.preventDefault();
      var href = target.getAttribute('href');
      if (href) {
        window.location.href = href;
      }
    }
  });

  window.switchLanguage = switchLanguage;
})();

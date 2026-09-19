document.addEventListener("DOMContentLoaded", function () {
  // Hide loader
  setTimeout(function () {
    var loader = document.getElementById("page-loader"); if (loader) loader.classList.add("hidden");
  }, 800);

  // AOS init
  if (typeof AOS !== "undefined") {
    AOS.init({
      duration: 600,
      once: true,
      easing: 'ease-out-cubic',
      offset: 50,
    });
  }

  // Header scroll effect
  var header = document.querySelector(".header-nav");
  if (header) {
    window.addEventListener("scroll", function () {
      header.classList.toggle("scrolled", window.scrollY > 50);
    });
  }

  // SweetAlert for main action buttons
  document.querySelectorAll(".btn-custom").forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      Swal.fire({
        title: "مرحباً بك!",
        text: "جاري العمل على تجهيز نظام الحجز..",
        icon: "info",
        confirmButtonText: "فهمت",
        confirmButtonColor: "#0F6D80",
      });
    });
  });

  // Support fab button
  var supportFab = document.getElementById("support-fab");
  if (supportFab) {
    supportFab.addEventListener("click", function () {
      var href = this.getAttribute("href");
      if (href && href !== "#") {
        window.location.href = href;
      }
    });
  }

  // Quiz option selection
  document.querySelectorAll(".quiz-option-btn").forEach(function (opt) {
    opt.addEventListener("click", function () {
      document.querySelectorAll(".quiz-option-btn").forEach(function (o) {
        o.classList.remove("quiz-option-active");
      });
      this.classList.add("quiz-option-active");
    });
  });

  // Certificates Gallery Modal
  var certData = window.CERT_DATA || [
    { src: "images/certificates/أجازات جزرية/1.jpeg", title: "إجازة الجزرية" },
    { src: "images/certificates/دورة زهرات كنوز/1.jpeg", title: "دورة زهرات كنوز" },
    { src: "images/certificates/اجازات نور بيان/1.jpeg", title: "إجازة نور البيان" },
    { src: "images/certificates/اطفال ختمواالقران الكريم كاملا/1.jpeg", title: "ختم القرآن كاملاً" },
    { src: "images/certificates/شهادات اطفال/1.jpeg", title: "شهادات الأطفال" },
    { src: "images/certificates/اجازات فتح الرحمن/1.jpeg", title: "إجازة فتح الرحمن" },
    { src: "images/certificates/جزء عم وتبارك/1.jpeg", title: "جزء عم وتبارك" },
    { src: "images/certificates/شهادة المستوي الاول/1.jpeg", title: "المستوى الأول" }
  ];

  var certModal = document.getElementById("certModal");
  var certModalImg = document.getElementById("certModalImg");
  var certModalTitle = document.getElementById("certModalTitle");
  var certModalCount = document.getElementById("certModalCount");
  var certModalThumbs = document.getElementById("certModalThumbs");
  var certNavPrev = document.getElementById("certNavPrev");
  var certNavNext = document.getElementById("certNavNext");
  var certIndex = 0;

  function renderCertThumbs() {
    if (!certModalThumbs) return;
    certModalThumbs.innerHTML = "";
    certData.forEach(function (cert, i) {
      var t = document.createElement("button");
      t.type = "button";
      t.className = "cert-thumb" + (i === certIndex ? " active" : "");
      t.setAttribute("data-index", i);
      t.setAttribute("aria-label", cert.title);
      t.innerHTML = '<img src="' + cert.src + '" alt="' + cert.title + '" loading="lazy" />';
      t.addEventListener("click", function () {
        showCert(parseInt(this.getAttribute("data-index")));
      });
      certModalThumbs.appendChild(t);
    });
  }

  function showCert(i) {
    if (!certData.length) return;
    certIndex = i;
    certModalImg.src = certData[i].src;
    certModalTitle.textContent = certData[i].title;
    certModalCount.textContent = (i + 1) + " / " + certData.length;
    var thumbs = certModalThumbs.querySelectorAll(".cert-thumb");
    thumbs.forEach(function (t, k) {
      t.classList.toggle("active", k === i);
    });
    var activeThumb = certModalThumbs.querySelector(".cert-thumb.active");
    if (activeThumb && activeThumb.scrollIntoView) {
      activeThumb.scrollIntoView({ behavior: "smooth", block: "nearest", inline: "center" });
    }
  }

  function certNext() {
    showCert((certIndex + 1) % certData.length);
  }

  function certPrev() {
    showCert((certIndex - 1 + certData.length) % certData.length);
  }

  document.querySelectorAll(".cert-gallery-card, .cert-item").forEach(function (item) {
    item.addEventListener("click", function () {
      if (!certModal) return;
      var slideIndex = parseInt(this.getAttribute("data-slide"));
      if (isNaN(slideIndex)) slideIndex = 0;
      renderCertThumbs();
      showCert(slideIndex);
    });
  });

  if (certNavNext) certNavNext.addEventListener("click", certNext);
  if (certNavPrev) certNavPrev.addEventListener("click", certPrev);
  if (certModal) {
    certModal.addEventListener("keydown", function (e) {
      if (e.key === "ArrowRight") certPrev();
      if (e.key === "ArrowLeft") certNext();
      if (e.key === "Escape" && bootstrap.Modal && bootstrap.Modal.getInstance(certModal)) {
        bootstrap.Modal.getInstance(certModal).hide();
      }
    });
  }

  // Payment Modal
  var paymentData = {
    vodafone: {
      icon: '<i class="fa-solid fa-wallet fs-2"></i>',
      title: "فودافون كاش",
      desc: "يمكنك الدفع عبر فودافون كاش على الرقم التالي",
      value: "0100 123 4567"
    },
    instapay: {
      icon: '<i class="fa-solid fa-coins fs-2"></i>',
      title: "إنستا باي",
      desc: "يمكنك الدفع عبر إنستا باي على الحساب التالي",
      value: "info@konozalquran.com"
    },
    card: {
      icon: '<i class="fa-solid fa-credit-card fs-2"></i>',
      title: "ماستركارد / فيزا",
      desc: "يمكنك الدفع عبر البطاقات الائتمانية",
      value: "**** **** **** 4242"
    }
  };

  var paymentModal = document.getElementById("paymentModal");
  var paymentModalIcon = document.getElementById("paymentModalIcon");
  var paymentModalTitle = document.getElementById("paymentModalTitle");
  var paymentModalDesc = document.getElementById("paymentModalDesc");
  var paymentModalValue = document.getElementById("paymentModalValue");
  var paymentModalCopy = document.getElementById("paymentModalCopy");

  document.querySelectorAll(".payment-item").forEach(function (item) {
    item.addEventListener("click", function () {
      var type = this.getAttribute("data-payment");
      var data = paymentData[type];
      if (!data) return;
      paymentModalIcon.innerHTML = data.icon;
      paymentModalTitle.textContent = data.title;
      paymentModalDesc.textContent = data.desc;
      paymentModalValue.textContent = data.value;
      paymentModalCopy.setAttribute("data-copy", data.value);
    });
  });

  if (paymentModalCopy) paymentModalCopy.addEventListener("click", function () {
    var text = this.getAttribute("data-copy");
    navigator.clipboard.writeText(text).then(function () {
      Swal.fire({
        title: "تم النسخ!",
        text: "تم نسخ " + text + " إلى الحافظة",
        icon: "success",
        timer: 2000,
        showConfirmButton: false,
      });
    });
  });

  // Copy payment numbers (backward compat)
  document.querySelectorAll("#payment .btn").forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      var text = this.closest(".d-flex").querySelector("span").textContent;
      navigator.clipboard.writeText(text).then(function () {
        Swal.fire({
          title: "تم النسخ!",
          text: "تم نسخ " + text + " إلى الحافظة",
          icon: "success",
          timer: 2000,
          showConfirmButton: false,
        });
      });
    });
  });

  // Single-row scroller navigation
  window.scrollRow = function (id, dir) {
    var el = document.getElementById(id);
    if (!el) return;
    var step = Math.round(el.clientWidth * 0.8);
    el.scrollBy({ left: dir * step, behavior: "smooth" });
  };

  // Review lightbox
  window.openReview = function (id, src) {
    var url = src || ("images/reviews/" + id + ".jpeg");
    Swal.fire({
      html: '<img src="' + url + '" alt="تقييم" style="max-width:100%;max-height:85vh;border-radius:12px;display:block" />',
      showConfirmButton: false,
      showCloseButton: true,
      background: "rgba(0,0,0,0.85)",
      width: "auto",
      padding: "1rem",
      customClass: { closeButton: "text-white fs-4" },
    });
  };

  // Support ticket form
  window.submitSupport = function (e) {
    e.preventDefault();
    Swal.fire({
      icon: "success",
      title: "تم استلام تذكرتك!",
      text: "سيقوم فريق الدعم بالتواصل معك في أقرب وقت ممكن",
      confirmButtonColor: "#0F6D80",
      confirmButtonText: "حسناً",
    });
    e.target.reset();
    return false;
  };

  // Contact form
  window.submitContact = function (e) {
    e.preventDefault();
    Swal.fire({
      icon: "success",
      title: "تم إرسال رسالتك!",
      text: "سنقوم بالرد عليك في أقرب وقت ممكن",
      confirmButtonColor: "#0F6D80",
      confirmButtonText: "حسناً",
    });
    e.target.reset();
    return false;
  };
});

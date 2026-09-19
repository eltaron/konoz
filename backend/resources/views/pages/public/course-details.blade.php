@extends('layouts.public')

@section('title', __('messages.page_course_details', ['site_name' => __('messages.site_name')]))
@section('meta_description', __('messages.page_course_details_meta', ['site_name' => __('messages.site_name')]))
@section('meta_robots', 'index, follow')

@section('content')
    <div id="courseDetails">
      <style>
        .hero-section {
          position: relative;
          /* min-height: 372px; */
          background: linear-gradient(145deg, #0a4a56 0%, #0F6D80 35%, #157a8c 65%, #d89b1d 100%);
          overflow: hidden;
          padding-bottom: 38px;
        }
        .hero-section::before {
          content: '';
          position: absolute;
          inset: 0;
          background:
            radial-gradient(ellipse 80% 50% at 20% 10%, rgba(216, 155, 29, 0.18) 0%, transparent 60%),
            radial-gradient(ellipse 60% 40% at 80% 90%, rgba(15, 109, 128, 0.15) 0%, transparent 55%),
            url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
          pointer-events: none;
        }
        .hero-shape {
          position: absolute;
          border-radius: 50%;
          filter: blur(60px);
          opacity: 0.35;
          pointer-events: none;
          animation: float 8s ease-in-out infinite;
        }
        .hero-shape-1 { width: 320px; height: 320px; background: #d89b1d; top: -80px; right: -80px; animation-delay: 0s; }
        .hero-shape-2 { width: 240px; height: 240px; background: #0F6D80; bottom: -50px; left: -60px; animation-delay: -3s; }
        .hero-shape-3 { width: 160px; height: 160px; background: #1a8a9e; top: 40%; right: 10%; animation-delay: -5s; }
        @keyframes float { 0%, 100% { transform: translate(0, 0) scale(1); } 50% { transform: translate(20px, -15px) scale(1.05); } }

        .hero-content { position: relative; z-index: 2; }

        .hero-back-btn {
          background: rgba(255, 255, 255, 0.12);
          backdrop-filter: blur(8px);
          border: 1px solid rgba(255, 255, 255, 0.2);
          color: #fff;
          padding: 8px 18px;
          border-radius: 50px;
          font-size: 0.8rem;
          font-weight: 600;
          transition: all 0.3s ease;
          display: inline-flex;
          align-items: center;
          gap: 6px;
          text-decoration: none;
        }
        .hero-back-btn:hover { background: rgba(255, 255, 255, 0.2); color: #fff; transform: translateX(-3px); }

        .hero-category-badge {
          background: linear-gradient(135deg, rgba(216, 155, 29, 0.25), rgba(216, 155, 29, 0.1));
          backdrop-filter: blur(8px);
          border: 1px solid rgba(216, 155, 29, 0.4);
          color: #fff;
          padding: 8px 18px;
          border-radius: 50px;
          font-size: 0.7rem;
          font-weight: 700;
          text-transform: uppercase;
          letter-spacing: 0.5px;
        }

        .hero-title {
          font-size: clamp(1.75rem, 4vw, 2.75rem);
          font-weight: 800;
          line-height: 1.25;
          color: #fff;
          text-shadow: 0 4px 24px rgba(0,0,0,0.25);
        }

        .hero-meta {
          display: flex;
          flex-wrap: wrap;
          gap: 16px;
          align-items: center;
        }
        .hero-meta-item {
          display: inline-flex;
          align-items: center;
          gap: 6px;
          background: rgba(255,255,255,0.08);
          backdrop-filter: blur(8px);
          border: 1px solid rgba(255,255,255,0.12);
          padding: 8px 14px;
          border-radius: 50px;
          color: rgba(255,255,255,0.95);
          font-size: 0.8rem;
          font-weight: 500;
          transition: all 0.3s ease;
        }
        .hero-meta-item:hover { background: rgba(255,255,255,0.15); color: #fff; }
        .hero-meta-item i { color: #d89b1d; font-size: 0.85rem; }

        .hero-image-wrapper {
          position: relative;
          display: inline-block;
        }
        .hero-image-wrapper::before {
          content: '';
          position: absolute;
          inset: -12px;
          border-radius: 24px;
          background: linear-gradient(135deg, rgba(216,155,29,0.3), rgba(15,109,128,0.2));
          filter: blur(20px);
          z-index: -1;
          opacity: 0.6;
        }
        .hero-image {
          max-width: 100%;
          max-height: 320px;
          border-radius: 20px;
          box-shadow:
            0 30px 80px rgba(0,0,0,0.35),
            0 0 0 1px rgba(255,255,255,0.05);
          transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1), box-shadow 0.4s ease;
        }
        .hero-image:hover { transform: scale(1.02); box-shadow: 0 40px 100px rgba(0,0,0,0.4), 0 0 0 1px rgba(255,255,255,0.08); }

        @media (max-width: 991.98px) {
          .hero-section { min-height: auto; padding: 60px 0 80px; }
          .hero-image { max-height: 240px; margin-top: 24px; }
          .hero-image-wrapper::before { inset: -8px; filter: blur(16px); }
        }
        @media (max-width: 575.98px) {
          .hero-section { padding: 48px 0 60px; }
          .hero-meta { gap: 10px; }
          .hero-meta-item { padding: 6px 12px; font-size: 0.7rem; }
        }
      </style>

      <section class="hero-section" aria-labelledby="detail-title">
        <div class="hero-shape hero-shape-1" aria-hidden="true"></div>
        <div class="hero-shape hero-shape-2" aria-hidden="true"></div>
        <div class="hero-shape hero-shape-3" aria-hidden="true"></div>

        <div class="container h-100 d-flex align-items-center hero-content">
          <div class="row w-100 align-items-center g-4">
            <div class="col-lg-7" data-aos="fade-right">
              <a href="{{ route('departments') }}" class="hero-back-btn"><i class="fa-solid fa-arrow-right"></i> {{ __('messages.teacher_course_details_back_departments') }}</a>

              <span class="hero-category-badge d-inline-block mt-3" id="detail-category-badge"></span>

              <h1 class="hero-title mt-3" id="detail-title"></h1>

              <div class="hero-meta mt-4" role="list" aria-label="معلومات الدورة">
                <span class="hero-meta-item" id="detail-students" role="listitem"><i class="fa-regular fa-user"></i><span></span></span>
                <span class="hero-meta-item" id="detail-level" role="listitem"><i class="fa fa-signal"></i><span></span></span>
                <span class="hero-meta-item" id="detail-price" role="listitem"><i class="fa-regular fa-credit-card"></i><span></span></span>
              </div>
            </div>

            <div class="col-lg-5 text-center d-none d-lg-block" data-aos="zoom-in" data-aos-delay="150">
              <div class="hero-image-wrapper">
                <img id="detail-hero-img" class="hero-image" src="" alt="" loading="lazy" />
              </div>
            </div>
          </div>
        </div>
      </section>

      <div class="container py-5">
        <div class="row g-5">
          <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" data-aos="fade-up">
              <h4 class="fw-bold mb-3" style="color: #0F6D80;">عن الدورة</h4>
              <p class="text-secondary opacity-75" id="detail-desc"></p>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" data-aos="fade-up" id="detail-curriculum-card">
              <h4 class="fw-bold mb-3" style="color: #0F6D80;">المنهج الدراسي</h4>
              <div id="detail-lessons"></div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4" data-aos="fade-up" id="detail-instructor-card">
              <h4 class="fw-bold mb-3" style="color: #0F6D80;">المعلمة</h4>
              <div class="d-flex align-items-center gap-3">
                <div id="detail-instructor-avatar" style="width: 64px; height: 64px; border-radius: 50%; background: rgba(15,109,128,0.08); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #0F6D80; flex-shrink: 0;"></div>
                <div>
                  <h5 class="fw-bold small mb-1" id="detail-instructor-name"></h5>
                  <p class="small text-secondary opacity-75 mb-0" id="detail-instructor-bio"></p>
                </div>
              </div>
            </div>

          </div>

          <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 sticky-lg-top" style="top: 100px;" data-aos="fade-up">
              <h5 class="fw-bold mb-3" style="color: #0F6D80;">ملخص الدورة</h5>
              <ul class="list-unstyled small d-flex flex-column gap-3">
                <li class="d-flex align-items-center gap-3"><span class="d-flex align-items-center justify-content-center rounded-3" style="width: 40px; height: 40px; min-width: 40px; background: rgba(15,109,128,0.06); color: #0F6D80;"><i class="fa-solid fa-clock"></i></span><span><span class="fw-bold">المدة:</span><br /><span class="text-secondary opacity-75" id="summary-duration">١٢ أسبوع</span></span></li>
                <li class="d-flex align-items-center gap-3"><span class="d-flex align-items-center justify-content-center rounded-3" style="width: 40px; height: 40px; min-width: 40px; background: rgba(15,109,128,0.06); color: #0F6D80;"><i class="fa-solid fa-video"></i></span><span><span class="fw-bold">الحصص:</span><br /><span class="text-secondary opacity-75" id="summary-sessions">٣ حصص/أسبوع</span></span></li>
                <li class="d-flex align-items-center gap-3"><span class="d-flex align-items-center justify-content-center rounded-3" style="width: 40px; height: 40px; min-width: 40px; background: rgba(15,109,128,0.06); color: #0F6D80;"><i class="fa-solid fa-users"></i></span><span><span class="fw-bold">الطلاب:</span><br /><span class="text-secondary opacity-75" id="summary-students"></span></span></li>
                <li class="d-flex align-items-center gap-3"><span class="d-flex align-items-center justify-content-center rounded-3" style="width: 40px; height: 40px; min-width: 40px; background: rgba(15,109,128,0.06); color: #0F6D80;"><i class="fa-solid fa-signal"></i></span><span><span class="fw-bold">المستوى:</span><br /><span class="text-secondary opacity-75" id="summary-level"></span></span></li>
                <li class="d-flex align-items-center gap-3"><span class="d-flex align-items-center justify-content-center rounded-3" style="width: 40px; height: 40px; min-width: 40px; background: rgba(15,109,128,0.06); color: #0F6D80;"><i class="fa-solid fa-dollar-sign"></i></span><span><span class="fw-bold">السعر:</span><br /><span class="fw-bold" style="color: var(--pumpkin);" id="summary-price"></span></span></li>
              </ul>
              @auth
                <div id="enrollFormSection" class="mt-4 pt-3 border-top" style="border-color: rgba(15,109,128,0.08) !important;">
                  <h6 class="fw-bold mb-3" style="color: #0F6D80;"><i class="fa-solid fa-graduation-cap ml-1"></i> {{ __('messages.enroll_submit') }}</h6>
                  <div id="enrollMsg"></div>
                  <div class="mb-2">
                    <label class="small fw-medium text-secondary mb-1">{{ __('messages.enroll_name') }}</label>
                    <input type="text" id="enrollName" class="form-control form-control-sm rounded-3 text-end" placeholder="{{ __('messages.enroll_name_placeholder') }}" value="{{ $student?->name_ar ?? '' }}" />
                  </div>
                  <div class="mb-2">
                    <label class="small fw-medium text-secondary mb-1">{{ __('messages.your_phone') }}</label>
                    <input type="tel" id="enrollPhone" class="form-control form-control-sm rounded-3 text-end" placeholder="{{ __('messages.enroll_phone_placeholder') }}" value="{{ $student?->phone ?? '' }}" />
                  </div>
                  <div class="mb-3">
                    <label class="small fw-medium text-secondary mb-1">{{ __('messages.dept_enroll') }}</label>
                    <select id="enrollGender" class="form-select form-select-sm rounded-3 text-end">
                      <option value="woman" {{ ($student?->gender ?? '') == 'woman' ? 'selected' : '' }}>{{ __('messages.enroll_gender_woman') }}</option>
                      <option value="girl" {{ ($student?->gender ?? '') == 'girl' ? 'selected' : '' }}>{{ __('messages.enroll_gender_girl') }}</option>
                      <option value="boy" {{ ($student?->gender ?? '') == 'boy' ? 'selected' : '' }}>{{ __('messages.enroll_gender_boy') }}</option>
                    </select>
                  </div>
                  <button class="btn w-100 rounded-3 py-2 fw-bold" style="background: linear-gradient(135deg, #0F6D80, #1a8a9c); color: #fff; border: none; box-shadow: 0 4px 15px rgba(15,109,128,0.3);" onclick="enrollCurrent()"><i class="fa-solid fa-paper-plane ml-1"></i> {{ __('messages.enroll_submit') }}</button>
                </div>
              @else
                <div class="mt-4 pt-3 border-top text-center" style="border-color: rgba(15,109,128,0.08) !important;">
                  <p class="small text-secondary opacity-75 mb-2">{{ __('messages.enroll_login_required') }}</p>
                  <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-3 py-2 fw-bold small w-100" style="border-color: #0F6D80; color: #0F6D80;"><i class="fa-solid fa-user ml-1"></i> {{ __('messages.nav_login') }}</a>
                </div>
              @endauth
            </div>
          </div>
        </div>
      </div>
    </div>

@endsection

@push('scripts')
<script>
      const courseData = @json($courseData);
      const categories = @json($categories->keyBy('slug')->map(fn($c) => $c->name));
      const levelMap = { beginner: '{{ __('messages.dept_beginner') }}', intermediate: '{{ __('messages.dept_intermediate') }}', advanced: '{{ __('messages.dept_advanced') }}' };
      const csrfToken = '{{ csrf_token() }}';

      let currentDept = courseData;

      function loadCourse() {
        if (!currentDept) {
          document.getElementById('courseDetails').innerHTML = '<div class="container py-5 text-center"><h2 class="fw-bold text-primary mb-3">الدورة غير موجودة</h2><p class="text-secondary opacity-75">لم يتم العثور على الدورة المطلوبة.</p><a href="{{ route("departments") }}" class="btn btn-primary rounded-3 px-4 py-2 mt-3">العودة للأقسام</a></div>';
          return;
        }

        document.title = currentDept.name + ' | منصة كُنوز التعليمية';
        document.getElementById('detail-title').textContent = currentDept.name;
        document.getElementById('detail-desc').textContent = currentDept.desc;
        document.getElementById('detail-category-badge').innerHTML = (categories[currentDept.category] || '');
        document.getElementById('detail-students').innerHTML = '<i class="fa-regular fa-user me-1"></i>' + currentDept.students;
        document.getElementById('detail-level').innerHTML = '<i class="fa fa-signal me-1"></i>' + (levelMap[currentDept.level] || '');
        document.getElementById('detail-price').innerHTML = currentDept.is_free
          ? '<i class="fa-regular fa-credit-card me-1"></i>{{ __('messages.dept_free') }}'
          : '<i class="fa-regular fa-credit-card me-1"></i>$' + currentDept.price + '/{{ __('messages.dept_monthly') }}';
        document.getElementById('detail-hero-img').src = currentDept.img;
        document.getElementById('detail-hero-img').alt = currentDept.name;

        document.getElementById('summary-students').textContent = currentDept.students;
        document.getElementById('summary-level').textContent = levelMap[currentDept.level] || '';
        document.getElementById('summary-price').textContent = currentDept.is_free
          ? '{{ __('messages.dept_free') }}'
          : '$' + currentDept.price + ' / {{ __('messages.dept_monthly') }}';
        if (currentDept.duration) document.getElementById('summary-duration').textContent = currentDept.duration;
        if (currentDept.sessions) document.getElementById('summary-sessions').textContent = currentDept.sessions;

        var instructorAvatar = document.getElementById('detail-instructor-avatar');
        var nameParts = (currentDept.instructor || 'أ. معلمة').split(' ');
        var initials = nameParts.map(function(p) { return p[0]; }).join('');
        instructorAvatar.innerHTML = initials;
        document.getElementById('detail-instructor-name').textContent = currentDept.instructor || 'أ. معلمة';
        document.getElementById('detail-instructor-bio').textContent = currentDept.instructorBio || '';

        var lessonsEl = document.getElementById('detail-lessons');
        if (currentDept.lessons && currentDept.lessons.length) {
          lessonsEl.innerHTML = currentDept.lessons.map(function(l, i) {
            return '<div class="d-flex align-items-center gap-3 py-2 border-bottom" style="border-color: rgba(15,109,128,0.04) !important;"><span class="d-flex align-items-center justify-content-center rounded-2 fw-bold small" style="width: 32px; height: 32px; min-width: 32px; background: #0F6D80; color: #fff;">' + (i + 1) + '</span><span class="small">' + l + '</span></div>';
          }).join('');
        } else {
          lessonsEl.innerHTML = '<p class="small text-secondary opacity-75">{{ __('messages.student_lesson_content_pending') }}</p>';
        }
      }

      function enrollCurrent() {
        if (!currentDept) return;
        var name = document.getElementById('enrollName').value.trim();
        var phone = document.getElementById('enrollPhone').value.trim();
        var gender = document.getElementById('enrollGender').value;
        var msgEl = document.getElementById('enrollMsg');
        var btn = document.querySelector('#enrollFormSection button');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> {{ __('messages.enroll_loading') }}';
        fetch('{{ route("enroll") }}', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
          body: JSON.stringify({ name: name, phone: phone, gender: gender, course_id: currentDept.id })
        }).then(function(r) {
          return r.json().catch(function() { return {}; }).then(function(data) {
            if (!r.ok) {
              var errMsg = data.message || data.error || '{{ __('messages.enroll_error_required') }}';
              if (data.errors) {
                var keys = Object.keys(data.errors);
                if (keys.length) errMsg = data.errors[keys[0]][0] || errMsg;
              }
              throw new Error(errMsg);
            }
            if (data.success) {
              msgEl.innerHTML = '<div class="alert alert-success py-2 small rounded-3 text-center">{{ __('messages.enroll_complete') }}</div>';
            } else {
              throw new Error(data.message || '{{ __('messages.enroll_error_required') }}');
            }
          });
        }).catch(function(e) {
          msgEl.innerHTML = '<div class="alert alert-danger py-2 small rounded-3 text-center">' + e.message + '</div>';
        }).then(function() {
          btn.disabled = false;
          btn.innerHTML = '<i class="fa-solid fa-paper-plane ml-1"></i> {{ __('messages.enroll_submit') }}';
        });
      }

      loadCourse();
    </script>
@endpush


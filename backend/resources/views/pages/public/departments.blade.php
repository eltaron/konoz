@extends('layouts.public')

@section('title', __('messages.page_departments_title', ['site_name' => __('messages.site_name')]))
@section('meta_description', __('messages.page_departments_meta'))
@section('meta_robots', 'index, follow')

@section('content')
      <div class="page-hero text-center" data-aos="fade-up"><div class="hero-orb hero-orb-1"></div><div class="hero-orb hero-orb-2"></div><div class="hero-dot hero-dot-1"></div><div class="hero-dot hero-dot-2"></div><div class="hero-dot hero-dot-3"></div>
        <div class="container">
          <div class="page-hero-icon"><i class="fa-solid fa-quran"></i></div>
          <h1 class="fw-bold">{{ __('messages.dept_title') }}</h1>
          <p>{{ __('messages.dept_subtitle') }}</p>
        </div>
      </div>

      <div class="container py-5">

        <div class="dept-toolbar" data-aos="fade-up">
          <div class="dept-search-wrap">
            <i class="fa-solid fa-magnifying-glass dept-search-icon"></i>
            <input type="text" id="searchInput" class="dept-search-input" placeholder="{{ __('messages.dept_search_placeholder') }}" autocomplete="off" />
          </div>
          <div class="dept-select">
            <i class="fa-solid fa-tags"></i>
            <select id="priceFilter" aria-label="{{ __('messages.dept_all_prices') }}">
              <option value="all">{{ __('messages.dept_all_prices') }}</option>
              <option value="low">{{ __('messages.dept_price_low') }}</option>
              <option value="mid">{{ __('messages.dept_price_mid') }}</option>
              <option value="high">{{ __('messages.dept_price_high') }}</option>
            </select>
          </div>
          <div class="dept-select">
            <i class="fa-solid fa-signal"></i>
            <select id="levelFilter" aria-label="{{ __('messages.dept_all_levels') }}">
              <option value="all">{{ __('messages.dept_all_levels') }}</option>
              <option value="beginner">{{ __('messages.dept_beginner') }}</option>
              <option value="intermediate">{{ __('messages.dept_intermediate') }}</option>
              <option value="advanced">{{ __('messages.dept_advanced') }}</option>
            </select>
          </div>
        </div>

        <div class="dept-section-title" data-aos="fade-up">
          <i class="fa-solid fa-compass-drafting"></i>
          <span>{{ __('messages.dept_departments_label') }}</span>
          <span class="dept-title-line"></span>
        </div>

        <div class="dept-scroller" data-aos="fade-up">
          <button type="button" class="dept-scroll-btn dept-scroll-prev" onclick="scrollRow('deptScroller', -1)" aria-label="{{ __('messages.dept_all') }}">
            <i class="fa-solid fa-chevron-right"></i>
          </button>
          <div class="dept-scroller-track" id="deptScroller"></div>
          <button type="button" class="dept-scroll-btn dept-scroll-next" onclick="scrollRow('deptScroller', 1)" aria-label="{{ __('messages.dept_all') }}">
            <i class="fa-solid fa-chevron-left"></i>
          </button>
        </div>

        <div class="dept-results d-flex align-items-center justify-content-between flex-wrap gap-2" data-aos="fade-up">
          <span id="deptCount" class="dept-count small fw-bold"></span>
        </div>

        <div class="row g-4" id="deptGrid" data-aos="fade-up"></div>

        <div class="d-flex justify-content-center align-items-center gap-2 mt-5" id="pagination"></div>
      </div>
@endsection

@push('scripts')
<script>
      const departments = {!! $coursesJson !!};
      const categories = {!! $categoriesJson !!};
      const lang = @json($deptLang);
      const csrfToken = '{{ csrf_token() }}';

      const perPage = 6;
      let currentPage = 1;

      function deptPill(filter, label, icon, count, active) {
        return `<button type="button" class="dept-pill${active ? ' active' : ''}" data-filter="${filter}" onclick="setDeptFilter('${filter}', this)">
          <span class="dept-pill-icon"><i class="fa-solid ${icon}"></i></span>
          <span class="dept-pill-name">${label}</span>
          <span class="dept-pill-count">${count}</span>
        </button>`;
      }

      function setDeptFilter(slug, btn) {
        document.querySelectorAll('.dept-pill').forEach(function(b) { b.classList.remove('active'); });
        btn.classList.add('active');
        currentPage = 1;
        renderPage();
      }

      (function renderDepts() {
        const track = document.getElementById('deptScroller');
        let html = deptPill('all', lang.all, 'fa-layer-group', departments.length, true);
        categories.forEach(function(cat) {
          html += deptPill(cat.slug, cat.name, cat.icon, cat.count, false);
        });
        track.innerHTML = html;
      })();

      function getFiltered() {
        const cat = document.querySelector('.dept-pill.active')?.dataset?.filter || 'all';
        const search = document.getElementById('searchInput').value.toLowerCase().trim();
        const priceVal = document.getElementById('priceFilter').value;
        const levelVal = document.getElementById('levelFilter').value;

        return departments.filter(d => {
          if (cat !== 'all' && d.category !== cat) return false;
          if (search && !d.name.includes(search) && !d.desc.includes(search)) return false;
          if (priceVal === 'low' && d.price >= 50) return false;
          if (priceVal === 'mid' && (d.price < 50 || d.price > 100)) return false;
          if (priceVal === 'high' && d.price <= 100) return false;
          if (levelVal !== 'all' && d.level !== levelVal) return false;
          return true;
        });
      }

      function categoryLabel(cat) {
        var found = categories.find(function(c) { return c.slug === cat; });
        return found ? found.name : cat;
      }

      function levelLabel(level) {
        const map = { beginner: lang.level_beginner, intermediate: lang.level_intermediate, advanced: lang.level_advanced };
        return map[level] || level;
      }

      function renderPage() {
        const filtered = getFiltered();
        const totalPages = Math.ceil(filtered.length / perPage) || 1;
        if (currentPage > totalPages) currentPage = totalPages;
        const start = (currentPage - 1) * perPage;
        const pageItems = filtered.slice(start, start + perPage);

        document.getElementById('deptCount').innerHTML =
          '<i class="fa-solid fa-gem ml-1"></i> ' + filtered.length + ' ' + lang.courses;

        const grid = document.getElementById('deptGrid');
        grid.innerHTML = pageItems.map(d => `
          <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100" style="transition: all 0.3s; cursor: default;">
              <div class="position-relative overflow-hidden" style="height: 180px;">
                <img src="${d.img}" alt="${d.name}" class="w-100 h-100" style="object-fit: cover; transition: transform 0.4s;" loading="lazy" />
                ${d.is_free ? '<span class="position-absolute top-0 end-0 m-3 badge rounded-3 px-3 py-2 small fw-medium" style="background: rgba(40,167,69,0.92); color: #fff; backdrop-filter: blur(4px);"><i class="fa-solid fa-gift ml-1"></i>' + lang.free_badge + '</span>' : ''}
                <span class="position-absolute top-0 start-0 m-3 badge rounded-3 px-3 py-2 small fw-medium" style="background: rgba(255,255,255,0.92); color: #0F6D80; backdrop-filter: blur(4px);">
                  <i class="fa-regular fa-user me-1"></i>${d.students}
                </span>
              </div>
              <div class="card-body p-4 d-flex flex-column">
                <div class="d-flex align-items-center gap-3 mb-3">
                  <span class="d-flex align-items-center justify-content-center rounded-3" style="width: 44px; height: 44px; min-width: 44px; background: rgba(216,139,18,0.08); color: var(--pumpkin); font-size: 1.2rem;"><i class="fa-solid ${d.icon}"></i></span>
                  <div>
                    <h5 class="fw-bold small mb-0" style="color: #0F6D80;">${d.name}</h5>
                    <span class="small text-secondary opacity-50">${categoryLabel(d.category)}</span>
                  </div>
                </div>
                <p class="small text-secondary opacity-75 flex-grow-1 mb-2">${d.desc}</p>
                <div class="d-flex align-items-center justify-content-between mb-3">
                  <span class="fw-bold" style="color: var(--pumpkin);">${d.is_free ? '<i class="fa-solid fa-gift ml-1"></i>' + lang.free : '$' + d.price + ' <span class="small fw-normal text-secondary opacity-50">/ ' + lang.monthly + '</span>'}</span>
                  <span class="small text-secondary opacity-50">${levelLabel(d.level)}</span>
                </div>
                <div class="d-flex gap-2">
                  ${d.link ? `
                    <a href="${d.link}" class="btn flex-grow-1 rounded-3 py-2 small fw-medium text-decoration-none" style="background: #0F6D80; color: #fff;"><i class="fa-regular fa-compass ml-1"></i>${lang.discover}</a>
                  ` : `
                    <button class="btn flex-grow-1 rounded-3 py-2 small fw-medium" style="background: #0F6D80; color: #fff;" onclick="detailsDept(${d.id})"><i class="fa-regular fa-compass ml-1"></i>${lang.discover}</button>
                    <button class="btn rounded-3 py-2 small fw-medium" style="border: 1.5px solid rgba(15,109,128,0.12); color: var(--text-body);" onclick="detailsDept(${d.id})"><i class="fa-solid fa-circle-info"></i></button>
                  `}
                </div>
              </div>
            </div>
          </div>
        `).join('');

        renderPagination(totalPages);
        AOS.refresh();
      }

      function renderPagination(totalPages) {
        const el = document.getElementById('pagination');
        if (totalPages <= 1) { el.innerHTML = ''; return; }
        let html = '';
        html += `<button class="btn btn-sm rounded-3 px-3 py-2 border" style="border-color: rgba(15,109,128,0.12);" onclick="goPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}><i class="fa-solid fa-chevron-right"></i></button>`;
        for (let i = 1; i <= totalPages; i++) {
          html += `<button class="btn btn-sm rounded-3 px-3 py-2 fw-bold ${i === currentPage ? 'text-white' : 'border'}" style="${i === currentPage ? 'background: #0F6D80;' : 'border-color: rgba(15,109,128,0.12);'}" onclick="goPage(${i})">${i}</button>`;
        }
        html += `<button class="btn btn-sm rounded-3 px-3 py-2 border" style="border-color: rgba(15,109,128,0.12);" onclick="goPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}><i class="fa-solid fa-chevron-left"></i></button>`;
        el.innerHTML = html;
      }

      function goPage(p) { currentPage = p; renderPage(); window.scrollTo({ top: document.querySelector('.page-hero').nextElementSibling.offsetTop - 100, behavior: 'smooth' }); }

      document.getElementById('searchInput').addEventListener('input', function() { currentPage = 1; renderPage(); });
      document.getElementById('priceFilter').addEventListener('change', function() { currentPage = 1; renderPage(); });
      document.getElementById('levelFilter').addEventListener('change', function() { currentPage = 1; renderPage(); });

      function detailsDept(id) {
        window.location.href = '{{ route('course-details') }}?id=' + id;
      }

      renderPage();
</script>
@endpush

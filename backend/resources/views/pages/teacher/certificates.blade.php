@extends('layouts.teacher')

@section('title', __('messages.teacher_certificates') . ' | ' . __('messages.site_name'))
@section('meta_description', __('messages.teacher_certificates_meta'))
@section('header_greeting', __('messages.teacher_header_greeting', ['name' => __('messages.teacher_female')]))
@section('page_title', __('messages.teacher_certificates_title'))

@push('styles')
<style>
      :root { --cf-teal: #0F6D80; --cf-teal-dark: #0a4a56; --cf-teal-mid: #157a8c; --cf-gold: #d89b1d; --cf-ink: #1f2937; --cf-muted: #6b7a7e; }

      .td-page-title { font-size: 1.35rem; font-weight: 800; color: var(--cf-ink); margin: 0; display: flex; align-items: center; gap: 12px; }
      .td-page-title i { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.05rem; background: linear-gradient(135deg, rgba(15,109,128,0.1), rgba(15,109,128,0.04)); color: var(--cf-teal); }

      .btn-teacher-primary { background: linear-gradient(135deg, var(--cf-teal), var(--cf-teal-mid)); color: #fff; border: none; border-radius: 9px; font-weight: 600; font-size: .78rem; padding: 8px 16px; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
      .btn-teacher-primary:hover { filter: brightness(1.08); color: #fff; }

      .td-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 14px; border-radius: 20px; font-size: .76rem; font-weight: 700; white-space: nowrap; }
      .td-badge-strong { background: linear-gradient(135deg, var(--cf-teal), var(--cf-teal-mid)); color: #fff; }
      .td-badge-mid { background: rgba(15, 109, 128, 0.12); color: var(--cf-teal); }
      .td-badge-soft { background: rgba(216, 155, 29, 0.14); color: #b57f0e; }
      .td-badge-danger { background: rgba(220, 53, 69, 0.12); color: #c0392b; }
      .td-badge-sm { padding: 4px 12px; font-size: .7rem; }

      /* ===== Stats ===== */
      .td-stat { background: #fff; border-radius: 14px; border: 1px solid rgba(15,109,128,0.06); box-shadow: 0 3px 14px rgba(15,109,128,0.04); padding: 14px 16px; display: flex; align-items: center; gap: 12px; height: 100%; }
      .td-stat-icon { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.05rem; flex-shrink: 0; background: linear-gradient(135deg, rgba(15,109,128,0.12), rgba(15,109,128,0.05)); color: var(--cf-teal); }
      .td-stat-num { font-size: 1.25rem; font-weight: 800; color: var(--cf-ink); margin: 0; line-height: 1.1; }
      .td-stat-label { font-size: .74rem; font-weight: 600; color: var(--cf-muted); margin: 0; }

      /* ===== Certificate cards ===== */
      .cert-card { position: relative; background: #fff; border-radius: 16px; padding: 20px 22px; border: 1px solid rgba(15,109,128,0.06); box-shadow: 0 4px 20px rgba(15,109,128,0.04); cursor: pointer; transition: transform .2s, box-shadow .2s, border-color .2s; }
      .cert-card::after { content: ""; position: absolute; inset: auto 0 0 22px; height: 3px; border-radius: 3px 3px 0 0; }
      .cert-card:hover { transform: translateY(-3px); box-shadow: 0 10px 26px rgba(15,109,128,0.13); border-color: rgba(15,109,128,0.14); }
      .cert-card.st-issued::after, .cert-card.st-active::after { background: linear-gradient(90deg, var(--cf-teal), var(--cf-teal-mid)); }
      .cert-card.st-pending::after { background: linear-gradient(90deg, var(--cf-gold), #f0b429); }
      .cert-card.st-cancelled::after { background: linear-gradient(90deg, #dc3545, #e05a6d); }
      .cert-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; color: #fff; flex-shrink: 0; }
      .cert-icon.ic-teal { background: linear-gradient(135deg, var(--cf-teal), var(--cf-teal-mid)); }
      .cert-icon.ic-gold { background: linear-gradient(135deg, var(--cf-gold), #b57f0e); }
      .cert-title { font-weight: 800; font-size: .98rem; color: var(--cf-ink); }
      .cert-number { font-size: .74rem; color: var(--cf-muted); font-weight: 600; }
      .cert-student { font-weight: 700; font-size: .9rem; color: var(--cf-ink); }
      .cert-course { font-size: .78rem; color: var(--cf-muted); }
      .cert-date { font-size: .74rem; color: var(--cf-muted); opacity: .8; white-space: nowrap; }

      .td-empty-icon { font-size: 2.8rem; color: rgba(15,109,128,0.15); }

      /* ===== Details modal ===== */
      .td-info-row { display: flex; align-items: center; gap: 10px; padding: 10px 14px; border-radius: 10px; background: linear-gradient(135deg, rgba(15,109,128,0.04), rgba(15,109,128,0.015)); border: 1px solid rgba(15,109,128,0.07); min-width: 0; }
      .td-info-row i { color: rgba(107,122,126,0.6); font-size: .9rem; }
      .td-info-label { color: var(--cf-muted); font-size: .78rem; white-space: nowrap; }
      .td-info-val { font-weight: 700; margin-right: auto; color: var(--cf-ink); font-size: .82rem; overflow-wrap: anywhere; }
  </style>
@endpush

@section('content')
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <h1 class="td-page-title"><i class="fa-solid fa-certificate"></i>{{ __('messages.teacher_certificates_title') }}</h1>
    <button class="btn-teacher-primary" onclick="showAddCertificateModal()"><i class="fa-solid fa-plus me-1"></i>{{ __('messages.teacher_certificates_issue') }}</button>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-certificate"></i></div>
        <div>
          <p class="td-stat-num">{{ count($certificates ?? []) }}</p>
          <p class="td-stat-label">{{ __('messages.teacher_certificates_total') }}</p>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-circle-check"></i></div>
        <div>
          <p class="td-stat-num">{{ $stats['issued'] ?? 0 }}</p>
          <p class="td-stat-label">{{ __('messages.teacher_certificates_issued') }}</p>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-hourglass-half"></i></div>
        <div>
          <p class="td-stat-num">{{ $stats['pending'] ?? 0 }}</p>
          <p class="td-stat-label">{{ __('messages.teacher_certificates_pending_issue') }}</p>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="td-stat">
        <div class="td-stat-icon"><i class="fa-solid fa-ban"></i></div>
        <div>
          <p class="td-stat-num">{{ $stats['cancelled'] ?? 0 }}</p>
          <p class="td-stat-label">{{ __('messages.teacher_certificates_cancelled') }}</p>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3">
    @forelse($certificates ?? [] as $c)
    @php
      $cStatus = $c->status ?? 'issued';
      $statusMap = ['issued'=>__('messages.teacher_certificates_status_issued'),'active'=>__('messages.teacher_certificates_status_active'),'pending'=>__('messages.teacher_certificates_status_pending'),'cancelled'=>__('messages.teacher_certificates_status_cancelled')];
      $badgeClass = in_array($cStatus, ['issued', 'active', 'delivered']) ? 'td-badge-strong' : ($cStatus === 'pending' ? 'td-badge-soft' : 'td-badge-danger');
      $iconClass = ($cStatus === 'pending') ? 'ic-gold' : 'ic-teal';
      $colorMap = ['issued'=>'#0F6D80','active'=>'#0F6D80','pending'=>'#cc9a06','cancelled'=>'#dc3545'];
    @endphp
    <div class="col-md-6 col-lg-4">
      <div class="cert-card h-100 st-{{ $cStatus }}"
        onclick='showCertDetails(this)'
        data-title="{{ $c->title }}"
        data-number="{{ $c->certificate_number ?? '#' . str_pad($c->id, 3, '0', STR_PAD_LEFT) }}"
        data-student="{{ $c->student?->user?->name ?? $c->student?->name_ar ?? __('messages.teacher_certificates_student') }}"
        data-course="{{ $c->course?->title ?? '—' }}"
        data-date="{{ $c->issued_at ? \Carbon\Carbon::parse($c->issued_at)->format('Y-m-d') : '' }}"
        data-status="{{ $statusMap[$cStatus] ?? __('messages.teacher_certificates_status_issued') }}"
        data-status-color="{{ $colorMap[$cStatus] ?? '#0F6D80' }}"
        data-code="{{ $c->verification_code }}"
        data-verify-url="{{ $c->verification_url }}"
        data-image="{{ $c->image_url }}">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
          <div class="d-flex align-items-start gap-3">
            <span class="cert-icon {{ $iconClass }}"><i class="fa-solid fa-certificate"></i></span>
            <div>
              <h6 class="cert-title mb-1">{{ $c->title }}</h6>
              <span class="cert-number">{{ $c->certificate_number ?? '#' . str_pad($c->id, 3, '0', STR_PAD_LEFT) }}</span>
            </div>
          </div>
          <span class="td-badge {{ $badgeClass }} td-badge-sm">{{ $statusMap[$cStatus] ?? __('messages.teacher_certificates_status_issued') }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center gap-2">
          <div>
            <span class="cert-student d-block">{{ $c->student->user->name ?? __('messages.teacher_certificates_student') }}</span>
            <span class="cert-course">{{ $c->course->title ?? '—' }}</span>
          </div>
          <span class="cert-date">{{ $c->issued_at ? \Carbon\Carbon::parse($c->issued_at)->format('Y-m-d') : '—' }}</span>
        </div>
      </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
      <i class="fa-solid fa-certificate td-empty-icon mb-3 d-block"></i>
      <p class="text-secondary">{{ __('messages.teacher_certificates_empty') }}</p>
    </div>
    @endforelse
  </div>

  <!-- Certificate Details Modal -->
  <div class="modal fade" id="certDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content" style="border-radius:18px;border:none;overflow:hidden;">
        <div class="modal-header border-0 pb-2" style="background:linear-gradient(135deg, rgba(15,109,128,0.06), rgba(216,155,29,0.05));">
          <h5 class="modal-title fw-bold" id="certModalTitle" style="color:#0F6D80;"><i class="fa-solid fa-certificate me-2"></i></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <div class="text-center mb-4">
            <img id="certModalImage" src="" alt="" style="max-width:100%;max-height:340px;border-radius:12px;border:1px solid rgba(15,109,128,0.12);box-shadow:0 6px 20px rgba(15,109,128,0.10);" />
            <div id="certModalNoImage" style="display:none;padding:48px 0;">
              <i class="fa-solid fa-image" style="font-size:3rem;color:rgba(15,109,128,0.15);"></i>
              <p class="text-secondary small mt-2 mb-0">{{ __('messages.teacher_certificates_no_image') }}</p>
            </div>
          </div>
          <div class="row g-3 small">
            <div class="col-md-6">
              <div class="td-info-row">
                <i class="fa-solid fa-hashtag"></i>
                <span class="td-info-label">{{ __('messages.teacher_certificates_number') }}:</span>
                <span class="td-info-val" id="certModalNumber"></span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="td-info-row">
                <i class="fa-solid fa-user-graduate"></i>
                <span class="td-info-label">{{ __('messages.teacher_certificates_student') }}:</span>
                <span class="td-info-val" id="certModalStudent"></span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="td-info-row">
                <i class="fa-solid fa-book-open"></i>
                <span class="td-info-label">{{ __('messages.teacher_certificates_course') }}:</span>
                <span class="td-info-val" id="certModalCourse"></span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="td-info-row">
                <i class="fa-regular fa-calendar"></i>
                <span class="td-info-label">{{ __('messages.teacher_certificates_issue_date') }}:</span>
                <span class="td-info-val" id="certModalDate"></span>
              </div>
            </div>
            <div class="col-12">
              <div class="td-info-row">
                <i class="fa-solid fa-circle-info"></i>
                <span class="td-info-label">{{ __('messages.teacher_certificates_status_label') }}:</span>
                <span class="td-badge td-badge-mid td-badge-sm" id="certModalStatus" style="font-size:0.75rem;"></span>
              </div>
            </div>
          <div class="col-12">
              <div class="td-info-row">
                <i class="fa-solid fa-shield-halved"></i>
                <span class="td-info-label">{{ __('messages.teacher_certificates_verify_code') }}:</span>
                <span class="td-info-val" id="certModalCode" style="direction:ltr;letter-spacing:1px;"></span>
                <button type="button" class="btn btn-sm btn-light rounded-3" style="font-size:.72rem;white-space:nowrap;" onclick="copyCertCode()"><i class="fa-solid fa-copy"></i></button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <a id="certModalVerify" href="#" target="_blank" rel="noopener" class="btn btn-teacher-primary rounded-3 px-4" style="display:none;"><i class="fa-solid fa-certificate me-1"></i>{{ __('messages.teacher_certificates_view_verify') }}</a>
          <a id="certModalDownload" href="#" download class="btn btn-teacher-primary rounded-3 px-4" style="display:none;"><i class="fa-solid fa-download me-1"></i>{{ __('messages.teacher_certificates_download') }}</a>
          <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">{{ __('messages.teacher_certificates_close') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
      var certUploadData = null;
      var certConfirmData = null;
      var csrfToken = '{{ csrf_token() }}';
      var certStudents = @json($students);
      var certCourses = @json($courses);

      function certStudentOptions(courseId, selectedIds) {
        return certStudents.filter(function(s) {
          return !courseId || s.course_ids.indexOf(parseInt(courseId)) !== -1 || s.course_ids.indexOf(courseId) !== -1;
        }).map(function(s) {
          var sel = selectedIds && selectedIds.indexOf(s.id) !== -1 ? ' selected' : '';
          return '<option value="' + s.id + '"' + sel + '>' + s.name + '</option>';
        }).join('');
      }

      function certStudentChecks(courseId) {
        return certStudents.filter(function(s) {
          return !courseId || s.course_ids.indexOf(parseInt(courseId)) !== -1 || s.course_ids.indexOf(courseId) !== -1;
        }).map(function(s) {
          return '<label class="d-flex align-items-center gap-2 py-1"><input type="checkbox" class="form-check-input student-check" value="' + s.id + '" checked /> <span style="font-size:0.85rem;">' + s.name + '</span></label>';
        }).join('');
      }

      function onCertCourseChange() {
        var courseId = document.getElementById('certCourseSelect').value;
        document.getElementById('singleGroup').innerHTML = '<select id="singleStudent" class="teacher-input form-control">' + certStudentOptions(courseId) + '</select>';
        document.getElementById('multiGroup').innerHTML = '<div style="max-height:160px;overflow-y:auto;border:1px solid rgba(15,109,128,0.06);border-radius:10px;padding:10px;">' + certStudentChecks(courseId) + '</div>';
      }

      function onCertTypeChange() {
        var isOther = document.getElementById('certTypeSelect').value === 'other';
        document.getElementById('certTypeOtherWrap').style.display = isOther ? '' : 'none';
      }

      function showAddCertificateModal() {
        certUploadData = null;
        certConfirmData = null;
        var courseOptions = '<option value="">{{ __('messages.teacher_certificates_select_course') }}</option>' +
          certCourses.map(function(c) { return '<option value="' + c.id + '">' + c.name_ar + '</option>'; }).join('');
        var typeOptions =
          '<option value="{{ __('messages.teacher_certificates_cert_type_ijaza') }}">{{ __('messages.teacher_certificates_cert_type_ijaza') }}</option>' +
          '<option value="{{ __('messages.teacher_certificates_cert_type_tajweed') }}">{{ __('messages.teacher_certificates_cert_type_tajweed') }}</option>' +
          '<option value="{{ __('messages.teacher_certificates_cert_type_hifdh') }}">{{ __('messages.teacher_certificates_cert_type_hifdh') }}</option>' +
          '<option value="{{ __('messages.teacher_certificates_cert_type_qiraat') }}">{{ __('messages.teacher_certificates_cert_type_qiraat') }}</option>' +
          '<option value="{{ __('messages.teacher_certificates_cert_type_recitation') }}">{{ __('messages.teacher_certificates_cert_type_recitation') }}</option>' +
          '<option value="{{ __('messages.teacher_certificates_cert_type_attendance') }}">{{ __('messages.teacher_certificates_cert_type_attendance') }}</option>' +
          '<option value="other">{{ __('messages.teacher_certificates_cert_type_other') }}</option>';

        Swal.fire({
          title: '{{ __('messages.teacher_certificates_modal_title') }}',
          html:
            '<div class="text-end">' +
              '<div class="mb-3"><label class="d-block small fw-medium mb-1" style="color:#3f484b;">{{ __('messages.teacher_certificates_select_course') }}</label>' +
                '<select id="certCourseSelect" onchange="onCertCourseChange()" class="teacher-input form-control">' + courseOptions + '</select></div>' +
              '<div class="mb-3"><label class="d-block small fw-medium mb-1" style="color:#3f484b;">{{ __('messages.teacher_certificates_type_label') }}</label>' +
                '<select id="certTypeSelect" onchange="onCertTypeChange()" class="teacher-input form-control">' + typeOptions + '</select>' +
                '<div id="certTypeOtherWrap" style="display:none;margin-top:8px;"><input id="certTypeOther" class="teacher-input form-control" placeholder="{{ __('messages.teacher_certificates_cert_type_placeholder') }}" /></div></div>' +
              '<div class="d-flex gap-3 mb-3 justify-content-center">' +
                '<label class="d-flex align-items-center gap-1" style="font-size:0.85rem;"><input type="radio" name="certMode" value="single" checked onchange="certMode=\'single\';document.getElementById(\'singleGroup\').style.display=\'\';document.getElementById(\'multiGroup\').style.display=\'none\';" /> {{ __('messages.teacher_certificates_single') }}</label>' +
                '<label class="d-flex align-items-center gap-1" style="font-size:0.85rem;"><input type="radio" name="certMode" value="group" onchange="certMode=\'group\';document.getElementById(\'singleGroup\').style.display=\'none\';document.getElementById(\'multiGroup\').style.display=\'\';" /> {{ __('messages.teacher_certificates_group') }}</label>' +
              '</div>' +
              '<div id="singleGroup">' +
                '<div class="mb-3"><select id="singleStudent" class="teacher-input form-control">' + certStudentOptions('') + '</select></div>' +
              '</div>' +
              '<div id="multiGroup" style="display:none;">' +
                '<div class="mb-2" style="max-height:160px;overflow-y:auto;border:1px solid rgba(15,109,128,0.06);border-radius:10px;padding:10px;">' + certStudentChecks('') + '</div>' +
              '</div>' +
              '<div class="mb-3"><input id="certDate" class="teacher-input form-control" type="date" value="' + new Date().toISOString().slice(0,10) + '" /></div>' +
              '<div class="mb-3"><label class="d-block small fw-medium mb-1" style="color:#3f484b;text-align:start;">{{ __('messages.teacher_certificates_upload') }}</label><input class="form-control teacher-input" type="file" id="certFileInput" accept="image/*,.pdf" onchange="handleCertUpload(event)" /></div>' +
              '<div id="certPreview" class="text-center mb-2" style="display:none;"></div>' +
            '</div>',
          confirmButtonText: '{{ __('messages.teacher_certificates_issue_btn') }}',
          confirmButtonColor: '#0F6D80',
          showCancelButton: true,
          cancelButtonText: '{{ __('messages.teacher_certificates_cancel') }}',
          width: 500,
          preConfirm: function() {
            var mode = document.querySelector('input[name="certMode"]:checked').value;
            var courseId = document.getElementById('certCourseSelect').value;
            if (!courseId) { Swal.showValidationMessage('{{ __('messages.teacher_certificates_choose_course') }}'); return false; }
            var typeId = document.getElementById('certTypeSelect').value;
            var title = typeId === 'other' ? document.getElementById('certTypeOther').value.trim() : typeId;
            if (!title) { Swal.showValidationMessage('{{ __('messages.teacher_certificates_choose_course') }}'); return false; }
            var studentIds = [];
            if (mode === 'single') {
              studentIds = [document.getElementById('singleStudent').value];
            } else {
              studentIds = Array.prototype.map.call(document.querySelectorAll('.student-check:checked'), function(c) { return c.value; });
            }
            if (!studentIds[0]) { Swal.showValidationMessage(mode === 'single' ? '{{ __('messages.teacher_certificates_choose_student') }}' : '{{ __('messages.teacher_certificates_choose_min_one') }}'); return false; }
            var fd = new FormData();
            fd.append('course_id', courseId);
            fd.append('title_ar', title);
            fd.append('issued_at', document.getElementById('certDate').value);
            studentIds.forEach(function(id) { fd.append('student_ids[]', id); });
            if (certUploadData && certUploadData.file) fd.append('image', certUploadData.file);
            return fetch('{{ route("teacher.certificates.store") }}', {
              method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: fd
            }).then(function(r) {
              if (!r.ok) return r.json().then(function(e) { throw new Error((e.errors && Object.values(e.errors)[0][0]) || e.message || '{{ __('messages.teacher_messages_error') }}'); });
              return r.json();
            }).then(function(result) {
              Swal.fire({ icon: 'success', title: null, html: '<div class="text-center"><i class="fa-solid fa-check-circle text-success" style="font-size:3rem;"></i><h5 class="mt-3 fw-bold">{{ __('messages.teacher_certificates_issued_count', ['count'=>'']) }} ' + result.count + '</h5></div>', confirmButtonColor: '#0F6D80', confirmButtonText: '{{ __('messages.teacher_certificates_ok') }}' }).then(function() { window.location.reload(); });
            }).catch(function(e) { Swal.showValidationMessage(e.message); return false; });
          },
          allowOutsideClick: false
        });
      }

      function showCertDetails(card) {
        var d = card.dataset;
        document.getElementById('certModalTitle').innerHTML =
          '<i class="fa-solid fa-certificate me-2"></i>' + (d.title || '');
        document.getElementById('certModalNumber').textContent = d.number || '';
        document.getElementById('certModalStudent').textContent = d.student || '';
        document.getElementById('certModalCourse').textContent = d.course || '—';
        document.getElementById('certModalDate').textContent = d.date || '—';

        var statusBadge = document.getElementById('certModalStatus');
        statusBadge.textContent = d.status || '';
        statusBadge.style.background = d.statusColor + '1a';
        statusBadge.style.color = d.statusColor;

        var img = document.getElementById('certModalImage');
        var noImg = document.getElementById('certModalNoImage');
        var download = document.getElementById('certModalDownload');
        if (d.image) {
          img.src = d.image;
          img.style.display = '';
          noImg.style.display = 'none';
          download.href = d.image;
          download.style.display = '';
        } else {
          img.style.display = 'none';
          noImg.style.display = '';
          download.style.display = 'none';
        }

        document.getElementById('certModalCode').textContent = d.code || '—';
        var verifyLink = document.getElementById('certModalVerify');
        if (d.verifyUrl) {
          verifyLink.href = d.verifyUrl;
          verifyLink.style.display = '';
        } else {
          verifyLink.style.display = 'none';
        }

        new bootstrap.Modal(document.getElementById('certDetailsModal')).show();
      }

      function copyCertCode() {
        var code = document.getElementById('certModalCode').textContent;
        if (!code || code === '—') return;
        if (navigator.clipboard && navigator.clipboard.writeText) {
          navigator.clipboard.writeText(code);
        }
        Swal.fire({ icon: 'success', title: null, text: code, timer: 1200, showConfirmButton: false });
      }

      function handleCertUpload(e) {
        var file = e.target.files[0];
        if (!file) { certUploadData = null; document.getElementById('certPreview').style.display = 'none'; return; }
        var reader = new FileReader();
        reader.onload = function(ev) {
          certUploadData = { name: file.name, file: file, url: ev.target.result };
          var preview = document.getElementById('certPreview');
          if (file.type.startsWith('image/')) {
            preview.innerHTML = '<img src="'+ev.target.result+'" style="max-width:100%;max-height:120px;border-radius:8px;border:1px solid rgba(15,109,128,0.08);" />';
          } else {
            preview.innerHTML = '<span class="small text-success"><i class="fa-solid fa-file-pdf me-1"></i>'+file.name+'</span>';
          }
          preview.style.display = '';
        };
        reader.readAsDataURL(file);
      }
    </script>
@endpush
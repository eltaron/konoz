// Mobile offcanvas toggle (falls back if Bootstrap JS not ready)
function toggleMobileNav() {
  var el = document.getElementById('teacherOffcanvas');
  if (el) { var bsOffcanvas = bootstrap.Offcanvas.getInstance(el) || new bootstrap.Offcanvas(el); bsOffcanvas.toggle(); }
}

// Reusable SweetAlert modals
function showConfirm(title, text, callback) {
  Swal.fire({ icon: 'question', title: title, text: text, showCancelButton: true, confirmButtonColor: '#0F6D80', confirmButtonText: 'نعم', cancelButtonText: 'إلغاء' }).then(function(r) { if (r.isConfirmed && callback) callback(); });
}

function showSuccess(title, text) {
  Swal.fire({ icon: 'success', title: title, text: text, confirmButtonColor: '#0F6D80', confirmButtonText: 'حسناً' });
}

function showAddModal(title, fieldsHtml) {
  Swal.fire({ title: title, html: fieldsHtml, showCancelButton: true, confirmButtonColor: '#0F6D80', confirmButtonText: 'حفظ', cancelButtonText: 'إلغاء', preConfirm: function() { return true; } });
}

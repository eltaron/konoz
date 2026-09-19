@extends('layouts.teacher')

@section('title', __('messages.teacher_notifications') . ' | ' . __('messages.site_name'))
@section('meta_description', __('messages.teacher_notifications_meta'))
@section('header_greeting', __('messages.teacher_header_greeting', ['name' => __('messages.teacher_female')]))
@section('page_title', __('messages.teacher_notifications_title'))

@push('styles')
<style>
  .notif-item { padding: 16px 20px; border-bottom: 1px solid rgba(15,109,128,0.04); transition: background 0.15s; }
  .notif-item:hover { background: rgba(15,109,128,0.02); }
  .notif-item.unread { background: rgba(15,109,128,0.03); border-right: 3px solid #0F6D80; }
  .notif-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
  .notif-time { font-size: 0.72rem; color: #aab7bc; }
  .notif-type { font-size: 0.75rem; font-weight: 600; }
  .notif-title { font-size: 0.9rem; }
  .notif-desc { font-size: 0.82rem; color: #6b7a7e; }
  .mark-all-btn { border-radius: 8px; border: 1px solid rgba(15,109,128,0.12); background: #fff; color: #0F6D80; font-weight: 600; font-size: 0.82rem; padding: 6px 16px; }
  .mark-all-btn:hover { background: rgba(15,109,128,0.04); }
</style>
@endpush

@section('content')
  <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
    <div>
      <h1 class="fw-bold fs-4 mb-0" style="color:#1f2937;">{{ __('messages.teacher_notifications_title') }}</h1>
      <span style="font-size:0.82rem;color:#6b7a7e;">{{ __('messages.teacher_notifications_subtitle') }}</span>
    </div>
    <button class="mark-all-btn" onclick="markAllRead()"><i class="fa-regular fa-circle-check me-1"></i>{{ __('messages.teacher_notifications_mark_all') }}</button>
  </div>
  <div class="teacher-card p-0" id="notifList">
    @forelse($notifications ?? [] as $n)
    @php
      $typeIcons = ['new_registration'=>'fa-solid fa-user-plus','new_exam'=>'fa-solid fa-pen-to-square','reminder'=>'fa-solid fa-bell','certificate'=>'fa-solid fa-certificate','cancelled'=>'fa-solid fa-calendar-xmark','attended'=>'fa-solid fa-circle-check','message'=>'fa-regular fa-message'];
      $typeColors = ['new_registration'=>'#198754','new_exam'=>'#0d6efd','reminder'=>'#cc9a06','certificate'=>'#0F6D80','cancelled'=>'#dc3545','attended'=>'#198754','message'=>'#6f42c1'];
      $typeBg = ['new_registration'=>'rgba(25,135,84,0.08)','new_exam'=>'rgba(13,110,253,0.08)','reminder'=>'rgba(255,193,7,0.08)','certificate'=>'rgba(15,109,128,0.08)','cancelled'=>'rgba(220,53,69,0.08)','attended'=>'rgba(25,135,84,0.08)','message'=>'rgba(111,66,193,0.08)'];
      $typeLabels = ['new_registration'=>__('messages.teacher_notifications_type_new_reg'),'new_exam'=>__('messages.teacher_notifications_type_new_exam'),'reminder'=>__('messages.teacher_notifications_type_reminder'),'certificate'=>__('messages.teacher_notifications_type_certificate'),'cancelled'=>__('messages.teacher_notifications_type_cancelled'),'attended'=>__('messages.teacher_notifications_type_attended'),'message'=>__('messages.teacher_notifications_type_message')];
      $nType = $n->type ?? 'message';
      $isUnread = !($n->is_read ?? false);
    @endphp
    <div class="notif-item @if($isUnread) unread @endif d-flex align-items-start gap-3" @if(!empty($n->url)) style="cursor:pointer;" data-url="{{ $n->url }}" onclick="@if($nType === 'message')handleMessageNotif(event, this)@else location.href='{{ $n->url }}'@endif" @endif>
      <div class="notif-icon" style="background:{{ $typeBg[$nType] ?? 'rgba(15,109,128,0.08)' }};color:{{ $typeColors[$nType] ?? '#0F6D80' }};"><i class="{{ $typeIcons[$nType] ?? 'fa-solid fa-bell' }}"></i></div>
      <div class="flex-grow-1">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-1">
          <div>
            <span class="notif-type" style="color:{{ $typeColors[$nType] ?? '#0F6D80' }};">{{ $typeLabels[$nType] ?? '' }}</span>
            @if($isUnread)<span class="teacher-badge teacher-badge-success ml-2" style="font-size:0.65rem;padding:1px 8px;">{{ __('messages.teacher_notifications_type_new') }}</span>@endif
          </div>
          <span class="notif-time">{{ $n->created_at ? \Carbon\Carbon::parse($n->created_at)->diffForHumans() : '' }}</span>
        </div>
        <div class="notif-title fw-semibold mt-1" style="color:#1f2937;">{{ $n->title ?? '' }}</div>
        <div class="notif-desc mt-1">{{ $n->body ?? $n->description ?? '' }}</div>
      </div>
    </div>
    @empty
    <div class="text-center py-5">
      <i class="fa-solid fa-bell fs-1 text-secondary opacity-25 mb-3 d-block"></i>
      <p class="text-secondary">{{ __('messages.teacher_notifications_empty') }}</p>
    </div>
    @endforelse
  </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function handleMessageNotif(e, el) {
  e.preventDefault();
  fetch('{{ route("teacher.notifications.mark-all-read") }}', {
    method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
  }).catch(function() {}).finally(function() {
    window.location.href = el.getAttribute('data-url');
  });
}
function markAllRead() {
  fetch('{{ route("teacher.notifications.mark-all-read") }}', {
    method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
  }).then(function(r) { return r.json(); }).then(function(result) {
    if (result.status === 'ok') {
      document.querySelectorAll('.notif-item.unread').forEach(function(el) { el.classList.remove('unread'); });
      Swal.fire({ icon: 'success', title: '{{ __('messages.teacher_notifications_marked') }}', text: '{{ __('messages.teacher_notifications_marked_text') }}', confirmButtonColor: '#0F6D80', timer: 1500 });
    }
  }).catch(function() {
    Swal.fire({ icon: 'success', title: '{{ __('messages.teacher_notifications_marked') }}', text: '{{ __('messages.teacher_notifications_marked_text') }}', confirmButtonColor: '#0F6D80', timer: 1500 });
  });
}
</script>
@endpush
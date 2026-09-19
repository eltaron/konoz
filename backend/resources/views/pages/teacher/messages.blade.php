@extends('layouts.teacher')

@section('title', __('messages.teacher_messages') . ' | ' . __('messages.site_name'))
@section('meta_description', __('messages.teacher_messages_meta'))
@section('header_greeting', __('messages.teacher_header_greeting', ['name' => __('messages.teacher_female')]))
@section('page_title', __('messages.teacher_messages_page_title'))

@push('styles')
<style>
  .chat-search { border-radius: 10px; border: 1.5px solid rgba(15,109,128,0.1); padding: 8px 14px; font-size: 0.85rem; width: 100%; }
  .chat-search:focus { border-color: #0F6D80; box-shadow: 0 0 0 3px rgba(15,109,128,0.08); outline: none; }
  .conv-item { display: flex; align-items: center; gap: 12px; padding: 12px; border-radius: 12px; cursor: pointer; transition: all 0.2s; border: none; background: none; width: 100%; text-align: right; }
  .conv-item:hover { background: rgba(15,109,128,0.04); }
  .conv-item.active { background: rgba(15,109,128,0.08); }
  .conv-avatar { width: 44px; height: 44px; border-radius: 50%; object-fit: cover; background: #e9ecef; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #0F6D80; flex-shrink: 0; }
  .conv-info { flex: 1; min-width: 0; }
  .conv-name { font-weight: 700; font-size: 0.9rem; color: #1f2937; margin-bottom: 2px; }
  .conv-msg { font-size: 0.8rem; color: #6b7a7e; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .conv-time { font-size: 0.7rem; color: #aab7bc; white-space: nowrap; }
  .unread-badge { background: #0F6D80; color: #fff; border-radius: 50%; min-width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: 700; }
  .msg-received { display: flex; justify-content: flex-start; margin-bottom: 12px; }
  .msg-sent { display: flex; justify-content: flex-end; margin-bottom: 12px; }
  .msg-bubble { max-width: 75%; padding: 10px 16px; border-radius: 16px; font-size: 0.85rem; line-height: 1.5; }
  .msg-received .msg-bubble { background: #f0f4f5; color: #1f2937; border-bottom-right-radius: 4px; }
  .msg-sent .msg-bubble { background: #0F6D80; color: #fff; border-bottom-left-radius: 4px; }
  .msg-time { font-size: 0.65rem; color: #aab7bc; margin-top: 4px; }
  .msg-sent .msg-time { color: rgba(255,255,255,0.6); }
  .chat-input { border-radius: 10px; border: 1.5px solid rgba(15,109,128,0.1); padding: 10px 14px; font-size: 0.85rem; flex: 1; }
  .chat-input:focus { border-color: #0F6D80; box-shadow: 0 0 0 3px rgba(15,109,128,0.08); outline: none; }
  .msg-area { max-height: 420px; overflow-y: auto; padding: 8px 4px; }
</style>
@endpush

@section('content')
  <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
    <h5 class="fw-bold mb-0">{{ __('messages.teacher_messages_page_title') }}</h5>
    <button class="add-btn" onclick="showNewMessageModal()"><i class="fa-solid fa-pen ml-1"></i>{{ __('messages.teacher_messages_send_btn') }}</button>
  </div>
  <div class="row g-3">
    <div class="col-12 col-lg-4">
      <div class="teacher-card">
        <h6 class="teacher-section-title"><i class="fa-solid fa-comments text-primary"></i>{{ __('messages.teacher_messages_conversations') }}</h6>
        <input type="text" class="chat-search mb-3" placeholder="{{ __('messages.teacher_messages_search') }}" oninput="filterConversations(this.value)" />
        <div style="max-height:480px;overflow-y:auto;" id="conversationList">
          @php
            $conversations = collect();
            if (isset($messages) && $messages->count()) {
              $grouped = $messages->groupBy(function($m) use ($user) {
                return $m->sender_id === $user->id ? $m->receiver_id : $m->sender_id;
              });
              $conversations = $grouped->map(function($msgs, $contactId) use ($user) {
                $last = $msgs->first();
                $contact = $last->sender_id === $user->id ? $last->receiver : $last->sender;
                $unread = $msgs->where('is_read', false)->where('receiver_id', $user->id)->count();
                return (object)[
                  'contact_id' => $contactId,
                  'name' => $contact->name ?? __('messages.teacher_messages_unknown'),
                  'last_msg' => $last->body,
                  'time' => $last->created_at->diffForHumans(),
                  'unread' => $unread,
                ];
              })->keyBy('contact_id');
            }
          @endphp
          @php
            $hasContacts = ($admins->count() + $students->count()) > 0;
            $renderContact = function($contactId, $name, $isAdmin) use ($conversations) {
              $conv = $conversations->get($contactId);
              echo '<button class="conv-item" data-contact-id="' . e($contactId) . '" data-name="' . e($name) . '" onclick="selectConversation(this, ' . $contactId . ', \'' . addslashes($name) . '\')">';
              echo '<div class="conv-avatar" style="background:' . ($isAdmin ? '#6f42c1' : '#0F6D80') . ';color:#fff;">' . mb_substr($name, 0, 1) . '</div>';
              echo '<div class="conv-info"><div class="conv-name">' . e($name) . '</div>';
              if ($conv) {
                echo '<div class="conv-msg">' . e($conv->last_msg) . '</div></div><div class="text-end"><div class="conv-time">' . e($conv->time) . '</div>';
                if ($conv->unread > 0) echo '<div class="unread-badge mt-1">' . $conv->unread . '</div>';
                echo '</div>';
              } else {
                echo '<div class="conv-msg">' . __('messages.teacher_messages_start_hint') . '</div></div>';
              }
              echo '</button>';
            };
          @endphp
          @if(!$hasContacts)
          <p class="text-center text-secondary opacity-75 my-3">{{ __('messages.teacher_messages_no_conversations') }}</p>
          @endif

          @if($admins->count())
          <div class="px-3 pt-2 pb-1 fw-bold" style="font-size:0.72rem;color:#6f42c1;letter-spacing:0.5px;"><i class="fa-solid fa-building-shield me-1"></i>{{ __('messages.teacher_messages_group_admin') }}</div>
          @foreach($admins as $a)
            {{ $renderContact($a->id, $a->name, true) }}
          @endforeach
          @endif

          @php
            $studentsWithUser = $students->filter(fn($s) => $s->user);
          @endphp
          @if($studentsWithUser->count())
          <div class="px-3 pt-2 pb-1 fw-bold" style="font-size:0.72rem;color:#0F6D80;letter-spacing:0.5px;"><i class="fa-solid fa-user-graduate me-1"></i>{{ __('messages.teacher_messages_group_students') }}</div>
          @foreach($studentsWithUser as $s)
            {{ $renderContact($s->user->id, $s->name, false) }}
          @endforeach
          @endif
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-8">
      <div class="teacher-card d-flex flex-column">
        <div class="d-flex align-items-center gap-3 pb-3 border-bottom mb-3">
          <div class="conv-avatar" style="background:#0F6D80;color:#fff;width:40px;height:40px;font-size:0.9rem;" id="chatAvatar">م</div>
          <div>
<h6 class="fw-bold mb-0" style="font-size:0.95rem;" id="chatContactName">{{ __('messages.teacher_messages_select_chat') }}</h6>
            <small style="color:#6b7a7e;" id="chatContactInfo">{{ __('messages.teacher_messages_start_chat') }}</small>
          </div>
        </div>
        <div class="msg-area flex-grow-1" id="chatMessages">
          <p class="text-center text-secondary opacity-75 my-5">{{ __('messages.teacher_messages_select_from_list') }}</p>
        </div>
        <div class="d-flex align-items-center gap-2 pt-3 border-top mt-3" id="chatInputArea" style="display:none !important;">
          <input type="text" class="chat-input" id="chatInput" placeholder="{{ __('messages.teacher_messages_placeholder') }}" onkeypress="if(event.key==='Enter'){sendChatMessage();}" />
          <button class="add-btn" style="padding:8px 16px;" onclick="sendChatMessage()"><i class="fa-solid fa-paper-plane"></i></button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
var currentContactId = null;
var csrfToken = '{{ csrf_token() }}';
var locale = '{{ app()->getLocale() }}';
var teacherNoMessages = '{{ __('messages.teacher_messages_no_messages_yet') }}';
var teacherActiveChat = '{{ __('messages.teacher_messages_active_chat') }}';

function toggleMobileNav() {
  var sidebar = document.querySelector('.teacher-sidebar');
  if (sidebar) sidebar.style.display = sidebar.style.display === 'flex' ? 'none' : 'flex';
}

function selectConversation(el, contactId, name) {
  document.querySelectorAll('.conv-item').forEach(function(e) { e.classList.remove('active'); });
  el.classList.add('active');
  currentContactId = contactId;
  document.getElementById('chatContactName').textContent = name;
  document.getElementById('chatContactInfo').textContent = teacherActiveChat;
  document.getElementById('chatAvatar').textContent = name.charAt(0);
  document.getElementById('chatInputArea').style.display = 'flex';
  fetchMessages(contactId);
}

function fetchMessages(contactId) {
  fetch('{{ route("teacher.messages.fetch", "PLACEHOLDER") }}'.replace('PLACEHOLDER', contactId), {
    headers: { 'Accept': 'application/json' }
  }).then(function(r) { return r.json(); }).then(function(data) {
    renderMessages(data.messages);
    var badge = document.querySelector('.conv-item[data-contact-id="' + contactId + '"] .unread-badge');
    if (badge) badge.remove();
  }).catch(function(e) { console.error(e); });
}

function renderMessages(messages) {
  var area = document.getElementById('chatMessages');
  if (!messages || messages.length === 0) {
    area.innerHTML = '<p class="text-center text-secondary opacity-75 my-5">' + teacherNoMessages + '</p>';
    return;
  }
  var userId = {{ $user->id }};
  area.innerHTML = messages.map(function(m) {
    var isSent = m.sender_id === userId;
    var cls = isSent ? 'msg-sent' : 'msg-received';
    var localeStr = locale === 'ar' ? 'ar-SA' : 'en-US';
    var time = m.created_at ? new Date(m.created_at).toLocaleString(localeStr) : '';
    return '<div class="' + cls + '"><div><div class="msg-bubble">' + escapeHtml(m.body) + '</div><div class="msg-time">' + time + '</div></div></div>';
  }).join('');
  area.scrollTop = area.scrollHeight;
}

function sendChatMessage() {
  var input = document.getElementById('chatInput');
  var text = input.value.trim();
  if (!text || !currentContactId) return;
  fetch('{{ route("teacher.messages.send") }}', {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
    body: JSON.stringify({ receiver_id: currentContactId, body: text })
  }).then(function(r) { return r.json(); }).then(function(result) {
    if (result.status === 'sent') {
      input.value = '';
      fetchMessages(currentContactId);
    }
  }).catch(function(e) { console.error(e); });
}

function showNewMessageModal() {
  Swal.fire({
    title: '{{ __('messages.teacher_messages_new_message') }}',
    html: '<div class="text-end"><div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_messages_to') }}</label><select id="msgReceiver" class="form-select teacher-input">@if($admins->count())<optgroup label="{{ __('messages.teacher_messages_group_admin') }}">@foreach($admins as $a)<option value="{{ $a->id }}">{{ $a->name }}</option>@endforeach</optgroup>@endif@php($studentsWithUser = $students->filter(function($s) { return $s->user; }))@if($studentsWithUser->count())<optgroup label="{{ __('messages.teacher_messages_group_students') }}">@foreach($studentsWithUser as $s)<option value="{{ $s->user->id }}">{{ $s->name }}</option>@endforeach</optgroup>@endif</select></div><div class="mb-3"><label class="form-label fw-medium small d-block">{{ __('messages.teacher_messages_message') }}</label><textarea id="msgBody" class="form-control teacher-input" rows="4" placeholder="{{ __('messages.teacher_messages_write_message') }}"></textarea></div></div>',
    showCancelButton: true, focusConfirm: false,
    confirmButtonColor: '#0F6D80', cancelButtonColor: '#6c757d',
    confirmButtonText: '<i class="fa-solid fa-paper-plane ml-1"></i>{{ __('messages.teacher_messages_send') }}', cancelButtonText: '{{ __('messages.teacher_messages_cancel') }}',
    preConfirm: function() {
      var receiverId = document.getElementById('msgReceiver').value;
      var body = document.getElementById('msgBody').value.trim();
      if (!body) { Swal.showValidationMessage('{{ __('messages.teacher_messages_validation_required') }}'); return; }
      return fetch('{{ route("teacher.messages.send") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify({ receiver_id: receiverId, body: body })
      }).then(function(r) {
        if (!r.ok) return r.json().then(function(e) { throw new Error(e.message || '{{ __('messages.teacher_messages_error') }}'); });
        return r.json();
      }).then(function(result) {
        if (result.status === 'sent') {
          Swal.fire({ icon: 'success', title: '{{ __('messages.teacher_messages_success_title') }}', text: '{{ __('messages.teacher_messages_success') }}', confirmButtonColor: '#0F6D80', confirmButtonText: '{{ __('messages.teacher_messages_ok') }}', timer: 1500 });
        }
      }).catch(function(e) { Swal.showValidationMessage(e.message); });
    },
    allowOutsideClick: false
  });
}

function filterConversations(query) {
  document.querySelectorAll('.conv-item').forEach(function(el) {
    var name = el.getAttribute('data-name') || '';
    el.style.display = name.includes(query) ? '' : 'none';
  });
}

function escapeHtml(text) {
  var d = document.createElement('div');
  d.textContent = text;
  return d.innerHTML;
}

(function() {
  var urlTo = new URLSearchParams(window.location.search).get('to');
  if (urlTo) {
    var btn = document.querySelector('.conv-item[data-contact-id="' + urlTo + '"]');
    if (btn) btn.click();
  }
})();
</script>
@endpush

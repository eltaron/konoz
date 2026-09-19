@extends('layouts.student')

@section('title', __('messages.student_messages') . ' | ' . __('messages.site_name'))
@section('meta_description', __('messages.student_messages_meta', ['site_name' => __('messages.site_name')]))

@push('styles')
<style>
.chat-conversation { cursor: pointer; transition: 0.2s; border-radius: 12px; padding: 12px 16px; }
      .chat-conversation:hover, .chat-conversation.active { background: rgba(15,109,128,0.04); }
      .chat-conversation.active { border-right: 3px solid #0F6D80; }
      .unread-dot { width: 10px; height: 10px; border-radius: 50%; background: #0F6D80; display: inline-block; flex-shrink: 0; }
      .chat-bubble-received { background: #f1f4f5; border-radius: 16px 16px 16px 4px; padding: 12px 16px; max-width: 80%; align-self: flex-end; }
      .chat-bubble-sent { background: #0F6D80; color: #fff; border-radius: 16px 16px 4px 16px; padding: 12px 16px; max-width: 80%; align-self: flex-start; }
      .chat-area { display: flex; flex-direction: column; gap: 12px; max-height: 460px; overflow-y: auto; padding: 16px; }
      .chat-input-area { border-top: 1px solid rgba(15,109,128,0.08); padding: 12px 16px; background: #fff; border-radius: 0 0 12px 12px; }
      .chat-header { border-bottom: 1px solid rgba(15,109,128,0.08); padding: 12px 16px; }
      @media (max-width: 991.98px) { .conversations-col { border-bottom: 1px solid rgba(15,109,128,0.08); } }
</style>
@endpush

@section('content')
<h1 class="fw-bold mb-4" style="color: #0F6D80; font-size: 1.6rem;" data-aos="fade-up">{{ __('messages.student_messages') }}</h1>

      <div class="dash-card p-0 overflow-hidden" data-aos="fade-up">
        <div class="row g-0">
          <div class="col-lg-6 conversations-col p-3">
            <div class="d-flex flex-column gap-1" id="conversationList">
              @php
              $conversations = collect();
              if (isset($messages) && $messages->count()) {
                $grouped = $messages->groupBy(function($m) use ($user) {
                  return $m->sender_id === ($user->id ?? 0) ? $m->receiver_id : $m->sender_id;
                });
                $conversations = $grouped->map(function($msgs, $contactId) use ($user) {
                  $last = $msgs->first();
                  $contact = $last->sender_id === ($user->id ?? 0) ? $last->receiver : $last->sender;
                  $unread = $msgs->where('is_read', false)->where('receiver_id', $user->id ?? 0)->count();
                  return (object)[
                    'contact_id' => $contactId,
                    'name' => $contact->name ?? 'غير معروف',
                    'last_msg' => $last->body,
                    'time' => $last->created_at->diffForHumans(),
                    'unread' => $unread,
                  ];
                })->sortByDesc(function($c) { return $c->unread; });
              }
              @endphp
              @forelse($conversations as $conv)
              <div class="chat-conversation d-flex align-items-center gap-2" data-contact-id="{{ $conv->contact_id }}" data-name="{{ $conv->name }}" onclick="openConversation(this, {{ $conv->contact_id }}, '{{ addslashes($conv->name) }}')">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;background:#0F6D80;color:#fff;font-weight:700;">
                  {{ mb_substr($conv->name, 0, 1) }}
                </div>
                <div class="flex-grow-1 overflow-hidden">
                  <div class="d-flex justify-content-between">
                    <span class="fw-bold small">{{ $conv->name }}</span>
                    <span class="small text-secondary opacity-50">{{ $conv->time }}</span>
                  </div>
                  <div class="small text-secondary opacity-75 text-truncate">{{ $conv->last_msg }}</div>
                </div>
                @if($conv->unread > 0)
                <span class="badge rounded-pill unread-badge" style="background:#0F6D80;font-size:0.6rem;">{{ $conv->unread }}</span>
                @endif
              </div>
              @empty
              <p class="text-center text-secondary opacity-75 my-3">{{ __('messages.student_chat_no_conversations') }}</p>
              @endforelse
            </div>
          </div>
          <div class="col-lg-6 d-flex flex-column">
            <div class="chat-header d-flex align-items-center gap-3">
              <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;background:#0F6D80;color:#fff;font-weight:700;" id="chatAvatar">م</div>
              <div>
                <span class="fw-bold small" style="color: #0F6D80;" id="chatContactName">{{ __('messages.student_chat_select') }}</span>
                <small class="d-block text-secondary opacity-50" style="font-size: 0.65rem;">{{ __('messages.student_chat_start') }}</small>
              </div>
            </div>
            <div id="chatMessages" class="chat-messages p-3" style="max-height: 400px; overflow-y: auto;">
              <p class="text-center text-secondary opacity-75 my-5">{{ __('messages.student_chat_no_messages') }}</p>
            </div>
            <div class="chat-input-area d-flex align-items-center gap-2" id="chatInputArea" style="display:none !important;">
              <input type="text" class="form-control form-control-custom py-2" id="chatInput" placeholder="{{ __('messages.student_chat_placeholder') }}" style="border-radius: 30px;" onkeypress="if(event.key==='Enter'){sendStudentMessage();}" />
              <button class="btn rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: #0F6D80; color: #fff;" onclick="sendStudentMessage()"><i class="fa-solid fa-paper-plane"></i></button>
            </div>
          </div>
        </div>
      </div>
@endsection

@push('scripts')
<script>
var currentContactId = null;
var csrfToken = '{{ csrf_token() }}';
var chatNoMessages = '{{ __('messages.student_chat_no_messages') }}';
var locale = '{{ app()->getLocale() }}';

function openConversation(el, contactId, name) {
  document.querySelectorAll('.chat-conversation').forEach(function(e) { e.classList.remove('active'); });
  el.classList.add('active');
  currentContactId = contactId;
  document.getElementById('chatContactName').textContent = name;
  document.getElementById('chatAvatar').textContent = name.charAt(0);
  document.getElementById('chatInputArea').style.display = 'flex';
  fetchStudentMessages(contactId);
}

function fetchStudentMessages(contactId) {
  fetch('{{ route("student.messages.fetch", "PLACEHOLDER") }}'.replace('PLACEHOLDER', contactId), {
    headers: { 'Accept': 'application/json' }
  }).then(function(r) { return r.json(); }).then(function(data) {
    renderStudentMessages(data.messages);
    var badge = el = document.querySelector('.chat-conversation[data-contact-id="' + contactId + '"] .unread-badge');
    if (badge) badge.remove();
  }).catch(function(e) { console.error(e); });
}

function renderStudentMessages(messages) {
  var area = document.getElementById('chatMessages');
  if (!messages || messages.length === 0) {
    area.innerHTML = '<p class="text-center text-secondary opacity-75 my-5">' + chatNoMessages + '</p>';
    return;
  }
  var userId = {{ $user->id ?? 0 }};
  area.innerHTML = '<div class="chat-area">' + messages.map(function(m) {
    var isSent = m.sender_id === userId;
    var cls = isSent ? 'chat-bubble-sent' : 'chat-bubble-received';
    var align = isSent ? 'align-items-end' : 'align-items-start';
    var localeStr = locale === 'ar' ? 'ar-SA' : 'en-US';
    return '<div class="d-flex flex-column ' + align + '"><div class="' + cls + '">' + escapeHtml(m.body) + '</div><small class="text-secondary opacity-50" style="font-size:0.6rem;">' + (m.created_at ? new Date(m.created_at).toLocaleString(localeStr) : '') + '</small></div>';
  }).join('') + '</div>';
  area.scrollTop = area.scrollHeight;
}

function sendStudentMessage() {
  var input = document.getElementById('chatInput');
  var text = input.value.trim();
  if (!text || !currentContactId) return;
  fetch('{{ route("student.messages.send") }}', {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
    body: JSON.stringify({ receiver_id: currentContactId, body: text })
  }).then(function(r) { return r.json(); }).then(function(result) {
    if (result.status === 'sent') {
      input.value = '';
      fetchStudentMessages(currentContactId);
    }
  }).catch(function(e) { console.error(e); });
}

function escapeHtml(text) {
  var d = document.createElement('div');
  d.textContent = text;
  return d.innerHTML;
}
</script>
@endpush

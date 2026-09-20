@extends('layouts.student')

@section('title', __('messages.student_messages') . ' | ' . __('messages.site_name'))
@section('meta_description', __('messages.student_messages_meta', ['site_name' => __('messages.site_name')]))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard/dashboard-messages.css') }}" />
    <style>
        .modal-header .btn-close {
            margin: calc(-.5 * var(--bs-modal-header-padding-y)) auto calc(-.5 * var(--bs-modal-header-padding-x)) calc(-.5 * var(--bs-modal-header-padding-y));
        }

        .modal-title {
            color: #fff;
        }
    </style>
@endpush

@section('content')
    {{-- Hero --}}
    <div class="cd-msgs-hero mb-4" data-aos="fade-up">
        <div class="d-flex align-items-center gap-3">
            <div class="cd-msgs-hero-icon"><i class="fa-solid fa-envelope-open-text"></i></div>
            <div class="flex-grow-1">
                <h4 class="cd-msgs-hero-title mb-1">{{ __('messages.student_messages_inbox') }}</h4>
                <div class="cd-msgs-hero-sub">{{ __('messages.student_messages_subtitle') }}</div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="cd-msgs-hero-chip"><span class="chip-dot"></span>{{ $conversations->count() }}
                    {{ __('messages.student_messages_conversations') }}</span>
                @if ($unreadTotal > 0)
                    <span class="cd-msgs-hero-chip chip-gold"><i class="fa-solid fa-envelope"></i>{{ $unreadTotal }}
                        {{ __('messages.student_messages_unread') }}</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Messenger --}}
    <div class="cd-msgs-wrap" data-aos="fade-up">
        {{-- Conversations list --}}
        <div class="cd-msgs-list" id="cdMsgList">
            <div class="cd-msgs-list-head">
                <button type="button" class="cd-msgs-new-btn" data-bs-toggle="modal" data-bs-target="#cdNewChatModal">
                    <i class="fa-solid fa-pen"></i>{{ __('messages.student_messages_new_message') }}
                </button>
                <div class="cd-msgs-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="cdConvSearch" placeholder="{{ __('messages.student_messages_search') }}"
                        oninput="filterConversations()" />
                </div>
            </div>
            <div class="cd-msgs-colls" id="cdConvList">
                @forelse($conversations as $conv)
                    <div class="cd-conv" data-contact-id="{{ $conv->contact_id }}"
                        data-name="{{ addslashes($conv->name) }}"
                        onclick="openConversation(this, {{ $conv->contact_id }}, '{{ addslashes($conv->name) }}')">
                        <div class="cd-avatar {{ 'g' . (($conv->contact_id % 6) + 1) }}">
                            {{ mb_strtoupper(mb_substr($conv->name, 0, 1)) }}</div>
                        <div class="cd-conv-body">
                            <div class="cd-conv-top">
                                <span class="cd-conv-name">{{ $conv->name }}</span>
                                <span class="cd-conv-time">{{ $conv->time }}</span>
                            </div>
                            <div class="cd-conv-last">
                                @if ($conv->last_sender_me)
                                    <span class="you-label">{{ __('messages.student_messages_you') }}:</span>
                                @endif
                                <span class="txt">{{ $conv->last_msg }}</span>
                            </div>
                        </div>
                        @if ($conv->unread > 0)
                            <span class="cd-conv-unread">{{ $conv->unread }}</span>
                        @endif
                    </div>
                @empty
                    <div class="cd-list-empty">
                        <i class="fa-regular fa-envelope"></i>
                        <h6>{{ __('messages.student_messages_no_conv') }}</h6>
                        <p>{{ __('messages.student_messages_empty_hint') }}</p>
                    </div>
                @endforelse
                <div class="cd-search-none" id="cdSearchNone">{{ __('messages.student_messages_no_conv') }}</div>
            </div>
        </div>

        {{-- Chat pane --}}
        <div class="cd-msgs-chat" id="cdMsgChat">
            <div class="cd-chat-header">
                <button type="button" class="cd-chat-back" onclick="backToList()"
                    aria-label="{{ __('messages.student_messages_back') }}"><i
                        class="fa-solid fa-arrow-right"></i></button>
                <div class="cd-avatar g2" id="cdChatAvatar">م</div>
                <div class="flex-grow-1">
                    <div class="cd-chat-name" id="cdChatName">{{ __('messages.student_chat_select') }}</div>
                    <div class="cd-chat-status"><span class="live-dot"></span>{{ __('messages.student_messages_online') }}
                    </div>
                </div>
            </div>
            <div class="cd-chat-body" id="cdChatBody">
                <div class="cd-chat-empty">
                    <div class="icon"><i class="fa-regular fa-comments"></i></div>
                    <h6>{{ __('messages.student_messages_pick') }}</h6>
                    <p>{{ __('messages.student_messages_pick_hint') }}</p>
                </div>
            </div>
            <div class="cd-chat-input" id="cdChatInput">
                <input type="text" id="cdMsgInput" autocomplete="off"
                    placeholder="{{ __('messages.student_messages_placeholder') }}"
                    onkeypress="if(event.key==='Enter')sendStudentMessage()" />
                <button id="cdSendBtn" onclick="sendStudentMessage()" title="{{ __('messages.student_messages_send') }}"><i
                        class="fa-solid fa-paper-plane"></i></button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- New message modal --}}
    <div class="modal fade cd-newchat-modal" id="cdNewChatModal" tabindex="-1" aria-labelledby="cdNewChatModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="cdNewChatModalLabel">{{ __('messages.student_messages_new_message') }}
                        </h5>
                        <small>{{ __('messages.student_messages_admin_hint') }}</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="cd-sec-name"><i
                            class="fa-solid fa-shield-halved"></i>{{ __('messages.student_messages_admin_team') }}</div>
                    <div class="vstack gap-2">
                        @forelse($admins as $adm)
                            <div class="cd-newchat-admin"
                                onclick="startNewChat({{ $adm->id }}, '{{ addslashes($adm->name ?? '') }}')">
                                <div class="cd-mini-avatar {{ 'g' . (($adm->id % 6) + 1) }}">
                                    {{ mb_strtoupper(mb_substr($adm->name ?? 'م', 0, 1)) }}</div>
                                <div>
                                    <div class="name">{{ $adm->name ?? __('messages.teacher_messages_group_admin') }}
                                    </div>
                                    <div class="role"><i
                                            class="fa-solid fa-shield-halved me-1"></i>{{ __('messages.student_messages_admin_team') }}
                                    </div>
                                </div>
                                <i class="fa-solid fa-chevron-left ms-auto" style="color:#c2cdd0; font-size:0.75rem;"></i>
                            </div>
                        @empty
                            <p class="text-center text-secondary opacity-75 small py-3">—</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var currentContactId = null;
        var csrfToken = '{{ csrf_token() }}';
        var locale = '{{ app()->getLocale() }}';
        var userId = {{ $user->id ?? 0 }};
        var translations = {
            noMessages: '{{ __('messages.student_chat_no_messages') }}',
            you: '{{ __('messages.student_messages_you') }}',
            today: '{{ __('messages.student_messages_today') }}',
            yesterday: '{{ __('messages.student_messages_yesterday') }}',
            welcome: '{{ __('messages.student_messages_welcome') }}',
            welcomeHint: '{{ __('messages.student_messages_welcome_hint') }}',
            adminTeam: '{{ __('messages.teacher_messages_group_admin') }}',
            sending: '{{ __('messages.student_messages_sending') }}'
        };
        var dayLabels = {
            today: translations.today,
            yesterday: translations.yesterday
        };

        function openConversation(el, contactId, name) {
            currentContactId = contactId;
            document.querySelectorAll('.cd-conv').forEach(function(c) {
                c.classList.remove('active');
            });
            if (el) el.classList.add('active');

            document.getElementById('cdChatName').textContent = name;
            var av = document.getElementById('cdChatAvatar');
            av.textContent = name.charAt(0);
            av.className = 'cd-avatar g' + ((contactId % 6) + 1);

            var list = document.getElementById('cdMsgList');
            var chat = document.getElementById('cdMsgChat');
            if (window.innerWidth < 992) {
                list.classList.add('cd-hidden');
                chat.classList.add('cd-open');
            }

            fetchStudentMessages(contactId);
        }

        function backToList() {
            document.getElementById('cdMsgChat').classList.remove('cd-open');
            document.getElementById('cdMsgList').classList.remove('cd-hidden');
        }

        function startNewChat(contactId, name) {
            var modalEl = document.getElementById('cdNewChatModal');
            var modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
            else document.querySelector('#cdNewChatModal .btn-close').click();
            name = name || translations.adminTeam;

            var existing = document.querySelector('.cd-conv[data-contact-id="' + contactId + '"]');
            if (existing) {
                openConversation(existing, contactId, name);
                return;
            }

            currentContactId = contactId;
            document.getElementById('cdChatName').textContent = name;
            var av = document.getElementById('cdChatAvatar');
            av.textContent = name.charAt(0);
            av.className = 'cd-avatar g' + ((contactId % 6) + 1);

            var body = document.getElementById('cdChatBody');
            body.innerHTML =
                '<div class="cd-chat-empty"><div class="icon welcome"><i class="fa fa-hand-sparkles"></i></div><h6>' +
                translations.welcome + '</h6><p>' + translations.welcomeHint + '</p></div>';

            var list = document.getElementById('cdMsgList');
            var chat = document.getElementById('cdMsgChat');
            if (window.innerWidth < 992) {
                list.classList.add('cd-hidden');
                chat.classList.add('cd-open');
            }
        }

        function fetchStudentMessages(contactId) {
            fetch('{{ route('student.messages.fetch', 'PLACEHOLDER') }}'.replace('PLACEHOLDER', contactId), {
                headers: {
                    'Accept': 'application/json'
                }
            }).then(function(r) {
                return r.json();
            }).then(function(data) {
                renderStudentMessages(data.messages);
                var badge = document.querySelector('.cd-conv[data-contact-id="' + contactId + '"] .cd-conv-unread');
                if (badge) badge.remove();
            }).catch(function(e) {
                console.error(e);
            });
        }

        function dayChip(dateStr) {
            var d = new Date(dateStr);
            var today = new Date();
            today.setHours(0, 0, 0, 0);
            var that = new Date(d);
            that.setHours(0, 0, 0, 0);
            var diff = Math.round((today - that) / 86400000);
            var label;
            if (diff === 0) label = dayLabels.today;
            else if (diff === 1) label = dayLabels.yesterday;
            else label = d.toLocaleDateString(locale === 'ar' ? 'ar-EG' : 'en-US', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            return '<div class="cd-day-chip">' + label + '</div>';
        }

        function renderStudentMessages(messages) {
            var area = document.getElementById('cdChatBody');
            if (!messages || messages.length === 0) {
                area.innerHTML =
                    '<div class="cd-chat-empty"><div class="icon"><i class="fa-regular fa-comments"></i></div><h6>' +
                    translations.noMessages + '</h6><p>' + translations.welcomeHint + '</p></div>';
                return;
            }
            var html = '';
            var currentDay = null;
            messages.forEach(function(m) {
                var dayKey = new Date(m.created_at).toDateString();
                if (dayKey !== currentDay) {
                    currentDay = dayKey;
                    html += dayChip(m.created_at);
                }
                var isSent = String(m.sender_id) === String(userId);
                var cls = isSent ? 'sent' : 'received';
                var localeStr = locale === 'ar' ? 'ar-EG' : 'en-US';
                var msgTime = '<span class="cd-msg-time">' + new Date(m.created_at).toLocaleTimeString(localeStr, {
                    hour: '2-digit',
                    minute: '2-digit'
                }) + '</span>';
                html += '<div class="cd-msg ' + cls + '"><div class="cd-bubble">' + escapeHtml(m.body) + '</div>' +
                    msgTime + '</div>';
            });
            area.innerHTML = html;
            area.scrollTop = area.scrollHeight;
        }

        function sendStudentMessage() {
            var input = document.getElementById('cdMsgInput');
            var text = input.value.trim();
            if (!text || currentContactId === null) return;
            var btn = document.getElementById('cdSendBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

            fetch('{{ route('student.messages.send') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    receiver_id: currentContactId,
                    body: text
                })
            }).then(function(r) {
                return r.json();
            }).then(function(result) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i>';
                if (result.status === 'sent') {
                    input.value = '';
                    input.focus();
                    updateConvPreview(result.message ? result : null, text);
                    var exists = document.querySelector('.cd-conv[data-contact-id="' + currentContactId + '"]');
                    if (!exists) {
                        location.reload();
                        return;
                    }
                    fetchStudentMessages(currentContactId);
                }
            }).catch(function(e) {
                console.error(e);
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i>';
            });
        }

        function updateConvPreview(result, text) {
            var conv = document.querySelector('.cd-conv[data-contact-id="' + currentContactId + '"]');
            if (!conv) return;
            var lastTxt = conv.querySelector('.cd-conv-last .txt');
            if (lastTxt) {
                lastTxt.textContent = text.length > 60 ? text.slice(0, 60) + '…' : text;
                var you = conv.querySelector('.cd-conv-last .you-label');
                if (!you) {
                    var s = document.createElement('span');
                    s.className = 'you-label';
                    s.textContent = translations.you + ':';
                    conv.querySelector('.cd-conv-last').prepend(s);
                }
            }
        }

        function filterConversations() {
            var q = document.getElementById('cdConvSearch').value.trim().toLowerCase();
            var items = document.querySelectorAll('#cdConvList .cd-conv');
            var visible = 0;
            items.forEach(function(el) {
                var name = (el.getAttribute('data-name') || '').toLowerCase();
                var show = q === '' || name.indexOf(q) > -1;
                el.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            document.getElementById('cdSearchNone').style.display = (visible === 0 && q !== '') ? 'block' : 'none';
        }

        function escapeHtml(text) {
            var d = document.createElement('div');
            d.textContent = text;
            return d.innerHTML;
        }
    </script>
@endpush

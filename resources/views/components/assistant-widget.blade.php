@props(['lessonId' => null, 'fullpage' => false, 'messages' => [], 'conversationId' => null])

@php
    $mentorName = config('assistant.name');
    $firstName = \Illuminate\Support\Str::of(auth()->user()->name ?? '')->before(' ')->limit(24, '');
@endphp

<style>
    .mq-ai, .mq-ai * { box-sizing: border-box; }
    .mq-ai { font-family: system-ui, -apple-system, "Segoe UI", Tahoma, "Noto Sans Arabic", sans-serif; color: #292333; }
    .mq-ai__launcher {
        position: fixed; right: 20px; bottom: 110px; z-index: 900;
        width: 58px; height: 58px; border: 0; border-radius: 50%; cursor: pointer;
        background: linear-gradient(135deg, #a64ca6, #800080); color: #fff;
        box-shadow: 0 10px 28px rgba(128, 0, 128, .35); display: grid; place-items: center;
        transition: transform .2s ease;
    }
    .mq-ai__launcher:hover { transform: translateY(-2px) scale(1.04); }
    .mq-ai__panel {
        position: fixed; right: 20px; bottom: 180px; z-index: 901;
        width: min(390px, calc(100vw - 32px)); height: min(560px, calc(100vh - 220px));
        background: #fff; border: 1px solid #eee5f0; border-radius: 20px;
        box-shadow: 0 24px 60px rgba(41, 35, 51, .22);
        display: flex; flex-direction: column; overflow: hidden;
    }
    .mq-ai__panel[hidden] { display: none; }
    .mq-ai--full .mq-ai__panel {
        position: static; width: 100%; height: 70vh; min-height: 460px; box-shadow: 0 4px 18px rgba(0,0,0,.05);
    }
    .mq-ai__head {
        display: flex; align-items: center; gap: 12px; padding: 14px 16px; color: #fff;
        background: linear-gradient(135deg, #800080, #a64ca6);
    }
    .mq-ai__avatar { width: 38px; height: 38px; border-radius: 12px; background: rgba(255,255,255,.18); display: grid; place-items: center; font-weight: 700; }
    .mq-ai__head strong { display: block; font-size: 15px; }
    .mq-ai__head small { opacity: .85; font-size: 12px; }
    .mq-ai__head-actions { margin-inline-start: auto; display: flex; gap: 6px; }
    .mq-ai__icon-btn { background: rgba(255,255,255,.16); color: #fff; border: 0; border-radius: 9px; padding: 6px 10px; font-size: 12px; cursor: pointer; }
    .mq-ai__icon-btn:hover { background: rgba(255,255,255,.28); }
    .mq-ai__log { flex: 1; overflow-y: auto; padding: 16px; display: flex; flex-direction: column; gap: 10px; background: #faf8fc; }
    .mq-ai__msg { max-width: 88%; padding: 10px 14px; border-radius: 16px; line-height: 1.7; font-size: 14.5px; white-space: pre-wrap; word-break: break-word; }
    .mq-ai__msg--assistant { align-self: flex-start; background: #fff; border: 1px solid #eee5f0; border-end-start-radius: 4px; }
    .mq-ai__msg--user { align-self: flex-end; background: #800080; color: #fff; border-end-end-radius: 4px; }
    .mq-ai__msg--error { align-self: center; background: #fff4f4; border: 1px solid #f3c6c6; color: #8a1f1f; font-size: 13px; }
    .mq-ai__sources { align-self: flex-start; display: flex; flex-wrap: wrap; gap: 6px; max-width: 92%; }
    .mq-ai__src { font-size: 12.5px; text-decoration: none; color: #800080; background: #f5eaf5; border-radius: 999px; padding: 5px 11px; }
    .mq-ai__src:hover { background: #ecd6ec; }
    .mq-ai__typing { align-self: flex-start; display: flex; gap: 4px; padding: 12px 14px; background: #fff; border: 1px solid #eee5f0; border-radius: 16px; }
    .mq-ai__typing i { width: 7px; height: 7px; border-radius: 50%; background: #b27ab2; animation: mqai 1s infinite ease-in-out; }
    .mq-ai__typing i:nth-child(2) { animation-delay: .15s; } .mq-ai__typing i:nth-child(3) { animation-delay: .3s; }
    @keyframes mqai { 0%, 80%, 100% { transform: scale(.6); opacity: .5; } 40% { transform: scale(1); opacity: 1; } }
    .mq-ai__chips { display: flex; flex-wrap: wrap; gap: 6px; padding: 8px 14px 0; background: #faf8fc; }
    .mq-ai__chip { border: 1px solid #e3d3e3; background: #fff; color: #690069; border-radius: 999px; font-size: 12.5px; padding: 6px 12px; cursor: pointer; }
    .mq-ai__chip:hover { background: #f5eaf5; }
    .mq-ai__form { display: flex; gap: 8px; padding: 12px; border-top: 1px solid #eee5f0; background: #fff; align-items: flex-end; }
    .mq-ai__form textarea { flex: 1; resize: none; max-height: 110px; border: 1px solid #e3d3e3; border-radius: 12px; padding: 10px 12px; font: inherit; font-size: 14.5px; outline: none; }
    .mq-ai__form textarea:focus { border-color: #800080; box-shadow: 0 0 0 3px rgba(128,0,128,.12); }
    .mq-ai__send { border: 0; background: #800080; color: #fff; border-radius: 12px; padding: 10px 16px; font-weight: 600; cursor: pointer; }
    .mq-ai__send:disabled { opacity: .55; cursor: not-allowed; }
    @media (max-width: 520px) { .mq-ai__panel { right: 8px; left: 8px; width: auto; bottom: 176px; } }
</style>

<div id="mq-ai"
     class="mq-ai {{ $fullpage ? 'mq-ai--full' : '' }}"
     data-send-url="{{ route('assistant.send') }}"
     data-history-url="{{ url('/assistant/conversations') }}"
     data-lesson="{{ $lessonId }}"
     data-conversation="{{ $conversationId }}"
     data-fullpage="{{ $fullpage ? 1 : 0 }}">

    @unless($fullpage)
        <button type="button" class="mq-ai__launcher" aria-label="{{ $mentorName }}" aria-expanded="false">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a8 8 0 0 1-11.6 7.1L4 20l1-4.4A8 8 0 1 1 21 12z"/></svg>
        </button>
    @endunless

    <section class="mq-ai__panel" role="dialog" aria-label="{{ $mentorName }}" {{ $fullpage ? '' : 'hidden' }}>
        <header class="mq-ai__head">
            <span class="mq-ai__avatar" aria-hidden="true">M</span>
            <div>
                <strong>{{ $mentorName }}</strong>
                <small>رفيقك في التعلّم</small>
            </div>
            <div class="mq-ai__head-actions">
                <button type="button" class="mq-ai__icon-btn" data-action="new">محادثة جديدة</button>
                @unless($fullpage)
                    <button type="button" class="mq-ai__icon-btn" data-action="close" aria-label="إغلاق">✕</button>
                @endunless
            </div>
        </header>

        <div class="mq-ai__log" aria-live="polite">
            <div class="mq-ai__msg mq-ai__msg--assistant" dir="auto" data-welcome>
                أهلًا {{ $firstName }}! أنا {{ $mentorName }}. اسألني عن أي درس أو مهارة، أو اطلب مني ترشيح دورة تناسب مستواك.
            </div>
            @foreach($messages as $m)
                <div class="mq-ai__msg mq-ai__msg--{{ $m->role }}" dir="auto">{{ $m->content }}</div>
            @endforeach
        </div>

        <div class="mq-ai__chips">
            <button type="button" class="mq-ai__chip">رشّح لي دورة للمبتدئين</button>
            @if($lessonId)
                <button type="button" class="mq-ai__chip">بسّط لي هذا الدرس</button>
            @endif
            <button type="button" class="mq-ai__chip">ما هي خطوتي التالية؟</button>
        </div>

        <form class="mq-ai__form" autocomplete="off">
            <textarea rows="1" maxlength="{{ config('assistant.max_input_chars') }}" placeholder="اكتب سؤالك…" aria-label="رسالتك" dir="auto"></textarea>
            <button type="submit" class="mq-ai__send">إرسال</button>
        </form>
    </section>
</div>

<script>
(function () {
    const root = document.getElementById('mq-ai');
    if (!root || root.dataset.init) return;
    root.dataset.init = '1';

    const q = (s) => root.querySelector(s);
    const panel = q('.mq-ai__panel'), log = q('.mq-ai__log'), form = q('.mq-ai__form');
    const input = form.querySelector('textarea'), sendBtn = form.querySelector('.mq-ai__send');
    const launcher = q('.mq-ai__launcher'), chips = q('.mq-ai__chips');
    const full = root.dataset.fullpage === '1';
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const KEY = 'mq_mentor_conversation';

    let conversationId = root.dataset.conversation || (full ? null : sessionStorage.getItem(KEY)) || null;
    let busy = false;

    const welcomeHtml = log.querySelector('[data-welcome]')?.cloneNode(true);

    function scrollDown() { log.scrollTop = log.scrollHeight; }

    function addMessage(role, text) {
        const el = document.createElement('div');
        el.className = 'mq-ai__msg mq-ai__msg--' + role;
        el.setAttribute('dir', 'auto');
        el.textContent = text;
        log.appendChild(el);
        scrollDown();
        return el;
    }

    function addSources(sources) {
        if (!sources || !sources.length) return;
        const wrap = document.createElement('div');
        wrap.className = 'mq-ai__sources';
        sources.forEach((s) => {
            const a = document.createElement('a');
            a.className = 'mq-ai__src';
            a.href = s.url;
            a.textContent = s.title;
            wrap.appendChild(a);
        });
        log.appendChild(wrap);
        scrollDown();
    }

    function typing(on) {
        let t = log.querySelector('.mq-ai__typing');
        if (on && !t) {
            t = document.createElement('div');
            t.className = 'mq-ai__typing';
            t.innerHTML = '<i></i><i></i><i></i>';
            log.appendChild(t);
            scrollDown();
        } else if (!on && t) {
            t.remove();
        }
    }

    async function send(text) {
        text = (text || '').trim();
        if (!text || busy) return;
        busy = true;
        sendBtn.disabled = true;
        chips.style.display = 'none';
        addMessage('user', text);
        input.value = '';
        input.style.height = 'auto';
        typing(true);

        try {
            const res = await fetch(root.dataset.sendUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    message: text,
                    conversation_id: conversationId ? Number(conversationId) : null,
                    lesson_id: root.dataset.lesson ? Number(root.dataset.lesson) : null,
                }),
            });

            typing(false);

            if (res.status === 429) {
                addMessage('error', 'أرسلت رسائل كثيرة خلال وقت قصير، انتظر قليلًا ثم حاول مجددًا.');
            } else if (res.status === 401 || res.status === 419) {
                addMessage('error', 'انتهت جلستك، أعد تحميل الصفحة وسجّل الدخول من جديد.');
            } else if (!res.ok) {
                addMessage('error', 'تعذّر إرسال رسالتك الآن، حاول مرة أخرى بعد قليل.');
            } else {
                const data = await res.json();
                conversationId = data.conversation_id;
                if (!full) sessionStorage.setItem(KEY, conversationId);
                addMessage('assistant', data.message.content);
                addSources(data.sources);
            }
        } catch (e) {
            typing(false);
            addMessage('error', 'تعذّر الاتصال بالخادم، تحقّق من اتصالك بالإنترنت.');
        } finally {
            busy = false;
            sendBtn.disabled = false;
            input.focus();
        }
    }

    async function restore() {
        if (!conversationId || full) return;
        try {
            const res = await fetch(root.dataset.historyUrl + '/' + conversationId, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
            });
            if (!res.ok) throw new Error('missing');
            const data = await res.json();
            data.messages.forEach((m) => addMessage(m.role, m.content));
            if (data.messages.length) chips.style.display = 'none';
        } catch (e) {
            conversationId = null;
            sessionStorage.removeItem(KEY);
        }
    }

    function open(state) {
        panel.hidden = !state;
        launcher?.setAttribute('aria-expanded', state ? 'true' : 'false');
        if (state) { scrollDown(); input.focus(); }
    }

    launcher?.addEventListener('click', () => open(panel.hidden));
    root.querySelector('[data-action="close"]')?.addEventListener('click', () => open(false));
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !full && !panel.hidden) open(false); });

    root.querySelector('[data-action="new"]').addEventListener('click', () => {
        conversationId = null;
        sessionStorage.removeItem(KEY);
        log.innerHTML = '';
        if (welcomeHtml) log.appendChild(welcomeHtml.cloneNode(true));
        chips.style.display = '';
        input.focus();
    });

    chips.querySelectorAll('.mq-ai__chip').forEach((c) => c.addEventListener('click', () => send(c.textContent)));

    form.addEventListener('submit', (e) => { e.preventDefault(); send(input.value); });
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); send(input.value); }
    });
    input.addEventListener('input', () => {
        input.style.height = 'auto';
        input.style.height = Math.min(input.scrollHeight, 110) + 'px';
    });

    if (full && log.querySelectorAll('.mq-ai__msg:not([data-welcome])').length) chips.style.display = 'none';
    restore();
    scrollDown();
})();
</script>

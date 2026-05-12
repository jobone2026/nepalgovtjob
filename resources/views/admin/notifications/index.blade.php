@extends('layouts.admin')

@section('title', 'Send Notification')

@section('content')
<style>
:root{--white:#fff;--off:#f9fafb;--surface:#f3f4f6;--border:#e5e7eb;--t1:#111827;--t2:#6b7280;--t3:#9ca3af;--blue:#2563eb;--blue-h:#1d4ed8;--blue-l:#eff6ff;--blue-b:#bfdbfe;--green:#059669;--green-l:#ecfdf5;--green-b:#a7f3d0;--amber:#d97706;--amber-l:#fffbeb;--amber-b:#fcd34d;--red:#dc2626;--red-l:#fef2f2;--purple:#7c3aed;--purple-l:#f5f3ff;--r:10px;--rs:6px;--sh0:0 1px 3px rgba(0,0,0,.05);--sh1:0 4px 14px rgba(0,0,0,.07);}
.sn-wrap{max-width:760px;}
/* Header */
.sn-header{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:18px 22px;margin-bottom:20px;display:flex;align-items:center;gap:14px;box-shadow:var(--sh0);}
.sn-h-icon{width:46px;height:46px;background:var(--purple-l);border-radius:var(--rs);display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;}
.sn-h-title{font-size:19px;font-weight:800;color:var(--t1);}
.sn-h-sub{font-size:12.5px;color:var(--t3);margin-top:1px;}
/* Channel picker */
.sn-channels{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:20px;}
@media(max-width:540px){.sn-channels{grid-template-columns:1fr;}}
.sn-channel-card{background:var(--white);border:2px solid var(--border);border-radius:var(--r);padding:16px 14px;cursor:pointer;transition:all .15s;position:relative;user-select:none;}
.sn-channel-card input[type="radio"]{display:none;}
.sn-channel-card.checked{border-color:var(--blue);background:var(--blue-l);}
.sn-channel-card.checked .sn-ch-radio{background:var(--blue);border-color:var(--blue);}
.sn-channel-card.checked .sn-ch-radio::after{opacity:1;}
.sn-ch-top{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:10px;}
.sn-ch-icon{width:38px;height:38px;border-radius:var(--rs);display:flex;align-items:center;justify-content:center;font-size:18px;}
.sn-ch-icon.telegram{background:#e8f4fd;color:#229ed9;}
.sn-ch-icon.android{background:var(--green-l);color:var(--green);}
.sn-ch-icon.whatsapp{background:#f0fdf4;color:#25d366;}
.sn-ch-radio{width:18px;height:18px;border:2px solid var(--border);border-radius:50%;transition:all .15s;position:relative;flex-shrink:0;}
.sn-ch-radio::after{content:'';position:absolute;width:8px;height:8px;background:#fff;border-radius:50%;top:50%;left:50%;transform:translate(-50%,-50%);opacity:0;transition:opacity .15s;}
.sn-ch-name{font-size:14px;font-weight:700;color:var(--t1);margin-bottom:3px;}
.sn-ch-status{display:inline-flex;align-items:center;gap:4px;font-size:11.5px;font-weight:600;padding:2px 8px;border-radius:20px;}
.sn-ch-status.ok{background:var(--green-l);color:var(--green);}
.sn-ch-status.err{background:var(--red-l);color:var(--red);}
.sn-ch-status.warn{background:var(--amber-l);color:var(--amber);}
.sn-ch-status i{font-size:9px;}
/* Form card */
.sn-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh0);overflow:hidden;}
.sn-card-head{padding:14px 20px;border-bottom:1px solid var(--border);background:var(--off);display:flex;align-items:center;gap:10px;}
.sn-card-icon{width:30px;height:30px;background:var(--purple-l);border-radius:var(--rs);display:flex;align-items:center;justify-content:center;}
.sn-card-icon i{color:var(--purple);font-size:13px;}
.sn-card-title{font-size:14px;font-weight:700;color:var(--t1);}
.sn-card-body{padding:22px;}
.sn-field{display:flex;flex-direction:column;gap:5px;margin-bottom:16px;}
.sn-field:last-child{margin-bottom:0;}
.sn-label{font-size:13px;font-weight:600;color:var(--t1);display:flex;align-items:center;gap:5px;justify-content:space-between;}
.sn-label-left{display:flex;align-items:center;gap:5px;}
.required{color:var(--red);}
.sn-hint{font-size:11.5px;color:var(--t3);}
.sn-char-count{font-size:11.5px;color:var(--t3);font-weight:600;}
.sn-char-count.warn{color:var(--amber);}
.sn-char-count.over{color:var(--red);}
.sn-input,.sn-textarea{padding:9px 12px;background:var(--off);border:1px solid var(--border);border-radius:var(--rs);font-size:13.5px;font-family:inherit;color:var(--t1);outline:none;transition:all .15s;width:100%;}
.sn-input:focus,.sn-textarea:focus{background:var(--white);border-color:var(--blue-b);box-shadow:0 0 0 3px rgba(37,99,235,.08);}
.sn-textarea{resize:vertical;}
/* Preview card */
.sn-preview{background:var(--white);border:1px solid var(--border);border-radius:var(--r);margin-bottom:16px;box-shadow:var(--sh0);overflow:hidden;}
.sn-preview-head{padding:12px 16px;background:var(--off);border-bottom:1px solid var(--border);font-size:12px;font-weight:700;color:var(--t3);text-transform:uppercase;letter-spacing:.6px;}
.sn-preview-phone{background:var(--surface);padding:16px;display:flex;justify-content:center;}
.sn-notif-bubble{background:#fff;border-radius:10px;padding:12px 14px;max-width:280px;width:100%;box-shadow:0 2px 10px rgba(0,0,0,.1);border-left:3px solid var(--blue);}
.sn-notif-app{font-size:10px;font-weight:700;color:var(--blue);text-transform:uppercase;letter-spacing:.5px;margin-bottom:5px;}
.sn-notif-title{font-size:13px;font-weight:700;color:var(--t1);margin-bottom:3px;min-height:16px;}
.sn-notif-msg{font-size:12px;color:var(--t2);line-height:1.5;min-height:14px;}
/* Recipients */
.sn-recipients{display:flex;flex-wrap:wrap;gap:8px;}
.sn-recipient-check{display:flex;align-items:center;gap:8px;padding:8px 14px;border:1px solid var(--border);border-radius:var(--rs);cursor:pointer;transition:all .15s;background:var(--off);}
.sn-recipient-check:has(input:checked){background:var(--blue-l);border-color:var(--blue-b);}
.sn-recipient-check input{accent-color:var(--blue);width:14px;height:14px;}
.sn-recipient-check span{font-size:13px;font-weight:600;color:var(--t2);}
.sn-recipient-check:has(input:checked) span{color:var(--blue);}
/* Actions */
.sn-actions{display:flex;align-items:center;gap:10px;padding:18px 22px;background:var(--off);border-top:1px solid var(--border);}
.sn-btn{display:inline-flex;align-items:center;gap:7px;padding:10px 20px;border-radius:var(--rs);font-size:13.5px;font-weight:600;cursor:pointer;border:none;transition:all .15s;font-family:inherit;}
.sn-btn-primary{background:var(--blue);color:#fff;box-shadow:0 1px 5px rgba(37,99,235,.3);}
.sn-btn-primary:hover{background:var(--blue-h);transform:translateY(-1px);}
.sn-btn-outline{background:var(--white);color:var(--t2);border:1px solid var(--border);}
.sn-btn-outline:hover{background:var(--surface);color:var(--t1);}
/* Success state */
.sn-success{display:none;text-align:center;padding:36px 24px;}
.sn-success-icon{width:60px;height:60px;background:var(--green-l);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;}
.sn-success-icon i{color:var(--green);font-size:26px;}
.sn-success-title{font-size:18px;font-weight:800;color:var(--t1);margin-bottom:6px;}
.sn-success-sub{font-size:13.5px;color:var(--t2);}
</style>

<div class="sn-wrap">
{{-- Header --}}
<div class="sn-header">
<div class="sn-h-icon">📢</div>
<div>
<div class="sn-h-title">Send Notification</div>
<div class="sn-h-sub">Push custom notifications to users via Telegram, Android App, or WhatsApp</div>
</div>
</div>

{{-- Channel picker --}}
<div class="sn-channels" x-data="{ channel: 'android' }">
<label class="sn-channel-card" :class="{ checked: channel === 'telegram' }" @click="channel = 'telegram'">
<input type="radio" name="channel_display" value="telegram">
<div class="sn-ch-top">
<div class="sn-ch-icon telegram"><i class="fab fa-telegram"></i></div>
<div class="sn-ch-radio"></div>
</div>
<div class="sn-ch-name">Telegram</div>
<span class="sn-ch-status {{ env('TELEGRAM_BOT_TOKEN') && env('TELEGRAM_CHANNEL_ID') ? 'ok' : 'err' }}">
<i class="fas {{ env('TELEGRAM_BOT_TOKEN') && env('TELEGRAM_CHANNEL_ID') ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
{{ env('TELEGRAM_BOT_TOKEN') && env('TELEGRAM_CHANNEL_ID') ? 'Configured' : 'Not configured' }}
</span>
</label>

<label class="sn-channel-card" :class="{ checked: channel === 'android' }" @click="channel = 'android'">
<input type="radio" name="channel_display" value="android">
<div class="sn-ch-top">
<div class="sn-ch-icon android"><i class="fab fa-android"></i></div>
<div class="sn-ch-radio"></div>
</div>
<div class="sn-ch-name">Android Push</div>
<span class="sn-ch-status {{ env('FIREBASE_CREDENTIALS') && file_exists(base_path(env('FIREBASE_CREDENTIALS'))) ? 'ok' : 'err' }}">
<i class="fas {{ env('FIREBASE_CREDENTIALS') && file_exists(base_path(env('FIREBASE_CREDENTIALS'))) ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
{{ env('FIREBASE_CREDENTIALS') && file_exists(base_path(env('FIREBASE_CREDENTIALS'))) ? 'Configured' : 'Not configured' }}
</span>
</label>

<label class="sn-channel-card" :class="{ checked: channel === 'whatsapp' }" @click="channel = 'whatsapp'">
<input type="radio" name="channel_display" value="whatsapp">
<div class="sn-ch-top">
<div class="sn-ch-icon whatsapp"><i class="fab fa-whatsapp"></i></div>
<div class="sn-ch-radio"></div>
</div>
<div class="sn-ch-name">WhatsApp</div>
<span class="sn-ch-status warn">
<i class="fas fa-exclamation-circle"></i> Optional
</span>
</label>
</div>

{{-- Preview --}}
<div class="sn-preview">
<div class="sn-preview-head">Live Preview</div>
<div class="sn-preview-phone">
<div class="sn-notif-bubble">
<div class="sn-notif-app">JobOne.in</div>
<div class="sn-notif-title" id="prev-title">Notification title…</div>
<div class="sn-notif-msg" id="prev-msg">Your message will appear here…</div>
</div>
</div>
</div>

{{-- Form --}}
<form action="{{ route('admin.notifications.send') }}" method="POST" id="notifForm">
@csrf
<div class="sn-card">
<div class="sn-card-head">
<div class="sn-card-icon"><i class="fas fa-pen"></i></div>
<div class="sn-card-title">Compose Notification</div>
</div>
<div class="sn-card-body">
<div class="sn-field">
<div class="sn-label">
<div class="sn-label-left">Notification Title <span class="required">*</span></div>
</div>
<input type="text" name="title" id="notifTitle" class="sn-input" placeholder="e.g., New Job Alert! 🎉" maxlength="100" oninput="updatePreview()" required>
</div>

<div class="sn-field">
<div class="sn-label">
<div class="sn-label-left">Message <span class="required">*</span></div>
<span class="sn-char-count" id="msgCounter">0 / 500</span>
</div>
<textarea name="message" id="notifMsg" class="sn-textarea" rows="4" placeholder="Enter your notification message…" maxlength="500" oninput="updateMsgCounter(); updatePreview()" required></textarea>
<span class="sn-hint">Maximum 500 characters</span>
</div>

<div class="sn-field">
<div class="sn-label">
<div class="sn-label-left">Link URL <span style="font-size:11px;color:var(--t3);font-weight:400;">(optional)</span></div>
</div>
<input type="url" name="url" class="sn-input" placeholder="https://jobone.in/job/example">
<span class="sn-hint">Users will be redirected here when they tap the notification.</span>
</div>

<div class="sn-field">
<div class="sn-label">
<div class="sn-label-left">Send To <span class="required">*</span></div>
</div>
<div class="sn-recipients">
<label class="sn-recipient-check">
<input type="checkbox" name="channels[]" value="firebase" checked>
<span>📱 Android App (Firebase)</span>
</label>
<label class="sn-recipient-check">
<input type="checkbox" name="channels[]" value="telegram">
<span>✈️ Telegram Channel</span>
</label>
<label class="sn-recipient-check">
<input type="checkbox" name="channels[]" value="whatsapp">
<span>💬 WhatsApp</span>
</label>
</div>
</div>
</div>

<div class="sn-actions">
<button type="submit" class="sn-btn sn-btn-primary">
<i class="fas fa-paper-plane"></i> Send Notification
</button>
<a href="{{ route('admin.dashboard') }}" class="sn-btn sn-btn-outline">
<i class="fas fa-times"></i> Cancel
</a>
</div>
</div>
</form>
</div>

<script>
function updatePreview() {
const t = document.getElementById('notifTitle').value;
const m = document.getElementById('notifMsg').value;
document.getElementById('prev-title').textContent = t || 'Notification title…';
document.getElementById('prev-msg').textContent   = m || 'Your message will appear here…';
}

function updateMsgCounter() {
const el = document.getElementById('notifMsg');
const c  = document.getElementById('msgCounter');
const n  = el.value.length;
c.textContent = n + ' / 500';
c.className = 'sn-char-count' + (n > 450 ? (n >= 500 ? ' over' : ' warn') : '');
}
</script>

@endsection

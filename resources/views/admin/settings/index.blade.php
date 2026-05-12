@extends('layouts.admin')

@section('title', 'Site Settings')

@section('content')
<style>
:root{--white:#fff;--off:#f9fafb;--surface:#f3f4f6;--border:#e5e7eb;--t1:#111827;--t2:#6b7280;--t3:#9ca3af;--blue:#2563eb;--blue-h:#1d4ed8;--blue-l:#eff6ff;--blue-b:#bfdbfe;--green:#059669;--green-l:#ecfdf5;--amber:#d97706;--amber-l:#fffbeb;--red:#dc2626;--red-l:#fef2f2;--r:10px;--rs:6px;--sh0:0 1px 3px rgba(0,0,0,.05);--sh1:0 4px 14px rgba(0,0,0,.07);}
.ss-wrap{max-width:860px;}
/* Page header */
.ss-header{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:18px 22px;margin-bottom:20px;display:flex;align-items:center;gap:13px;box-shadow:var(--sh0);}
.ss-h-icon{width:42px;height:42px;background:var(--blue-l);border-radius:var(--rs);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.ss-h-icon i{color:var(--blue);font-size:18px;}
.ss-h-title{font-size:19px;font-weight:800;color:var(--t1);letter-spacing:-.3px;}
.ss-h-sub{font-size:12.5px;color:var(--t3);margin-top:1px;}
/* Section card */
.ss-section{background:var(--white);border:1px solid var(--border);border-radius:var(--r);overflow:hidden;margin-bottom:18px;box-shadow:var(--sh0);}
.ss-section-head{padding:14px 20px;border-bottom:1px solid var(--border);background:var(--off);display:flex;align-items:center;gap:10px;}
.ss-section-icon{width:30px;height:30px;border-radius:var(--rs);display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0;}
.ss-section-icon.blue{background:var(--blue-l);color:var(--blue);}
.ss-section-icon.green{background:var(--green-l);color:var(--green);}
.ss-section-icon.amber{background:var(--amber-l);color:var(--amber);}
.ss-section-icon.purple{background:#f5f3ff;color:#7c3aed;}
.ss-section-icon.pink{background:#fdf2f8;color:#be185d;}
.ss-section-title{font-size:14px;font-weight:700;color:var(--t1);}
.ss-section-sub{font-size:12px;color:var(--t3);margin-top:1px;}
.ss-section-body{padding:20px;}
/* Form grid */
.ss-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
.ss-grid.full{grid-template-columns:1fr;}
.ss-grid.three{grid-template-columns:1fr 1fr 1fr;}
@media(max-width:640px){.ss-grid,.ss-grid.three{grid-template-columns:1fr;}.ss-grid-span{grid-column:span 1;}}
.ss-field{display:flex;flex-direction:column;gap:5px;}
.ss-field.span2{grid-column:span 2;}
@media(max-width:640px){.ss-field.span2{grid-column:span 1;}}
.ss-label{font-size:13px;font-weight:600;color:var(--t1);display:flex;align-items:center;gap:5px;}
.ss-label .required{color:var(--red);font-size:14px;line-height:1;}
.ss-hint{font-size:11.5px;color:var(--t3);margin-top:1px;}
.ss-input,.ss-textarea,.ss-select{padding:9px 12px;background:var(--off);border:1px solid var(--border);border-radius:var(--rs);font-size:13.5px;font-family:inherit;color:var(--t1);outline:none;transition:all .15s;width:100%;}
.ss-input:focus,.ss-textarea:focus,.ss-select:focus{background:var(--white);border-color:var(--blue-b);box-shadow:0 0 0 3px rgba(37,99,235,.08);}
.ss-textarea{resize:vertical;min-height:90px;line-height:1.6;}
.ss-input-icon-wrap{position:relative;}
.ss-input-icon-wrap .ss-ico{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--t3);font-size:13px;pointer-events:none;}
.ss-input-icon-wrap .ss-input{padding-left:34px;}
/* Switch / toggle */
.ss-toggle-row{display:flex;align-items:center;justify-content:space-between;padding:13px 0;border-bottom:1px solid var(--border);}
.ss-toggle-row:last-child{border-bottom:none;padding-bottom:0;}
.ss-toggle-row:first-child{padding-top:0;}
.ss-toggle-info{}
.ss-toggle-label{font-size:13.5px;font-weight:600;color:var(--t1);}
.ss-toggle-desc{font-size:12px;color:var(--t3);margin-top:2px;}
.ss-switch{position:relative;display:inline-block;width:40px;height:22px;flex-shrink:0;}
.ss-switch input{opacity:0;width:0;height:0;}
.ss-slider{position:absolute;cursor:pointer;inset:0;background:#d1d5db;border-radius:22px;transition:.2s;}
.ss-slider::before{content:'';position:absolute;height:16px;width:16px;left:3px;bottom:3px;background:var(--white);border-radius:50%;transition:.2s;}
.ss-switch input:checked + .ss-slider{background:var(--blue);}
.ss-switch input:checked + .ss-slider::before{transform:translateX(18px);}
/* Color picker field */
.ss-color-wrap{display:flex;align-items:center;gap:8px;}
.ss-color-input{width:40px;height:36px;padding:2px 4px;border:1px solid var(--border);border-radius:var(--rs);cursor:pointer;background:var(--off);}
.ss-color-text{flex:1;}
/* File upload */
.ss-file-wrap{border:2px dashed var(--border);border-radius:var(--rs);padding:20px;text-align:center;cursor:pointer;transition:all .15s;background:var(--off);}
.ss-file-wrap:hover{border-color:var(--blue-b);background:var(--blue-l);}
.ss-file-wrap i{font-size:24px;color:var(--t3);margin-bottom:8px;display:block;}
.ss-file-wrap p{font-size:13px;color:var(--t2);margin-bottom:4px;}
.ss-file-wrap span{font-size:11.5px;color:var(--t3);}
.ss-file-input{display:none;}
/* Char counter */
.ss-char-wrap{position:relative;}
.ss-char-counter{position:absolute;right:10px;bottom:8px;font-size:11px;color:var(--t3);pointer-events:none;}
.ss-char-counter.warn{color:var(--amber);}
.ss-char-counter.over{color:var(--red);}
/* Save bar */
.ss-save-bar{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:16px 22px;display:flex;align-items:center;justify-content:space-between;gap:12px;box-shadow:var(--sh0);position:sticky;bottom:16px;z-index:20;flex-wrap:wrap;}
.ss-save-info{font-size:13px;color:var(--t3);display:flex;align-items:center;gap:6px;}
.ss-save-info i{font-size:12px;}
.ss-save-btns{display:flex;align-items:center;gap:8px;}
.ss-btn{display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:var(--rs);font-size:13.5px;font-weight:600;text-decoration:none;cursor:pointer;border:none;transition:all .15s;font-family:inherit;white-space:nowrap;}
.ss-btn-primary{background:var(--blue);color:#fff;box-shadow:0 1px 5px rgba(37,99,235,.3);}
.ss-btn-primary:hover{background:var(--blue-h);transform:translateY(-1px);}
.ss-btn-outline{background:var(--white);color:var(--t2);border:1px solid var(--border);}
.ss-btn-outline:hover{background:var(--off);color:var(--t1);}
</style>

<div class="ss-wrap">
{{-- Header --}}
<div class="ss-header">
<div class="ss-h-icon"><i class="fas fa-cog"></i></div>
<div>
<div class="ss-h-title">Site Settings</div>
<div class="ss-h-sub">Configure your site's core information and preferences</div>
</div>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
@csrf

{{-- General Info --}}
<div class="ss-section">
<div class="ss-section-head">
<div class="ss-section-icon blue"><i class="fas fa-globe"></i></div>
<div>
<div class="ss-section-title">General Information</div>
<div class="ss-section-sub">Basic site identity and contact details</div>
</div>
</div>
<div class="ss-section-body">
<div class="ss-grid">
<div class="ss-field">
<label class="ss-label">Site Name <span class="required">*</span></label>
<div class="ss-input-icon-wrap">
<i class="fas fa-briefcase ss-ico"></i>
<input type="text" name="site_name" class="ss-input" value="{{ old('site_name', $settings['site_name'] ?? '') }}" placeholder="JobOne.in">
</div>
</div>
<div class="ss-field">
<label class="ss-label">Contact Email <span class="required">*</span></label>
<div class="ss-input-icon-wrap">
<i class="fas fa-envelope ss-ico"></i>
<input type="email" name="contact_email" class="ss-input" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" placeholder="admin@example.com">
</div>
</div>
<div class="ss-field">
<label class="ss-label">Phone</label>
<div class="ss-input-icon-wrap">
<i class="fas fa-phone ss-ico"></i>
<input type="text" name="phone" class="ss-input" value="{{ old('phone', $settings['phone'] ?? '') }}" placeholder="+91 00000 00000">
</div>
</div>
<div class="ss-field">
<label class="ss-label">WhatsApp Number</label>
<div class="ss-input-icon-wrap">
<i class="fab fa-whatsapp ss-ico"></i>
<input type="text" name="whatsapp_number" class="ss-input" value="{{ old('whatsapp_number', $settings['whatsapp_number'] ?? '') }}" placeholder="+91 00000 00000">
</div>
</div>
<div class="ss-field span2">
<label class="ss-label">Address</label>
<textarea name="address" class="ss-textarea" rows="2" placeholder="City, State, Country">{{ old('address', $settings['address'] ?? '') }}</textarea>
</div>
<div class="ss-field span2">
<label class="ss-label">Site Description <span class="required">*</span></label>
<div class="ss-char-wrap">
<textarea name="site_description" class="ss-textarea" rows="3" placeholder="Describe your site in 1–2 sentences…" maxlength="300" id="siteDesc" oninput="updateCounter('siteDesc','siteDescCounter',300)">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
<span class="ss-char-counter" id="siteDescCounter">0/300</span>
</div>
<span class="ss-hint">Shown in search engine results and social sharing.</span>
</div>
</div>
</div>
</div>

{{-- Analytics & Ads --}}
<div class="ss-section">
<div class="ss-section-head">
<div class="ss-section-icon amber"><i class="fas fa-chart-line"></i></div>
<div>
<div class="ss-section-title">Analytics &amp; Advertising</div>
<div class="ss-section-sub">Tracking and monetization integrations</div>
</div>
</div>
<div class="ss-section-body">
<div class="ss-grid">
<div class="ss-field">
<label class="ss-label">Google Analytics ID</label>
<div class="ss-input-icon-wrap">
<i class="fab fa-google ss-ico"></i>
<input type="text" name="ga_tracking_id" class="ss-input" value="{{ old('ga_tracking_id', $settings['ga_tracking_id'] ?? '') }}" placeholder="G-XXXXXXXXXX">
</div>
<span class="ss-hint">Google Analytics 4 Measurement ID</span>
</div>
<div class="ss-field">
<label class="ss-label">AdSense Publisher ID</label>
<div class="ss-input-icon-wrap">
<i class="fas fa-ad ss-ico"></i>
<input type="text" name="adsense_publisher_id" class="ss-input" value="{{ old('adsense_publisher_id', $settings['adsense_publisher_id'] ?? '') }}" placeholder="ca-pub-xxxxxxxxxxxxxxxx">
</div>
<span class="ss-hint">Google AdSense publisher ID</span>
</div>
<div class="ss-field span2">
<label class="ss-label">Custom Header Scripts</label>
<textarea name="header_scripts" class="ss-textarea" rows="3" placeholder="&lt;!-- Paste custom HTML/JS/Meta tags here --&gt;" style="font-family:monospace;font-size:12.5px;">{{ old('header_scripts', $settings['header_scripts'] ?? '') }}</textarea>
<span class="ss-hint">Inserted in &lt;head&gt; on every page.</span>
</div>
</div>
</div>
</div>

{{-- Social Media --}}
<div class="ss-section">
<div class="ss-section-head">
<div class="ss-section-icon purple"><i class="fas fa-share-alt"></i></div>
<div>
<div class="ss-section-title">Social Media Links</div>
<div class="ss-section-sub">Your social channels shown in footer</div>
</div>
</div>
<div class="ss-section-body">
<div class="ss-grid">
<div class="ss-field">
<label class="ss-label"><i class="fab fa-facebook" style="color:#1877f2;"></i> Facebook URL</label>
<input type="url" name="facebook_url" class="ss-input" value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}" placeholder="https://facebook.com/yourpage">
</div>
<div class="ss-field">
<label class="ss-label"><i class="fab fa-twitter" style="color:#1da1f2;"></i> Twitter URL</label>
<input type="url" name="twitter_url" class="ss-input" value="{{ old('twitter_url', $settings['twitter_url'] ?? '') }}" placeholder="https://twitter.com/yourhandle">
</div>
<div class="ss-field">
<label class="ss-label"><i class="fab fa-telegram" style="color:#229ed9;"></i> Telegram URL</label>
<input type="url" name="telegram_url" class="ss-input" value="{{ old('telegram_url', $settings['telegram_url'] ?? '') }}" placeholder="https://t.me/yourchannel">
</div>
<div class="ss-field">
<label class="ss-label"><i class="fab fa-youtube" style="color:#ff0000;"></i> YouTube URL</label>
<input type="url" name="youtube_url" class="ss-input" value="{{ old('youtube_url', $settings['youtube_url'] ?? '') }}" placeholder="https://youtube.com/@yourchannel">
</div>
<div class="ss-field">
<label class="ss-label"><i class="fab fa-instagram" style="color:#e1306c;"></i> Instagram URL</label>
<input type="url" name="instagram_url" class="ss-input" value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}" placeholder="https://instagram.com/yourhandle">
</div>
<div class="ss-field">
<label class="ss-label"><i class="fab fa-whatsapp" style="color:#25d366;"></i> WhatsApp Channel</label>
<input type="url" name="whatsapp_channel" class="ss-input" value="{{ old('whatsapp_channel', $settings['whatsapp_channel'] ?? '') }}" placeholder="https://whatsapp.com/channel/…">
</div>
<div class="ss-field">
<label class="ss-label"><i class="fab fa-android" style="color:#3ddc84;"></i> Android App URL</label>
<input type="url" name="android_app_url" class="ss-input" value="{{ old('android_app_url', $settings['android_app_url'] ?? '') }}" placeholder="https://play.google.com/store/apps/details?id=…">
<span class="ss-hint">Google Play Store link for your Android app</span>
</div>
</div>
</div>
</div>

{{-- SEO --}}
<div class="ss-section">
<div class="ss-section-head">
<div class="ss-section-icon green"><i class="fas fa-search"></i></div>
<div>
<div class="ss-section-title">SEO Settings</div>
<div class="ss-section-sub">Default meta tags for search engines</div>
</div>
</div>
<div class="ss-section-body">
<div class="ss-grid full">
<div class="ss-field">
<label class="ss-label">Default Meta Title</label>
<div class="ss-char-wrap">
<input type="text" name="meta_title" class="ss-input" id="metaTitle" maxlength="60" value="{{ old('meta_title', $settings['meta_title'] ?? 'JobOne.in – Latest Government Jobs & Admit Cards') }}" placeholder="JobOne.in – Latest Government Jobs 2026" oninput="updateCounter('metaTitle','metaTitleCounter',60)">
<span class="ss-char-counter" id="metaTitleCounter">0/60</span>
</div>
<span class="ss-hint">Recommended: 50–60 characters</span>
</div>
<div class="ss-field">
<label class="ss-label">Default Meta Description</label>
<div class="ss-char-wrap">
<textarea name="meta_description" class="ss-textarea" rows="3" id="metaDesc" maxlength="160" placeholder="Your default meta description…" oninput="updateCounter('metaDesc','metaDescCounter',160)">{{ old('meta_description', $settings['meta_description'] ?? 'Find latest government job notifications, admit cards, results, answer keys, and syllabus for SSC, UPSC, Railways, Banking, and State PSC exams.') }}</textarea>
<span class="ss-char-counter" id="metaDescCounter">0/160</span>
</div>
<span class="ss-hint">Recommended: 120–160 characters</span>
</div>
<div class="ss-field">
<label class="ss-label">Default Meta Keywords</label>
<input type="text" name="meta_keywords" class="ss-input" value="{{ old('meta_keywords', $settings['meta_keywords'] ?? 'government jobs, sarkari naukri, admit card, result, answer key, syllabus, SSC, UPSC, Railways, Banking, State PSC') }}" placeholder="government jobs, sarkari naukri, admit card, result…">
<span class="ss-hint">Comma-separated keywords</span>
</div>
</div>
</div>
</div>

{{-- Sticky save bar --}}
<div class="ss-save-bar">
<div class="ss-save-info">
<i class="fas fa-info-circle" style="color:var(--blue);"></i>
Changes will be applied site-wide immediately after saving.
</div>
<div class="ss-save-btns">
<a href="{{ route('admin.dashboard') }}" class="ss-btn ss-btn-outline">
<i class="fas fa-times"></i> Discard
</a>
<button type="submit" class="ss-btn ss-btn-primary">
<i class="fas fa-save"></i> Save Settings
</button>
</div>
</div>

</form>
</div>

<script>
function updateCounter(inputId, counterId, max) {
const el = document.getElementById(inputId);
const counter = document.getElementById(counterId);
if (!el || !counter) return;
const len = el.tagName === 'TEXTAREA' ? el.value.length : el.value.length;
counter.textContent = len + '/' + max;
counter.className = 'ss-char-counter' + (len > max * .9 ? (len >= max ? ' over' : ' warn') : '');
}
// Init counters on load
document.querySelectorAll('[id$="Counter"]').forEach(c => {
const id = c.id.replace('Counter','');
const el = document.getElementById(id);
if (el) updateCounter(id, c.id, parseInt(c.textContent.split('/')[1]));
});
</script>

@endsection

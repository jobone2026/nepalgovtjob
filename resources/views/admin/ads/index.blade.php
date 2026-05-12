@extends('layouts.admin')

@section('title', 'Advertisements Management')

@section('content')
<style>
:root{--white:#fff;--off:#f9fafb;--surface:#f3f4f6;--border:#e5e7eb;--bsoft:#f0f1f4;--t1:#111827;--t2:#6b7280;--t3:#9ca3af;--blue:#2563eb;--blue-h:#1d4ed8;--blue-l:#eff6ff;--blue-b:#bfdbfe;--green:#059669;--green-l:#ecfdf5;--green-b:#a7f3d0;--amber:#d97706;--amber-l:#fffbeb;--red:#dc2626;--red-l:#fef2f2;--red-b:#fecaca;--purple:#7c3aed;--purple-l:#f5f3ff;--r:10px;--rs:6px;--sh0:0 1px 3px rgba(0,0,0,.05);--sh1:0 4px 14px rgba(0,0,0,.07);}
.ad-btn{display:inline-flex;align-items:center;gap:7px;padding:9px 16px;border-radius:var(--rs);font-size:13px;font-weight:600;text-decoration:none;cursor:pointer;border:none;transition:all .15s;font-family:inherit;white-space:nowrap;}
.ad-btn-primary{background:var(--blue);color:#fff;box-shadow:0 1px 5px rgba(37,99,235,.3);}
.ad-btn-primary:hover{background:var(--blue-h);transform:translateY(-1px);}
.ad-btn-outline{background:var(--white);color:var(--t2);border:1px solid var(--border);}
.ad-btn-outline:hover{background:var(--off);color:var(--t1);}
.ad-btn-sm{padding:6px 12px;font-size:12.5px;}
.ad-btn-danger{background:var(--red);color:#fff;}
.ad-btn-danger:hover{background:#b91c1c;}
/* Layout: form + list side by side on wide */
.ad-layout{display:grid;grid-template-columns:340px 1fr;gap:18px;align-items:start;}
@media(max-width:900px){.ad-layout{grid-template-columns:1fr;}}
/* Header */
.ad-header{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:18px 22px;margin-bottom:18px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;box-shadow:var(--sh0);}
.ad-h-left{display:flex;align-items:center;gap:13px;}
.ad-h-icon{width:42px;height:42px;background:var(--amber-l);border-radius:var(--rs);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.ad-h-icon i{color:var(--amber);font-size:18px;}
.ad-h-title{font-size:19px;font-weight:800;color:var(--t1);}
.ad-h-sub{font-size:12.5px;color:var(--t3);margin-top:1px;}
/* Form card */
.ad-form-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh0);overflow:hidden;position:sticky;top:80px;}
.ad-form-head{padding:14px 18px;border-bottom:1px solid var(--border);background:var(--off);display:flex;align-items:center;gap:10px;}
.ad-form-icon{width:28px;height:28px;background:var(--blue-l);border-radius:var(--rs);display:flex;align-items:center;justify-content:center;}
.ad-form-icon i{color:var(--blue);font-size:12px;}
.ad-form-title{font-size:13.5px;font-weight:700;color:var(--t1);}
.ad-form-body{padding:18px;}
.ad-field{display:flex;flex-direction:column;gap:5px;margin-bottom:14px;}
.ad-field:last-child{margin-bottom:0;}
.ad-label{font-size:13px;font-weight:600;color:var(--t1);}
.ad-label .req{color:var(--red);}
.ad-hint{font-size:11.5px;color:var(--t3);}
.ad-input,.ad-select,.ad-textarea{padding:9px 12px;background:var(--off);border:1px solid var(--border);border-radius:var(--rs);font-size:13.5px;font-family:inherit;color:var(--t1);outline:none;transition:all .15s;width:100%;}
.ad-input:focus,.ad-select:focus,.ad-textarea:focus{background:var(--white);border-color:var(--blue-b);box-shadow:0 0 0 3px rgba(37,99,235,.08);}
.ad-textarea{resize:vertical;min-height:100px;font-family:monospace;font-size:12.5px;}
.ad-toggle-row{display:flex;align-items:center;gap:10px;padding:10px 0;border-top:1px solid var(--border);margin-top:2px;}
.ad-switch{position:relative;display:inline-block;width:38px;height:20px;flex-shrink:0;}
.ad-switch input{opacity:0;width:0;height:0;}
.ad-slider{position:absolute;cursor:pointer;inset:0;background:#d1d5db;border-radius:20px;transition:.2s;}
.ad-slider::before{content:'';position:absolute;height:14px;width:14px;left:3px;bottom:3px;background:#fff;border-radius:50%;transition:.2s;}
.ad-switch input:checked + .ad-slider{background:var(--green);}
.ad-switch input:checked + .ad-slider::before{transform:translateX(18px);}
.ad-toggle-label{font-size:13px;font-weight:600;color:var(--t1);}
.ad-toggle-sub{font-size:11.5px;color:var(--t3);}
.ad-form-actions{padding:14px 18px;background:var(--off);border-top:1px solid var(--border);display:flex;gap:8px;}
/* List card */
.ad-list-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh0);overflow:hidden;}
.ad-list-head{padding:14px 18px;border-bottom:1px solid var(--border);background:var(--off);display:flex;align-items:center;justify-content:space-between;}
.ad-list-title{font-size:14px;font-weight:700;color:var(--t1);}
.ad-count-badge{background:var(--blue-l);color:var(--blue);font-size:12px;font-weight:700;padding:2px 9px;border-radius:20px;}
/* Ad item */
.ad-item{padding:16px 18px;border-bottom:1px solid var(--bsoft);transition:background .1s;}
.ad-item:last-child{border-bottom:none;}
.ad-item:hover{background:#fafbff;}
.ad-item-top{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:8px;}
.ad-item-info{flex:1;min-width:0;}
.ad-item-name{font-size:14px;font-weight:700;color:var(--t1);margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.ad-item-badges{display:flex;flex-wrap:wrap;gap:5px;}
.ad-badge{display:inline-flex;align-items:center;gap:3px;padding:3px 9px;border-radius:20px;font-size:11.5px;font-weight:600;}
.ad-badge.pos{background:var(--blue-l);color:var(--blue);}
.ad-badge.type{background:var(--purple-l);color:var(--purple);}
.ad-badge.active{background:var(--green-l);color:var(--green);}
.ad-badge.inactive{background:var(--surface);color:var(--t3);}
.ad-badge.active::before,.ad-badge.inactive::before{content:'';width:5px;height:5px;border-radius:50%;background:currentColor;}
.ad-item-actions{display:flex;align-items:center;gap:4px;flex-shrink:0;}
.ad-act{width:30px;height:30px;display:flex;align-items:center;justify-content:center;border-radius:var(--rs);border:1px solid var(--border);background:var(--white);cursor:pointer;text-decoration:none;transition:all .15s;font-size:12px;color:var(--t2);}
.ad-act.edit:hover{background:var(--blue-l);border-color:var(--blue-b);color:var(--blue);}
.ad-act.del:hover{background:var(--red-l);border-color:var(--red-b);color:var(--red);}
.ad-item-code{background:var(--off);border:1px solid var(--border);border-radius:var(--rs);padding:8px 10px;font-family:monospace;font-size:11.5px;color:var(--t2);max-height:56px;overflow:hidden;line-height:1.5;position:relative;}
.ad-item-code::after{content:'';position:absolute;bottom:0;left:0;right:0;height:20px;background:linear-gradient(transparent,var(--off));}
/* Empty state */
.ad-empty{padding:48px 24px;text-align:center;}
.ad-empty-icon{width:56px;height:56px;background:var(--surface);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;}
.ad-empty-icon i{color:var(--t3);font-size:22px;}
.ad-empty p{font-size:13.5px;color:var(--t3);}
/* Delete modal */
.ad-modal-bg{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:900;backdrop-filter:blur(3px);align-items:center;justify-content:center;padding:20px;}
.ad-modal-bg.open{display:flex;}
.ad-modal{background:var(--white);border-radius:var(--r);padding:28px 24px 22px;max-width:380px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,.15);text-align:center;}
.ad-modal-ico{width:50px;height:50px;background:var(--red-l);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;}
.ad-modal-ico i{color:var(--red);font-size:20px;}
.ad-modal-title{font-size:16px;font-weight:800;color:var(--t1);margin-bottom:7px;}
.ad-modal-sub{font-size:13px;color:var(--t2);margin-bottom:22px;line-height:1.6;}
.ad-modal-acts{display:flex;gap:10px;justify-content:center;}
</style>

{{-- Page header --}}
<div class="ad-header">
<div class="ad-h-left">
<div class="ad-h-icon"><i class="fas fa-ad"></i></div>
<div>
<div class="ad-h-title">Advertisements</div>
<div class="ad-h-sub">Manage ad slots and monetization across the site</div>
</div>
</div>
<button class="ad-btn ad-btn-primary" onclick="document.getElementById('adName').focus(); window.scrollTo({top:0,behavior:'smooth'})">
<i class="fas fa-plus"></i> New Ad
</button>
</div>

<div class="ad-layout" x-data="{ deleteId: null, showModal: false, editMode: false, editId: null, confirmDelete(id) { this.deleteId = id; this.showModal = true; } }">

{{-- ── ADD FORM ── --}}
<div class="ad-form-card">
<div class="ad-form-head">
<div class="ad-form-icon"><i class="fas fa-plus"></i></div>
<div class="ad-form-title" x-text="editMode ? 'Edit Advertisement' : 'Add Advertisement'"></div>
</div>

<form action="{{ route('admin.ads.store') }}" method="POST" id="adForm">
@csrf
<input type="hidden" name="_method" id="adMethod" value="POST">
<input type="hidden" name="ad_id" id="adId">

<div class="ad-form-body">
<div class="ad-field">
<label class="ad-label">Name <span class="req">*</span></label>
<input type="text" name="name" id="adName" class="ad-input" placeholder="e.g., Header Banner" required>
</div>

<div class="ad-field">
<label class="ad-label">Position <span class="req">*</span></label>
<select name="position" id="adPosition" class="ad-select" required>
<option value="">Select Position</option>
<option value="header">Header</option>
<option value="sidebar">Sidebar</option>
<option value="after_post">After Post</option>
<option value="footer">Footer</option>
</select>
</div>

<div class="ad-field">
<label class="ad-label">Type <span class="req">*</span></label>
<select name="type" id="adType" class="ad-select" required>
<option value="">Select Type</option>
<option value="adsense">Google AdSense</option>
<option value="custom">Custom Code</option>
</select>
</div>

<div class="ad-field">
<label class="ad-label">Ad Code / HTML</label>
<textarea name="code" id="adCode" class="ad-textarea" rows="5" placeholder="Paste your ad code here…"></textarea>
<span class="ad-hint">For AdSense, paste the full &lt;script&gt; tag.</span>
</div>

<div class="ad-toggle-row">
<label class="ad-switch">
<input type="checkbox" name="is_active" id="adActive" value="1" checked>
<span class="ad-slider"></span>
</label>
<div>
<div class="ad-toggle-label">Active</div>
<div class="ad-toggle-sub">Ad will display on the site</div>
</div>
</div>
</div>

<div class="ad-form-actions">
<button type="submit" class="ad-btn ad-btn-primary" style="flex:1;justify-content:center;">
<i class="fas fa-save"></i>
<span x-text="editMode ? 'Update Ad' : 'Create Ad'"></span>
</button>
<button type="button" class="ad-btn ad-btn-outline" @click="resetForm()">
<i class="fas fa-times"></i> Cancel
</button>
</div>
</form>
</div>

{{-- ── LIST ── --}}
<div class="ad-list-card">
<div class="ad-list-head">
<div class="ad-list-title">All Advertisements</div>
<span class="ad-count-badge">{{ $ads->count() }} ads</span>
</div>

@forelse($ads as $ad)
<div class="ad-item">
<div class="ad-item-top">
<div class="ad-item-info">
<div class="ad-item-name">{{ $ad->name }}</div>
<div class="ad-item-badges">
<span class="ad-badge pos">
<i class="fas fa-map-pin"></i> {{ ucfirst(str_replace('_',' ',$ad->position)) }}
</span>
<span class="ad-badge type">{{ ucfirst(str_replace('_',' ',$ad->type)) }}</span>
@if($ad->is_active)
<span class="ad-badge active">Active</span>
@else
<span class="ad-badge inactive">Inactive</span>
@endif
</div>
</div>
<div class="ad-item-actions">
<button type="button" class="ad-act edit" title="Edit" @click="loadEdit({{ $ad->id }}, '{{ addslashes($ad->name) }}', '{{ $ad->position }}', '{{ $ad->type }}', {{ $ad->is_active ? 'true' : 'false' }})">
<i class="fas fa-pen"></i>
</button>
<button type="button" class="ad-act del" title="Delete" @click="confirmDelete({{ $ad->id }})">
<i class="fas fa-trash"></i>
</button>
</div>
</div>
@if($ad->code)
<div class="ad-item-code">{{ $ad->code }}</div>
@endif
</div>
@empty
<div class="ad-empty">
<div class="ad-empty-icon"><i class="fas fa-ad"></i></div>
<p>No advertisements yet. Create your first ad slot above.</p>
</div>
@endforelse
</div>

{{-- Delete Modal --}}
<div class="ad-modal-bg" :class="{ open: showModal }" @click.self="showModal=false">
<div class="ad-modal">
<div class="ad-modal-ico"><i class="fas fa-trash"></i></div>
<div class="ad-modal-title">Remove this ad?</div>
<div class="ad-modal-sub">The ad slot will be permanently deleted and will stop displaying on the site.</div>
<div class="ad-modal-acts">
<button class="ad-btn ad-btn-outline" @click="showModal=false">Cancel</button>
<form :action="`{{ url('admin/ads') }}/${deleteId}`" method="POST">
@csrf
@method('DELETE')
<button type="submit" class="ad-btn ad-btn-danger">
<i class="fas fa-trash"></i> Remove
</button>
</form>
</div>
</div>
</div>

</div>

<script>
function resetForm() {
document.getElementById('adForm').reset();
document.getElementById('adMethod').value = 'POST';
document.getElementById('adId').value = '';
document.getElementById('adActive').checked = true;
}

// Alpine method — attach to window for easy access from inline handlers
window.loadEdit = function(id, name, position, type, active) {
document.getElementById('adName').value     = name;
document.getElementById('adPosition').value = position;
document.getElementById('adType').value     = type;
document.getElementById('adActive').checked = active;
document.getElementById('adMethod').value   = 'PUT';
document.getElementById('adId').value       = id;
document.getElementById('adForm').action    = `/admin/ads/${id}`;
document.getElementById('adName').scrollIntoView({ behavior: 'smooth', block: 'center' });
document.getElementById('adName').focus();
};
</script>

@endsection

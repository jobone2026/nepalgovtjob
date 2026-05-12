@extends('layouts.admin')

@section('title', 'Authors Management')

@section('content')
<style>
:root{--white:#fff;--off:#f9fafb;--surface:#f3f4f6;--border:#e5e7eb;--bsoft:#f0f1f4;--t1:#111827;--t2:#6b7280;--t3:#9ca3af;--blue:#2563eb;--blue-h:#1d4ed8;--blue-l:#eff6ff;--blue-b:#bfdbfe;--green:#059669;--green-l:#ecfdf5;--green-b:#a7f3d0;--amber:#d97706;--amber-l:#fffbeb;--red:#dc2626;--red-l:#fef2f2;--red-b:#fecaca;--purple:#7c3aed;--purple-l:#f5f3ff;--r:10px;--rs:6px;--sh0:0 1px 3px rgba(0,0,0,.05);--sh1:0 4px 14px rgba(0,0,0,.07);}
.au-btn{display:inline-flex;align-items:center;gap:7px;padding:9px 16px;border-radius:var(--rs);font-size:13px;font-weight:600;text-decoration:none;cursor:pointer;border:none;transition:all .15s;font-family:inherit;}
.au-btn-primary{background:var(--blue);color:#fff;box-shadow:0 1px 5px rgba(37,99,235,.3);}
.au-btn-primary:hover{background:var(--blue-h);transform:translateY(-1px);}
.au-btn-outline{background:var(--white);color:var(--t2);border:1px solid var(--border);}
.au-btn-outline:hover{background:var(--off);}
.au-btn-danger{background:var(--red);color:#fff;}
.au-btn-danger:hover{background:#b91c1c;}
.au-btn-sm{padding:6px 12px;font-size:12.5px;}
/* Layout */
.au-layout{display:grid;grid-template-columns:340px 1fr;gap:18px;align-items:start;}
@media(max-width:900px){.au-layout{grid-template-columns:1fr;}}
/* Header */
.au-header{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:18px 22px;margin-bottom:18px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;box-shadow:var(--sh0);}
.au-h-left{display:flex;align-items:center;gap:13px;}
.au-h-icon{width:42px;height:42px;background:var(--purple-l);border-radius:var(--rs);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.au-h-icon i{color:var(--purple);font-size:18px;}
.au-h-title{font-size:19px;font-weight:800;color:var(--t1);}
.au-h-sub{font-size:12.5px;color:var(--t3);margin-top:1px;}
/* Form card */
.au-form-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh0);overflow:hidden;position:sticky;top:80px;}
.au-form-head{padding:14px 18px;border-bottom:1px solid var(--border);background:var(--off);display:flex;align-items:center;gap:10px;}
.au-form-icon{width:28px;height:28px;background:var(--purple-l);border-radius:var(--rs);display:flex;align-items:center;justify-content:center;}
.au-form-icon i{color:var(--purple);font-size:12px;}
.au-form-title{font-size:13.5px;font-weight:700;color:var(--t1);}
.au-form-body{padding:18px;}
.au-field{display:flex;flex-direction:column;gap:5px;margin-bottom:14px;}
.au-field:last-child{margin-bottom:0;}
.au-label{font-size:13px;font-weight:600;color:var(--t1);}
.au-label .req{color:var(--red);}
.au-hint{font-size:11.5px;color:var(--t3);}
.au-input,.au-textarea{padding:9px 12px;background:var(--off);border:1px solid var(--border);border-radius:var(--rs);font-size:13.5px;font-family:inherit;color:var(--t1);outline:none;transition:all .15s;width:100%;}
.au-input:focus,.au-textarea:focus{background:var(--white);border-color:var(--blue-b);box-shadow:0 0 0 3px rgba(37,99,235,.08);}
.au-textarea{resize:vertical;}
.au-pw-wrap{position:relative;}
.au-pw-wrap .au-input{padding-right:38px;}
.au-pw-toggle{position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--t3);font-size:14px;padding:0;}
.au-pw-toggle:hover{color:var(--t2);}
.au-grid2{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
@media(max-width:380px){.au-grid2{grid-template-columns:1fr;}}
.au-toggle-row{display:flex;align-items:center;gap:10px;padding:10px 0;border-top:1px solid var(--border);margin-top:2px;}
.au-switch{position:relative;display:inline-block;width:38px;height:20px;flex-shrink:0;}
.au-switch input{opacity:0;width:0;height:0;}
.au-slider{position:absolute;cursor:pointer;inset:0;background:#d1d5db;border-radius:20px;transition:.2s;}
.au-slider::before{content:'';position:absolute;height:14px;width:14px;left:3px;bottom:3px;background:#fff;border-radius:50%;transition:.2s;}
.au-switch input:checked + .au-slider{background:var(--green);}
.au-switch input:checked + .au-slider::before{transform:translateX(18px);}
.au-toggle-label{font-size:13px;font-weight:600;color:var(--t1);}
.au-toggle-sub{font-size:11.5px;color:var(--t3);}
.au-form-actions{padding:14px 18px;background:var(--off);border-top:1px solid var(--border);display:flex;gap:8px;}
/* Author list */
.au-list-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh0);overflow:hidden;}
.au-list-head{padding:14px 18px;border-bottom:1px solid var(--border);background:var(--off);display:flex;align-items:center;justify-content:space-between;}
.au-list-title{font-size:14px;font-weight:700;color:var(--t1);}
.au-count-badge{background:var(--purple-l);color:var(--purple);font-size:12px;font-weight:700;padding:2px 9px;border-radius:20px;}
/* Author card */
.au-author-item{padding:16px 18px;border-bottom:1px solid var(--bsoft);transition:background .1s;display:flex;align-items:flex-start;gap:14px;}
.au-author-item:last-child{border-bottom:none;}
.au-author-item:hover{background:#fafbff;}
.au-avatar{width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:800;color:#fff;flex-shrink:0;letter-spacing:-.5px;}
.au-author-info{flex:1;min-width:0;}
.au-author-name{font-size:14px;font-weight:700;color:var(--t1);margin-bottom:2px;}
.au-author-email{font-size:12.5px;color:var(--t3);margin-bottom:6px;display:flex;align-items:center;gap:4px;}
.au-author-email i{font-size:10px;}
.au-author-bio{font-size:12.5px;color:var(--t2);line-height:1.5;margin-bottom:8px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;}
.au-author-stats{display:flex;align-items:center;gap:8px;}
.au-stat-chip{display:flex;align-items:center;gap:4px;font-size:11.5px;color:var(--t2);background:var(--surface);padding:2px 8px;border-radius:20px;}
.au-stat-chip i{font-size:10px;color:var(--t3);}
.au-status-chip{display:flex;align-items:center;gap:4px;font-size:11.5px;font-weight:600;padding:2px 8px;border-radius:20px;}
.au-status-chip.active{background:var(--green-l);color:var(--green);}
.au-status-chip.inactive{background:var(--surface);color:var(--t3);}
.au-status-chip::before{content:'';width:5px;height:5px;border-radius:50%;background:currentColor;}
.au-author-acts{display:flex;flex-direction:column;gap:4px;flex-shrink:0;}
.au-act{width:30px;height:30px;display:flex;align-items:center;justify-content:center;border-radius:var(--rs);border:1px solid var(--border);background:var(--white);cursor:pointer;text-decoration:none;transition:all .15s;font-size:12px;color:var(--t2);}
.au-act.edit:hover{background:var(--blue-l);border-color:var(--blue-b);color:var(--blue);}
.au-act.del:hover{background:var(--red-l);border-color:var(--red-b);color:var(--red);}
/* Empty */
.au-empty{padding:48px 24px;text-align:center;}
.au-empty-icon{width:56px;height:56px;background:var(--surface);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;}
.au-empty-icon i{color:var(--t3);font-size:22px;}
.au-empty p{font-size:13.5px;color:var(--t3);}
/* Delete modal */
.au-modal-bg{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:900;backdrop-filter:blur(3px);align-items:center;justify-content:center;padding:20px;}
.au-modal-bg.open{display:flex;}
.au-modal{background:var(--white);border-radius:var(--r);padding:28px 24px 22px;max-width:380px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,.15);text-align:center;}
.au-modal-ico{width:50px;height:50px;background:var(--red-l);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;}
.au-modal-ico i{color:var(--red);font-size:20px;}
.au-modal-title{font-size:16px;font-weight:800;color:var(--t1);margin-bottom:7px;}
.au-modal-sub{font-size:13px;color:var(--t2);margin-bottom:22px;line-height:1.6;}
.au-modal-acts{display:flex;gap:10px;justify-content:center;}
</style>

{{-- Avatar color palette --}}
@php
$avatarColors = ['#2563eb','#7c3aed','#059669','#d97706','#be185d','#0891b2','#dc2626','#16a34a'];
function getInitials($name) {
$parts = explode(' ', trim($name));
return strtoupper(substr($parts[0],0,1) . (isset($parts[1]) ? substr($parts[1],0,1) : ''));
}
@endphp

{{-- Page Header --}}
<div class="au-header">
<div class="au-h-left">
<div class="au-h-icon"><i class="fas fa-users"></i></div>
<div>
<div class="au-h-title">Authors</div>
<div class="au-h-sub">Manage content writers and contributors</div>
</div>
</div>
<button class="au-btn au-btn-primary" onclick="document.getElementById('auName').focus()">
<i class="fas fa-plus"></i> New Author
</button>
</div>

<div class="au-layout" x-data="{ deleteId: null, showModal: false, editMode: false, confirmDelete(id) { this.deleteId = id; this.showModal = true; } }">

{{-- ── FORM ── --}}
<div class="au-form-card">
<div class="au-form-head">
<div class="au-form-icon"><i class="fas fa-user-plus"></i></div>
<div class="au-form-title" x-text="editMode ? 'Edit Author' : 'Add Author'"></div>
</div>

<form action="{{ route('admin.authors.store') }}" method="POST" id="auForm">
@csrf
<input type="hidden" name="_method" id="auMethod" value="POST">
<input type="hidden" name="author_id" id="auIdField">

<div class="au-form-body">
<div class="au-grid2">
<div class="au-field">
<label class="au-label">Name <span class="req">*</span></label>
<input type="text" name="name" id="auName" class="au-input" placeholder="Full name" required>
</div>
<div class="au-field">
<label class="au-label">Email <span class="req">*</span></label>
<input type="email" name="email" id="auEmail" class="au-input" placeholder="email@domain.com" required>
</div>
</div>

<div class="au-grid2">
<div class="au-field">
<label class="au-label">Password <span class="req" x-show="!editMode">*</span></label>
<div class="au-pw-wrap">
<input type="password" name="password" id="auPassword" class="au-input" placeholder="••••••••" :required="!editMode">
<button type="button" class="au-pw-toggle" onclick="togglePw('auPassword',this)">
<i class="fas fa-eye"></i>
</button>
</div>
</div>
<div class="au-field">
<label class="au-label">Confirm Password <span class="req" x-show="!editMode">*</span></label>
<div class="au-pw-wrap">
<input type="password" name="password_confirmation" id="auConfirmPw" class="au-input" placeholder="••••••••" :required="!editMode">
<button type="button" class="au-pw-toggle" onclick="togglePw('auConfirmPw',this)">
<i class="fas fa-eye"></i>
</button>
</div>
</div>
</div>

<div class="au-field">
<label class="au-label">Bio</label>
<textarea name="bio" id="auBio" class="au-textarea" rows="3" placeholder="Brief description of this author…"></textarea>
<span class="au-hint">Shown on author profile and post pages.</span>
</div>

<div class="au-toggle-row">
<label class="au-switch">
<input type="checkbox" name="is_active" id="auActive" value="1" checked>
<span class="au-slider"></span>
</label>
<div>
<div class="au-toggle-label">Active</div>
<div class="au-toggle-sub">Author can publish posts</div>
</div>
</div>
</div>

<div class="au-form-actions">
<button type="submit" class="au-btn au-btn-primary" style="flex:1;justify-content:center;">
<i class="fas fa-save"></i>
<span x-text="editMode ? 'Update Author' : 'Create Author'"></span>
</button>
<button type="button" class="au-btn au-btn-outline" @click="resetForm()">
<i class="fas fa-times"></i>
</button>
</div>
</form>
</div>

{{-- ── LIST ── --}}
<div class="au-list-card">
<div class="au-list-head">
<div class="au-list-title">All Authors</div>
<span class="au-count-badge">{{ $authors->count() }} authors</span>
</div>

@forelse($authors as $i => $author)
@php $avatarBg = $avatarColors[$i % count($avatarColors)]; @endphp
<div class="au-author-item">
<div class="au-avatar" style="background:{{ $avatarBg }}">
{{ getInitials($author->name) }}
</div>
<div class="au-author-info">
<div class="au-author-name">{{ $author->name }}</div>
<div class="au-author-email">
<i class="fas fa-envelope"></i> {{ $author->email }}
</div>
@if($author->bio)
<div class="au-author-bio">{{ $author->bio }}</div>
@endif
<div class="au-author-stats">
<span class="au-stat-chip">
<i class="fas fa-newspaper"></i> {{ $author->posts_count ?? 0 }} posts
</span>
@if($author->is_active)
<span class="au-status-chip active">Active</span>
@else
<span class="au-status-chip inactive">Inactive</span>
@endif
<span class="au-stat-chip">
<i class="fas fa-calendar"></i> {{ $author->created_at->format('M Y') }}
</span>
</div>
</div>
<div class="au-author-acts">
<button type="button" class="au-act edit" title="Edit" @click="loadEdit({{ $author->id }}, '{{ addslashes($author->name) }}', '{{ $author->email }}', '{{ addslashes($author->bio ?? '') }}', {{ $author->is_active ? 'true' : 'false' }})">
<i class="fas fa-pen"></i>
</button>
<button type="button" class="au-act del" title="Delete" @click="confirmDelete({{ $author->id }})">
<i class="fas fa-trash"></i>
</button>
</div>
</div>
@empty
<div class="au-empty">
<div class="au-empty-icon"><i class="fas fa-users"></i></div>
<p>No authors yet. Add your first author using the form.</p>
</div>
@endforelse
</div>

{{-- Delete Modal --}}
<div class="au-modal-bg" :class="{ open: showModal }" @click.self="showModal=false">
<div class="au-modal">
<div class="au-modal-ico"><i class="fas fa-user-times"></i></div>
<div class="au-modal-title">Remove this author?</div>
<div class="au-modal-sub">This will permanently remove the author account. Their posts will remain but become unattributed.</div>
<div class="au-modal-acts">
<button class="au-btn au-btn-outline" @click="showModal=false">Cancel</button>
<form :action="`{{ url('admin/authors') }}/${deleteId}`" method="POST">
@csrf
@method('DELETE')
<button type="submit" class="au-btn au-btn-danger">
<i class="fas fa-trash"></i> Remove
</button>
</form>
</div>
</div>
</div>

</div>

<script>
function resetForm() {
document.getElementById('auForm').reset();
document.getElementById('auMethod').value = 'POST';
document.getElementById('auIdField').value = '';
document.getElementById('auActive').checked = true;
}

window.loadEdit = function(id, name, email, bio, active) {
document.getElementById('auName').value    = name;
document.getElementById('auEmail').value   = email;
document.getElementById('auBio').value     = bio;
document.getElementById('auActive').checked = active;
document.getElementById('auMethod').value  = 'PUT';
document.getElementById('auIdField').value = id;
document.getElementById('auForm').action   = `/admin/authors/${id}`;
document.querySelector('[x-data]').__x.$data.editMode = true;
document.getElementById('auName').scrollIntoView({ behavior: 'smooth', block: 'center' });
document.getElementById('auName').focus();
};

function togglePw(inputId, btn) {
const input = document.getElementById(inputId);
const icon  = btn.querySelector('i');
if (input.type === 'password') {
input.type = 'text';
icon.className = 'fas fa-eye-slash';
} else {
input.type = 'password';
icon.className = 'fas fa-eye';
}
}
</script>

@endsection

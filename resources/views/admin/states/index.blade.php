@extends('layouts.admin')

@section('title', 'States Management')

@section('content')
<style>
:root{--white:#fff;--off:#f9fafb;--surface:#f3f4f6;--border:#e5e7eb;--bsoft:#f0f1f4;--t1:#111827;--t2:#6b7280;--t3:#9ca3af;--blue:#2563eb;--blue-h:#1d4ed8;--blue-l:#eff6ff;--blue-b:#bfdbfe;--green:#059669;--green-l:#ecfdf5;--red:#dc2626;--red-l:#fef2f2;--red-b:#fecaca;--teal:#0d9488;--teal-l:#f0fdfa;--r:10px;--rs:6px;--sh0:0 1px 3px rgba(0,0,0,.05);}
.st-btn{display:inline-flex;align-items:center;gap:7px;padding:9px 16px;border-radius:var(--rs);font-size:13px;font-weight:600;text-decoration:none;cursor:pointer;border:none;transition:all .15s;font-family:inherit;}
.st-btn-primary{background:var(--blue);color:#fff;box-shadow:0 1px 5px rgba(37,99,235,.3);}
.st-btn-primary:hover{background:var(--blue-h);transform:translateY(-1px);}
.st-btn-outline{background:var(--white);color:var(--t2);border:1px solid var(--border);}
.st-btn-outline:hover{background:var(--off);}
.st-btn-danger{background:var(--red);color:#fff;}
.st-btn-danger:hover{background:#b91c1c;}
.st-layout{display:grid;grid-template-columns:340px 1fr;gap:18px;align-items:start;}
@media(max-width:900px){.st-layout{grid-template-columns:1fr;}}
.st-header{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:18px 22px;margin-bottom:18px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;box-shadow:var(--sh0);}
.st-h-left{display:flex;align-items:center;gap:13px;}
.st-h-icon{width:42px;height:42px;background:var(--teal-l);border-radius:var(--rs);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.st-h-icon i{color:var(--teal);font-size:18px;}
.st-h-title{font-size:19px;font-weight:800;color:var(--t1);}
.st-h-sub{font-size:12.5px;color:var(--t3);margin-top:1px;}
.st-form-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh0);overflow:hidden;position:sticky;top:80px;}
.st-form-head{padding:14px 18px;border-bottom:1px solid var(--border);background:var(--off);display:flex;align-items:center;gap:10px;}
.st-form-icon{width:28px;height:28px;background:var(--teal-l);border-radius:var(--rs);display:flex;align-items:center;justify-content:center;}
.st-form-icon i{color:var(--teal);font-size:12px;}
.st-form-title{font-size:13.5px;font-weight:700;color:var(--t1);}
.st-form-body{padding:18px;}
.st-field{display:flex;flex-direction:column;gap:5px;margin-bottom:14px;}
.st-field:last-child{margin-bottom:0;}
.st-label{font-size:13px;font-weight:600;color:var(--t1);}
.st-label .req{color:var(--red);}
.st-hint{font-size:11.5px;color:var(--t3);}
.st-input{padding:9px 12px;background:var(--off);border:1px solid var(--border);border-radius:var(--rs);font-size:13.5px;font-family:inherit;color:var(--t1);outline:none;transition:all .15s;width:100%;}
.st-input:focus{background:var(--white);border-color:var(--blue-b);box-shadow:0 0 0 3px rgba(37,99,235,.08);}
.st-form-actions{padding:14px 18px;background:var(--off);border-top:1px solid var(--border);display:flex;gap:8px;}
.st-list-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh0);overflow:hidden;}
.st-list-head{padding:14px 18px;border-bottom:1px solid var(--border);background:var(--off);display:flex;align-items:center;justify-content:space-between;}
.st-list-title{font-size:14px;font-weight:700;color:var(--t1);}
.st-count-badge{background:var(--teal-l);color:var(--teal);font-size:12px;font-weight:700;padding:2px 9px;border-radius:20px;}
.st-item{padding:16px 18px;border-bottom:1px solid var(--bsoft);transition:background .1s;display:flex;align-items:center;gap:14px;}
.st-item:last-child{border-bottom:none;}
.st-item:hover{background:#fafbff;}
.st-icon-box{width:44px;height:44px;border-radius:var(--rs);display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;background:var(--teal-l);color:var(--teal);}
.st-item-info{flex:1;min-width:0;}
.st-item-name{font-size:14px;font-weight:700;color:var(--t1);margin-bottom:2px;}
.st-item-meta{display:flex;align-items:center;gap:8px;}
.st-stat-chip{display:flex;align-items:center;gap:4px;font-size:11.5px;color:var(--t2);background:var(--surface);padding:2px 8px;border-radius:20px;}
.st-stat-chip i{font-size:10px;color:var(--t3);}
.st-item-acts{display:flex;gap:4px;flex-shrink:0;}
.st-act{width:30px;height:30px;display:flex;align-items:center;justify-content:center;border-radius:var(--rs);border:1px solid var(--border);background:var(--white);cursor:pointer;text-decoration:none;transition:all .15s;font-size:12px;color:var(--t2);}
.st-act.edit:hover{background:var(--blue-l);border-color:var(--blue-b);color:var(--blue);}
.st-act.del:hover{background:var(--red-l);border-color:var(--red-b);color:var(--red);}
.st-empty{padding:48px 24px;text-align:center;}
.st-empty-icon{width:56px;height:56px;background:var(--surface);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;}
.st-empty-icon i{color:var(--t3);font-size:22px;}
.st-empty p{font-size:13.5px;color:var(--t3);}
.st-modal-bg{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:900;backdrop-filter:blur(3px);align-items:center;justify-content:center;padding:20px;}
.st-modal-bg.open{display:flex;}
.st-modal{background:var(--white);border-radius:var(--r);padding:28px 24px 22px;max-width:380px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,.15);text-align:center;}
.st-modal-ico{width:50px;height:50px;background:var(--red-l);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;}
.st-modal-ico i{color:var(--red);font-size:20px;}
.st-modal-title{font-size:16px;font-weight:800;color:var(--t1);margin-bottom:7px;}
.st-modal-sub{font-size:13px;color:var(--t2);margin-bottom:22px;line-height:1.6;}
.st-modal-acts{display:flex;gap:10px;justify-content:center;}
</style>

<div class="st-header">
<div class="st-h-left">
<div class="st-h-icon"><i class="fas fa-map-marked-alt"></i></div>
<div>
<div class="st-h-title">States</div>
<div class="st-h-sub">Manage geographical locations for job posts</div>
</div>
</div>
<button class="st-btn st-btn-primary" onclick="document.getElementById('stName').focus()">
<i class="fas fa-plus"></i> New State
</button>
</div>

<div class="st-layout" x-data="{ deleteId: null, showModal: false, editMode: false, confirmDelete(id) { this.deleteId = id; this.showModal = true; } }">

<div class="st-form-card">
<div class="st-form-head">
<div class="st-form-icon"><i class="fas fa-map-pin"></i></div>
<div class="st-form-title" x-text="editMode ? 'Edit State' : 'Add State'"></div>
</div>

<form action="{{ route('admin.states.store') }}" method="POST" id="stForm">
@csrf
<input type="hidden" name="_method" id="stMethod" value="POST">
<input type="hidden" name="state_id" id="stIdField">

<div class="st-form-body">
<div class="st-field">
<label class="st-label">State Name <span class="req">*</span></label>
<input type="text" name="name" id="stName" class="st-input" placeholder="e.g., Karnataka" required>
<span class="st-hint">Full name of the state or union territory</span>
</div>

<div class="st-field">
<label class="st-label">Meta Title</label>
<input type="text" name="meta_title" id="stMetaTitle" class="st-input" placeholder="SEO Title">
</div>
<div class="st-field">
<label class="st-label">Meta Description</label>
<textarea name="meta_description" id="stMetaDesc" class="st-input" rows="2" placeholder="SEO Description"></textarea>
</div>
<div class="st-field">
<label class="st-label">Meta Keywords</label>
<textarea name="meta_keywords" id="stMetaKeywords" class="st-input" rows="2" placeholder="SEO Keywords"></textarea>
</div>
<div class="st-field">
<label class="st-label">SEO Content</label>
<textarea name="seo_content" id="stSeoContent" class="st-input" rows="3" placeholder="Rich HTML content for the bottom of the page"></textarea>
</div>
</div>

<div class="st-form-actions">
<button type="submit" class="st-btn st-btn-primary" style="flex:1;justify-content:center;">
<i class="fas fa-save"></i>
<span x-text="editMode ? 'Update' : 'Create'"></span>
</button>
<button type="button" class="st-btn st-btn-outline" @click="resetForm()">
<i class="fas fa-times"></i>
</button>
</div>
</form>
</div>

<div class="st-list-card">
<div class="st-list-head">
<div class="st-list-title">All States</div>
<span class="st-count-badge">{{ $states->count() }} states</span>
</div>

@forelse($states as $st)
<div class="st-item">
<div class="st-icon-box">
<i class="fas fa-map-marker-alt"></i>
</div>
<div class="st-item-info">
<div class="st-item-name">{{ $st->name }}</div>
<div class="st-item-meta">
<span class="st-stat-chip">
<i class="fas fa-newspaper"></i> {{ $st->posts_count ?? 0 }} posts
</span>
<span class="st-stat-chip">
<i class="fas fa-calendar"></i> {{ $st->created_at->format('M Y') }}
</span>
</div>
</div>
<div class="st-item-acts">
<button type="button" class="st-act edit" title="Edit" @click="loadEdit({{ $st->id }}, '{{ addslashes($st->name) }}', '{{ addslashes($st->meta_title ?? '') }}', '{{ addslashes($st->meta_description ?? '') }}', '{{ addslashes($st->meta_keywords ?? '') }}')">
<i class="fas fa-pen"></i>
</button>
<button type="button" class="st-act del" title="Delete" @click="confirmDelete({{ $st->id }})">
<i class="fas fa-trash"></i>
</button>
</div>
</div>
@empty
<div class="st-empty">
<div class="st-empty-icon"><i class="fas fa-map-marked-alt"></i></div>
<p>No states yet. Add your first state above.</p>
</div>
@endforelse
</div>

<div class="st-modal-bg" :class="{ open: showModal }" @click.self="showModal=false">
<div class="st-modal">
<div class="st-modal-ico"><i class="fas fa-trash"></i></div>
<div class="st-modal-title">Remove this state?</div>
<div class="st-modal-sub">This will permanently delete the state. Posts in this state will become unassigned.</div>
<div class="st-modal-acts">
<button class="st-btn st-btn-outline" @click="showModal=false">Cancel</button>
<form :action="`{{ url('admin/states') }}/${deleteId}`" method="POST">
@csrf
@method('DELETE')
<button type="submit" class="st-btn st-btn-danger">
<i class="fas fa-trash"></i> Remove
</button>
</form>
</div>
</div>
</div>

</div>

<script>
function resetForm() {
document.getElementById('stForm').reset();
document.getElementById('stMethod').value = 'POST';
document.getElementById('stIdField').value = '';
document.getElementById('stMetaTitle').value = '';
document.getElementById('stMetaDesc').value = '';
document.getElementById('stMetaKeywords').value = '';
document.getElementById('stSeoContent').value = '';
document.querySelector('[x-data]').__x.$data.editMode = false;
}

window.loadEdit = function(id, name, meta_title, meta_desc, meta_keywords) {
document.getElementById('stName').value = name;
document.getElementById('stMetaTitle').value = meta_title || '';
document.getElementById('stMetaDesc').value = meta_desc || '';
document.getElementById('stMetaKeywords').value = meta_keywords || '';
document.getElementById('stSeoContent').value = '<!-- Content preserved. Updating from this quick form will currently overwrite it as empty if untouched unless AJAX is used -->';
document.getElementById('stMethod').value = 'PUT';
document.getElementById('stIdField').value = id;
document.getElementById('stForm').action = `/admin/states/${id}`;
document.querySelector('[x-data]').__x.$data.editMode = true;
document.getElementById('stName').scrollIntoView({ behavior: 'smooth', block: 'center' });
document.getElementById('stName').focus();
};
</script>

@endsection

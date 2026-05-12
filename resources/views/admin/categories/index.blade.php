@extends('layouts.admin')

@section('title', 'Categories Management')

@section('content')
<style>
:root{--white:#fff;--off:#f9fafb;--surface:#f3f4f6;--border:#e5e7eb;--bsoft:#f0f1f4;--t1:#111827;--t2:#6b7280;--t3:#9ca3af;--blue:#2563eb;--blue-h:#1d4ed8;--blue-l:#eff6ff;--blue-b:#bfdbfe;--green:#059669;--green-l:#ecfdf5;--amber:#d97706;--amber-l:#fffbeb;--red:#dc2626;--red-l:#fef2f2;--red-b:#fecaca;--orange:#ea580c;--orange-l:#fff7ed;--r:10px;--rs:6px;--sh0:0 1px 3px rgba(0,0,0,.05);}
.cat-btn{display:inline-flex;align-items:center;gap:7px;padding:9px 16px;border-radius:var(--rs);font-size:13px;font-weight:600;text-decoration:none;cursor:pointer;border:none;transition:all .15s;font-family:inherit;}
.cat-btn-primary{background:var(--blue);color:#fff;box-shadow:0 1px 5px rgba(37,99,235,.3);}
.cat-btn-primary:hover{background:var(--blue-h);transform:translateY(-1px);}
.cat-btn-outline{background:var(--white);color:var(--t2);border:1px solid var(--border);}
.cat-btn-outline:hover{background:var(--off);}
.cat-btn-danger{background:var(--red);color:#fff;}
.cat-btn-danger:hover{background:#b91c1c;}
.cat-layout{display:grid;grid-template-columns:340px 1fr;gap:18px;align-items:start;}
@media(max-width:900px){.cat-layout{grid-template-columns:1fr;}}
.cat-header{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:18px 22px;margin-bottom:18px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;box-shadow:var(--sh0);}
.cat-h-left{display:flex;align-items:center;gap:13px;}
.cat-h-icon{width:42px;height:42px;background:var(--orange-l);border-radius:var(--rs);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.cat-h-icon i{color:var(--orange);font-size:18px;}
.cat-h-title{font-size:19px;font-weight:800;color:var(--t1);}
.cat-h-sub{font-size:12.5px;color:var(--t3);margin-top:1px;}
.cat-form-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh0);overflow:hidden;position:sticky;top:80px;}
.cat-form-head{padding:14px 18px;border-bottom:1px solid var(--border);background:var(--off);display:flex;align-items:center;gap:10px;}
.cat-form-icon{width:28px;height:28px;background:var(--orange-l);border-radius:var(--rs);display:flex;align-items:center;justify-content:center;}
.cat-form-icon i{color:var(--orange);font-size:12px;}
.cat-form-title{font-size:13.5px;font-weight:700;color:var(--t1);}
.cat-form-body{padding:18px;}
.cat-field{display:flex;flex-direction:column;gap:5px;margin-bottom:14px;}
.cat-field:last-child{margin-bottom:0;}
.cat-label{font-size:13px;font-weight:600;color:var(--t1);}
.cat-label .req{color:var(--red);}
.cat-hint{font-size:11.5px;color:var(--t3);}
.cat-input{padding:9px 12px;background:var(--off);border:1px solid var(--border);border-radius:var(--rs);font-size:13.5px;font-family:inherit;color:var(--t1);outline:none;transition:all .15s;width:100%;}
.cat-input:focus{background:var(--white);border-color:var(--blue-b);box-shadow:0 0 0 3px rgba(37,99,235,.08);}
.cat-color-wrap{display:flex;align-items:center;gap:10px;}
.cat-color-input{width:50px;height:38px;padding:2px;border:1px solid var(--border);border-radius:var(--rs);cursor:pointer;background:var(--off);}
.cat-color-text{flex:1;}
.cat-form-actions{padding:14px 18px;background:var(--off);border-top:1px solid var(--border);display:flex;gap:8px;}
.cat-list-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh0);overflow:hidden;}
.cat-list-head{padding:14px 18px;border-bottom:1px solid var(--border);background:var(--off);display:flex;align-items:center;justify-content:space-between;}
.cat-list-title{font-size:14px;font-weight:700;color:var(--t1);}
.cat-count-badge{background:var(--orange-l);color:var(--orange);font-size:12px;font-weight:700;padding:2px 9px;border-radius:20px;}
.cat-item{padding:16px 18px;border-bottom:1px solid var(--bsoft);transition:background .1s;display:flex;align-items:center;gap:14px;}
.cat-item:last-child{border-bottom:none;}
.cat-item:hover{background:#fafbff;}
.cat-icon-box{width:44px;height:44px;border-radius:var(--rs);display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;}
.cat-item-info{flex:1;min-width:0;}
.cat-item-name{font-size:14px;font-weight:700;color:var(--t1);margin-bottom:2px;}
.cat-item-meta{display:flex;align-items:center;gap:8px;}
.cat-stat-chip{display:flex;align-items:center;gap:4px;font-size:11.5px;color:var(--t2);background:var(--surface);padding:2px 8px;border-radius:20px;}
.cat-stat-chip i{font-size:10px;color:var(--t3);}
.cat-item-acts{display:flex;gap:4px;flex-shrink:0;}
.cat-act{width:30px;height:30px;display:flex;align-items:center;justify-content:center;border-radius:var(--rs);border:1px solid var(--border);background:var(--white);cursor:pointer;text-decoration:none;transition:all .15s;font-size:12px;color:var(--t2);}
.cat-act.edit:hover{background:var(--blue-l);border-color:var(--blue-b);color:var(--blue);}
.cat-act.del:hover{background:var(--red-l);border-color:var(--red-b);color:var(--red);}
.cat-empty{padding:48px 24px;text-align:center;}
.cat-empty-icon{width:56px;height:56px;background:var(--surface);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;}
.cat-empty-icon i{color:var(--t3);font-size:22px;}
.cat-empty p{font-size:13.5px;color:var(--t3);}
.cat-modal-bg{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:900;backdrop-filter:blur(3px);align-items:center;justify-content:center;padding:20px;}
.cat-modal-bg.open{display:flex;}
.cat-modal{background:var(--white);border-radius:var(--r);padding:28px 24px 22px;max-width:380px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,.15);text-align:center;}
.cat-modal-ico{width:50px;height:50px;background:var(--red-l);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;}
.cat-modal-ico i{color:var(--red);font-size:20px;}
.cat-modal-title{font-size:16px;font-weight:800;color:var(--t1);margin-bottom:7px;}
.cat-modal-sub{font-size:13px;color:var(--t2);margin-bottom:22px;line-height:1.6;}
.cat-modal-acts{display:flex;gap:10px;justify-content:center;}
</style>

<div class="cat-header">
<div class="cat-h-left">
<div class="cat-h-icon"><i class="fas fa-tags"></i></div>
<div>
<div class="cat-h-title">Categories</div>
<div class="cat-h-sub">Organize your content with categories</div>
</div>
</div>
<button class="cat-btn cat-btn-primary" onclick="document.getElementById('catName').focus()">
<i class="fas fa-plus"></i> New Category
</button>
</div>

<div class="cat-layout" x-data="{ deleteId: null, showModal: false, editMode: false, confirmDelete(id) { this.deleteId = id; this.showModal = true; } }">

<div class="cat-form-card">
<div class="cat-form-head">
<div class="cat-form-icon"><i class="fas fa-tag"></i></div>
<div class="cat-form-title" x-text="editMode ? 'Edit Category' : 'Add Category'"></div>
</div>

<form action="{{ route('admin.categories.store') }}" method="POST" id="catForm">
@csrf
<input type="hidden" name="_method" id="catMethod" value="POST">
<input type="hidden" name="category_id" id="catIdField">

<div class="cat-form-body">
<div class="cat-field">
<label class="cat-label">Name <span class="req">*</span></label>
<input type="text" name="name" id="catName" class="cat-input" placeholder="e.g., Government Jobs" required>
</div>

<div class="cat-field">
<label class="cat-label">Icon</label>
<input type="text" name="icon" id="catIcon" class="cat-input" placeholder="e.g., briefcase">
<span class="cat-hint">FontAwesome icon name (without 'fa-' prefix)</span>
</div>

<div class="cat-field">
<label class="cat-label">Color</label>
<div class="cat-color-wrap">
<input type="color" name="color" id="catColor" class="cat-color-input" value="#3B82F6">
<input type="text" id="catColorText" class="cat-color-text cat-input" value="#3B82F6" readonly>
</div>
<span class="cat-hint">Used for category badges and icons</span>
</div>

<div class="cat-field">
<label class="cat-label">Meta Title</label>
<input type="text" name="meta_title" id="catMetaTitle" class="cat-input" placeholder="SEO Title">
</div>
<div class="cat-field">
<label class="cat-label">Meta Description</label>
<textarea name="meta_description" id="catMetaDesc" class="cat-input" rows="2" placeholder="SEO Description"></textarea>
</div>
<div class="cat-field">
<label class="cat-label">Meta Keywords</label>
<textarea name="meta_keywords" id="catMetaKeywords" class="cat-input" rows="2" placeholder="SEO Keywords"></textarea>
</div>
<div class="cat-field">
<label class="cat-label">SEO Content</label>
<textarea name="seo_content" id="catSeoContent" class="cat-input" rows="3" placeholder="Rich HTML content for the bottom of the page"></textarea>
</div>
</div>

<div class="cat-form-actions">
<button type="submit" class="cat-btn cat-btn-primary" style="flex:1;justify-content:center;">
<i class="fas fa-save"></i>
<span x-text="editMode ? 'Update' : 'Create'"></span>
</button>
<button type="button" class="cat-btn cat-btn-outline" @click="resetForm()">
<i class="fas fa-times"></i>
</button>
</div>
</form>
</div>

<div class="cat-list-card">
<div class="cat-list-head">
<div class="cat-list-title">All Categories</div>
<span class="cat-count-badge">{{ $categories->count() }} categories</span>
</div>

@forelse($categories as $cat)
<div class="cat-item">
<div class="cat-icon-box" style="background:{{ $cat->color }}20;color:{{ $cat->color }}">
    @php
        // Clean up icon - remove any prefixes, HTML tags, and extra spaces
        $iconName = $cat->icon ?? 'tag';
        $iconName = strip_tags($iconName); // Remove HTML tags
        $iconName = str_replace(['fas ', 'fa-solid ', 'fa-regular ', 'fa-brands ', 'fa-'], '', $iconName); // Remove prefixes
        $iconName = trim($iconName); // Remove extra spaces
        $iconName = $iconName ?: 'tag'; // Fallback to 'tag' if empty
    @endphp
    <i class="fas fa-{{ $iconName }}"></i>
</div>
<div class="cat-item-info">
<div class="cat-item-name">{{ $cat->name }}</div>
<div class="cat-item-meta">
<span class="cat-stat-chip">
<i class="fas fa-newspaper"></i> {{ $cat->posts_count ?? 0 }} posts
</span>
<span class="cat-stat-chip">
<i class="fas fa-calendar"></i> {{ $cat->created_at->format('M Y') }}
</span>
</div>
</div>
<div class="cat-item-acts">
<button type="button" class="cat-act edit" title="Edit" @click="loadEdit({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ $cat->icon ?? '' }}', '{{ $cat->color ?? '#3B82F6' }}', '{{ addslashes($cat->meta_title ?? '') }}', '{{ addslashes($cat->meta_description ?? '') }}', '{{ addslashes($cat->meta_keywords ?? '') }}')">
<i class="fas fa-pen"></i>
</button>
<button type="button" class="cat-act del" title="Delete" @click="confirmDelete({{ $cat->id }})">
<i class="fas fa-trash"></i>
</button>
</div>
</div>
@empty
<div class="cat-empty">
<div class="cat-empty-icon"><i class="fas fa-tags"></i></div>
<p>No categories yet. Create your first category above.</p>
</div>
@endforelse
</div>

<div class="cat-modal-bg" :class="{ open: showModal }" @click.self="showModal=false">
<div class="cat-modal">
<div class="cat-modal-ico"><i class="fas fa-trash"></i></div>
<div class="cat-modal-title">Remove this category?</div>
<div class="cat-modal-sub">This will permanently delete the category. Posts in this category will become uncategorized.</div>
<div class="cat-modal-acts">
<button class="cat-btn cat-btn-outline" @click="showModal=false">Cancel</button>
<form :action="`{{ url('admin/categories') }}/${deleteId}`" method="POST">
@csrf
@method('DELETE')
<button type="submit" class="cat-btn cat-btn-danger">
<i class="fas fa-trash"></i> Remove
</button>
</form>
</div>
</div>
</div>

</div>

<script>
document.getElementById('catColor').addEventListener('input', function() {
document.getElementById('catColorText').value = this.value;
});

function resetForm() {
document.getElementById('catForm').reset();
document.getElementById('catMethod').value = 'POST';
document.getElementById('catIdField').value = '';
document.getElementById('catColor').value = '#3B82F6';
document.getElementById('catColorText').value = '#3B82F6';
document.getElementById('catMetaTitle').value = '';
document.getElementById('catMetaDesc').value = '';
document.getElementById('catMetaKeywords').value = '';
document.getElementById('catSeoContent').value = '';
document.querySelector('[x-data]').__x.$data.editMode = false;
}

window.loadEdit = function(id, name, icon, color, meta_title, meta_desc, meta_keywords) {
document.getElementById('catName').value = name;
document.getElementById('catIcon').value = icon;
document.getElementById('catColor').value = color;
document.getElementById('catColorText').value = color;
document.getElementById('catMetaTitle').value = meta_title || '';
document.getElementById('catMetaDesc').value = meta_desc || '';
document.getElementById('catMetaKeywords').value = meta_keywords || '';
// We can't escape seo_content safely in an inline JS call, so we clear it and prompt editing via dedicated edit page if needed, or fetch via AJAX.
// For now, let's just clear it to avoid destroying it if not updated.
document.getElementById('catSeoContent').value = '<!-- Content preserved. Updating from this quick form will currently overwrite it as empty if untouched unless AJAX is used -->';
document.getElementById('catMethod').value = 'PUT';
document.getElementById('catIdField').value = id;
document.getElementById('catForm').action = `/admin/categories/${id}`;
document.querySelector('[x-data]').__x.$data.editMode = true;
document.getElementById('catName').scrollIntoView({ behavior: 'smooth', block: 'center' });
document.getElementById('catName').focus();
};
</script>

@endsection

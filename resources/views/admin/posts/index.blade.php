@extends('layouts.admin')

@section('title', 'Posts Management')

@section('content')
<style>
:root{--white:#fff;--off:#f9fafb;--border:#e5e7eb;--t1:#111827;--t2:#6b7280;--t3:#9ca3af;--blue:#2563eb;--green:#059669;--red:#dc2626;--amber:#d97706;}
.pm-header{background:var(--white);border:1px solid var(--border);border-radius:8px;padding:16px 20px;margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;}
.pm-title{font-size:18px;font-weight:700;color:var(--t1);}
.pm-actions{display:flex;gap:8px;flex-wrap:wrap;}
.pm-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:6px;font-size:13px;font-weight:600;text-decoration:none;cursor:pointer;border:none;transition:all .15s;}
.pm-btn-primary{background:var(--blue);color:#fff;}
.pm-btn-primary:hover{background:#1d4ed8;}
.pm-btn-outline{background:var(--white);color:var(--t2);border:1px solid var(--border);}
.pm-btn-outline:hover{background:var(--off);}
.pm-filters{background:var(--white);border:1px solid var(--border);border-radius:8px;padding:16px;margin-bottom:16px;}
.pm-filter-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px;margin-bottom:12px;}
.pm-filter-field label{display:block;font-size:12px;font-weight:600;color:var(--t2);margin-bottom:4px;}
.pm-filter-field input,.pm-filter-field select{width:100%;padding:7px 10px;border:1px solid var(--border);border-radius:6px;font-size:13px;background:var(--off);}
.pm-filter-field input:focus,.pm-filter-field select:focus{outline:none;border-color:var(--blue);background:var(--white);}
.pm-filter-actions{display:flex;gap:8px;}
.pm-table-wrap{background:var(--white);border:1px solid var(--border);border-radius:8px;overflow:hidden;margin-bottom:16px;}
.pm-table{width:100%;border-collapse:collapse;}
.pm-table thead{background:var(--off);border-bottom:1px solid var(--border);}
.pm-table th{padding:12px 16px;text-align:left;font-size:12px;font-weight:700;color:var(--t1);text-transform:uppercase;letter-spacing:.5px;}
.pm-table td{padding:12px 16px;border-bottom:1px solid var(--border);font-size:13px;}
.pm-table tbody tr:last-child td{border-bottom:none;}
.pm-table tbody tr:hover{background:var(--off);}
.pm-post-title{font-weight:600;color:var(--t1);margin-bottom:4px;}
.pm-post-meta{font-size:11px;color:var(--t3);}
.pm-badge{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:12px;font-size:11px;font-weight:600;}
.pm-badge.job{background:#dbeafe;color:#1e40af;}
.pm-badge.result{background:#d1fae5;color:#065f46;}
.pm-badge.admit_card{background:#fed7aa;color:#92400e;}
.pm-badge.answer_key{background:#e9d5ff;color:#6b21a8;}
.pm-badge.syllabus{background:#ddd6fe;color:#5b21b6;}
.pm-badge.blog{background:#fce7f3;color:#9f1239;}
.pm-badge.published{background:#d1fae5;color:#065f46;}
.pm-badge.draft{background:#fef3c7;color:#92400e;}
.pm-action-btn{display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:6px;border:1px solid var(--border);background:var(--white);cursor:pointer;transition:all .15s;text-decoration:none;}
.pm-action-btn:hover{background:var(--off);}
.pm-action-btn.edit:hover{background:#dbeafe;border-color:#3b82f6;color:#1e40af;}
.pm-action-btn.view:hover{background:#d1fae5;border-color:#10b981;color:#065f46;}
.pm-action-btn.delete:hover{background:#fee2e2;border-color:#ef4444;color:#991b1b;}
.pm-pagination{background:var(--white);border:1px solid var(--border);border-radius:8px;padding:16px;}
.pm-pagination nav{display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;}
.pm-pagination .pagination-info{font-size:13px;color:var(--t2);}
.pm-pagination .pagination-links{display:flex;gap:4px;}
.pm-pagination .pagination-links a,
.pm-pagination .pagination-links span{display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;padding:0 12px;border:1px solid var(--border);border-radius:6px;font-size:13px;font-weight:600;text-decoration:none;transition:all .15s;}
.pm-pagination .pagination-links a{background:var(--white);color:var(--t2);}
.pm-pagination .pagination-links a:hover{background:var(--blue);color:#fff;border-color:var(--blue);}
.pm-pagination .pagination-links span.current{background:var(--blue);color:#fff;border-color:var(--blue);}
.pm-pagination .pagination-links span.disabled{background:var(--off);color:var(--t3);cursor:not-allowed;}
.pm-pagination .pagination-links .dots{border:none;background:none;color:var(--t3);cursor:default;}
.pm-bulk-bar{background:#eff6ff;border:1px solid #3b82f6;border-radius:8px;padding:12px 16px;margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;}
.pm-bulk-info{font-size:13px;font-weight:600;color:var(--t1);}
.pm-bulk-actions{display:flex;gap:6px;flex-wrap:wrap;}
.pm-bulk-btn{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border-radius:6px;font-size:12px;font-weight:600;border:none;cursor:pointer;transition:all .15s;}
.pm-bulk-btn.publish{background:#10b981;color:#fff;}
.pm-bulk-btn.publish:hover{background:#059669;}
.pm-bulk-btn.unpublish{background:#f59e0b;color:#fff;}
.pm-bulk-btn.unpublish:hover{background:#d97706;}
.pm-bulk-btn.delete{background:#ef4444;color:#fff;}
.pm-bulk-btn.delete:hover{background:#dc2626;}
.pm-checkbox{width:16px;height:16px;accent-color:var(--blue);cursor:pointer;}
.pm-pagination{background:var(--white);border:1px solid var(--border);border-radius:8px;padding:16px;display:flex;justify-content:center;}
.pm-empty{background:var(--white);border:1px solid var(--border);border-radius:8px;padding:48px 24px;text-align:center;}
.pm-empty-icon{width:64px;height:64px;background:var(--off);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;}
.pm-empty-icon i{font-size:28px;color:var(--t3);}
.pm-empty-title{font-size:18px;font-weight:700;color:var(--t1);margin-bottom:8px;}
.pm-empty-text{font-size:14px;color:var(--t2);margin-bottom:20px;}
@media(max-width:768px){.pm-table-wrap{overflow-x:auto;}.pm-table{min-width:800px;}}
</style>

<div x-data="{ showFilters: false, selectedPosts: [], selectAll: false }">

<div class="pm-header">
<div class="pm-title">Posts Management</div>
<div class="pm-actions">
<button @click="showFilters = !showFilters" class="pm-btn pm-btn-outline">
<i class="fas fa-filter"></i>
<span x-text="showFilters ? 'Hide Filters' : 'Show Filters'"></span>
</button>
<a href="{{ route('admin.posts.create') }}" class="pm-btn pm-btn-primary">
<i class="fas fa-plus"></i>
New Post
</a>
</div>
</div>

<div x-show="showFilters" x-transition class="pm-filters">
<form method="GET" action="{{ route('admin.posts.index') }}">
<div class="pm-filter-grid">
<div class="pm-filter-field">
<label>Type</label>
<select name="type">
<option value="">All Types</option>
<option value="job" {{ request('type') == 'job' ? 'selected' : '' }}>Jobs</option>
<option value="result" {{ request('type') == 'result' ? 'selected' : '' }}>Results</option>
<option value="admit_card" {{ request('type') == 'admit_card' ? 'selected' : '' }}>Admit Cards</option>
<option value="answer_key" {{ request('type') == 'answer_key' ? 'selected' : '' }}>Answer Keys</option>
<option value="syllabus" {{ request('type') == 'syllabus' ? 'selected' : '' }}>Syllabus</option>
<option value="blog" {{ request('type') == 'blog' ? 'selected' : '' }}>Blogs</option>
</select>
</div>
<div class="pm-filter-field">
<label>Status</label>
<select name="status">
<option value="">All Status</option>
<option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
<option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
</select>
</div>
<div class="pm-filter-field">
<label>Category</label>
<select name="category_id">
<option value="">All Categories</option>
@foreach($categories as $category)
<option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
@endforeach
</select>
</div>
<div class="pm-filter-field">
<label>State</label>
<select name="state_id">
<option value="">All States</option>
@foreach($states as $state)
<option value="{{ $state->id }}" {{ request('state_id') == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
@endforeach
</select>
</div>
<div class="pm-filter-field">
<label>Search</label>
<input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts...">
</div>
</div>
<div class="pm-filter-actions">
<button type="submit" class="pm-btn pm-btn-primary">
<i class="fas fa-search"></i>
Apply Filters
</button>
<a href="{{ route('admin.posts.index') }}" class="pm-btn pm-btn-outline">
<i class="fas fa-times"></i>
Clear
</a>
</div>
</form>
</div>

<div x-show="selectedPosts.length > 0" x-transition class="pm-bulk-bar">
<div class="pm-bulk-info">
<i class="fas fa-check-circle"></i>
<span x-text="selectedPosts.length"></span> post(s) selected
</div>
<form action="{{ route('admin.posts.bulk-action') }}" method="POST" class="pm-bulk-actions">
@csrf
<template x-for="postId in selectedPosts" :key="postId">
<input type="hidden" name="posts[]" :value="postId">
</template>
<button type="submit" name="action" value="publish" class="pm-bulk-btn publish">
<i class="fas fa-eye"></i> Publish
</button>
<button type="submit" name="action" value="unpublish" class="pm-bulk-btn unpublish">
<i class="fas fa-eye-slash"></i> Unpublish
</button>
<button type="submit" name="action" value="delete" class="pm-bulk-btn delete" onclick="return confirm('Delete selected posts?')">
<i class="fas fa-trash"></i> Delete
</button>
</form>
</div>

@if ($posts->count() > 0)
<div class="pm-table-wrap">
<table class="pm-table">
<thead>
<tr>
<th style="width:40px">
<input type="checkbox" class="pm-checkbox" @change="selectAll = !selectAll; selectedPosts = selectAll ? {{ json_encode($posts->pluck('id')->toArray()) }} : []">
</th>
<th style="width:30%">Post</th>
<th style="width:10%">Type</th>
<th style="width:10%">Category</th>
<th style="width:10%">State</th>
<th style="width:12%">Tags</th>
<th style="width:12%">Education</th>
<th style="width:9%">Status</th>
<th style="width:7%">Views</th>
<th style="width:12%">Actions</th>
</tr>
</thead>
<tbody>
@foreach ($posts as $post)
<tr>
<td>
<input type="checkbox" class="pm-checkbox" :value="{{ $post->id }}" 
@change="selectedPosts.includes({{ $post->id }}) ? selectedPosts = selectedPosts.filter(id => id !== {{ $post->id }}) : selectedPosts.push({{ $post->id }})"
:checked="selectedPosts.includes({{ $post->id }})">
</td>
<td>
<div class="pm-post-title">{{ Str::limit($post->title, 60) }}</div>
<div class="pm-post-meta">
<i class="fas fa-calendar"></i> {{ $post->created_at->format('M d, Y') }}
</div>
</td>
<td>
<span class="pm-badge {{ $post->type }}">
{{ ucfirst(str_replace('_', ' ', $post->type)) }}
</span>
</td>
<td>{{ $post->category->name ?? 'N/A' }}</td>
<td>{{ $post->state->name ?? 'N/A' }}</td>
<td>
@if($post->tags && count($post->tags) > 0)
<div style="display:flex;flex-wrap:wrap;gap:4px;">
@foreach($post->tags as $tag)
<span style="display:inline-block;padding:2px 8px;background:#f3e8ff;color:#6b21a8;border-radius:10px;font-size:10px;font-weight:600;">
{{ ucfirst(str_replace('_', ' ', $tag)) }}
</span>
@endforeach
</div>
@else
<span style="color:var(--t3);font-size:12px;">—</span>
@endif
</td>
<td>
@if($post->education && count($post->education) > 0)
<div style="display:flex;flex-wrap:wrap;gap:4px;">
@foreach($post->education as $edu)
@php
$eduLabels = [
'10th_pass' => '10th',
'12th_pass' => '12th',
'graduate' => 'Grad',
'post_graduate' => 'PG',
'diploma' => 'Dip',
'iti' => 'ITI',
'btech' => 'BTech',
'mtech' => 'MTech',
'bsc' => 'BSc',
'msc' => 'MSc',
'bcom' => 'BCom',
'mcom' => 'MCom',
'ba' => 'BA',
'ma' => 'MA',
'bba' => 'BBA',
'mba' => 'MBA',
'ca' => 'CA',
'cs' => 'CS',
'cma' => 'CMA',
'llb' => 'LLB',
'llm' => 'LLM',
'mbbs' => 'MBBS',
'bds' => 'BDS',
'bpharm' => 'BPh',
'mpharm' => 'MPh',
'nursing' => 'Nurs',
'bed' => 'BEd',
'med' => 'MEd',
'phd' => 'PhD',
'any_qualification' => 'Any'
];
@endphp
<span style="display:inline-block;padding:2px 8px;background:#d1fae5;color:#065f46;border-radius:10px;font-size:10px;font-weight:600;">
{{ $eduLabels[$edu] ?? ucfirst(str_replace('_', ' ', $edu)) }}
</span>
@endforeach
</div>
@else
<span style="color:var(--t3);font-size:12px;">—</span>
@endif
</td>
<td>
@if ($post->is_published)
<span class="pm-badge published">
<i class="fas fa-check-circle"></i> Published
</span>
@else
<span class="pm-badge draft">
<i class="fas fa-clock"></i> Draft
</span>
@endif
</td>
<td>{{ number_format($post->view_count) }}</td>
<td>
<div style="display:flex;gap:4px;">
<a href="{{ route('admin.posts.edit', $post) }}" class="pm-action-btn edit" title="Edit">
<i class="fas fa-edit"></i>
</a>
<a href="{{ route('posts.show', [$post->type, $post]) }}" target="_blank" class="pm-action-btn view" title="View">
<i class="fas fa-eye"></i>
</a>
<form action="{{ route('admin.posts.destroy', $post) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this post?')">
@csrf
@method('DELETE')
<button type="submit" class="pm-action-btn delete" title="Delete">
<i class="fas fa-trash"></i>
</button>
</form>
</div>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>

<div class="pm-pagination">
{{ $posts->appends(request()->query())->links() }}
</div>
@else
<div class="pm-empty">
<div class="pm-empty-icon">
<i class="fas fa-newspaper"></i>
</div>
<div class="pm-empty-title">No posts found</div>
<div class="pm-empty-text">Create your first post to get started</div>
<a href="{{ route('admin.posts.create') }}" class="pm-btn pm-btn-primary">
<i class="fas fa-plus"></i>
Create Post
</a>
</div>
@endif

</div>

@endsection

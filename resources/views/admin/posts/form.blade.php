<style>
:root{--white:#fff;--off:#f9fafb;--surface:#f3f4f6;--border:#e5e7eb;--t1:#111827;--t2:#6b7280;--t3:#9ca3af;--blue:#2563eb;--blue-h:#1d4ed8;--blue-l:#eff6ff;--blue-b:#bfdbfe;--green:#059669;--green-l:#ecfdf5;--amber:#d97706;--amber-l:#fffbeb;--red:#dc2626;--red-l:#fef2f2;--purple:#7c3aed;--purple-l:#f5f3ff;--orange:#ea580c;--orange-l:#fff7ed;--teal:#0d9488;--teal-l:#f0fdfa;--r:10px;--rs:6px;--sh0:0 1px 3px rgba(0,0,0,.05);}
.pf-wrap{max-width:1100px;margin:0 auto;}
.pf-header{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:18px 22px;margin-bottom:18px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;box-shadow:var(--sh0);}
.pf-h-left{display:flex;align-items:center;gap:13px;}
.pf-h-icon{width:42px;height:42px;background:var(--blue-l);border-radius:var(--rs);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.pf-h-icon i{color:var(--blue);font-size:18px;}
.pf-h-title{font-size:19px;font-weight:800;color:var(--t1);}
.pf-h-sub{font-size:12.5px;color:var(--t3);margin-top:1px;}
.pf-error-box{background:var(--red-l);border-left:3px solid var(--red);padding:14px 16px;border-radius:var(--rs);margin-bottom:18px;}
.pf-error-title{font-size:13px;font-weight:700;color:var(--red);margin-bottom:6px;display:flex;align-items:center;gap:6px;}
.pf-error-list{list-style:disc;margin-left:20px;font-size:12px;color:#991b1b;}

/* SEO Score Card */
.pf-seo-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:18px;margin-bottom:18px;box-shadow:var(--sh0);}
.pf-seo-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;}
.pf-seo-title{font-size:15px;font-weight:700;color:var(--t1);display:flex;align-items:center;gap:8px;}
.pf-seo-score{font-size:28px;font-weight:800;}
.seo-score-red{color:#dc2626;}
.seo-score-amber{color:#d97706;}
.seo-score-green{color:#059669;}
.pf-seo-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:10px;}
.pf-seo-item{background:var(--off);border:1px solid var(--border);border-radius:var(--rs);padding:10px 12px;}
.pf-seo-item-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;}
.pf-seo-item-label{font-size:12px;font-weight:600;color:var(--t2);}
.pf-seo-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;}
.dot-gray{background:#9ca3af;}
.dot-green{background:#059669;}
.dot-amber{background:#d97706;}
.dot-red{background:#dc2626;}
.pf-seo-item-val{font-size:11px;color:var(--t3);}

/* Cards */
.pf-card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:20px;margin-bottom:18px;box-shadow:var(--sh0);}
.pf-section-title{font-size:14px;font-weight:700;color:var(--t1);margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px;}
.pf-field{margin-bottom:16px;position:relative;}
.pf-field:last-child{margin-bottom:0;}
.pf-field.has-error .pf-input,.pf-field.has-error .pf-select,.pf-field.has-error .pf-textarea{border-color:var(--red);background:#fef2f2;}
.pf-field.has-success .pf-input,.pf-field.has-success .pf-select,.pf-field.has-success .pf-textarea{border-color:var(--green);background:#f0fdf4;}
.pf-field-icon{position:absolute;right:12px;top:34px;font-size:14px;}
.pf-field-icon.success{color:var(--green);}
.pf-field-icon.error{color:var(--red);}
.pf-label{font-size:13px;font-weight:600;color:var(--t1);margin-bottom:6px;display:block;}
.pf-label .req{color:var(--red);}
.pf-hint{font-size:11.5px;color:var(--t3);margin-top:4px;display:block;}
.pf-input,.pf-select,.pf-textarea{padding:9px 12px;background:var(--off);border:1px solid var(--border);border-radius:var(--rs);font-size:13.5px;font-family:inherit;color:var(--t1);outline:none;transition:all .15s;width:100%;}
.pf-input:focus,.pf-select:focus,.pf-textarea:focus{background:var(--white);border-color:var(--blue-b);box-shadow:0 0 0 3px rgba(37,99,235,.08);}
.pf-input.error,.pf-select.error,.pf-textarea.error{border-color:var(--red);}
.pf-textarea{resize:vertical;min-height:200px;font-family:monospace;font-size:12.5px;line-height:1.6;}
.pf-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
.pf-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;}
.pf-grid-4{display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:16px;}
@media(max-width:900px){.pf-grid-4{grid-template-columns:1fr 1fr;}}
@media(max-width:768px){.pf-grid-2,.pf-grid-3,.pf-grid-4{grid-template-columns:1fr;}}
.pf-checkbox-wrap{display:flex;align-items:center;gap:10px;padding:12px;background:var(--off);border:1px solid var(--border);border-radius:var(--rs);}
.pf-checkbox{width:18px;height:18px;accent-color:var(--blue);}
.pf-checkbox-label{font-size:13px;font-weight:600;color:var(--t1);}
.pf-actions{display:flex;gap:10px;padding-top:20px;border-top:1px solid var(--border);}
.pf-btn{display:inline-flex;align-items:center;gap:7px;padding:11px 20px;border-radius:var(--rs);font-size:13.5px;font-weight:600;text-decoration:none;cursor:pointer;border:none;transition:all .15s;font-family:inherit;justify-content:center;}
.pf-btn-primary{background:var(--blue);color:#fff;box-shadow:0 1px 5px rgba(37,99,235,.3);flex:1;}
.pf-btn-primary:hover{background:var(--blue-h);transform:translateY(-1px);}
.pf-btn-outline{background:var(--white);color:var(--t2);border:1px solid var(--border);}
.pf-btn-outline:hover{background:var(--off);}
.pf-error-msg{color:var(--red);font-size:11.5px;margin-top:4px;display:flex;align-items:center;gap:4px;}

/* Tags & Education Chips */
.chip-group{display:flex;flex-wrap:wrap;gap:8px;margin-top:8px;}
.chip{display:inline-flex;align-items:center;gap:6px;padding:7px 13px;border:1.5px solid var(--border);border-radius:20px;cursor:pointer;transition:all .15s;user-select:none;font-size:12.5px;font-weight:500;color:var(--t1);background:var(--off);}
.chip input[type=checkbox]{display:none;}
.chip.checked-purple{background:var(--purple-l);border-color:var(--purple);color:var(--purple);}
.chip.checked-blue{background:var(--blue-l);border-color:var(--blue);color:var(--blue);}
.chip.checked-green{background:var(--green-l);border-color:var(--green);color:var(--green);}
.chip.checked-orange{background:var(--orange-l);border-color:var(--orange);color:var(--orange);}
.chip.checked-teal{background:var(--teal-l);border-color:var(--teal);color:var(--teal);}

/* Education category headers */
.edu-group{margin-bottom:14px;}
.edu-group-title{font-size:11.5px;font-weight:700;color:var(--t3);text-transform:uppercase;letter-spacing:.6px;margin-bottom:7px;display:flex;align-items:center;gap:6px;}
.edu-group-title::after{content:'';flex:1;height:1px;background:var(--border);}

/* Upcoming badge */
.upcoming-wrap{display:flex;align-items:center;gap:10px;padding:13px 16px;background:linear-gradient(135deg,#fff7ed,#fef3c7);border:1.5px solid var(--orange);border-radius:var(--rs);}
.upcoming-wrap input[type=checkbox]{width:18px;height:18px;accent-color:var(--orange);}
.upcoming-wrap label{font-size:13.5px;font-weight:700;color:var(--orange);cursor:pointer;}

/* URL fields with icon */
.pf-input-icon-wrap{position:relative;}
.pf-input-icon-wrap .pf-input{padding-left:38px;}
.pf-input-icon-wrap .field-prefix{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--t3);font-size:13px;pointer-events:none;}
</style>

<div class="pf-wrap" id="post-form-wrap">

<div class="pf-header">
<div class="pf-h-left">
<div class="pf-h-icon"><i class="fas fa-pen"></i></div>
<div>
<div class="pf-h-title">{{ isset($post) ? 'Edit Post' : 'Create New Post' }}</div>
<div class="pf-h-sub">Fill in the details below to publish your content</div>
</div>
</div>
</div>

@if ($errors->any())
<div class="pf-error-box">
<div class="pf-error-title"><i class="fas fa-exclamation-circle"></i>Please fix the following errors:</div>
<ul class="pf-error-list">
@foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif

{{-- ===== SEO SCORE ===== --}}
<div class="pf-seo-card" id="seo-score-card">
<div class="pf-seo-head">
<div class="pf-seo-title"><i class="fas fa-chart-line"></i>SEO Score</div>
<div class="pf-seo-score seo-score-red" id="seo-total">0/100</div>
</div>
<div class="pf-seo-grid">
<div class="pf-seo-item">
<div class="pf-seo-item-head"><span class="pf-seo-item-label">Title Length</span><div class="pf-seo-dot dot-gray" id="dot-title"></div></div>
<div class="pf-seo-item-val" id="val-title">0/60</div>
</div>
<div class="pf-seo-item">
<div class="pf-seo-item-head"><span class="pf-seo-item-label">Description</span><div class="pf-seo-dot dot-gray" id="dot-desc"></div></div>
<div class="pf-seo-item-val" id="val-desc">0/160</div>
</div>
<div class="pf-seo-item">
<div class="pf-seo-item-head"><span class="pf-seo-item-label">Keyword in Title</span><div class="pf-seo-dot dot-gray" id="dot-kw-title"></div></div>
<div class="pf-seo-item-val">Auto-check</div>
</div>
<div class="pf-seo-item">
<div class="pf-seo-item-head"><span class="pf-seo-item-label">Keyword in Desc</span><div class="pf-seo-dot dot-gray" id="dot-kw-desc"></div></div>
<div class="pf-seo-item-val">Auto-check</div>
</div>
<div class="pf-seo-item">
<div class="pf-seo-item-head"><span class="pf-seo-item-label">Word Count</span><div class="pf-seo-dot dot-gray" id="dot-words"></div></div>
<div class="pf-seo-item-val" id="val-words">0 words</div>
</div>
<div class="pf-seo-item">
<div class="pf-seo-item-head"><span class="pf-seo-item-label">Internal Links</span><div class="pf-seo-dot dot-gray" id="dot-links"></div></div>
<div class="pf-seo-item-val" id="val-links">0 links</div>
</div>
<div class="pf-seo-item">
<div class="pf-seo-item-head"><span class="pf-seo-item-label">Meta Keywords</span><div class="pf-seo-dot dot-gray" id="dot-kw"></div></div>
<div class="pf-seo-item-val" id="val-kw">0 keywords</div>
</div>
</div>
</div>

{{-- ===== BASIC INFO ===== --}}
<div class="pf-card">
<div class="pf-section-title"><i class="fas fa-file-alt"></i>Basic Information</div>

<div class="pf-field" id="field-title">
<label class="pf-label">Title <span class="req">*</span></label>
<input type="text" name="title" id="inp-title" class="pf-input @error('title') error @enderror"
value="{{ old('title', $post->title ?? '') }}" required>
@error('title')<span class="pf-error-msg"><i class="fas fa-exclamation-circle"></i>{{ $message }}</span>@enderror
</div>

<div class="pf-grid-3">
<div class="pf-field">
<label class="pf-label">Type <span class="req">*</span></label>
<select name="type" id="inp-type" class="pf-select @error('type') error @enderror" required>
<option value="">Select Type</option>
<option value="job" {{ old('type', $post->type ?? '') === 'job' ? 'selected' : '' }}>📋 Job</option>
<option value="result" {{ old('type', $post->type ?? '') === 'result' ? 'selected' : '' }}>🏆 Result</option>
<option value="admit_card" {{ old('type', $post->type ?? '') === 'admit_card' ? 'selected' : '' }}>🎟️ Admit Card</option>
<option value="answer_key" {{ old('type', $post->type ?? '') === 'answer_key' ? 'selected' : '' }}>🔑 Answer Key</option>
<option value="syllabus" {{ old('type', $post->type ?? '') === 'syllabus' ? 'selected' : '' }}>📚 Syllabus</option>
<option value="scholarship" {{ old('type', $post->type ?? '') === 'scholarship' ? 'selected' : '' }}>🎓 Scholarship</option>
<option value="blog" {{ old('type', $post->type ?? '') === 'blog' ? 'selected' : '' }}>✍️ Blog</option>
</select>
@error('type')<span class="pf-error-msg"><i class="fas fa-exclamation-circle"></i>{{ $message }}</span>@enderror
</div>

<div class="pf-field">
<label class="pf-label">Category <span class="req">*</span></label>
<select name="category_id" id="inp-category" class="pf-select @error('category_id') error @enderror" required>
<option value="">Select Category</option>
@foreach ($categories as $category)
<option value="{{ $category->id }}" {{ old('category_id', $post->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
@endforeach
</select>
@error('category_id')<span class="pf-error-msg"><i class="fas fa-exclamation-circle"></i>{{ $message }}</span>@enderror
</div>

<div class="pf-field">
<label class="pf-label">State</label>
<select name="state_id" class="pf-select @error('state_id') error @enderror">
<option value="">All India / Select State</option>
@foreach ($states as $state)
<option value="{{ $state->id }}" {{ old('state_id', $post->state_id ?? '') == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
@endforeach
</select>
@error('state_id')<span class="pf-error-msg"><i class="fas fa-exclamation-circle"></i>{{ $message }}</span>@enderror
</div>

<div class="pf-field">
<label class="pf-label">Organization</label>
<input type="text" name="organization" class="pf-input @error('organization') error @enderror"
value="{{ old('organization', $post->organization ?? '') }}"
placeholder="e.g., SSC, UPSC, Indian Railways">
<span class="pf-hint">Recruiting organization name</span>
@error('organization')<span class="pf-error-msg"><i class="fas fa-exclamation-circle"></i>{{ $message }}</span>@enderror
</div>
</div>

{{-- Upcoming Jobs toggle --}}
<div class="upcoming-wrap" style="margin-bottom:16px;">
<input type="checkbox" name="is_upcoming" id="is_upcoming" value="1"
{{ old('is_upcoming', $post->is_upcoming ?? false) ? 'checked' : '' }}>
<label for="is_upcoming">⏳ Mark as Upcoming Job (notify users before application opens)</label>
</div>

</div>

{{-- ===== VACANCY & SALARY ===== --}}
<div class="pf-card">
<div class="pf-section-title"><i class="fas fa-users"></i>Vacancy &amp; Salary Details</div>

<div class="pf-grid-4">
<div class="pf-field">
<label class="pf-label">Total Vacancies</label>
<input type="number" name="total_posts" class="pf-input @error('total_posts') error @enderror"
value="{{ old('total_posts', $post->total_posts ?? '') }}" placeholder="e.g., 500" min="1">
</div>

<div class="pf-field">
<label class="pf-label">Salary Type <span class="req">*</span></label>
<select name="salary_type" class="pf-select @error('salary_type') error @enderror" required>
<option value="salary" {{ old('salary_type', $post->salary_type ?? '') === 'salary' ? 'selected' : '' }}>Salary</option>
<option value="stipend" {{ old('salary_type', $post->salary_type ?? '') === 'stipend' ? 'selected' : '' }}>Stipend</option>
<option value="consolidated" {{ old('salary_type', $post->salary_type ?? '') === 'consolidated' ? 'selected' : '' }}>Consolidated</option>
<option value="pay_scale" {{ old('salary_type', $post->salary_type ?? '') === 'pay_scale' ? 'selected' : '' }}>Pay Scale</option>
</select>
</div>

<div class="pf-field">
<label class="pf-label">Pay Level (7th CPC)</label>
<select name="pay_scale_level" class="pf-select" onchange="if(this.value) { document.getElementById('inp-salary').value = this.options[this.selectedIndex].text.split(' - ')[1] || ''; }">
<option value="">Select Level</option>
<option value="Level 1" {{ old('pay_scale_level', $post->pay_scale_level ?? '') == 'Level 1' ? 'selected' : '' }}>Level 1 - ₹18,000–₹56,900</option>
<option value="Level 2" {{ old('pay_scale_level', $post->pay_scale_level ?? '') == 'Level 2' ? 'selected' : '' }}>Level 2 - ₹19,900–₹63,200</option>
<option value="Level 3" {{ old('pay_scale_level', $post->pay_scale_level ?? '') == 'Level 3' ? 'selected' : '' }}>Level 3 - ₹21,700–₹69,100</option>
<option value="Level 4" {{ old('pay_scale_level', $post->pay_scale_level ?? '') == 'Level 4' ? 'selected' : '' }}>Level 4 - ₹25,500–₹81,100</option>
<option value="Level 5" {{ old('pay_scale_level', $post->pay_scale_level ?? '') == 'Level 5' ? 'selected' : '' }}>Level 5 - ₹29,200–₹92,300</option>
<option value="Level 6" {{ old('pay_scale_level', $post->pay_scale_level ?? '') == 'Level 6' ? 'selected' : '' }}>Level 6 - ₹35,400–₹1,12,400</option>
<option value="Level 7" {{ old('pay_scale_level', $post->pay_scale_level ?? '') == 'Level 7' ? 'selected' : '' }}>Level 7 - ₹44,900–₹1,42,400</option>
<option value="Level 8" {{ old('pay_scale_level', $post->pay_scale_level ?? '') == 'Level 8' ? 'selected' : '' }}>Level 8 - ₹47,600–₹1,51,100</option>
<option value="Level 9" {{ old('pay_scale_level', $post->pay_scale_level ?? '') == 'Level 9' ? 'selected' : '' }}>Level 9 - ₹53,100–₹1,67,800</option>
<option value="Level 10" {{ old('pay_scale_level', $post->pay_scale_level ?? '') == 'Level 10' ? 'selected' : '' }}>Level 10 - ₹56,100–₹1,77,500</option>
</select>
</div>

<div class="pf-field">
<label class="pf-label">Salary String / Display</label>
<input type="text" name="salary" id="inp-salary" class="pf-input @error('salary') error @enderror"
value="{{ old('salary', $post->salary ?? '') }}">
</div>
</div>
</div>

{{-- ===== ELIGIBILITY & FEES ===== --}}
<div class="pf-card">
<div class="pf-section-title"><i class="fas fa-id-card"></i>Age Limit &amp; Application Fee</div>

<div class="pf-grid-4">
<div class="pf-field">
<label class="pf-label">Min Age</label>
<input type="number" name="age_min" class="pf-input" placeholder="e.g. 18" value="{{ old('age_min', $post->age_min ?? '') }}">
</div>
<div class="pf-field">
<label class="pf-label">Max Age (UR)</label>
<input type="number" name="age_max_gen" class="pf-input" placeholder="e.g. 27" value="{{ old('age_max_gen', $post->age_max_gen ?? '') }}">
</div>
<div class="pf-field">
<label class="pf-label">Age As On Date</label>
<input type="date" name="age_as_on_date" class="pf-input" value="{{ old('age_as_on_date', $post->age_as_on_date ?? '') }}">
</div>
<div class="pf-field">
<label class="pf-label">Age Relaxation Note</label>
<input type="text" name="age_relaxation_note" class="pf-input" placeholder="e.g. SC/ST 5 Yrs, OBC 3 Yrs" value="{{ old('age_relaxation_note', $post->age_relaxation_note ?? '') }}">
</div>
</div>

<div class="pf-grid-4" style="margin-top: 16px;">
<div class="pf-field">
<label class="pf-label">Fee: General/UR</label>
<input type="number" name="fee_general" class="pf-input" placeholder="e.g. 100 (0 for free)" value="{{ old('fee_general', $post->fee_general ?? '') }}">
</div>
<div class="pf-field">
<label class="pf-label">Fee: OBC/EWS</label>
<input type="number" name="fee_obc" class="pf-input" value="{{ old('fee_obc', $post->fee_obc ?? '') }}">
</div>
<div class="pf-field">
<label class="pf-label">Fee: SC/ST/PwD</label>
<input type="number" name="fee_sc_st" class="pf-input" value="{{ old('fee_sc_st', $post->fee_sc_st ?? '') }}">
</div>
<div class="pf-field">
<label class="pf-label">Fee: Women</label>
<input type="number" name="fee_women" class="pf-input" value="{{ old('fee_women', $post->fee_women ?? '') }}">
</div>
</div>
<div class="pf-grid-2" style="margin-top: 16px;">
<div class="pf-field">
<label class="pf-label">Fee Payment Mode</label>
<input type="text" name="fee_payment_mode" class="pf-input" placeholder="e.g. Online via SBI Collect, Net Banking" value="{{ old('fee_payment_mode', $post->fee_payment_mode ?? '') }}">
</div>
<div class="pf-field">
<label class="pf-label">Recruitment Year</label>
<input type="number" name="recruitment_year" class="pf-input" placeholder="e.g. 2026" value="{{ old('recruitment_year', $post->recruitment_year ?? '') }}">
</div>
</div>
</div>

{{-- ===== IMPORTANT DATES ===== --}}
<div class="pf-card">
<div class="pf-section-title"><i class="fas fa-calendar-alt"></i>Important Dates</div>

<div class="pf-grid-4">
<div class="pf-field">
<label class="pf-label">Notification Date</label>
<input type="date" name="notification_date" class="pf-input @error('notification_date') error @enderror"
value="{{ old('notification_date', isset($post->notification_date) ? $post->notification_date->format('Y-m-d') : '') }}">
<span class="pf-hint">Official notification released</span>
@error('notification_date')<span class="pf-error-msg"><i class="fas fa-exclamation-circle"></i>{{ $message }}</span>@enderror
</div>

<div class="pf-field">
<label class="pf-label">Start Date (Form Open)</label>
<input type="date" name="start_date" class="pf-input @error('start_date') error @enderror"
value="{{ old('start_date', isset($post->start_date) ? $post->start_date->format('Y-m-d') : '') }}">
<span class="pf-hint">Application form open date</span>
@error('start_date')<span class="pf-error-msg"><i class="fas fa-exclamation-circle"></i>{{ $message }}</span>@enderror
</div>

<div class="pf-field">
<label class="pf-label">End Date / Last Date</label>
<input type="date" name="end_date" class="pf-input @error('end_date') error @enderror"
value="{{ old('end_date', isset($post->end_date) ? $post->end_date->format('Y-m-d') : '') }}">
<span class="pf-hint">Application deadline</span>
@error('end_date')<span class="pf-error-msg"><i class="fas fa-exclamation-circle"></i>{{ $message }}</span>@enderror
</div>

<div class="pf-field">
<label class="pf-label">Last Date (Extended)</label>
<input type="date" name="last_date" class="pf-input @error('last_date') error @enderror"
value="{{ old('last_date', isset($post->last_date) ? $post->last_date->format('Y-m-d') : '') }}">
<span class="pf-hint">Final/extended application deadline</span>
@error('last_date')<span class="pf-error-msg"><i class="fas fa-exclamation-circle"></i>{{ $message }}</span>@enderror
</div>
</div>
</div>

{{-- ===== LINKS ===== --}}
<div class="pf-card">
<div class="pf-section-title"><i class="fas fa-link"></i>Important Links</div>

<div class="pf-grid-2">
<div class="pf-field">
<label class="pf-label">Online Application Form URL</label>
<div class="pf-input-icon-wrap">
<i class="fas fa-globe field-prefix"></i>
<input type="url" name="online_form" class="pf-input @error('online_form') error @enderror"
value="{{ old('online_form', $post->online_form ?? '') }}"
placeholder="https://recruitment.example.com/apply">
</div>
<span class="pf-hint">Direct link to online application form</span>
@error('online_form')<span class="pf-error-msg"><i class="fas fa-exclamation-circle"></i>{{ $message }}</span>@enderror
</div>

<div class="pf-field">
<label class="pf-label">Final Result URL</label>
<div class="pf-input-icon-wrap">
<i class="fas fa-trophy field-prefix"></i>
<input type="url" name="final_result" class="pf-input @error('final_result') error @enderror"
value="{{ old('final_result', $post->final_result ?? '') }}"
placeholder="https://result.example.com/final">
</div>
<span class="pf-hint">Direct link to final result PDF/page</span>
@error('final_result')<span class="pf-error-msg"><i class="fas fa-exclamation-circle"></i>{{ $message }}</span>@enderror
</div>
</div>
</div>

{{-- ===== TAGS ===== --}}
<div class="pf-card">
<div class="pf-section-title"><i class="fas fa-tags"></i>Post Tags</div>

@php
$tagGroups = [
    'Application' => [
        'new_vacancy'     => '🆕 New Vacancy',
        'upcoming'        => '⏳ Upcoming',
        'govt_job'        => '🏛️ Govt Job',
        'central_govt'    => '🇮🇳 Central Govt',
        'state_govt'      => '🗺️ State Govt',
        'walk_in'         => '🚶 Walk-in Interview',
        'direct_recruit'  => '✅ Direct Recruitment',
        'age_relaxation'  => '👴 Age Relaxation',
        'ex_servicemen'   => '⭐ Ex-Servicemen',
        'pwbd'            => '♿ PwBD / Divyang',
    ],
    'Result / Selection' => [
        'cutoff'          => '📊 Cutoff',
        'merit_list'      => '🏆 Merit List',
        'selection_list'  => '✅ Selection List',
        'final_result'    => '🎯 Final Result',
        'provisional_result' => '📋 Provisional Result',
        'revised_result'  => '🔄 Revised Result',
        'scorecard'       => '📝 Scorecard',
        'marks'           => '💯 Marks',
        'interview_call'  => '📞 Interview Call Letter',
    ],
    'Exam' => [
        'admit_card'      => '🎟️ Admit Card',
        'exam_date'       => '📅 Exam Date',
        'answer_key'      => '🔑 Answer Key',
        'syllabus'        => '📚 Syllabus',
        'exam_pattern'    => '📐 Exam Pattern',
        'last_date_soon'  => '⚠️ Last Date Soon',
    ],
];
$postTags = isset($post) && $post->tags ? (is_array($post->tags) ? $post->tags : json_decode($post->tags, true) ?? []) : [];
$selectedTags = old('tags', $postTags);
if (!is_array($selectedTags)) $selectedTags = [];
@endphp

@foreach($tagGroups as $groupName => $tags)
<div class="edu-group">
<div class="edu-group-title">{{ $groupName }}</div>
<div class="chip-group">
@foreach($tags as $val => $label)
@php $chk = in_array($val, $selectedTags); @endphp
<label class="chip {{ $chk ? 'checked-purple' : '' }}" onclick="toggleChip(this,'checked-purple')">
<input type="checkbox" name="tags[]" value="{{ $val }}" {{ $chk ? 'checked' : '' }}>
{{ $label }}
</label>
@endforeach
</div>
</div>
@endforeach
<span class="pf-hint">Select all tags that apply to this post</span>
</div>

{{-- ===== EDUCATION ===== --}}
<div class="pf-card">
<div class="pf-section-title"><i class="fas fa-graduation-cap"></i>Education Qualification Required</div>

@php
$educationGroups = [
    'Basic' => [
        '10th_pass'       => '📚 10th Pass (Matric)',
        '12th_pass'       => '📖 12th Pass (Inter)',
        'any_qualification' => '✅ Any Qualification',
    ],
    'Diploma & ITI' => [
        'iti'             => '🔧 ITI (Industrial Training)',
        'diploma'         => '📜 Diploma (Any Branch)',
        'diploma_civil'   => '🏗️ Diploma – Civil Engg.',
        'diploma_mech'    => '⚙️ Diploma – Mechanical',
        'diploma_elec'    => '⚡ Diploma – Electrical',
        'diploma_cs'      => '💻 Diploma – Computer Sci.',
        'diploma_it'      => '🖥️ Diploma – IT',
        'diploma_auto'    => '🚗 Diploma – Automobile',
        'diploma_pharma'  => '💊 Diploma – Pharmacy',
        'diploma_nursing' => '👩‍⚕️ Diploma – Nursing (GNM)',
        'diploma_arch'    => '🏛️ Diploma – Architecture',
        'diploma_other'   => '📄 Diploma – Other Branch',
    ],
    'Graduation' => [
        'graduate'        => '🎓 Any Graduate (BA/B.Sc/B.Com)',
        'ba'              => '📖 B.A (Arts)',
        'bsc'             => '🔬 B.Sc (Science)',
        'bcom'            => '💼 B.Com (Commerce)',
        'bba'             => '💼 BBA',
        'btech'           => '⚙️ B.Tech / B.E (Engg.)',
        'bca'             => '💻 BCA (Computer Apps)',
        'llb'             => '⚖️ LLB (Law)',
        'mbbs'            => '🩺 MBBS',
        'bds'             => '🦷 BDS (Dental)',
        'bpharm'          => '💊 B.Pharm',
        'nursing'         => '👩‍⚕️ B.Sc Nursing',
        'bed'             => '👨‍🏫 B.Ed (Education)',
    ],
    'Post Graduation' => [
        'post_graduate'   => '🎓 Any Post Graduate',
        'ma'              => '📖 M.A',
        'msc'             => '🔬 M.Sc',
        'mcom'            => '💼 M.Com',
        'mba'             => '💼 MBA',
        'mtech'           => '⚙️ M.Tech / M.E',
        'mca'             => '💻 MCA',
        'llm'             => '⚖️ LLM (Master of Law)',
        'mpharm'          => '💊 M.Pharm',
        'med'             => '👨‍🏫 M.Ed',
        'msc_nursing'     => '👩‍⚕️ M.Sc Nursing',
    ],
    'Professional / CA / PhD' => [
        'ca'              => '💰 CA (Chartered Accountant)',
        'cs'              => '📋 CS (Company Secretary)',
        'cma'             => '💹 CMA (Cost Accountant)',
        'phd'             => '🎓 PhD / Doctorate',
    ],
];
$postEducation = isset($post) && $post->education ? (is_array($post->education) ? $post->education : json_decode($post->education, true) ?? []) : [];
$selectedEducation = old('education', $postEducation);
if (!is_array($selectedEducation)) $selectedEducation = [];
@endphp

@foreach($educationGroups as $groupName => $eduList)
<div class="edu-group">
<div class="edu-group-title">{{ $groupName }}</div>
<div class="chip-group">
@foreach($eduList as $val => $label)
@php $chk = in_array($val, $selectedEducation); @endphp
<label class="chip {{ $chk ? 'checked-blue' : '' }}" onclick="toggleChip(this,'checked-blue')">
<input type="checkbox" name="education[]" value="{{ $val }}" {{ $chk ? 'checked' : '' }}>
{{ $label }}
</label>
@endforeach
</div>
</div>
@endforeach
<span class="pf-hint">Select all education qualifications required for this post</span>
</div>

{{-- ===== CONTENT ===== --}}
<div class="pf-card">
<div class="pf-section-title"><i class="fas fa-align-left"></i>Post Content</div>

<div class="pf-field" id="field-content">
<label class="pf-label">Content <span class="req">*</span></label>
<textarea name="content" id="inp-content" class="pf-textarea @error('content') error @enderror" required style="min-height:300px;">{!! old('content', $post->content ?? '') !!}</textarea>
<span class="pf-hint">Paste HTML content here. It will be preserved exactly as entered.</span>
@error('content')<span class="pf-error-msg"><i class="fas fa-exclamation-circle"></i>{{ $message }}</span>@enderror
</div>
</div>

{{-- ===== SEO & META ===== --}}
<div class="pf-card">
<div class="pf-section-title"><i class="fas fa-search"></i>SEO &amp; Meta Tags</div>

<div class="pf-grid-2">
<div class="pf-field">
<label class="pf-label">Meta Title <span style="font-size:11px;color:var(--t3);">(50–60 chars recommended)</span></label>
<input type="text" name="meta_title" id="inp-meta-title" maxlength="255" class="pf-input @error('meta_title') error @enderror"
value="{{ old('meta_title', $post->meta_title ?? '') }}">
<span class="pf-hint" id="meta-title-count">0/60 characters</span>
@error('meta_title')<span class="pf-error-msg"><i class="fas fa-exclamation-circle"></i>{{ $message }}</span>@enderror
</div>

<div class="pf-field">
<label class="pf-label">Meta Description <span style="font-size:11px;color:var(--t3);">(120–160 chars recommended)</span></label>
<input type="text" name="meta_description" id="inp-meta-desc" maxlength="160" class="pf-input @error('meta_description') error @enderror"
value="{{ old('meta_description', $post->meta_description ?? '') }}">
<span class="pf-hint" id="meta-desc-count">0/160 characters</span>
@error('meta_description')<span class="pf-error-msg"><i class="fas fa-exclamation-circle"></i>{{ $message }}</span>@enderror
</div>
</div>

<div class="pf-field">
<label class="pf-label">Meta Keywords</label>
<input type="text" name="meta_keywords" id="inp-meta-kw" maxlength="1000" class="pf-input @error('meta_keywords') error @enderror"
value="{{ old('meta_keywords', $post->meta_keywords ?? '') }}">
<span class="pf-hint" id="meta-kw-count">Separate keywords with commas — 0/1000 characters</span>
@error('meta_keywords')<span class="pf-error-msg"><i class="fas fa-exclamation-circle"></i>{{ $message }}</span>@enderror
</div>
</div>

{{-- ===== PUBLISHING ===== --}}
<div class="pf-card">
<div class="pf-section-title"><i class="fas fa-cog"></i>Publishing Options</div>

<div class="pf-grid-2" style="margin-bottom:0;">
<div class="pf-checkbox-wrap">
<input type="checkbox" name="is_featured" value="1" class="pf-checkbox" id="is_featured"
{{ old('is_featured', $post->is_featured ?? false) ? 'checked' : '' }}>
<label for="is_featured" class="pf-checkbox-label">⭐ Featured Post</label>
</div>

<div class="pf-checkbox-wrap">
<input type="checkbox" name="is_published" value="1" class="pf-checkbox" id="is_published"
{{ old('is_published', $post->is_published ?? false) ? 'checked' : '' }}>
<label for="is_published" class="pf-checkbox-label">✅ Published</label>
</div>

@if(isset($post))
<div class="pf-checkbox-wrap" style="grid-column: 1 / -1; margin-top: 10px;">
<input type="checkbox" name="resend_notifications" value="1" class="pf-checkbox" id="resend_notifications">
<label for="resend_notifications" class="pf-checkbox-label">📢 Resend Notification to Telegram & WhatsApp</label>
</div>
@endif
</div>

<div class="pf-actions">
<button type="submit" class="pf-btn pf-btn-primary" id="submit-btn">
<i class="fas fa-save"></i>
{{ isset($post) ? 'Update Post' : 'Create Post' }}
</button>
<a href="{{ route('admin.posts.index') }}" class="pf-btn pf-btn-outline">
<i class="fas fa-times"></i>Cancel
</a>
</div>
</div>

</div>{{-- /pf-wrap --}}

<script>
// ===== CHIP TOGGLE =====
function toggleChip(label, checkedClass) {
    const cb = label.querySelector('input[type=checkbox]');
    if (!cb) return;
    // Toggle happens after click default, so read new state
    setTimeout(() => {
        if (cb.checked) {
            label.classList.add(checkedClass);
        } else {
            label.classList.remove(checkedClass);
        }
    }, 0);
}

// ===== SEO ANALYZER (vanilla JS, no Tailwind) =====
(function() {
    const $ = id => document.getElementById(id);

    function dot(id, cls) {
        const el = $(id);
        if (!el) return;
        el.className = 'pf-seo-dot ' + cls;
    }
    function txt(id, val) {
        const el = $(id);
        if (el) el.textContent = val;
    }

    function stripHtml(html) {
        const d = document.createElement('div');
        d.innerHTML = html;
        return d.textContent || d.innerText || '';
    }

    function analyze() {
        const title      = ($('inp-title')       || {}).value || '';
        const metaTitle  = ($('inp-meta-title')  || {}).value || '';
        const metaDesc   = ($('inp-meta-desc')   || {}).value || '';
        const metaKw     = ($('inp-meta-kw')     || {}).value || '';
        const content    = ($('inp-content')     || {}).value || '';

        const effectiveTitle = metaTitle || title;
        const titleLen   = effectiveTitle.length;
        const descLen    = metaDesc.length;
        const keywords   = metaKw.split(',').map(k=>k.trim()).filter(k=>k.length>0);
        const kwCount    = keywords.length;
        const wordCount  = ((stripHtml(content).trim().split(/\s+/).filter(w=>w.length>0)) || []).length;
        const linkMatches= (content.match(/<a\s+[^>]*href=["'][^"']*["'][^>]*>/gi) || []).length;

        // Update character counters
        txt('meta-title-count', titleLen + '/60 characters');
        txt('meta-desc-count', descLen + '/160 characters');
        txt('meta-kw-count', 'Separate keywords with commas — ' + metaKw.length + '/1000 characters');

        // Score calculation
        let score = 0;

        // Title (20pts)
        if (titleLen >= 50 && titleLen <= 60)      { dot('dot-title','pf-seo-dot dot-green'); score += 20; }
        else if (titleLen >= 40 && titleLen < 50)  { dot('dot-title','pf-seo-dot dot-amber'); score += 13; }
        else if (titleLen > 0)                     { dot('dot-title','pf-seo-dot dot-red');   score += 5; }
        else                                       { dot('dot-title','pf-seo-dot dot-red'); }
        txt('val-title', titleLen + '/60');

        // Description (20pts)
        if (descLen >= 120 && descLen <= 160)      { dot('dot-desc','pf-seo-dot dot-green'); score += 20; }
        else if (descLen >= 80 && descLen < 120)   { dot('dot-desc','pf-seo-dot dot-amber'); score += 13; }
        else if (descLen > 0)                      { dot('dot-desc','pf-seo-dot dot-red');   score += 5; }
        else                                       { dot('dot-desc','pf-seo-dot dot-red'); }
        txt('val-desc', descLen + '/160');

        // Keyword in title (15pts)
        const kwInTitle = keywords.some(k => effectiveTitle.toLowerCase().includes(k.toLowerCase()));
        if (keywords.length === 0)                       { dot('dot-kw-title','pf-seo-dot dot-gray'); }
        else if (kwInTitle)                              { dot('dot-kw-title','pf-seo-dot dot-green'); score += 15; }
        else                                             { dot('dot-kw-title','pf-seo-dot dot-red'); }

        // Keyword in description (15pts)
        const kwInDesc = keywords.some(k => metaDesc.toLowerCase().includes(k.toLowerCase()));
        if (keywords.length === 0 || descLen === 0)      { dot('dot-kw-desc','pf-seo-dot dot-gray'); }
        else if (kwInDesc)                               { dot('dot-kw-desc','pf-seo-dot dot-green'); score += 15; }
        else                                             { dot('dot-kw-desc','pf-seo-dot dot-amber'); }

        // Word count (15pts)
        if (wordCount >= 300)                      { dot('dot-words','pf-seo-dot dot-green'); score += 15; }
        else if (wordCount >= 150)                 { dot('dot-words','pf-seo-dot dot-amber'); score += 10; }
        else if (wordCount > 0)                    { dot('dot-words','pf-seo-dot dot-red');   score += 5; }
        else                                       { dot('dot-words','pf-seo-dot dot-red'); }
        txt('val-words', wordCount + ' words');

        // Links (10pts)
        if (linkMatches >= 2)                      { dot('dot-links','pf-seo-dot dot-green'); score += 10; }
        else if (linkMatches === 1)                { dot('dot-links','pf-seo-dot dot-amber'); score += 5; }
        else                                       { dot('dot-links','pf-seo-dot dot-red'); }
        txt('val-links', linkMatches + ' links');

        // Meta keywords (5pts)
        if (kwCount >= 5)                          { dot('dot-kw','pf-seo-dot dot-green'); score += 5; }
        else if (kwCount >= 2)                     { dot('dot-kw','pf-seo-dot dot-amber'); score += 3; }
        else if (kwCount > 0)                      { dot('dot-kw','pf-seo-dot dot-red'); score += 1; }
        else                                       { dot('dot-kw','pf-seo-dot dot-red'); }
        txt('val-kw', kwCount + ' keywords');

        // Total score color
        const scoreEl = $('seo-total');
        if (scoreEl) {
            scoreEl.textContent = score + '/100';
            scoreEl.className = 'pf-seo-score ' +
                (score >= 80 ? 'seo-score-green' : score >= 55 ? 'seo-score-amber' : 'seo-score-red');
        }
    }

    // Bind events
    ['inp-title','inp-meta-title','inp-meta-desc','inp-meta-kw','inp-content'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('input', analyze);
    });

    // Run on page load
    document.addEventListener('DOMContentLoaded', analyze);
    analyze();
})();
</script>

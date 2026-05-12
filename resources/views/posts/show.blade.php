@extends('layouts.app')

@section('title', $post->meta_title)
@section('description', $post->meta_description)
@section('keywords', $post->meta_keywords)
@section('canonical', route('posts.show', [$post->type, $post->slug]))
@section('og_title', $post->meta_title)
@section('og_description', $post->meta_description)
@section('og_url', route('posts.show', [$post->type, $post->slug]))

@section('content')

@php
    $typeRouteMap = [
        'job'        => ['route' => 'posts.jobs',       'label' => 'Jobs'],
        'admit_card' => ['route' => 'posts.admit-cards','label' => 'Admit Cards'],
        'result'     => ['route' => 'posts.results',    'label' => 'Results'],
        'answer_key' => ['route' => 'posts.answer-keys','label' => 'Answer Keys'],
        'syllabus'   => ['route' => 'posts.syllabus',   'label' => 'Syllabus'],
        'blog'       => ['route' => 'posts.blogs',      'label' => 'Blog'],
        'scholarship'=> ['route' => 'posts.scholarships','label' => 'Scholarships'],
    ];
    $typeInfo   = $typeRouteMap[$post->type] ?? ['route' => 'home', 'label' => 'Posts'];
    $postUrl    = route('posts.show', [$post->type, $post->slug]);

    // Important links
    $importantLinks = $post->important_links;
    if (is_string($importantLinks)) { $importantLinks = json_decode($importantLinks, true) ?? []; }
    if (!is_array($importantLinks)) { $importantLinks = []; }
    // Filter out links with no valid URL or no meaningful label
    $importantLinks = array_filter($importantLinks, function($v) {
        $url   = is_array($v) ? ($v['url']   ?? $v['link'] ?? '') : $v;
        $label = is_array($v) ? ($v['label'] ?? $v['title'] ?? '') : '';
        return !empty($url) && $url !== '#' && filter_var(trim($url), FILTER_VALIDATE_URL);
    });
    $importantLinks = array_values($importantLinks);

    // Education labels — extended map with new diploma sub-types
    $eduMap = [
        '10th_pass'       => '10th Pass',      '12th_pass'      => '12th Pass',
        'graduate'        => 'Graduate',        'post_graduate'  => 'Post Graduate',
        'diploma'         => 'Diploma',         'diploma_civil'  => 'Diploma (Civil)',
        'diploma_mech'    => 'Diploma (Mech)',  'diploma_elec'   => 'Diploma (Electrical)',
        'diploma_cs'      => 'Diploma (CS)',    'diploma_it'     => 'Diploma (IT)',
        'diploma_auto'    => 'Diploma (Auto)',  'diploma_pharma' => 'Diploma (Pharmacy)',
        'diploma_nursing' => 'Diploma (Nursing)','diploma_arch'  => 'Diploma (Architecture)',
        'diploma_other'   => 'Diploma (Other)', 'iti'            => 'ITI',
        'btech'           => 'B.Tech/B.E',      'mtech'          => 'M.Tech/M.E',
        'bca'             => 'BCA',             'mca'            => 'MCA',
        'bsc'             => 'B.Sc',            'msc'            => 'M.Sc',
        'bcom'            => 'B.Com',           'mcom'           => 'M.Com',
        'ba'              => 'B.A',             'ma'             => 'M.A',
        'bba'             => 'BBA',             'mba'            => 'MBA',
        'ca'              => 'CA',              'cs'             => 'CS',
        'cma'             => 'CMA',             'llb'            => 'LLB',
        'llm'             => 'LLM',             'mbbs'           => 'MBBS',
        'bds'             => 'BDS',             'bpharm'         => 'B.Pharm',
        'mpharm'          => 'M.Pharm',         'nursing'        => 'B.Sc Nursing',
        'msc_nursing'     => 'M.Sc Nursing',    'bed'            => 'B.Ed',
        'med'             => 'M.Ed',            'phd'            => 'PhD',
        'any_qualification'=> 'Any Qualification',
    ];
    $eduLabels = [];
    if ($post->education && is_array($post->education)) {
        foreach ($post->education as $e) { $eduLabels[] = $eduMap[$e] ?? ucwords(str_replace('_',' ',$e)); }
    }

    // Days remaining
    $daysLeft    = null;
    $lastDateStr = null;
    if ($post->last_date) {
        $daysLeft    = now()->startOfDay()->diffInDays($post->last_date->startOfDay(), false);
        $lastDateStr = $post->last_date->format('d M Y');
    }

    // Org initials for logo box
    $orgName     = $post->organization ?? 'GOV';
    $orgWords    = explode(' ', $orgName);
    $orgInitials = '';
    foreach(array_slice($orgWords, 0, 2) as $w) { $orgInitials .= strtoupper(substr($w,0,2)) . ' '; }
    $orgInitials = trim($orgInitials);

    // Direct apply link (online_form field takes priority over important_links)
    $directApplyLink = $post->online_form ?? null;
    if (!$directApplyLink && count($importantLinks) > 0) {
        foreach ($importantLinks as $k => $v) {
            $lbl = strtolower(is_array($v) ? ($v['label'] ?? $k) : $k);
            $lu  = is_array($v) ? ($v['url'] ?? $v) : $v;
            if (str_contains($lbl,'apply') || str_contains($lbl,'official') || str_contains($lbl,'register')) {
                $directApplyLink = $lu; break;
            }
        }
    }
    if (!$directApplyLink && count($importantLinks) > 0) {
        $first = reset($importantLinks);
        $directApplyLink = is_array($first) ? ($first['url'] ?? '#') : $first;
    }
@endphp

@php
    // Group post types
    $isJobType    = in_array($post->type, ['job', 'scholarship']);
    $isExamType   = in_array($post->type, ['admit_card', 'result', 'answer_key', 'syllabus']);
    $isBlog       = $post->type === 'blog';
    // Type-specific CTA labels
    $ctaLabel = match($post->type) {
        'job'         => '✅ Apply on Official Site',
        'admit_card'  => '🎟️ Download Admit Card',
        'result'      => '🏆 Check Result',
        'answer_key'  => '🔑 Download Answer Key',
        'syllabus'    => '📚 Download Syllabus',
        'scholarship' => '✅ Apply for Scholarship',
        'blog'        => '🔗 View Official Link',
        default       => '📄 View Official Link',
    };
    $ctaLabelMobile = match($post->type) {
        'job'         => '✅ Apply Now',
        'admit_card'  => '🎟️ Download',
        'result'      => '🏆 Result',
        'answer_key'  => '🔑 Answer Key',
        'syllabus'    => '📚 Syllabus',
        'scholarship' => '✅ Apply',
        default       => '📄 View',
    };
    // Section title
    $quickInfoTitle = match($post->type) {
        'job'         => 'ℹ️ Job Details',
        'admit_card'  => 'ℹ️ Admit Card Details',
        'result'      => 'ℹ️ Result Details',
        'answer_key'  => 'ℹ️ Answer Key Details',
        'syllabus'    => 'ℹ️ Syllabus Details',
        'scholarship' => 'ℹ️ Scholarship Details',
        default       => 'ℹ️ Quick Information',
    };
    $contentTitle = match($post->type) {
        'admit_card'  => '🎟️ Admit Card Details',
        'result'      => '🏆 Result Details',
        'answer_key'  => '🔑 Answer Key Details',
        'syllabus'    => '📚 Syllabus & Exam Pattern',
        'scholarship' => '🎓 Scholarship Details',
        'blog'        => '📝 Article',
        default       => '📋 Full Details',
    };
@endphp

<style>
*{box-sizing:border-box}
.pg{padding:14px 16px;max-width:1000px;margin:0 auto;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;overflow-x:hidden;width:100%;box-sizing:border-box}
.breadcrumb{font-size:12px;color:#9ca3af;margin-bottom:14px;display:flex;flex-wrap:wrap;gap:4px;align-items:center;line-height:1.6}
.breadcrumb a{color:#2563eb;text-decoration:none;white-space:nowrap}
.breadcrumb a:hover{text-decoration:underline}
.breadcrumb span{opacity:.5;flex-shrink:0}
.breadcrumb .current-title{color:#374151;word-break:break-word;overflow-wrap:anywhere;display:inline}
@media(max-width:820px){.breadcrumb{padding:2px 0}}

/* Layout */
.layout{display:grid;grid-template-columns:1fr 270px;gap:16px;align-items:start}
@media(max-width:820px){.layout{grid-template-columns:1fr}}
@media(max-width:820px){.sidebar{display:none}}

/* Banner Overlap Fix */
.mobile-apply-top-banner{position:fixed;top:0;left:0;right:0;z-index:2000 !important;background:#fff;border-bottom:1.5px solid #2563eb;padding:8px 12px;display:none;align-items:center;gap:12px;box-shadow:0 2px 10px rgba(0,0,0,0.1)}
body.has-banner header{top:58px !important} /* Adjust header when banner is visible */

/* Cards */
.card{background:#fff;border:0.5px solid #e5e7eb;border-radius:12px;padding:18px 20px;margin-bottom:12px}
.sec-title{font-size:14px;font-weight:600;color:#111827;margin-bottom:12px;padding-bottom:10px;border-bottom:0.5px solid #f3f4f6}
@media(max-width:820px){.card{padding:14px 16px;border-radius:10px;margin-bottom:10px}}
@media(max-width:820px){.sec-title{font-size:13px;margin-bottom:10px}}

/* Header card */
.hdr-top{display:flex;gap:12px;align-items:flex-start;margin-bottom:14px}
.org-logo{min-width:48px;width:48px;height:48px;border-radius:9px;background:#e1f5ee;border:0.5px solid #9fe1cb;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:600;color:#085041;text-align:center;line-height:1.3;flex-shrink:0}
.hdr-title{font-size:18px;font-weight:600;color:#111827;margin-bottom:5px;line-height:1.35}
.hdr-org{font-size:13px;color:#6b7280;margin-bottom:8px}
.badges{display:flex;gap:5px;flex-wrap:wrap}
.badge{font-size:10.5px;font-weight:500;padding:3px 8px;border-radius:10px;white-space:nowrap}
.b-govt{background:#e1f5ee;color:#085041}
.b-new{background:#eaf3de;color:#27500a}
.b-warn{background:#faeeda;color:#633806}
.b-info{background:#e6f1fb;color:#0c447c}
.b-purple{background:#f3e8ff;color:#6b21a8}
@media(max-width:820px){.hdr-title{font-size:15px;line-height:1.35;margin-bottom:4px}}
@media(max-width:820px){.hdr-org{font-size:12px;margin-bottom:6px}}
@media(max-width:820px){.org-logo{min-width:40px;width:40px;height:40px;font-size:8px}}

.hdr-meta{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;border-top:0.5px solid #f3f4f6;padding-top:14px;margin-top:2px}
@media(max-width:820px){.hdr-meta{grid-template-columns:1fr 1fr;gap:8px;padding-top:12px}}
.meta-item{display:flex;flex-direction:column;gap:3px}
.meta-label{font-size:10px;color:#9ca3af;text-transform:uppercase;letter-spacing:.04em}
.meta-val{font-size:13px;font-weight:600;color:#111827}
@media(max-width:820px){.meta-val{font-size:12px}}

/* Row table */
.row-table{width:100%;border-collapse:collapse;font-size:13px}
.row-table tr td{padding:8px 0;border-bottom:0.5px solid #f3f4f6;vertical-align:top}
.row-table tr:last-child td{border-bottom:none}
.row-table td:first-child{color:#6b7280;width:42%;padding-right:10px;font-size:12px}
.row-table td:last-child{color:#111827;font-weight:500;font-size:12px}
@media(max-width:400px){.row-table td:first-child{width:38%;font-size:11px}.row-table td:last-child{font-size:11px}}

/* List items */
.list-items{display:flex;flex-direction:column;gap:8px}
.list-item{display:flex;gap:10px;font-size:13px;color:#374151;align-items:flex-start;line-height:1.6}
.dot{width:6px;height:6px;border-radius:50%;background:#1d9e75;flex-shrink:0;margin-top:6px}
@media(max-width:820px){.list-item{font-size:12px}}

/* Dates grid */
.dates-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}
@media(max-width:380px){.dates-grid{grid-template-columns:1fr}}
.date-card{background:#f9fafb;border-radius:8px;padding:12px 14px;border:0.5px solid #e5e7eb}
.date-label{font-size:10px;color:#9ca3af;margin-bottom:4px;text-transform:uppercase;letter-spacing:.03em}
.date-val{font-size:13px;font-weight:600;color:#111827}
.date-card.urgent{background:#fff5f5;border-color:#fecaca}
.date-card.urgent .date-val{color:#b91c1c}
.date-card.urgent .date-label{color:#b91c1c}
@media(max-width:820px){.date-card{padding:10px 12px}}
@media(max-width:820px){.date-val{font-size:12px}}

/* Links grid */
.links-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px}
@media(max-width:540px){.links-grid{grid-template-columns:1fr}}
.link-btn{display:flex;align-items:center;justify-content:space-between;gap:8px;padding:10px 14px;background:#f0fdf4;border:0.5px solid #bbf7d0;border-radius:8px;text-decoration:none;transition:.2s;cursor:pointer}
.link-btn:hover{background:#dcfce7;border-color:#4ade80}
.link-btn-label{font-size:13px;font-weight:600;color:#065f46;overflow:hidden;white-space:nowrap;text-overflow:ellipsis}
.link-arrow{color:#10b981;flex-shrink:0}

/* Content body */
.post-content-body{font-size:14px;line-height:1.75;color:#374151;word-break:break-word;overflow-wrap:break-word;overflow-x:hidden;width:100%;box-sizing:border-box}
/* Force ALL child elements — including those with inline width/style resets — to stay contained */
.post-content-body *{max-width:100% !important;word-break:break-word !important;overflow-wrap:break-word !important;box-sizing:border-box !important}
.post-content-body [style*="width"]{width:auto !important;max-width:100% !important}
.post-content-body [style*="min-width"]{min-width:0 !important}
.post-content-body h1,.post-content-body h2,.post-content-body h3,.post-content-body h4{font-weight:700;color:#111827;margin-top:1.5em;margin-bottom:.65em;line-height:1.3;word-break:break-word;white-space:normal !important}
.post-content-body h2{font-size:1.2rem;padding:10px 14px;background:#eff6ff;border-left:4px solid #2563eb;border-radius:4px;margin-top:1.8em}
@media(max-width:640px){.post-content-body h2{font-size:1.05rem;padding:8px 10px}}
.post-content-body h3{font-size:1.05rem;padding:8px 12px;background:#f8fafc;border-left:4px solid #3b82f6;border-radius:4px}
@media(max-width:640px){.post-content-body h3{font-size:.95rem}}
.post-content-body h4{font-size:.95rem;padding:7px 10px;background:#f8fafc;border-left:3px solid #94a3b8;border-radius:4px}
.post-content-body p{margin-bottom:1em;white-space:normal !important}
.post-content-body a{color:#2563eb;text-decoration:underline;font-weight:500;word-break:break-all}
.post-content-body ul{padding-left:1.2em;margin-bottom:1em;list-style:disc}
.post-content-body ol{padding-left:1.2em;margin-bottom:1em;list-style:decimal}
.post-content-body li{margin-bottom:.4em;line-height:1.7}
/* Tables: scroll horizontally if too wide, don't expand page */
.post-content-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch;width:100%}
.post-content-body table{width:100% !important;max-width:100% !important;border-collapse:collapse;margin:1.2em 0;font-size:12px;table-layout:fixed}
@media(max-width:640px){.post-content-body table{display:block;overflow-x:auto;-webkit-overflow-scrolling:touch;font-size:11px}}
.post-content-body table tr td,.post-content-body table tr th{border:1px solid #e2e8f0;padding:8px;word-break:break-word;overflow-wrap:break-word}
@media(max-width:640px){.post-content-body table tr td,.post-content-body table tr th{padding:5px 6px;min-width:60px}}
.post-content-body th{background:#1e3a8a;color:#fff;text-align:left;font-weight:600}
.post-content-body td{border-bottom:1px solid #f1f5f9;vertical-align:middle}
.post-content-body tr:nth-child(even) td{background:#f9fafb}
.post-content-body strong,.post-content-body b{color:#111827;font-weight:700}
.post-content-body blockquote{border-left:4px solid #e5e7eb;padding-left:.8em;margin:1.2em 0 1.2em .4em;font-style:italic;color:#6b7280}
.post-content-body .job-featured-image{margin-bottom:24px;text-align:center}
.post-content-body .job-featured-image img{border-radius:8px;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1),0 2px 4px -1px rgba(0,0,0,0.06)}
.post-content-body img{max-width:100% !important;height:auto !important;border-radius:.5rem;margin:1rem 0;display:block}
.post-content-body code{background:#f3f4f6;padding:.2em .4em;border-radius:.25rem;font-size:.85em;font-family:monospace;word-break:break-all}
.post-content-body pre{background:#1f2937;color:#f9fafb;padding:1em;border-radius:.5rem;overflow-x:auto;margin:1.2em 0}
.post-content-body pre code{background:transparent;padding:0;color:inherit}
.post-content-body hr{border:none;border-top:2px solid #e5e7eb;margin:1.8em 0}
/* Iframes (youtube embeds etc) */
.post-content-body iframe{max-width:100% !important;width:100% !important}
/* Prevent white-space:nowrap from any injected spans/divs */
.post-content-body span,.post-content-body div{white-space:normal !important;max-width:100% !important}

/* Sidebar */
.apply-card{background:#fff;border:1px solid #1d9e75;border-radius:12px;padding:18px;margin-bottom:14px}
.apply-title{font-size:15px;font-weight:600;color:#111827;margin-bottom:4px}
.apply-sub{font-size:12px;color:#6b7280;margin-bottom:14px}
.deadline-bar{background:#faeeda;border-radius:8px;padding:10px 12px;margin-bottom:14px;display:flex;align-items:center;gap:8px}
.deadline-text{font-size:12px;color:#633806;font-weight:500}
.apply-btn-main{display:block;width:100%;padding:10px;background:#0f6e56;color:#e1f5ee;border:none;border-radius:8px;font-size:14px;font-weight:600;text-align:center;cursor:pointer;text-decoration:none;margin-bottom:8px;transition:.2s}
.apply-btn-main:hover{background:#085041;color:#e1f5ee}
.share-row{display:flex;gap:10px;margin-top:12px;padding-top:12px;border-top:0.5px solid #f3f4f6;flex-wrap:wrap}
.share-btn{flex-shrink:0;padding:8px 12px;font-size:12px;border-radius:8px;border:0.5px solid #e5e7eb;background:#fff;color:#374151;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:6px;cursor:pointer;transition:all 0.2s}
@media(max-width:480px){.share-btn{flex:1;min-width:calc(50% - 10px);padding:9px 4px}}
.share-btn:hover{background:#f9fafb;transform:translateY(-1px)}

.quick-facts{background:#fff;border:0.5px solid #e5e7eb;border-radius:12px;padding:16px;margin-bottom:14px}
.qf-title{font-size:13px;font-weight:600;color:#111827;margin-bottom:10px}
.qf-row{display:flex;justify-content:space-between;align-items:center;padding:7px 0;border-bottom:0.5px solid #f3f4f6;font-size:12px}
.qf-row:last-child{border-bottom:none}
.qf-label{color:#6b7280}
.qf-val{font-weight:600;color:#111827;text-align:right;max-width:55%}

.similar-card{background:#fff;border:0.5px solid #e5e7eb;border-radius:12px;padding:14px}
.sim-title{font-size:13px;font-weight:600;margin-bottom:10px;color:#111827}
.sim-item{padding:9px 0;border-bottom:0.5px solid #f3f4f6;cursor:pointer}
.sim-item:last-child{border-bottom:none}
.sim-item-title{font-size:13px;font-weight:500;color:#2563eb;margin-bottom:3px;line-height:1.4}
.sim-item-title:hover{text-decoration:underline}
.sim-item-org{font-size:11px;color:#6b7280}
.sim-item-meta{display:flex;gap:6px;margin-top:5px;flex-wrap:wrap}
.sim-tag{font-size:10px;padding:2px 7px;border-radius:8px;background:#f9fafb;color:#6b7280;border:0.5px solid #e5e7eb}

/* Mobile sticky bottom apply bar */
.mobile-apply-bar{display:none;position:fixed;bottom:0;left:0;right:0;background:#fff;border-top:1px solid #e5e7eb;padding:10px 16px;z-index:100;gap:8px;align-items:center;justify-content:space-between}
@media(max-width:820px){.mobile-apply-bar{display:flex}}
.mobile-apply-btn{flex:1;padding:10px;background:#0f6e56;color:#e1f5ee;border:none;border-radius:8px;font-size:13px;font-weight:600;text-align:center;cursor:pointer;text-decoration:none;display:block}
.mobile-share-btn{padding:9px 14px;background:#f3f4f6;border:0.5px solid #e5e7eb;border-radius:8px;font-size:13px;cursor:pointer;text-align:center;color:#374151;display:flex;align-items:center;gap:5px}
@media(max-width:820px){.pg{padding-bottom:72px}}

/* Puc / blog content compatibility */
.puc-result a{color:#1565C0;text-decoration:none}
.puc-blog a{color:#2563eb}
</style>

{{-- Mobile-only Quick Apply Banner (90% of traffic is mobile) --}}
@if(count($importantLinks) > 0)
    @php
        $mobileApplyLink = null;
        foreach($importantLinks as $k => $v){
            $lbl = strtolower(is_array($v) ? ($v['label'] ?? $k) : $k);
            $lu  = is_array($v) ? ($v['url'] ?? $v) : $v;
            if (!$mobileApplyLink && (str_contains($lbl,'apply') || str_contains($lbl,'official') || str_contains($lbl,'register') || str_contains($lbl,'notification'))) { 
                $mobileApplyLink = $lu; 
            }
        }
        if(!$mobileApplyLink){ 
            $first = reset($importantLinks); 
            $mobileApplyLink = is_array($first) ? ($first['url'] ?? '#') : $first; 
        }
    @endphp
    <div id="top-sticky-banner" class="mobile-apply-top-banner">
        <div style="flex:1;min-width:0">
            <div style="font-size:10px;color:#6b7280;text-transform:uppercase;font-weight:700;margin-bottom:2px">{{ $typeInfo['label'] }} · {{ $post->state ? $post->state->name : 'All Nepal' }}</div>
            <div style="font-size:13px;font-weight:600;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $post->title }}</div>
        </div>
        <a href="{{ $mobileApplyLink }}" target="_blank" rel="noopener" 
           style="background:#0f6e56;color:#fff;padding:9px 14px;border-radius:8px;text-decoration:none;font-size:12px;font-weight:700;flex-shrink:0">
           {{ match($post->type) { 'job'=>'Quick Apply', 'admit_card'=>'Download', 'result'=>'Check Result', 'answer_key'=>'Download', 'syllabus'=>'Download', 'scholarship'=>'Apply Now', default=>'View' } }}
        </a>
    </div>

    <script>
        (function() {
            const banner = document.getElementById('top-sticky-banner');
            if (!banner) return;
            window.addEventListener('scroll', function() {
                if (window.scrollY > 400) {
                    banner.style.display = 'flex';
                    document.body.classList.add('has-banner');
                } else {
                    banner.style.display = 'none';
                    document.body.classList.remove('has-banner');
                }
            });
        })();
    </script>
@endif

<div class="pg">
    {{-- Breadcrumb --}}
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>›</span>
        <a href="{{ route($typeInfo['route']) }}">{{ $typeInfo['label'] }}</a>
        @if($post->state)
        <span>›</span>
        <a href="{{ route('states.show', $post->state) }}">{{ $post->state->name }}</a>
        @endif
        <span>›</span>
        <span class="current-title">{{ Str::limit($post->title, 65) }}</span>
    </div>

    <div class="layout">
        {{-- ====== MAIN COLUMN ====== --}}
        <div class="main">

            {{-- Header Card --}}
            <div class="card">
                <div class="hdr-top">
                    <div class="org-logo">{{ $orgInitials }}</div>
                    <div style="flex:1;min-width:0">
                        <h1 class="hdr-title">{{ \App\Helpers\PostHelper::cleanTitle($post->title) }}</h1>
                        @if($post->organization)
                        <div class="hdr-org">{{ $post->organization }}@if($post->state) · {{ $post->state->name }}@endif</div>
                        @endif
                        <div class="badges">
                            <span class="badge b-govt">{{ $typeInfo['label'] }}</span>
                            @if($post->is_upcoming)<span class="badge" style="background:#fff7ed;color:#c2410c;border:1px solid #fed7aa">⏳ Upcoming</span>@endif
                            @if($post->is_date_extended)<span class="badge" style="background:#fff1f2;color:#be123c;border:1px solid #fecdd3">🔥 Date Extended</span>@endif
                            @if($post->isNew())<span class="badge b-new">🔥 New</span>@endif
                            @if($isJobType && $post->last_date && $daysLeft !== null && $daysLeft >= 0)
                                <span class="badge b-warn">Apply by {{ $post->last_date->format('d M') }}</span>
                            @endif
                            @if($isJobType && $post->total_posts)
                                <span class="badge b-info">{{ number_format($post->total_posts) }} Vacancies</span>
                            @endif
                            @if($isJobType && $post->salary)
                                <span class="badge" style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0">💰 {{ Str::limit($post->salary, 30) }}</span>
                            @endif
                            @if($post->category)<span class="badge b-purple">{{ $post->category->name }}</span>@endif
                        </div>
                    </div>
                </div>

                @if($isJobType && ($post->total_posts || $post->salary || $eduLabels || ($post->last_date && $daysLeft !== null)))
                <div class="hdr-meta">
                    @if($post->total_posts)
                    <div class="meta-item">
                        <span class="meta-label">Total Vacancies</span>
                        <span class="meta-val">{{ number_format($post->total_posts) }} Posts</span>
                    </div>
                    @endif
                    @if($post->salary)
                    <div class="meta-item">
                        <span class="meta-label">💰 {{ $post->type === 'scholarship' ? 'Amount' : 'Salary / Pay' }}</span>
                        <span class="meta-val" style="color:#166534">{{ $post->salary }}</span>
                    </div>
                    @endif
                    @if($isJobType && $eduLabels)
                    <div class="meta-item">
                        <span class="meta-label">Qualification</span>
                        <span class="meta-val">{{ implode(' / ', array_slice($eduLabels, 0, 2)) }}</span>
                    </div>
                    @endif
                    @if($post->last_date && $daysLeft !== null)
                    <div class="meta-item">
                        <span class="meta-label">Last Date</span>
                        <span class="meta-val" @if($daysLeft < 10 && $daysLeft >= 0) style="color:#b91c1c" @endif>{{ $lastDateStr }}</span>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            {{-- Featured Image (scraped from official source) --}}
            @if($post->featured_image)
            <div class="card" style="padding:0;overflow:hidden;border-radius:12px;background:#f8f9fa;">
                <img src="{{ $post->featured_image }}"
                     alt="{{ $post->title }}"
                     style="width:100%;height:auto;max-height:600px;object-fit:contain;display:block;border-radius:12px"
                     loading="lazy"
                     onerror="this.parentElement.style.display='none'">
            </div>
            @endif

            {{-- Important Dates Card --}}
            @if($post->notification_date || $post->start_date || $post->end_date || $post->last_date)
            <div class="card">
                <div class="sec-title">📅 Important Dates</div>
                <div class="dates-grid">
                    @if($post->notification_date)
                    <div class="date-card">
                        <div class="date-label">Notification Date</div>
                        <div class="date-val">{{ $post->notification_date->format('d M Y') }}</div>
                    </div>
                    @endif
                    @if($post->start_date)
                    <div class="date-card" style="background:#f0fdf4;border-color:#bbf7d0">
                        <div class="date-label" style="color:#166534">{{ $isJobType ? '🟢 Form Start Date' : '🟢 Start Date' }}</div>
                        <div class="date-val" style="color:#166534">{{ $post->start_date->format('d M Y') }}</div>
                    </div>
                    @endif
                    @if($post->end_date)
                    @php $endDays = now()->startOfDay()->diffInDays($post->end_date->startOfDay(), false); @endphp
                    <div class="date-card @if($endDays !== null && $endDays <= 10 && $endDays >= 0) urgent @endif">
                        <div class="date-label">{{ $isJobType ? '🔴 Form End Date' : '🔴 End Date' }}</div>
                        <div class="date-val">{{ $post->end_date->format('d M Y') }}
                            @if($endDays !== null && $endDays >= 0 && $endDays <= 30)
                                <span style="font-size:11px;font-weight:500;color:#b91c1c"> · {{ $endDays }} days left</span>
                            @elseif($endDays !== null && $endDays < 0)
                                <span style="font-size:11px;font-weight:500;color:#6b7280"> · Closed</span>
                            @endif
                        </div>
                    </div>
                    @endif
                    @if($post->last_date)
                    <div class="date-card @if($daysLeft !== null && $daysLeft <= 10 && $daysLeft >= 0) urgent @endif">
                        <div class="date-label">{{ $isJobType ? '⏰ Last Date to Apply' : '⏰ Last Date' }}</div>
                        <div class="date-val">{{ $post->last_date->format('d M Y') }}
                            @if($daysLeft !== null && $daysLeft >= 0 && $daysLeft <= 30)
                                <span style="font-size:11px;font-weight:500;color:#b91c1c"> · {{ $daysLeft }} days left</span>
                            @elseif($daysLeft !== null && $daysLeft < 0)
                                <span style="font-size:11px;font-weight:500;color:#6b7280"> · Expired</span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Quick info card --}}
            @if($post->organization || $post->total_posts || $post->salary || $post->state || ($isJobType && $eduLabels))
            <div class="card">
                <div class="sec-title">{{ $quickInfoTitle }}</div>
                <table class="row-table">
                    @if($post->organization)
                    <tr><td>Organisation</td><td>{{ $post->organization }}</td></tr>
                    @endif
                    @if($isJobType && $post->total_posts)
                    <tr><td>Total Vacancies</td><td>{{ number_format($post->total_posts) }} posts</td></tr>
                    @endif
                    @if($isJobType && ($post->age_min || $post->age_max_gen || $post->age_as_on_date))
                    <tr><td>Age Limit</td><td>{{ $post->age_min ? $post->age_min . ' - ' : 'Max ' }}{{ $post->age_max_gen ?: 'NA' }} Years {{ $post->age_as_on_date ? '(As on ' . \Carbon\Carbon::parse($post->age_as_on_date)->format('d M Y') . ')' : '' }}</td></tr>
                    @endif
                    @if($post->salary)
                    <tr><td>💰 {{ $post->type === 'scholarship' ? 'Scholarship Amount' : (($post->salary_type ?? '') === 'stipend' ? 'Stipend' : 'Salary / Pay Scale') }}</td><td style="color:#166534;font-weight:600">{{ $post->salary }}</td></tr>
                    @endif
                    @if($isJobType && ($post->fee_general !== null || $post->fee_obc !== null || $post->fee_sc_st !== null || $post->fee_women !== null))
                    <tr><td>Application Fee</td><td>Gen/UR: ₹{{ $post->fee_general ?? '0' }}
                        @if($post->fee_obc !== null) | OBC/EWS: ₹{{ $post->fee_obc }}@endif
                        @if($post->fee_sc_st !== null) | SC/ST: ₹{{ $post->fee_sc_st }}@endif
                        @if($post->fee_women !== null) | Women: ₹{{ $post->fee_women }}@endif
                        @if($post->fee_payment_mode) <br><span style="font-size:11px;color:#6b7280">Mode: {{ $post->fee_payment_mode }}</span>@endif
                    </td></tr>
                    @endif
                    @if($post->state)
                    <tr><td>{{ $isJobType ? 'State' : 'Location' }}</td><td><a href="{{ route('states.show', $post->state) }}" style="color:#2563eb;text-decoration:underline">{{ $post->state->name }}</a></td></tr>
                    @elseif($isJobType)
                    <tr><td>Job Location</td><td>All Nepal</td></tr>
                    @endif
                    @if($post->category)
                    <tr><td>Category</td><td>{{ $post->category->name }}</td></tr>
                    @endif
                    @if($isJobType && $eduLabels)
                    <tr><td>Education Required</td><td>{{ implode(', ', $eduLabels) }}</td></tr>
                    @endif
                    @if($post->tags && count($post->tags) > 0)
                    <tr><td>Tags</td><td>{{ implode(', ', array_map(fn($t) => ucwords(str_replace('_',' ',$t)), $post->tags)) }}</td></tr>
                    @endif
                    <tr><td>Post Type</td><td>{{ ucwords(str_replace('_',' ', $post->type)) }}</td></tr>
                    <tr><td>Updated</td><td>{{ $post->updated_at->format('d M Y') }}</td></tr>
                </table>
            </div>
            @endif

            {{-- Qualifications & Duties --}}
            @if($isJobType && ($post->qualifications || $post->skills || $post->responsibilities))
            <div class="card" style="background:#f8fafc; border-left:4px solid #3b82f6;">
                <div class="sec-title" style="border-bottom-color:#e2e8f0;">🎯 Qualifications & Responsibilities</div>
                <div class="list-items" style="gap:14px;">
                    @if($post->qualifications)
                    <div><strong style="color:#1e40af">Education & Experience:</strong><div style="margin-top:4px;font-size:13px;line-height:1.6;color:#334155">{{ $post->qualifications }}</div></div>
                    @endif
                    @if($post->skills)
                    <div><strong style="color:#1e40af">Key Skills:</strong><div style="margin-top:4px;font-size:13px;line-height:1.6;color:#334155">{{ $post->skills }}</div></div>
                    @endif
                    @if($post->responsibilities)
                    <div><strong style="color:#1e40af">Role & Duties:</strong><div style="margin-top:4px;font-size:13px;line-height:1.6;color:#334155">{{ $post->responsibilities }}</div></div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Main Content --}}
            <div class="card" style="overflow-x:hidden">
                <div class="sec-title">{{ $contentTitle }}</div>
                <div class="post-content-wrap">
                    <div class="post-content-body">
                        @php
                            // Remove "Stay Updated" section from content
                            $cleanContent = $post->content;
                            $cleanContent = preg_replace('/<h3[^>]*>📢\s*Stay Updated<\/h3>.*?(<h3|$)/is', '$1', $cleanContent);
                            $cleanContent = preg_replace('/<h3[^>]*>Stay Updated<\/h3>.*?(<h3|$)/is', '$1', $cleanContent);
                        @endphp
                        {!! $cleanContent !!}
                    </div>
                </div>
                
                {{-- Share Buttons --}}
                <div style="margin-top:24px;padding-top:20px;border-top:1px solid #e5e7eb">
                    <div style="font-size:14px;font-weight:700;color:#374151;margin-bottom:12px;display:flex;align-items:center;gap:8px">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92-1.31-2.92-2.92-2.92z"/></svg>
                        Share This Post
                    </div>
                    <div style="display:flex;flex-wrap:wrap;gap:10px">
                        {{-- WhatsApp --}}
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' - ' . route('posts.show', [$post->type, $post->slug])) }}" 
                           target="_blank" rel="noopener noreferrer"
                           style="display:inline-flex;align-items:center;gap:8px;padding:10px 16px;background:#25D366;color:#fff;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600;transition:all 0.2s"
                           onmouseover="this.style.background='#128C7E'" onmouseout="this.style.background='#25D366'">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            WhatsApp
                        </a>
                        
                        {{-- Telegram --}}
                        <a href="https://t.me/share/url?url={{ urlencode(route('posts.show', [$post->type, $post->slug])) }}&text={{ urlencode($post->title) }}" 
                           target="_blank" rel="noopener noreferrer"
                           style="display:inline-flex;align-items:center;gap:8px;padding:10px 16px;background:#0088cc;color:#fff;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600;transition:all 0.2s"
                           onmouseover="this.style.background='#006699'" onmouseout="this.style.background='#0088cc'">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                            Telegram
                        </a>
                        
                        {{-- Facebook --}}
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('posts.show', [$post->type, $post->slug])) }}" 
                           target="_blank" rel="noopener noreferrer"
                           style="display:inline-flex;align-items:center;gap:8px;padding:10px 16px;background:#1877F2;color:#fff;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600;transition:all 0.2s"
                           onmouseover="this.style.background='#145dbf'" onmouseout="this.style.background='#1877F2'">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            Facebook
                        </a>
                        
                        {{-- Twitter/X --}}
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(route('posts.show', [$post->type, $post->slug])) }}" 
                           target="_blank" rel="noopener noreferrer"
                           style="display:inline-flex;align-items:center;gap:8px;padding:10px 16px;background:#000;color:#fff;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600;transition:all 0.2s"
                           onmouseover="this.style.background='#333'" onmouseout="this.style.background='#000'">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            Twitter
                        </a>
                        
                        {{-- Copy Link --}}
                        <button onclick="copyToClipboard('{{ route('posts.show', [$post->type, $post->slug]) }}')" 
                                style="display:inline-flex;align-items:center;gap:8px;padding:10px 16px;background:#6b7280;color:#fff;border:none;border-radius:8px;cursor:pointer;font-size:13px;font-weight:600;transition:all 0.2s"
                                onmouseover="this.style.background='#4b5563'" onmouseout="this.style.background='#6b7280'">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            Copy Link
                        </button>
                    </div>
                </div>
            </div>
            
            <script>
            function copyToClipboard(text) {
                navigator.clipboard.writeText(text).then(function() {
                    alert('✅ Link copied to clipboard!');
                }, function(err) {
                    console.error('Could not copy text: ', err);
                });
            }
            </script>

            {{-- Online Apply + Final Result CTA --}}
            @if($post->online_form || $post->final_result)
            <div class="card" style="background:linear-gradient(135deg,#f0fdf4,#eff6ff);border-color:#bbf7d0">
                <div class="sec-title" style="border-color:#d1fae5">🚀 Quick Action Links</div>
                <div class="links-grid">
                    @if($post->online_form)
                    <a href="{{ $post->online_form }}" target="_blank" rel="noopener noreferrer"
                       style="display:flex;align-items:center;justify-content:space-between;gap:8px;padding:12px 16px;background:#0f6e56;border-radius:10px;text-decoration:none;color:#fff;font-weight:700;font-size:13px">
                        <span>{{ match($post->type) { 'job'=>'✅ Apply Online', 'admit_card'=>'🎟️ Download Admit Card', 'result'=>'🏆 Check Result', 'answer_key'=>'🔑 Download Answer Key', 'syllabus'=>'📚 Download Syllabus', 'scholarship'=>'✅ Apply Online', default=>'🔗 View Official Link' } }}</span>
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    @endif
                    @if($post->final_result)
                    <a href="{{ $post->final_result }}" target="_blank" rel="noopener noreferrer"
                       style="display:flex;align-items:center;justify-content:space-between;gap:8px;padding:12px 16px;background:#1d4ed8;border-radius:10px;text-decoration:none;color:#fff;font-weight:700;font-size:13px">
                        <span>🏆 Final Result</span>
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    @endif
                </div>
            </div>
            @endif

            {{-- Important Links (from JSON) --}}
            @if(count($importantLinks) > 0)
            <div class="card">
                <div class="sec-title">🔗 Important Links</div>
                <div class="links-grid">
                    @foreach($importantLinks as $key => $value)
                        @php
                            $linkUrl   = is_array($value) ? ($value['url'] ?? $value['link'] ?? '#') : $value;
                            $linkLabel = is_array($value) ? ($value['label'] ?? $value['title'] ?? ucwords(str_replace('_',' ',$key))) : ucwords(str_replace('_',' ',$key));
                            $linkLabel = trim($linkLabel) ?: 'View Link';
                            if (empty(trim($linkUrl)) || $linkUrl === '#') continue;
                        @endphp
                        <a href="{{ $linkUrl }}" target="_blank" rel="noopener noreferrer" class="link-btn">
                            <span class="link-btn-label">{{ $linkLabel }}</span>
                            <svg class="link-arrow" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            @if($post->type === 'job')
            <div class="card">
                <div class="sec-title">📝 How to Apply</div>
                <div class="list-items">
                    <div class="list-item"><div class="dot"></div><span>Visit the official website of the recruiting organisation.</span></div>
                    @if($post->online_form)
                    <div class="list-item"><div class="dot"></div><span>Click <strong>"Apply Online"</strong> — direct link available above in Quick Action Links.</span></div>
                    @else
                    <div class="list-item"><div class="dot"></div><span>Find the recruitment notification and click "Apply Online".</span></div>
                    @endif
                    <div class="list-item"><div class="dot"></div><span>Register using your mobile number and email address.</span></div>
                    <div class="list-item"><div class="dot"></div><span>Fill in your personal, educational and category details.</span></div>
                    <div class="list-item"><div class="dot"></div><span>Upload required documents (photo, signature) in specified formats.</span></div>
                    <div class="list-item"><div class="dot"></div><span>Pay the application fee online and submit; save printout for records.</span></div>
                    @if($post->end_date)
                    <div class="list-item"><div class="dot" style="background:#b91c1c"></div><span>⚠️ Form closes on <strong>{{ $post->end_date->format('d M Y') }}</strong> — don't miss the deadline!</span></div>
                    @endif
                </div>
            </div>
            @endif

            @php
    $encodedUrl = urlencode($postUrl);

    // Build rich share message with all key job data
    $shareLines = [];
    $shareLines[] = '🔔 *' . $post->title . '*';
    if ($post->organization) $shareLines[] = '🏛️ ' . $post->organization;
    if ($post->state)        $shareLines[] = '📍 ' . $post->state->name;
    if ($post->total_posts)  $shareLines[] = '📋 Vacancies: ' . number_format($post->total_posts);
    if ($post->salary)       $shareLines[] = '💰 Salary: ' . $post->salary;
    if ($eduLabels)          $shareLines[] = '🎓 Qualification: ' . implode(' / ', array_slice($eduLabels, 0, 2));
    if ($lastDateStr)        $shareLines[] = '⏰ Last Date: ' . $lastDateStr . ($daysLeft !== null && $daysLeft >= 0 && $daysLeft <= 30 ? ' (' . $daysLeft . ' days left!)' : '');
    $shareLines[] = '';
    $shareLines[] = match($post->type) {
        'admit_card'  => '🎟️ Download Admit Card:',
        'result'      => '🏆 Check Result:',
        'answer_key'  => '🔑 Download Answer Key:',
        'syllabus'    => '📚 Download Syllabus:',
        'scholarship' => '✅ Apply for Scholarship:',
        'blog'        => '📖 Read Article:',
        default       => '✅ Apply Now:',
    };
    $shareLines[] = $postUrl;
    $shareLines[] = '';
    $shareLines[] = '📲 More Govt Jobs: http://13.206.244.237';

    $shareMsg     = implode("\n", $shareLines);
    $encodedTitle = urlencode($shareMsg);
@endphp
            <div class="card" style="border-color:#e0f2fe;background:linear-gradient(135deg,#f0fdf4,#eff6ff)">
                <div class="sec-title">📲 Share this Post</div>
                @if($post->featured_image)
                <div style="margin-bottom:12px;border-radius:8px;overflow:hidden;max-height:160px">
                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" style="width:100%;height:160px;object-fit:cover;border-radius:8px" loading="lazy">
                </div>
                @endif
                <div style="font-size:12px;color:#374151;background:#fff;border-radius:8px;padding:10px 12px;margin-bottom:10px;border:0.5px solid #e5e7eb;line-height:1.8">
                    <strong style="color:#0f6e56">{{ $post->title }}</strong><br>
                    @if($post->organization)<span>🏛️ {{ $post->organization }}</span><br>@endif
                    @if($post->total_posts)<span>📋 {{ number_format($post->total_posts) }} Vacancies</span> &nbsp;@endif
                    @if($post->salary)<span>💰 {{ $post->salary }}</span><br>@endif
                    @if($lastDateStr)<span style="color:#b91c1c">⏰ Last Date: {{ $lastDateStr }}</span>@endif
                </div>
                <div class="share-row">
                    <a href="https://wa.me/?text={{ $encodedTitle }}" target="_blank" rel="noopener" class="share-btn" style="background:#dcfce7;color:#166534;border-color:#bbf7d0;font-weight:600">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                    <a href="https://t.me/share/url?url={{ $encodedUrl }}&text={{ $encodedTitle }}" target="_blank" rel="noopener" class="share-btn" style="background:#e0f2fe;color:#0c4a6e;border-color:#bae6fd;font-weight:600">
                        <i class="fab fa-telegram"></i> Telegram
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}" target="_blank" rel="noopener" class="share-btn" style="background:#eff6ff;color:#1e40af;border-color:#bfdbfe">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <button onclick="navigator.clipboard.writeText('{{ $postUrl }}').then(()=>this.textContent='✅ Copied!')" class="share-btn">
                        📋 Copy link
                    </button>
                </div>
            </div>

            {{-- Related Posts - Rich Data Cards --}}
            @if($related->count() > 0)
            <div class="card">
                <div class="sec-title">📌 Related {{ $typeInfo['label'] }}</div>
                <div style="display:flex;flex-direction:column;gap:0">
                @foreach($related as $rel)
                @php
                    $relDaysLeft = null;
                    if ($rel->last_date) {
                        $relDaysLeft = now()->startOfDay()->diffInDays($rel->last_date->startOfDay(), false);
                    }
                    $relWords    = explode(' ', $rel->organization ?? 'GOV');
                    $relInitials = '';
                    foreach(array_slice($relWords, 0, 2) as $rw) { $relInitials .= strtoupper(substr($rw,0,2)); }
                @endphp
                <a href="{{ route('posts.show', [$rel->type, $rel->slug]) }}" style="display:block;text-decoration:none;padding:10px 0;border-bottom:0.5px solid #f3f4f6" class="rel-card-link">
                    <div style="display:flex;gap:10px;align-items:flex-start">
                        {{-- Image or Initials Box --}}
                        @if($rel->featured_image)
                        <img src="{{ $rel->featured_image }}" alt="{{ $rel->title }}" style="width:56px;height:56px;border-radius:8px;object-fit:cover;flex-shrink:0;border:0.5px solid #e5e7eb" loading="lazy">
                        @else
                        <div style="width:56px;height:56px;border-radius:8px;background:#e1f5ee;border:0.5px solid #9fe1cb;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:700;color:#085041;text-align:center;flex-shrink:0;line-height:1.2">{{ $relInitials }}</div>
                        @endif
                        <div style="flex:1;min-width:0">
                            <div style="font-size:13px;font-weight:600;color:#1d4ed8;line-height:1.35;margin-bottom:3px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical">{{ Str::limit($rel->title, 80) }}</div>
                            @if($rel->organization)
                            <div style="font-size:11px;color:#6b7280;margin-bottom:5px">{{ Str::limit($rel->organization, 45) }}</div>
                            @endif
                            <div style="display:flex;flex-wrap:wrap;gap:4px;align-items:center">
                                <span style="font-size:10px;padding:2px 6px;border-radius:6px;background:#e1f5ee;color:#085041;font-weight:500">{{ ucwords(str_replace('_',' ',$rel->type)) }}</span>
                                @if($rel->state)<span style="font-size:10px;padding:2px 6px;border-radius:6px;background:#eff6ff;color:#1e40af">{{ $rel->state->name }}</span>@endif
                                @if($rel->total_posts)<span style="font-size:10px;padding:2px 6px;border-radius:6px;background:#f0fdf4;color:#166534">{{ number_format($rel->total_posts) }} posts</span>@endif
                                @if($rel->salary)<span style="font-size:10px;padding:2px 6px;border-radius:6px;background:#fefce8;color:#854d0e">💰 {{ Str::limit($rel->salary, 20) }}</span>@endif
                                @if($rel->last_date)
                                    @php $relDl = $relDaysLeft; @endphp
                                    <span style="font-size:10px;padding:2px 6px;border-radius:6px;background:{{ ($relDl !== null && $relDl <= 7 && $relDl >= 0) ? '#fff1f2' : '#f9fafb' }};color:{{ ($relDl !== null && $relDl <= 7 && $relDl >= 0) ? '#be123c' : '#6b7280' }}">⏰ {{ $rel->last_date->format('d M Y') }}{{ ($relDl !== null && $relDl >= 0 && $relDl <= 15) ? ' · '.$relDl.'d left' : '' }}{{ ($relDl !== null && $relDl < 0) ? ' · Closed' : '' }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
                </div>
            </div>
            @endif
            <style>.rel-card-link:hover{background:#f9fafb;margin:0 -4px;padding-left:4px;padding-right:4px;border-radius:8px}</style>

        </div>

        {{-- ====== SIDEBAR ====== --}}
        <div class="sidebar">

            {{-- Apply Card --}}
            <div class="apply-card">
                <div class="apply-title">{{ match($post->type) { 'job' => 'Apply for this Job', 'admit_card' => 'Download Admit Card', 'result' => 'Check Your Result', 'answer_key' => 'Download Answer Key', 'syllabus' => 'View Syllabus', 'scholarship' => 'Apply for Scholarship', default => 'View ' . $typeInfo['label'] } }}</div>
                @if($post->organization)
                <div class="apply-sub">{{ $post->organization }}</div>
                @endif

                @if($post->last_date && $daysLeft !== null)
                <div class="deadline-bar">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="6.5" stroke="#BA7517" stroke-width="1.2"/><path d="M8 5v3.5l2 1.5" stroke="#BA7517" stroke-width="1.2" stroke-linecap="round"/></svg>
                    <span class="deadline-text">
                        @if($daysLeft > 0)
                            Closes in {{ $daysLeft }} days — {{ $lastDateStr }}
                        @elseif($daysLeft === 0)
                            Closes TODAY — {{ $lastDateStr }}
                        @else
                            Closed on {{ $lastDateStr }}
                        @endif
                    </span>
                </div>
                @endif

                @if($post->salary)
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:10px 12px;margin-bottom:12px;display:flex;align-items:center;gap:8px">
                    <span style="font-size:18px">💰</span>
                    <div>
                        <div style="font-size:10px;color:#6b7280;text-transform:uppercase;letter-spacing:.04em">{{ $post->type === 'scholarship' ? 'Scholarship Amount' : (($post->salary_type ?? '') === 'stipend' ? 'Stipend' : 'Salary / Pay Scale') }}</div>
                        <div style="font-size:13px;font-weight:700;color:#166534">{{ $post->salary }}</div>
                    </div>
                </div>
                @endif

                @if($directApplyLink)
                    <a href="{{ $directApplyLink }}" target="_blank" rel="noopener" class="apply-btn-main">
                        {{ $ctaLabel }}
                    </a>
                @else
                    <a href="#" class="apply-btn-main" style="opacity:.6;pointer-events:none">Link in details below ↓</a>
                @endif

                @if($post->final_result)
                <a href="{{ $post->final_result }}" target="_blank" rel="noopener"
                   style="display:block;width:100%;padding:10px;background:#1d4ed8;color:#fff;border-radius:8px;font-size:13px;font-weight:600;text-align:center;text-decoration:none;margin-top:8px">
                    🏆 View Final Result
                </a>
                @endif

                <div class="share-row">
                    <a href="https://wa.me/?text={{ $encodedTitle }}" target="_blank" rel="noopener" class="share-btn">
                        <i class="fab fa-whatsapp" style="color:#166534"></i> <span>WhatsApp</span>
                    </a>
                    <a href="https://t.me/share/url?url={{ $encodedUrl }}&text={{ $encodedTitle }}" target="_blank" rel="noopener" class="share-btn">
                        <i class="fab fa-telegram" style="color:#0c4a6e"></i> <span>Telegram</span>
                    </a>
                    <button onclick="navigator.clipboard.writeText('{{ $postUrl }}').then(()=>this.textContent='✅')" class="share-btn">📋</button>
                </div>
            </div>

            {{-- Quick Facts --}}
            <div class="quick-facts">
                <div class="qf-title">Quick Facts</div>
                @if($post->organization)
                <div class="qf-row"><span class="qf-label">Organisation</span><span class="qf-val">{{ Str::limit($post->organization, 30) }}</span></div>
                @endif
                @if($post->category)
                <div class="qf-row"><span class="qf-label">Category</span><span class="qf-val">{{ $post->category->name }}</span></div>
                @endif
                @if($isJobType && ($post->total_posts))
                <div class="qf-row"><span class="qf-label">Vacancies</span><span class="qf-val">{{ number_format($post->total_posts) }}</span></div>
                @endif
                @if($isJobType && ($post->age_min || $post->age_max_gen))
                <div class="qf-row"><span class="qf-label">Age Limit</span><span class="qf-val">{{ $post->age_min ? $post->age_min . '-' : 'Max ' }}{{ $post->age_max_gen ?: 'NA' }} Yrs</span></div>
                @endif
                @if($post->salary)
                <div class="qf-row"><span class="qf-label">{{ $post->type === 'scholarship' ? '🎓 Amount' : (($post->salary_type ?? '') === 'stipend' ? '💰 Stipend' : '💰 Salary') }}</span><span class="qf-val" style="color:#166534">{{ Str::limit($post->salary, 28) }}</span></div>
                @endif
                @if($post->state)
                <div class="qf-row"><span class="qf-label">{{ $isJobType ? 'Work State' : 'Location' }}</span><span class="qf-val">{{ $post->state->name }}</span></div>
                @elseif($isJobType)
                <div class="qf-row"><span class="qf-label">Location</span><span class="qf-val">All Nepal</span></div>
                @endif
                @if($isJobType && $eduLabels)
                <div class="qf-row"><span class="qf-label">Education</span><span class="qf-val">{{ implode(', ', array_slice($eduLabels, 0, 2)) }}</span></div>
                @endif
                @if($post->start_date)
                <div class="qf-row"><span class="qf-label">Form Start</span><span class="qf-val" style="color:#166534">{{ $post->start_date->format('d M Y') }}</span></div>
                @endif
                @if($post->end_date)
                <div class="qf-row"><span class="qf-label">Form End</span><span class="qf-val" style="color:#b91c1c">{{ $post->end_date->format('d M Y') }}</span></div>
                @endif
                @if($post->notification_date)
                <div class="qf-row"><span class="qf-label">Notif. Date</span><span class="qf-val">{{ $post->notification_date->format('d M Y') }}</span></div>
                @endif
                @if($post->last_date)
                <div class="qf-row"><span class="qf-label">Last Date</span><span class="qf-val" @if($daysLeft !== null && $daysLeft <= 10 && $daysLeft >=0) style="color:#b91c1c" @endif>{{ $lastDateStr }}</span></div>
                @endif
                <div class="qf-row"><span class="qf-label">Post Type</span><span class="qf-val">{{ ucwords(str_replace('_',' ',$post->type)) }}</span></div>
                <div class="qf-row"><span class="qf-label">Views</span><span class="qf-val">{{ number_format($post->view_count) }}</span></div>
            </div>

            {{-- Similar Jobs - with image thumbnails --}}
            @if($related->count() > 0)
            <div class="similar-card">
                <div class="sim-title">Similar {{ $typeInfo['label'] }}</div>
                @foreach($related->take(4) as $rel)
                @php
                    $sdw = explode(' ', $rel->organization ?? 'GOV');
                    $sdi = '';
                    foreach(array_slice($sdw, 0, 2) as $sw) { $sdi .= strtoupper(substr($sw,0,2)); }
                @endphp
                <div class="sim-item">
                    <div style="display:flex;gap:8px;align-items:flex-start">
                        @if($rel->featured_image)
                        <a href="{{ route('posts.show', [$rel->type, $rel->slug]) }}"><img src="{{ $rel->featured_image }}" alt="{{ $rel->title }}" style="width:44px;height:44px;border-radius:6px;object-fit:cover;flex-shrink:0;border:0.5px solid #e5e7eb" loading="lazy"></a>
                        @else
                        <a href="{{ route('posts.show', [$rel->type, $rel->slug]) }}" style="display:flex;width:44px;height:44px;border-radius:6px;background:#e1f5ee;border:0.5px solid #9fe1cb;align-items:center;justify-content:center;font-size:8px;font-weight:700;color:#085041;text-align:center;flex-shrink:0;text-decoration:none">{{ $sdi }}</a>
                        @endif
                        <div style="flex:1;min-width:0">
                            <a href="{{ route('posts.show', [$rel->type, $rel->slug]) }}" class="sim-item-title" style="display:block;text-decoration:none">{{ Str::limit($rel->title, 60) }}</a>
                            <div class="sim-item-org">{{ Str::limit($rel->organization ?? ucwords(str_replace('_',' ',$rel->type)), 35) }}</div>
                            <div class="sim-item-meta">
                                @if($rel->total_posts)<span class="sim-tag">{{ number_format($rel->total_posts) }} posts</span>@endif
                                @if($rel->salary)<span class="sim-tag" style="color:#166534">{{ Str::limit($rel->salary,18) }}</span>@endif
                                @if($rel->last_date)<span class="sim-tag">Last: {{ $rel->last_date->format('d M') }}</span>@endif
                                @if($rel->state)<span class="sim-tag">{{ $rel->state->name }}</span>@endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>
</div>

{{-- Mobile Sticky Bottom Bar --}}
<div class="mobile-apply-bar">
    @if($directApplyLink)
    <a href="{{ $directApplyLink }}" target="_blank" rel="noopener" class="mobile-apply-btn">
        {{ $ctaLabelMobile }}
    </a>
    @endif
    @if($post->final_result)
    <a href="{{ $post->final_result }}" target="_blank" rel="noopener" class="mobile-apply-btn" style="background:#1d4ed8">
        🏆 Result
    </a>
    @endif
    <a href="https://wa.me/?text={{ $encodedTitle }}" target="_blank" rel="noopener" class="mobile-share-btn">
        <i class="fab fa-whatsapp" style="color:#166534"></i> Share
    </a>
</div>

<x-ad-slot position="after_post" />

<script>
// Handle external app opening for WebView
function openExternalApp(app, data) {
    var isWebView = /WebView|wv|\.0\.0\.0|Version\/[\d.]+(?!.*Safari)/.test(navigator.userAgent);
    if (isWebView) {
        var url = '';
        if (app === 'whatsapp') url = 'whatsapp://send?text=' + data;
        else if (app === 'telegram') url = 'tg://msg?text=' + data;
        if (url) {
            window.location.href = url;
            setTimeout(function() {
                if (app === 'whatsapp') window.location.href = 'https://wa.me/?text=' + data;
                else if (app === 'telegram') window.location.href = 'https://t.me/share/url?text=' + data;
            }, 1000);
        }
    }
}

// Track page view via AJAX (works even with cached pages)
(function() {
    // Wait for page to fully load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', trackView);
    } else {
        trackView();
    }
    
    function trackView() {
        // Send view tracking request
        fetch('{{ route("posts.track-view", $post->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).catch(function(error) {
            // Silently fail - don't disrupt user experience
            console.log('View tracking failed:', error);
        });
    }
})();
</script>
@endsection

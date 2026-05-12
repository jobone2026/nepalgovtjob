<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'JobOne Admin')</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
:root {
--sidebar-w: 260px;
--topbar-h: 64px;
--white: #ffffff;
--off-white: #f9fafb;
--surface: #f3f4f6;
--border: #e5e7eb;
--border-soft: #f0f0f0;
--text-primary: #111827;
--text-secondary: #6b7280;
--text-muted: #9ca3af;
--navy: #0f172a;
--navy-hover: #1e293b;
--blue: #2563eb;
--blue-light: #eff6ff;
--blue-mid: #dbeafe;
--success: #16a34a;
--success-light: #dcfce7;
--radius: 10px;
--radius-sm: 6px;
--shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
--shadow: 0 4px 12px rgba(0,0,0,0.06);
--shadow-md: 0 8px 24px rgba(0,0,0,0.08);
}
body {
font-family: 'Plus Jakarta Sans', sans-serif;
background: var(--off-white);
color: var(--text-primary);
height: 100vh;
overflow: hidden;
display: flex;
}
/* ── SIDEBAR ── */
.sidebar {
width: var(--sidebar-w);
background: var(--navy);
display: flex;
flex-direction: column;
height: 100vh;
flex-shrink: 0;
position: relative;
z-index: 100;
transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
}
/* Subtle sidebar texture */
.sidebar::before {
content: '';
position: absolute;
inset: 0;
background: repeating-linear-gradient(135deg,rgba(255,255,255,0.01) 0px,rgba(255,255,255,0.01) 1px,transparent 1px,transparent 60px);
pointer-events: none;
}
.sidebar-logo {
padding: 20px 20px 16px;
border-bottom: 1px solid rgba(255,255,255,0.06);
display: flex;
align-items: center;
gap: 12px;
}
.logo-icon {
width: 40px;
height: 40px;
background: var(--blue);
border-radius: var(--radius);
display: flex;
align-items: center;
justify-content: center;
flex-shrink: 0;
box-shadow: 0 0 0 4px rgba(37,99,235,0.2);
}
.logo-icon i { color: #fff; font-size: 17px; }
.logo-text h1 {
color: #fff;
font-size: 16px;
font-weight: 800;
letter-spacing: -0.3px;
line-height: 1.2;
}
.logo-text p {
color: #60a5fa;
font-size: 11px;
font-weight: 600;
letter-spacing: 0.5px;
text-transform: uppercase;
margin-top: 1px;
}
.sidebar-nav {
flex: 1;
overflow-y: auto;
padding: 16px 12px;
scrollbar-width: thin;
scrollbar-color: rgba(255,255,255,0.1) transparent;
}
.sidebar-nav::-webkit-scrollbar { width: 4px; }
.sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }
.nav-group-label {
color: rgba(255,255,255,0.3);
font-size: 10px;
font-weight: 700;
letter-spacing: 1.2px;
text-transform: uppercase;
padding: 0 10px;
margin: 20px 0 6px;
}
.nav-group-label:first-child { margin-top: 4px; }
.nav-item {
display: flex;
align-items: center;
gap: 10px;
padding: 9px 10px;
border-radius: var(--radius-sm);
margin-bottom: 2px;
text-decoration: none;
transition: all 0.15s ease;
color: rgba(255,255,255,0.55);
font-size: 13.5px;
font-weight: 500;
position: relative;
}
.nav-item:hover {
background: rgba(255,255,255,0.07);
color: rgba(255,255,255,0.9);
}
.nav-item.active {
background: var(--blue);
color: #fff;
box-shadow: 0 2px 8px rgba(37,99,235,0.4);
}
.nav-item .nav-icon {
width: 32px;
height: 32px;
display: flex;
align-items: center;
justify-content: center;
border-radius: var(--radius-sm);
background: rgba(255,255,255,0.06);
flex-shrink: 0;
font-size: 14px;
transition: background 0.15s;
}
.nav-item.active .nav-icon { background: rgba(255,255,255,0.2); }
.nav-item:hover:not(.active) .nav-icon { background: rgba(255,255,255,0.1); }
.nav-badge {
margin-left: auto;
background: rgba(255,255,255,0.1);
color: rgba(255,255,255,0.7);
font-size: 10px;
font-weight: 700;
padding: 2px 7px;
border-radius: 20px;
min-width: 24px;
text-align: center;
}
.nav-item.active .nav-badge {
background: rgba(255,255,255,0.25);
color: #fff;
}
.sidebar-footer {
padding: 14px 16px;
border-top: 1px solid rgba(255,255,255,0.06);
background: rgba(0,0,0,0.2);
}
.user-card {
display: flex;
align-items: center;
gap: 10px;
}
.user-avatar {
width: 36px;
height: 36px;
background: linear-gradient(135deg, var(--blue) 0%, #6366f1 100%);
border-radius: 50%;
display: flex;
align-items: center;
justify-content: center;
flex-shrink: 0;
}
.user-avatar i { color: #fff; font-size: 14px; }
.user-info { flex: 1; min-width: 0; }
.user-name {
color: #fff;
font-size: 13px;
font-weight: 700;
white-space: nowrap;
overflow: hidden;
text-overflow: ellipsis;
}
.user-role {
color: #60a5fa;
font-size: 11px;
font-weight: 500;
margin-top: 1px;
}
.logout-btn {
width: 32px;
height: 32px;
display: flex;
align-items: center;
justify-content: center;
border-radius: var(--radius-sm);
background: transparent;
border: 1px solid rgba(255,255,255,0.1);
color: rgba(255,255,255,0.4);
cursor: pointer;
transition: all 0.15s;
flex-shrink: 0;
}
.logout-btn:hover {
background: rgba(239,68,68,0.15);
border-color: rgba(239,68,68,0.4);
color: #ef4444;
}
/* ── MAIN AREA ── */
.main-area {
flex: 1;
display: flex;
flex-direction: column;
overflow: hidden;
min-width: 0;
}
/* ── TOPBAR ── */
.topbar {
height: var(--topbar-h);
background: var(--white);
border-bottom: 1px solid var(--border);
display: flex;
align-items: center;
justify-content: space-between;
padding: 0 24px;
gap: 16px;
flex-shrink: 0;
box-shadow: var(--shadow-sm);
}
.topbar-left {
display: flex;
align-items: center;
gap: 16px;
min-width: 0;
}
/* Mobile menu toggle */
.menu-toggle {
display: none;
width: 36px;
height: 36px;
align-items: center;
justify-content: center;
border: 1px solid var(--border);
border-radius: var(--radius-sm);
background: var(--off-white);
cursor: pointer;
color: var(--text-secondary);
flex-shrink: 0;
transition: all 0.15s;
}
.menu-toggle:hover { background: var(--surface); color: var(--text-primary); }
.page-title {
font-size: 18px;
font-weight: 800;
color: var(--text-primary);
letter-spacing: -0.3px;
white-space: nowrap;
overflow: hidden;
text-overflow: ellipsis;
}
.topbar-time {
display: flex;
align-items: center;
gap: 6px;
color: var(--text-muted);
font-size: 12.5px;
font-weight: 500;
background: var(--off-white);
border: 1px solid var(--border);
padding: 5px 10px;
border-radius: 20px;
}
.topbar-actions {
display: flex;
align-items: center;
gap: 8px;
flex-shrink: 0;
}
.btn-topbar {
display: inline-flex;
align-items: center;
gap: 6px;
padding: 8px 14px;
border-radius: var(--radius-sm);
font-size: 13px;
font-weight: 600;
text-decoration: none;
transition: all 0.15s;
white-space: nowrap;
}
.btn-primary-topbar {
background: var(--blue);
color: #fff;
box-shadow: 0 1px 4px rgba(37,99,235,0.3);
}
.btn-primary-topbar:hover { background: #1d4ed8; transform: translateY(-1px); box-shadow: 0 3px 8px rgba(37,99,235,0.35); }
.btn-success-topbar {
background: var(--success-light);
color: var(--success);
border: 1px solid #bbf7d0;
}
.btn-success-topbar:hover { background: #bbf7d0; }
/* ── PAGE CONTENT ── */
.page-content {
flex: 1;
overflow-y: auto;
padding: 24px;
background: var(--off-white);
}
/* Alert boxes */
.alert-error {
margin-bottom: 16px;
background: #fff1f2;
border: 1px solid #fecdd3;
border-left: 4px solid #ef4444;
border-radius: var(--radius);
padding: 14px 16px;
}
.alert-error-header {
display: flex;
align-items: center;
gap: 8px;
margin-bottom: 8px;
}
.alert-error-header i { color: #ef4444; }
.alert-error-header h4 { font-weight: 700; color: #991b1b; font-size: 14px; }
.alert-error ul { list-style: none; padding: 0; }
.alert-error li { color: #b91c1c; font-size: 13px; padding: 2px 0 2px 16px; position: relative; }
.alert-error li::before { content: '›'; position: absolute; left: 4px; color: #ef4444; font-weight: 700; }
.alert-success {
margin-bottom: 16px;
background: #f0fdf4;
border: 1px solid #bbf7d0;
border-left: 4px solid #16a34a;
border-radius: var(--radius);
padding: 14px 16px;
display: flex;
align-items: center;
gap: 10px;
}
.alert-success i { color: var(--success); flex-shrink: 0; }
.alert-success p { color: #15803d; font-weight: 600; font-size: 14px; }
/* ── MOBILE OVERLAY ── */
.sidebar-overlay {
display: none;
position: fixed;
inset: 0;
background: rgba(0,0,0,0.5);
z-index: 99;
backdrop-filter: blur(2px);
}
/* ── RESPONSIVE ── */
@media (max-width: 768px) {
.sidebar {
position: fixed;
top: 0;
left: 0;
height: 100vh;
transform: translateX(-100%);
z-index: 200;
}
.sidebar.open {
transform: translateX(0);
box-shadow: var(--shadow-md);
}
.sidebar-overlay.open { display: block; }
.menu-toggle { display: flex; }
.topbar-time { display: none; }
.btn-topbar span { display: none; }
.btn-topbar { padding: 8px 10px; }
.page-content { padding: 16px; }
}
@media (max-width: 480px) {
.topbar { padding: 0 14px; gap: 10px; }
.topbar-actions { gap: 6px; }
}
</style>
</head>
<body>
<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- ── SIDEBAR ── -->
<aside class="sidebar" id="adminSidebar">
<div class="sidebar-logo">
<div class="logo-icon">
<i class="fas fa-briefcase"></i>
</div>
<div class="logo-text">
<h1>JobOne.in</h1>
<p>Admin Panel</p>
</div>
</div>

<nav class="sidebar-nav">
<a href="{{ route('admin.dashboard') }}"
class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
<span class="nav-icon"><i class="fas fa-chart-line"></i></span>
<span>Dashboard</span>
</a>

<div class="nav-group-label">Content</div>

<a href="{{ route('admin.posts.index') }}"
class="nav-item {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
<span class="nav-icon"><i class="fas fa-newspaper"></i></span>
<span>Posts</span>
@if(\App\Models\Post::count() > 0)
<span class="nav-badge">{{ \App\Models\Post::count() }}</span>
@endif
</a>

<a href="{{ route('admin.categories.index') }}"
class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
<span class="nav-icon"><i class="fas fa-tags"></i></span>
<span>Categories</span>
@if(\App\Models\Category::count() > 0)
<span class="nav-badge">{{ \App\Models\Category::count() }}</span>
@endif
</a>

<a href="{{ route('admin.states.index') }}"
class="nav-item {{ request()->routeIs('admin.states.*') ? 'active' : '' }}">
<span class="nav-icon"><i class="fas fa-map-marker-alt"></i></span>
<span>States</span>
@if(\App\Models\State::count() > 0)
<span class="nav-badge">{{ \App\Models\State::count() }}</span>
@endif
</a>

<a href="{{ route('admin.authors.index') }}"
class="nav-item {{ request()->routeIs('admin.authors.*') ? 'active' : '' }}">
<span class="nav-icon"><i class="fas fa-users"></i></span>
<span>Authors</span>
@if(\App\Models\Author::count() > 0)
<span class="nav-badge">{{ \App\Models\Author::count() }}</span>
@endif
</a>

<div class="nav-group-label">Monetization</div>

<a href="{{ route('admin.ads.index') }}"
class="nav-item {{ request()->routeIs('admin.ads.*') ? 'active' : '' }}">
<span class="nav-icon"><i class="fas fa-ad"></i></span>
<span>Ads</span>
@if(\App\Models\Ad::count() > 0)
<span class="nav-badge">{{ \App\Models\Ad::count() }}</span>
@endif
</a>

<a href="{{ route('admin.notifications.index') }}"
class="nav-item {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
<span class="nav-icon"><i class="fas fa-bell"></i></span>
<span>Notifications</span>
</a>

<a href="{{ route('admin.feedback') }}"
class="nav-item {{ request()->routeIs('admin.feedback') ? 'active' : '' }}">
<span class="nav-icon"><i class="fas fa-comments"></i></span>
<span>User Feedback</span>
</a>

<a href="{{ route('admin.whatsapp.index') }}"
class="nav-item {{ request()->routeIs('admin.whatsapp.*') ? 'active' : '' }}">
<span class="nav-icon"><i class="fab fa-whatsapp"></i></span>
<span>WhatsApp Share</span>
</a>

<div class="nav-group-label">Settings</div>

<a href="{{ route('admin.settings.index') }}"
class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
<span class="nav-icon"><i class="fas fa-cog"></i></span>
<span>Site Settings</span>
</a>

<a href="{{ route('admin.backups.index') }}"
class="nav-item {{ request()->routeIs('admin.backups.*') ? 'active' : '' }}">
<span class="nav-icon"><i class="fas fa-database"></i></span>
<span>Backup & Restore</span>
</a>
</nav>

<div class="sidebar-footer">
<div class="user-card">
<div class="user-avatar">
<i class="fas fa-user"></i>
</div>
<div class="user-info">
<div class="user-name">{{ auth('admin')->user()->name }}</div>
<div class="user-role">Administrator</div>
</div>
<form action="{{ route('admin.logout') }}" method="POST">
@csrf
<button type="submit" class="logout-btn" title="Logout">
<i class="fas fa-sign-out-alt"></i>
</button>
</form>
</div>
</div>
</aside>

<!-- ── MAIN AREA ── -->
<div class="main-area">
<!-- Top Bar -->
<header class="topbar">
<div class="topbar-left">
<button class="menu-toggle" onclick="toggleSidebar()" aria-label="Toggle menu">
<i class="fas fa-bars"></i>
</button>
<h2 class="page-title">@yield('title', 'Dashboard')</h2>
<div class="topbar-time hidden md:flex">
<i class="fas fa-clock" style="color:#93c5fd;font-size:11px;"></i>
<span id="current-time"></span>
</div>
</div>
<div class="topbar-actions">
<a href="{{ route('admin.posts.create') }}" class="btn-topbar btn-primary-topbar">
<i class="fas fa-plus"></i>
<span>New Post</span>
</a>
<a href="{{ route('home') }}" target="_blank" class="btn-topbar btn-success-topbar">
<i class="fas fa-external-link-alt"></i>
<span>View Site</span>
</a>
</div>
</header>

<!-- Page Content -->
<main class="page-content">
@if ($errors->any())
<div class="alert-error">
<div class="alert-error-header">
<i class="fas fa-exclamation-triangle"></i>
<h4>Please fix the following errors:</h4>
</div>
<ul>
@foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif

@if (session('success'))
<div class="alert-success">
<i class="fas fa-check-circle fa-lg"></i>
<p>{{ session('success') }}</p>
</div>
@endif

@yield('content')
</main>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
// Real-time clock
function updateTime() {
const now = new Date();
const el = document.getElementById('current-time');
if (el) {
el.textContent = now.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' })
+ ', ' + now.toLocaleTimeString('en-US', { hour12: true, hour: '2-digit', minute: '2-digit' });
}
}
updateTime();
setInterval(updateTime, 1000);

// Mobile sidebar
function toggleSidebar() {
const sidebar = document.getElementById('adminSidebar');
const overlay = document.getElementById('sidebarOverlay');
sidebar.classList.toggle('open');
overlay.classList.toggle('open');
}

function closeSidebar() {
document.getElementById('adminSidebar').classList.remove('open');
document.getElementById('sidebarOverlay').classList.remove('open');
}
</script>
</body>
</html>

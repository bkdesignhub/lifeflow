<!doctype html>
<html lang="en" data-theme="{{ auth()->user()->settings->theme ?? 'light' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#6c3cff">
    <meta name="firebase-config" content='@json(config("services.firebase"))'>
    <meta name="fcm-token-url" content="{{ route('notification-tokens.store') }}">
    <title>@yield('title', 'LifeFlow')</title>
    <link rel="manifest" href="/manifest.json">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="/assets/app.css" rel="stylesheet">
</head>
<body>
@php
    $nav = [
        ['label' => 'Dashboard', 'mobile' => 'Home', 'icon' => 'home', 'route' => 'dashboard'],
        ['label' => 'Today', 'mobile' => 'Today', 'icon' => 'calendar-days', 'route' => 'tasks.index'],
        ['label' => 'Notes', 'mobile' => 'Notes', 'icon' => 'square-pen', 'route' => 'notes.index'],
        ['label' => 'Money', 'mobile' => 'Money', 'icon' => 'wallet', 'route' => 'money.index'],
        ['label' => 'Reminders', 'mobile' => 'More', 'icon' => 'bell', 'route' => 'reminders.index'],
        ['label' => 'Assistant', 'mobile' => 'Assistant', 'icon' => 'bot', 'route' => 'assistant.index'],
        ['label' => 'Profile', 'mobile' => 'Profile', 'icon' => 'user-round', 'route' => 'profile.index'],
        ['label' => 'Settings', 'mobile' => 'Settings', 'icon' => 'settings', 'route' => 'settings.index'],
    ];
    $pageTitle = trim($__env->yieldContent('page-title', $__env->yieldContent('title', 'Dashboard')));
@endphp
<div class="app-shell">
    <aside class="sidebar">
        <a class="brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
            <span class="brand-mark">LF</span>
            <span><strong>LifeFlow</strong><small>My Personal Assistant</small></span>
        </a>
        <nav class="side-nav">
            @foreach($nav as $item)
                <a class="{{ request()->routeIs($item['route']) ? 'active' : '' }}" href="{{ route($item['route']) }}">
                    <i data-lucide="{{ $item['icon'] }}"></i><span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>
        <div class="sidebar-card">
            <span class="sidebar-illustration"><i data-lucide="sparkles"></i></span>
            <strong>Stay organized.</strong>
            <span>Build your day with calm focus.</span>
        </div>
    </aside>

    <main class="main-panel">
        <header class="topbar">
            <div class="topbar-title">
                <button class="icon-btn d-lg-none" type="button"><i data-lucide="menu"></i></button>
                <h1>{{ $pageTitle }}</h1>
            </div>
            <form class="search-box d-none d-md-flex" action="{{ route('notes.index') }}">
                <i data-lucide="search"></i>
                <input name="search" placeholder="Search anything...">
            </form>
            <div class="ms-auto d-flex align-items-center gap-2">
                <a class="icon-btn notification-dot" href="{{ route('reminders.index') }}"><i data-lucide="bell"></i></a>
                <a class="icon-btn" href="{{ route('install') }}"><i data-lucide="smartphone"></i></a>
                <a class="profile-pill" href="{{ route('profile.index') }}">
                    <span class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    <span class="d-none d-sm-inline">{{ auth()->user()->name }}</span>
                    <i data-lucide="chevron-down"></i>
                </a>
            </div>
        </header>
        <div class="content-wrap">
            @if(session('status'))
                <div class="alert alert-success rounded-4">{{ session('status') }}</div>
            @endif
            @yield('content')
        </div>
    </main>
</div>

<nav class="bottom-nav">
    @foreach(array_slice($nav, 0, 4) as $item)
        <a class="{{ request()->routeIs($item['route']) ? 'active' : '' }}" href="{{ route($item['route']) }}">
            <i data-lucide="{{ $item['icon'] }}"></i><span>{{ $item['mobile'] }}</span>
        </a>
    @endforeach
    <a class="{{ request()->routeIs('profile.index') || request()->routeIs('settings.index') || request()->routeIs('reminders.index') || request()->routeIs('assistant.index') ? 'active' : '' }}" href="{{ route('profile.index') }}">
        <i data-lucide="grid-2x2"></i><span>More</span>
    </a>
</nav>

<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="appToast" class="toast align-items-center border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body"></div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-messaging-compat.js"></script>
<script src="/assets/app.js"></script>
@stack('scripts')
</body>
</html>

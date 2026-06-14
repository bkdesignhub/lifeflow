@extends('layouts.app')
@section('title', 'Profile - LifeFlow')
@section('page-title', 'Profile')

@section('content')
<div class="mobile-header"><h1>Profile</h1></div>

<div class="profile-board">
    <section class="profile-identity-card">
        <div class="profile-cover"></div>
        <div class="profile-identity-body">
            <div class="avatar big">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <h2>{{ auth()->user()->name }}</h2>
                <p>{{ auth()->user()->email }}</p>
                <span class="profile-badge"><i data-lucide="sparkles"></i> LifeFlow Member</span>
            </div>
        </div>
    </section>

    <section class="app-card profile-edit-card">
        <div class="section-title"><h2>Edit Profile</h2></div>
        <form class="ajax-form profile-form" method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')
            <label>Full Name<input class="form-control form-control-lg" name="name" value="{{ auth()->user()->name }}" required></label>
            <label>Email Address<input class="form-control form-control-lg" name="email" type="email" value="{{ auth()->user()->email }}" required></label>
            <button class="btn btn-primary rounded-4"><i data-lucide="save"></i> Save Profile</button>
        </form>
    </section>

    <section class="profile-actions-card">
        <a href="{{ route('reminders.index') }}"><span class="icon-bubble purple-bg"><i data-lucide="bell"></i></span><div><strong>Reminders</strong><small>Upcoming personal alerts</small></div><i data-lucide="chevron-right"></i></a>
        <a href="{{ route('assistant.index') }}"><span class="icon-bubble purple-bg"><i data-lucide="bot"></i></span><div><strong>Assistant</strong><small>Ask about plans, notes, and money</small></div><i data-lucide="chevron-right"></i></a>
        <a href="{{ route('settings.index') }}"><span class="icon-bubble purple-bg"><i data-lucide="target"></i></span><div><strong>My Goals</strong><small>Manage personal focus areas</small></div><i data-lucide="chevron-right"></i></a>
        <a href="{{ route('export') }}"><span class="icon-bubble success-bg"><i data-lucide="cloud-download"></i></span><div><strong>Backup & Export</strong><small>Download your LifeFlow data</small></div><i data-lucide="chevron-right"></i></a>
        <a href="{{ route('settings.index') }}"><span class="icon-bubble purple-bg"><i data-lucide="bell"></i></span><div><strong>Notifications</strong><small>Reminder and daily summary settings</small></div><i data-lucide="chevron-right"></i></a>
        <a href="{{ route('install') }}"><span class="icon-bubble purple-bg"><i data-lucide="smartphone"></i></span><div><strong>PWA Install</strong><small>Install LifeFlow on your device</small></div><i data-lucide="chevron-right"></i></a>
        <form method="POST" action="{{ route('logout') }}">@csrf<button class="logout"><span class="icon-bubble danger-bg"><i data-lucide="log-out"></i></span><div><strong>Logout</strong><small>End this session</small></div></button></form>
    </section>
</div>
@endsection

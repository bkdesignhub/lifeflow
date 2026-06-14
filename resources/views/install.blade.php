@extends('layouts.app')
@section('title', 'Install LifeFlow')

@section('content')
<section class="install-card">
    <div class="phone-mock"><div class="brand-mark">LF</div><strong>LifeFlow</strong></div>
    <h1>Install LifeFlow App</h1>
    <p>Install the app on your device for faster access, offline support, and push notifications.</p>
    <div class="install-benefits">
        <span><i class="fa-regular fa-clock"></i> Works offline</span>
        <span><i class="fa-regular fa-bell"></i> Push notifications</span>
        <span><i class="fa-solid fa-bolt"></i> Faster access</span>
        <span><i class="fa-solid fa-mobile-screen"></i> Feels like an app</span>
    </div>
    <button id="installBtn" class="btn btn-primary btn-lg rounded-4"><i class="fa-solid fa-download me-2"></i>Install Now</button>
</section>
@endsection

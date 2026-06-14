@extends('layouts.auth')

@section('content')
<h1 class="auth-title">Welcome back</h1>
<p class="text-muted text-center mb-4">Sign in to continue your LifeFlow.</p>
<form method="POST" action="{{ route('login.store') }}" class="vstack gap-3">
    @csrf
    <input class="form-control form-control-lg" name="email" type="email" value="{{ old('email') }}" placeholder="Email address" required>
    <input class="form-control form-control-lg" name="password" type="password" placeholder="Password" required>
    <div class="d-flex justify-content-between align-items-center small">
        <label class="form-check"><input class="form-check-input" type="checkbox" name="remember"> Remember me</label>
        <a href="{{ route('password.request') }}">Forgot?</a>
    </div>
    @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
    <button class="btn btn-primary btn-lg rounded-4">Login</button>
    <p class="text-center mb-0">New here? <a href="{{ route('register') }}">Create account</a></p>
</form>
@endsection

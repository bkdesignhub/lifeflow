@extends('layouts.auth')

@section('content')
<h1 class="auth-title">Reset password</h1>
<p class="text-muted text-center mb-4">Enter your email and we will send reset instructions.</p>
@if(session('status'))<div class="alert alert-success rounded-4">{{ session('status') }}</div>@endif
<form method="POST" action="{{ route('password.email') }}" class="vstack gap-3">
    @csrf
    <input class="form-control form-control-lg" name="email" type="email" value="{{ old('email') }}" placeholder="Email address" required>
    @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
    <button class="btn btn-primary btn-lg rounded-4">Send Link</button>
    <a class="text-center" href="{{ route('login') }}">Back to login</a>
</form>
@endsection

@extends('layouts.auth')

@section('content')
<h1 class="auth-title">Create LifeFlow</h1>
<p class="text-muted text-center mb-4">Your clean personal command center.</p>
<form method="POST" action="{{ route('register.store') }}" class="vstack gap-3">
    @csrf
    <input class="form-control form-control-lg" name="name" value="{{ old('name') }}" placeholder="Full name" required>
    <input class="form-control form-control-lg" name="email" type="email" value="{{ old('email') }}" placeholder="Email address" required>
    <input class="form-control form-control-lg" name="password" type="password" placeholder="Password" required>
    <input class="form-control form-control-lg" name="password_confirmation" type="password" placeholder="Confirm password" required>
    @if($errors->any())<div class="text-danger small">{{ $errors->first() }}</div>@endif
    <button class="btn btn-primary btn-lg rounded-4">Register</button>
    <p class="text-center mb-0">Already have an account? <a href="{{ route('login') }}">Login</a></p>
</form>
@endsection

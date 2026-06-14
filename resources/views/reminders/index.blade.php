@extends('layouts.app')
@section('title', 'Reminders - LifeFlow')

@section('content')
<div class="mobile-header"><h1>Reminders</h1><a class="icon-btn" href="{{ route('reminders.create') }}"><i class="fa-solid fa-plus"></i></a></div>
<section class="toolbar-card chip-scroll">
    <a class="filter-chip {{ !request('filter') ? 'active' : '' }}" href="{{ route('reminders.index') }}">All</a>
    <a class="filter-chip {{ request('filter') === 'upcoming' ? 'active' : '' }}" href="{{ route('reminders.index', ['filter' => 'upcoming']) }}">Upcoming</a>
    <a class="filter-chip {{ request('filter') === 'completed' ? 'active' : '' }}" href="{{ route('reminders.index', ['filter' => 'completed']) }}">Completed</a>
</section>
<section class="app-card">
    <div class="vstack gap-2">
        @forelse($reminders as $reminder)
            <div class="reminder-row" id="reminder-{{ $reminder->id }}">
                <i class="fa-regular fa-bell {{ $reminder->status === 'completed' ? 'success' : 'purple-text' }}"></i>
                <div><strong>{{ $reminder->title }}</strong><small>{{ $reminder->reminder_date->format('d M, Y') }}, {{ $reminder->reminder_time ? date('g:i A', strtotime($reminder->reminder_time)) : 'All Day' }}</small></div>
                <button class="icon-btn" data-action="{{ route('reminders.complete', $reminder) }}" data-method="PATCH"><i class="fa-solid fa-check"></i></button>
            </div>
        @empty
            <div class="empty-state big"><i class="fa-regular fa-bell"></i><p>No reminders yet.</p><a class="btn btn-primary rounded-4" href="{{ route('reminders.create') }}">Add Reminder</a></div>
        @endforelse
    </div>
</section>
@endsection

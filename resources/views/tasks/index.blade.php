@extends('layouts.app')
@section('title', 'Today - LifeFlow')

@section('content')
@php
    $current = \Illuminate\Support\Carbon::parse($date);
@endphp
<div class="mobile-header">
    <h1>Today</h1>
    <a class="icon-btn" href="{{ route('tasks.create') }}"><i class="fa-solid fa-plus"></i></a>
</div>
<section class="app-card page-card">
    <div class="date-switch">
        <a href="{{ route('tasks.index', ['date' => $current->copy()->subDay()->toDateString()]) }}"><i class="fa-solid fa-chevron-left"></i></a>
        <strong>{{ $current->format('d F, Y') }}</strong>
        <a href="{{ route('tasks.index', ['date' => $current->copy()->addDay()->toDateString()]) }}"><i class="fa-solid fa-chevron-right"></i></a>
    </div>
    <div class="vstack gap-2 mt-3">
        @forelse($tasks as $task)
            <div class="task-row" id="task-{{ $task->id }}">
                <div>
                    <small>{{ $task->start_time ? date('g:i A', strtotime($task->start_time)) : 'Any time' }} - {{ $task->end_time ? date('g:i A', strtotime($task->end_time)) : '' }}</small>
                    <strong>{{ $task->title }}</strong>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="status-chip {{ $task->status }}">{{ ucfirst($task->status) }}</span>
                    <button class="circle-check" data-action="{{ route('tasks.status', $task) }}" data-method="PATCH" data-payload='{"status":"done"}'><i class="fa-solid fa-check"></i></button>
                </div>
            </div>
        @empty
            <div class="empty-state big"><i class="fa-regular fa-calendar-plus"></i><p>No plan for this date.</p><a class="btn btn-primary rounded-4" href="{{ route('tasks.create') }}">Add Activity</a></div>
        @endforelse
    </div>
    <a class="btn btn-primary w-100 rounded-4 mt-3" href="{{ route('tasks.create') }}"><i class="fa-solid fa-plus me-2"></i>Add Task</a>
</section>
@endsection

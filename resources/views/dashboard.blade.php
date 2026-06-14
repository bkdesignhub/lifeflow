@extends('layouts.app')
@section('title', 'Dashboard - LifeFlow')
@section('page-title', 'Dashboard')

@section('content')
@php
    $taskCount = max($todayTasks->count(), 1);
    $doneCount = $todayTasks->where('status', 'done')->count();
    $monthTotal = max($monthIncome + $monthExpense, 1);
    $incomePct = round(($monthIncome / $monthTotal) * 100);
    $expensePct = round(($monthExpense / $monthTotal) * 100);
@endphp

<div class="dashboard-board">
    <div class="dashboard-left">
        <section class="hero-card">
            <div>
                <span class="eyebrow">{{ now()->format('l, d M') }}</span>
                <h1>{{ $greeting }}, {{ auth()->user()->name }}!</h1>
                <p>Have a productive day ahead.</p>
            </div>
            <div class="hero-art"><i data-lucide="sparkles"></i></div>
        </section>

        <section class="quick-actions">
            <a href="{{ route('tasks.create') }}" class="qa purple"><i data-lucide="plus"></i><span>Add Task</span></a>
            <button class="qa blue" data-bs-toggle="modal" data-bs-target="#noteModal"><i data-lucide="plus"></i><span>Add Note</span></button>
            <a href="{{ route('money.create', ['type' => 'income']) }}" class="qa green"><i data-lucide="plus"></i><span>Add Money</span></a>
            <a href="{{ route('reminders.create') }}" class="qa orange"><i data-lucide="plus"></i><span>Add Reminder</span></a>
        </section>

        <section class="app-card schedule-card">
            <div class="section-title"><h2>Today's Schedule</h2><a href="{{ route('tasks.index') }}">View All</a></div>
            <div id="dashboardTasks" class="schedule-list">
                @forelse($todayTasks as $task)
                    <div class="schedule-row">
                        <span class="rail {{ $task->status }}"></span>
                        <div class="task-icon">
                            <i data-lucide="{{ $task->icon === 'fa-code' ? 'code-2' : ($task->icon === 'fa-briefcase' ? 'briefcase-business' : ($task->icon === 'fa-book-open' || $task->icon === 'fa-book' ? 'book-open' : 'activity')) }}"></i>
                        </div>
                        <div class="task-copy">
                            <small>{{ $task->start_time ? date('g:i A', strtotime($task->start_time)) : 'Any time' }} - {{ $task->end_time ? date('g:i A', strtotime($task->end_time)) : '' }}</small>
                            <strong>{{ $task->title }}</strong>
                        </div>
                        <button class="status-chip {{ $task->status }}" data-action="{{ route('tasks.status', $task) }}" data-method="PATCH" data-payload='{"status":"done"}'>{{ ucfirst($task->status) }}</button>
                    </div>
                @empty
                    <div class="empty-state"><i data-lucide="calendar-plus"></i><p>No activities planned today.</p></div>
                @endforelse
            </div>
            <a class="ghost-add" href="{{ route('tasks.create') }}"><i data-lucide="plus"></i>Add Activity</a>
        </section>
    </div>

    <div class="dashboard-middle">
        <section class="metric-card cash-card">
            <div class="card-head">
                <span>Cash in Hand</span>
                <span class="icon-bubble success-bg"><i data-lucide="wallet"></i></span>
            </div>
            <strong>Rs {{ number_format($cashInHand) }}</strong>
            <div class="cash-sub">
                <small>Today spent Rs {{ number_format($todaySpent) }}</small>
                <em>+ Rs {{ number_format(max($monthSaved, 0)) }} Saved</em>
            </div>
        </section>

        <section class="app-card">
            <div class="section-title"><h2>Today Overview</h2></div>
            <div class="mini-grid">
                <div class="mini success"><span>Received Today</span><strong>Rs {{ number_format($todayIncome) }}</strong></div>
                <div class="mini danger"><span>Spent Today</span><strong>Rs {{ number_format($todaySpent) }}</strong></div>
                <div class="mini purple"><span>Balance Today</span><strong>Rs {{ number_format($todayIncome - $todaySpent) }}</strong></div>
                <div class="mini blue"><span>Tasks Done</span><strong>{{ $doneCount }}/{{ $taskCount }}</strong></div>
            </div>
        </section>

        <section class="app-card">
            <div class="section-title"><h2>Upcoming Reminders</h2><a href="{{ route('reminders.index') }}">View All</a></div>
            <div class="vstack gap-2">
                @forelse($upcomingReminders as $reminder)
                    <div class="reminder-mini">
                        <span class="rail pending"></span>
                        <span class="icon-bubble purple-bg"><i data-lucide="bell"></i></span>
                        <div><strong>{{ $reminder->title }}</strong><small>{{ $reminder->reminder_date->format('d M') }}, {{ $reminder->reminder_time ? date('g:i A', strtotime($reminder->reminder_time)) : 'All Day' }}</small></div>
                    </div>
                @empty
                    <div class="empty-state"><i data-lucide="bell-off"></i><p>No upcoming reminders.</p></div>
                @endforelse
            </div>
        </section>
    </div>

    <div class="dashboard-right">
        <section class="app-card">
            <div class="section-title"><h2>Quick Notes</h2><a href="{{ route('notes.index') }}">View All</a></div>
            <div class="note-stack">
                @forelse($quickNotes as $note)
                    <a class="note-mini {{ $note->color }}" href="{{ route('notes.show', $note) }}">
                        <strong>{{ $note->title }}</strong>
                        <span>{{ str($note->body)->limit(42) }}</span>
                        <small>{{ $note->updated_at->format('d M, h:i A') }}</small>
                    </a>
                @empty
                    <div class="empty-state"><i data-lucide="notebook-pen"></i><p>No notes yet.</p></div>
                @endforelse
                <button class="ghost-add" data-bs-toggle="modal" data-bs-target="#noteModal"><i data-lucide="plus"></i>New Note</button>
            </div>
        </section>

        <section class="app-card money-month-card">
            <div class="section-title"><h2>This Month Money</h2></div>
            <div class="money-ring" style="--income: {{ $incomePct }}%; --expense: {{ $expensePct }}%;">
                <div><strong>Rs {{ number_format($monthSaved) }}</strong><span>Saved</span></div>
            </div>
            <div class="legend-list">
                <span><i class="dot green"></i>Income <strong>Rs {{ number_format($monthIncome) }}</strong></span>
                <span><i class="dot red"></i>Expense <strong>Rs {{ number_format($monthExpense) }}</strong></span>
                <span><i class="dot purple"></i>Balance <strong>Rs {{ number_format($monthSaved) }}</strong></span>
            </div>
            <a class="ghost-add" href="{{ route('money.index') }}">View Details</a>
        </section>
    </div>
</div>

<section class="mobile-dashboard-extra">
    <div class="section-title"><h2>Recent Money</h2><a href="{{ route('money.index') }}">View All</a></div>
    <div class="mini-grid">
        <div class="mini success"><span>Received</span><strong>Rs {{ number_format($todayIncome) }}</strong></div>
        <div class="mini danger"><span>Spent</span><strong>Rs {{ number_format($todaySpent) }}</strong></div>
    </div>
</section>

@include('notes._modal')
@endsection

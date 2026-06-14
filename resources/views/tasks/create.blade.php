@extends('layouts.app')
@section('title', 'Add Task - LifeFlow')

@section('content')
@php
    $isEdit = $task->exists;
    $dateValue = $task->plan_date ? \Illuminate\Support\Carbon::parse($task->plan_date)->format('Y-m-d') : today()->toDateString();
@endphp
<div class="mobile-header"><a class="icon-btn" href="{{ route('tasks.index') }}"><i class="fa-solid fa-arrow-left"></i></a><h1>{{ $isEdit ? 'Edit Task' : 'Add Task' }}</h1></div>
<section class="form-card">
    <form class="ajax-form vstack gap-3" method="POST" action="{{ $isEdit ? route('tasks.update', $task) : route('tasks.store') }}">
        @csrf
        @if($isEdit) @method('PUT') @endif
        <label>Title<input class="form-control form-control-lg" name="title" value="{{ old('title', $task->title) }}" placeholder="e.g. Gym" required></label>
        <div class="row g-3">
            <label class="col-6">Start Time<input class="form-control form-control-lg" type="time" name="start_time" value="{{ old('start_time', $task->start_time ? date('H:i', strtotime($task->start_time)) : '') }}"></label>
            <label class="col-6">End Time<input class="form-control form-control-lg" type="time" name="end_time" value="{{ old('end_time', $task->end_time ? date('H:i', strtotime($task->end_time)) : '') }}"></label>
        </div>
        <label>Date<input class="form-control form-control-lg" type="date" name="plan_date" value="{{ old('plan_date', $dateValue) }}" required></label>
        <label>Repeat<select class="form-select form-select-lg" name="repeat">@foreach(['once','daily','weekly','monthly'] as $repeat)<option value="{{ $repeat }}" @selected(old('repeat', $task->repeat) === $repeat)>{{ ucfirst($repeat) }}</option>@endforeach</select></label>
        <label>Reminder<select class="form-select form-select-lg" name="reminder_minutes"><option value="10">10 minutes before</option><option value="30">30 minutes before</option><option value="60">1 hour before</option></select></label>
        <input type="hidden" name="category" value="{{ old('category', $task->category ?: 'Personal') }}">
        <input type="hidden" name="icon" id="taskIcon" value="{{ old('icon', $task->icon ?: 'fa-dumbbell') }}">
        <div>
            <span class="label">Category</span>
            <div class="icon-picker">
                @foreach(['fa-dumbbell','fa-book-open','fa-code','fa-briefcase','fa-ellipsis'] as $icon)
                    <button type="button" class="icon-choice {{ old('icon', $task->icon) === $icon ? 'active' : '' }}" data-icon="{{ $icon }}"><i class="fa-solid {{ $icon }}"></i></button>
                @endforeach
            </div>
        </div>
        <label class="form-check form-switch"><input class="form-check-input" type="checkbox" name="push_enabled" value="1" @checked($task->push_enabled ?? true)> Push notification</label>
        <button class="btn btn-primary btn-lg rounded-4">{{ $isEdit ? 'Update Task' : 'Save Task' }}</button>
    </form>
</section>
@endsection

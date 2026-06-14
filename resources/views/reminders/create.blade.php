@extends('layouts.app')
@section('title', 'Add Reminder - LifeFlow')

@section('content')
@php
    $isEdit = $reminder->exists;
    $dateValue = $reminder->reminder_date ? \Illuminate\Support\Carbon::parse($reminder->reminder_date)->format('Y-m-d') : today()->toDateString();
@endphp
<div class="mobile-header"><a class="icon-btn" href="{{ route('reminders.index') }}"><i class="fa-solid fa-arrow-left"></i></a><h1>{{ $isEdit ? 'Edit Reminder' : 'Add Reminder' }}</h1></div>
<section class="form-card">
    <form class="ajax-form vstack gap-3" method="POST" action="{{ $isEdit ? route('reminders.update', $reminder) : route('reminders.store') }}">
        @csrf
        @if($isEdit) @method('PUT') @endif
        <label>Title<input class="form-control form-control-lg" name="title" value="{{ old('title', $reminder->title) }}" placeholder="Gym Fees" required></label>
        <label>Date<input class="form-control form-control-lg" type="date" name="reminder_date" value="{{ old('reminder_date', $dateValue) }}" required></label>
        <label>Time<input class="form-control form-control-lg" type="time" name="reminder_time" value="{{ old('reminder_time', $reminder->reminder_time ? date('H:i', strtotime($reminder->reminder_time)) : '') }}"></label>
        <label>Repeat<select class="form-select form-select-lg" name="repeat">@foreach(['none','daily','weekly','monthly','yearly'] as $repeat)<option value="{{ $repeat }}" @selected(old('repeat', $reminder->repeat) === $repeat)>{{ ucfirst($repeat) }}</option>@endforeach</select></label>
        <label>Note<textarea class="form-control" name="note" rows="4" placeholder="Optional">{{ old('note', $reminder->note) }}</textarea></label>
        <label class="form-check form-switch"><input class="form-check-input" type="checkbox" name="push_enabled" value="1" @checked($reminder->push_enabled ?? true)> Push notification</label>
        <button class="btn btn-primary btn-lg rounded-4">Save Reminder</button>
    </form>
</section>
@endsection

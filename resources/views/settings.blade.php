@extends('layouts.app')
@section('title', 'Settings - LifeFlow')

@section('content')
<div class="mobile-header"><h1>Settings</h1></div>
<section class="form-card">
    <form class="ajax-form vstack gap-3" method="POST" action="{{ route('settings.update') }}">
        @csrf @method('PATCH')
        <label>Language<input class="form-control form-control-lg" name="language" value="{{ $settings->language }}"></label>
        <label>Date Format<input class="form-control form-control-lg" name="date_format" value="{{ $settings->date_format }}"></label>
        <label>Time Format<select class="form-select form-select-lg" name="time_format"><option value="12" @selected($settings->time_format === '12')>12 Hours</option><option value="24" @selected($settings->time_format === '24')>24 Hours</option></select></label>
        <label>Theme<select class="form-select form-select-lg" name="theme"><option value="light" @selected($settings->theme === 'light')>Light</option><option value="dark" @selected($settings->theme === 'dark')>Dark</option></select></label>
        <label class="form-check form-switch"><input class="form-check-input" type="checkbox" name="task_notifications" value="1" @checked($settings->task_notifications)> Task Reminders</label>
        <label class="form-check form-switch"><input class="form-check-input" type="checkbox" name="reminder_notifications" value="1" @checked($settings->reminder_notifications)> Reminder Alerts</label>
        <label class="form-check form-switch"><input class="form-check-input" type="checkbox" name="daily_summary" value="1" @checked($settings->daily_summary)> Daily Summary</label>
        <button class="btn btn-primary btn-lg rounded-4">Save Settings</button>
    </form>
</section>
@endsection

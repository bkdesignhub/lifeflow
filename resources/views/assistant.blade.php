@extends('layouts.app')
@section('title', 'Assistant - LifeFlow')

@section('content')
<section class="assistant-panel">
    <div class="assistant-head">
        <div class="bot-face"><i class="fa-solid fa-robot"></i></div>
        <h1>Hello {{ auth()->user()->name }}!</h1>
        <p>How can I help you today?</p>
    </div>
    <div class="suggestion-grid">
        @foreach(['What is my plan today?','How much cash in hand?','Add gym tomorrow 7 AM','Show this month expenses','Remind me to learn Python daily 8 PM','What are my upcoming reminders?'] as $suggestion)
            <button class="suggestion" data-suggestion="{{ $suggestion }}"><i class="fa-regular fa-message"></i>{{ $suggestion }}</button>
        @endforeach
    </div>
    <div id="chatLog" class="chat-log"></div>
    <form id="assistantForm" class="chat-form" action="{{ route('assistant.ask') }}">
        <input name="message" placeholder="Ask me anything..." autocomplete="off">
        <button class="btn btn-primary"><i class="fa-solid fa-arrow-right"></i></button>
    </form>
</section>
@endsection

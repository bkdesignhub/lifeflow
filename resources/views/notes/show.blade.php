@extends('layouts.app')
@section('title', $note->title.' - LifeFlow')

@section('content')
<div class="mobile-header">
    <a class="icon-btn" href="{{ route('notes.index') }}"><i class="fa-solid fa-arrow-left"></i></a>
    <div class="ms-auto d-flex gap-2">
        <button class="icon-btn" data-action="{{ route('notes.pin', $note) }}" data-method="PATCH"><i class="fa-solid fa-star"></i></button>
        <button class="icon-btn" data-action="{{ route('notes.destroy', $note) }}" data-method="DELETE" data-confirm="Delete this note?"><i class="fa-solid fa-trash"></i></button>
    </div>
</div>
<section class="note-detail {{ $note->color }}">
    <span class="filter-chip active">{{ $note->category }}</span>
    <h1>{{ $note->title }}</h1>
    <small>{{ $note->updated_at->format('d F, Y h:i A') }}</small>
    <div class="note-body">{!! nl2br(e($note->body)) !!}</div>
</section>
@endsection

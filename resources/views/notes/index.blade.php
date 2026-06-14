@extends('layouts.app')
@section('title', 'Notes - LifeFlow')
@section('page-title', 'Notes')

@section('content')
@php
    $categories = ['All','Ideas','Learning','Work','Personal','Important'];
    $counts = ['All' => $notes->count()];
    foreach ($categories as $cat) {
        if ($cat !== 'All') $counts[$cat] = $notes->where('category', $cat)->count();
    }
@endphp

<div class="mobile-header">
    <h1>Notes</h1>
    <button class="icon-btn" data-bs-toggle="modal" data-bs-target="#noteModal"><i data-lucide="plus"></i></button>
</div>

<div class="notes-board">
    <aside class="notes-filter-panel">
        <div class="section-title"><h2>Categories</h2></div>
        <nav class="notes-categories">
            @foreach($categories as $cat)
                <a class="{{ request('category', 'All') === $cat ? 'active' : '' }}" href="{{ route('notes.index', ['category' => $cat]) }}">
                    <span>{{ $cat === 'All' ? 'All Notes' : $cat }}</span>
                    <strong>{{ $counts[$cat] ?? 0 }}</strong>
                </a>
            @endforeach
        </nav>
    </aside>

    <section class="notes-workspace">
        <div class="notes-toolbar">
            <form class="notes-search" method="GET">
                <i data-lucide="search"></i>
                <input name="search" value="{{ request('search') }}" placeholder="Search notes...">
                @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
            </form>
            <select class="form-select notes-select" onchange="window.location.href=this.value">
                @foreach($categories as $cat)
                    <option value="{{ route('notes.index', ['category' => $cat]) }}" @selected(request('category', 'All') === $cat)>{{ $cat === 'All' ? 'All Categories' : $cat }}</option>
                @endforeach
            </select>
            <button class="btn btn-primary notes-add-btn" data-bs-toggle="modal" data-bs-target="#noteModal"><i data-lucide="plus"></i> New Note</button>
        </div>

        <div class="chip-scroll notes-mobile-chips">
            @foreach($categories as $cat)
                <a class="filter-chip {{ request('category', 'All') === $cat ? 'active' : '' }}" href="{{ route('notes.index', ['category' => $cat]) }}">{{ $cat }}</a>
            @endforeach
        </div>

        <div class="sticky-notes-grid">
            @forelse($notes as $note)
                @include('notes._card', ['note' => $note])
            @empty
                <div class="empty-state big"><i data-lucide="notebook-pen"></i><p>No notes found.</p><button class="btn btn-primary rounded-4" data-bs-toggle="modal" data-bs-target="#noteModal">Add Note</button></div>
            @endforelse
        </div>
    </section>
</div>

@include('notes._modal')
@endsection

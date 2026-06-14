@extends('layouts.app')
@section('title', 'Money History - LifeFlow')

@section('content')
<div class="mobile-header"><h1>History</h1><a class="icon-btn" href="{{ route('money.index') }}"><i class="fa-solid fa-arrow-left"></i></a></div>
<section class="toolbar-card chip-scroll">
    <a class="filter-chip {{ request('filter', 'month') === 'today' ? 'active' : '' }}" href="{{ route('money.history', ['filter' => 'today']) }}">Today</a>
    <a class="filter-chip {{ request('filter') === 'week' ? 'active' : '' }}" href="{{ route('money.history', ['filter' => 'week']) }}">This Week</a>
    <a class="filter-chip {{ request('filter', 'month') === 'month' ? 'active' : '' }}" href="{{ route('money.history', ['filter' => 'month']) }}">This Month</a>
</section>
<section class="app-card">
    @forelse($entries as $entry)
        <div class="money-row"><strong class="{{ $entry->type === 'income' ? 'success' : 'danger' }}">{{ $entry->type === 'income' ? '+' : '-' }} Rs {{ number_format($entry->amount) }}</strong><span>{{ $entry->category }}</span><small>{{ $entry->entry_date->format('d M, h:i A') }}</small></div>
    @empty
        <div class="empty-state big"><i class="fa-solid fa-receipt"></i><p>No transactions for this filter.</p></div>
    @endforelse
</section>
@endsection

@extends('layouts.app')
@section('title', 'Money - LifeFlow')
@section('page-title', 'Money')

@section('content')
<div class="mobile-header">
    <h1>Money</h1>
    <a class="icon-btn" href="{{ route('money.history') }}"><i data-lucide="history"></i></a>
</div>

<div class="money-board">
    <section class="money-main">
        <div class="money-hero-card">
            <div>
                <span class="eyebrow">Cash in Hand</span>
                <strong>Rs {{ number_format($summary['cash']) }}</strong>
                <p>Simple personal cash tracking for received and spent money.</p>
            </div>
            <span class="money-wallet"><i data-lucide="wallet"></i></span>
        </div>

        <div class="money-summary-grid">
            <div class="mini success"><span>Received Today</span><strong>Rs {{ number_format($summary['todayIncome']) }}</strong></div>
            <div class="mini danger"><span>Spent Today</span><strong>Rs {{ number_format($summary['todayExpense']) }}</strong></div>
            <div class="mini purple"><span>Balance Today</span><strong>Rs {{ number_format($summary['todayIncome'] - $summary['todayExpense']) }}</strong></div>
        </div>

        <div class="money-action-row">
            <a href="{{ route('money.create', ['type' => 'income']) }}" class="qa green"><i data-lucide="plus"></i><span>Received</span></a>
            <a href="{{ route('money.create', ['type' => 'expense']) }}" class="qa red"><i data-lucide="minus"></i><span>Spent</span></a>
        </div>

        <section class="app-card money-history-card">
            <div class="section-title"><h2>Recent Money History</h2><a href="{{ route('money.history') }}">View All</a></div>
            <div class="money-timeline">
                @forelse($entries as $entry)
                    <div class="money-timeline-row">
                        <span class="money-type-icon {{ $entry->type }}"><i data-lucide="{{ $entry->type === 'income' ? 'arrow-down-left' : 'arrow-up-right' }}"></i></span>
                        <div>
                            <strong>{{ $entry->category }}</strong>
                            <small>{{ $entry->source ?: ($entry->note ?: 'Personal entry') }}</small>
                        </div>
                        <div class="money-amount">
                            <strong class="{{ $entry->type === 'income' ? 'success' : 'danger' }}">{{ $entry->type === 'income' ? '+' : '-' }} Rs {{ number_format($entry->amount) }}</strong>
                            <small>{{ $entry->entry_date->format('d M, h:i A') }}</small>
                        </div>
                    </div>
                @empty
                    <div class="empty-state"><i data-lucide="wallet"></i><p>No money entries yet.</p></div>
                @endforelse
            </div>
        </section>
    </section>

    <aside class="money-side">
        <section class="app-card">
            <div class="section-title"><h2>This Month</h2><a href="{{ route('money.history') }}">Report</a></div>
            <div class="summary-lines refined">
                <p><span>Income</span><strong class="success">Rs {{ number_format($summary['monthIncome']) }}</strong></p>
                <p><span>Expense</span><strong class="danger">Rs {{ number_format($summary['monthExpense']) }}</strong></p>
                <p><span>Balance</span><strong>Rs {{ number_format($summary['monthIncome'] - $summary['monthExpense']) }}</strong></p>
            </div>
        </section>
        <section class="app-card money-tip-card">
            <span class="icon-bubble purple-bg"><i data-lucide="sparkles"></i></span>
            <h2>Keep it simple</h2>
            <p>Add every received and spent entry today to keep Cash in Hand accurate.</p>
        </section>
    </aside>
</div>
@endsection

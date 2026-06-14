@extends('layouts.app')
@section('title', ($type === 'income' ? 'Add Income' : 'Add Expense').' - LifeFlow')

@section('content')
@php
    $incomeCats = ['Salary'=>'fa-briefcase','Freelance'=>'fa-laptop-code','Friend'=>'fa-user-group','Refund'=>'fa-rotate-left','Other'=>'fa-ellipsis'];
    $expenseCats = ['Food'=>'fa-burger','Petrol'=>'fa-gas-pump','Shopping'=>'fa-cart-shopping','Gym'=>'fa-dumbbell','Entertainment'=>'fa-film','Recharge'=>'fa-mobile-screen','Medical'=>'fa-kit-medical','Learning'=>'fa-book-open','Work'=>'fa-briefcase','Other'=>'fa-ellipsis'];
    $cats = $type === 'income' ? $incomeCats : $expenseCats;
@endphp
<div class="mobile-header"><a class="icon-btn" href="{{ route('money.index') }}"><i class="fa-solid fa-arrow-left"></i></a><h1>{{ $type === 'income' ? 'Add Income' : 'Add Expense' }}</h1></div>
<section class="form-card">
    <form class="ajax-form vstack gap-3" method="POST" action="{{ route('money.store') }}">
        @csrf
        <input type="hidden" name="type" value="{{ $type }}">
        <input type="hidden" name="category" id="moneyCategory" value="{{ array_key_first($cats) }}">
        <input type="hidden" name="icon" id="moneyIcon" value="{{ reset($cats) }}">
        <label>Amount<input class="form-control form-control-lg" name="amount" type="number" min="1" placeholder="1000" required></label>
        @if($type === 'income')
            <label>From<select class="form-select form-select-lg" name="source">@foreach(array_keys($cats) as $cat)<option>{{ $cat }}</option>@endforeach</select></label>
        @endif
        <div>
            <span class="label">Category</span>
            <div class="category-grid">
                @foreach($cats as $cat => $icon)
                    <button type="button" class="category-choice {{ $loop->first ? 'active' : '' }}" data-category="{{ $cat }}" data-icon="{{ $icon }}"><i class="fa-solid {{ $icon }}"></i><span>{{ $cat }}</span></button>
                @endforeach
            </div>
        </div>
        <label>Date<input class="form-control form-control-lg" type="date" name="entry_date" value="{{ today()->toDateString() }}" required></label>
        <label>Note<textarea class="form-control" name="note" rows="4" placeholder="Optional"></textarea></label>
        <button class="btn btn-lg rounded-4 {{ $type === 'income' ? 'btn-success' : 'btn-danger' }}">Save {{ $type === 'income' ? 'Income' : 'Expense' }}</button>
    </form>
</section>
@endsection

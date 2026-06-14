<?php

namespace App\Http\Controllers;

use App\Models\MoneyEntry;
use Illuminate\Http\Request;

class MoneyController extends Controller
{
    public function index(Request $request)
    {
        $entries = $this->filteredEntries($request)->latest('entry_date')->latest()->get();
        $summary = $this->summary($request);
        return view('money.index', compact('entries', 'summary'));
    }

    public function create(Request $request)
    {
        $type = $request->query('type', 'income') === 'expense' ? 'expense' : 'income';
        return view('money.create', compact('type'));
    }

    public function store(Request $request)
    {
        $entry = MoneyEntry::create($this->validated($request) + ['user_id' => $request->user()->id]);
        return $request->expectsJson()
            ? response()->json(['message' => ucfirst($entry->type).' saved.', 'entry' => $entry, 'redirect' => route('money.index')])
            : redirect()->route('money.index')->with('status', 'Money entry saved.');
    }

    public function history(Request $request)
    {
        $entries = $this->filteredEntries($request)->latest('entry_date')->latest()->get();
        return view('money.history', compact('entries'));
    }

    public function destroy(MoneyEntry $moneyEntry)
    {
        abort_unless($moneyEntry->user_id === auth()->id(), 403);
        $moneyEntry->delete();
        return response()->json(['message' => 'Entry deleted.']);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'min:1'],
            'category' => ['required', 'string', 'max:60'],
            'source' => ['nullable', 'string', 'max:120'],
            'note' => ['nullable', 'string'],
            'entry_date' => ['required', 'date'],
            'icon' => ['nullable', 'string', 'max:60'],
        ]);

        return $data + ['icon' => 'fa-wallet'];
    }

    private function filteredEntries(Request $request)
    {
        $query = MoneyEntry::where('user_id', $request->user()->id);
        $filter = $request->query('filter', 'month');
        if ($filter === 'today') {
            $query->whereDate('entry_date', today());
        } elseif ($filter === 'week') {
            $query->whereBetween('entry_date', [now()->startOfWeek(), now()->endOfWeek()]);
        } else {
            $query->whereMonth('entry_date', now()->month)->whereYear('entry_date', now()->year);
        }

        return $query;
    }

    private function summary(Request $request): array
    {
        $base = MoneyEntry::where('user_id', $request->user()->id);
        $income = (clone $base)->where('type', 'income')->sum('amount');
        $expense = (clone $base)->where('type', 'expense')->sum('amount');
        $todayIncome = (clone $base)->where('type', 'income')->whereDate('entry_date', today())->sum('amount');
        $todayExpense = (clone $base)->where('type', 'expense')->whereDate('entry_date', today())->sum('amount');
        $monthIncome = (clone $base)->where('type', 'income')->whereMonth('entry_date', now()->month)->whereYear('entry_date', now()->year)->sum('amount');
        $monthExpense = (clone $base)->where('type', 'expense')->whereMonth('entry_date', now()->month)->whereYear('entry_date', now()->year)->sum('amount');

        return compact('income', 'expense', 'todayIncome', 'todayExpense', 'monthIncome', 'monthExpense') + ['cash' => $income - $expense];
    }
}

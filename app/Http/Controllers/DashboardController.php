<?php

namespace App\Http\Controllers;

use App\Models\MoneyEntry;
use App\Models\Note;
use App\Models\Reminder;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard', $this->payload($request));
    }

    public function data(Request $request)
    {
        return response()->json($this->payload($request));
    }

    private function payload(Request $request): array
    {
        $user = $request->user();
        $today = today();
        $base = MoneyEntry::where('user_id', $user->id);
        $income = (clone $base)->where('type', 'income')->sum('amount');
        $expense = (clone $base)->where('type', 'expense')->sum('amount');
        $monthIncome = (clone $base)->where('type', 'income')->whereMonth('entry_date', $today->month)->whereYear('entry_date', $today->year)->sum('amount');
        $monthExpense = (clone $base)->where('type', 'expense')->whereMonth('entry_date', $today->month)->whereYear('entry_date', $today->year)->sum('amount');

        return [
            'cashInHand' => $income - $expense,
            'todayIncome' => (clone $base)->where('type', 'income')->whereDate('entry_date', $today)->sum('amount'),
            'todaySpent' => (clone $base)->where('type', 'expense')->whereDate('entry_date', $today)->sum('amount'),
            'monthIncome' => $monthIncome,
            'monthExpense' => $monthExpense,
            'monthSaved' => $monthIncome - $monthExpense,
            'todayTasks' => Task::where('user_id', $user->id)->whereDate('plan_date', $today)->orderBy('start_time')->limit(5)->get(),
            'quickNotes' => Note::where('user_id', $user->id)->orderByDesc('is_pinned')->latest()->limit(4)->get(),
            'upcomingReminders' => Reminder::where('user_id', $user->id)->where('status', 'upcoming')->whereDate('reminder_date', '>=', $today)->orderBy('reminder_date')->orderBy('reminder_time')->limit(3)->get(),
            'greeting' => $this->greeting(),
        ];
    }

    private function greeting(): string
    {
        $hour = Carbon::now()->hour;
        return $hour < 12 ? 'Good Morning' : ($hour < 17 ? 'Good Afternoon' : 'Good Evening');
    }
}

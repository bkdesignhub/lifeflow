<?php

namespace App\Http\Controllers;

use App\Models\MoneyEntry;
use App\Models\Task;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    public function index()
    {
        return view('assistant');
    }

    public function ask(Request $request)
    {
        $request->validate(['message' => ['required', 'string', 'max:300']]);
        $text = strtolower($request->message);
        $userId = $request->user()->id;

        if (str_contains($text, 'cash')) {
            $income = MoneyEntry::where('user_id', $userId)->where('type', 'income')->sum('amount');
            $expense = MoneyEntry::where('user_id', $userId)->where('type', 'expense')->sum('amount');
            $reply = 'Your cash in hand is Rs '.number_format($income - $expense, 2).'.';
        } elseif (str_contains($text, 'plan')) {
            $count = Task::where('user_id', $userId)->whereDate('plan_date', today())->count();
            $reply = $count ? "You have {$count} activities planned today." : 'No plan added for today yet.';
        } else {
            $reply = 'Assistant is ready. AI connection can be added later; for now I can answer simple LifeFlow summaries.';
        }

        return response()->json(['message' => $reply]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\MoneyEntry;
use App\Models\Note;
use App\Models\Reminder;
use App\Models\Task;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function __invoke(Request $request)
    {
        $userId = $request->user()->id;
        return response()->json([
            'exported_at' => now()->toIso8601String(),
            'user' => $request->user()->only(['name', 'email']),
            'tasks' => Task::where('user_id', $userId)->get(),
            'notes' => Note::where('user_id', $userId)->get(),
            'money_entries' => MoneyEntry::where('user_id', $userId)->get(),
            'reminders' => Reminder::where('user_id', $userId)->get(),
            'settings' => $request->user()->settings,
        ])->header('Content-Disposition', 'attachment; filename="lifeflow-backup.json"');
    }
}

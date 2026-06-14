<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function index(Request $request)
    {
        $query = Reminder::where('user_id', $request->user()->id);
        if (in_array($request->filter, ['upcoming', 'completed'], true)) {
            $query->where('status', $request->filter);
        }
        $reminders = $query->orderBy('reminder_date')->orderBy('reminder_time')->get();
        return view('reminders.index', compact('reminders'));
    }

    public function create()
    {
        return view('reminders.create', ['reminder' => new Reminder(['reminder_date' => today(), 'repeat' => 'none'])]);
    }

    public function store(Request $request)
    {
        $reminder = Reminder::create($this->validated($request) + ['user_id' => $request->user()->id]);
        return $this->saved($request, 'Reminder saved.', $reminder);
    }

    public function edit(Reminder $reminder)
    {
        $this->authorizeOwner($reminder);
        return view('reminders.create', compact('reminder'));
    }

    public function update(Request $request, Reminder $reminder)
    {
        $this->authorizeOwner($reminder);
        $reminder->update($this->validated($request));
        return $this->saved($request, 'Reminder updated.', $reminder);
    }

    public function complete(Reminder $reminder)
    {
        $this->authorizeOwner($reminder);
        $reminder->update(['status' => 'completed']);
        return response()->json(['message' => 'Reminder completed.', 'reminder' => $reminder]);
    }

    public function destroy(Reminder $reminder)
    {
        $this->authorizeOwner($reminder);
        $reminder->delete();
        return response()->json(['message' => 'Reminder deleted.']);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:140'],
            'note' => ['nullable', 'string'],
            'reminder_date' => ['required', 'date'],
            'reminder_time' => ['nullable', 'date_format:H:i'],
            'repeat' => ['required', 'in:none,daily,weekly,monthly,yearly'],
            'push_enabled' => ['nullable', 'boolean'],
        ]);

        return $data + ['push_enabled' => $request->boolean('push_enabled', true), 'status' => 'upcoming'];
    }

    private function authorizeOwner(Reminder $reminder): void
    {
        abort_unless($reminder->user_id === auth()->id(), 403);
    }

    private function saved(Request $request, string $message, Reminder $reminder)
    {
        return $request->expectsJson()
            ? response()->json(['message' => $message, 'reminder' => $reminder, 'redirect' => route('reminders.index')])
            : redirect()->route('reminders.index')->with('status', $message);
    }
}

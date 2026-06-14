<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ? date('Y-m-d', strtotime($request->date)) : today()->toDateString();
        $tasks = Task::where('user_id', $request->user()->id)->whereDate('plan_date', $date)->orderBy('start_time')->get();
        return view('tasks.index', compact('tasks', 'date'));
    }

    public function create()
    {
        return view('tasks.create', ['task' => new Task(['plan_date' => today(), 'repeat' => 'daily', 'reminder_minutes' => 10, 'icon' => 'fa-dumbbell'])]);
    }

    public function store(Request $request)
    {
        $task = Task::create($this->validated($request) + ['user_id' => $request->user()->id]);
        return $this->saved($request, 'Task saved.', $task);
    }

    public function edit(Task $task)
    {
        $this->authorizeOwner($task);
        return view('tasks.create', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorizeOwner($task);
        $task->update($this->validated($request));
        return $this->saved($request, 'Task updated.', $task);
    }

    public function status(Request $request, Task $task)
    {
        $this->authorizeOwner($task);
        $data = $request->validate(['status' => ['required', 'in:pending,done,skipped']]);
        $task->update($data);
        return response()->json(['message' => 'Task marked '.$data['status'].'.', 'task' => $task]);
    }

    public function destroy(Task $task)
    {
        $this->authorizeOwner($task);
        $task->delete();
        return response()->json(['message' => 'Task deleted.']);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'repeat' => ['required', 'in:once,daily,weekly,monthly'],
            'reminder_minutes' => ['nullable', 'integer', 'min:0', 'max:1440'],
            'category' => ['required', 'string', 'max:50'],
            'icon' => ['required', 'string', 'max:60'],
            'plan_date' => ['required', 'date'],
            'push_enabled' => ['nullable', 'boolean'],
        ]);

        return $data + ['status' => $request->input('status', 'pending'), 'push_enabled' => $request->boolean('push_enabled', true)];
    }

    private function authorizeOwner(Task $task): void
    {
        abort_unless($task->user_id === auth()->id(), 403);
    }

    private function saved(Request $request, string $message, Task $task)
    {
        $redirect = route('tasks.index', ['date' => $task->plan_date->toDateString()]);
        return $request->expectsJson()
            ? response()->json(['message' => $message, 'task' => $task, 'redirect' => $redirect])
            : redirect($redirect)->with('status', $message);
    }
}

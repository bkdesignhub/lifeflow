<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $settings = $request->user()->settings()->firstOrCreate(['user_id' => $request->user()->id]);
        return view('settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'language' => ['required', 'string', 'max:40'],
            'date_format' => ['required', 'string', 'max:40'],
            'time_format' => ['required', 'in:12,24'],
            'theme' => ['required', 'in:light,dark'],
            'task_notifications' => ['nullable', 'boolean'],
            'reminder_notifications' => ['nullable', 'boolean'],
            'daily_summary' => ['nullable', 'boolean'],
        ]);

        $request->user()->settings()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $data + [
                'task_notifications' => $request->boolean('task_notifications'),
                'reminder_notifications' => $request->boolean('reminder_notifications'),
                'daily_summary' => $request->boolean('daily_summary'),
            ]
        );

        return response()->json(['message' => 'Settings saved.']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\NotificationToken;
use Illuminate\Http\Request;

class NotificationTokenController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate(['token' => ['required', 'string'], 'device_name' => ['nullable', 'string', 'max:120']]);
        NotificationToken::updateOrCreate(
            ['user_id' => $request->user()->id, 'token' => $data['token']],
            ['device_name' => $data['device_name'] ?? $request->userAgent(), 'last_used_at' => now()]
        );

        return response()->json(['message' => 'Notification device saved.']);
    }
}

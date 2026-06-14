<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $settings = $request->user()->settings()->firstOrCreate(['user_id' => $request->user()->id]);
        return view('profile', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$request->user()->id],
        ]);
        $request->user()->update($data);
        return response()->json(['message' => 'Profile updated.']);
    }
}

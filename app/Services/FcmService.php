<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FcmService
{
    public function send(string $token, string $title, string $body, array $data = []): bool
    {
        $serverKey = config('services.firebase.server_key');
        if (! $serverKey) {
            return false;
        }

        $response = Http::withToken($serverKey)->post('https://fcm.googleapis.com/fcm/send', [
            'to' => $token,
            'notification' => compact('title', 'body'),
            'data' => $data,
        ]);

        return $response->successful();
    }
}

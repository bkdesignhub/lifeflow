<?php

namespace App\Console\Commands;

use App\Models\NotificationToken;
use App\Models\Reminder;
use App\Models\Task;
use App\Services\FcmService;
use Illuminate\Console\Command;

class SendDueNotifications extends Command
{
    protected $signature = 'lifeflow:send-due-notifications';
    protected $description = 'Send LifeFlow task, reminder, and daily spending push notifications.';

    public function handle(FcmService $fcm): int
    {
        $now = now();

        Reminder::where('status', 'upcoming')
            ->where('push_enabled', true)
            ->whereDate('reminder_date', $now->toDateString())
            ->whereTime('reminder_time', '<=', $now->format('H:i:s'))
            ->whereNull('last_notified_at')
            ->each(function (Reminder $reminder) use ($fcm) {
                $this->notifyUser($fcm, $reminder->user_id, $reminder->title, $reminder->note ?: 'LifeFlow reminder');
                $reminder->update(['last_notified_at' => now()]);
            });

        Task::where('status', 'pending')
            ->where('push_enabled', true)
            ->whereDate('plan_date', $now->toDateString())
            ->whereNotNull('start_time')
            ->whereTime('start_time', '<=', $now->copy()->addMinutes(10)->format('H:i:s'))
            ->each(fn (Task $task) => $this->notifyUser($fcm, $task->user_id, $task->title, 'Your planned activity is coming up.'));

        if ($now->format('H:i') === '22:00') {
            NotificationToken::query()->select('user_id')->distinct()->each(
                fn ($row) => $this->notifyUser($fcm, $row->user_id, "Did you add today's spending?", 'Keep your Cash Pilot accurate.')
            );
        }

        return self::SUCCESS;
    }

    private function notifyUser(FcmService $fcm, int $userId, string $title, string $body): void
    {
        NotificationToken::where('user_id', $userId)->each(
            fn (NotificationToken $token) => $fcm->send($token->token, $title, $body, ['app' => 'LifeFlow'])
        );
    }
}

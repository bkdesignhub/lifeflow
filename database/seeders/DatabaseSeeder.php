<?php

namespace Database\Seeders;

use App\Models\MoneyEntry;
use App\Models\Note;
use App\Models\Reminder;
use App\Models\Task;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Bharath',
            'email' => 'bharath@example.com',
            'password' => 'password',
        ]);

        $user = User::where('email', 'bharath@example.com')->first();
        UserSetting::firstOrCreate(['user_id' => $user->id]);

        foreach ([
            ['Gym', '07:00', '08:00', 'done', 'fa-dumbbell'],
            ['Learn English', '10:00', '11:00', 'pending', 'fa-book-open'],
            ['Python Practice', '12:00', '13:00', 'pending', 'fa-code'],
            ['Freelance Work', '15:00', '16:00', 'pending', 'fa-briefcase'],
            ['Reading Books', '20:00', '21:00', 'pending', 'fa-book'],
        ] as [$title, $start, $end, $status, $icon]) {
            Task::create([
                'user_id' => $user->id,
                'title' => $title,
                'start_time' => $start,
                'end_time' => $end,
                'repeat' => 'daily',
                'reminder_minutes' => 10,
                'category' => 'Personal',
                'icon' => $icon,
                'status' => $status,
                'plan_date' => today(),
            ]);
        }

        foreach ([
            ['Laravel Project Idea', 'Build mini SaaS for freelancers', 'Work', 'soft-yellow', true],
            ['Python Learning', 'Learn Django next', 'Learning', 'soft-purple', false],
            ['Grocery List', 'Milk, Eggs, Bread, Banana', 'Personal', 'soft-yellow', false],
            ['YouTube Ideas', 'Laravel tutorial series', 'Ideas', 'soft-pink', false],
            ['Book Notes', 'Atomic Habits - Chapter 1 Notes', 'Learning', 'soft-blue', false],
        ] as [$title, $body, $category, $color, $pinned]) {
            Note::create(compact('title', 'body', 'category', 'color') + ['user_id' => $user->id, 'is_pinned' => $pinned]);
        }

        foreach ([
            ['income', 1000, 'Freelance', 'Freelance Payment', today(), 'fa-laptop-code'],
            ['expense', 250, 'Food', null, today(), 'fa-burger'],
            ['expense', 50, 'Tea', null, today(), 'fa-mug-hot'],
            ['expense', 200, 'Petrol', null, today()->subDay(), 'fa-gas-pump'],
            ['income', 500, 'Friend', 'Client Payment', today()->subDay(), 'fa-user-group'],
        ] as [$type, $amount, $category, $source, $date, $icon]) {
            MoneyEntry::create([
                'user_id' => $user->id,
                'type' => $type,
                'amount' => $amount,
                'category' => $category,
                'source' => $source,
                'entry_date' => $date,
                'icon' => $icon,
            ]);
        }

        foreach ([
            ['Internet Bill Payment', today()->addDay(), '10:00', 'none'],
            ['Gym Fees', today()->addDays(5), '09:00', 'monthly'],
            ['Interview at ABC Company', today()->addDays(18), '14:00', 'none'],
            ['Mom Birthday', today()->addMonth(), null, 'yearly'],
            ['Car Service', today()->addMonth()->addDays(5), '11:00', 'none'],
        ] as [$title, $date, $time, $repeat]) {
            Reminder::create([
                'user_id' => $user->id,
                'title' => $title,
                'reminder_date' => $date,
                'reminder_time' => $time,
                'repeat' => $repeat,
                'push_enabled' => true,
            ]);
        }
    }
}

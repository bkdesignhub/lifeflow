<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LifeFlowPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_lifeflow_pages_render(): void
    {
        $user = User::factory()->create();
        UserSetting::create(['user_id' => $user->id]);

        foreach ([
            '/dashboard',
            '/tasks',
            '/tasks/create',
            '/notes',
            '/money',
            '/money/create?type=income',
            '/money/history',
            '/reminders',
            '/reminders/create',
            '/assistant',
            '/profile',
            '/settings',
            '/install',
        ] as $uri) {
            $this->actingAs($user)->get($uri)->assertOk();
        }
    }

    public function test_ajax_create_endpoints_work(): void
    {
        $user = User::factory()->create();
        UserSetting::create(['user_id' => $user->id]);

        $this->actingAs($user)->postJson('/tasks', [
            'title' => 'Gym',
            'start_time' => '07:00',
            'end_time' => '08:00',
            'repeat' => 'daily',
            'reminder_minutes' => 10,
            'category' => 'Personal',
            'icon' => 'fa-dumbbell',
            'plan_date' => today()->toDateString(),
            'push_enabled' => true,
        ])->assertOk()->assertJsonPath('message', 'Task saved.');

        $this->actingAs($user)->postJson('/notes', [
            'title' => 'Laravel Project Idea',
            'body' => 'Build mini SaaS for freelancers',
            'category' => 'Work',
            'color' => 'soft-yellow',
        ])->assertOk()->assertJsonPath('message', 'Note saved.');

        $this->actingAs($user)->postJson('/money', [
            'type' => 'income',
            'amount' => 1000,
            'category' => 'Freelance',
            'source' => 'Client',
            'entry_date' => today()->toDateString(),
        ])->assertOk()->assertJsonPath('message', 'Income saved.');

        $this->actingAs($user)->postJson('/reminders', [
            'title' => 'Gym Fees',
            'reminder_date' => today()->addDay()->toDateString(),
            'reminder_time' => '09:00',
            'repeat' => 'monthly',
            'push_enabled' => true,
        ])->assertOk()->assertJsonPath('message', 'Reminder saved.');
    }
}

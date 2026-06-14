<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->enum('repeat', ['once', 'daily', 'weekly', 'monthly'])->default('once');
            $table->unsignedSmallInteger('reminder_minutes')->nullable();
            $table->string('category')->default('Personal');
            $table->string('icon')->default('fa-calendar-check');
            $table->enum('status', ['pending', 'done', 'skipped'])->default('pending');
            $table->date('plan_date');
            $table->boolean('push_enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('task_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('task_id')->nullable()->constrained()->nullOnDelete();
            $table->date('log_date');
            $table->enum('status', ['pending', 'done', 'skipped'])->default('pending');
            $table->timestamps();
            $table->unique(['task_id', 'log_date']);
        });

        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('body')->nullable();
            $table->enum('category', ['Ideas', 'Learning', 'Work', 'Personal', 'Important'])->default('Personal');
            $table->string('color')->default('soft-yellow');
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();
        });

        Schema::create('money_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['income', 'expense']);
            $table->decimal('amount', 12, 2);
            $table->string('category');
            $table->string('source')->nullable();
            $table->text('note')->nullable();
            $table->date('entry_date');
            $table->string('icon')->default('fa-wallet');
            $table->timestamps();
        });

        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('note')->nullable();
            $table->date('reminder_date');
            $table->time('reminder_time')->nullable();
            $table->enum('repeat', ['none', 'daily', 'weekly', 'monthly', 'yearly'])->default('none');
            $table->boolean('push_enabled')->default(true);
            $table->enum('status', ['upcoming', 'completed'])->default('upcoming');
            $table->timestamp('last_notified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('language')->default('English');
            $table->string('date_format')->default('d M, Y');
            $table->string('time_format')->default('12');
            $table->enum('theme', ['light', 'dark'])->default('light');
            $table->boolean('task_notifications')->default(true);
            $table->boolean('reminder_notifications')->default(true);
            $table->boolean('daily_summary')->default(true);
            $table->timestamps();
        });

        Schema::create('notification_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('token', 255);
            $table->string('device_name')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'token'], 'user_notification_token_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_tokens');
        Schema::dropIfExists('user_settings');
        Schema::dropIfExists('reminders');
        Schema::dropIfExists('money_entries');
        Schema::dropIfExists('notes');
        Schema::dropIfExists('task_logs');
        Schema::dropIfExists('tasks');
    }
};

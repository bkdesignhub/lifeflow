<?php

use App\Http\Controllers\AssistantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\MoneyController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\NotificationTokenController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::view('/offline', 'offline')->name('offline');

Route::middleware('guest')->group(function () {
    Route::get('/', fn () => redirect()->route('login'));
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/forgot-password', [AuthController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgot'])->name('password.email');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'data'])->name('dashboard.data');
    Route::resource('tasks', TaskController::class)->except(['show']);
    Route::patch('/tasks/{task}/status', [TaskController::class, 'status'])->name('tasks.status');
    Route::resource('notes', NoteController::class)->except(['create', 'edit']);
    Route::patch('/notes/{note}/pin', [NoteController::class, 'pin'])->name('notes.pin');
    Route::get('/money', [MoneyController::class, 'index'])->name('money.index');
    Route::get('/money/create', [MoneyController::class, 'create'])->name('money.create');
    Route::post('/money', [MoneyController::class, 'store'])->name('money.store');
    Route::get('/money/history', [MoneyController::class, 'history'])->name('money.history');
    Route::delete('/money/{moneyEntry}', [MoneyController::class, 'destroy'])->name('money.destroy');
    Route::resource('reminders', ReminderController::class)->except(['show']);
    Route::patch('/reminders/{reminder}/complete', [ReminderController::class, 'complete'])->name('reminders.complete');
    Route::get('/assistant', [AssistantController::class, 'index'])->name('assistant.index');
    Route::post('/assistant/ask', [AssistantController::class, 'ask'])->name('assistant.ask');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/notification-tokens', [NotificationTokenController::class, 'store'])->name('notification-tokens.store');
    Route::get('/export', ExportController::class)->name('export');
    Route::view('/install', 'install')->name('install');
});

<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('lifeflow:send-due-notifications')->everyMinute();

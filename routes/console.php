<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/**
 * Define the schedule.
 * 
 * Schedule the install command to run daily at 04:45 if the APP_DEMO environment variable is set to true.
 */
Schedule::command('install')->dailyAt('04:45')->when(function () {
    return env('APP_DEMO', false);
})->description('Refresh demo data daily');

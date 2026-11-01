<?php

use App\Models\ActivityLog;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(fn () => ActivityLog::prune(90))->daily()->name('prune-activity-logs');
Schedule::call(fn () => \App\Models\Order::batalkanKedaluwarsa(24))->hourly()->name('cancel-expired-orders');

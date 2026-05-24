<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule analytics data cleanup to run monthly
Schedule::command('analytics:cleanup --retention-days=365')
    ->monthly()
    ->description('Clean up old analytics data')
    ->withoutOverlapping();

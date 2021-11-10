<?php

namespace App;

use Illuminate\Foundation\Console\Kernel;
use Illuminate\Console\Scheduling\Schedule;

class ConsoleKernel extends Kernel
{
    protected $commands = [];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('tool-requests:send-reminders')
            ->timezone('America/New_York')
            ->dailyAt(7) // Run daily at 7:00 AM.
            ->runInBackground()
            ->withoutOverlapping();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Console/Commands');
    }
}

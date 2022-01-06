<?php

namespace App;

use Illuminate\Foundation\Console\Kernel;
use Illuminate\Console\Scheduling\Schedule;

class ConsoleKernel extends Kernel
{
    protected $commands = [];

    protected function schedule(Schedule $schedule)
    {
        //
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Console/Commands');
    }
}

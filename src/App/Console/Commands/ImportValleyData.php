<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportValleyData extends Command
{
    protected $signature = 'import:all';

    protected $description = 'Import Valley of the Shadow data for all models';

    public function handle()
    {
        $this->call('import:free-black-registry');
    }
}

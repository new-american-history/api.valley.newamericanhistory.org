<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportValleyData extends Command
{
    protected $signature = 'import:all';

    protected $description = 'Import Valley of the Shadow data for all models';

    public function handle()
    {
        $this->call('import:agricultural-census');
        $this->call('import:augusta-tax-records');
        $this->call('import:battlefield-correspondence');
        $this->call('import:chambersburg-claims');
        $this->call('import:church-records');
        $this->call('import:civil-war-images');
        $this->call('import:cohabitation-records');
        $this->call('import:diaries');
        $this->call('import:fire-insurance-policies');
        $this->call('import:franklin-tax-records');
        $this->call('import:free-black-registry');
        $this->call('import:letters');
        $this->call('import:manufacturing-census');
        $this->call('import:memory-articles');
        $this->call('import:newspapers');
        $this->call('import:population-census');
        $this->call('import:regimental-movements');
        $this->call('import:slaveowning-census');
        $this->call('import:soldier-dossiers');
        $this->call('import:southern-claims-commission');
        $this->call('import:veteran-census');
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SlaMonitorService;

class CheckSlaCommand extends Command
{
    protected $signature = 'sla:check';
    protected $description = 'Evaluate SLAs and trigger escalations';

    public function handle(SlaMonitorService $monitor)
    {
        $this->info('Running SLA checks...');
        $results = $monitor->evaluateAll();
        $this->info('Evaluated '.count($results).' complaints.');

        return 0;
    }
}

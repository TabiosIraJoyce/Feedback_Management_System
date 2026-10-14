<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (App\Models\SlaConfig::all() as $config) {
    echo $config->category . ' => ' . $config->hours . ' hours' . PHP_EOL;
}

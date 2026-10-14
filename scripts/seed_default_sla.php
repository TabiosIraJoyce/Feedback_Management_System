<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SlaConfig;

$defaults = [
    ['category' => 'safety_concern', 'hours' => 1, 'enabled' => true],
    ['category' => 'fuel_quality', 'hours' => 4, 'enabled' => true],
    ['category' => 'service_concern', 'hours' => 24, 'enabled' => true],
];

foreach ($defaults as $data) {
    SlaConfig::updateOrCreate(
        ['category' => $data['category']],
        ['hours' => $data['hours'], 'enabled' => $data['enabled']]
    );
}

echo "Default SLA configs seeded.\n";

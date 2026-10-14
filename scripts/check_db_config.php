<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

echo 'DB default: '.Config::get('database.default')."\n";
echo 'DB database: '.Config::get('database.connections.sqlite.database')."\n";

try {
    $count = DB::table('users')->count();
    echo "users count: $count\n";
} catch (Throwable $e) {
    echo 'error: '.$e->getMessage()."\n";
}

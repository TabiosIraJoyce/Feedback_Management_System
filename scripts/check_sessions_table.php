<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name='sessions'");
    echo 'sessions table: ' . (count($tables) ? 'exists' : 'missing') . PHP_EOL;
    if (count($tables)) {
        $info = DB::select("PRAGMA table_info('sessions')");
        foreach ($info as $col) {
            echo "$col->name ($col->type)" . PHP_EOL;
        }
    }
} catch (Throwable $e) {
    echo 'error: ' . $e->getMessage() . PHP_EOL;
}

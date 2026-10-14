<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$u = App\Models\User::where('email','iratbios@gmail.com')->first();
if ($u) {
    echo 'found: ' . $u->email . PHP_EOL;
    echo 'roles: ' . json_encode($u->getRoleNames()->toArray()) . PHP_EOL;
} else {
    echo 'not found' . PHP_EOL;
}

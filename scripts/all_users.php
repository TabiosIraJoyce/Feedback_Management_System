<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo 'All users in database:' . PHP_EOL;
$users = App\Models\User::all();
foreach ($users as $user) {
    echo 'ID: ' . $user->id . ' | Name: ' . $user->name . ' | Email: ' . $user->email . ' | Roles: ' . json_encode($user->getRoleNames()->toArray()) . PHP_EOL;
}

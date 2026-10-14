<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$user = User::where('email', 'iratbios@gmail.com')->first();
if (! $user) {
    echo "User not found\n";
    exit(1);
}

echo "id: {$user->id}\n";
echo "email: {$user->email}\n";
echo "name: {$user->name}\n";
echo "password hash: {$user->password}\n";
echo "verify gl3dc0MC2002: ".(password_verify('gl3dc0MC2002', $user->password) ? 'yes' : 'no')."\n";

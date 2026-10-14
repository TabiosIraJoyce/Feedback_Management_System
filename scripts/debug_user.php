<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$u = App\Models\User::where('email','iratbios@gmail.com')->first();
if ($u) {
    echo 'User ID: ' . $u->id . PHP_EOL;
    echo 'Email: ' . $u->email . PHP_EOL;
    echo 'Name: ' . $u->name . PHP_EOL;
    echo 'All roles: ' . json_encode($u->getRoleNames()->toArray()) . PHP_EOL;
    echo 'Has Super Admin: ' . ($u->hasRole('Super Admin') ? 'yes' : 'no') . PHP_EOL;
    
    // Check all users with this email
    echo PHP_EOL . 'All users with this email:' . PHP_EOL;
    $users = App\Models\User::where('email','iratbios@gmail.com')->get();
    foreach ($users as $user) {
        echo '  ID: ' . $user->id . ' | Roles: ' . json_encode($user->getRoleNames()->toArray()) . PHP_EOL;
    }
} else {
    echo 'not found' . PHP_EOL;
}

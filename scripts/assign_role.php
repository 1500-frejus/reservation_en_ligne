<?php
$vendor = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($vendor)) {
    echo "vendor autoload not found\n";
    exit(1);
}
require $vendor;

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::first();
if (!$user) {
    echo "No user found\n";
    exit(1);
}

if (!method_exists($user, 'assignRole')) {
    echo "User model does not have assignRole() method. Has Spatie trait been loaded?\n";
} else {
    $user->assignRole('super_admin');
    echo implode(',', $user->getRoleNames()->toArray()) . "\n";
}

// debug helpers
echo "class_uses(User):\n";
print_r(class_uses(App\Models\User::class));
echo "method exists assignRole: ";
var_export(method_exists(App\Models\User::class, 'assignRole'));
echo "\n";

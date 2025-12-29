<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = $argv[1] ?? null;
try {
    if ($email) {
        $user = App\Models\User::where('email', $email)->first();
    } else {
        $user = App\Models\User::first();
    }

    if (! $user) {
        echo json_encode(['error' => 'no_user']);
        exit(0);
    }

    $roles = [];
    if (class_exists('Spatie\\Permission\\Models\\Role')) {
        try {
            $roles = $user->getRoleNames()->toArray();
        } catch (Throwable $e) {
            $roles = ['error' => $e->getMessage()];
        }
    }

    $org = null;
    if ($user->organization_id) {
        $orgModel = App\Models\Organization::find($user->organization_id);
        if ($orgModel) {
            $org = ['id' => $orgModel->id, 'name' => $orgModel->name, 'slug' => $orgModel->slug];
        }
    }

    echo json_encode([
        'id' => $user->id,
        'email' => $user->email,
        'organization_id' => $user->organization_id,
        'organization' => $org,
        'roles' => $roles,
    ], JSON_PRETTY_PRINT) . PHP_EOL;
} catch (Throwable $e) {
    echo json_encode(['error' => $e->getMessage()]) . PHP_EOL;
}

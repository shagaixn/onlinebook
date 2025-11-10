<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

try {
    $u = User::where('email', 'admin@example.com')->first();
    if ($u) {
        echo "FOUND: id={$u->id} email={$u->email} role={$u->role}" . PHP_EOL;
    } else {
        echo "NOT_FOUND" . PHP_EOL;
    }
} catch (Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
}

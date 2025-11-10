<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

try {
    $u = User::firstOrCreate(
        ['email' => 'admin@example.com'],
        [
            'name' => 'Admin',
            'password' => Hash::make('admin_password'),
            'role' => 'admin',
        ]
    );

    // Хэрвээ өмнө нь байсан бол ч админ болгоно, нууц үгийг шинэчилнэ
    $u->role = 'admin';
    $u->password = Hash::make('admin_password');
    $u->save();

    echo "OK: id={$u->id} email={$u->email} role={$u->role}" . PHP_EOL;
} catch (Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
}

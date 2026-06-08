<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('name', 'like', '%Rodrigo%')->first();
if ($user) {
    $user->role = 'admin';
    $user->save();
    echo "Usuario '{$user->name}' actualizado a rol: {$user->role}\n";
} else {
    echo "Usuario no encontrado\n";
}

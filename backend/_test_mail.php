<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$status = Illuminate\Support\Facades\Password::sendResetLink(['email' => 'sara@email.com']);
echo "Status: " . $status . "\n";
echo "Expected: " . Illuminate\Support\Facades\Password::RESET_LINK_SENT . "\n";

$log = shell_exec('tail -1 ' . __DIR__ . '/storage/logs/laravel.log');
if (preg_match('/password\/reset/i', $log, $m)) {
    echo "RESET LINK FOUND IN LOG\n";
    preg_match('/http[^\s]+/', $log, $url);
    echo "URL: " . ($url[0] ?? 'not found') . "\n";
} else {
    echo "MAIL LOG:\n" . substr($log, 0, 500) . "\n";
}

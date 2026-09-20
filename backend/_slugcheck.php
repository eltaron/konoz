<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$rows = DB::select('select id, slug from courses');
echo 'total=' . count($rows) . PHP_EOL;
$nulls = 0;
$unique = [];
foreach ($rows as $r) {
    if (!$r->slug) { $nulls++; }
    $unique[$r->slug] = ($unique[$r->slug] ?? 0) + 1;
}
echo 'null slugs=' . $nulls . PHP_EOL;
$dups = array_filter($unique, fn($c) => $c > 1);
echo 'dup slug values=' . count($dups) . PHP_EOL;
foreach (array_slice($rows, 0, 10) as $r) {
    echo $r->id . ' => [' . $r->slug . ']' . PHP_EOL;
}
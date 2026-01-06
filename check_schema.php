<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

echo "Status Column: " . (Schema::hasColumn('orders', 'status') ? 'YES' : 'NO') . "\n";
echo "Tracking Column: " . (Schema::hasColumn('orders', 'tracking_number') ? 'YES' : 'NO') . "\n";
echo "History Table: " . (Schema::hasTable('order_status_history') ? 'YES' : 'NO') . "\n";

<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Total Simpanan Saldo: " . \App\Models\Simpanan::sum('saldo') . PHP_EOL;
echo "Total Pinjaman Jumlah: " . \App\Models\Pinjaman::sum('jumlah_pinjaman') . PHP_EOL;
echo "Total Pinjaman Saldo: " . \App\Models\Pinjaman::sum('saldo_pinjaman') . PHP_EOL;

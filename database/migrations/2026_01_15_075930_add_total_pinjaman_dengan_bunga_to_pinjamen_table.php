<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pinjamen', function (Blueprint $table) {
            $table->decimal('total_pinjaman_dengan_bunga', 15, 2)
                ->after('jumlah_pinjaman')
                ->default(0)
                ->comment('Total pinjaman termasuk bunga yang harus dibayar');
        });
    }

    public function down(): void
    {
        Schema::table('pinjamen', function (Blueprint $table) {
            $table->dropColumn('total_pinjaman_dengan_bunga');
        });
    }
};

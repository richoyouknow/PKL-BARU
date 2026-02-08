<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('simpanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained()->onDelete('cascade');
            $table->string('no_simpanan')->unique()->comment('Format: PJM-YYYYMMDD-XXXX');
            // no_rekening dihapus, karena sekarang ada di tabel anggotas
            $table->enum('jenis_simpanan', [
                'simpanan_pokok',
                'simpanan_wajib',
                'simpanan_sukarela',
                'simpanan_berjangka'
            ]);
            $table->decimal('saldo', 15, 2)->default(0);
            $table->enum('status', ['aktif', 'nonaktif', 'ditutup'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();

            // Index untuk performa query
            $table->index('anggota_id');
            $table->index('jenis_simpanan');
            $table->index('status');
            $table->index('no_simpanan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simpanans');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->enum('jenis_transaksi', ['simpanan', 'pinjaman', 'penarikan_simpanan', 'pembayaran_pinjaman']);
            $table->foreignId('anggota_id')->constrained()->onDelete('cascade');
            $table->foreignId('simpanan_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('pinjaman_id')->nullable()->constrained()->onDelete('cascade');
            $table->decimal('jumlah', 15, 2);
            $table->decimal('saldo_sebelum', 15, 2);
            $table->decimal('saldo_sesudah', 15, 2);
            $table->text('keterangan');
            $table->enum('status', ['pending', 'sukses', 'gagal', 'menunggu_verifikasi'])->default('pending');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('diverifikasi_pada')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};

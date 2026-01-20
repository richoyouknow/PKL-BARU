<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pinjamen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained()->onDelete('cascade');
            $table->string('no_pinjaman')->unique();
            $table->decimal('jumlah_pinjaman', 15, 2);
            $table->decimal('saldo_pinjaman', 15, 2);
            $table->integer('tenor'); // dalam bulan
            $table->decimal('bunga_per_tahun', 5, 2); // persentase
            $table->decimal('angsuran_per_bulan', 15, 2);
            $table->date('tanggal_pinjaman');
            $table->date('tanggal_jatuh_tempo');
            $table->enum('kategori_pinjaman', [
                'pinjaman_cash',
                'pinjaman_elektronik'
            ])->default('pinjaman_cash');
            $table->enum('status', [
                'diajukan',
                'diproses',
                'disetujui',
                'ditolak',
                'aktif',
                'lunas',
                'macet'
            ])->default('diajukan');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();



        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pinjamen');
    }
};

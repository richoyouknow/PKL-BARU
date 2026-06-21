<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            $table->index('nama');
            $table->index('grup_wilayah');
            $table->index('tanggal_daftar');
            $table->index('created_at');
        });

        Schema::table('pinjamen', function (Blueprint $table) {
            $table->index('status');
            $table->index('kategori_pinjaman');
            $table->index('tanggal_pinjaman');
            $table->index('tanggal_jatuh_tempo');
            $table->index('created_at');
        });

        Schema::table('transaksis', function (Blueprint $table) {
            $table->index('jenis_transaksi');
            $table->index('status');
            $table->index('created_at');
            $table->index('diverifikasi_pada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            $table->dropIndex(['nama']);
            $table->dropIndex(['grup_wilayah']);
            $table->dropIndex(['tanggal_daftar']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('pinjamen', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['kategori_pinjaman']);
            $table->dropIndex(['tanggal_pinjaman']);
            $table->dropIndex(['tanggal_jatuh_tempo']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropIndex(['jenis_transaksi']);
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['diverifikasi_pada']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('anggotas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
            ->unique()
            ->constrained('users')
            ->cascadeOnDelete();

            $table->string('no_registrasi', 30)->unique();
            $table->string('no_anggota', 30)->unique()->nullable();

            $table->string('nama', 100);
            $table->text('alamat')->nullable();
            $table->string('no_telepon', 20)->nullable();

            $table->enum('grup_wilayah', [
                'Karyawan Koperasi',
                'Karyawan PKWT',
                'Karyawan Tetap',
                'Non Karyawan',
                'Outsourcing',
                'Pensiun',
                'Petugas Gudang Pengolah'
            ])->default('Non Karyawan');

            $table->string('jenis_identitas', 20)->nullable();
            $table->string('no_identitas', 30)->nullable();
            $table->date('berlaku_sampai')->nullable();

            $table->string('agama', 20)->nullable();
            $table->string('tempat_lahir', 50)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Pria', 'Wanita'])->nullable();

            $table->string('nama_pasangan', 100)->nullable();
            $table->string('pekerjaan', 50)->nullable();
            $table->decimal('pendapatan', 15, 2)->nullable();

            $table->text('alamat_kantor')->nullable();
            $table->text('keterangan')->nullable();

            // Field baru untuk rekening bank
            $table->string('nama_bank', 50)->nullable()->comment('Nama bank anggota');
            $table->string('no_rekening', 30)->nullable()->comment('Nomor rekening bank anggota');
            $table->string('atas_nama', 100)->nullable()->comment('Nama pemilik rekening');

            $table->string('foto')->nullable();
            $table->date('tanggal_daftar')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index untuk performa
            $table->index('no_rekening');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggotas');
    }
};

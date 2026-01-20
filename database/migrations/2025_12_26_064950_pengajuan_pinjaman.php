<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_pinjamen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained()->onDelete('cascade');
            $table->decimal('jumlah_pinjaman', 15, 2);
            $table->integer('tenor'); // dalam bulan
            $table->text('tujuan_pinjaman');
            $table->enum('status', ['menunggu', 'diproses', 'disetujui', 'ditolak'])->default('menunggu');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('catatan_admin')->nullable();
            $table->timestamp('disetujui_pada')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_pinjamen');
    }
};

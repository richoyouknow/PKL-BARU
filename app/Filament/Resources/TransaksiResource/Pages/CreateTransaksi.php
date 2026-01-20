<?php

namespace App\Filament\Resources\TransaksiResource\Pages;

use App\Filament\Resources\TransaksiResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Simpanan;
use App\Models\Pinjaman;
use Filament\Notifications\Notification;

class CreateTransaksi extends CreateRecord
{
    protected static string $resource = TransaksiResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set admin_id dan waktu verifikasi jika status sukses
        if ($data['status'] === 'sukses') {
            $data['admin_id'] = auth()->id();
            $data['diverifikasi_pada'] = now();
        }

        // Pastikan kode_transaksi tidak di-set di sini
        // Biarkan boot method yang handle
        unset($data['kode_transaksi']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;

        // Update saldo HANYA jika status langsung sukses
        // Jika menunggu_verifikasi, akan di-update saat verifikasi
        if ($record->status === 'sukses') {
            $this->prosesTransaksi($record);
        }

        Notification::make()
            ->success()
            ->title('Transaksi Berhasil Dibuat')
            ->body("Kode Transaksi: {$record->kode_transaksi}")
            ->send();
    }

    private function prosesTransaksi($record): void
    {
        $jenis = $record->jenis_transaksi;
        $jumlah = floatval($record->jumlah);

        // Update saldo simpanan
        if (in_array($jenis, ['simpanan', 'penarikan_simpanan']) && !empty($record->simpanan_id)) {
            $simpanan = Simpanan::find($record->simpanan_id);
            if ($simpanan) {
                if ($jenis === 'simpanan') {
                    $simpanan->saldo += $jumlah;
                } else {
                    // Validasi saldo cukup untuk penarikan
                    if ($simpanan->saldo < $jumlah) {
                        Notification::make()
                            ->warning()
                            ->title('Peringatan')
                            ->body('Saldo simpanan tidak mencukupi untuk penarikan.')
                            ->send();
                        return;
                    }
                    $simpanan->saldo -= $jumlah;

                    // Pastikan saldo tidak negatif
                    if ($simpanan->saldo < 0) {
                        $simpanan->saldo = 0;
                    }
                }
                $simpanan->save();
            }
        }

        // Update saldo pinjaman
        if (in_array($jenis, ['pinjaman', 'pembayaran_pinjaman']) && !empty($record->pinjaman_id)) {
            $pinjaman = Pinjaman::find($record->pinjaman_id);
            if ($pinjaman) {
                if ($jenis === 'pinjaman') {
                    $pinjaman->saldo_pinjaman += $jumlah;
                    // Set status menjadi aktif jika masih disetujui
                    if ($pinjaman->status === 'disetujui') {
                        $pinjaman->status = 'aktif';
                    }
                } else {
                    // Pembayaran pinjaman
                    $pinjaman->saldo_pinjaman -= $jumlah;

                    // Update status pinjaman jika lunas
                    if ($pinjaman->saldo_pinjaman <= 0) {
                        $pinjaman->status = 'lunas';
                        $pinjaman->saldo_pinjaman = 0; // Set ke 0 jika ada kelebihan bayar
                    }
                }
                $pinjaman->save();
            }
        }
    }
}

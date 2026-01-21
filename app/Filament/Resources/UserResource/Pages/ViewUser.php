<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Tambahkan data anggota ke form data
        $user = $this->getRecord();

        if ($user->anggota) {
            $anggota = $user->anggota;

            $data = array_merge($data, [
                'no_registrasi' => $anggota->no_registrasi,
                'no_anggota' => $anggota->no_anggota,
                'nama_anggota' => $anggota->nama,
                'alamat_anggota' => $anggota->alamat,
                'no_telepon_anggota' => $anggota->no_telepon,
                'grup_wilayah' => $anggota->grup_wilayah,
                'jenis_identitas' => $anggota->jenis_identitas,
                'no_identitas' => $anggota->no_identitas,
                'agama' => $anggota->agama,
                'tempat_lahir' => $anggota->tempat_lahir,
                'tanggal_lahir' => $anggota->tanggal_lahir,
                'jenis_kelamin' => $anggota->jenis_kelamin,
                'nama_pasangan' => $anggota->nama_pasangan,
                'pekerjaan' => $anggota->pekerjaan,
                'pendapatan' => $anggota->pendapatan,
                'alamat_kantor' => $anggota->alamat_kantor,
                'keterangan' => $anggota->keterangan,
                'foto_url' => $anggota->foto_url,
                'tanggal_daftar' => $anggota->tanggal_daftar,
            ]);
        }

        return $data;
    }
}

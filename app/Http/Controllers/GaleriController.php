<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        $fotoPath = $request->file('foto')->store('galeri', 'public');

        Galeri::create([
            'judul' => $request->judul,
            'foto' => $fotoPath,
            'keterangan' => $request->keterangan,
            'urutan' => Galeri::max('urutan') + 1
        ]);

        return redirect()->back()->with('success', 'Galeri berhasil ditambahkan');
    }

    public function update(Request $request, Galeri $galeri)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        $data = [
            'judul' => $request->judul,
            'keterangan' => $request->keterangan,
        ];

        if ($request->hasFile('foto')) {
            if ($galeri->foto) {
                Storage::disk('public')->delete($galeri->foto);
            }
            $data['foto'] = $request->file('foto')->store('galeri', 'public');
        }

        $galeri->update($data);

        return redirect()->back()->with('success', 'Galeri berhasil diupdate');
    }

    public function destroy(Galeri $galeri)
    {
        if ($galeri->foto) {
            Storage::disk('public')->delete($galeri->foto);
        }
        
        $galeri->delete();

        return redirect()->back()->with('success', 'Galeri berhasil dihapus');
    }
}
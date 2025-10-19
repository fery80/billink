<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Paket;
use Illuminate\Support\Facades\Storage;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelanggan::with('paket');

        if ($request->filled('search')) {
            $query->where('nama_pelanggan', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_pelanggan', 'like', '%' . $request->search . '%');
        }

        $pelanggan = $query->orderBy('nama_pelanggan', 'asc')->get();
        $paket = Paket::orderBy('nama_paket', 'asc')->get();

        return view('admin.pelanggan.index', compact('pelanggan', 'paket'));
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'kode_pelanggan' => 'required|string|max:20|unique:pelanggan,kode_pelanggan',
        'nama_pelanggan' => 'required|string|max:100',
        'user_pppoe' => 'nullable|string|max:50',

        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'alamat' => 'required|string|max:255',
        'nomer_hp' => 'required|string|max:15',
        'email' => 'nullable|string|max:100',
        'id_paket' => 'required|exists:paket,id_paket',
        'tanggal_bulan_pertama' => 'required|date',
        'tanggal_tagihan' => 'required|date',
        'status_pembayaran' => 'required|in:lunas,belumbayar,telat',
        'status_langganan' => 'required|in:aktif,nonaktif,tunggakan,isolir',
        // ⛔ jangan validasi tanggal_aktivasi
    ]);

    // 📸 Simpan foto jika ada
    if ($request->hasFile('foto')) {
        $validated['foto'] = $request->file('foto')->store('pelanggan', 'public');
    }

    // 🕒 isi tanggal aktivasi otomatis
    $validated['tanggal_aktivasi'] = now();

    Pelanggan::create($validated);

    return redirect()->route('pelanggan.index')->with('success', '✅ Pelanggan berhasil ditambahkan!');
}
public function daftar()
{
    $pelanggan = Pelanggan::with('paket')->orderBy('nama_pelanggan', 'asc')->get();
    return view('admin.pelanggan.daftar_pelanggan', compact('pelanggan'));
}


    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $paket = Paket::orderBy('nama_paket', 'asc')->get();
        return view('admin.pelanggan.edit', compact('pelanggan', 'paket'));
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $validated = $request->validate([
            'kode_pelanggan' => 'required|string|max:20|unique:pelanggan,kode_pelanggan,' . $id . ',id_pelanggan',
            'nama_pelanggan' => 'required|string|max:100',
            'user_pppoe' => 'nullable|string|max:50',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'alamat' => 'required|string|max:255',
            'nomer_hp' => 'required|string|max:15',
            'id_paket' => 'required|exists:paket,id_paket',
            'tanggal_bulan_pertama' => 'required|date',
            'tanggal_tagihan' => 'required|date',
            'status_pembayaran' => 'required|in:lunas,belumbayar,telat',
            'status_langganan' => 'required|in:aktif,nonaktif,tunggakan,isolir',
            'tanggal_aktivasi' => 'required|date',
        ]);

        if ($request->hasFile('foto')) {
            if ($pelanggan->foto && Storage::disk('public')->exists($pelanggan->foto)) {
                Storage::disk('public')->delete($pelanggan->foto);
            }
            $validated['foto'] = $request->file('foto')->store('pelanggan', 'public');
        }

        $pelanggan->update($validated);
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        if ($pelanggan->foto && Storage::disk('public')->exists($pelanggan->foto)) {
            Storage::disk('public')->delete($pelanggan->foto);
        }
        $pelanggan->delete();

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dihapus!');
    }
}

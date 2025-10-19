<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paket;
use Illuminate\Validation\Rule;

class PaketController extends Controller
{
    public function index()
    {
        $paket = Paket::orderBy('id_paket', 'desc')->get();
        return view('admin.paket', compact('paket'));
    }

    // 🟢 CREATE / STORE
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:100|unique:paket,nama_paket',
            'kecepatan'  => 'required|string|max:50',
            'harga'      => 'required|numeric|min:0',
            'deskripsi'  => 'nullable|string',
        ], [
            'nama_paket.unique' => 'Nama paket sudah digunakan, silakan pilih nama lain.',
        ]);

        Paket::create($validated);
        return redirect()->route('paket.index')->with('success', 'Paket berhasil ditambahkan!');
    }

    // 🟡 EDIT / UPDATE
    public function edit($id)
    {
        $paket = Paket::orderBy('id_paket', 'desc')->get();
        $editPaket = Paket::findOrFail($id);
        return view('admin.paket', compact('paket', 'editPaket'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_paket' => [
                'required',
                'string',
                'max:100',
                Rule::unique('paket', 'nama_paket')->ignore($id, 'id_paket'),
            ],
            'kecepatan'  => 'required|string|max:50',
            'harga'      => 'required|numeric|min:0',
            'deskripsi'  => 'nullable|string',
        ], [
            'nama_paket.unique' => 'Nama paket sudah digunakan, silakan pilih nama lain.',
        ]);

        $paket = Paket::findOrFail($id);
        $paket->update($validated);

        return redirect()->route('paket.index')->with('success', 'Paket berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Paket::findOrFail($id)->delete();
        return redirect()->route('paket.index')->with('success', 'Paket berhasil dihapus!');
    }
}

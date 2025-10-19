<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Pelanggan;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    // 📋 Daftar Pembayaran
    public function index(Request $request)
    {
        $query = Pembayaran::with('pelanggan')->orderBy('tanggal_bayar', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pelanggan', function ($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%$search%")
                  ->orWhere('kode_pelanggan', 'like', "%$search%");
            });
        }

        $pembayaran = $query->paginate(10);
        return view('admin.pembayaran.index', compact('pembayaran'));
    }

    // 🧾 Form Tambah Pembayaran
    public function create(Request $request)
    {
        $pelangganQuery = Pelanggan::with('paket')->orderBy('nama_pelanggan', 'asc');

        if ($request->filled('q')) {
            $q = $request->q;
            $pelangganQuery->where('nama_pelanggan', 'like', "%$q%")
                           ->orWhere('kode_pelanggan', 'like', "%$q%");
        }

        $pelanggan = $pelangganQuery->get();
        return view('admin.pembayaran.create', compact('pelanggan'));
    }

    // 💾 Simpan Pembayaran
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pelanggan' => 'required|exists:pelanggan,id_pelanggan',
            'metode_pembayaran' => 'required|in:cash,transfer,cod',
            'tanggal_bayar' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        // Ambil data pelanggan + paket
        $pelanggan = Pelanggan::with('paket')->findOrFail($request->id_pelanggan);
        if (!$pelanggan->paket) {
            return back()->with('error', '❌ Pelanggan belum memiliki paket!');
        }

        // 💰 Jumlah otomatis dari paket
        $validated['jumlah_bayar'] = $pelanggan->paket->harga;

        // 📅 Periode otomatis: bulan setelah tanggal bayar
        $tgl = Carbon::parse($request->tanggal_bayar);
        $periodeBulan = $tgl->addMonth()->translatedFormat('F Y'); // contoh: Februari 2025
        $validated['periode'] = $periodeBulan;

        Pembayaran::create($validated);

        return redirect()->route('pembayaran.index')->with('success', '✅ Pembayaran berhasil ditambahkan!');
    }

    // 🗑️ Hapus Pembayaran
    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->delete();

        return back()->with('success', '🗑️ Pembayaran berhasil dihapus!');
    }
}

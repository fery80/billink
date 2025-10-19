<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan', date('m'));
        $search = $request->get('search'); // 🔍 untuk pencarian pelanggan

        // 📆 Data pembayaran bulan & tahun terpilih
        $pembayaran = Pembayaran::with('pelanggan')
            ->whereYear('tanggal_bayar', $tahun)
            ->whereMonth('tanggal_bayar', $bulan)
            ->get();

        // 💰 Total pemasukan bulan ini
        $totalPemasukan = $pembayaran->sum('jumlah_bayar');

        // 👥 Hitung pelanggan yang bayar & belum bayar
        $pelangganTotal = Pelanggan::count();
        $pelangganBayar = $pembayaran->pluck('id_pelanggan')->unique()->count();
        $pelangganBelumBayar = $pelangganTotal - $pelangganBayar;

        // 📈 Rekap pemasukan tiap bulan
        $rekapBulanan = Pembayaran::select(
            DB::raw('YEAR(tanggal_bayar) as tahun'),
            DB::raw('MONTH(tanggal_bayar) as bulan'),
            DB::raw('SUM(jumlah_bayar) as total')
        )
        ->groupBy('tahun', 'bulan')
        ->orderBy('tahun', 'desc')
        ->orderBy('bulan', 'desc')
        ->get();

        // 🧾 Riwayat pembayaran tiap pelanggan + filter nama/kode
        $riwayatPelangganQuery = Pelanggan::with(['pembayaran' => function ($q) {
            $q->orderBy('tanggal_bayar', 'desc');
        }]);

        if ($search) {
            $riwayatPelangganQuery->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%$search%")
                  ->orWhere('kode_pelanggan', 'like', "%$search%");
            });
        }

        $riwayatPelanggan = $riwayatPelangganQuery->orderBy('nama_pelanggan')->get();

        return view('admin.laporan.index', compact(
            'pembayaran',
            'totalPemasukan',
            'pelangganBayar',
            'pelangganBelumBayar',
            'rekapBulanan',
            'riwayatPelanggan',
            'bulan',
            'tahun',
            'search'
        ));
    }
}

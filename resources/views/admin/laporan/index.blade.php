@extends('admin.layouts.app')

@section('title', 'Laporan Pembukuan')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h4 class="mb-3"><i class="bi bi-graph-up"></i> Laporan Pembukuan</h4>

        {{-- 🔍 Filter Bulan & Tahun --}}
        <form method="GET" class="row g-2 mb-4">
            <div class="col-md-3">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select">
                    @foreach(range(1,12) as $b)
                        <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $b)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select">
                    @foreach(range(date('Y'), date('Y')-5) as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 align-self-end">
                <button class="btn btn-primary w-100"><i class="bi bi-search"></i> Tampilkan</button>
            </div>
        </form>

        {{-- 📊 Statistik Ringkas --}}
        <div class="row text-center mb-4">
            <div class="col-md-4">
                <div class="p-3 bg-success text-white rounded shadow-sm">
                    <h5 class="mb-1">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h5>
                    <small>Total Pendapatan Bulan Ini</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-primary text-white rounded shadow-sm">
                    <h5 class="mb-1">{{ $pelangganBayar }}</h5>
                    <small>Pelanggan Sudah Bayar</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-danger text-white rounded shadow-sm">
                    <h5 class="mb-1">{{ $pelangganBelumBayar }}</h5>
                    <small>Pelanggan Belum Bayar</small>
                </div>
            </div>
        </div>

        {{-- 💰 Daftar Pembayaran Bulan Ini --}}
        <h5 class="mb-2"><i class="bi bi-cash-stack"></i> Transaksi Bulan {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}</h5>
        <div class="table-responsive mb-4">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Kode</th>
                        <th>Periode</th>
                        <th>Tanggal Bayar</th>
                        <th>Metode</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayaran as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->pelanggan->nama_pelanggan }}</td>
                            <td>{{ $p->pelanggan->kode_pelanggan }}</td>
                            <td>{{ $p->periode }}</td>
                            <td>{{ date('d M Y', strtotime($p->tanggal_bayar)) }}</td>
                            <td>{{ ucfirst($p->metode_pembayaran) }}</td>
                            <td>Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada transaksi bulan ini</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- 📈 Grafik Pendapatan Bulanan --}}
        <h5 class="mb-3"><i class="bi bi-bar-chart-line"></i> Grafik Pendapatan per Bulan</h5>
        <canvas id="chartPendapatan" height="100"></canvas>

        {{-- 📆 Rekap Pendapatan per Bulan --}}
        <h5 class="mt-4"><i class="bi bi-calendar3"></i> Rekap Pemasukan Bulanan</h5>
        <div class="table-responsive">
            <table class="table table-sm table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Bulan</th>
                        <th>Tahun</th>
                        <th>Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekapBulanan as $r)
                        <tr>
                            <td>{{ DateTime::createFromFormat('!m', $r->bulan)->format('F') }}</td>
                            <td>{{ $r->tahun }}</td>
                            <td>Rp {{ number_format($r->total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- 🧾 Riwayat Pembayaran per Pelanggan --}}
        <h5 class="mt-4"><i class="bi bi-people"></i> Riwayat Pembayaran Pelanggan</h5>

        {{-- 🔍 Pencarian pelanggan --}}
        <form method="GET" action="{{ route('laporan.index') }}" class="mb-3">
            <input type="hidden" name="bulan" value="{{ $bulan }}">
            <input type="hidden" name="tahun" value="{{ $tahun }}">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau kode pelanggan..."
                       value="{{ request('search') }}">
                <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
            </div>
        </form>

        <div class="accordion" id="riwayatPelanggan">
            @forelse($riwayatPelanggan as $pel)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ $pel->id_pelanggan }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $pel->id_pelanggan }}">
                            {{ $pel->nama_pelanggan }}
                            <small class="ms-2 text-muted">({{ $pel->kode_pelanggan }})</small>
                        </button>
                    </h2>
                    <div id="collapse{{ $pel->id_pelanggan }}" class="accordion-collapse collapse"
                        data-bs-parent="#riwayatPelanggan">
                        <div class="accordion-body">
                            @if($pel->pembayaran->count())
                                <ul class="list-group list-group-flush">
                                    @foreach($pel->pembayaran as $bayar)
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>
                                                📅 <strong>{{ $bayar->periode }}</strong> — dibayar {{ date('d M Y', strtotime($bayar->tanggal_bayar)) }}
                                            </span>
                                            <span>Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted mb-0">Belum ada riwayat pembayaran</p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted">Tidak ada pelanggan ditemukan</p>
            @endforelse
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('chartPendapatan');
    const labels = @json($rekapBulanan->map(fn($r) => DateTime::createFromFormat('!m', $r->bulan)->format('M') . ' ' . $r->tahun));
    const data = @json($rekapBulanan->pluck('total'));

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Pendapatan (Rp)',
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } },
            plugins: { legend: { display: false } }
        }
    });
});
</script>
@endpush

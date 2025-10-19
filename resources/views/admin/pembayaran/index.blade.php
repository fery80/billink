@extends('admin.layouts.app')
@section('title', 'Daftar Pembayaran')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">📄 Daftar Pembayaran</h4>
            <a href="{{ route('pembayaran.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Tambah Pembayaran
            </a>
        </div>

        <form method="GET" action="{{ route('pembayaran.index') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control"
                    placeholder="Cari nama / kode pelanggan..." value="{{ request('search') }}">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Pelanggan</th>
                        <th>Periode</th>
                        <th>Jumlah</th>
                        <th>Metode</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayaran as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $p->pelanggan->nama_pelanggan ?? '-' }}</strong><br>
                                <small>{{ $p->pelanggan->kode_pelanggan ?? '' }}</small>
                            </td>
                            <td>{{ $p->periode }}</td>
                            <td>Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                            <td><span class="badge bg-info">{{ strtoupper($p->metode_pembayaran) }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_bayar)->translatedFormat('d F Y H:i') }}</td>
                            <td>{{ $p->keterangan ?? '-' }}</td>
                            <td>
                                <form action="{{ route('pembayaran.destroy', $p->id_pembayaran) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted">Belum ada pembayaran</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $pembayaran->links() }}</div>
    </div>
</div>
@endsection

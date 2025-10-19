@extends('admin.layouts.app')
@section('title', 'Daftar Pelanggan')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">📋 Daftar Pelanggan</h4>

            {{-- 🔹 Tombol Tambah Pelanggan --}}
            <a href="{{ route('pelanggan.index') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Tambah Pelanggan
            </a>
        </div>

        {{-- 🔍 Form Pencarian --}}
        <form method="GET" action="{{ route('admin.daftar_pelanggan') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control"
                       placeholder="Cari nama atau kode pelanggan..."
                       value="{{ request('search') }}">
                <button class="btn btn-outline-primary" type="submit">
                    <i class="bi bi-search"></i> Cari
                </button>
            </div>
        </form>

        {{-- 📋 Tabel Daftar Pelanggan --}}
        <div class="table-responsive" style="max-height: 400px; overflow-y: auto; border-radius: 8px;">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light sticky-top">
    <tr>
        <th width="5%" class="text-center">No</th>
        <th>Kode</th>
        <th>Nama</th>
        <th>User PPPoE</th> {{-- ✅ baru --}}
        <th>Foto</th>
        <th>Alamat</th>
        <th>No HP</th>
        <th>Paket</th>
        <th>Bulan Pertama</th>
        <th>Tagihan</th>
        <th>Pembayaran</th>
        <th>Status</th>
        <th>Aktivasi</th>
        <th width="10%" class="text-center">Aksi</th>
    </tr>
</thead>

<tbody>
    @forelse($pelanggan as $p)
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td><strong>{{ $p->kode_pelanggan }}</strong></td>
            <td>{{ $p->nama_pelanggan }}</td>
            <td>{{ $p->user_pppoe ?? '-' }}</td> {{-- ✅ tampilkan --}}
            <td>
                @if($p->foto)
                    <img src="{{ asset('storage/' . $p->foto) }}" 
                         style="width:45px; height:45px; border-radius:10px; object-fit:cover;">
                @else
                    <img src="https://via.placeholder.com/45?text=No+Img" 
                         style="width:45px; height:45px; border-radius:10px;">
                @endif
            </td>
            <td>{{ $p->alamat }}</td>
            <td>{{ $p->nomer_hp }}</td>
            <td>{{ $p->paket->nama_paket ?? '-' }}</td>
            <td>{{ $p->tanggal_bulan_pertama }}</td>
            <td>{{ $p->tanggal_tagihan }}</td>
            <td>
                <span class="badge bg-{{ 
                    $p->status_pembayaran == 'lunas' ? 'success' : 
                    ($p->status_pembayaran == 'telat' ? 'warning' : 'secondary')
                }}">
                    {{ ucfirst($p->status_pembayaran) }}
                </span>
            </td>
            <td>
                <span class="badge bg-{{ 
                    $p->status_langganan == 'aktif' ? 'success' : 
                    ($p->status_langganan == 'tunggakan' ? 'warning' : 
                    ($p->status_langganan == 'isolir' ? 'danger' : 'secondary'))
                }}">
                    {{ ucfirst($p->status_langganan) }}
                </span>
            </td>
            <td>{{ $p->tanggal_aktivasi }}</td>
            <td class="text-center">
                <a href="{{ route('pelanggan.edit', $p->id_pelanggan) }}" 
                   class="btn btn-warning btn-sm" title="Edit">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <form action="{{ route('pelanggan.destroy', $p->id_pelanggan) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" title="Hapus">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="14" class="text-center text-muted">Belum ada data pelanggan</td>
        </tr>
    @endforelse
</tbody>

            </table>
        </div>

        <small class="text-muted d-block mt-2">
            💡 Scroll ke bawah untuk melihat lebih banyak pelanggan (maks 5 tampilan per layar)
        </small>
    </div>
</div>

{{-- 🗺️ Map pelanggan --}}
@include('admin.map', ['pelanggan' => $pelanggan])
@endsection

@push('styles')
<style>
    /* Scroll rapi */
    .table-responsive {
        scrollbar-width: thin;
        scrollbar-color: #bbb #f8f9fa;
    }
    .table-responsive::-webkit-scrollbar {
        width: 8px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background-color: #b5b5b5;
        border-radius: 10px;
    }
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background-color: #888;
    }
    .table thead th {
        background-color: #f8f9fa;
        position: sticky;
        top: 0;
        z-index: 10;
    }
</style>
@endpush

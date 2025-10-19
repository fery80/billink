@extends('admin.layouts.app')

@section('title', 'Manajemen Paket Internet')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <h4 class="mb-3">📦 Tambah / Kelola Paket Internet</h4>

        {{-- Pesan sukses --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- FORM TAMBAH / EDIT --}}
        <form action="{{ isset($editPaket) ? route('paket.update', $editPaket->id_paket) : route('paket.store') }}" method="POST" class="mb-4">
            @csrf
            @if(isset($editPaket))
                @method('PUT')
            @endif
            <div class="row">
                <div class="col-md-3 mb-2">
                    <input type="text" name="nama_paket" class="form-control" placeholder="Nama Paket"
                           value="{{ old('nama_paket', $editPaket->nama_paket ?? '') }}" required>
                    @error('nama_paket') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-2 mb-2">
                    <input type="text" name="kecepatan" class="form-control" placeholder="Kecepatan"
                           value="{{ old('kecepatan', $editPaket->kecepatan ?? '') }}" required>
                </div>
                <div class="col-md-2 mb-2">
                    <input type="number" step="0.01" name="harga" class="form-control" placeholder="Harga"
                           value="{{ old('harga', $editPaket->harga ?? '') }}" required>
                </div>
                <div class="col-md-3 mb-2">
                    <input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi (opsional)"
                           value="{{ old('deskripsi', $editPaket->deskripsi ?? '') }}">
                </div>
                <div class="col-md-2 mb-2">
                    <button type="submit" class="btn btn-{{ isset($editPaket) ? 'warning' : 'primary' }} w-100">
                        {{ isset($editPaket) ? 'Update' : 'Tambah' }}
                    </button>
                </div>
            </div>
        </form>

        {{-- FORM PENCARIAN --}}
        <form method="GET" action="{{ route('paket.index') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari nama paket..."
                       value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit">Cari</button>
                <a href="{{ route('paket.index') }}" class="btn btn-outline-danger">Reset</a>
            </div>
        </form>

        {{-- DAFTAR PAKET --}}
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Nama Paket</th>
                    <th>Kecepatan</th>
                    <th>Harga</th>
                    <th>Deskripsi</th>
                    <th style="width:150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($paket as $index => $p)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $p->nama_paket }}</td>
                        <td>{{ $p->kecepatan }}</td>
                        <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                        <td>{{ $p->deskripsi ?? '-' }}</td>
                        <td>
                            <a href="{{ route('paket.edit', $p->id_paket) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('paket.destroy', $p->id_paket) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus paket ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">Belum ada data paket.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

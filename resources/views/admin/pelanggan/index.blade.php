@extends('admin.layouts.app')
@section('title', 'Tambah Pelanggan')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <h4 class="mb-3">👤 Tambah Pelanggan Baru</h4>

        {{-- ✅ Notifikasi error global --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>⚠️ Terjadi kesalahan!</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ✅ Notifikasi sukses --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('pelanggan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">

                {{-- 🆔 Kode Pelanggan --}}
                <div class="col-md-3 mb-2">
                    <input type="text" name="kode_pelanggan"
                           class="form-control @error('kode_pelanggan') is-invalid @enderror"
                           value="{{ old('kode_pelanggan') }}" placeholder="Kode Pelanggan" required>
                    @error('kode_pelanggan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 👤 Nama Pelanggan --}}
                <div class="col-md-3 mb-2">
                    <input type="text" name="nama_pelanggan"
                           class="form-control @error('nama_pelanggan') is-invalid @enderror"
                           value="{{ old('nama_pelanggan') }}" placeholder="Nama Pelanggan" required>
                    @error('nama_pelanggan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 🔐 User PPPoE --}}
                <div class="col-md-3 mb-2">
                    <input type="text" name="user_pppoe"
                           class="form-control @error('user_pppoe') is-invalid @enderror"
                           value="{{ old('user_pppoe') }}" placeholder="User PPPoE (opsional)">
                    @error('user_pppoe')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 📷 Foto --}}
                <div class="col-md-3 mb-2">
                    <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror">
                    @error('foto')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 📍 Alamat --}}
                <div class="col-md-3 mb-2">
                    <input type="text" name="alamat"
                           class="form-control @error('alamat') is-invalid @enderror"
                           value="{{ old('alamat') }}" placeholder="Alamat" required>
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 📞 Nomor HP --}}
                <div class="col-md-3 mb-2">
                    <input type="text" name="nomer_hp"
                           class="form-control @error('nomer_hp') is-invalid @enderror"
                           value="{{ old('nomer_hp') }}" placeholder="Nomor HP" required>
                    @error('nomer_hp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 📦 Pilih Paket --}}
                <div class="col-md-3 mb-2">
                    <select name="id_paket"
                            class="form-select @error('id_paket') is-invalid @enderror" required>
                        <option value="">-- Pilih Paket --</option>
                        @foreach($paket as $p)
                            <option value="{{ $p->id_paket }}" {{ old('id_paket') == $p->id_paket ? 'selected' : '' }}>
                                {{ $p->nama_paket }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_paket')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 📅 Tanggal Mulai --}}
                <div class="col-md-3 mb-2">
                    <input type="date" name="tanggal_bulan_pertama"
                           class="form-control @error('tanggal_bulan_pertama') is-invalid @enderror"
                           value="{{ old('tanggal_bulan_pertama') }}" required>
                    @error('tanggal_bulan_pertama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 📆 Tanggal Tagihan --}}
                <div class="col-md-3 mb-2">
                    <input type="date" name="tanggal_tagihan"
                           class="form-control @error('tanggal_tagihan') is-invalid @enderror"
                           value="{{ old('tanggal_tagihan') }}" required>
                    @error('tanggal_tagihan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 💰 Status Pembayaran --}}
                <div class="col-md-3 mb-2">
                    <select name="status_pembayaran" class="form-select">
                        <option value="belumbayar" {{ old('status_pembayaran') == 'belumbayar' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="lunas" {{ old('status_pembayaran') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="telat" {{ old('status_pembayaran') == 'telat' ? 'selected' : '' }}>Telat</option>
                    </select>
                </div>

                {{-- ⚡ Status Langganan --}}
                <div class="col-md-3 mb-2">
                    <select name="status_langganan" class="form-select">
                        <option value="aktif" {{ old('status_langganan') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status_langganan') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        <option value="tunggakan" {{ old('status_langganan') == 'tunggakan' ? 'selected' : '' }}>Tunggakan</option>
                        <option value="isolir" {{ old('status_langganan') == 'isolir' ? 'selected' : '' }}>Isolir</option>
                    </select>
                </div>

                {{-- 🌍 Hidden koordinat dari map --}}
                <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">

                {{-- 💾 Tombol Simpan --}}
                <div class="col-md-3 mb-2">
                    <button type="submit" class="btn btn-primary w-100">💾 Simpan Pelanggan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- 🌍 Include Map (klik map → koordinat otomatis masuk form) --}}
@include('admin.map')
@endsection

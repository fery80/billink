@extends('admin.layouts.app')

@section('title', 'Edit Data Pelanggan')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <h4 class="mb-3">✏️ Edit Data Pelanggan</h4>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('pelanggan.update', $pelanggan->id_pelanggan) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- 🆔 Kode Pelanggan --}}
                <div class="col-md-3 mb-3">
                    <label>Kode Pelanggan</label>
                    <input type="text" name="kode_pelanggan" class="form-control" 
                        value="{{ $pelanggan->kode_pelanggan }}" required>
                </div>

                {{-- 👤 Nama Pelanggan --}}
                <div class="col-md-3 mb-3">
                    <label>Nama Pelanggan</label>
                    <input type="text" name="nama_pelanggan" class="form-control" 
                        value="{{ $pelanggan->nama_pelanggan }}" required>
                </div>

                {{-- 🔐 User PPPoE --}}
                <div class="col-md-3 mb-3">
                    <label>User PPPoE</label>
                    <input type="text" name="user_pppoe" class="form-control" 
                        value="{{ $pelanggan->user_pppoe }}" placeholder="Masukkan user PPPoE (opsional)">
                </div>

                {{-- 📞 Nomor HP --}}
                <div class="col-md-3 mb-3">
                    <label>Nomor HP</label>
                    <input type="text" name="nomer_hp" class="form-control" 
                        value="{{ $pelanggan->nomer_hp }}" required>
                </div>

                {{-- ✉️ Email --}}
                <div class="col-md-3 mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" 
                        value="{{ $pelanggan->email }}">
                </div>

                {{-- 📦 Paket --}}
                <div class="col-md-3 mb-3">
                    <label>Paket</label>
                    <select name="id_paket" class="form-select" required>
                        @foreach($paket as $p)
                            <option value="{{ $p->id_paket }}" {{ $pelanggan->id_paket == $p->id_paket ? 'selected' : '' }}>
                                {{ $p->nama_paket }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 📍 Alamat --}}
                <div class="col-md-3 mb-3">
                    <label>Alamat</label>
                    <input type="text" name="alamat" class="form-control" 
                        value="{{ $pelanggan->alamat }}" required>
                </div>

                {{-- 💰 Status Pembayaran --}}
                <div class="col-md-3 mb-3">
                    <label>Status Pembayaran</label>
                    <select name="status_pembayaran" class="form-select">
                        @foreach(['lunas','belumbayar','telat'] as $status)
                            <option value="{{ $status }}" {{ $pelanggan->status_pembayaran == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- ⚡ Status Langganan --}}
                <div class="col-md-3 mb-3">
                    <label>Status Langganan</label>
                    <select name="status_langganan" class="form-select">
                        @foreach(['aktif','nonaktif','tunggakan','isolir'] as $s)
                            <option value="{{ $s }}" {{ $pelanggan->status_langganan == $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 🗓️ Tanggal Aktivasi --}}
                <div class="col-md-3 mb-3">
                    <label>Tanggal Aktivasi</label>
                    <input type="datetime-local" name="tanggal_aktivasi" class="form-control"
                        value="{{ date('Y-m-d\TH:i', strtotime($pelanggan->tanggal_aktivasi)) }}" required>
                </div>

                {{-- 📷 Foto Pelanggan --}}
                <div class="col-md-3 mb-3">
                    <label>Foto Pelanggan</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                    @if($pelanggan->foto)
                        <img src="{{ asset('storage/' . $pelanggan->foto) }}" class="mt-2 rounded" width="80">
                    @endif
                </div>

                {{-- Hidden Latitude dan Longitude --}}
                <input type="hidden" id="latitude" name="latitude" value="{{ $pelanggan->latitude }}">
                <input type="hidden" id="longitude" name="longitude" value="{{ $pelanggan->longitude }}">

                {{-- 💾 Tombol --}}
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-success">💾 Update Data</button>
                    <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">⬅️ Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- 🌍 Include Map untuk edit titik --}}
@include('admin.map', ['pelanggan' => [$pelanggan]])
@endsection

@extends('admin.layouts.app')
@section('title', 'Tambah Pelanggan')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <h4 class="mb-3">👤 Tambah Pelanggan Baru</h4>

        <form action="{{ route('pelanggan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-3 mb-2">
                    <input type="text" name="kode_pelanggan" class="form-control" placeholder="Kode Pelanggan" required>
                </div>
                <div class="col-md-3 mb-2">
                    <input type="text" name="nama_pelanggan" class="form-control" placeholder="Nama Pelanggan" required>
                </div>
                <div class="col-md-3 mb-2">
                    <input type="file" name="foto" class="form-control">
                </div>
                <div class="col-md-3 mb-2">
                    <input type="text" name="alamat" class="form-control" placeholder="Alamat" required>
                </div>

                <div class="col-md-3 mb-2">
                    <input type="text" name="nomer_hp" class="form-control" placeholder="Nomor HP" required>
                </div>
                <div class="col-md-3 mb-2">
                    <select name="id_paket" class="form-select" required>
                        <option value="">-- Pilih Paket --</option>
                        @foreach($paket as $p)
                            <option value="{{ $p->id_paket }}">{{ $p->nama_paket }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-2">
                    <input type="date" name="tanggal_bulan_pertama" class="form-control" required>
                </div>
                <div class="col-md-3 mb-2">
                    <input type="date" name="tanggal_tagihan" class="form-control" required>
                </div>

                <div class="col-md-3 mb-2">
                    <select name="status_pembayaran" class="form-select">
                        <option value="belumbayar">Belum Bayar</option>
                        <option value="lunas">Lunas</option>
                        <option value="telat">Telat</option>
                    </select>
                </div>

                <div class="col-md-3 mb-2">
                    <select name="status_langganan" class="form-select">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                        <option value="tunggakan">Tunggakan</option>
                        <option value="isolir">Isolir</option>
                    </select>
                </div>

                {{-- Hidden koordinat --}}
                <input type="hidden" id="latitude" name="latitude">
                <input type="hidden" id="longitude" name="longitude">

                <div class="col-md-3 mb-2">
                    <button type="submit" class="btn btn-primary w-100">Simpan Pelanggan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Include peta --}}
@include('admin.map')
@endsection

@extends('admin.layouts.app')
@section('title', 'Tambah Pembayaran')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <h4 class="mb-3">💳 Tambah Pembayaran Pascabayar</h4>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('pembayaran.store') }}" method="POST">
            @csrf

            <div class="row">
                {{-- 🔍 Cari Pelanggan --}}
                <div class="col-md-6 mb-3">
                    <label>Pilih Pelanggan</label>
                    <input type="text" id="searchInput" class="form-control mb-2" placeholder="Cari nama atau kode pelanggan...">

                    <select name="id_pelanggan" id="id_pelanggan" class="form-select" required size="5" style="height: auto;">
                        @foreach($pelanggan as $p)
                            <option value="{{ $p->id_pelanggan }}" data-harga="{{ $p->paket->harga ?? 0 }}">
                                {{ $p->nama_pelanggan }} ({{ $p->kode_pelanggan }}) - {{ $p->paket->nama_paket ?? 'Tanpa Paket' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 📆 Tanggal Bayar --}}
                <div class="col-md-3 mb-3">
                    <label>Tanggal Bayar</label>
                    <input type="datetime-local" name="tanggal_bayar" id="tanggal_bayar"
                        class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                </div>

                {{-- 📅 Periode Otomatis --}}
                <div class="col-md-3 mb-3">
                    <label>Periode (otomatis bulan berikutnya)</label>
                    <input type="text" id="periode" class="form-control" readonly placeholder="Contoh: Februari 2025">
                </div>

                {{-- 💰 Jumlah Bayar --}}
                <div class="col-md-3 mb-3">
                    <label>Jumlah Bayar</label>
                    <input type="text" id="jumlah_bayar" class="form-control" readonly placeholder="Rp 0">
                </div>

                {{-- 💳 Metode Pembayaran --}}
                <div class="col-md-3 mb-3">
                    <label>Metode Pembayaran</label>
                    <select name="metode_pembayaran" class="form-select" required>
                        <option value="cash">Cash</option>
                        <option value="transfer">Transfer</option>
                        <option value="cod">COD</option>
                    </select>
                </div>

                {{-- 🗒️ Keterangan --}}
                <div class="col-md-12 mb-3">
                    <label>Keterangan (Opsional)</label>
                    <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan..."></textarea>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">💾 Simpan Pembayaran</button>
                    <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary">⬅️ Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // 🔍 Filter pelanggan sesuai teks
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let options = document.getElementById('id_pelanggan').options;

        for (let i = 0; i < options.length; i++) {
            let text = options[i].text.toLowerCase();
            options[i].style.display = text.includes(filter) ? '' : 'none';
        }
    });

    // 💰 Update jumlah bayar saat pilih pelanggan
    document.getElementById('id_pelanggan').addEventListener('change', function() {
        let harga = this.selectedOptions[0].getAttribute('data-harga');
        document.getElementById('jumlah_bayar').value = 'Rp ' + Number(harga).toLocaleString('id-ID');
    });

    // 📅 Update periode otomatis dari tanggal bayar
    document.getElementById('tanggal_bayar').addEventListener('change', function() {
        let tgl = new Date(this.value);
        if (!isNaN(tgl)) {
            let bulan = tgl.getMonth() + 2; // bulan depan
            let tahun = tgl.getFullYear();
            if (bulan > 12) { bulan = 1; tahun += 1; }
            const bulanNama = [
                "Januari","Februari","Maret","April","Mei","Juni",
                "Juli","Agustus","September","Oktober","November","Desember"
            ];
            document.getElementById('periode').value = bulanNama[bulan - 1] + " " + tahun;
        }
    });

    // Inisialisasi awal
    document.getElementById('tanggal_bayar').dispatchEvent(new Event('change'));
</script>
@endsection

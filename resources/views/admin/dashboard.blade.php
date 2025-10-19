<h2>Selamat Datang, Admin!</h2>
<a href="{{ route('logout') }}">Logout</a>
@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('navbar-right')
    <a href="#" class="btn btn-sm btn-outline-primary">Bantuan</a>
@endsection

@section('content')
    <div class="row g-3">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">Total Client</div>
                            <div class="display-6">{{ $totalClient ?? 0 }}</div>
                        </div>
                        <div class="fs-1">👥</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="fw-semibold">Total OD</div>
                    <div class="display-6">{{ $totalOD ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6">
            <div class="card h-100">
                <div class="card-body">
                    <h2 class="h6">Aktivitas Terbaru</h2>
                    <ul class="list-group list-group-flush">
                        @forelse(($logs ?? []) as $log)
                            <li class="list-group-item small">{{ $log }}</li>
                        @empty
                            <li class="list-group-item small text-muted">Belum ada aktivitas.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <div class="text-muted small">© {{ date('Y') }} — My App</div>
@endsection

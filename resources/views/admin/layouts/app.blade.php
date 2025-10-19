<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Mikrotik App')</title>

    {{-- Bootstrap + Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Leaflet (untuk map) --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-measure/3.3.0/leaflet-measure.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-measure/3.3.0/leaflet-measure.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet-ant-path/dist/leaflet-ant-path.css"/>
<script src="https://unpkg.com/leaflet-ant-path/dist/leaflet-ant-path.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- tambahkan plugin antPath -->
<script src="https://unpkg.com/leaflet-ant-path/dist/leaflet-ant-path.min.js"></script>

    <style>
        body {
            min-height: 100vh;
            display: flex;
            font-family: 'Inter', Arial, sans-serif;
            background: #f4f6f8;
        }
#main-content {
    margin-left: 160px;          /* sama dengan lebar sidebar */
    width: calc(100% - 160px);   /* sisa lebar viewport */
    padding: 60px;               /* bisa disesuaikan */
    background: #f4f6f8;
    min-height: 100vh;           /* agar memenuhi tinggi layar */
}

        @media (max-width: 768px) {
            #main-content {
                margin-left: 100px;
                padding: 20px 8px;
            }
        }
    </style>
</head>
<body>
    {{-- Navbar --}}
    <nav class="navbar navbar-light bg-white shadow-sm fixed-top" style="z-index:1040; height:56px;">
        <div class="container-fluid px-4">
            <a href="/" class="navbar-brand mb-0 p-0">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" height="36" style="object-fit:contain;" />
            </a>
            <div class="d-flex align-items-center gap-3">
                <span class="d-none d-md-inline text-muted small">{{ Auth::user()->name ?? 'User Name' }}</span>
                <img src="https://github.com/mdo.png" alt="User" width="32" height="32" class="rounded-circle" />
            </div>
        </div>
    </nav>

    {{-- Sidebar --}}
    @include('admin.layouts.sidebar')

    {{-- Konten --}}
    <div id="main-content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

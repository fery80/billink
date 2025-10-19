<nav id="sidebar" class="d-flex flex-column px-2 fixed-top"
     style="top:56px; left:0; height:calc(100vh - 56px); width:180px; min-width:150px;
            background:#fff; border-right:1px solid #e5e7eb; font-size:0.93rem;">

    <ul class="nav nav-pills flex-column mb-auto">

       

        {{-- 📦 Paket --}}
        <li>
            <a href="{{ route('paket.index') }}"
               class="nav-link {{ request()->routeIs('paket.*') ? 'active' : 'text-dark' }}"
               style="font-size:0.95rem; padding:6px 8px;">
                <i class="bi bi-box-seam me-2"></i> Paket
            </a>
        </li>

        {{-- 👥 Pelanggan --}}
        <li>
            <a class="nav-link d-flex justify-content-between align-items-center
                      {{ request()->routeIs('pelanggan.*') || request()->routeIs('admin.daftar_pelanggan') ? '' : 'collapsed' }}"
               data-bs-toggle="collapse" href="#pelangganMenu"
               aria-expanded="{{ request()->routeIs('pelanggan.*') || request()->routeIs('admin.daftar_pelanggan') ? 'true' : 'false' }}"
               aria-controls="pelangganMenu"
               style="font-size:0.95rem; padding:6px 8px;">
                <span><i class="bi bi-people me-2"></i> Pelanggan</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse ps-3 {{ request()->routeIs('pelanggan.*') || request()->routeIs('admin.daftar_pelanggan') ? 'show' : '' }}" id="pelangganMenu">
                <ul class="nav flex-column">
                    <li>
                        <a href="{{ route('pelanggan.index') }}" class="nav-link {{ request()->routeIs('pelanggan.index') ? 'active' : 'text-dark' }}"
                           style="font-size:0.8rem; padding:5px 8px;">
                            <i class="bi bi-person-lines-fill me-2"></i> Tambah Pelanggan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.daftar_pelanggan') }}" class="nav-link {{ request()->routeIs('admin.daftar_pelanggan') ? 'active' : 'text-dark' }}"
                           style="font-size:0.8rem; padding:5px 8px;">
                            <i class="bi bi-list-ul me-2"></i> Daftar Pelanggan
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- 💳 Pembayaran --}}
        <li>
            <a class="nav-link d-flex justify-content-between align-items-center
                      {{ request()->routeIs('pembayaran.*') ? '' : 'collapsed' }}"
               data-bs-toggle="collapse" href="#pembayaranMenu"
               aria-expanded="{{ request()->routeIs('pembayaran.*') ? 'true' : 'false' }}"
               aria-controls="pembayaranMenu"
               style="font-size:0.95rem; padding:6px 8px;">
                <span><i class="bi bi-wallet2 me-2"></i> Pembayaran</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse ps-3 {{ request()->routeIs('pembayaran.*') ? 'show' : '' }}" id="pembayaranMenu">
                <ul class="nav flex-column">
                    <li>
                        <a href="{{ route('pembayaran.index') }}" class="nav-link {{ request()->routeIs('pembayaran.index') ? 'active' : 'text-dark' }}"
                           style="font-size:0.8rem; padding:5px 8px;">
                            <i class="bi bi-cash-coin me-2"></i> Data Pembayaran
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pembayaran.create') }}" class="nav-link {{ request()->routeIs('pembayaran.create') ? 'active' : 'text-dark' }}"
                           style="font-size:0.8rem; padding:5px 8px;">
                            <i class="bi bi-plus-square me-2"></i> Tambah Pembayaran
                        </a>
                    </li>
                </ul>
            </div>
        </li>
<li>
    <a href="{{ route('laporan.index') }}" class="nav-link" style="font-size:0.95rem; padding:6px 8px;">
        <i class="bi bi-graph-up me-2"></i> Laporan
    </a>
</li>

        {{-- ⚙️ Pengaturan --}}
        <li>
            <a href="#" class="nav-link text-dark" style="font-size:0.95rem; padding:6px 8px;">
                <i class="bi bi-gear me-2"></i> Pengaturan
            </a>
        </li>
    </ul>

    {{-- 👤 User Info --}}
    <div class="mt-auto pt-4 border-top">
        <a href="#" class="d-flex align-items-center text-dark text-decoration-none px-2 py-2">
            <img src="https://github.com/mdo.png" alt="User" width="28" height="28" class="rounded-circle me-2">
            <strong>{{ Auth::user()->name ?? 'Admin' }}</strong>
        </a>
    </div>
</nav>

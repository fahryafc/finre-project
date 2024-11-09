<div class="app-menu">

    <!-- Sidenav Brand Logo -->
    <a href="javascript:void(0)" class="logo-box">
        <!-- Light Brand Logo -->
        <div class="logo-light">
            <img src="/images/finre.png" class="logo-lg h-6" alt="Light logo">
            <img src="/images/finre-mini.png" class="logo-sm" alt="Small logo">
        </div>

        <!-- Dark Brand Logo -->
        <div class="logo-dark">
            <img src="/images/finre.png" class="logo-lg h-6" alt="Dark logo">
            <img src="/images/finre-mini.png" class="logo-sm" alt="Small logo">
        </div>
    </a>

    <!-- Sidenav Menu Toggle Button -->
    <button id="button-hover-toggle" class="absolute top-5 end-2 rounded-full p-1.5">
        <span class="sr-only">Menu Toggle Button</span>
        <i class="mgc_round_line text-xl"></i>
    </button>

    <!--- Menu -->
    <div class="srcollbar" data-simplebar>
        <ul class="menu" data-fc-type="accordion">
            <!-- <li class="menu-title">Menu</li> -->

            <li class="menu-item">
                <a href="{{ route('dashboard.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="ti ti-chart-pie mr-2"></i></span>
                    <span class="menu-text"> Dashboard </span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('penjualan.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="ti ti-report-money"></i></span>
                    <span class="menu-text"> Penjualan </span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('pengeluaran.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="ti ti-shopping-cart"></i></span>
                    <span class="menu-text"> Pengeluaran </span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('hutangpiutang.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="ti ti-chart-histogram"></i></span>
                    <span class="menu-text"> Hutang & Piutang </span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('kasdanbank.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="ti ti-wallet"></i></span>
                    <span class="menu-text"> Kas & Bank </span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('pajak.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="ti ti-receipt-tax"></i></span>
                    <span class="menu-text"> Pajak </span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('produkdaninventori.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="ti ti-building-store"></i></span>
                    <span class="menu-text"> Produk & Inventori </span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('asset.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="ti ti-database-cog"></i></span>
                    <span class="menu-text"> Aset </span>
                </a>
            </li>
            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link">
                    <span class="menu-icon"><i class="ti ti-clipboard-text"></i></span>
                    <span class="menu-text"> Laporan </span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('kontak.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="ti ti-address-book"></i></span>
                    <span class="menu-text"> Kontak </span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('akun.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="ti ti-chart-infographic"></i></span>
                    <span class="menu-text"> Akun </span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('modal.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="ti ti-coins"></i></span>
                    <span class="menu-text"> Modal </span>
                </a>
            </li>
            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link">
                    <span class="menu-icon"><i class="ti ti-settings"></i></span>
                    <span class="menu-text"> Pengaturan </span>
                </a>
            </li>

        </ul>
    </div>
</div>
<!-- Sidenav Menu End  -->
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

            @if (auth()->user()->hasRole('inviter') || auth()->user()->can('dashboard') && \App\Models\Invites::where('email', auth()->user()->email)->where('status', 'accepted')->exists())
                <li class="menu-item">
                    <a href="/dashboard" class="menu-link">
                        <span class="menu-icon"><i class="mgc_home_3_line"></i></span>
                        <span class="menu-text"> Dashboard </span>
                    </a>
                </li>
                @endif

            @if (auth()->user()->hasRole('owner'))
                <li class="menu-item">
                    <a href="/dashboard-owner" class="menu-link">
                        <span class="menu-icon"><i class="mgc_home_3_line"></i></span>
                        <span class="menu-text"> Dashboard </span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="/dashboard-owner/pendapatan" class="menu-link">
                        <span class="menu-icon"><i class="mgc_home_3_line"></i></span>
                        <span class="menu-text"> Pendapatan </span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="/dashboard-owner/user-list" class="menu-link">
                        <span class="menu-icon"><i class="mgc_home_3_line"></i></span>
                        <span class="menu-text"> Daftar Pengguna </span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasRole('inviter') || auth()->user()->can('penjualan') && \App\Models\Invites::where('email', auth()->user()->email)->where('status', 'accepted')->exists())
                <li class="menu-item">
                    <a href="/penjualan" class="menu-link">
                        <span class="menu-icon"><i class="mgc_home_3_line"></i></span>
                        <span class="menu-text"> Penjualan </span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasRole('inviter') || auth()->user()->can('pengeluaran') && \App\Models\Invites::where('email', auth()->user()->email)->where('status', 'accepted')->exists())
                <li class="menu-item">
                    <a href="/pengeluaran" class="menu-link">
                        <span class="menu-icon"><i class="mgc_home_3_line"></i></span>
                        <span class="menu-text"> Pengeluaran </span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasRole('inviter') || auth()->user()->can('hutang-piutang') && \App\Models\Invites::where('email', auth()->user()->email)->where('status', 'accepted')->exists())
                <li class="menu-item">
                    <a href="/hutang-piutang" class="menu-link">
                        <span class="menu-icon"><i class="mgc_home_3_line"></i></span>
                        <span class="menu-text"> Hutang & Piutang </span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasRole('inviter') || auth()->user()->can('kas-bank') && \App\Models\Invites::where('email', auth()->user()->email)->where('status', 'accepted')->exists())
                <li class="menu-item">
                    <a href="/kas-bank" class="menu-link">
                        <span class="menu-icon"><i class="mgc_home_3_line"></i></span>
                        <span class="menu-text"> Kas & Bank </span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasRole('inviter') || auth()->user()->can('pajak') && \App\Models\Invites::where('email', auth()->user()->email)->where('status', 'accepted')->exists())
            <li class="menu-item">
                <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link fc-collapse">
                    <span class="menu-icon"><i class="ti ti-receipt-tax"></i></span>
                    <span class="menu-text"> Pajak </span>
                    <span class="menu-arrow"></span>
                </a>

                <ul class="sub-menu hidden" style="">
                    <li class="menu-item">
                        <a href="{{ route('pajak.ppn') }}" class="menu-link">
                            <span class="menu-text">PPN</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('pajak.pph') }}" class="menu-link">
                            <span class="menu-text">PPH</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('pajak.ppnbm') }}" class="menu-link">
                            <span class="menu-text">PPnBM</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            @if (auth()->user()->hasRole('inviter') || auth()->user()->can('produk-inventori') && \App\Models\Invites::where('email', auth()->user()->email)->where('status', 'accepted')->exists())
            <li class="menu-item">
                <a href="{{ route('produkdaninventori.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="ti ti-building-store"></i></span>
                    <span class="menu-text"> Produk & Inventori </span>
                </a>
            </li>
            @endif

            @if (auth()->user()->hasRole('inviter') || auth()->user()->can('aset') && \App\Models\Invites::where('email', auth()->user()->email)->where('status', 'accepted')->exists())
            <li class="menu-item">
                <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link fc-collapse">
                    <span class="menu-icon"><i class="ti ti-database-cog"></i></span>
                    <span class="menu-text"> Asset </span>
                    <span class="menu-arrow"></span>
                </a>

                <ul class="sub-menu hidden" style="">
                    <li class="menu-item">
                        <a href="{{ route('asset.asset_tersedia') }}" class="menu-link">
                            <span class="menu-text">Asset Tersedia</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('asset.asset_terjual') }}" class="menu-link">
                            <span class="menu-text">Asset Terjual</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            @if (auth()->user()->hasRole('inviter') || auth()->user()->can('laporan') && \App\Models\Invites::where('email', auth()->user()->email)->where('status', 'accepted')->exists())
            <li class="menu-item">
                <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link fc-collapse">
                    <span class="menu-icon"><i class="ti ti-database-cog"></i></span>
                    <span class="menu-text"> Laporan </span>
                    <span class="menu-arrow"></span>
                </a>

                <ul class="sub-menu hidden" style="">
                    <li class="menu-item">
                        <a href="{{ route('jurnal.index') }}" class="menu-link">
                            <span class="menu-text">Jurnal</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('aruskas.index') }}" class="menu-link">
                            <span class="menu-text">Arus Kas</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('neraca.index') }}" class="menu-link">
                            <span class="menu-text">Neraca</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('labarugi.index') }}" class="menu-link">
                            <span class="menu-text">Laba Rugi</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            @if (auth()->user()->hasRole('inviter') || auth()->user()->can('kontak') && \App\Models\Invites::where('email', auth()->user()->email)->where('status', 'accepted')->exists())
            <li class="menu-item">
                <a href="{{ route('kontak.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="ti ti-address-book"></i></span>
                    <span class="menu-text"> Kontak </span>
                </a>
            </li>
            @endif

            @if (auth()->user()->hasRole('inviter') || auth()->user()->can('akun') && \App\Models\Invites::where('email', auth()->user()->email)->where('status', 'accepted')->exists())
            <li class="menu-item">
                <a href="{{ route('akun.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="ti ti-chart-infographic"></i></span>
                    <span class="menu-text"> Akun </span>
                </a>
            </li>
            @endif

            @if (auth()->user()->hasRole('inviter') || auth()->user()->can('modal') && \App\Models\Invites::where('email', auth()->user()->email)->where('status', 'accepted')->exists())
            <li class="menu-item">
                <a href="{{ route('modal.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="ti ti-coins"></i></span>
                    <span class="menu-text"> Modal </span>
                </a>
            </li>
            @endif

            @if (auth()->user()->hasRole('inviter'))
                <li class="menu-item">
                    <a href="/paket-setting" class="menu-link">
                        <span class="menu-icon"><i class="mgc_box_3_line"></i></span>
                        <span class="menu-text"> Paket </span>
                    </a>
                </li>
            @elseif(\App\Models\Invites::where('email', auth()->user()->email)->where('status', '!=', 'accepted')->exists() || !\App\Models\Invites::where('email', auth()->user()->email)->exists() && !auth()->user()->hasRole('owner'))
                <li class="menu-item">
                    <a href="/daftar-paket" class="menu-link">
                        <span class="menu-icon"><i class="mgc_box_3_line"></i></span>
                        <span class="menu-text"> Daftar Paket </span>
                    </a>
                </li>
            @endif
            
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
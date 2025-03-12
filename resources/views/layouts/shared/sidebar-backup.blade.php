<div class="app-menu">

    <!-- Sidenav Brand Logo -->
    <a href="any', 'index') }}" class="logo-box">
        <div>
            <img src="{{ asset('images/brands/logo.png') }}" class="logo-lg h-10" alt="Small logo">
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
            <li class="menu-title">Menu</li>

            @if (auth()->user()->hasRole('inviter') || \App\Models\Subscription::where('user_id', auth()->user()->id)->where('nama_paket', 'Free Trial')->where('status', 'active')->exists() || auth()->user()->can('dashboard') && \App\Models\Invites::where('email', auth()->user()->email)->where('status', 'accepted')->exists())
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

            @if (auth()->user()->hasRole('inviter') || \App\Models\Subscription::where('user_id', auth()->user()->id)->where('nama_paket', 'Free Trial')->where('status', 'active')->exists() || auth()->user()->can('penjualan') && \App\Models\Invites::where('email', auth()->user()->email)->where('status', 'accepted')->exists())
                <li class="menu-item">
                    <a href="/penjualan" class="menu-link">
                        <span class="menu-icon"><i class="mgc_home_3_line"></i></span>
                        <span class="menu-text"> Penjualan </span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasRole('inviter') || \App\Models\Subscription::where('user_id', auth()->user()->id)->where('nama_paket', 'Free Trial')->where('status', 'active')->exists() || auth()->user()->can('pengeluaran') && \App\Models\Invites::where('email', auth()->user()->email)->where('status', 'accepted')->exists())
                <li class="menu-item">
                    <a href="/pengeluaran" class="menu-link">
                        <span class="menu-icon"><i class="mgc_home_3_line"></i></span>
                        <span class="menu-text"> Pengeluaran </span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasRole('inviter') || \App\Models\Subscription::where('user_id', auth()->user()->id)->where('nama_paket', 'Free Trial')->where('status', 'active')->exists() || auth()->user()->can('hutang-piutang') && \App\Models\Invites::where('email', auth()->user()->email)->where('status', 'accepted')->exists())
                <li class="menu-item">
                    <a href="/hutang-piutang" class="menu-link">
                        <span class="menu-icon"><i class="mgc_home_3_line"></i></span>
                        <span class="menu-text"> Hutang & Piutang </span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasRole('inviter') || \App\Models\Subscription::where('user_id', auth()->user()->id)->where('nama_paket', 'Free Trial')->where('status', 'active')->exists() || auth()->user()->can('kas-bank') && \App\Models\Invites::where('email', auth()->user()->email)->where('status', 'accepted')->exists())
                <li class="menu-item">
                    <a href="/kas-bank" class="menu-link">
                        <span class="menu-icon"><i class="mgc_home_3_line"></i></span>
                        <span class="menu-text"> Kas & Bank </span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasRole('inviter') || \App\Models\Subscription::where('user_id', auth()->user()->id)->where('nama_paket', 'Free Trial')->where('status', 'active')->exists() || auth()->user()->can('pajak') && \App\Models\Invites::where('email', auth()->user()->email)->where('status', 'accepted')->exists())
                <li class="menu-item">
                    <a href="/pajak" class="menu-link">
                        <span class="menu-icon"><i class="mgc_home_3_line"></i></span>
                        <span class="menu-text"> Pajak </span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasRole('inviter') || \App\Models\Subscription::where('user_id', auth()->user()->id)->where('nama_paket', 'Free Trial')->where('status', 'active')->exists() || auth()->user()->can('produk-inventori') && \App\Models\Invites::where('email', auth()->user()->email)->where('status', 'accepted')->exists())
                <li class="menu-item">
                    <a href="/produk-inventori" class="menu-link">
                        <span class="menu-icon"><i class="mgc_home_3_line"></i></span>
                        <span class="menu-text"> Produk & Inventori </span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasRole('inviter') || \App\Models\Subscription::where('user_id', auth()->user()->id)->where('nama_paket', 'Free Trial')->where('status', 'active')->exists() || auth()->user()->can('aset') && \App\Models\Invites::where('email', auth()->user()->email)->where('status', 'accepted')->exists())
                <li class="menu-item">
                    <a href="/aset" class="menu-link">
                        <span class="menu-icon"><i class="mgc_home_3_line"></i></span>
                        <span class="menu-text"> Aset </span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasRole('inviter') || \App\Models\Subscription::where('user_id', auth()->user()->id)->where('nama_paket', 'Free Trial')->where('status', 'active')->exists() || auth()->user()->can('laporan') && \App\Models\Invites::where('email', auth()->user()->email)->where('status', 'accepted')->exists())
                <li class="menu-item">
                    <a href="/laporan" class="menu-link">
                        <span class="menu-icon"><i class="mgc_home_3_line"></i></span>
                        <span class="menu-text"> Laporan </span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasRole('inviter') || \App\Models\Subscription::where('user_id', auth()->user()->id)->where('nama_paket', 'Free Trial')->where('status', 'active')->exists() || auth()->user()->can('kontak') && \App\Models\Invites::where('email', auth()->user()->email)->where('status', 'accepted')->exists())
                <li class="menu-item">
                    <a href="/kontak" class="menu-link">
                        <span class="menu-icon"><i class="mgc_home_3_line"></i></span>
                        <span class="menu-text"> Kontak </span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasRole('inviter') || \App\Models\Subscription::where('user_id', auth()->user()->id)->where('nama_paket', 'Free Trial')->where('status', 'active')->exists() || auth()->user()->can('akun') && \App\Models\Invites::where('email', auth()->user()->email)->where('status', 'accepted')->exists())
                <li class="menu-item">
                    <a href="/akun" class="menu-link">
                        <span class="menu-icon"><i class="mgc_home_3_line"></i></span>
                        <span class="menu-text"> Akun </span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasRole('inviter') || \App\Models\Subscription::where('user_id', auth()->user()->id)->where('nama_paket', 'Free Trial')->where('status', 'active')->exists() || auth()->user()->can('modal') && \App\Models\Invites::where('email', auth()->user()->email)->where('status', 'accepted')->exists())
                <li class="menu-item">
                    <a href="/modal" class="menu-link">
                        <span class="menu-icon"><i class="mgc_home_3_line"></i></span>
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
                <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                    <span class="menu-icon"><i class="mgc_building_2_line"></i></span>
                    <span class="menu-text"> Project </span>
                    <span class="menu-arrow"></span>
                </a>

                <ul class="sub-menu hidden">
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <span class="menu-text">List</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <span class="menu-text">Detail</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <span class="menu-text">Create</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- Sidenav Menu End  -->
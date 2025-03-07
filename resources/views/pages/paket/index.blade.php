@extends('layouts.default')

@section('content')
<script type="text/javascript"
src="https://app.sandbox.midtrans.com/snap/snap.js"
data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <div id="snap-container"></div>
    <div class="container mx-auto">
        <!-- Tab Navigation -->
        <div class="flex justify-center space-x-4 mb-10">
            <button
                id="btn-bulanan"
                class="tab-btn px-6 py-2 bg-teal-600 text-white dark:bg-teal-500 rounded-lg"
                onclick="showPaket('bulanan', this)"
            >
                Bulanan
            </button>
            <button
                id="btn-6-bulanan"
                class="tab-btn px-6 py-2 bg-gray-200 text-gray-700 rounded-lg dark:bg-gray-800 dark:text-gray-300"
                onclick="showPaket('6-bulanan', this)"
            >
                6 Bulanan
            </button>
            <button
                id="btn-tahunan"
                class="tab-btn px-6 py-2 bg-gray-200 text-gray-700 rounded-lg dark:bg-gray-800 dark:text-gray-300"
                onclick="showPaket('tahunan', this)"
            >
                Tahunan
            </button>
        </div>

        <!-- Pricing Cards -->
        <div id="bulanan" class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 text-center">
                <h3 class="text-2xl font-semibold text-teal-600 dark:text-teal-400">Personal</h3>
                <p class="text-4xl font-bold text-gray-800 dark:text-gray-200 my-4">
                    Rp <span>199</span> K<span class="text-lg text-gray-500 dark:text-gray-400">/Bulan</span>
                </p>
                <ul class="text-left space-y-2 text-gray-600 dark:text-gray-300">
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">1 Data Usaha</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">3 User</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Produk & Inventori</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Penghitungan Pajak</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Manajemen Stok</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Pencatatan Hutang & Piutang</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Laporan Keuangan</span>
                    </li>
                </ul>
                @if (Auth::user()->email_verified_at)
                    <button data-name="personal" data-price="199" data-jml-user="3" data-periode="bulanan" class="buy mt-6 px-4 py-2 bg-teal-600 text-white dark:bg-teal-500 rounded-lg w-32">Beli Sekarang</button>
                @else
                    <a href="/pengaturan" class="mt-6 px-4 py-2 text-white bg-yellow-500 rounded-lg w-full block">Verify your email for purchase</a>
                @endif
            </div>
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 text-center">
                <h3 class="text-2xl font-semibold text-teal-600 dark:text-teal-400">Business</h3>
                <p class="text-4xl font-bold text-gray-800 dark:text-gray-200 my-4">
                    Rp <span>299</span> K<span class="text-lg text-gray-500 dark:text-gray-400">/Bulan</span>
                </p>
                <ul class="text-left space-y-2 text-gray-600 dark:text-gray-300">
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">1 Data Usaha</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">5 User</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Produk & Inventori</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Penghitungan Pajak</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Manajemen Stok</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Pencatatan Hutang & Piutang</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Laporan Keuangan</span>
                    </li>
                <!-- Additional features... -->
                </ul>
                @if (Auth::user()->email_verified_at)
                    <button data-name="business" data-price="299" data-jml-user="5" data-periode="bulanan" class="buy mt-6 px-4 py-2 bg-teal-600 text-white dark:bg-teal-500 rounded-lg w-32">Beli Sekarang</button>
                @else
                    <a href="/pengaturan" class="mt-6 px-4 py-2 text-white bg-yellow-500 rounded-lg w-full block">Verify your email for purchase</a>
                @endif
            </div>
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 text-center">
                <h3 class="text-2xl font-semibold text-teal-600 dark:text-teal-400">Enterprise</h3>
                <p class="text-4xl font-bold text-gray-800 dark:text-gray-200 my-4">
                    Rp <span>399</span> K<span class="text-lg text-gray-500 dark:text-gray-400">/Bulan</span>
                </p>
                <ul class="text-left space-y-2 text-gray-600 dark:text-gray-300">
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">1 Data Usaha</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">7 User</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Produk & Inventori</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Penghitungan Pajak</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Manajemen Stok</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Pencatatan Hutang & Piutang</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Laporan Keuangan</span>
                    </li>
                </ul>
                @if (Auth::user()->email_verified_at)
                    <button data-name="enterprise" data-price="399" data-jml-user="7" data-periode="bulanan" class="buy mt-6 px-4 py-2 bg-teal-600 text-white dark:bg-teal-500 rounded-lg w-32">Beli Sekarang</button>
                @else
                    <a href="/pengaturan" class="mt-6 px-4 py-2 text-white bg-yellow-500 rounded-lg w-full block">Verify your email for purchase</a>
                @endif
            </div>
        </div>

        <div id="6-bulanan" class="grid grid-cols-1 sm:grid-cols-3 gap-6 hidden">
            <!-- Card for 6 Bulanan -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 text-center">
                <h3 class="text-2xl font-semibold text-teal-600 dark:text-teal-400">Personal</h3>
                <p class="font-bold text-gray-800 dark:text-gray-200 my-4">
                    175K/bulan atau hemat 144 K
                </p>
                <p class="text-4xl font-bold text-gray-800 dark:text-gray-200 my-4">
                    Rp <span>1.050</span> K<span class="text-lg text-gray-500 dark:text-gray-400">/6 Bulan</span>
                </p>
                <ul class="text-left space-y-2 text-gray-600 dark:text-gray-300">
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">1 Data Usaha</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">3 User</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Produk & Inventori</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Penghitungan Pajak</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Manajemen Stok</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Pencatatan Hutang & Piutang</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Laporan Keuangan</span>
                    </li>
                </ul>
                @if (Auth::user()->email_verified_at)
                    <button data-name="personal" data-price="1.050" data-jml-user="3" data-periode="6 bulan" class="buy mt-6 px-4 py-2 bg-teal-600 text-white dark:bg-teal-500 rounded-lg w-32">Beli Sekarang</button>
                @else
                    <a href="/pengaturan" class="mt-6 px-4 py-2 text-white bg-yellow-500 rounded-lg w-full block">Verify your email for purchase</a>
                @endif
            </div>
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 text-center">
                <h3 class="text-2xl font-semibold text-teal-600 dark:text-teal-400">Business</h3>
                <p class="font-bold text-gray-800 dark:text-gray-200 my-4">
                    275/bulan atau hemat 144 K
                </p>
                <p class="text-4xl font-bold text-gray-800 dark:text-gray-200 my-4">
                    Rp <span>1.650</span> K<span class="text-lg text-gray-500 dark:text-gray-400">/6 Bulan</span>
                </p>
                <ul class="text-left space-y-2 text-gray-600 dark:text-gray-300">
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">1 Data Usaha</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">5 User</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Produk & Inventori</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Penghitungan Pajak</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Manajemen Stok</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Pencatatan Hutang & Piutang</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Laporan Keuangan</span>
                    </li>
                </ul>
                @if (Auth::user()->email_verified_at)
                    <button data-name="business" data-price="1.650" data-jml-user="5" data-periode="6 bulan" class="buy mt-6 px-4 py-2 bg-teal-600 text-white dark:bg-teal-500 rounded-lg w-32">Beli Sekarang</button>
                @else
                    <a href="/pengaturan" class="mt-6 px-4 py-2 text-white bg-yellow-500 rounded-lg w-full block">Verify your email for purchase</a>
                @endif
            </div>
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 text-center">
                <h3 class="text-2xl font-semibold text-teal-600 dark:text-teal-400">Enterprise</h3>
                <p class="font-bold text-gray-800 dark:text-gray-200 my-4">
                    375K/bulan atau hemat 144 K
                </p>
                <p class="text-4xl font-bold text-gray-800 dark:text-gray-200 my-4">
                    Rp <span>2.250</span> K<span class="text-lg text-gray-500 dark:text-gray-400">/6 Bulan</span>
                </p>
                <ul class="text-left space-y-2 text-gray-600 dark:text-gray-300">
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">1 Data Usaha</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">7 User</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Produk & Inventori</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Penghitungan Pajak</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Manajemen Stok</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Pencatatan Hutang & Piutang</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Laporan Keuangan</span>
                    </li>
                </ul>
                @if (Auth::user()->email_verified_at)
                    <button data-name="enterprise" data-price="2.250" data-jml-user="7" data-periode="6 bulan" class="buy mt-6 px-4 py-2 bg-teal-600 text-white dark:bg-teal-500 rounded-lg w-32">Beli Sekarang</button>
                @else
                    <a href="/pengaturan" class="mt-6 px-4 py-2 text-white bg-yellow-500 rounded-lg w-full block">Verify your email for purchase</a>
                @endif
            </div>
        </div>

        <div id="tahunan" class="grid grid-cols-1 sm:grid-cols-3 gap-6 hidden">
            <!-- Card for Tahunan -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 text-center">
                <h3 class="text-2xl font-semibold text-teal-600 dark:text-teal-400">Personal</h3>
                <p class="font-bold text-gray-800 dark:text-gray-200 my-4">
                    149K/bulan atau hemat 600 K
                </p>
                <p class="text-4xl font-bold text-gray-800 dark:text-gray-200 my-4">
                    Rp <span>1.788</span> K<span class="text-lg text-gray-500 dark:text-gray-400">/Tahun</span>
                </p>
                <ul class="text-left space-y-2 text-gray-600 dark:text-gray-300">
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">1 Data Usaha</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">3 User</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Produk & Inventori</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Penghitungan Pajak</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Manajemen Stok</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Pencatatan Hutang & Piutang</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Laporan Keuangan</span>
                    </li>
                </ul>
                @if (Auth::user()->email_verified_at)
                    <button data-name="personal" data-price="1.788" data-jml-user="3" data-periode="tahunan" class="buy mt-6 px-4 py-2 bg-teal-600 text-white dark:bg-teal-500 rounded-lg w-32">Beli Sekarang</button>
                @else
                    <a href="/pengaturan" class="mt-6 px-4 py-2 text-white bg-yellow-500 rounded-lg w-full block">Verify your email for purchase</a>
                @endif
            </div>
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 text-center">
                <h3 class="text-2xl font-semibold text-teal-600 dark:text-teal-400">Business</h3>
                <p class="font-bold text-gray-800 dark:text-gray-200 my-4">
                    249K/bulan atau hemat 600 K
                </p>
                <p class="text-4xl font-bold text-gray-800 dark:text-gray-200 my-4">
                    Rp <span>2.988</span> K<span class="text-lg text-gray-500 dark:text-gray-400">/Tahun</span>
                </p>
                <ul class="text-left space-y-2 text-gray-600 dark:text-gray-300">
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">1 Data Usaha</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">5 User</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Produk & Inventori</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Penghitungan Pajak</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Manajemen Stok</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Pencatatan Hutang & Piutang</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Laporan Keuangan</span>
                    </li>
                </ul>
                @if (Auth::user()->email_verified_at)
                    <button data-name="business" data-price="2.988" data-jml-user="5" data-periode="tahunan" class="buy mt-6 px-4 py-2 bg-teal-600 text-white dark:bg-teal-500 rounded-lg w-32">Beli Sekarang</button>
                @else
                    <a href="/pengaturan" class="mt-6 px-4 py-2 text-white bg-yellow-500 rounded-lg w-full block">Verify your email for purchase</a>
                @endif
            </div>
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 text-center">
                <h3 class="text-2xl font-semibold text-teal-600 dark:text-teal-400">Enterprise</h3>
                <p class="font-bold text-gray-800 dark:text-gray-200 my-4">
                    349K/bulan atau hemat 600 K
                </p>
                <p class="text-4xl font-bold text-gray-800 dark:text-gray-200 my-4">
                    Rp <span>4.188</span> K<span class="text-lg text-gray-500 dark:text-gray-400">/Tahun</span>
                </p>
                <ul class="text-left space-y-2 text-gray-600 dark:text-gray-300">
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">1 Data Usaha</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">7 User</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Produk & Inventori</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Penghitungan Pajak</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Manajemen Stok</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Pencatatan Hutang & Piutang</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                        <span class="ml-2">Laporan Keuangan</span>
                    </li>
                </ul>
                @if (Auth::user()->email_verified_at)
                    <button data-name="enterprise" data-price="4.188" data-jml-user="7" data-periode="tahunan" class="buy mt-6 px-4 py-2 bg-teal-600 text-white dark:bg-teal-500 rounded-lg w-32">Beli Sekarang</button>
                @else
                    <a href="/pengaturan" class="mt-6 px-4 py-2 text-white bg-yellow-500 rounded-lg w-full block">Verify your email for purchase</a>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Show the selected pricing package
            function showPaket(category, btn) {
                // Hide all packages
                $('#bulanan').addClass('hidden');
                $('#6-bulanan').addClass('hidden');
                $('#tahunan').addClass('hidden');

                // Show the selected package
                $(`#${category}`).removeClass('hidden');

                // Update active button style
                const buttons = document.querySelectorAll('.tab-btn');
                buttons.forEach((button) => {
                    button.classList.remove(
                    'bg-teal-600',
                    'text-white',
                    'dark:bg-teal-500'
                    );
                    button.classList.add('bg-gray-200', 'text-gray-700', 'dark:bg-gray-800', 'dark:text-gray-300');
                });
                btn.classList.remove(
                    'bg-gray-200',
                    'text-gray-700',
                    'dark:bg-gray-800',
                    'dark:text-gray-300'
                );
                btn.classList.add('bg-teal-600', 'text-white', 'dark:bg-teal-500');
            }

            $('.buy').click(function () {
                const nama = $(this).attr('data-name');
                const harga = $(this).attr('data-price');
                const jmlUser = $(this).attr('data-jml-user');
                const periode = $(this).attr('data-periode');

                window.location.href = '/checkout/?name=' + nama + '&price=' + harga + '&users=' + jmlUser + '&periode=' + periode;
            })
        </script>
    @endpush
@endsection

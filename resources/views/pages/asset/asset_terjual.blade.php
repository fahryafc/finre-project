@extends('layouts.vertical', ['title' => 'Asset Terjual', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('css')
<link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
@vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
@vite([
'node_modules/flatpickr/dist/flatpickr.min.css',
'node_modules/@simonwep/pickr/dist/themes/classic.min.css',
'node_modules/@simonwep/pickr/dist/themes/monolith.min.css',
'node_modules/@simonwep/pickr/dist/themes/nano.min.css',
])
<style>
    .disabled {
        background-color: #e0e0e0 !important;
        color: #a0a0a0;
        cursor: not-allowed !important;
    }

    .asset-name {
        cursor: pointer;
        color: inherit;
        text-decoration: none;
    }

    .asset-name:hover {
        color: #307487;
        text-decoration: underline;
    }

    .swal-custom-title {
        color: #EF3054;
        /* Custom color for the title */
    }
</style>
@vite(['node_modules/sweetalert2/dist/sweetalert2.min.css'])
@endsection

@section('content')
<div class="grid grid-rows-1 grid-flow-col gap-4 mb-5">
    <div class="card">
        <div class="p-5">
            <div class="flex justify-between">
                <div class="w-20 h-20 rounded-full inline-flex items-center justify-center bg-success/25 ">
                    <i class="ti ti-package text-4xl text-success"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-gray-700 mt-1 text-2xl font-bold mb-5 dark:text-gray-300">
                        {{ $totalTersedia }}
                    </h3>
                    <p class="text-gray-500 mb-1 truncate dark:text-primary-400">Asset Tersedia</p>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="p-5">
            <div class="flex justify-between">
                <div class="w-20 h-20 rounded-full inline-flex items-center justify-center bg-danger/25 ">
                    <i class="ti ti-package text-4xl text-danger"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-gray-700 mt-1 text-2xl font-bold mb-5 dark:text-gray-300">
                        {{ $totalTerjual }}
                    </h3>
                    <p class="text-gray-500 mb-1 truncate dark:text-primary-400">Asset Terjual</p>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="p-5">
            <div class="flex justify-between">
                <div class="w-20 h-20 rounded-full inline-flex items-center justify-center bg-[#307487]/25 ">
                    <i class="ti ti-package text-4xl text-primary"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-gray-700 mt-1 text-2xl font-bold mb-5 dark:text-gray-300">
                        {{ "Rp. ".number_format($total_nilai_asset, 0, ".", ".") }}
                    </h3>
                    <p class="text-gray-500 mb-1 truncate dark:text-primary-400">Total Nilai Asset</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid lg grid-cols-1 gap-6">
    <div class="card mt-10 p-5">
        <div class="card-header mb-5">
            <div class="flex justify-between items-center">
                <h4 class="card-title">Data Asset Terjual</h4>
                <a href="{{ route('penjualan.create') }}" class="btn bg-[#307487] text-white">
                    <i class="mgc_add_fill text-base me-4"></i>
                    Tambah Data 
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="asset-terjual">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Nama Asset</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Kategori</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Kuantitas</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Tanggal Pembelian</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Harga Beli</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Nilai Buku</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Depresiasi</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Total Nilai Asset</th>
                        <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter = 1; @endphp
                    @foreach($asset as $ast)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $counter + ($assetTerjual->currentPage() - 1) * $assetTerjual->perPage() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $terjual->nm_aset }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $terjual->kategori }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $terjual->terjual }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $terjual->tanggal }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ "Rp. ".number_format($terjual->harga_beli, 0, ".", ".") }}
                        </td>
                        <!-- Harga Buku -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ "Rp. ".number_format($terjual->harga_buku, 0, ".", ".") }}
                        </td>

                        <!-- Persentase Depresiasi Tahunan -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                            @php
                            // Mengambil tanggal penyusutan
                            $tanggalPenyusutan = \Carbon\Carbon::parse($terjual->tanggal_penyusutan);
                            // Hitung selisih tahun antara tanggal sekarang dengan tanggal penyusutan
                            $tahunBerjalan = \Carbon\Carbon::now()->diffInYears($tanggalPenyusutan);

                            // Inisialisasi variabel untuk persentase akumulasi
                            $persentaseAkumulasi = 0;

                            // Logika depresiasi berdasarkan input penyusutan
                            if ($terjual->penyusutan == 1) {
                            if (!is_null($terjual->masa_manfaat) && $terjual->masa_manfaat > 0) {
                            // Jika masa manfaat terisi
                            $persentaseDepresiasiTahunan = 100 / $terjual->masa_manfaat;
                            $persentaseAkumulasi = $tahunBerjalan * $persentaseDepresiasiTahunan;
                            } elseif (!is_null($terjual->nilai_tahun) && $terjual->nilai_tahun > 0) {
                            // Jika nilai tahun terisi
                            $persentaseAkumulasi = $terjual->nilai_tahun * $tahunBerjalan;
                            }

                            // Pastikan persentase akumulasi tidak melebihi 100%
                            $persentaseAkumulasi = min($persentaseAkumulasi, 100);
                            }
                            @endphp

                            <!-- Tampilkan hasil berdasarkan kondisi depresiasi -->
                            @if($terjual->penyusutan == 1 && $tahunBerjalan >= 1)
                            {{ number_format($persentaseAkumulasi, 2, ".", ",") . "%" }}
                            @else
                            <!-- Tampilkan tanda '-' jika tidak memenuhi syarat -->
                            -
                            @endif
                        </td>

                        <!-- Total Harga Aset -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ "Rp. ".number_format($ast->total_harga, 0, ".", ".") }}
                        </td>
                    </tr>
                    @php $counter++; @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Detail Asset -->
<div id="modalDetailAsset" class="w-full h-auto max-h-[70vh] mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
    <div class="max-w-[60rem] fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                Detail Asset
            </h3>
            <div class="flex items-center">
                <button data-fc-type="dropdown" type="button" class="py-2 px-3 inline-flex justify-center items-center rounded-md font-medium bg-white text-gray-700 shadow-sm align-middle hover:bg-gray-50 transition-all text-sm dark:bg-gray-800 dark:hover:bg-gray-700 dark:border-gray-700 dark:text-gray-400 dark:hover:text-white">
                    <span class="material-symbols-rounded">more_vert</span>
                </button>
                @foreach($asset as $ast)
                <div class="hidden fc-dropdown-open:opacity-100 opacity-0 z-50 transition-all duration-300 bg-white border shadow-md rounded-lg p-2 dark:bg-slate-800 dark:border-slate-700">
                    <a class="flex items-center py-2 px-3 rounded-md text-sm text-gray-800 hover:bg-[#307487] hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="#">
                        Edit
                    </a>
                    <button class="flex items-center py-2 px-3 rounded-md text-sm text-gray-800 hover:bg-[#307487] hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" data-id="{{ $ast->id_aset }}" data-fc-target="modalJualAset" data-fc-type="modal"
                        onclick="loadAssetData(this)">
                        Jual
                    </button>
                    <a class="flex items-center py-2 px-3 rounded-md text-sm text-gray-800 hover:bg-[#307487] hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="#">
                        Hapus
                    </a>
                </div>

                @endforeach
                <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                    <span class="material-symbols-rounded">close</span>
                </button>
            </div>
        </div>
        <!-- Isi detail Asset -->
        <div class="flex flex-col gap-3 px-4 py-4 overflow-y-auto">
            <div class="grid grid-cols-4 items-center gap-6">
                <label for="nm_asset" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Asset</label>
                <div class="md:col-span-3">
                    <span class="nm_asset text-gray-800 text-sm font-medium inline-block"></span>
                </div>
            </div>
            <div class="grid grid-cols-4 items-center gap-6">
                <label for="kode" class="text-gray-800 text-sm font-medium inline-block mb-2">Kode Asset</label>
                <div class="md:col-span-3">
                    <span class="kode text-gray-800 text-sm font-medium inline-block"></span>
                </div>
            </div>
            <div class="grid grid-cols-4 items-center gap-6">
                <label for="tanggal" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Pembelian</label>
                <div class="md:col-span-3">
                    <span class="tanggal text-gray-800 text-sm font-medium inline-block"></span>
                </div>
            </div>
            <div class="grid grid-cols-4 items-center gap-6">
                <label for="kuantitas" class="text-gray-800 text-sm font-medium inline-block mb-2">Kuantitas</label>
                <div class="md:col-span-3">
                    <span class="kuantitas text-gray-800 text-sm font-medium inline-block"></span>
                </div>
            </div>
            <div class="grid grid-cols-4 items-center gap-6">
                <label for="harga_beli" class="text-gray-800 text-sm font-medium inline-block mb-2">Harga Beli</label>
                <div class="md:col-span-3">
                    <span class="harga_beli text-gray-800 text-sm font-medium inline-block"></span>
                </div>
            </div>
            <div class="grid grid-cols-4 items-center gap-6">
                <label for="nilai_buku" class="text-gray-800 text-sm font-medium inline-block mb-2">Nilai Buku</label>
                <div class="md:col-span-3">
                    <span class="nilai_buku text-gray-800 text-sm font-medium inline-block"></span>
                </div>
            </div>
        </div>
        <!-- Penyusutan -->
        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                Penyusutan
            </h3>
        </div>
        <div class="penyusutan-container flex flex-col gap-3 px-4 py-4 overflow-y-auto">
            <div class="grid grid-cols-4 items-center gap-6">
                <label for="nm_asset" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Penyusutan</label>
                <div class="md:col-span-3">
                    <span class="tanggal_penyusutan text-gray-800 text-sm font-medium inline-block"></span>
                </div>
            </div>
            <div class="grid grid-cols-4 items-center gap-6">
                <label for="metode_penyusutan" class="text-gray-800 text-sm font-medium inline-block mb-2">Metode Penyusutan</label>
                <div class="md:col-span-3">
                    <span class="metode_penyusutan text-gray-800 text-sm font-medium inline-block"></span>
                </div>
            </div>
            <div class="grid grid-cols-4 items-center gap-6">
                <label for="nilai_penyusutan" class="text-gray-800 text-sm font-medium inline-block mb-2">Nilai Penyusutan</label>
                <div class="md:col-span-3">
                    <span class="nilai_penyusutan text-gray-800 text-sm font-medium inline-block"></span>
                </div>
            </div>
        </div>

        <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
            <button class="btn dark:text-gray-200 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
            </button>
        </div>
    </div>
</div>
<!-- End Modal -->

<!-- Modal Tambah Assets -->
<div id="tambahAsset" class="w-full h-auto max-h-[70vh] mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
    <div class="max-w-[60rem] fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                Tambah Asset
            </h3>
            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <form action="{{ route('asset.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="px-4 py-4 overflow-y-auto max-h-[60vh]">
                <!-- row pertama -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3">
                        <label for="pemasok" class="text-gray-800 text-sm font-medium inline-block mb-2">Pemasok</label>
                        <select id="pemasok" name="pemasok" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="" selected>-- Pilih Pemasok --</option>
                            @foreach ( $pemasoks as $pemasok)
                            <option value="{{ $pemasok->nama_kontak }}">{{ $pemasok->nama_kontak }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr class="border-2 border-gray-300 my-4">

                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3">
                        <label for="tanggal" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Pembelian</label>
                        <input type="text" class="form-input" name="tanggal" id="datepicker-basic">
                    </div>
                    <div class="mb-3">
                        <label for="nm_aset" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Asset</label>
                        <input type="text" class="form-input" id="nm_aset" name="nm_aset" aria-describedby="nm_aset" placeholder="Masukan Nama Asset">
                    </div>
                    <div class="mb-3">
                        <label for="satuan" class="text-gray-800 text-sm font-medium inline-block mb-2">Satuan</label>
                        <select id="satuan" name="satuan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="" selected>-- Pilih Jenis Satuan --</option>
                            @foreach ($satuan as $satuans)
                            <option value="{{ $satuans->nama_satuan }}">{{ $satuans->nama_satuan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="text-gray-800 text-sm font-medium inline-block mb-2">Kategori</label>
                        <select id="kategori" name="satuan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="" selected>-- Pilih Kategori --</option>
                            @foreach ($kategori as $ktg)
                            <option value="{{ $ktg->nama_kategori }}">{{ $ktg->nama_kategori }}</option>
                            @endforeach
                            <option value="tambahKategori">+ Tambah Kategori</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="kuantitas" class="text-gray-800 text-sm font-medium inline-block mb-2">Kuantitas</label>
                        <input type="number" class="form-input" id="kuantitas" name="kuantitas" aria-describedby="kuantitas" placeholder="Masukan Kuantitas">
                    </div>
                </div>
                <hr class="border-2 border-gray-300 my-4">
                
                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3">
                        <label for="kode_sku" class="text-gray-800 text-sm font-medium inline-block mb-2">Kode/SKU</label>
                        <input type="text" class="form-input" id="kode_sku" name="kode_sku" aria-describedby="kode_sku" placeholder="Masukan Kode/SKU">
                    </div>
                    <div class="mb-3">
                        <label for="harga_beli" class="text-gray-800 text-sm font-medium inline-block mb-2">Harga Beli</label>
                        <input type="text" class="form-input" id="harga_beli" name="harga_beli" aria-describedby="harga_beli" placeholder="Masukan Harga Beli">
                    </div>
                    <div class="mb-3">
                        <label for="akun_pembayaran" class="text-gray-800 text-sm font-medium inline-block mb-2">Dibayarkan dari akun</label>
                        <select id="akun_pembayaran" name="akun_pembayaran" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="" selected>-- Pilih Pembayaran --</option>
                            @foreach ( $kasdanbank as $kasbank )
                            <option value="{{ $kasbank->kode_akun }}">
                                <span class="flex justify-between w-full">
                                    <span>{{ $kasbank->nama_akun }}</span>
                                    <span> - </span>
                                    <span>{{ $kasbank->kode_akun }}</span>
                                </span>
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="akun_aset" class="text-gray-800 text-sm font-medium inline-block mb-2">Akun Aset</label>
                        <select id="akun_aset" name="akun_aset" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="" selected>-- Pilih Akun Asset --</option>
                            @foreach ( $akun as $akuns )
                            <option value="{{ $akuns->kode_akun }}">
                                <span class="flex justify-between w-full">
                                    <span>{{ $akuns->nama_akun }}</span>
                                </span>
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="flex items-center">
                            <input type="checkbox" id="penyusutan" name="penyusutan" class="form-switch text-primary" value="1" onchange="toggleCollapseWithSwitch()">
                            <label for="penyusutan" class="ms-1.5">Penyusutan</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="mb-3">
                            <div class="flex items-center">
                                <input type="checkbox" id="pajakButton" name="pajakButton" class="form-switch text-primary" value="1" onchange="toggleCollapsePajak()">
                                <label for="pajakButton" class="ms-1.5">Pajak</label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <hr class="border-2 border-gray-300 my-4"> <!-- Garis pemisah -->

                <!-- collapse pajak -->
                <div id="collapsePajak" class="hidden w-full overflow-hidden transition-[height] duration-300 mt-5">
                    <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                        <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                            Pajak
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-3">
                        <div class="mb-3">
                            <label for="jns_pajak" class="text-gray-800 text-sm font-medium inline-block mb-2">Jenis Pajak</label>
                            <select id="jns_pajak" name="jns_pajak" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Jenis Pajak --</option>
                                <option value="ppn">PPN</option>
                                <option value="ppnbm">PPnBM</option>
                                <option value="bphtb">BPHTB</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="pajak" class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak (%)</label>
                            <input type="text" class="form-input" id="pajak" name="pajak" aria-describedby="pajak" placeholder="Masukan Pajak (%)">
                        </div>
                        <div class="mb-3">
                            <label for="pajak_dibayarkan" class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak Dibayarkan</label>
                            <input type="text" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" id="pajak_dibayarkan" name="pajak_dibayarkan" aria-describedby="pajak_dibayarkan" placeholder="Pajak Dibayarkan" readonly>
                        </div>
                    </div>
                    <!-- end row collapse -->
                    <hr class="border-2 border-gray-300 my-4"> <!-- Garis pemisah -->
                </div>

                <!-- row kedua: collapse penyusutan-->
                <div id="collapseWithTarget" class="hidden w-full overflow-hidden transition-[height] duration-300 mt-5">
                    <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                        <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                            Penyusutan
                        </h3>
                    </div>

                    <!-- row pertama -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                        <div>
                            <div class="mb-3">
                                <label for="tanggal_penyusutan" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Penyusutan</label>
                                <input type="text" class="form-input" name="tanggal_penyusutan" id="datepicker-basic">
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <!-- row kedua -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                        <div>
                            <div class="mb-3">
                                <label for="masa_manfaat" class="text-gray-800 text-sm font-medium inline-block mb-2 flex items-center">
                                    <input type="checkbox" id="enable_masa_manfaat" class="form-checkbox rounded text-primary mr-2" onchange="toggleFields('masa_manfaat', 'nilai_tahun')">
                                    Masa Manfaat
                                </label>
                                <div class="flex">
                                    <input type="number" class="form-input" id="masa_manfaat" name="masa_manfaat" aria-describedby="masa_manfaat" placeholder="Masukan Masa Manfaat">
                                    <div class="inline-flex items-center px-4 rounded-e border border-s-0 border-gray-200 bg-gray-200 text-gray-500 dark:bg-gray-700 dark:border-gray-700 dark:text-gray-400">
                                        Tahun
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="mb-3">
                                <label for="nilai_tahun" class="text-gray-800 text-sm font-medium inline-block mb-2 flex items-center">
                                    <input type="checkbox" id="enable_nilai_tahun" class="form-checkbox rounded text-primary mr-2" onchange="toggleFields('nilai_tahun', 'masa_manfaat')">
                                    Nilai/Tahun
                                </label>
                                <div class="flex">
                                    <input type="number" class="form-input" id="nilai_tahun" name="nilai_tahun" aria-describedby="nilai_tahun">
                                    <div class="inline-flex items-center px-4 rounded-e border border-s-0 border-gray-200 bg-gray-200 text-gray-500 dark:bg-gray-700 dark:border-gray-700 dark:text-gray-400">
                                        %
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <!-- row ketiga -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                        <div>
                            <div class="mb-3">
                                <label for="akun_penyusutan" class="text-gray-800 text-sm font-medium inline-block mb-2">Akun Penyusutan</label>
                                <select id="akun_penyusutan" name="akun_penyusutan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" selected>-- Pilih Akun --</option>
                                    @foreach ( $akun_penyusutan as $penyusutan )
                                    <option value="{{ $penyusutan->kode_akun }}">
                                        <span class="flex justify-between w-full">
                                            <span>{{ $penyusutan->nama_akun }}</span>
                                        </span>
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <div class="mb-3">
                                <label for="akumulasi_akun" class="text-gray-800 text-sm font-medium inline-block mb-2">Akumulasi Akun Penyusutan</label>
                                <select id="akumulasi_akun" name="akumulasi_akun" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" selected>-- Akun Akumulasi --</option>
                                    @foreach ( $akun_penyusutan as $penyusutan )
                                    <option value="{{ $penyusutan->kode_akun }}">
                                        <span class="flex justify-between w-full">
                                            <span>{{ $penyusutan->nama_akun }}</span>
                                        </span>
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
                </div>
            </div>
            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                <button class="btn dark:text-gray-200 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                </button>
                <button class="btn bg-[#307487] text-white" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>
<!-- end modal -->

<!-- Modal Edit Assets -->
@foreach($asset as $ast)
<div id="modalEditAsset" class="w-full h-auto max-h-[70vh] mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
    <div class="max-w-[60rem] fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                Edit Asset
            </h3>
            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <form action="{{ route('asset.update', $ast->id_aset) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="px-4 py-4 overflow-y-auto max-h-[60vh]">
                <!-- row pertama -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Kolom 1 -->
                    <div>
                        <div class="mb-3">
                            <label for="pemasok" class="text-gray-800 text-sm font-medium inline-block mb-2">Pemasok</label>
                            <input type="text" class="form-input" id="pemasok" name="pemasok" aria-describedby="pemasok" placeholder="Masukan Nama Pemasok" value="{{ old('pemasok', $ast->pemasok) }}">
                        </div>
                        <div class="mb-3">
                            <label for="no_hp" class="text-gray-800 text-sm font-medium inline-block mb-2">No Handphone</label>
                            <input type="number" class="form-input" id="no_hp" name="no_hp" aria-describedby="no_hp" placeholder="Masukan No Handphone" value="{{ old('no_hp', $ast->no_hp) }}">
                        </div>
                        <div class="mb-3">
                            <label for="nm_perusahaan" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Perusahaan</label>
                            <input type="text" class="form-input" id="nm_perusahaan" name="nm_perusahaan" aria-describedby="nm_perusahaan" placeholder="Masukan Nama Perusahaan" value="{{ old('nm_perusahaan', $ast->nm_perusahaan) }}">
                        </div>
                        <div class="mb-e">
                            <label for="email" class="text-gray-800 text-sm font-medium inline-block mb-2">Email</label>
                            <input type="email" class="form-input" id="email" name="email" aria-describedby="email" placeholder="Masukan Email" value="{{ old('email', $ast->email) }}">
                        </div>
                        <div class="mb-3">
                            <label for="alamat" name="alamat" class="text-gray-800 text-sm font-medium inline-block mb-2">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukan Alamat"> {{ old('alamat', $ast->alamat) }}</textarea>
                        </div>
                    </div>
                    <!-- Kolom 2 -->
                    <div>
                        <div class="mb-3">
                            <label for="tanggal" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Pembelian</label>
                            <input type="text" class="form-input" name="tanggal" id="datepicker-basic" value="{{ old('tanggal', $ast->tanggal) }}">
                        </div>
                        <div class="mb-3">
                            <label for="nm_aset" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Asset</label>
                            <input type="text" class="form-input" id="nm_aset" name="nm_aset" aria-describedby="nm_aset" placeholder="Masukan Nama Asset" value="{{ old('nm_aset', $ast->nm_aset) }}">
                        </div>
                        <div class="mb-3">
                            <label for="satuan" class="text-gray-800 text-sm font-medium inline-block mb-2">Satuan</label>
                            <select id="satuan" name="satuan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" {{ $ast->satuan == '' ? 'selected' : '' }}>-- Pilih Jenis Satuan --</option>
                                @foreach ($satuan as $satuans)
                                <option value="{{ $satuans->nama_satuan }}" {{ $ast->satuan == $satuans->nama_satuan ? 'selected' : '' }}>
                                    {{ $satuans->nama_satuan }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="kategori" class="text-gray-800 text-sm font-medium inline-block mb-2">Kategori</label>
                            <select id="kategori" name="satuan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Kategori --</option>
                                @foreach ($kategori as $ktg)
                                <option value="{{ $ktg->nama_kategori }}">{{ $ktg->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="kuantitas" class="text-gray-800 text-sm font-medium inline-block mb-2">Kuantitas</label>
                            <input type="number" class="form-input" id="kuantitas" name="kuantitas" aria-describedby="kuantitas" placeholder="Masukan Kuantitas" value="{{ old('kuantitas', $ast->kuantitas) }}">
                        </div>
                    </div>
                    <!-- kolom 3 -->
                    <div>
                        <div class="mb-3">
                            <label for="kode_sku" class="text-gray-800 text-sm font-medium inline-block mb-2">Kode/SKU</label>
                            <input type="text" class="form-input" id="kode_sku" name="kode_sku" aria-describedby="kode_sku" placeholder="Masukan Kode/SKU" value="{{ old('kode_sku', $ast->kode_sku) }}">
                        </div>
                        <div class="mb-3">
                            <label for="harga_beli" class="text-gray-800 text-sm font-medium inline-block mb-2">Harga Beli</label>
                            <input type="text" class="form-input" id="harga_beli" name="harga_beli" aria-describedby="harga_beli" placeholder="Masukan Harga Beli" value="{{ old('harga_beli', $ast->harga_beli) }}">
                        </div>
                        <div class="mb-3">
                            <label for="akun_pembayaran" class="text-gray-800 text-sm font-medium inline-block mb-2">Dibayarkan dari akun</label>
                            <select id="akun_pembayaran" name="akun_pembayaran" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" {{ $ast->akun_pembayaran == '' ? 'selected' : '' }}>-- Pilih Pembayaran --</option>
                                @foreach ( $kasdanbank as $kasbank )
                                <option value="{{ $kasbank->kode_akun }}" {{ $ast->akun_pembayaran == $kasbank->kode_akun ? 'selected' : '' }}>
                                    <span class="flex justify-between w-full">
                                        <span>{{ $kasbank->nama_akun }}</span>
                                        <span> - </span>
                                        <span>{{ $kasbank->kode_akun }}</span>
                                    </span>
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="akun_aset" class="text-gray-800 text-sm font-medium inline-block mb-2">Akun Aset</label>
                            <select id="akun_aset" name="akun_aset" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" {{ $ast->akun_aset == '' ? 'selected' : '' }}>-- Pilih Akun Asset --</option>
                                @foreach ( $akun as $akuns )
                                <option value="{{ $akuns->kode_akun }}" {{ $ast->akun_aset == $akuns->kode_akun ? 'selected' : '' }}>
                                    <span class="flex justify-between w-full">
                                        <span>{{ $akuns->nama_akun }}</span>
                                    </span>
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <div class="flex items-center">
                                <input type="checkbox" id="penyusutan1" name="penyusutan1" class="form-switch text-primary" value="1" onchange="toggleCollapseWithSwitch1()" {{ $ast->penyusutan == 1 ? 'checked' : '' }}>
                                <label for="penyusutan1" class="ms-1.5">Penyusutan</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="mb-3">
                                <div class="flex items-center">
                                    <input type="checkbox" id="pajakButton1" name="pajakButton1" class="form-switch text-primary" value="1" onchange="toggleCollapsePajak1()" {{ $ast->pajak == 1 ? 'checked' : '' }}>
                                    <label for="pajakButton1" class="ms-1.5">Pajak</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <hr class="border-2 border-gray-300 my-4"> <!-- Garis pemisah -->

                <!-- collapse pajak -->
                <div id="collapsePajak1" class="{{ $ast->pajak == 1 ? '' : 'hidden' }}hidden w-full overflow-hidden transition-[height] duration-300 mt-5">
                    <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                        <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                            Pajak
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-3">
                        <div class="mb-3">
                            <label for="jns_pajak" class="text-gray-800 text-sm font-medium inline-block mb-2">Jenis Pajak</label>
                            <select id="jns_pajak" name="jns_pajak" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Jenis Pajak --</option>
                                <option value="ppn">PPN</option>
                                <option value="ppnbm">PPnBM</option>
                                <option value="bphtb">BPHTB</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="pajak" class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak (%)</label>
                            <input type="text" class="form-input" id="pajak" name="pajak" aria-describedby="pajak" placeholder="Masukan Pajak (%)">
                        </div>
                        <div class="mb-3">
                            <label for="pajak_dibayarkan" class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak Dibayarkan</label>
                            <input type="text" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" id="pajak_dibayarkan" name="pajak_dibayarkan" aria-describedby="pajak_dibayarkan" placeholder="Pajak Dibayarkan" readonly>
                        </div>
                    </div>
                    <!-- end row collapse -->
                    <hr class="border-2 border-gray-300 my-4"> <!-- Garis pemisah -->
                </div>

                <!-- row kedua: collapse penyusutan-->
                <div id="collapseWithTarget1" class="{{ $ast->penyusutan == 1 ? '' : 'hidden' }}hidden w-full overflow-hidden transition-[height] duration-300 mt-5">
                    <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                        <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                            Penyusutan
                        </h3>
                    </div>

                    <!-- row pertama -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                        <div>
                            <div class="mb-3">
                                <label for="tanggal_penyusutan" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Penyusutan</label>
                                <input type="text" class="form-input" name="tanggal_penyusutan" id="datepicker-basic">
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <!-- row kedua -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                        <div>
                            <div class="mb-3">
                                <label for="masa_manfaat" class="text-gray-800 text-sm font-medium inline-block mb-2 flex items-center">
                                    <input type="checkbox" id="enable_masa_manfaat" class="form-checkbox rounded text-primary mr-2" onchange="toggleFields('masa_manfaat', 'nilai_tahun')">
                                    Masa Manfaat
                                </label>
                                <div class="flex">
                                    <input type="number" class="form-input" id="masa_manfaat" name="masa_manfaat" aria-describedby="masa_manfaat" placeholder="Masukan Masa Manfaat">
                                    <div class="inline-flex items-center px-4 rounded-e border border-s-0 border-gray-200 bg-gray-200 text-gray-500 dark:bg-gray-700 dark:border-gray-700 dark:text-gray-400">
                                        Tahun
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="mb-3">
                                <label for="nilai_tahun" class="text-gray-800 text-sm font-medium inline-block mb-2 flex items-center">
                                    <input type="checkbox" id="enable_nilai_tahun" class="form-checkbox rounded text-primary mr-2" onchange="toggleFields('nilai_tahun', 'masa_manfaat')">
                                    Nilai/Tahun
                                </label>
                                <div class="flex">
                                    <input type="number" class="form-input" id="nilai_tahun" name="nilai_tahun" aria-describedby="nilai_tahun">
                                    <div class="inline-flex items-center px-4 rounded-e border border-s-0 border-gray-200 bg-gray-200 text-gray-500 dark:bg-gray-700 dark:border-gray-700 dark:text-gray-400">
                                        %
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <!-- row ketiga -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                        <div>
                            <div class="mb-3">
                                <label for="akun_penyusutan" class="text-gray-800 text-sm font-medium inline-block mb-2">Akun Penyusutan</label>
                                <select id="akun_penyusutan" name="akun_penyusutan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" selected>-- Pilih Akun --</option>
                                    @foreach ( $akun_penyusutan as $penyusutan )
                                    <option value="{{ $penyusutan->kode_akun }}">
                                        <span class="flex justify-between w-full">
                                            <span>{{ $penyusutan->nama_akun }}</span>
                                        </span>
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <div class="mb-3">
                                <label for="akumulasi_akun" class="text-gray-800 text-sm font-medium inline-block mb-2">Akumulasi Akun Penyusutan</label>
                                <select id="akumulasi_akun" name="akumulasi_akun" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" selected>-- Akun Akumulasi --</option>
                                    @foreach ( $akun_penyusutan as $penyusutan )
                                    <option value="{{ $penyusutan->kode_akun }}">
                                        <span class="flex justify-between w-full">
                                            <span>{{ $penyusutan->nama_akun }}</span>
                                        </span>
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
                </div>
            </div>
            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                <button class="btn dark:text-gray-200 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                </button>
                <button class="btn bg-[#307487] text-white" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>
@endforeach
<!-- end modal -->

<!-- Modal Jual Asset -->
<div id="modalJualAset" class="w-full h-auto max-h-[70vh] mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
    <div class="max-w-[60rem] fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                Jual Asset
            </h3>
            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <form action="{{ route('penjualan-asset.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="px-4 py-4 overflow-y-auto max-h-[60vh]">
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <input type="hidden" id="id_aset" name="id_aset"> <!-- ID asset yang dijual -->
                        <div class="mb-3">
                            <label for="nm_pelanggan" class="text-gray-800 text-sm font-medium inline-block mb-2">Pelanggan</label>
                            <input type="text" class="form-input" id="nm_pelanggan" name="nm_pelanggan" aria-describedby="nm_pelanggan" placeholder="Masukan Nama Pelanggan">
                        </div>
                        <div class="mb-3">
                            <label for="no_hp" class="text-gray-800 text-sm font-medium inline-block mb-2">No Handphone</label>
                            <input type="number" class="form-input" id="no_hp" name="no_hp" aria-describedby="no_hp" placeholder="Masukan No handphone">
                        </div>
                    </div>

                    <div>
                        <div class="mb-3">
                            <label for="gender" class="text-gray-800 text-sm font-medium inline-block mb-2">Gender</label>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                <select id="gender" name="gender" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="Laki - Laki">Laki - Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </td>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="text-gray-800 text-sm font-medium inline-block mb-2">Email</label>
                            <input type="email" class="form-input" id="email" name="email" aria-describedby="emailHelp" placeholder="Masukan email">
                        </div>
                    </div>

                    <div>
                        <div class="mb-3">
                            <label for="nm_perusahaan" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Perusahaan</label>
                            <input type="text" class="form-input" id="nm_perusahaan" name="nm_perusahaan" aria-describedby="nm_perusahaan" placeholder="Masukan Nama Perusahaan">
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="text-gray-800 text-sm font-medium inline-block mb-2">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukan Alamat"></textarea>
                        </div>
                    </div>
                </div>

                <hr class="border-2 border-gray-300 my-4"> <!-- Garis pemisah -->

                <!-- Row 1: Tanggal Pembelian dan Tanggal Penjualan -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="mb-3">
                        <label for="tanggal" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Pembelian</label>
                        <input type="text" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" name="tanggal" id="datepicker-basic" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="tgl_penjualan" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Penjualan</label>
                        <input type="text" class="form-input" name="tgl_penjualan" id="datepicker-basic">
                    </div>
                </div>

                <!-- Row 2: Harga Beli, Kuantitas, Total Nilai Asset -->
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div class="mb-3">
                        <label for="harga_beli" class="text-gray-800 text-sm font-medium inline-block mb-2">Harga Beli</label>
                        <input type="text" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" id="harga_beli_aset" name="harga_beli" placeholder="Masukan Harga Beli" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="kuantitas" class="text-gray-800 text-sm font-medium inline-block mb-2">Kuantitas</label>
                        <input type="text" class="form-input" id="jumlah" name="kuantitas" placeholder="Masukan Kuantitas">
                    </div>
                    <div class="mb-3">
                        <label for="total_nilai_asset" class="text-gray-800 text-sm font-medium inline-block mb-2">Total Nilai Asset</label>
                        <input type="text" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" id="total_nilai_asset" name="total_nilai_asset" placeholder="Total Nilai Asset" readonly>
                    </div>
                </div>

                <!-- Row 3: Harga Pelepasan, Total Nilai Penyusutan -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="mb-3">
                        <label for="harga_pelepasan" class="text-gray-800 text-sm font-medium inline-block mb-2">Harga Pelepasan</label>
                        <input type="text" class="form-input" id="harga_pelepasan" name="harga_pelepasan" placeholder="Masukan Harga Pelepasan">
                    </div>
                    <div class="mb-3">
                        <label for="nilai_penyusutan_terakhir" class="text-gray-800 text-sm font-medium inline-block mb-2">Total Nilai Penyusutan</label>
                        <input type="text" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" id="nilai_penyusutan_terakhir" name="nilai_penyusutan_terakhir" placeholder="Total Nilai Penyusutan" readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="mb-3">
                        <div class="flex items-center">
                            <input type="checkbox" id="buttonPajakPenjualan" name="buttonPajakPenjualan" class="form-switch text-primary" value="1" onchange="toggleCollapsePajakPenjualan()">
                            <label for="buttonPajakPenjualan" class="ms-1.5">Aktifkan Pajak Penjualan</label>
                        </div>
                    </div>
                </div>

                <!-- collapse pajak penjualan -->
                <div id="collapsePajakPenjualan" class="hidden w-full overflow-hidden transition-[height] duration-300 mt-5">
                    <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                        <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                            Pajak
                        </h3>
                    </div>

                    <div class="grid grid-cols-4 gap-4 mb-4">
                        <div class="mb-3">
                            <label for="jns_pajak_penjualan" class="text-gray-800 text-sm font-medium inline-block mb-2">Jenis Pajak</label>
                            <select id="jns_pajak_penjualan" name="jns_pajak_penjualan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Jenis Pajak --</option>
                                <option value="ppn">PPN</option>
                                <option value="ppnbm">PPnBM</option>
                                <option value="bphtb">BPHTB</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="pajak_penjualan" class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak (%)</label>
                            <input type="text" class="form-input" id="pajak_penjualan" name="pajak_penjualan" aria-describedby="pajak" placeholder="Masukan Pajak (%)">
                        </div>
                        <div class="mb-3">
                            <label for="pajak_penjualan_dibayarkan" class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak Dibayarkan</label>
                            <input type="text" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" id="pajak_penjualan_dibayarkan" name="pajak_penjualan_dibayarkan" aria-describedby="pajak_penjualan_dibayarkan" placeholder="Pajak Dibayarkan" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="akun_pajak_penjualan" class="text-gray-800 text-sm font-medium inline-block mb-2">Akun Pajak</label>
                            <select id="akun_pajak_penjualan" name="akun_pajak_penjualan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Akun Pajak --</option>
                                <option value="ppn">Ex Akun Pajak</option>
                            </select>
                        </div>
                    </div>
                    <!-- end row collapse -->
                    <hr class="border-2 border-gray-300 my-4"> <!-- Garis pemisah -->
                </div>

                <hr class="border-2 border-gray-300 my-4"> <!-- Garis pemisah -->

                <div class="overflow-x-auto">
                    <div class="min-w-full inline-block align-middle">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis"></th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis"></th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Debit</th>
                                        <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Kredit</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">Nilai Buku</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                            <input type="text" id="nilai_buku" name="nilai_buku" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" value="Rp. 0">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">Didepositkan Ke</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                            <select id="akun_deposit" name="akun_deposit" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <option value="" selected>-- Akun --</option>
                                                @foreach ( $akun_deposit as $deposit )
                                                <option value="{{ $deposit->kode_akun }}">
                                                    <span class="flex justify-between w-full">
                                                        <span>{{ $deposit->nama_akun }}</span>
                                                    </span>
                                                </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                            <input type="text" id="hargaPelepasanDisplay" name="nominal_deposit" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" value="Rp. 0">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> Keuntungan / Kerugian </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                            <select id="akun_keuntungan_kerugian" name="akun_keuntungan_kerugian" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <option value="" selected>-- Akun --</option>
                                                @foreach ( $akun_kredit as $kredit )
                                                <option value="{{ $kredit->kode_akun }}">
                                                    <span class="flex justify-between w-full">
                                                        <span>{{ $kredit->nama_akun }}</span>
                                                    </span>
                                                </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                            <input type="text" id="keuntungan_kerugian" name="nominal_keuntungan_kerugian" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" value="Rp. 0">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                <button class="btn dark:text-gray-200 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                </button>
                <button class="btn bg-[#307487] text-white" type="submit">Jual</button>
            </div>
        </form>
    </div>
</div>
<!-- end modal -->

<!-- Modal Tambah Kategori -->
<div id="tambahKategori" class="w-full h-full mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
    <div class="sm:max-w-2xl fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                Tambah Kategori
            </h3>
            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <form action="{{ route('kategori.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="px-4 py-8 overflow-y-auto">
                <div class="grid grid-cols-2 gap-4">
                    <!-- col 1 -->
                    <div>
                        <div class="mb-3">
                            <label for="nama_kategori" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Kategori</label>
                            <input type="text" class="form-input" id="nama_kategori" name="nama_kategori" aria-describedby="nama_kategori" placeholder="Masukan Nama Kategori">
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                <button class="btn dark:text-gray-200 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                </button>
                <button class="btn bg-[#307487] text-white" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>
<!-- end modal -->
@endsection

@section('script')
@vite(['resources/js/pages/table-gridjs.js'])
@vite(['resources/js/pages/highlight.js', 'resources/js/pages/form-flatpickr.js', 'resources/js/pages/form-color-pickr.js'])
@vite(['resources/js/pages/extended-sweetalert.js'])
@vite(['resources/js/pages/highlight.js'])
<!-- <script src="{{ asset('js/custom-js/assets.js') }}" defer></script> -->
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/custom-js/assets.js') }}" defer></script>
@endsection
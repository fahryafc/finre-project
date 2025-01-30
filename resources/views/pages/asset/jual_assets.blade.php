@extends('layouts.vertical', ['title' => 'Jual Asset', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('css')
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
<div class="grid lg grid-cols-1 gap-6 mt-5">
    <div class="card">
        <div class="card-header">
            <div class="flex justify-between items-center">
                <h4 class="card-title">Data Jual Asset</h4>
            </div>
        </div>
        <div class="p-6">
            <form action="{{ route('penjualan-asset.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                    <div class="grid grid-cols-3 gap-4">
                        <div class="mb-3">
                            <label for="id_kontak" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Pelanggan</label>
                            <select id="id_kontak" name="id_kontak" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Pelanggan --</option>
                                @foreach ($pelanggan as $customer)
                                <option value="{{ $customer->id_kontak }}">{{ $customer->nama_kontak }} - {{ $customer->nm_perusahaan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr class="border-2 border-gray-300 my-4"> <!-- Garis pemisah -->

                    <!-- Row 1: Tanggal Pembelian dan Tanggal Penjualan -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="mb-3">
                            <label for="tanggal" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Pembelian</label>
                            <input type="text" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed tgl_pembelian"  name="tanggal" id="datepicker-basic" value="{{ \Carbon\Carbon::parse($asset->tanggal)->format('d-m-Y') }}" data-tanggal="{{ \Carbon\Carbon::parse($asset->tanggal)->format('d-m-Y') }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="tgl_penjualan" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Penjualan</label>
                            <input type="text" class="form-input tgl_penjualan" name="tgl_penjualan" id="datepicker-basic">
                        </div>
                    </div>

                    <!-- Row 2: Harga Beli, Kuantitas, Total Nilai Asset -->
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div class="mb-3">
                            <label for="harga_beli" class="text-gray-800 text-sm font-medium inline-block mb-2">Harga Beli</label>
                            <input type="text" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" id="harga_beli" name="harga_beli" placeholder="Masukan Harga Beli" value="{{ 'Rp. '.number_format(old('harga_beli', $asset->harga_beli, 0, '.', '.')) }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="kuantitas" class="text-gray-800 text-sm font-medium inline-block mb-2">Kuantitas</label>
                            <input type="text" class="form-input" id="kuantitas" name="kuantitas" placeholder="Masukan Kuantitas" value="{{ old('kuantitas', $asset->kuantitas) }}" data-max-kuantitas="{{ $asset->kuantitas }}">
                        </div>
                        <div class="mb-3">
                            <label for="total_nilai_asset" class="text-gray-800 text-sm font-medium inline-block mb-2">Total Nilai Asset</label>
                            <input type="text" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" id="total_nilai_asset" name="total_nilai_asset" placeholder="Total Nilai Asset" value="{{ 'Rp. '.number_format(old('total_nilai_asset', $asset->harga_beli * $asset->kuantitas, 0, '.', '.')) }}" readonly>
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
                                <label for="jns_pajak" class="text-gray-800 text-sm font-medium inline-block mb-2">Jenis Pajak</label>
                                <select id="jns_pajak" name="jns_pajak" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" selected>-- Pilih Jenis Pajak --</option>
                                    <option value="ppn11">PPN (11%)</option>
                                    <option value="ppn12">PPN (12%)</option>
                                    <option value="ppnbm">PPnBM</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="pajak_penjualan" class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak (%)</label>
                                <input type="text" class="form-input" id="pajak_penjualan" name="pajak_penjualan" aria-describedby="pajak" placeholder="Masukan Pajak (%)">
                            </div>
                            <div class="mb-3">
                                <label for="pajak_dibayarkan" class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak Dibayarkan</label>
                                <input type="text" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" id="pajak_dibayarkan" name="pajak_dibayarkan" aria-describedby="pajak_dibayarkan" placeholder="Pajak Dibayarkan" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="akun_pajak_penjualan" class="text-gray-800 text-sm font-medium inline-block mb-2">Akun Pajak</label>
                                <select id="akun_pajak_penjualan" name="akun_pajak_penjualan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" selected>-- Pilih Akun Pajak --</option>
                                    <option value="ppn">Ex Akun Pajak</option>
                                </select>
                            </div>
                        </div>
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
                                                <input type="text" id="nominal_deposit" name="nominal_deposit" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" value="Rp. 0">
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

                <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                    <button class="btn dark:text-gray-200 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                    </button>
                    <button class="btn bg-[#307487] text-white" type="submit">Jual</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
@vite(['resources/js/pages/table-gridjs.js'])
@vite(['resources/js/pages/highlight.js', 'resources/js/pages/form-flatpickr.js', 'resources/js/pages/form-color-pickr.js'])
@vite(['resources/js/pages/extended-sweetalert.js'])
@vite(['resources/js/pages/highlight.js'])
<!-- <script src="{{ asset('js/custom-js/assets.js') }}" defer></script> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/custom-js/jual-assets.js') }}" defer></script>
@endsection
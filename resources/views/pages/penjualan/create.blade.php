@extends('layouts.vertical', ['title' => 'Penjualan', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('css')
<link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
@vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
@vite(['node_modules/nice-select2/dist/css/nice-select2.css'])
@vite([
'node_modules/flatpickr/dist/flatpickr.min.css',
'node_modules/@simonwep/pickr/dist/themes/classic.min.css',
'node_modules/@simonwep/pickr/dist/themes/monolith.min.css',
'node_modules/@simonwep/pickr/dist/themes/nano.min.css',
])
@vite(['node_modules/sweetalert2/dist/sweetalert2.min.css'])
@endsection

@section('content')
<div class="col-span-2">
        <div class="card">
            <div class="card-header">
                <div class="flex justify-between items-center">
                    <h4 class="card-title">Tambah Data Penjualan</h4>
                    <!-- <div class="flex items-center gap-2">
                        <button type="button" class="btn-code" data-fc-type="collapse" data-fc-target="GridFormHtml">
                            <i class="mgc_eye_line text-lg"></i>
                            <span class="ms-2">Code</span>
                        </button>

                        <button class="btn-code" data-clipboard-action="copy">
                            <i class="mgc_copy_line text-lg"></i>
                            <span class="ms-2">Copy</span>
                        </button>
                    </div> -->
                </div>
            </div>
            
            <div class="p-6">
                <form id="" action="{{ route('penjualan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-3 gap-4">
                        <div class="mb-3">
                            <label for="tanggal" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Penjualan</label>
                            <input type="text" class="form-input tgl_penjualan" name="tanggal" id="datepicker-basic">
                        </div>
                    </div>
                    <hr class="border-1 border-gray-300 my-1">
                    <div class="grid grid-cols-2 gap-4">
                        <!-- col 1 -->
                        <div class="mb-3">
                            <label for="id_kontak" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Pelanggan</label>
                            <select id="id_kontak" name="id_kontak" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Pelanggan --</option>
                                @foreach ( $pelanggan as $customer)
                                <option value="{{ $customer->id_kontak }}">{{ $customer->nama_kontak }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr class="border-1 border-gray-300 my-1">
                    <!-- col 2 -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="mb-3">
                            <label for="produk" name="produk" class="text-gray-800 text-sm font-medium inline-block mb-2">Produk</label>
                            <select id="produk" name="produk" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Produk --</option>
                                @foreach ( $produk as $produks)
                                <option value="{{ $produks->nama_produk }}">{{ $produks->nama_produk }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="satuan" name="satuan" class="text-gray-800 text-sm font-medium inline-block mb-2">Satuan</label>
                            <select id="satuan" name="satuan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Satuan --</option>
                                @foreach ( $satuan as $satuans)
                                <option value="{{ $satuans->nama_satuan }}">{{ $satuans->nama_satuan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-3">
                                <label for="harga" class="text-gray-800 text-sm font-medium inline-block mb-2">Harga</label>
                                <input type="text" class="form-input" id="harga" name="harga" aria-describedby="harga" placeholder="Masukan Harga">
                            </div>
                            <div class="mb-3">
                                <label for="kuantitas" class="text-gray-800 text-sm font-medium inline-block mb-2">Kuantitas</label>
                                <input type="text" class="form-input" id="kuantitas" name="kuantitas" aria-describedby="kuantitas" placeholder="Masukan Kuantitas">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="pembayaran" class="text-gray-800 text-sm font-medium inline-block mb-2"> Kas & Bank </label>
                            <select id="pembayaran" name="pembayaran" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Akun --</option>
                                @foreach ( $kas_bank as $akuns )
                                <option value="{{ $akuns->kode_akun }}">
                                    <span class="flex justify-between w-full">
                                        <span>{{ $akuns->nama_akun }}</span>
                                        <span> - </span>
                                        <span>{{ $akuns->kode_akun }}</span>
                                    </span>
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr class="border-1 border-gray-300 my-2">

                    <div class="grid grid-cols-2 gap-4">
                        <div class="mb-3">
                            <label for="jns_pajak" class="text-gray-800 text-sm font-medium inline-block mb-2"> Jenis Pajak </label>
                            <select id="jns_pajak" name="jns_pajak" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="jns_pajak" selected>-- Jenis Pajak --</option>
                                <option value="ppn">PPN</option>
                                <option value="ppnbm">PPnBM</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="persen_pajak" class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak (%)</label>
                            <div class="flex w-full">
                                <input type="text" id="persen_pajak" name="persen_pajak" placeholder="Masukan Pajak (%)" class="form-input ltr:rounded-r-none rtl:rounded-l-none  w-1/3" disabled/>
                                <input type="text" id="nominal_pajak" name="nominal_pajak" class="inline-flex items-center px-4 rounded-e border border-s-0 border-gray-200 bg-gray-300 text-gray-500 dark:bg-gray-700 dark:border-gray-700 dark:text-gray-400 pajak-output flex-1" value="" disabled>
                            </div>
                        </div>
                    </div>
                    <hr class="border-1 border-gray-300 my-1">

                    <div class="grid grid-cols-2 gap-4">
                        <div class="mb-3">
                            <label for="diskon" class="text-gray-800 text-sm font-medium inline-block mb-2">Diskon</label>
                            <div class="flex w-full">
                                <input type="text" id="diskon" name="diskon" placeholder="Masukan Diskon" class="form-input ltr:rounded-r-none rtl:rounded-l-none w-1/3" />
                                <div class="inline-flex items-center px-4 rounded-e border border-s-0 border-gray-200 bg-gray-300 text-gray-500 dark:bg-gray-700 dark:border-gray-700 dark:text-gray-400 diskon-output flex-1">
                                    Rp. 0
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="ongkir" class="text-gray-800 text-sm font-medium inline-block mb-2">Biaya Pengiriman</label>
                            <input type="text" class="form-input" id="ongkir" name="ongkir" aria-describedby="ongkir" placeholder="Masukan Ongkir">
                        </div>
                        <div class="mb-3">
                            <div class="flex items-center">
                                <label for="piutangSwitch" class="text-gray-800 text-sm font-medium">Piutang</label>
                                <label class="inline-flex items-center ml-2">
                                    <input type="checkbox" id="piutangSwitch" name="piutangSwitch" class="form-switch text-primary" onclick="togglePiutangInput()">
                                </label>
                            </div>

                            <!-- Input Field for Piutang (Hidden by Default) -->
                            <div id="piutangInputContainer" class="mt-2 hidden">
                                <input type="text" class="form-input w-full" id="piutang" name="piutang" aria-describedby="piutang" placeholder="Masukan Piutang">
                            </div>
                        </div>
                        <div class="mb-3 hidden" id="tglJatuhTempoContainer">
                            <label for="tgl_jatuh_tempo" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Jatuh Tempo</label>
                            <input type="text" class="form-input" name="tgl_jatuh_tempo" id="datepicker-basic">
                        </div>
                    </div>
                    <hr class="border-1 border-gray-300 my-2">
                    <!-- col 4 -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="mb-3"></div>
                        <div class="mb-3">
                            <div class="flex w-full">
                                <label class="text-gray-800 text-sm font-medium inline-block pajak-output p-2 w-1/3">
                                    Total Pemasukan
                                </label>
                                <input type="text" id="total_pemasukan" name="total_pemasukan" class="form-input ltr:rounded-r-none rtl:rounded-l-none bg-[#307487] flex-1" value="Total Pemasukan" style="color: white;" readonly/>
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
    </div> <!-- end col -->
@endsection

@section('script')
@vite('resources/js/pages/charts-apex.js')
@vite(['resources/js/pages/table-gridjs.js'])
@vite(['resources/js/pages/highlight.js', 'resources/js/pages/form-select.js'])
@vite(['resources/js/pages/highlight.js', 'resources/js/pages/form-flatpickr.js', 'resources/js/pages/form-color-pickr.js'])
@vite(['resources/js/pages/extended-sweetalert.js'])
@vite(['resources/js/pages/highlight.js'])
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
<script src="{{ asset('js/custom-js/penjualan.js') }}" defer></script>
@endsection
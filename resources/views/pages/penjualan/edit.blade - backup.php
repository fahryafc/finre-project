@extends('layouts.vertical', ['title' => 'Edit Penjualan', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

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
                    <h4 class="card-title">Edit Data Penjualan | {{ $penjualan->tanggal }}</h4>
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
                <form id="" action="{{ route('penjualan.update', $penjualan->id_penjualan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                        <div class="grid grid-cols-3 gap-4">
                            <div class="mb-3">
                                <label for="tanggal" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Penjualan</label>
                                <input type="text" class="form-input tgl_edit" name="tanggal" id="datepicker-basic" value="{{ $penjualan->tanggal }}" data-tanggal="{{ $penjualan->tanggal }}">
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
                                    <option value="{{ $customer->id_kontak }}" {{ old('id_kontak', $penjualan->id_kontak) == $customer->id_kontak ? 'selected' : '' }}>{{ $customer->nama_kontak }}</option>
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
                                    <option value="{{ $produks->nama_produk }}" {{ old('nama_produk', $penjualan->produk) == $produks->nama_produk ? 'selected' : '' }}>{{ $produks->nama_produk }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="satuan" name="satuan" class="text-gray-800 text-sm font-medium inline-block mb-2">Satuan</label>
                                <select id="satuan" name="satuan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" selected>-- Pilih Satuan --</option>
                                    @foreach ( $satuan as $satuans)
                                    <option value="{{ $satuans->nama_satuan }}" {{ old('nama_satuan', $penjualan->satuan) == $satuans->nama_satuan ? 'selected' : '' }}>{{ $satuans->nama_satuan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="mb-3">
                                    <label for="harga" class="text-gray-800 text-sm font-medium inline-block mb-2">Harga</label>
                                    <input type="text" class="form-input" id="harga" name="harga" aria-describedby="harga" placeholder="Masukan Harga" value="{{ old('harga','Rp '.number_format($penjualan->harga, 0, '.', '.')) }}">
                                </div>
                                <div class="mb-3">
                                    <label for="kuantitas" class="text-gray-800 text-sm font-medium inline-block mb-2">Kuantitas</label>
                                    <input type="text" class="form-input" id="kuantitas" name="kuantitas" aria-describedby="kuantitas" placeholder="Masukan Kuantitas" value="{{ old('kuantitas', $penjualan->kuantitas) }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="pembayaran" class="text-gray-800 text-sm font-medium inline-block mb-2"> Kas & Bank </label>
                                <select id="pembayaran" name="pembayaran" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" selected>-- Pilih Akun --</option>
                                    @foreach ( $kas_bank as $akuns )
                                    <option value="{{ $akuns->kode_akun }}" {{ old('pembayaran', $penjualan->pembayaran) == $akuns->kode_akun ? 'selected' : '' }}>
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
                        <hr class="border-1 border-gray-300 my-1">

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-3">
                                <label for="jns_pajak" class="text-gray-800 text-sm font-medium inline-block mb-2">Jenis Pajak</label>
                                <select id="jns_pajak" name="jns_pajak" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="">-- Jenis Pajak --</option>
                                    <option value="ppn" {{ $penjualan->jns_pajak == 'ppn' ? 'selected' : '' }}>PPN</option>
                                    <option value="ppnbm" {{ $penjualan->jns_pajak == 'ppnbm' ? 'selected' : '' }}>PPnBM</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="persen_pajak" class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak (%)</label>
                                <div class="flex w-full">
                                    <input type="text" id="persen_pajak" name="persen_pajak" placeholder="Masukan Pajak (%)" class="form-input ltr:rounded-r-none rtl:rounded-l-none  w-1/3" value="{{$penjualan->pajak_persen}}" disabled>
                                    <input type="text" id="nominal_pajak" name="nominal_pajak" class="inline-flex items-center px-4 rounded-e border border-s-0 border-gray-200 bg-gray-300 text-gray-500 dark:bg-gray-700 dark:border-gray-700 dark:text-gray-400 pajak-output flex-1" value="{{ old('nominal_pajak','Rp '.number_format($penjualan->nominal_pajak, 0, '.', '.')) }}" disabled>
                                </div>
                            </div>
                        </div>
                        <hr class="border-1 border-gray-300 my-1">

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-3">
                                <label for="diskon" class="text-gray-800 text-sm font-medium inline-block mb-2">Diskon</label>
                                <div class="flex w-full">
                                    <input type="text" id="diskon" name="diskon" placeholder="Masukan Diskon" class="form-input ltr:rounded-r-none rtl:rounded-l-none w-1/3" value="{{ old('diskon', $penjualan->diskon) }}" />
                                    <div class="inline-flex items-center px-4 rounded-e border border-s-0 border-gray-200 bg-gray-300 text-gray-500 dark:bg-gray-700 dark:border-gray-700 dark:text-gray-400 diskon-output flex-1" id="diskon-output">
                                        Rp. 0
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="ongkir" class="text-gray-800 text-sm font-medium inline-block mb-2">Biaya Pengiriman</label>
                                <input type="text" class="form-input" id="ongkir" name="ongkir" aria-describedby="ongkir" value="{{ old('ongkir','Rp '.number_format($penjualan->ongkir, 0, '.', '.')) }}" placeholder="Masukan Ongkir">
                            </div>
                            <div class="mb-3">
                                <div class="flex items-center">
                                    <label for="piutangSwitch" class="text-gray-800 text-sm font-medium">Piutang</label>
                                    <label class="inline-flex items-center ml-2">
                                        <input type="checkbox" id="piutangSwitch" name="piutangSwitch" class="form-switch text-primary" onclick="togglePiutang({{$penjualan->id_penjualan}})" 
                                        {{ $penjualan->piutang == 1 ? 'checked' : '' }}>
                                    </label>
                                </div>

                                <!-- Input Field for Piutang (Hidden by Default) -->
                                <div id="piutangInputContainer" class="mt-2 {{ $penjualan->piutang == 1 ? '' : 'hidden' }}">
                                    <input type="text" class="form-input w-full" id="piutang" name="piutang" aria-describedby="piutang" placeholder="Masukan Piutang" 
                                    value="{{ old('piutang','Rp '.number_format($penjualan->nominal_piutang, 0, '.', '.')) }}">
                                </div>
                            </div>
                            <div class="mb-3 hidden" id="tglJatuhTempoContainer">
                                <label for="tgl_jatuh_tempo" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Jatuh Tempo</label>
                                <input type="text" class="form-input" name="tgl_jatuh_tempo" id="datepicker-basic">
                            </div>
                        </div>

                        <hr class="border-1 border-gray-300 my-1">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-3"></div>
                            <div class="mb-3">
                                <div class="flex w-full">
                                    <label class="text-gray-800 text-sm font-medium inline-blockpajak-output p-2 w-1/3">
                                        Total Pemasukan
                                    </label>
                                    <input type="text" id="total_pemasukan" name="total_pemasukan" class="form-input ltr:rounded-r-none rtl:rounded-l-none bg-[#307487] flex-1" style="color: white;" value="{{ old('total_pemasukan','Rp '.number_format($penjualan->total_pemasukan, 0, '.', '.')) }}" readonly/>
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
<!-- @vite('resources/js/pages/charts-apex.js') -->
@vite(['resources/js/pages/table-gridjs.js'])
@vite(['resources/js/pages/highlight.js'])
@vite(['resources/js/pages/highlight.js', 'resources/js/pages/form-flatpickr.js', 'resources/js/pages/form-color-pickr.js'])
@vite(['resources/js/pages/extended-sweetalert.js'])
@vite(['resources/js/pages/highlight.js'])
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
<script src="{{ asset('js/custom-js/penjualan.js') }}" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tanggalInput = document.querySelector('.tgl_edit');
        const defaultDate = tanggalInput.getAttribute('data-tanggal'); // Ambil nilai dari data-tanggal

        flatpickr(".tgl_edit", {
            dateFormat: "d-m-Y",
            defaultDate: defaultDate
        });
    });
</script>
@endsection
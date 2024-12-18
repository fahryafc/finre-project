@extends('layouts.vertical', ['title' => 'Edit Tambah Produk & Inventori', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('css')
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
                <h4 class="card-title">Edit Produk & Inventori</h4>
            </div>
        </div>
        <div class="p-6">
            <form id="" action="{{ route('produkdaninventori.store') }}" method="POST" enctype="multipart/form-data">
                @csrf            
                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3">
                        <label for="pemasok" class="text-gray-800 text-sm font-medium inline-block mb-2">Pemasok</label>
                        <select id="pemasok" name="pemasok" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="" selected>-- Pilih Pemasok --</option>
                            @foreach ( $pemasoks as $pemasok)
                            <option value="{{ $pemasok->id_kontak }}" 
                                {{ $produk->id_kontak == $pemasok->id_kontak ? 'selected' : '' }}>
                                {{ $pemasok->nama_kontak }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal</label>
                        <input type="text" class="form-input tgl_edit" name="tanggal" id="datepicker-basic" value="{{ old('tanggal', $produk->tanggal) }}" data-tanggal="{{ $produk->tanggal }}">
                    </div>
                </div>
                <hr class="border-2 border-gray-300 my-2">

                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3">
                        <label for="nama_produk" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Produk</label>
                        <input type="text" class="form-input" id="nama_produk" name="nama_produk" aria-describedby="nama_produk" placeholder="Masukan Nama Produk" value="{{ old('nama_produk', $produk->nama_produk) }}">
                    </div>
                    <div class="mb-3">
                        <label for="satuan" class="text-gray-800 text-sm font-medium inline-block mb-2">Satuan</label>
                        <select id="satuan" class="selectize" name="satuan">
                            <option value="" selected>-- Pilih Satuan --</option>
                            @foreach ($satuan as $key)
                            <option value="{{ $key->nama_satuan }}" 
                            {{ $produk->satuan == $key->nama_satuan ? 'selected' : '' }}>
                            {{ $key->nama_satuan }}
                        </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="text-gray-800 text-sm font-medium inline-block mb-2">Kategori</label>
                        <select id="kategori" class="selectize" name="kategori">
                            <option value="" selected>-- Pilih Kategori --</option>
                            @foreach ( $kategori as $key)
                            <option value="{{ $key->nama_kategori }}"
                                {{ $produk->kategori == $key->nama_kategori ? 'selected' : '' }}>
                                {{ $key->nama_kategori }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="kuantitas" class="text-gray-800 text-sm font-medium inline-block mb-2">Kuantitas</label>
                        <input type="number" class="form-input" id="kuantitas" name="kuantitas" placeholder="Masukan Kuantitas" value="{{ old('kuantitas', $produk->kuantitas) }}">
                    </div>
                    <div class="mb-3">
                        <label for="kode_sku" class="text-gray-800 text-sm font-medium inline-block mb-2">Kode/SKU</label>
                        <input type="text" class="form-input" id="kode_sku" name="kode_sku" aria-describedby="kode_sku" placeholder="Masukan Kode/SKU" value="{{ old('kode_sku', $produk->kode_sku) }}">
                    </div>
                </div>
                <hr class="border-2 border-gray-300 my-2">

                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3">
                        <label for="harga_beli" class="text-gray-800 text-sm font-medium inline-block mb-2">Harga Beli</label>
                        <input type="text" class="form-input" id="harga_beli" name="harga_beli" placeholder="Masukan Harga Beli" value="{{ 'Rp. '.number_format(old('harga_beli', $produk->harga_beli, 0, '.', '.')) }}">
                    </div>
                    <div class="mb-3">
                        <label for="harga_jual" class="text-gray-800 text-sm font-medium inline-block mb-2">Harga Jual</label>
                        <input type="text" class="form-input" id="harga_jual" name="harga_jual" placeholder="Masukan Harga Jual" value="{{ 'Rp. '.number_format(old('harga_jual', $produk->harga_jual, 0, '.', '.')) }}">
                    </div>
                </div>
                <hr class="border-2 border-gray-300 my-2">

                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3">
                        <label for="jns_pajak" class="text-gray-800 text-sm font-medium inline-block mb-2">Jenis Pajak</label>
                        <select id="jns_pajak" name="jns_pajak" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="" selected>-- Pilih Jenis Pajak --</option>
                            <option value="ppn" {{ $produk->jns_pajak == 'PPN' ? 'selected' : '' }}>PPN</option>
                            <option value="ppnbm" {{ $produk->jns_pajak == 'PPNBM' ? 'selected' : '' }}>PPnBM</option>
                        </select>
                    </div>
                    <div class="mb-3" id="pajakPersenContainer">
                        <label for="pajak_persen" class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak (%)</label>
                        <input type="text" class="form-input" id="pajak_persen" name="pajak_persen" aria-describedby="pajak_persen" placeholder="Masukan Pajak (%)" value="{{ old('pajak_persen', $produk->persen_pajak) }}">
                    </div>
                    <div class="mb-3">
                        <label for="nominal_pajak" class="text-gray-800 text-sm font-medium inline-block mb-2">Nominal Pajak (Rp)</label>
                        <input type="text" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" id="nominal_pajak" name="nominal_pajak" aria-describedby="nominal_pajak" placeholder="Nominal Pajak" value="{{ 'Rp. '.number_format(old('nominal_pajak', $produk->nominal_pajak, 0, '.', '.')) }}" readonly>
                    </div>
                </div>
                <hr class="border-2 border-gray-300 my-2">

                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3">
                        <label for="akun_pembayaran" class="text-gray-800 text-sm font-medium inline-block mb-2">Dibayarkan dari akun</label>
                        <select id="akun_pembayaran" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="akun_pembayaran">
                            @foreach ( $akun as $key)
                            <option value="{{ $key->nama_akun }}"
                                {{ $produk->akun_pembayaran == $key->nama_akun ? 'selected' : '' }}>
                                {{ $key->nama_akun }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="masuk_akun" class="text-gray-800 text-sm font-medium inline-block mb-2"> Masuk Akun </label>
                        <select id="masuk_akun" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="masuk_akun">
                            @foreach ( $akun as $key)
                            <option value="{{ $key->nama_akun }}"
                                {{ $key->nama_akun == $produk->masuk_akun ? 'selected' : '' }}>
                                {{ $key->nama_akun }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr class="border-2 border-gray-300 my-2">

                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3"></div>
                    <div class="mb-3"></div>
                    <div class="mb-3">
                        <div class="flex w-full">
                            <label for="total_transaksi" class="text-gray-800 text-sm font-medium inline-block p-2">Total Transaksi</label>
                            <input type="text" class="form-input ltr:rounded-r-none rtl:rounded-l-none bg-[#307487] flex-1" style="color: white;" id="total_transaksi" name="total_transaksi" value="{{ 'Rp. '.number_format(old('total_transaksi', $produk->total_transaksi, 0, '.', '.')) }}" readonly>
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
</div>
@endsection

@section('script')
@vite(['resources/js/pages/table-gridjs.js'])
@vite(['resources/js/pages/highlight.js', 'resources/js/pages/form-select.js'])
@vite(['resources/js/pages/highlight.js', 'resources/js/pages/form-flatpickr.js', 'resources/js/pages/form-color-pickr.js'])
@vite(['resources/js/pages/extended-sweetalert.js'])
@vite(['resources/js/pages/highlight.js'])
<script src="{{ asset('js/custom-js/produks.js') }}" defer></script>
@endsection
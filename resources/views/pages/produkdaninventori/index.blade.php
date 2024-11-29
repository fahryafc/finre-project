@extends('layouts.vertical', ['title' => 'Produk & Inventori', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

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
<div class="grid grid-rows-1 grid-flow-col gap-4 mb-5">
    <div class="card">
        <div class="p-5">
            <div class="flex justify-between">
                <div class="w-20 h-20 rounded-full inline-flex items-center justify-center bg-green-100">
                    <i class="ti ti-package text-4xl text-green-500"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-gray-700 mt-1 text-2xl font-bold mb-5 dark:text-gray-300">{{$produkTersedia}}</h3>
                    <p class="text-gray-500 mb-1 truncate dark:text-gray-400">Tersedia</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="p-5">
            <div class="flex justify-between">
                <div class="w-20 h-20 rounded-full inline-flex items-center justify-center bg-yellow-100">
                    <i class="ti ti-package text-4xl text-yellow-500"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-gray-700 mt-1 text-2xl font-bold mb-5 dark:text-gray-300">{{$produkHampirHabis}}</h3>
                    <p class="text-gray-500 mb-1 truncate dark:text-gray-400">Hampir Habis</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="p-5">
            <div class="flex justify-between">
                <div class="w-20 h-20 rounded-full inline-flex items-center justify-center bg-red-100">
                    <i class="ti ti-package text-4xl text-red-500"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-gray-700 mt-1 text-2xl font-bold mb-5 dark:text-gray-300">{{$produkHabis}}</h3>
                    <p class="text-gray-500 mb-1 truncate dark:text-gray-400">Habis</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="p-5">
            <div class="flex justify-between">
                <div class="w-20 h-20 rounded-full inline-flex items-center justify-center bg-primary/25 ">
                    <i class="ti ti-package text-4xl text-primary"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-gray-700 mt-1 text-2xl font-bold mb-5 dark:text-gray-300">{{$totalProduk}}</h3>
                    <p class="text-gray-500 mb-1 truncate dark:text-gray-400">Total Produk</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid lg grid-cols-1 gap-6 mt-5">
    <div class="card">
        <div class="card-header">
            <div class="flex justify-between items-center">
                <h4 class="card-title">Data Produk & Inventori</h4>
                <div class="flex space-x-2">
                    <button class="btn bg-secondary text-white" data-fc-target="tambahKategori" data-fc-type="modal" type="button">
                        <i class="mgc_add_fill text-base me-1"></i> Tambah Kategori
                    </button>
                    <button class="btn bg-primary text-white" data-fc-target="tambahProduk" data-fc-type="modal" type="button">
                        <i class="mgc_add_fill text-base me-1"></i> Tambah Produk
                    </button>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <div class="min-w-full inline-block align-middle">
                    <div class="border rounded-lg divide-y divide-gray-200 dark:border-gray-700 dark:divide-gray-700">
                        <div class="py-3 px-4">
                            <div class="relative max-w-xs">
                                <label for="table-with-pagination-search" class="sr-only">Search</label>
                                <input type="text" name="table-with-pagination-search" id="table-with-pagination-search"
                                    class="form-input ps-11" placeholder="Search for items">
                                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                    <svg class="h-3.5 w-3.5 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" viewBox="0 0 16 16">
                                        <path
                                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z">
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama
                                            Produk</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Kode/SKU</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Kategori</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Satuan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Akun
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Harga Beli</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Harga Jual</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Kuantitas</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Nilai Produk</th>
                                        <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @php $counter = 1; @endphp
                                    @foreach($produk as $prd)
                                    <tr>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                            {{ $counter + ($produk->currentPage() - 1) * $produk->perPage() }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                            {{ $prd->nama_produk }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                            {{ $prd->kode_sku }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                            {{ $prd->kategori }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                            {{ $prd->satuan }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                            {{ $prd->akun_pembayaran }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                            {{ "Rp. ".number_format($prd->harga_beli, 0, ".", ".") }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                            {{ "Rp. ".number_format($prd->harga_jual, 0, ".", ".") }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                            {{ $prd->kuantitas }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                            {{ "Rp. ".number_format($prd->harga_jual * $prd->kuantitas, 0, ".", ".") }}
                                        </td>
                                        @csrf
                                        @method('DELETE')
                                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                            <a href="{{ route('produkdaninventori.destroy', $prd->id_produk) }}" data-confirm-delete="true" class="btn rounded-full bg-danger/25 text-danger hover:bg-danger hover:text-white"><i class="mgc_delete_2_line"></i></a>
                                            <button type="button" class="btn rounded-full bg-warning/25 text-warning hover:bg-warning hover:text-white" data-fc-target="modalEditProduk{{$prd->id_produk}}" data-id-produk="{{$prd->id_produk}}" onclick="openEditProduk(this)" data-fc-type="modal"><i class="mgc_edit_2_line"></i></button>
                                        </td>
                                    </tr>
                                    @php $counter++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="py-1 px-4">
                            <nav class="flex items-center space-x-2">
                                {{ $produk->links('pagination::tailwind') }}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Produk -->
<div id="tambahProduk" class="w-full h-auto max-h-[70vh] mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
    <div class="max-w-[60rem] fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                Tambah Data Produk
            </h3>
            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <form id="" action="{{ route('produkdaninventori.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="px-4 py-4 overflow-y-auto h-auto max-h-[60vh]">
                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3">
                        <label for="pemasok" class="text-gray-800 text-sm font-medium inline-block mb-2">Pemasok</label>
                        <select id="pemasok" name="pemasok" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="" selected>-- Pilih Pemasok --</option>
                            @foreach ( $pemasoks as $pemasok)
                            <option value="{{ $pemasok->id_kontak }}">{{ $pemasok->nama_kontak }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal</label>
                        <input type="text" class="form-input" name="tanggal" id="datepicker-basic">
                    </div>
                </div>
                <hr class="border-2 border-gray-300 my-2">

                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3">
                        <label for="nama_produk" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Produk</label>
                        <input type="text" class="form-input" id="nama_produk" name="nama_produk" aria-describedby="nama_produk" placeholder="Masukan Nama Produk">
                    </div>
                    <div class="mb-3">
                        <label for="satuan" class="text-gray-800 text-sm font-medium inline-block mb-2">Satuan</label>
                        <select id="satuan" class="selectize" name="satuan">
                            @foreach ($satuan as $key)
                            <option value="{{ $key->nama_satuan }}">{{ $key->nama_satuan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="text-gray-800 text-sm font-medium inline-block mb-2">Kategori</label>
                        <select id="kategori" class="selectize" name="kategori">
                            @foreach ( $kategori as $key)
                            <option value="{{ $key->nama_kategori }}">{{ $key->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="kuantitas" class="text-gray-800 text-sm font-medium inline-block mb-2">Kuantitas</label>
                        <input type="number" class="form-input" id="kuantitas" name="kuantitas" placeholder="Masukan Kuantitas" oninput="hitungTotal()">
                    </div>
                    <div class="mb-3">
                        <label for="kode_sku" class="text-gray-800 text-sm font-medium inline-block mb-2">Kode/SKU</label>
                        <input type="text" class="form-input" id="kode_sku" name="kode_sku" aria-describedby="kode_sku" placeholder="Masukan Kode/SKU">
                    </div>
                </div>
                <hr class="border-2 border-gray-300 my-2">

                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3">
                        <label for="harga_beli" class="text-gray-800 text-sm font-medium inline-block mb-2">Harga Beli</label>
                        <input type="text" class="form-input" id="harga_beli_input" name="harga_beli" placeholder="Masukan Harga Beli" oninput="hitungTotal()">
                    </div>
                    <div class="mb-3">
                        <label for="harga_jual" class="text-gray-800 text-sm font-medium inline-block mb-2">Harga Jual</label>
                        <input type="text" class="form-input" id="harga_jual_input" name="harga_jual" placeholder="Masukan Harga Jual">
                    </div>
                </div>
                <hr class="border-2 border-gray-300 my-2">

                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3">
                        <label for="jns_pajak" class="text-gray-800 text-sm font-medium inline-block mb-2">Jenis Pajak</label>
                        <input type="text" class="inline-flex items-center px-4 rounded-e border border-s-0 border-gray-200 bg-gray-300 text-gray-500 dark:bg-gray-700 dark:border-gray-700 dark:text-gray-400" id="jns_pajak" name="jns_pajak" disabled="" value="PPN">
                    </div>
                    <div class="mb-3">
                        <label for="persen_pajak" class="text-gray-800 text-sm font-medium inline-block mb-2">Persen Pajak (%)</label>
                        <input type="text" class="inline-flex items-center px-4 rounded-e border border-s-0 border-gray-200 bg-gray-300 text-gray-500 dark:bg-gray-700 dark:border-gray-700 dark:text-gray-400" id="persen_pajak" name="persen_pajak" disabled="" value="11 %">
                    </div>
                    <div class="mb-3">
                        <label for="nominal_pajak" class="text-gray-800 text-sm font-medium inline-block mb-2">Nominal Pajak</label>
                        <input type="text" class="form-input bg-gray-300 text-gray-500" id="nominal_pajak" name="nominal_pajak" readonly>
                    </div>
                </div>
                <hr class="border-2 border-gray-300 my-2">

                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3">
                        <label for="akun_pembayaran" class="text-gray-800 text-sm font-medium inline-block mb-2">Dibayarkan dari akun</label>
                        <select id="akun_pembayaran" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="akun_pembayaran">
                            @foreach ( $akun as $key)
                            <option value="{{ $key->nama_akun }}">{{ $key->nama_akun }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="masuk_akun" class="text-gray-800 text-sm font-medium inline-block mb-2"> Masuk Akun </label>
                        <select id="masuk_akun" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="masuk_akun">
                            @foreach ( $akun as $key)
                            <option value="{{ $key->nama_akun }}">{{ $key->nama_akun }}</option>
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
                            <input type="text" class="form-input ltr:rounded-r-none rtl:rounded-l-none bg-[#307487] flex-1" style="color: white;" id="total_transaksi" name="total_transaksi" readonly>
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

<!-- Modal Edit Produk -->
@foreach($produk as $prd)
<div id="modalEditProduk{{$prd->id_produk}}" class="w-full h-auto max-h-[70vh] mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
    <div class="max-w-[60rem] fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                Edit Data Produk
            </h3>
            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <form id="" action="{{ route('produkdaninventori.update', $prd->id_produk) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="px-4 py-4 overflow-y-auto h-auto max-h-[60vh]">
                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3">
                        <label for="pemasok" class="text-gray-800 text-sm font-medium inline-block mb-2">Pemasok</label>
                        <select id="pemasok" name="pemasok" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="" selected>-- Pilih Pemasok --</option>
                            @foreach ( $pemasoks as $vendor)
                            <option value="{{ $vendor->id_kontak }}" {{ old('id_kontak', $prd->id_kontak) == $vendor->id_kontak ? 'selected' : '' }}>{{ $vendor->nama_kontak }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal</label>
                        <input type="text" class="form-input" name="tanggal" id="datepicker-basic">
                    </div>
                </div>
                <hr class="border-2 border-gray-300 my-2">

                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3">
                        <label for="nama_produk" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Produk</label>
                        <input type="text" class="form-input" id="nama_produk" name="nama_produk" aria-describedby="nama_produk" placeholder="Masukan Nama Produk" value="{{ old('nama_produk', $prd->nama_produk) }}">
                    </div>
                    <div class="mb-3">
                        <label for="satuan" class="text-gray-800 text-sm font-medium inline-block mb-2">Satuan</label>
                        <select id="satuan" class="selectize" name="satuan">
                            <option value="" selected>-- Pilih Satuan --</option>
                            @foreach ($satuan as $key)
                            <option value="{{ $key->nama_satuan }}" {{ old('nama_satuan', $prd->satuan) == $key->nama_satuan ? 'selected' : '' }}>{{ $key->nama_satuan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="text-gray-800 text-sm font-medium inline-block mb-2">Kategori</label>
                        <select id="kategori" class="selectize" name="kategori">
                            <option value="" selected>-- Pilih Kategori --</option>
                            @foreach ( $kategori as $key)
                            <option value="{{ $key->nama_kategori }}" {{ old('nama_kategori', $prd->kategori) == $key->nama_kategori ? 'selected' : '' }}>{{ $key->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="kuantitas" class="text-gray-800 text-sm font-medium inline-block mb-2">Kuantitas</label>
                        <input type="number" class="form-input" id="kuantitasEdit{{$prd->id_produk}}" name="kuantitas" placeholder="Masukan Kuantitas" value="{{ old('kuantitas', $prd->kuantitas) }}">
                    </div>
                    <div class="mb-3">
                        <label for="kode_sku" class="text-gray-800 text-sm font-medium inline-block mb-2">Kode/SKU</label>
                        <input type="text" class="form-input" id="kode_sku" name="kode_sku" aria-describedby="kode_sku" placeholder="Masukan Kode/SKU" value="{{ old('kode_sku', $prd->kode_sku) }}">
                    </div>
                </div>
                <hr class="border-2 border-gray-300 my-2">

                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3">
                        <label for="harga_beli" class="text-gray-800 text-sm font-medium inline-block mb-2">Harga Beli</label>
                        <input type="text" class="form-input" id="harga_beli_edit{{$prd->id_produk}}" name="harga_beli" placeholder="Masukan Harga Beli" value="{{ old('harga_beli','Rp '.number_format($prd->harga_beli, 0, '.', '.')) }}">
                    </div>
                    <div class="mb-3">
                        <label for="harga_jual" class="text-gray-800 text-sm font-medium inline-block mb-2">Harga Jual</label>
                        <input type="text" class="form-input" id="harga_jual_edit{{ $prd->id_produk }}" name="harga_jual" placeholder="Masukan Harga Jual" value="{{ old('harga_jual','Rp '.number_format($prd->harga_jual, 0, '.', '.')) }}">
                    </div>
                </div>
                <hr class="border-2 border-gray-300 my-2">

                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3">
                        <label for="jns_pajak" class="text-gray-800 text-sm font-medium inline-block mb-2">Jenis Pajak</label>
                        <input type="text" class="inline-flex items-center px-4 rounded-e border border-s-0 border-gray-200 bg-gray-300 text-gray-500 dark:bg-gray-700 dark:border-gray-700 dark:text-gray-400" id="jns_pajak" name="jns_pajak" disabled="" value="PPN">
                    </div>
                    <div class="mb-3">
                        <label for="persen_pajak" class="text-gray-800 text-sm font-medium inline-block mb-2">Persen Pajak (%)</label>
                        <input type="text" class="inline-flex items-center px-4 rounded-e border border-s-0 border-gray-200 bg-gray-300 text-gray-500 dark:bg-gray-700 dark:border-gray-700 dark:text-gray-400" id="persen_pajak" name="persen_pajak" disabled="" value="11%">
                    </div>
                    <div class="mb-3">
                        <label for="nominal_pajak" class="text-gray-800 text-sm font-medium inline-block mb-2">Nominal Pajak</label>
                        <input type="text" class="form-input bg-gray-300 text-gray-500" id="nominal_pajak_edit{{ $prd->id_produk }}" name="nominal_pajak" value="{{ old('nominal_pajak','Rp '.number_format($prd->nominal_pajak, 0, '.', '.')) }}" readonly>
                    </div>
                </div>
                <hr class="border-2 border-gray-300 my-2">

                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3">
                        <label for="akun_pembayaran" class="text-gray-800 text-sm font-medium inline-block mb-2">Dibayarkan dari akun</label>
                        <select id="akun_pembayaran" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="akun_pembayaran">
                            <option value="" selected>-- Pilih Akun --</option>
                            @foreach ( $akun as $key)
                            <option value="{{ $key->nama_akun }}">{{ $key->nama_akun }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="masuk_akun" class="text-gray-800 text-sm font-medium inline-block mb-2"> Masuk Akun </label>
                        <select id="masuk_akun" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="masuk_akun">
                            <option value="" selected>-- Pilih Akun --</option>
                            @foreach ( $akun as $key)
                            <option value="{{ $key->nama_akun }}">{{ $key->nama_akun }}</option>
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
                            <input type="text" class="form-input ltr:rounded-r-none rtl:rounded-l-none bg-[#307487] flex-1" style="color: white;" id="total_transaksi_edit{{ $prd->id_produk }}" name="total_transaksi" value="{{ old('total_transaksi','Rp '.number_format($prd->harga_beli * $prd->kuantitas, 0, '.', '.')) }}" readonly>
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
@endforeach
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
                <button class="btn bg-primary text-white" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>
<!-- end modal -->
@endsection

@section('script')
@vite(['resources/js/pages/table-gridjs.js'])
@vite(['resources/js/pages/highlight.js', 'resources/js/pages/form-select.js'])
@vite(['resources/js/pages/highlight.js', 'resources/js/pages/form-flatpickr.js', 'resources/js/pages/form-color-pickr.js'])
@vite(['resources/js/pages/extended-sweetalert.js'])
@vite(['resources/js/pages/highlight.js'])
<script src="{{ asset('js/custom-js/produk.js') }}" defer></script>
@endsection
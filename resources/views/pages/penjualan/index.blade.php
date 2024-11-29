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
<div class="grid grid-rows-1 grid-flow-col gap-4">
    <div class="row-span-3">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Kategori Produk</h4>
            </div>
            <div class="p-6">
                <div id="pie_chart" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>
    <div class="row-span-2">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Overview Penjualan</h4>
            </div>
            <div class="p-6">

                <div id="spline_area" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>
</div>

<div class="grid lg grid-cols-1 gap-6">
    <div class="card mt-10 p-5">
        <div class="card-header mb-5">
            <div class="flex justify-between items-center">
                <h4 class="card-title">Data Penjualan</h4>
                <button class="btn bg-[#307487] text-white" data-fc-target="modalTambahAkun" data-fc-type="modal" type="button"><i class="mgc_add_fill text-base me-4"></i>
                    Tambah Data
                </button>
            </div>
        </div>
        <div class="card-body">
            <table id="penjualan-table">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Penjualan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Kuantitas</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Harga</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Total Harga</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Diskon</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Pajak (%)</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Piutang</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Total Pemasukan</th>
                        <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter = 1; @endphp
                    @foreach($penjualan as $p)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $counter + ($penjualan->currentPage() - 1) * $penjualan->perPage() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{$p->produk}}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{$p->kuantitas}}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ "Rp. ".number_format($p->harga, 0, ".", ".") }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{"Rp. ".number_format($p->harga * $p->kuantitas, 0, ".", ".")}}</td>

                        @if (!empty($p->diskon))
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{$p->diskon}}%</td>
                        @else
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">0</td>
                        @endif

                        @if (!empty($p->pajak))
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{$p->pajak}}%</td>
                        @else
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">0</td>
                        @endif

                        @if (!empty($p->piutang))
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{"Rp. ".number_format($p->nominal_piutang, 0, ".", ".")}}</td>
                        @else
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">0</td>
                        @endif

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{"Rp. ".number_format($p->total_pemasukan, 0, ".", ".") }}</td>
                        @csrf
                        @method('DELETE')
                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                            <a href="{{ route('penjualan.destroy', $p->id_penjualan) }}" data-confirm-delete="true" class="btn rounded-full bg-danger/25 text-danger hover:bg-danger hover:text-white"><i class="mgc_delete_2_line"></i></a>
                            <button type="button" class="btn rounded-full bg-warning/25 text-warning hover:bg-warning hover:text-white" data-fc-target="modalEditPenjualan{{$p->id_penjualan}}" data-id-penjualan="{{$p->id_penjualan}}" onclick="openEditPenjualan(this)" data-fc-type="modal"><i class="mgc_edit_2_line"></i></button>
                        </td>
                    </tr>
                    @php $counter++; @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Add -->
<div id="modalTambahAkun" class="w-full h-auto max-h-[70vh] mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
    <div class="max-w-[60rem] fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                Tambah Data Penjualan
            </h3>
            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <form id="" action="{{ route('penjualan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="px-4 py-4 overflow-y-auto h-auto max-h-[60vh]">
                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3">
                        <label for="tanggal" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Penjualan</label>
                        <input type="text" class="form-input" name="tanggal" id="datepicker-basic">
                    </div>
                </div>
                <hr class="border-2 border-gray-300 my-2">
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
                <hr class="border-2 border-gray-300 my-2">
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
                <hr class="border-2 border-gray-300 my-2">
                <!-- col 3 -->
                <div class="grid grid-cols-3 gap-4">
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
                        <label for="pajak" class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak</label>
                        <div class="flex w-full">
                            <input type="text" id="pajak" name="pajak" placeholder="Masukan Pajak" class="form-input ltr:rounded-r-none rtl:rounded-l-none  w-1/3" />
                            <input type="text" class="inline-flex items-center px-4 rounded-e border border-s-0 border-gray-200 bg-gray-300 text-gray-500 dark:bg-gray-700 dark:border-gray-700 dark:text-gray-400 pajak-output flex-1" value="" disabled>
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
                <hr class="border-2 border-gray-300 my-2">
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

<!-- modal edit -->
@foreach ($penjualan as $pnj)
<div id="modalEditPenjualan{{$pnj->id_penjualan}}" class="w-full h-auto max-h-[70vh] mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
    <div class="max-w-[60rem] fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                Tambah Data Penjualan
            </h3>
            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <form id="" action="{{ route('penjualan.update', $pnj->id_penjualan) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="px-4 py-4 overflow-y-auto h-auto max-h-[60vh]">
                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3">
                        <label for="tanggal" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Penjualan</label>
                        <input type="text" class="form-input" name="tanggal" id="datepicker-basic" value="{{ old('tanggal', $pnj->tanggal) }}">
                    </div>
                </div>
                <hr class="border-2 border-gray-300 my-2">
                <div class="grid grid-cols-2 gap-4">
                    <!-- col 1 -->
                    <div class="mb-3">
                        <label for="id_kontak" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Pelanggan</label>
                        <select id="id_kontak" name="id_kontak" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="" selected>-- Pilih Pelanggan --</option>
                            @foreach ( $pelanggan as $customer)
                            <option value="{{ $customer->id_kontak }}" {{ old('id_kontak', $pnj->id_kontak) == $customer->id_kontak ? 'selected' : '' }}>{{ $customer->nama_kontak }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr class="border-2 border-gray-300 my-2">
                <!-- col 2 -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-3">
                        <label for="produk" name="produk" class="text-gray-800 text-sm font-medium inline-block mb-2">Produk</label>
                        <select id="produk" name="produk" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="" selected>-- Pilih Produk --</option>
                            @foreach ( $produk as $produks)
                            <option value="{{ $produks->nama_produk }}" {{ old('nama_produk', $pnj->produk) == $produks->nama_produk ? 'selected' : '' }}>{{ $produks->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="satuan" name="satuan" class="text-gray-800 text-sm font-medium inline-block mb-2">Satuan</label>
                        <select id="satuan" name="satuan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="" selected>-- Pilih Satuan --</option>
                            @foreach ( $satuan as $satuans)
                            <option value="{{ $satuans->nama_satuan }}" {{ old('nama_satuan', $pnj->satuan) == $satuans->nama_satuan ? 'selected' : '' }}>{{ $satuans->nama_satuan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="mb-3">
                            <label for="harga" class="text-gray-800 text-sm font-medium inline-block mb-2">Harga</label>
                            <input type="text" class="form-input" id="hargaEdit{{$pnj->id_penjualan}}" name="harga" aria-describedby="harga" placeholder="Masukan Harga" value="{{ old('harga','Rp '.number_format($pnj->harga, 0, '.', '.')) }}">
                        </div>
                        <div class="mb-3">
                            <label for="kuantitas" class="text-gray-800 text-sm font-medium inline-block mb-2">Kuantitas</label>
                            <input type="text" class="form-input" id="kuantitasEdit{{$pnj->id_penjualan}}" name="kuantitas" aria-describedby="kuantitas" placeholder="Masukan Kuantitas" value="{{ old('kuantitas', $pnj->kuantitas) }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="pembayaran" class="text-gray-800 text-sm font-medium inline-block mb-2"> Kas & Bank </label>
                        <select id="pembayaran" name="pembayaran" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="" selected>-- Pilih Akun --</option>
                            @foreach ( $kas_bank as $akuns )
                            <option value="{{ $akuns->kode_akun }}" {{ old('pembayaran', $pnj->pembayaran) == $akuns->kode_akun ? 'selected' : '' }}>
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
                <hr class="border-2 border-gray-300 my-2">
                <!-- col 3 -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3">
                        <label for="diskon" class="text-gray-800 text-sm font-medium inline-block mb-2">Diskon</label>
                        <div class="flex w-full">
                            <input type="text" id="diskonEdit{{$pnj->id_penjualan}}" name="diskon" placeholder="Masukan Diskon" class="form-input ltr:rounded-r-none rtl:rounded-l-none w-1/3" value="{{ old('diskon', $pnj->diskon) }}" />
                            <div class="inline-flex items-center px-4 rounded-e border border-s-0 border-gray-200 bg-gray-300 text-gray-500 dark:bg-gray-700 dark:border-gray-700 dark:text-gray-400 diskon-output-edit flex-1" id="diskon-output-edit{{$pnj->id_penjualan}}">
                                Rp. 0
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="pajak" class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak</label>
                        <div class="flex w-full">
                            <input type="text" id="pajakEdit{{$pnj->id_penjualan}}" name="pajak" placeholder="Masukan Pajak" class="form-input ltr:rounded-r-none rtl:rounded-l-none  w-1/3" value="{{ old('pajak', $pnj->pajak) }}" />
                            <div class="inline-flex items-center px-4 rounded-e border border-s-0 border-gray-200 bg-gray-300 text-gray-500 dark:bg-gray-700 dark:border-gray-700 dark:text-gray-400 pajak-output-edit flex-1" id="pajak-output-edit{{$pnj->id_penjualan}}">
                                Rp. 0
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="ongkir" class="text-gray-800 text-sm font-medium inline-block mb-2">Biaya Pengiriman</label>
                        <input type="text" class="form-input" id="ongkirEdit{{$pnj->id_penjualan}}" name="ongkir" aria-describedby="ongkir" placeholder="Masukan Ongkir">
                    </div>
                    <div class="mb-3">
                        <div class="flex items-center">
                            <label for="piutangSwitch" class="text-gray-800 text-sm font-medium">Piutang</label>
                            <label class="inline-flex items-center ml-2">
                                <input type="checkbox" id="piutangSwitch{{$pnj->id_penjualan}}" name="piutangSwitch" class="form-switch text-primary" onclick="togglePiutangEdit({{$pnj->id_penjualan}})" 
                                {{ $pnj->piutang == 1 ? 'checked' : '' }}>
                            </label>
                        </div>

                        <!-- Input Field for Piutang (Hidden by Default) -->
                        <div id="piutangInputContainer{{$pnj->id_penjualan}}" class="mt-2 {{ $pnj->piutang == 1 ? '' : 'hidden' }}">
                            <input type="text" class="form-input w-full" id="piutangEdit{{$pnj->id_penjualan}}" name="piutang" aria-describedby="piutang" placeholder="Masukan Piutang" 
                            value="{{ old('piutang','Rp '.number_format($pnj->nominal_piutang, 0, '.', '.')) }}">
                        </div>
                    </div>
                    <div class="mb-3 hidden" id="tglJatuhTempoContainer">
                        <label for="tgl_jatuh_tempo" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Jatuh Tempo</label>
                        <input type="text" class="form-input" name="tgl_jatuh_tempo" id="datepicker-basic">
                    </div>
                </div>

                <hr class="border-2 border-gray-300 my-2">
                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-3"></div>
                    <div class="mb-3">
                        <div class="flex w-full">
                            <label class="text-gray-800 text-sm font-medium inline-blockpajak-output p-2 w-1/3">
                                Total Pemasukan
                            </label>
                            <input type="text" id="total_pemasukan_edit{{$pnj->id_penjualan}}" name="total_pemasukan" class="form-input ltr:rounded-r-none rtl:rounded-l-none bg-[#307487] flex-1" style="color: white;" value="{{ old('total_pemasukan', $pnj->total_pemasukan) }}" readonly/>
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
@extends('layouts.vertical', ['title' => 'Pengeluaran', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

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
<style>
    .hidden {
        display: none;
    }
</style>
@endsection

@section('content')
<div class="grid grid-rows-1 grid-flow-col gap-4">
    <div class="row-span-3">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Kategori Pembayaran</h4>
            </div>
            <div class="p-6">
                <div id="pie_chart" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>
    <div class="row-span-2">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Overview Pengeluaran</h4>
            </div>
            <div class="p-6">

                <div id="spline_area" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>
</div>

<div class="grid lg grid-cols-1 gap-6 mt-5">
    <div class="card">
        <div class="card-header">
            <div class="flex justify-between items-center">
                <h4 class="card-title">Data Pengeluaran</h4>
                <button class="btn bg-[#307487] text-white" data-fc-target="modalTambahPengeluaran" data-fc-type="modal" type="button"><i class="mgc_add_fill text-base me-4"></i>
                    Tambah Data
                </button>
            </div>
        </div>
        <div class="p-6">
            <!-- <div id="tabel-penjualan"></div> -->
            <div class="overflow-x-auto">
                <div class="min-w-full inline-block align-middle">
                    <div class="border rounded-lg divide-y divide-gray-200 dark:border-gray-700 dark:divide-gray-700">
                        <div class="py-3 px-4">
                            <div class="relative max-w-xs">
                                <label for="table-with-pagination-search" class="sr-only">Search</label>
                                <input type="text" name="table-with-pagination-search" id="table-with-pagination-search" class="form-input ps-11" placeholder="Search for items">
                                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                    <svg class="h-3.5 w-3.5 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z">
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">No</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Nama Pengeluaran</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Nama Vendor / Karyawan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Kategori</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Total Pembayaran</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Akun</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Pajak</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Total Pajak</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Hutang</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @php $counter = 1; @endphp
                                    @foreach($pengeluaran as $p)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $counter + ($pengeluaran->currentPage() - 1) * $pengeluaran->perPage() }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $p->nm_pengeluaran }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> {{ $p->nama_kontak }} </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{$p->kategori}}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ "Rp. ".number_format($p->biaya, 0, ".", ".") }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{$p->akun_pembayaran}}</td>
                                        @if ($p->pajak == 1)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{$p->jns_pajak}}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ "Rp. ".number_format($p->pajak_dibayarkan, 0, ".", ".") }}</td>
                                        @else
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> - </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> - </td>
                                        @endif
                                        @if ($p->hutang != 0)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ "Rp. ".number_format($p->nominal_hutang, 0, ".", ".") }}</td>
                                        @else
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">0</td>
                                        @endif
                                        @csrf
                                        @method('DELETE')
                                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                            <a href="{{ route('pengeluaran.destroy', $p->id_pengeluaran) }}" data-confirm-delete="true" class="btn rounded-full bg-danger/25 text-danger hover:bg-danger hover:text-white"><i class="mgc_delete_2_line"></i></a>
                                            <button type="button" class="btn rounded-full bg-warning/25 text-warning hover:bg-warning hover:text-white" data-fc-target="modalEditPengeluaran{{$p->id_pengeluaran}}" data-fc-type="modal"><i class="mgc_edit_2_line"></i></button>
                                        </td>
                                    </tr>
                                    @php $counter++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="py-1 px-4">
                            <nav class="flex items-center space-x-2">
                                {{ $pengeluaran->links('pagination::tailwind') }}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add -->
<div id="modalTambahPengeluaran" class="w-full h-auto max-h-[70vh] mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
    <div class="max-w-[60rem] fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                Tambah Data Pengeluaran
            </h3>
            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <form id="" action="{{ route('pengeluaran.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="px-4 py-4 overflow-y-auto h-auto max-h-[60vh]">
                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3">
                        <label for="tanggal" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal</label>
                        <input type="text" class="form-input" name="tanggal" id="datepicker-basic">
                    </div>
                </div>
                <hr class="border-2 border-gray-300 my-2">
                <div class="grid grid-cols-2 gap-4">
                    <!-- col 1 -->
                    <div class="mb-3">
                        <label for="nm_pengeluaran" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Pengeluaran</label>
                        <input type="text" class="form-input" id="nm_pengeluaran" name="nm_pengeluaran" aria-describedby="nm_pengeluaran" placeholder="Masukan Nama Pengeluaran">
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="text-gray-800 text-sm font-medium inline-block mb-2">Kategori</label>
                        <select id="kategori" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="kategori">
                            @foreach ( $kategori as $k)
                            <option value="{{ $k->nama_kategori }}">{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jenis_pengeluaran" class="text-gray-800 text-sm font-medium inline-block mb-2"> Jenis Pengeluaran </label>
                        <select id="jenis_pengeluaran" name="jenis_pengeluaran" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" onchange="togglePengeluaran()">
                            <option value="" selected>-- Pilih Jenis Pengeluaran --</option>
                            <option value="gaji_karyawan">Gaji Karyawan</option>
                            <option value="pembayaran_vendor">Pembayaran Vendor</option>
                        </select>
                    </div>
                    <div class="mb-3 hidden" id="div_nama_karyawan">
                        <label for="nama_karyawan" class="text-gray-800 text-sm font-medium inline-block mb-2"> Nama Karyawan </label>
                        <select id="nama_karyawan" name="id_kontak" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="" selected>-- Pilih Nama Karyawan --</option>
                            @foreach ( $karyawanKontak as $karyawan)
                            <option value="{{ $karyawan->id_kontak }}">{{ $karyawan->nama_kontak }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 hidden" id="div_nama_vendor">
                        <label for="nama_vendor" class="text-gray-800 text-sm font-medium inline-block mb-2"> Nama Vendor </label>
                        <select id="nama_vendor" name="id_kontak" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="" selected>-- Pilih Nama Vendor --</option>
                            @foreach ( $vendorKontak as $vendors)
                            <option value="{{ $vendors->id_kontak }}">{{ $vendors->nama_kontak }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr class="border-2 border-gray-300 my-2">
                <!-- col 2 -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3">
                        <label for="biaya" class="text-gray-800 text-sm font-medium inline-block mb-2">Biaya</label>
                        <input type="text" class="form-input" id="biaya" name="biaya" aria-describedby="biaya" placeholder="Masukan Biaya" oninput="formatRupiah(this)">
                    </div>
                    <div class="mb-3">
                        <label for="akun_pembayaran" class="text-gray-800 text-sm font-medium inline-block mb-2">Dibayarkan dari akun</label>
                        <select id="akun_pembayaran" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="akun_pembayaran">
                            @foreach ( $kas_bank as $a )
                            <option value="{{ $a->kode_akun }}">
                                <span class="flex justify-between w-full">
                                    <span>{{ $a->nama_akun }}</span>
                                    <span> - </span>
                                    <span>{{ $a->kode_akun }}</span>
                                </span>
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="akun_pemasukan" class="text-gray-800 text-sm font-medium inline-block mb-2">Pengeluaran masuk akun</label>
                        <select id="akun_pemasukan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="akun_pemasukan">
                            @foreach ( $kas_bank as $a )
                            <option value="{{ $a->kode_akun }}">
                                <span class="flex justify-between w-full">
                                    <span>{{ $a->nama_akun }}</span>
                                    <span> - </span>
                                    <span>{{ $a->kode_akun }}</span>
                                </span>
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr class="border-2 border-gray-300 my-2">
                <!-- col 3 -->
                <div class="grid grid-cols-6">
                    <div class="mb-3">
                        <div class="flex items-center">
                            <input type="checkbox" id="pajakButton" name="pajakButton" class="form-switch text-primary" value="1" onchange="toggleCollapsePajak()">
                            <label for="pajakButton" class="ms-1.5">Pajak</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="flex items-center">
                            <input type="checkbox" id="hutangButton" name="hutangButton" class="form-switch text-primary" value="1" onchange="toggleCollapseHutang()">
                            <label for="hutangButton" class="ms-1.5">Hutang</label>
                        </div>
                    </div>
                </div>

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
                            <select id="jns_pajak" name="jns_pajak" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" onchange="aturPajakDanHitung()">
                                <option value="" selected>-- Pilih Jenis Pajak --</option>
                                <option value="ppn">PPN</option>
                                <option value="ppnbm">PPnBM</option>
                                <option value="bphtb">BPHTB</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="pajak_persen" class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak (%)</label>
                            <input type="text" class="form-input" id="pajak_persen" name="pajak_persen" aria-describedby="pajak_persen" placeholder="Masukan Pajak (%)" oninput="hitungPajak()">
                        </div>
                        <div class="mb-3">
                            <label for="pajak_dibayarkan" class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak Dibayarkan</label>
                            <input type="text" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" id="pajak_dibayarkan" name="pajak_dibayarkan" aria-describedby="pajak_dibayarkan" placeholder="Pajak Dibayarkan" readonly>
                        </div>
                    </div>
                    <hr class="border-2 border-gray-300 my-2"> <!-- Garis pemisah -->
                </div>

                <!-- collapse pajak -->
                <div id="collapseHutang" class="hidden w-full overflow-hidden transition-[height] duration-300 mt-5">
                    <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                        <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                            Hutang
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-3">
                        <div class="mb-3">
                            <label for="nominal_hutang" class="text-gray-800 text-sm font-medium inline-block mb-2">Hutang</label>
                            <input type="number" class="form-input" id="nominal_hutang" name="nominal_hutang" aria-describedby="hutang" placeholder="Masukan Nominal Hutang">
                        </div>
                        <div class="mb-3">
                            <label for="tgl_jatuh_tempo" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Jatuh Tempo</label>
                            <input type="text" class="form-input" name="tgl_jatuh_tempo" id="datepicker-basic">
                        </div>
                    </div>
                    <hr class="border-2 border-gray-300 my-2"> <!-- Garis pemisah -->
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
@foreach ($pengeluaran as $png)
<div id="modalEditPengeluaran{{$png->id_pengeluaran}}" class="modalEditPengeluaran w-full h-auto max-h-[70vh] mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden" data-pajak-value="{{ $png->pajak }}"
    data-hutang-value="{{ $png->hutang }}">
    <div class="max-w-[60rem] fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                Edit Data Pengeluaran
            </h3>
            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <form id="" action="{{ route('pengeluaran.update', $png->id_pengeluaran) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="px-4 py-4 overflow-y-auto h-auto max-h-[60vh]">
                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3">
                        <label for="tanggal" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal</label>
                        <input type="text" class="form-input" name="tanggal" id="datepicker-basic" value="{{ old('tanggal', $png->tanggal) }}">
                    </div>
                </div>
                <hr class=" border-2 border-gray-300 my-2">
                <div class="grid grid-cols-2 gap-4">
                    <!-- col 1 -->
                    <div class="mb-3">
                        <label for="nm_pengeluaran" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Pengeluaran</label>
                        <input type="text" class="form-input" id="nm_pengeluaran" name="nm_pengeluaran" aria-describedby="nm_pengeluaran" placeholder="Masukan Nama Pengeluaran" value="{{ old('nm_pengeluaran', $png->nm_pengeluaran) }}">
                    </div>
                    <div class=" mb-3">
                        <label for="kategori" class="text-gray-800 text-sm font-medium inline-block mb-2">Kategori</label>
                        <select id="kategori" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="kategori">
                            <option value="" selected>-- Pilih Kategori --</option>
                            @foreach ($kategori as $k)
                            <option value="{{ $k->nama_kategori }}" {{ old('kategori', $png->kategori) == $k->nama_kategori ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jenis_pengeluaran" class="text-gray-800 text-sm font-medium inline-block mb-2"> Jenis Pengeluaran </label>
                        <select id="jenis_pengeluaran" name="jenis_pengeluaran" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" onchange="togglePengeluaranEdit()">
                            <option value="" selected>-- Pilih Jenis Pengeluaran --</option>
                            <option value="gaji_karyawan" {{ old('jenis_pengeluaran', $png->jenis_pengeluaran) == 'gaji_karyawan' ? 'selected' : '' }}>Gaji Karyawan</option>
                            <option value="pembayaran_vendor" {{ old('jenis_pengeluaran', $png->jenis_pengeluaran) == 'pembayaran_vendor' ? 'selected' : '' }}>Pembayaran Vendor</option>
                        </select>
                    </div>
                    <div class="mb-3 hidden" id="div_nama_karyawan">
                        <label for="nama_karyawan" class="text-gray-800 text-sm font-medium inline-block mb-2"> Nama Karyawan </label>
                        <select id="nama_karyawan" name="id_kontak" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="" selected>-- Pilih Nama Karyawan --</option>
                            @foreach ($karyawanKontak as $karyawan)
                            <option value="{{ $karyawan->id_kontak }}" {{ old('id_kontak', $png->id_kontak) == $karyawan->id_kontak ? 'selected' : '' }}>{{ $karyawan->nama_kontak }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 hidden" id="div_nama_vendor">
                        <label for="nama_vendor" class="text-gray-800 text-sm font-medium inline-block mb-2"> Nama Vendor </label>
                        <select id="nama_vendor" name="id_kontak" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="" selected>-- Pilih Vendor --</option>
                            @foreach ($vendorKontak as $vendors)
                            <option value="{{ $vendors->id_kontak }}" {{ old('id_kontak', $png->id_kontak) == $vendors->id_kontak ? 'selected' : '' }}>{{ $vendors->nama_kontak }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr class="border-2 border-gray-300 my-2">
                <!-- col 2 -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3">
                        <label for="biaya" class="text-gray-800 text-sm font-medium inline-block mb-2">Biaya</label>
                        <input type="text" class="form-input" id="biaya" name="biaya" aria-describedby="biaya" placeholder="Masukan Biaya" oninput="formatRupiah(this)" value="{{ 'Rp. '.number_format(old('biaya', $png->biaya, 0, '.', '.')) }}">
                    </div>
                    <div class=" mb-3">
                        <label for="akun_pembayaran" class="text-gray-800 text-sm font-medium inline-block mb-2">Dibayarkan dari akun</label>
                        <select id="akun_pembayaran" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="akun_pembayaran">
                            <option value="" selected>-- Pilih Akun --</option>
                            @foreach ( $kas_bank as $a )
                            <option value="{{ $a->kode_akun }} {{ old('kode_akun', $png->kode_akun) == $a->kode_akun ? 'selected' : '' }}">
                                <span class="flex justify-between w-full">
                                    <span>{{ $a->nama_akun }}</span>
                                    <span> - </span>
                                    <span>{{ $a->kode_akun }}</span>
                                </span>
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="akun_pemasukan" class="text-gray-800 text-sm font-medium inline-block mb-2">Pengeluaran masuk akun</label>
                        <select id="akun_pemasukan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="akun_pemasukan">
                            <option value="" selected>-- Pilih Akun --</option>
                            @foreach ( $kas_bank as $a )
                            <option value="{{ $a->kode_akun }}">
                                <span class="flex justify-between w-full">
                                    <span>{{ $a->nama_akun }}</span>
                                    <span> - </span>
                                    <span>{{ $a->kode_akun }}</span>
                                </span>
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr class="border-2 border-gray-300 my-2">
                <!-- col 3 -->
                <div class="grid grid-cols-6">
                    <div class="mb-3">
                        <div class="flex items-center">
                            <input type="checkbox" id="pajakButton" name="pajakButton" class="form-switch text-primary" value="1" onchange="toggleCollapsePajak()">
                            <label for="pajakButton" class="ms-1.5">Pajak</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="flex items-center">
                            <input type="checkbox" id="hutangButton" name="hutangButton" class="form-switch text-primary" value="1" onchange="toggleCollapseHutang()">
                            <label for="hutangButton" class="ms-1.5">Hutang</label>
                        </div>
                    </div>
                </div>

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
                            <select id="jns_pajak" name="jns_pajak" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" onchange="aturPajakDanHitung()">
                                <option value="" selected>-- Pilih Jenis Pajak --</option>
                                <option value="ppn">PPN</option>
                                <option value="ppnbm">PPnBM</option>
                                <option value="bphtb">BPHTB</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="pajak_persen" class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak (%)</label>
                            <input type="text" class="form-input" id="pajak_persen" name="pajak_persen" aria-describedby="pajak_persen" placeholder="Masukan Pajak (%)" oninput="hitungPajak()" value="{{$png->pajak_persen}}">
                        </div>
                        <div class="mb-3">
                            <label for="pajak_dibayarkan" class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak Dibayarkan</label>
                            <input type="text" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" id="pajak_dibayarkan" name="pajak_dibayarkan" aria-describedby="pajak_dibayarkan" placeholder="Pajak Dibayarkan" value="{{ $png->pajak_dibayarkan }}" readonly>
                        </div>
                    </div>
                    <hr class="border-2 border-gray-300 my-2"> <!-- Garis pemisah -->
                </div>

                <!-- collapse pajak -->
                <div id="collapseHutang" class="hidden w-full overflow-hidden transition-[height] duration-300 mt-5">
                    <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                        <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                            Hutang
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-3">
                        <div class="mb-3">
                            <label for="nominal_hutang" class="text-gray-800 text-sm font-medium inline-block mb-2">Hutang</label>
                            <input type="number" class="form-input" id="nominal_hutang" name="nominal_hutang" aria-describedby="hutang" placeholder="Masukan Nominal Hutang" value="{{  $png->nominal_hutang }}">
                        </div>
                        <div class="mb-3">
                            <label for="tgl_jatuh_tempo" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Jatuh Tempo</label>
                            <input type="text" class="form-input" name="tgl_jatuh_tempo" id="datepicker-basic">
                        </div>
                    </div>
                    <hr class="border-2 border-gray-300 my-2"> <!-- Garis pemisah -->
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
<script src="{{ asset('js/custom-js/pengeluaran.js') }}" defer></script>
@endsection
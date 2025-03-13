@extends('layouts.vertical', ['title' => 'Hutang & Piutang', 'sub_title' => 'Pages', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('css')
    @vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
    @vite([
    'node_modules/flatpickr/dist/flatpickr.min.css',
    'node_modules/@simonwep/pickr/dist/themes/classic.min.css',
    'node_modules/@simonwep/pickr/dist/themes/monolith.min.css',
    'node_modules/@simonwep/pickr/dist/themes/nano.min.css',
    ])
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="grid grid-cols-12">
    <div class="col-span-12">

        <!-- chart -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> Overview Hutang dan Piutang</h4>
            </div>
            <div class="p-6">
                <div id="column_chart_hutang_piutang" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
        <!-- end chart -->

        <!-- table hutang -->
        <div class="card mt-10 p-5">
            <div class="card-header mb-5 flex justify-between items-center">
                <h4 class="card-title">Tabel Hutang</h4>
                <div class="filter-container flex flex-row items-center gap-4">
                    <div class="flex flex-col">
                        <label for="filterStatus" class="text-gray-800 text-sm font-medium">Filter Status:</label>
                        <select id="filterStatus" class="p-2 border border-gray-300 rounded-md"
                            data-table="pagination-table" data-status="filterStatus">
                            <option value="">Semua Status</option>
                            <option value="Belum Lunas">Belum Lunas</option>
                            <option value="Lunas">Lunas</option>
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label for="filterKategori" class="text-gray-800 text-sm font-medium">Filter Kategori:</label>
                        <select id="filterKategori" class="p-2 border border-gray-300 rounded-md"
                            data-table="pagination-table" data-kategori="filterKategori">
                            <option value="">Semua Kategori</option>
                            <option value="Kategori A">Kategori A</option>
                            <option value="Kategori B">Kategori B</option>
                        </select>
                    </div>
                </div>
                
            </div>
            <div class="card-body">
                <table id="pagination-table">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Perusahaan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Hutang</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Jatuh Tempo</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter = 1; @endphp
                        @foreach($hutang as $data)
                        <tr data-kategori="{{ $data->kategori }}" data-status="{{ $data->status }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $counter }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $data->nama_kontak }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $data->nm_perusahaan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $data->kategori }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ "Rp ".number_format($data->total_hutang, 0, ".", ".") }}</td>
                            @if ($data->status == 'Belum Lunas')
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                    <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-red-500 text-white">{{ $data->status }}</span>
                                </td>
                            @else
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                    <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-green-500 text-white">{{ $data->status }}</span>
                                </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ date('d-m-Y', strtotime($data->tgl_jatuh_tempo)) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                <button type="button" class="btn rounded-full bg-warning/25 text-warning hover:bg-warning hover:text-white" data-fc-behavior="static" data-fc-target="modalHutang{{$data->id_hutangpiutang}}" data-fc-type="modal" data-type="hutang" data-id-hutang="{{$data->id_hutangpiutang}}" onclick="openDetailHutang(this)">Detail</button>
                                @if ($data->status == 'Belum Lunas')
                                    <button type="button" class="btn rounded-full bg-info/25 text-info hover:bg-info hover:text-white" data-fc-behavior="static" data-fc-target="modalPelunasanHutang{{$data->id_hutangpiutang}}" data-fc-type="modal">Bayar Hutang</button>
                                @else

                                @endif
                            </td>
                        </tr>
                        @php $counter++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- end table -->

        <!-- Modal Riwayat Pembayaran Hutang -->
        @foreach ($hutang as $index)
            <div id="modalHutang{{$index->id_hutangpiutang}}" class="w-full h-auto max-h-[70vh] mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
                <div class="max-w-[60rem] fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                    <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white text-xl">
                                Riwayat Pembayaran Hutang - <span class="namaKontak">{{ $index->nama_kontak }}</span>
                            </h3>
                            <span class="text-sm text-gray-600 dark:text-gray-400 nmPerusahaan">{{ $index->nm_perusahaan }}</span>
                        </div>
                        <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-full" data-fc-dismiss type="button">
                            <span class="material-symbols-rounded">close</span>
                        </button>
                    </div>

                    <div class="loading-spinner hidden animate-spin inline-block w-6 h-6 border-[3px] border-current border-t-transparent text-primary rounded-full mx-auto mt-4" role="status" aria-label="loading">
                        <span class="sr-only">Loading...</span>
                    </div>

                    <div class="card-body p-5">
                        <table id="detail-hutang">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Pembayaran</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nominal Dibayarkan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sisa Pembayaran</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Masuk Akun</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data rows akan diisi oleh JavaScript -->
                            </tbody>
                        </table>
                        <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                            <button class="btn rounded-full bg-danger/25 text-danger hover:bg-danger hover:text-white transition-all" data-fc-dismiss type="button">Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <!-- end modal -->

        <!-- Modal Pelunasan Hutang -->
        @foreach ( $hutang as $data)
        <div id="modalPelunasanHutang{{$data->id_hutangpiutang}}" class="w-full h-auto max-h-[70vh] mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
            <div class="max-w-[60rem] fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                    <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                        Pembayaran Hutang
                    </h3>
                    <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>
                <div class="px-4 py-4 overflow-y-auto h-auto max-h-[60vh]">
                    <ul class="max-w flex flex-col">
                        <li class="inline-flex items-center gap-x-2 py-2.5 px-4 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                            <div class="flex justify-between w-full">
                                Nama
                                <span class="inline-flex items-center py-1 px-2 rounded-full text-md font-medium bg-[#307487] text-white">{{ $data->nama_kontak }}</span>
                            </div>
                        </li>
                        <li class="inline-flex items-center gap-x-2 py-2.5 px-4 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                            <div class="flex justify-between w-full">
                                Perusahaan
                                <span class="inline-flex items-center py-1 px-2 rounded-full text-md font-medium bg-[#307487] text-white">{{ $data->nm_perusahaan }}</span>
                            </div>
                        </li>
                        <li class="inline-flex items-center gap-x-2 py-2.5 px-4 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                            <div class="flex justify-between w-full">
                                Total Hutang
                                <span class="inline-flex items-center py-1 px-2 rounded-full text-md font-medium bg-[#307487] text-white">{{ "Rp ".number_format($data->total_hutang, 0, ".", ".") }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
                <hr class="border-2 border-gray-300 my-2">
                <form id="" action="{{ route('hutangpiutang.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_hutangpiutang" value="{{ $data->id_hutangpiutang }}">
                    <div class="px-4 py-4 overflow-y-auto h-auto max-h-[60vh]">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-3">
                                <label for="tanggal_pembayaran" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Pembayaran</label>
                                <input type="text" class="form-input" name="tanggal_pembayaran" id="datepicker-basic">
                            </div>
                            <div class="mb-3">
                                <label for="dibayarkan" class="text-gray-800 text-sm font-medium inline-block mb-2">Nominal Pembayaran</label>
                                <input type="text" class="form-input" id="dibayarkan" name="dibayarkan" aria-describedby="dibayarkan" placeholder="Masukan Nominal Pembayaran">
                            </div>
                            <div class="mb-3">
                                <label for="masuk_akun" class="text-gray-800 text-sm font-medium inline-block mb-2"> Akun Tujuan Pembayaran </label>
                                <select id="masuk_akun" name="masuk_akun" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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
                            <div class="mb-3">
                                <label for="catatan" name="catatan" class="text-gray-800 text-sm font-medium inline-block mb-2">Catatan</label>
                                <textarea id="catatan" name="catatan" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukan Catatan"></textarea>
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
        <!-- End Modal -->

        <!-- table piutang -->
        <div class="card mt-10 p-5">
            <div class="card-header mb-5 flex justify-between items-center">
                <h4 class="card-title">Tabel Piutang</h4>
                <div class="filter-container flex flex-row items-center gap-4">
                    <div class="flex flex-col">
                        <label for="filterStatusPiutang" class="text-gray-800 text-sm font-medium mb-1">Filter Status:</label>
                        <select id="filterStatusPiutang" class="p-2 border border-gray-300 rounded-md"
                            data-table="table-piutang" data-status="filterStatusPiutang">
                            <option value="">Semua Status</option>
                            <option value="Belum Lunas">Belum Lunas</option>
                            <option value="Lunas">Lunas</option>
                        </select>
                    </div>
                </div>
                
            </div>
            <div class="card-body">
                <table id="table-piutang">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Perusahaan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Piutang</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Jatuh Tempo</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter = 1; @endphp
                        @foreach($piutang as $data)
                            <tr data-kategori="{{ $data->kategori }}" data-status="{{ $data->status }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $counter }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $data->nama_kontak }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $data->nm_perusahaan }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $data->kategori }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ "Rp ".number_format($data->total_hutang, 0, ".", ".") }}</td>
                                @if ($data->status == 'Belum Lunas')
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                        <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-red-500 text-white">{{ $data->status }}</span>
                                    </td>
                                @else
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                        <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-green-500 text-white">{{ $data->status }}</span>
                                    </td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ date('d-m-Y', strtotime($data->tgl_jatuh_tempo)) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                    <button type="button" class="btn rounded-full bg-warning/25 text-warning hover:bg-warning hover:text-white" data-fc-behavior="static" data-fc-target="riwayatPiutang{{$data->id_hutangpiutang}}" data-fc-type="modal" data-type="piutang" data-id-piutang="{{$data->id_hutangpiutang}}" onclick="riwayatPiutang(this)">Detail</button>
                                    @if ($data->status == 'Belum Lunas')
                                        <button type="button" class="btn rounded-full bg-info/25 text-info hover:bg-info hover:text-white" data-fc-behavior="static" data-fc-target="modalPelunasanPiutang{{$data->id_hutangpiutang}}" data-fc-type="modal">Bayar Piutang</button>
                                    @else

                                    @endif
                                </td>
                            </tr>
                            @php $counter++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- end table -->

        <!-- Modal Riwayat Pembayaran Piutang -->
        @foreach ($piutang as $index)
        <div id="riwayatPiutang{{$index->id_hutangpiutang}}" class="w-full h-auto max-h-[70vh] mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
            <div class="max-w-[60rem] fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-xl">
                            Riwayat Pembayaran Piutang - <span class="namaKontak">{{ $index->nama_kontak }}</span>
                        </h3>
                        <span class="text-sm text-gray-600 dark:text-gray-400 nmPerusahaan">{{ $index->nm_perusahaan }}</span>
                    </div>
                    <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-full" data-fc-dismiss type="button">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>

                <div class="loading-spinner hidden animate-spin inline-block w-6 h-6 border-[3px] border-current border-t-transparent text-primary rounded-full mx-auto mt-4" role="status" aria-label="loading">
                    <span class="sr-only">Loading...</span>
                </div>

                <div class="card-body p-5">
                    <table id="riwayat-piutang">
                        <thead>
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Pembayaran</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nominal Dibayarkan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sisa Pembayaran</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Masuk Akun</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data rows akan diisi oleh JavaScript -->
                        </tbody>
                    </table>
                    <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                        <button class="btn rounded-full bg-danger/25 text-danger hover:bg-danger hover:text-white transition-all" data-fc-dismiss type="button">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        <!-- end modal -->

        <!-- Modal Pelunasan Piutang -->
        @foreach ( $piutang as $data)
        <div id="modalPelunasanPiutang{{$data->id_hutangpiutang}}" class="w-full h-auto max-h-[70vh] mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
            <div class="max-w-[60rem] fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                    <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                        Pembayaran Piutang
                    </h3>
                    <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>
                <div class="px-4 py-4 overflow-y-auto h-auto max-h-[60vh]">
                    <ul class="max-w flex flex-col">
                        <li class="inline-flex items-center gap-x-2 py-2.5 px-4 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                            <div class="flex justify-between w-full">
                                Nama
                                <span class="inline-flex items-center py-1 px-2 rounded-full text-md font-medium bg-[#307487] text-white">{{ $data->nama_kontak }}</span>
                            </div>
                        </li>
                        <li class="inline-flex items-center gap-x-2 py-2.5 px-4 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                            <div class="flex justify-between w-full">
                                Perusahaan
                                <span class="inline-flex items-center py-1 px-2 rounded-full text-md font-medium bg-[#307487] text-white">{{ $data->nm_perusahaan }}</span>
                            </div>
                        </li>
                        <li class="inline-flex items-center gap-x-2 py-2.5 px-4 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                            <div class="flex justify-between w-full">
                                Total Hutang
                                <span class="inline-flex items-center py-1 px-2 rounded-full text-md font-medium bg-[#307487] text-white">{{ "Rp ".number_format($data->total_hutang, 0, ".", ".") }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
                <hr class="border-2 border-gray-300 my-2">
                <form id="" action="{{ route('hutangpiutang.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_hutangpiutang" value="{{ $data->id_hutangpiutang }}">
                    <div class="px-4 py-4 overflow-y-auto h-auto max-h-[60vh]">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-3">
                                <label for="tanggal_pembayaran" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Pembayaran</label>
                                <input type="date" class="form-input" name="tanggal_pembayaran" id="datepicker-basic">
                            </div>
                            <div class="mb-3">
                                <label for="dibayarkan" class="text-gray-800 text-sm font-medium inline-block mb-2">Nominal Pembayaran</label>
                                <input type="text" class="form-input" id="piutang_dibayarkan" name="dibayarkan" aria-describedby="dibayarkan" placeholder="Masukan Nominal Pembayaran">
                            </div>
                            <div class="mb-3">
                                <label for="masuk_akun" class="text-gray-800 text-sm font-medium inline-block mb-2"> Akun Tujuan Pembayaran </label>
                                <select id="masuk_akun" name="masuk_akun" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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
                            <div class="mb-3">
                                <label for="catatan" name="catatan" class="text-gray-800 text-sm font-medium inline-block mb-2">Catatan</label>
                                <textarea id="catatan" name="catatan" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukan Catatan"></textarea>
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
        <!-- End Modal -->
    </div>
</div>
@endsection

@section('script')
@vite(['resources/js/pages/highlight.js', 'resources/js/pages/form-flatpickr.js', 'resources/js/pages/form-color-pickr.js'])
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
<script src="{{ asset('js/custom-js/hutangpiutang.js') }}" defer></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    const hutang = {{ Js::from($chart['hutang']) }}
    const piutang = {{ Js::from($chart['piutang']) }}

    var options = {
        chart: {
            height: 350,
            type: 'bar',
            toolbar: {
                show: false,
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '45%',
                endingShape: 'rounded'
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        series: [{
            name: 'Piutang',
            data: piutang
        }, {
            name: 'Hutang',
            data: hutang
        }],
        colors: ['#34c38f', '#f46a6a'],
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Des'],
        },
        yaxis: {
            title: {
                text: 'Rp (Rupiah)',
                style: {
                    fontWeight: '500',
                },
            }
        },
        grid: {
            borderColor: '#9ca3af20',
        },
        fill: {
            opacity: 1

        },
        tooltip: {
            y: {
                formatter: function (val) {
                    let toRupiah = Intl.NumberFormat({
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(val)

                    return "Rp " + toRupiah
                }
            }
        }
    }

    var chart = new ApexCharts(
        document.querySelector("#column_chart_hutang_piutang"),
        options
    );

    chart.render();


    function filterTable(tableId, kategoriFilterId = null, statusFilterId) {
        const table = document.getElementById(tableId);
        if (!table) return;

        const kategoriFilter = kategoriFilterId ? document.getElementById(kategoriFilterId)?.value.toLowerCase() : "";
        const statusFilter = document.getElementById(statusFilterId)?.value.toLowerCase() || "";

        table.querySelectorAll("tbody tr").forEach(row => {
            const kategori = row.getAttribute("data-kategori")?.toLowerCase() || "";
            const status = row.getAttribute("data-status")?.toLowerCase() || "";

            const kategoriMatch = !kategoriFilter || kategori === kategoriFilter;
            const statusMatch = !statusFilter || status === statusFilter;

            row.style.display = kategoriMatch && statusMatch ? "" : "none";
        });
    }

    // Event Listeners untuk otomatis memanggil filter saat dropdown berubah
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll("select[data-table]").forEach(select => {
            select.addEventListener("change", function () {
                const tableId = this.getAttribute("data-table");
                const kategoriFilterId = this.getAttribute("data-kategori");
                const statusFilterId = this.getAttribute("data-status");

                filterTable(tableId, kategoriFilterId, statusFilterId);
            });
        });
    });


</script>
@endsection

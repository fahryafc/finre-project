@extends('layouts.vertical', ['title' => 'Pajak PPnBM', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

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
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card-wrapper">
        <div class="card h-full flex flex-col bg-white">
            <div class="p-6 flex-grow flex flex-col justify-center">
                <h4 class="card-title mb-4">Golongan Pajak</h4>
                <div id="pie_chart" class="apex-charts my-4 flex-grow flex items-center justify-center">
                </div>
            </div>
        </div>
    </div>
    <div class="grid grid-rows-2 gap-6">
        <div class="card-wrapper">
            <div class="card h-full flex flex-col bg-green-100">
                <div class="p-6 flex-grow flex flex-col justify-between relative">
                    <div class="flex justify-between items-start">
                        <h3 class="text-green-800 text-xl font-bold mt-2">Pajak Keluaran</h3>
                        <div class="w-16 h-16 rounded-full flex items-center justify-center bg-green-200">
                            <i class="mgc_arrow_left_down_fill text-3xl text-green-600"></i>
                        </div>
                    </div>
                    <p class="text-green-700 font-bold text-3xl truncate mt-auto pt-4">Rp. 50.000.000</p>
                </div>
            </div>
        </div>
        <!-- Card Uang Keluar -->
        <div class="card-wrapper">
            <div class="card h-full flex flex-col bg-red-100">
                <div class="p-6 flex-grow flex flex-col justify-between relative">
                    <div class="flex justify-between items-start">
                        <h3 class="text-red-800 text-xl font-bold mt-2">Pajak Masukan</h3>
                        <div class="w-16 h-16 rounded-full flex items-center justify-center bg-red-200">
                            <i class="mgc_arrow_right_up_fill text-3xl text-red-600"></i>
                        </div>
                    </div>
                    <p class="text-red-700 font-bold text-3xl truncate mt-auto pt-4">Rp. 20.000.000</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid lg grid-cols-1 gap-6">
    <div class="card mt-10 p-5">
        <div class="card-header mb-5">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <h4 class="card-title">Data Pajak PPnBM</h4>
                    <input type="date" class="border border-gray-300 rounded-md p-2" id="from_date" name="from_date" value="{{ request()->get('from') ?? request()->get('from') }}">
                    <span>To</span>
                    <input type="date" disabled class="border border-gray-300 rounded-md p-2" id="to_date" name="to_date" value="{{ request()->get('to') ?? request()->get('to') }}">
                    @if (request()->get('from') || request()->get('to'))
                        <a href="/pajak/ppnbm" class="btn bg-red-600 text-white">
                            Reset
                        </a>
                    @endif
                </div>
                <!-- <button class="btn bg-[#307487] text-white" data-fc-target="modalTambahAkun" data-fc-type="modal" type="button"><i class="mgc_add_fill text-base me-4"></i>
                    Tambah Data
                </button> -->
            </div>
        </div>
        <div class="card-body">
            <table id="penjualan-table">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Deskripsi Barang Mewah</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Harga Barang</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Tarif PPNBM</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Jenis Pajak</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">PPNBM yang Dikenakan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Tanggal Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter = 1; @endphp
                    @foreach($pajak_ppnbm as $ppnbm)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $counter + ($pajak_ppnbm->currentPage() - 1) * $pajak_ppnbm->perPage() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $ppnbm->deskripsi_barang }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ "Rp. ".number_format($ppnbm->harga_barang, 0, ".", ".") }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $ppnbm->tarif_ppnbm }}%</td>
                        @if ($ppnbm->jenis_pajak == 'Pajak Keluaran')
                            <td>
                                <span class="whitespace-nowrap gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-green-500 text-white">{{ $ppnbm->jenis_pajak }}</span>
                            </td>
                        @else
                            <td>
                                <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-red-500 text-white">{{ $ppnbm->jenis_pajak }}</span>
                            </td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ "Rp. ".number_format($ppnbm->ppnbm_dikenakan, 0, ".", ".") }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($ppnbm->tgl_transaksi)->format('d-m-Y') }}</td>
                    </tr>
                    @php $counter++; @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
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
<script>
    let dateFrom, dateTo
    document.getElementById('from_date').addEventListener('change', function() {
        dateFrom = this.value
        document.getElementById('to_date').disabled = false
        document.getElementById('to_date').min = dateFrom
    })

    document.getElementById('to_date').addEventListener('change', function() {
        dateTo = this.value
        window.location.href = `?from=${dateFrom}&to=${dateTo}`
    })
</script>
@endsection

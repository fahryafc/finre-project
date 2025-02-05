@extends('layouts.vertical', ['title' => 'Pengeluaran', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

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
<style>
    .hidden {
        display: none;
    }
</style>
@endsection

@section('content')
<div class="grid grid-rows-1 grid-flow-col gap-4">
    <div class="col-span-5 lg:col-span-2">
        <div class="bg-red-50 border border-red-200 rounded-lg p-6 h-full relative">
            <!-- Header Section -->
            <!-- Teks di Kiri Atas -->
            <h2 class="text-red-600 text-2xl font-bold h-[20&]">Total Pengeluaran</h2>
            <div class="flex items-center justify-between h-[70%]">
                <div class="flex items-center h-full">
                    <!-- Angka di bawah Teks "Total Pengeluaran" -->
                    <p class="text-3xl xl:text-4xl font-extrabold text-red-600 mt-4">Rp 6.500.000</p>
                </div>
                <!-- Ikon Tetap di Kanan -->
                <div class="text-red-600 p-3 rounded-full">
                    <i data-feather="activity" class="h-32 w-32"></i>
                </div>
            </div>
            <!-- Footer Section -->
            <div class="flex items-center justify-between mt-4 h-[10%]">
                <span class="text-gray-500 text-lg">Dari bulan lalu</span>
            </div>
        </div>
    </div>

    <div class="col-span-5 lg:col-span-3">
        <div class="bg-white shadow-md rounded-lg p-4">
            <div class="flex justify-between items-center mb-4">
                <div class="flex gap-2">
                    <h2 class="text-lg font-semibold">Pengeluaran</h2>
                </div>

            </div>

            <div class="flex flex-col items-start mb-2">
                <p class="text-2xl font-semibold ml-2 text-red-500">
                    Rp 6.600.000
                    {{-- <span class="inline-flex items-center">
                        <svg class="text-green-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M16.21 16H7.79a1.76 1.76 0 0 1-1.59-1a2.1 2.1 0 0 1 .26-2.21l4.21-5.1a1.76 1.76 0 0 1 2.66 0l4.21 5.1A2.1 2.1 0 0 1 17.8 15a1.76 1.76 0 0 1-1.59 1" />
                        </svg>
                        <svg class="text-red-500 -ml-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M12 17a1.72 1.72 0 0 1-1.33-.64l-4.21-5.1a2.1 2.1 0 0 1-.26-2.21A1.76 1.76 0 0 1 7.79 8h8.42a1.76 1.76 0 0 1 1.59 1.05a2.1 2.1 0 0 1-.26 2.21l-4.21 5.1A1.72 1.72 0 0 1 12 17" />
                        </svg>
                    </span> --}}
                </p>
            </div>

            <div id="chart-pengeluaran" class="apex-charts mt-4"></div>

        </div>
    </div>
</div>

<div class="grid lg grid-cols-1 gap-6">
    <div class="card mt-10 p-5">
        <div class="card-header mb-5">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <h4 class="card-title">Data Penjualan</h4>
                    <input type="date" class="border border-gray-300 rounded-md p-2" id="from_date" name="from_date" value="{{ request()->get('from') ?? request()->get('from') }}">
                    <span>To</span>
                    <input type="date" disabled class="border border-gray-300 rounded-md p-2" id="to_date" name="to_date" value="{{ request()->get('to') ?? request()->get('to') }}">
                    @if (request()->get('from') || request()->get('to'))
                        <a href="/pengeluaran" class="btn bg-red-600 text-white">
                            Reset
                        </a>
                    @endif
                </div>
                <a href="{{ route('pengeluaran.create') }}" class="btn bg-[#307487] text-white">
                    <i class="mgc_add_fill text-base me-4"></i>
                    Tambah Data
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="pengeluaran-table">
                <thead>
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
                <tbody>
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
                            <a href="{{ route('pengeluaran.edit', $p->id_pengeluaran) }}" class="btn rounded-full bg-warning/25 text-warning hover:bg-warning hover:text-white" data-fc-target="modalEditPengeluaran{{$p->id_pengeluaran}}" data-fc-type="modal"><i class="mgc_edit_2_line"></i></a>
                        </td>
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
@vite('resources/js/pages/custom.js')
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
<script src="{{ asset('js/custom-js/pengeluaran.js') }}" defer></script>
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

    if (document.getElementById("pengeluaran-table") && typeof simpleDatatables.DataTable !== 'undefined') {
        const dataTable = new simpleDatatables.DataTable("#pengeluaran-table", {
            paging: true,
            perPage: 5,
            perPageSelect: [5, 10, 15, 20, 25],
            sortable: false,
            labels: {
                perPage: "",
                noRows: "Tidak ada data",
                info: "Menampilkan {start} sampai {end} dari {rows} entri"
            }
        });
    }
</script>
@endsection

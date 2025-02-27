@extends('layouts.vertical', ['title' => 'Dashboard', 'sub_title' => 'Menu', 'mode' => $mode ?? '', 'demo' => $demo ?? '', 'isBreadcrumb' => false])

{{-- flatpickr daterange --}}
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
    <div class="max-w-7xl mx-auto p-4">
        {{-- summary card --}}
        <div class="grid lg:grid-cols-3 md:grid-cols-1 gap-6 mb-6">
            <div class="col-span-1">
                <div class="card bg-success/25">
                    <div class="py-8 px-6 relative">
                        <div class="flex items-center">
                            <div class="flex-grow">
                                <p class="text-success text-xl font-bold mb-5">Total Penjualan</p>
                                <p class="text-2xl text-green-900 font-bold">Rp. <br> {{ number_format($total_penjualan, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex-shrink-0 ms-3">
                                <div class="flex justify-center items-center text-success">
                                    <i class="w-20 h-20" data-feather="activity"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-1">
                <div class="card bg-danger/25 h-full">
                    <div class="py-8 px-6 relative">
                        <div class="flex items-center">
                            <div class="flex-grow">
                                <p class="text-danger text-xl font-bold mb-5">Total Pesanan</p>
                                <p class="text-2xl text-red-900 font-bold">{{ number_format($total_pesanan, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex-shrink-0 ms-3">
                                <div class="flex justify-center items-center text-danger">
                                    <i class="w-20 h-20" data-feather="activity"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-1">
                <div class="card bg-info/25 h-full">
                    <div class="py-8 px-6 relative">
                        <div class="flex items-center">
                            <div class="flex-grow">
                                <p class="text-info text-xl font-bold mb-5">Total Pelanggan</p>
                                <p class="text-2xl text-blue-900 font-bold">{{ number_format($total_pelanggan, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex-shrink-0 ms-3">
                                <div class="flex justify-center items-center text-info">
                                    <i class="w-20 h-20" data-feather="activity"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Sales Report -->
            <div class="col-span-1 md:col-span-3 h-full">
                <div class="bg-white shadow-md rounded-lg p-4">
                    <div class="mb-5">
                        <input type="date" class="border border-gray-300 rounded-md p-2" id="from_date" name="from_date" value="{{ request()->get('from') ?? request()->get('from') }}">
                        <span>To</span>
                        <input type="date" disabled class="border border-gray-300 rounded-md p-2" id="to_date" name="to_date" value="{{ request()->get('to') ?? request()->get('to') }}">
                        @if (request()->get('from') || request()->get('to'))
                            <a href="/dashboard/pendapatan" class="btn bg-red-600 text-white">
                                Reset
                            </a>
                        @endif
                    </div>
                    <table id="produk-terlaris-table">
                        <thead>
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">No</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Nama Produk</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">QTY</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @foreach($produk_terlaris as $p)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                        {{ $counter + ($produk_terlaris->currentPage() - 1) * $produk_terlaris->perPage() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                        {{ $p->nama_produk }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                        {{ number_format($p->kuantitas, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                        Rp {{ number_format($p->total_penjualan, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @php $counter++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Kas & Bank -->
            <div class="col-span-1 md:col-span-2 h-full">
                <div class="bg-white shadow-md rounded-lg p-4">
                    <p class="font-bold text-lg">Pendapatan Lainnya</p>
                    <div id="bar_chart" class="apex-charts mt-4" dir="ltr"></div>
                </div>
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
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
<script src="{{ asset('js/custom-js/pengeluaran.js') }}" defer></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    let dateFrom, dateTo
    document.getElementById('from_date').addEventListener('change', function() {
        dateFrom = this.value
        document.getElementById('to_date').disabled = false
        document.getElementById('to_date').min = dateFrom

        // Set maksimal hanya 1 tahun setelah from_date
        let maxToDate = new Date(dateFrom);
        maxToDate.setFullYear(maxToDate.getFullYear() + 1);
        document.getElementById('to_date').max = maxToDate.toISOString().split("T")[0];
    })

    document.getElementById('to_date').addEventListener('change', function() {
        dateTo = this.value

        let minFromDate = new Date(dateTo);
        minFromDate.setFullYear(minFromDate.getFullYear() - 1);
        document.getElementById('from_date').min = minFromDate.toISOString().split("T")[0];

        window.location.href = `?from=${dateFrom}&to=${dateTo}`
    })
</script>
<script>
    if (document.getElementById("produk-terlaris-table") && typeof simpleDatatables.DataTable !== 'undefined') {
        const dataTable = new simpleDatatables.DataTable("#produk-terlaris-table", {
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
<script>
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
                horizontal: true,
            }
        },
        dataLabels: {
            enabled: false
        },
        series: [{
            data: {{ Js::from($chart['pendapatan_lain']) }}
        }],
        tooltip: {
            y: {
                formatter: function (val) {
                    return "Rp " + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }
            }
        },
        colors: ['#34c38f'],
        grid: {
            borderColor: '#9ca3af20',
        },
        xaxis: {
            categories: {{ Js::from(count($period)) }} > 0 ? {{ Js::from($period) }} : ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        }
    }

    var chart = new ApexCharts(
        document.querySelector("#bar_chart"),
        options
    );

    chart.render();
</script>
@endsection

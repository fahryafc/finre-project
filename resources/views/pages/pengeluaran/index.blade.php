@extends('layouts.vertical', ['title' => 'Pengeluaran', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    @vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
    @vite(['node_modules/nice-select2/dist/css/nice-select2.css'])
    @vite(['node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/@simonwep/pickr/dist/themes/classic.min.css', 'node_modules/@simonwep/pickr/dist/themes/monolith.min.css', 'node_modules/@simonwep/pickr/dist/themes/nano.min.css'])
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

    <div class="grid lg grid-cols-1 gap-6">
        <div class="card mt-10 p-5">
            <div class="card-header mb-5">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <h4 class="card-title">Data Pengeluaran</h4>
                        <input type="date" class="border border-gray-300 rounded-md p-2" id="from_date" name="from_date"
                            value="{{ request()->get('from') ?? request()->get('from') }}">
                        <span>To</span>
                        <input type="date" disabled class="border border-gray-300 rounded-md p-2" id="to_date"
                            name="to_date" value="{{ request()->get('to') ?? request()->get('to') }}">
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
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">
                                No</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">
                                Nama Pengeluaran</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">
                                Nama Vendor / Karyawan</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">
                                Kategori</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">
                                Total Pembayaran</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">
                                Akun</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">
                                Pajak</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">
                                Total Pajak</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">
                                Hutang</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter = 1; @endphp
                        @foreach ($pengeluaran as $p)
                            <tr>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                    {{ $counter + ($pengeluaran->currentPage() - 1) * $pengeluaran->perPage() }}</td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                    {{ $p->nm_pengeluaran }}</td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                    {{ $p->nama_kontak }} </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                    {{ $p->kategori }}</td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                    {{ 'Rp. ' . number_format(parseRupiahToNumber($p->biaya), 0, '.', '.') }}</td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                    {{ $p->akun_pembayaran }}</td>
                                @if ($p->pajak == 1)
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                        {{ $p->jns_pajak }}</td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                        {{ 'Rp. ' . number_format(parseRupiahToNumber($p->pajak_dibayarkan), 0, '.', '.') }}
                                    </td>
                                @else
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                        - </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                        - </td>
                                @endif
                                @if ($p->hutang != 0)
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                        {{ 'Rp. ' . number_format(parseRupiahToNumber($p->nominal_hutang), 0, '.', '.') }}
                                    </td>
                                @else
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                        0</td>
                                @endif
                                @csrf
                                @method('DELETE')
                                <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                    <a href="{{ route('pengeluaran.destroy', $p->id_pengeluaran) }}"
                                        data-confirm-delete="true"
                                        class="btn rounded-full bg-danger/25 text-danger hover:bg-danger hover:text-white"><i
                                            class="mgc_delete_2_line"></i></a>
                                    <a href="{{ route('pengeluaran.edit', $p->id_pengeluaran) }}"
                                        class="btn rounded-full bg-warning/25 text-warning hover:bg-warning hover:text-white"
                                        data-fc-target="modalEditPengeluaran{{ $p->id_pengeluaran }}"
                                        data-fc-type="modal"><i class="mgc_edit_2_line"></i></a>
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
        let colors = []

        // for (let i = 0; i < {{ count($categoryList) }}; i++) {
        //     colors.push("#" + Math.floor(Math.random() * 16777215).toString(16));
        // }
        var options = {
            chart: {
                height: 320,
                type: 'pie',
            },
            series: {{ Js::from($categoryValue) }},
            labels: {{ Js::from($categoryList) }},
            colors: [
                '#3674B5', '#578FCA', '#A1E3F9', '#D1F8EF',
                '#F2EFE7', '#9ACBD0', '#9ACBD0', '#48A6A7', '#2973B2',
                '#EFDCAB', '#F2F6D0'
            ],
            legend: {
                show: true,
                position: 'bottom',
                horizontalAlign: 'center',
                verticalAlign: 'middle',
                floating: false,
                fontSize: '14px',
                offsetX: 0,
            },
            stroke: {
                colors: ['transparent']
            },
            responsive: [{
                breakpoint: 600,
                options: {
                    chart: {
                        height: 240
                    },
                    legend: {
                        show: false
                    },
                }
            }]

        }

        var chart = new ApexCharts(
            document.querySelector("#pie_chart"),
            options
        );

        chart.render();
    </script>

    <script>
        var options = {
            chart: {
                height: 350,
                type: 'area',
                toolbar: {
                    show: false,
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3,
            },
            series: [{
                name: 'Pemasukan',
                data: {{ Js::from($pengeluaranChart) }}
            }],
            colors: ['#556ee6'],
            xaxis: {
                type: 'month',
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Des'],
            },
            grid: {
                borderColor: '#9ca3af20',
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm'
                },
                y: {
                    formatter: function(val) {
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
            document.querySelector("#spline_area"),
            options
        );

        chart.render();
    </script>
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

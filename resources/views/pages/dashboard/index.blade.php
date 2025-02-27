@extends('layouts.vertical', ['title' => 'Dashboard', 'sub_title' => 'Menu', 'mode' => $mode ?? '', 'demo' => $demo ?? '', 'isBreadcrumb' => false])

{{-- flatpickr daterange --}}
@section('css')
    @vite(['node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/@simonwep/pickr/dist/themes/classic.min.css', 'node_modules/@simonwep/pickr/dist/themes/monolith.min.css', 'node_modules/@simonwep/pickr/dist/themes/nano.min.css'])
@endsection

@section('content')
    <div class="max-w-7xl mx-auto p-4">
        <!-- Carousel -->
        <div class="relative mb-6">
            <!-- Wrapper for slides -->
            <div class="carousel overflow-hidden rounded-lg relative">
                <div class="flex transition-transform duration-500 ease-in-out" id="carousel-images">
                    <div class="w-full flex-shrink-0">
                        <img src="https://picsum.photos/800/400" class="w-full h-[300px]" alt="Slide 1">
                    </div>
                    <div class="w-full flex-shrink-0">
                        <img src="https://picsum.photos/800/400" class="w-full h-[300px]" alt="Slide 2">
                    </div>
                    <div class="w-full flex-shrink-0">
                        <img src="https://picsum.photos/800/400" class="w-full h-[300px]" alt="Slide 3">
                    </div>
                    <div class="w-full flex-shrink-0">
                        <img src="https://picsum.photos/800/400" class="w-full h-[300px]" alt="Slide 4">
                    </div>
                    <div class="w-full flex-shrink-0">
                        <img src="https://picsum.photos/800/400" class="w-full h-[300px]" alt="Slide 5">
                    </div>
                </div>
            </div>
            <!-- Dots for navigation -->
            <div class="flex justify-center mt-2 space-x-2">
                <button class="h-4 w-4 rounded-full bg-green-500" data-slide="0"></button>
                <button class="h-4 w-4 rounded-full bg-gray-300" data-slide="1"></button>
                <button class="h-4 w-4 rounded-full bg-gray-300" data-slide="2"></button>
                <button class="h-4 w-4 rounded-full bg-gray-300" data-slide="3"></button>
                <button class="h-4 w-4 rounded-full bg-gray-300" data-slide="4"></button>
            </div>
        </div>

        {{-- summary card --}}
        <div class="grid lg:grid-cols-3 md:grid-cols-1 gap-6 mb-6">
            <div class="col-span-1">
                <div class="card">
                    <div class="py-8 px-6 relative">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 me-3">
                                <div class="w-12 h-12 flex justify-center items-center rounded text-success bg-success/25">
                                    <i data-feather="trending-up"></i>
                                </div>
                            </div>
                            <div class="flex-grow">
                                <p class="text-gray-600 font-medium">Pendapatan</p>
                                <p class="text-lg font-bold">Rp {{ number_format($data['total_pendapatan'], 0, ',', '.') }}</p>
                            </div>
                            <div class="">
                                <a href="/dashboard/pendapatan" class="text-slate-500 text-sm flex items-center">
                                    <span>Detail </span>
                                    <i data-feather="chevron-right" class="h-4"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-1">
                <div class="card">
                    <div class="py-8 px-6 relative">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 me-3">
                                <div class="w-12 h-12 flex justify-center items-center rounded text-danger bg-danger/25">
                                    <i data-feather="trending-down"></i>
                                </div>
                            </div>
                            <div class="flex-grow">
                                <p class="text-gray-600 font-medium">Pengeluaran</p>
                                <p class="text-lg font-bold">Rp {{ number_format($data['total_pengeluaran'], 0, ',', '.') }}</p>
                            </div>
                            <div class="">
                                <a href="/dashboard/pengeluaran" class="text-slate-500 text-sm flex items-center">
                                    <span>Detail </span>
                                    <i data-feather="chevron-right" class="h-4"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-1">
                <div class="card">
                    <div class="py-8 px-6 relative">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 me-3">
                                <div class="w-12 h-12 flex justify-center items-center rounded text-info bg-info/25">
                                    <i data-feather="dollar-sign"></i>
                                </div>
                            </div>
                            <div class="flex-grow">
                                <p class="text-gray-600 font-medium">Keuntungan</p>
                                <p class="text-lg font-bold">Rp {{ number_format($data['total_keuntungan'], 0, ',', '.') }}</p>
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
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex gap-2">
                            <h2 class="text-lg font-semibold">Penjualan</h2>
                        </div>
                        <div>
                            <input type="date" class="border border-gray-300 rounded-md p-2" id="from_date" name="from_date" value="{{ request()->get('from') ?? request()->get('from') }}">
                            <span>To</span>
                            <input type="date" disabled class="border border-gray-300 rounded-md p-2" id="to_date" name="to_date" value="{{ request()->get('to') ?? request()->get('to') }}">
                            @if (request()->get('from') || request()->get('to'))
                                <a href="/dashboard" class="btn bg-red-600 text-white">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col items-start mb-2">
                        <p class="text-sm text-gray-500">Avg. per month</p>
                        <p class="text-2xl font-semibold ml-2 text-green-500">
                            Rp {{ number_format(array_sum($chart['jml_penjualan']) / count($chart['jml_penjualan']), 0, ',', '.') }}
                            {{-- <span class="inline-flex items-center">
                                <svg class="text-green-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M16.21 16H7.79a1.76 1.76 0 0 1-1.59-1a2.1 2.1 0 0 1 .26-2.21l4.21-5.1a1.76 1.76 0 0 1 2.66 0l4.21 5.1A2.1 2.1 0 0 1 17.8 15a1.76 1.76 0 0 1-1.59 1" />
                                </svg>
                                <svg class="text-red-500 -ml-2" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M12 17a1.72 1.72 0 0 1-1.33-.64l-4.21-5.1a2.1 2.1 0 0 1-.26-2.21A1.76 1.76 0 0 1 7.79 8h8.42a1.76 1.76 0 0 1 1.59 1.05a2.1 2.1 0 0 1-.26 2.21l-4.21 5.1A1.72 1.72 0 0 1 12 17" />
                                </svg>
                            </span> --}}
                        </p>
                    </div>

                    <div id="chart" class="apex-charts mt-4"></div>

                </div>
            </div>

            <!-- Kas & Bank -->
            <div class="col-span-1 md:col-span-2 h-full">
                <div class="bg-white shadow-md rounded-lg p-4">
                    <p class="font-bold text-lg">Kas & Bank</p>
                    <div id="donut_chart" class="apex-charts mt-4" dir="ltr"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- @vite('resources/js/pages/custom.js') --}}
    {{-- flatpickr --}}
    @vite(['resources/js/pages/highlight.js', 'resources/js/pages/form-color-pickr.js'])
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
        var options = {
            series: [
                {
                    name: "Penjualan",
                    data: {{ Js::from($chart['jml_penjualan']) }},
                },
            ],
            chart: {
                height: 217,
                type: "bar",
                toolbar: {
                    show: false,
                },
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: "55%",
                    endingShape: "rounded",
                    borderRadius: 4,
                },
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                show: true,
                width: 2,
                colors: ["#00b461"],
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "Rp " + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }
                }
            },
            colors: ["#00b461"],
            grid: {
                borderColor: "#9ca3af20",
            },
            xaxis: {
                categories: {{ Js::from(count($period)) }} > 0 ? {{ Js::from($period) }} : [
                    "Jan",
                    "Feb",
                    "Mar",
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dec",
                ],
            },
            yaxis: {
                title: {
                    text: "Rupiah",
                },
            },
            tooltip: {
                custom: ({ series, seriesIndex, dataPointIndex, w }) => {
                    return (
                        '<div class="tooltip-box">' +
                        "<p>Rp " +
                            series[seriesIndex][dataPointIndex] * 130 +
                        "</p>" +
                        "<p>Qty: " +
                            series[seriesIndex][dataPointIndex] +
                        "</p>" +
                        "</div>"
                    );
                },
            },
            annotations: {
                yaxis: [
                    {
                        y: 24000,
                        borderColor: "grey",
                        label: {
                            borderColor: "transparent",
                            style: {
                                color: "black",
                                background: "transparent",
                            },
                            text: "Median: 24.000",
                            position: "left",
                            offsetX: 35, // Digeser sedikit ke kanan
                        },
                    },
                ],
            },
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>
    <script>
        var options = {
            chart: {
                height: 320,
                type: "donut",
            },
            series: [{{ Js::from((int)$chart['uang_masuk']) }}, {{ Js::from((int)$chart['uang_keluar']) }}],
            labels: ["Uang Masuk", "Uang Keluar"],
            colors: ["#34c38f", "#556ee6"],
            legend: {
                show: true,
                position: "bottom",
                horizontalAlign: "center",
                verticalAlign: "middle",
                floating: false,
                fontSize: "14px",
                offsetX: 0,
            },
            stroke: {
                colors: ["transparent"],
            },
            responsive: [
                {
                    breakpoint: 600,
                    options: {
                        chart: {
                            height: 240,
                        },
                        legend: {
                            show: false,
                        },
                    },
                },
            ],
        };

        var chart = new ApexCharts(document.querySelector("#donut_chart"), options);

        chart.render();
    </script>
@endsection

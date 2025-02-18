@extends('layouts.vertical', ['title' => 'Pajak PPH', 'sub_title' => 'Pages', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('css')
<link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
@endsection
@section('content')
<div class="grid grid-cols-1">
    <div class="card-wrapper">
        <div class="card h-full flex flex-col bg-white">
            <div class="p-6 flex-grow flex flex-col justify-center">
                <h4 class="card-title mb-4">Golongan Pajak</h4>
                <div id="column_chart" class="apex-charts my-4 flex-grow flex items-center justify-center">
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="grid grid-rows-2 gap-6">
        <div class="card-wrapper">
            <div class="card h-full flex flex-col bg-green-100">
                <div class="p-6 flex-grow flex flex-col justify-between relative">
                    <div class="flex justify-between items-start">
                        <h3 class="text-green-800 text-xl font-bold mt-2">Pajak Diterima</h3>
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
                        <h3 class="text-red-800 text-xl font-bold mt-2">Pajak Dibayarkan</h3>
                        <div class="w-16 h-16 rounded-full flex items-center justify-center bg-red-200">
                            <i class="mgc_arrow_right_up_fill text-3xl text-red-600"></i>
                        </div>
                    </div>
                    <p class="text-red-700 font-bold text-3xl truncate mt-auto pt-4">Rp. 20.000.000</p>
                </div>
            </div>
        </div>
    </div> --}}
</div>


<!-- table pajak -->
<div class="card mt-10 p-5">
    <div class="flex items-center gap-3">
        <h4 class="card-title">Data Pajak</h4>
        <input type="date" class="border border-gray-300 rounded-md p-2" id="from_date" name="from_date" value="{{ request()->get('from') ?? request()->get('from') }}">
        <span>To</span>
        <input type="date" disabled class="border border-gray-300 rounded-md p-2" id="to_date" name="to_date" value="{{ request()->get('to') ?? request()->get('to') }}">
        @if (request()->get('from') || request()->get('to'))
            <a href="/pajak/pph" class="btn bg-red-600 text-white">
                Reset
            </a>
        @endif
    </div>
    <div class="card-body">
        <div class="overflow-x-auto mt-5">
            <div class="min-w-full inline-block align-middle">
                <div class="border rounded-lg divide-y divide-gray-200 dark:border-gray-700 dark:divide-gray-700">
                    <div class="overflow-hidden p-5">
                        <table id="search-table">
                            <thead>
                                <tr>
                                    <th>
                                        <span class="flex items-center"> No </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center"> Nama Karyawan </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center"> Gaji Bruto (Rp) </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center"> PPH Terutang </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center"> Diterima </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $counter = 1; @endphp
                                @foreach($pajak_pph as $pph)
                                <tr class="pajak-row">
                                    <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white"> {{ $counter++}} </td>
                                    <td> {{ $pph->nm_karyawan }} </td>
                                    <td> {{ "Rp. ".number_format($pph->gaji_karyawan, 0, ".", ".") }} </td>
                                    <td> {{ "Rp. ".number_format($pph->pph_terutang, 0, ".", ".") }} </td>
                                    <td> {{ "Rp. ".number_format($pph->bersih_diterima, 0, ".", ".") }} </td>
                                </tr>
                                @php $counter++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="py-1 px-4">
                        <nav class="flex items-center space-x-2">
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end table -->
@endsection

@section('script')
{{-- @vite('resources/js/pages/charts-apex.js') --}}
@vite(['resources/js/pages/highlight.js'])
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
<script src="{{ asset('js/custom-js/pajak.js') }}" defer></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

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
            name: 'PPH',
            data: {{ Js::from($chart) }}
        }],
        colors: ['#037ffc'],
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Des'],
        },
        yaxis: {
            title: {
                text: 'Nominal',
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
                    const toRupiah = Intl.NumberFormat({
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
        document.querySelector("#column_chart"),
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

    document.getElementById('filter-jenis-pajak').addEventListener('change', function() {
        const selectedJenis = this.value.toLowerCase();
        document.querySelectorAll('#search-table .pajak-row').forEach(row => {
            const rowJenisPajak = row.getAttribute('data-jenis-pajak').toLowerCase();
            if (selectedJenis === "" || rowJenisPajak === selectedJenis) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
@endsection

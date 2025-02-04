@extends('layouts.vertical', ['title' => 'Pajak PPN', 'sub_title' => 'Pages', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('css')
<link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
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
    </div>
</div>


<!-- table pajak -->
<div class="card mt-10 p-5">
    <div class="card-header mb-5 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <h4 class="card-title">Data Pajak</h4>
            <input type="date" class="border border-gray-300 rounded-md p-2" onchange="filterByDate(this.value)" id="tanggal" name="tanggal" value="{{ request()->get('date') ?? request()->get('date') }}">
            @if (request()->get('date'))
                <a href="/pajak/ppn" class="btn bg-red-600 text-white">
                    Reset
                </a>
            @endif
        </div>
        <!-- Filter dropdown for Jenis Pajak -->
        <select id="filter-jenis-pajak" class="border border-gray-300 rounded-md py-1 px-3 text-gray-700 dark:text-gray-200">
            <option value="">Filter Jenis Pajak</option>
            <option value="Pajak Keluaran">Pajak Keluaran</option>
            <option value="Pajak Masukan">Pajak Masukan</option>
        </select>
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
                                        <span class="flex items-center"> Jenis Transaksi </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center"> Keterangan </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center"> Nilai Transaksi </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center"> Jenis Pajak </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center"> Nilai Pajak (PPN 11%) </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $counter = 1; @endphp
                                @foreach($pajak_ppn as $ppn)
                                <tr class="pajak-row" data-jenis-pajak="{{ $ppn->jenis_pajak }}">
                                    <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white"> {{ $counter++}} </td>
                                    <td> {{ $ppn->jenis_transaksi }} </td>
                                    <td> {{ $ppn->keterangan }} </td>
                                    <td> {{ "Rp. ".number_format($ppn->nilai_transaksi, 0, ".", ".") }} </td>
                                    @if ($ppn->jenis_pajak == 'Pajak Keluaran')
                                        <td>
                                            <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-green-500 text-white">{{ $ppn->jenis_pajak }}</span>
                                        </td>
                                    @else
                                        <td>
                                            <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-red-500 text-white">{{ $ppn->jenis_pajak }}</span>
                                        </td>
                                    @endif
                                    <td> {{ "Rp. ".number_format($ppn->saldo_pajak, 0, ".", ".") }} </td>
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
@vite('resources/js/pages/charts-apex.js')
@vite(['resources/js/pages/highlight.js'])
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
<script src="{{ asset('js/custom-js/pajak.js') }}" defer></script>
<script>
    function filterByDate(date) {
        window.location.href = `?date=${date}`;
    }

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

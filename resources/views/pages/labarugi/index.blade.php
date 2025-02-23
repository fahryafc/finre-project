@extends('layouts.vertical', ['title' => 'Laba Rugi'])

@section('css')
@vite(['node_modules/nice-select2/dist/css/nice-select2.css'])
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

<style>
    /* CSS untuk spinner */
.loader {
    border: 4px solid rgba(255, 255, 255, 0.3);
    border-top: 4px solid #3498db;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

</style>

@section('content')
<div class="grid lg grid-cols-1 gap-6">
    <div class="card p-5">
        <div class="card-header mb-5">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <h4 class="card-title">Laporan Laba Rugi</h4>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form id="salesForm" action="{{ route('labarugi.index') }}" method="GET" enctype="multipart/form-data">
            @csrf
                <div class="grid grid-cols-4 gap-4">
                    <div class="mb-3">
                        <label for="tanggal_mulai" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Mulai</label>
                        <input type="text" class="form-input" id="tanggal_mulai" name="tanggal_mulai" value="{{ request()->get('tanggal_mulai') ?? request()->get('tanggal_mulai') }}">
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_selesai" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Selesai</label>
                        <input type="text" class="form-input" id="tanggal_selesai" name="tanggal_selesai" id="datepicker-basic" value="{{ request()->get('tanggal_selesai') ?? request()->get('tanggal_selesai') }}">
                    </div>
                    <div class="mb-3">
                        <label for="tipe_periode" class="text-gray-800 text-sm font-medium inline-block mb-2">Filter Sesuai Periode</label>
                        <select id="tipe_periode" name="tipe_periode" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            @if (count($tipe_periode))
                                @foreach ($tipe_periode as $key => $option)
                                    <option value="{{$option['value']}}" @if(request()->get('tipe_periode')) @if(request()->get('tipe_periode') == $option['value']) selected @endif @endif>{{$option['text']}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="flex space-x-2">
                            <button class="btn bg-[#307487] text-white" type="submit">
                                Filter
                            </button>
                        </div>
                    </div>
                </div>
            <form>
            <table id="jurnal-table">
                <tbody>
                    {{-- Pendapatan --}}
                    <tr>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"><b>Pendapatan</b></th>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"></th>
                    </tr>
                    @php $total_pendapatan = 0; @endphp
                    @foreach($labarugi['pendapatans'] as $key => $value)
                    <tr>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $value['nama_akun'] }}</th>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ "Rp. ".number_format($value['total'], 0, ".", ".") }}</th>
                    </tr>
                    @php $total_pendapatan += $value['total']; @endphp
                    @endforeach
                    <tr>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"><b>Total Pendapatan</b></th>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"><b>{{ "Rp. ".number_format($total_pendapatan, 0, ".", ".") }}</b></th>
                    </tr>

                    {{-- Harga Pokok Penjualan --}}
                    <tr>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"><b>Harga Pokok Penjualan</b></th>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"></th>
                    </tr>
                    @php $total_hpp = 0; @endphp
                    @foreach($labarugi['hpps'] as $key => $value)
                    <tr>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $value['nama_akun'] }}</th>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ "Rp. ".number_format($value['total'], 0, ".", ".") }}</th>
                    </tr>
                    @php $total_hpp += $value['total']; @endphp
                    @endforeach
                    <tr>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"><b>Total Harga Pokok Penjualan</b></th>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"><b>{{ "Rp. ".number_format($total_hpp, 0, ".", ".") }}</b></th>
                    </tr>

                    {{-- Total Laba Kotor --}}
                    @php $total_laba_kotor = $total_pendapatan - $total_hpp; @endphp
                    <tr>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"><b>TOTAL LABA KOTOR</b></th>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"><b>{{ "Rp. ".number_format($total_laba_kotor, 0, ".", ".") }}</b></th>
                    </tr>

                    {{-- Beban Operasional --}}
                    <tr>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"><b>Beban Operasional</b></th>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"></th>
                    </tr>
                    @php $total_beban = 0; @endphp
                    @foreach($labarugi['bebans'] as $key => $value)
                    <tr>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $value['nama_akun'] }}</th>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ "Rp. ".number_format($value['total'], 0, ".", ".") }}</th>
                    </tr>
                    @php $total_beban += $value['total']; @endphp
                    @endforeach
                    <tr>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"><b>Total Beban Operasional</b></th>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"><b>{{ "Rp. ".number_format($total_beban, 0, ".", ".") }}</b></th>
                    </tr>

                    {{-- Total Laba Bersih --}}
                    @php $total_laba_bersih = $total_laba_kotor - $total_beban; @endphp
                    <tr>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"><b>LABA BERSIH OPERASIONAL</b></th>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"><b>{{ "Rp. ".number_format($total_laba_bersih, 0, ".", ".") }}</b></th>
                    </tr>

                    {{-- Pendapatan & Beban Lainnya --}}
                    <tr>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"><b>Pendapatan & Beban Lainnya</b></th>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"></th>
                    </tr>
                    <tr>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"><b>Total Pendapatan & Beban Lainnya</b></th>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"><b>{{ "Rp. ".number_format($labarugi['lains'], 0, ".", ".") }}</b></th>
                    </tr>

                    {{-- Laba/(Rugi) bersih --}}
                    @php $total_laba_rugi = $total_laba_bersih + $labarugi['lains']; @endphp
                    <tr>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"><b>LABA/(RUGI) BERSIH</b></th>
                        <th scope="col" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"><b>{{ "Rp. ".number_format($total_laba_rugi, 0, ".", ".") }}</b></th>
                    </tr>
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
<script src="{{ asset('js/custom-js/jurnal.js') }}" defer></script>
<script>
    if (document.getElementById("jurnal-table") && typeof simpleDatatables.DataTable !== 'undefined') {
        const dataTable = new simpleDatatables.DataTable("#jurnal-table", {
            paging: false,
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

    document.addEventListener('DOMContentLoaded', function () {
        const selectOption = document.getElementById('tipe_periode');

        // Event listener untuk select
        selectOption.addEventListener('change', function () {
            if (this.value == '1' || this.value == '2') {
                flatpickr("#tanggal_mulai", {
                    dateFormat: "d-m-Y",
                    defaultDate: "today"
                });

                flatpickr("#tanggal_selesai", {
                    dateFormat: "d-m-Y",
                    defaultDate: "today"
                });
            } else if(this.value == '3') {
                let today = new Date();
                let firstDay = new Date(today.setDate(today.getDate() - today.getDay() + 1)); // Monday
                let lastDay = new Date(today.setDate(firstDay.getDate() + 6)); // Sunday

                let formatDate = (date) => 
                    date.getDate().toString().padStart(2, '0') + '-' + 
                    (date.getMonth() + 1).toString().padStart(2, '0') + '-' + 
                    date.getFullYear();

                flatpickr("#tanggal_mulai", {
                    dateFormat: "d-m-Y",
                    defaultDate: formatDate(firstDay)
                });

                flatpickr("#tanggal_selesai", {
                    dateFormat: "d-m-Y",
                    defaultDate: formatDate(lastDay)
                });
            } else if(this.value == '4') {
                let today = new Date();
                let firstDay = new Date(today.getFullYear(), today.getMonth(), 1); // First day of the month
                let lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0); // Last day of the month

                let formatDate = (date) => 
                    date.getDate().toString().padStart(2, '0') + '-' + 
                    (date.getMonth() + 1).toString().padStart(2, '0') + '-' + 
                    date.getFullYear();

                flatpickr("#tanggal_mulai", {
                    dateFormat: "d-m-Y",
                    defaultDate: formatDate(firstDay)
                });

                flatpickr("#tanggal_selesai", {
                    dateFormat: "d-m-Y",
                    defaultDate: formatDate(lastDay)
                });
            } else if(this.value == '5') {
                let today = new Date();
                let firstDay = new Date(today.getFullYear(), 0, 1); // First day of the year (Jan 1)
                let lastDay = new Date(today.getFullYear(), 11, 31); // Last day of the year (Dec 31)

                let formatDate = (date) => 
                    date.getDate().toString().padStart(2, '0') + '-' + 
                    (date.getMonth() + 1).toString().padStart(2, '0') + '-' + 
                    date.getFullYear();

                flatpickr("#tanggal_mulai", {
                    dateFormat: "d-m-Y",
                    defaultDate: formatDate(firstDay)
                });

                flatpickr("#tanggal_selesai", {
                    dateFormat: "d-m-Y",
                    defaultDate: formatDate(lastDay)
                });
            }
        });

        // Trigger the event manually on page load
        selectOption.dispatchEvent(new Event('change'));
    });
</script>
@endsection
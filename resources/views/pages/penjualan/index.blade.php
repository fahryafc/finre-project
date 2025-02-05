@extends('layouts.vertical', ['title' => 'Penjualan', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

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
<div class="grid grid-rows-1 grid-flow-col gap-4">
    <div class="row-span-3">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Kategori Produk</h4>
            </div>
            <div class="p-6">
                <div id="pie_chart" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>
    <div class="row-span-2">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Overview Penjualan</h4>
            </div>
            <div class="p-6">

                <div id="spline_area_2" class="apex-charts" dir="ltr"></div>
            </div>
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
                        <a href="/penjualan" class="btn bg-red-600 text-white">
                            Reset
                        </a>
                    @endif
                </div>
                <!-- <button class="btn bg-[#307487] text-white" data-fc-target="modalTambahAkun" data-fc-type="modal" type="button"><i class="mgc_add_fill text-base me-4"></i>
                    Tambah Data
                </button> -->
                <a href="{{ route('penjualan.create') }}" class="btn bg-[#307487] text-white">
                    <i class="mgc_add_fill text-base me-4"></i>
                    Tambah Data
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="penjualan-table">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Nama Pelanggan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Total Harga</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Total Pajak</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Total Diskon</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Ongkir</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Piutang</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Pembayaran</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Total Pemasukan</th>
                        <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter = 1; @endphp
                    @foreach($penjualan as $p)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $counter }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{$p->nama_kontak}}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                            {{ date('d-m-Y', strtotime($p->tanggal)) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ "Rp. ".number_format($p->total_harga, 0, ".", ".") }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{"Rp.  ".number_format($p->total_pajak, 0, ".", ".")}}</td>

                        @if (!empty($p->diskon))
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{$p->total_diskon}}%</td>
                        @else
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">0</td>
                        @endif

                        @if (!empty($p->ongkir))
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{"Rp. ".number_format($p->ongkir, 0, ".", ".")}}</td>
                        @else
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">0</td>
                        @endif

                        @if (!empty($p->piutang))
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{"Rp. ".number_format($p->piutang, 0, ".", ".")}}</td>
                        @else
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">0</td>
                        @endif

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{$p->pembayaran}}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{"Rp. ".number_format($p->total_pemasukan, 0, ".", ".") }}</td>

                        @csrf
                        @method('DELETE')
                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                            <a href="{{ route('penjualan.destroy', $p->id_penjualan) }}" data-confirm-delete="true" class="btn rounded-full bg-danger/25 text-danger hover:bg-danger hover:text-white"><i class="mgc_delete_2_line"></i></a>
                            <a href="{{ route('penjualan.edit', $p->id_penjualan) }}" class="btn rounded-full bg-warning/25 text-warning hover:bg-warning hover:text-white"><i class="mgc_edit_2_line"></i></a>
                            <!-- <a href="{{ route('penjualan.edit', $p->id_penjualan) }}" class="btn rounded-full bg-info/25 text-info hover:bg-info hover:text-white"><i class="mgc_information_line"></i></a> -->
                            <button data-modal-target="modal-detail-penjualan" data-modal-toggle="modal-detail-penjualan" type="button" class="btn rounded-full bg-info/25 text-info hover:bg-info hover:text-white" data-id="{{ $p->id_penjualan }}">
                                <i class="mgc_information_line"></i>
                            </button>
                        </td>
                    </tr>
                    @php $counter++; @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!--Modal Detail Penjualan-->
<div id="modal-detail-penjualan" data-modal-backdrop="static" tabindex="-1" class="fixed top-0 left-[20%] z-50 hidden w-[60%] p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-4xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                    Detail Penjualan
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="modal-detail-penjualan">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">
                <table id="detail-penjualan-table">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Nama Pelanggan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Nama Produk</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Kategori Produk</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Satuan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Harga</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Kuantitas</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Pajak</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Diskon</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-4 md:p-5 space-x-3 rtl:space-x-reverse border-t border-gray-200 rounded-b dark:border-gray-600">
                <button data-modal-hide="modal-detail-penjualan" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">Close</button>
            </div>
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

    document.addEventListener('DOMContentLoaded', () => {
        const buttons = document.querySelectorAll('[data-modal-target="modal-detail-penjualan"]');
        const modalBody = document.querySelector('#detail-penjualan-table tbody');

        if (document.getElementById("detail-penjualan-table") && typeof simpleDatatables.DataTable !== 'undefined') {
            const dataTable = new simpleDatatables.DataTable("#detail-penjualan-table", {
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

        buttons.forEach(button => {
            button.addEventListener('click', async () => {
                const penjualanId = button.getAttribute('data-id');
                try {
                    const response = await fetch(`/penjualan/detail/${penjualanId}`);
                    const data = await response.json();

                    // Clear previous table rows
                    modalBody.innerHTML = '';

                    if (data && data.status === 'success') {
                        // Loop through each detail row and populate the table
                        data.detailPenjualan.forEach((detail, index) => {
                            const row = `
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">${index + 1}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">${detail.nama_kontak}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">${detail.produk}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">${detail.kategori_produk}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">${detail.satuan}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">Rp ${parseInt(detail.harga).toLocaleString()}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">${detail.kuantitas}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">Rp ${parseInt(detail.nominal_pajak).toLocaleString()}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">Rp ${parseInt(detail.nominal_diskon).toLocaleString()}</td>
                                </tr>
                            `;
                            modalBody.innerHTML += row;
                        });
                    } else {
                        modalBody.innerHTML = '<tr><td colspan="9" class="text-center text-gray-500">Detail tidak ditemukan</td></tr>';
                    }
                } catch (error) {
                    console.error('Error fetching detail:', error);
                    modalBody.innerHTML = '<tr><td colspan="9" class="text-center text-red-500">Terjadi kesalahan saat memuat detail</td></tr>';
                }
            });
        });
    });
</script>
@endsection

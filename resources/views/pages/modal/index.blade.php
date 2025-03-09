@extends('layouts.vertical', ['title' => 'Modal', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

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
<div class="grid grid-rows-1 grid-flow-col gap-4 mb-5">
    <div class="card">
        <div class="p-5">
            <div class="flex justify-between">
                <div class="w-20 h-20 rounded-full inline-flex items-center justify-center bg-green-100">
                    <i class="ti ti-package text-4xl text-green-500"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-gray-700 mt-1 text-2xl font-bold mb-5 dark:text-gray-300">{{ $modal->total() }}</h3>
                    <p class="text-gray-500 mb-1 truncate dark:text-gray-400">Investor</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="p-5">
            <div class="flex justify-between">
                <div class="w-20 h-20 rounded-full inline-flex items-center justify-center bg-yellow-100">
                    <i class="ti ti-package text-4xl text-yellow-500"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-gray-700 mt-1 text-2xl font-bold mb-5 dark:text-gray-300">Rp {{ number_format($jml_modal_disetor, 0, ',', '.') }}</h3>
                    <p class="text-gray-500 mb-1 truncate dark:text-gray-400">Modal Disetor</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="p-5">
            <div class="flex justify-between">
                <div class="w-20 h-20 rounded-full inline-flex items-center justify-center bg-primary/25 ">
                    <i class="ti ti-package text-4xl text-primary"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-gray-700 mt-1 text-2xl font-bold mb-5 dark:text-gray-300">Rp {{ number_format($jml_penarikan_deviden, 0, ',', '.') }}</h3>
                    <p class="text-gray-500 mb-1 truncate dark:text-gray-400">Penarikan Deviden</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid lg grid-cols-1 gap-6 mt-5">
    <div class="card">
        <div class="card-header">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <h4 class="card-title">Data Modal</h4>
                    <select name="filter" id="filter" class="border border-gray-300 rounded-md py-1 px-3 text-gray-700 dark:text-gray-200">
                        <option value="semua" @selected(!request()->get('filter'))>Semua</option>
                        <option value="Penyetoran Modal" @selected(request()->get('filter') == 'Penyetoran Modal')>Penyetoran Modal</option>
                        <option value="Penarikan Dividen" @selected(request()->get('filter') == 'Penarikan Dividen')>Penarikan Dividen</option>
                    </select>
                </div>
                <div class="flex space-x-2">
                    <button class="btn bg-primary text-white" data-fc-target="tambahModal" data-fc-type="modal" type="button">
                        <i class="mgc_add_fill text-base me-1"></i> Tambah Modal
                    </button>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <div class="min-w-full inline-block align-middle">
                    <div class="border rounded-lg divide-y divide-gray-200 dark:border-gray-700 dark:divide-gray-700">
                        <div class="py-3 px-4">
                            <div class="relative max-w-xs">
                                <label for="table-with-pagination-search" class="sr-only">Search</label>
                                <input type="text" name="table-with-pagination-search" id="table-with-pagination-search"
                                    class="form-input ps-11" placeholder="Search for items">
                                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                    <svg class="h-3.5 w-3.5 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" viewBox="0 0 16 16">
                                        <path
                                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z">
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaksi</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama/Jenis</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                        <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @php $counter = 1; @endphp
                                    @foreach($modal as $key)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> {{ $counter + ($modal->currentPage() - 1) * $modal->perPage() }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ date('d-m-Y', strtotime($key->tanggal)) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $key->jns_transaksi }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $key->nama_badan }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                {{ "Rp. ".number_format($key->nominal, 0, ".", ".") }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $key->keterangan }}</td>
                                            @csrf
                                            @method('DELETE')
                                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                                <a href="{{ route('modal.destroy', $key->id_modal) }}"
                                                    data-confirm-delete="true"
                                                    class="btn rounded-full bg-danger/25 text-danger hover:bg-danger hover:text-white"><i
                                                        class="mgc_delete_2_line"></i></a>
                                                <button type="button"
                                                    class="btn rounded-full bg-warning/25 text-warning hover:bg-warning hover:text-white"
                                                    data-fc-target="modalEditAkun{{$key->id_modal}}" data-fc-type="modal"><i
                                                        class="mgc_edit_2_line"></i></button>
                                                <button data-modal-target="modal-detail-jurnal" data-modal-toggle="modal-detail-jurnal" type="button" class="btn rounded-full bg-success/25 text-success hover:bg-success hover:text-white" data-id="{{ $key->id_modal }}">
                                                    <i class="ti ti-credit-card-pay text-base me-1"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @php $counter++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="py-1 px-4">
                            <nav class="flex items-center space-x-2">
                                {{ $modal->links('pagination::tailwind') }}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Modal -->
<div id="tambahModal" class="w-full h-auto max-h-[70vh] mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
    <div class="sm:max-w-2xl fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                Tambah Modal
            </h3>
            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <form action="{{ route('modal.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="px-4 py-4 overflow-y-auto max-h-[60vh]">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Kolom 1 -->
                    <div>
                        <div class="mb-3">
                            <label for="tanggal" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal</label>
                            <input type="text" class="form-input" name="tanggal" id="datepicker-basic">
                        </div>
                        <div class="mb-3">
                            <label for="jns_transaksi" class="text-gray-800 text-sm font-medium inline-block mb-2">Jenis Transaksi</label>
                            <select id="jns_transaksi" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="jns_transaksi">
                                <option value="" selected>-- Pilih Jenis Transaksi --</option>
                                <option value="Penyetoran Modal">Penyetoran Modal</option>
                                <option value="Penarikan Dividen">Penarikan Dividen</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nama_badan" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama/Badan</label>
                            <select id="nama_badan" name="nama_badan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Nama/Badan --</option>
                                @foreach ( $pemodal as $investor)
                                <option value="{{ $investor->nama_kontak }}">{{ $investor->nama_kontak }}</option>
                                @endforeach
                                <option value="tambah_badan" data-fc-target="tambahKontak" data-fc-type="modal">+ Tambah
                                    Kontak</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nominal" class="text-gray-800 text-sm font-medium inline-block mb-2">Jumlah / Nominal Modal</label>
                            <input type="text" class="form-input" id="nominal" name="nominal" aria-describedby="nominal" placeholder="Masukan Nominal Modal">
                        </div>
                    </div>
                    <!-- Kolom 2 -->
                    <div>
                        <div id="masukAkunContainer" class="mb-3 hidden">
                            <label for="masuk_akun" class="text-gray-800 text-sm font-medium inline-block mb-2">Masuk Akun</label>
                            <select id="masuk_akun" name="masuk_akun" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Akun --</option>
                                @foreach ( $kas_bank as $a )
                                <option value="{{ $a->kode_akun }}">
                                    {{ $a->nama_akun }} - {{ $a->kode_akun }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div id="creditAkunContainer" class="mb-3 hidden">
                            <label for="credit_akun" class="text-gray-800 text-sm font-medium inline-block mb-2">Credit Dari Akun</label>
                            <select id="credit_akun" name="credit_akun" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Akun --</option>
                                @foreach ( $kas_bank as $a )
                                <option value="{{ $a->kode_akun }}">
                                    {{ $a->nama_akun }} - {{ $a->kode_akun }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="text-gray-800 text-sm font-medium inline-block mb-2">Keterangan</label>
                            <textarea id="keterangan" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukan Keterangan" name="keterangan"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                <button class="btn dark:text-gray-200 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                </button>
                <button class="btn bg-primary text-white" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>
<!--Modal Detail Jurnal-->
<div id="modal-detail-jurnal" data-modal-backdrop="static" tabindex="-1" class="fixed top-0 left-[20%] z-50 hidden w-[60%] p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-4xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                    Detail Jurnal
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="modal-detail-jurnal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">
                <table id="detail-jurnal-table">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Kode Akun</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Nama Akun</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Debit</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Kredit</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>

                    </tfoot>
                </table>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-4 md:p-5 space-x-3 rtl:space-x-reverse border-t border-gray-200 rounded-b dark:border-gray-600">
                <button data-modal-hide="modal-detail-jurnal" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">Close</button>
            </div>
        </div>
    </div>
</div>

<x-modals.kontak.tambah-kontak />
<!-- end modal -->
@endsection

@section('script')
@vite('resources/js/pages/charts-apex.js')
@vite(['resources/js/pages/table-gridjs.js'])
@vite(['resources/js/pages/highlight.js', 'resources/js/pages/form-select.js'])
@vite(['resources/js/pages/highlight.js', 'resources/js/pages/form-flatpickr.js', 'resources/js/pages/form-color-pickr.js'])
@vite(['resources/js/pages/extended-sweetalert.js'])
@vite(['resources/js/pages/highlight.js'])
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const jnsTransaksi = document.getElementById('jns_transaksi');
        const masukAkunContainer = document.getElementById('masukAkunContainer');
        const creditAkunContainer = document.getElementById('creditAkunContainer');
        const tambahModal = document.getElementById('tambahModal');
        const nominal = document.getElementById('nominal');

        document.getElementById('filter').addEventListener('change', function() {
            this.value
            if (this.value === 'semua') {
                window.location.href = `/modal`
                return;
            }

            window.location.href = `?filter=${this.value}`
        })

        // Reset form saat modal dibuka
        function resetForm() {
            jnsTransaksi.value = ""; // Reset jenis transaksi ke default
            masukAkunContainer.classList.add('hidden'); // Sembunyikan Masuk Akun
            masukAkunContainer.value = ""; // Reset ke default
            creditAkunContainer.classList.add('hidden'); // Sembunyikan Credit Akun
            creditAkunContainer.value = ""; // Reset ke default
        }

        function formatRupiah(angka, prefix) {
            let numberString = angka.replace(/[^,\d]/g, "").toString(),
                split = numberString.split(","),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? "." : "";
                rupiah += separator + ribuan.join(".");
            }

            rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? "Rp " + rupiah : "");
        }

        nominal.addEventListener('input', function (e) {
            let value = e.target.value.replace(/[^,\d]/g, "").toString();
            e.target.value = formatRupiah(value, "Rp ");
        });

        // Event listener untuk modal open
        tambahModal.addEventListener('fc.modal.open', resetForm);
        tambahModal.addEventListener('fc.modal.close', resetForm);

        // Tambahkan event listener untuk mengubah tampilan select option
        jnsTransaksi.addEventListener('change', function() {
            toggleSelectOptions(this.value);
        });

        function toggleSelectOptions(value) {
            // Reset: sembunyikan semua select option
            masukAkunContainer.classList.add('hidden');
            creditAkunContainer.classList.add('hidden');

            // Tampilkan select option yang relevan berdasarkan jenis transaksi
            if (value === 'Penyetoran Modal') {
                masukAkunContainer.classList.remove('hidden');
            } else if (value === 'Penarikan Dividen') {
                creditAkunContainer.classList.remove('hidden');
            }
        }
    });

    const buttonsJurnal = document.querySelectorAll('[data-modal-target="modal-detail-jurnal"]');
    const modalBodyJurnal = document.querySelector('#detail-jurnal-table tbody');
    const modalFootJurnal = document.querySelector('#detail-jurnal-table tfoot');

    if (document.getElementById("detail-jurnal-table") && typeof simpleDatatables.DataTable !== 'undefined') {
        const dataTable = new simpleDatatables.DataTable("#detail-jurnal-table", {
            paging: true,
            perPage: 5,
            perPageSelect: [5, 10, 15, 20, 25],
            sortable: false,
            labels: {
                // perPage: "",
                // noRows: "Tidak ada data",
                // info: "Menampilkan {start} sampai {end} dari {rows} entri"
            }
        });
    }

    buttonsJurnal.forEach(button => {
        button.addEventListener('click', async () => {
            const modalId = button.getAttribute('data-id');
            const code = "{!! \App\Models\Modal::CODE_JURNAL !!}";
            try {
                const response = await fetch(`/jurnal/detail/${modalId}/${code}`);
                const data = await response.json();

                // Clear previous table rows
                modalBodyJurnal.innerHTML = '';
                modalFootJurnal.innerHTML = '';

                if (data && data.status === 'success') {
                    // Loop through each detail row and populate the table
                    var totalDebit = 0;
                    var totalKredit = 0;
                    data.detailJurnal.forEach((detail, index) => {
                        const row = `
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">${detail.kode_akun}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">${detail.nama_akun}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">Rp ${parseInt(detail.debit).toLocaleString()}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">Rp ${parseInt(detail.kredit).toLocaleString()}</td>
                            </tr>
                        `;
                        totalDebit += parseInt(detail.debit);
                        totalKredit += parseInt(detail.kredit);
                        modalBodyJurnal.innerHTML += row;
                    });

                    const rowFoot = `
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200" colspan="2"><b>Total</b></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><b>Rp ${parseInt(totalDebit).toLocaleString()}</b></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><b>Rp ${parseInt(totalKredit).toLocaleString()}</b></td>
                            </tr>
                        `;
                    modalFootJurnal.innerHTML += rowFoot;
                } else {
                    modalBodyJurnal.innerHTML = '<tr><td colspan="9" class="text-center text-gray-500">Detail tidak ditemukan</td></tr>';
                }
            } catch (error) {
                console.error('Error fetching detail:', error);
                modalBodyJurnal.innerHTML = '<tr><td colspan="9" class="text-center text-red-500">Terjadi kesalahan saat memuat detail</td></tr>';
            }
        });
    });

    const selectOptionKontak = document.getElementById('nama_badan');
        selectOptionKontak.addEventListener('change', function() {
            if (this.value === 'tambah_badan') {
                // Buka modal dan overlay
                document.querySelector('[data-fc-target="tambahKontak"]').click();
            }
        });
</script>
@endsection

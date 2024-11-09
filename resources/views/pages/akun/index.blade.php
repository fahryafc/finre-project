@extends('layouts.vertical', ['title' => 'Select', 'sub_title' => 'Forms', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('css')
@vite(['node_modules/nice-select2/dist/css/nice-select2.css'])
@endsection

@section('content')
<div class="grid lg grid-cols-1 gap-6">
    <div class="card">
        <div class="card-header">
            <div class="flex justify-between items-center">
                <h4 class="card-title">Data Akun</h4>
                <button class="btn bg-primary text-white" data-fc-target="modalTambahAkun" data-fc-type="modal" type="button"><i class="mgc_add_fill text-base me-4"></i>
                    Tambah Data
                </button>
            </div>
        </div>
        <div class="p-6">
            <!-- <div id="tabel-penjualan"></div> -->
            <div class="overflow-x-auto">
                <div class="min-w-full inline-block align-middle">
                    <div class="border rounded-lg divide-y divide-gray-200 dark:border-gray-700 dark:divide-gray-700">
                        <div class="py-3 px-4">
                            <div class="relative max-w-xs">
                                <label for="table-with-pagination-search" class="sr-only">Search</label>
                                <input type="text" name="table-with-pagination-search" id="table-with-pagination-search" class="form-input ps-11" placeholder="Search for items">
                                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                    <svg class="h-3.5 w-3.5 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z">
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Akun</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @php $counter = 1; @endphp
                                    @foreach($akun as $key)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $counter + ($akun->currentPage() - 1) * $akun->perPage() }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{$key->kode_akun}}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{$key->nama_akun}}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{$key->kategori_akun}}</td>

                                        @if (!empty($key->saldo))
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ "Rp. ".number_format($key->saldo, 0, ".", ".") }}</td>
                                        @else
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">Rp. 0</td>
                                        @endif
                                    </tr>
                                    @php $counter++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="py-1 px-4">
                            <nav class="flex items-center space-x-2">
                                {{ $akun->links('pagination::tailwind') }}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add -->
<div id="modalTambahAkun" class="w-full h-auto max-h-[70vh] mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
    <div class="sm:max-w-2xl fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                Tambah Data Akun
            </h3>
            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <form action="{{ route('akun.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="px-4 py-4 overflow-y-auto max-h-[60vh]">
                <div class="grid gap-4">
                    <!-- col 1 -->
                    <div>
                        <div class="mb-3">
                            <label for="nama_akun" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Akun</label>
                            <input type="text" class="form-input" id="nama_akun" name="nama_akun" aria-describedby="nama_akun" placeholder="Masukan Nama Akun">
                        </div>
                        <div class="mb-3">
                            <label for="kode_akun" class="text-gray-800 text-sm font-medium inline-block mb-2">Kode Akun</label>
                            <input type="text" class="form-input" id="kode_akun" name="kode_akun" aria-describedby="kode_akun" placeholder="Masukan Kode Akun">
                        </div>
                        <div class="mb-3">
                            <label for="kategori_akun" class="text-gray-800 text-sm font-medium inline-block mb-2">Kategori Akun</label>
                            <select id="kategori_akun" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="kategori_akun">
                                <option value="">-- Pilih Kategori Akun --</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="subakun" class="text-gray-800 text-sm font-medium inline-block mb-2">Sub Kategori Akun</label>
                            <select id="subakun" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="kategori_akun">
                                <option value="">-- Pilih Sub Kategori Akun --</option>
                            </select>
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
<!-- end modal -->
@endsection

@section('script')
@vite(['resources/js/pages/highlight.js', 'resources/js/pages/form-select.js'])
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Muat data kategori akun saat modal dibuka
        $.ajax({
            url: "{{ route('get-kategori-akun') }}",
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // console.log($('#kategori_akun')[0].selectize);

                let kategoriSelect = $('#kategori_akun');
                kategoriSelect.empty().append('<option value="">-- Pilih Kategori Akun --</option>');

                response.forEach(function(kategori) {
                    kategoriSelect.append('<option value="' + kategori.id_kategori_akun + '">' + kategori.nama_kategori + '</option>');
                });
            },
            error: function() {
                alert('Gagal memuat kategori akun.');
            }
        });

        // Muat sub akun berdasarkan kategori yang dipilih
        $('#kategori_akun').on('change', function() {
            let kategoriId = $(this).val();

            if (kategoriId) {
                $.ajax({
                    url: "{{ route('get-subakun-by-kategori') }}",
                    type: 'GET',
                    data: {
                        id_kategori: kategoriId
                    },
                    dataType: 'json',
                    success: function(response) {
                        let subakunSelect = $('#subakun');
                        subakunSelect.empty().append('<option value="">-- Pilih Sub Kategori Akun --</option>');

                        response.forEach(function(subakun) {
                            subakunSelect.append('<option value="' + subakun.id_subakun + '">' + subakun.nama_subakun + '</option>');
                        });
                    },
                    error: function() {
                        alert('Gagal memuat sub akun.');
                    }
                });
            } else {
                $('#subakun').empty().append('<option value="">-- Pilih Sub Kategori Akun --</option>');
            }
        });
    });
</script>
@endsection
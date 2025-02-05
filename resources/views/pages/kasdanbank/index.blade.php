@extends('layouts.vertical', ['title' => 'Kas & Bank', 'sub_title' => 'Pages', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Card Presentasi Kas & Bank -->
    <div class="card-wrapper">
        <div class="card h-full flex flex-col bg-white">
            <div class="p-6 flex-grow flex flex-col justify-center">
                <h4 class="card-title mb-4">Presentasi Kas & Bank</h4>
                <div id="pie_chart" class="apex-charts my-4 flex-grow flex items-center justify-center">
                </div>
            </div>
        </div>
    </div>
    <!-- Wrapper Uang Masuk dan Uang Keluar -->
    <div class="grid grid-rows-2 gap-6">
        <!-- Card Uang Masuk -->
        <div class="card-wrapper">
            <div class="card h-full flex flex-col bg-green-100">
                <div class="p-6 flex-grow flex flex-col justify-between relative">
                    <div class="flex justify-between items-start">
                        <h3 class="text-green-800 text-xl font-bold mt-2">Uang Masuk</h3>
                        <div class="w-16 h-16 rounded-full flex items-center justify-center bg-green-200">
                            <i class="mgc_arrow_left_down_fill text-3xl text-green-600"></i>
                        </div>
                    </div>
                    @php
                        $total_uang_masuk = 0;
                        $total_uang_keluar = 0;
                    @endphp
                    @foreach($kas_bank as $key)
                        @php
                            $total_uang_masuk += $key->total_pemasukan;
                            $total_uang_keluar += $key->uang_keluar;
                        @endphp
                    @endforeach
                    <p class="text-green-700 font-bold text-3xl truncate mt-auto pt-4">{{ 'Rp. ' . number_format($total_uang_masuk, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <!-- Card Uang Keluar -->
        <div class="card-wrapper">
            <div class="card h-full flex flex-col bg-red-100">
                <div class="p-6 flex-grow flex flex-col justify-between relative">
                    <div class="flex justify-between items-start">
                        <h3 class="text-red-800 text-xl font-bold mt-2">Uang Keluar</h3>
                        <div class="w-16 h-16 rounded-full flex items-center justify-center bg-red-200">
                            <i class="mgc_arrow_right_up_fill text-3xl text-red-600"></i>
                        </div>
                    </div>
                    <p class="text-red-700 font-bold text-3xl truncate mt-auto pt-4">{{ 'Rp. ' . number_format($total_uang_keluar, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- table kas & Bank -->
<div class="card mt-10 p-5">
    <div class="card-header">
        <div class="flex justify-between items-center">
            <h4 class="card-title">Kas & Bank</h4>
            <button id="tambahKasBank" class="btn bg-primary text-white" data-fc-target="modalKasBank" data-fc-type="modal" type="button"><i class="mgc_add_fill text-base me-4"></i>
                Tambah Kas & Bank
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="overflow-x-auto mt-5">
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
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kas & Bank</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Saldo Awal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Uang Masuk</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Uang Keluar</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Saldo Akhir</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700" id="tabelHutang">
                                @php $counter = 1; @endphp
                                @foreach($kas_bank as $key)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $counter + ($kas_bank->currentPage() - 1) * $kas_bank->perPage() }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $key->nama_akun }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $key->kode_akun }}</td>

                                        @if (!empty($key->saldo))
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ "Rp. ".number_format($key->saldo, 0, ".", ".") }}</td>
                                        @else
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">0</td>
                                        @endif

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ "Rp. ".number_format($key->total_pemasukan ?? 0, 0, ".", ".") }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">Rp. 0</td>

                                        <!-- Perhitungan Saldo Akhir -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">Rp. 0</td>
                                        @csrf
                                        @method('DELETE')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('kasdanbank.destroy', $key->id_kas_bank) }}" data-confirm-delete="true" class="btn rounded-full bg-danger/25 text-danger hover:bg-danger hover:text-white"><i class="mgc_delete_2_line"></i></a>
                                            <button type="button" data-id="{{ $key->id_kas_bank }}" class="edit btn rounded-full bg-warning/25 text-warning hover:bg-warning hover:text-white" data-fc-target="modalKasBank" data-fc-type="modal" data-fc-type="modal"><i class="mgc_edit_2_line"></i></button>
                                        </td>
                                    </tr>
                                    @php $counter++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="py-1 px-4">
                        <nav class="flex items-center space-x-2">
                            {{ $kas_bank->links('pagination::tailwind') }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end table -->

<!-- Modal Add -->
<div id="modalKasBank" class="w-full h-auto max-h-[70vh] mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
    <div class="sm:max-w-2xl fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                Tambah Kas & Bank
            </h3>
            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <form action="{{ route('kasdanbank.store') }}" method="POST" id="formKasBank" enctype="multipart/form-data">
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
                                @foreach ( $kategoriAkun as $akun)
                                <option value="{{ $akun->nama_kategori }}">{{ $akun->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="subakun" class="text-gray-800 text-sm font-medium inline-block mb-2">Sub Kategori Akun</label>
                            <select id="subakun" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="subakun">
                                <option value="">-- Pilih Sub Kategori Akun --</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                <button type="button" class="btn dark:text-gray-200 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                </button>
                <button class="btn bg-primary text-white" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>
<!-- end modal -->
@endsection

@section('script')
@vite('resources/js/pages/charts-apex.js')
@vite(['resources/js/pages/highlight.js'])
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/custom-js/kasbank.js') }}" defer></script>
<script>
    $(document).ready(function() {
        $('.edit').on('click', async function() {
            const id = $(this).data('id');
            const subakunSelect = document.getElementById('subakun');
            $("#formKasBank").prepend('<input type="hidden" name="_method" id="method" value="PUT">');
            $("#formKasBank").attr('action', `/kasdanbank/${id}`);

            const res = await fetch(`/kasdanbank/${id}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': @json(csrf_token())
                }
            });
            const result = await res.json();
            const { data } = result;

            $('#nama_akun').val(data.nama_akun);
            $('#kode_akun').val(data.kode_akun);
            $('#kategori_akun').val(data.kategori_akun);

            const kategoriTerpilih = data.kategori_akun;
            // Hapus opsi lama
            subakunSelect.innerHTML = '<option value="">-- Pilih Sub Kategori Akun --</option>';

            if (kategoriTerpilih) {
                const res = await fetch(`/get-subkategori?kategori=${kategoriTerpilih}`)
                const result = await res.json();
                result.forEach(sub => {
                    const option = document.createElement('option');
                    option.value = sub.subakun; // Ubah sesuai field
                    option.textContent = sub.subakun; // Ubah sesuai field
                    subakunSelect.appendChild(option);
                    if (sub.subakun === data.subakun) {
                        option.selected = true;
                    }
                });
            }
        })

        $('#tambahKasBank').on('click', function() {
            const subakunSelect = document.getElementById('subakun');
            // Hapus opsi lama
            subakunSelect.innerHTML = '<option value="">-- Pilih Sub Kategori Akun --</option>';

            $("#formKasBank").attr('action', `{{ route('kasdanbank.store') }}`);
            $("#method").remove();
            $('#nama_akun').val('');
            $('#kode_akun').val('');
            $('#kategori_akun').val('');
            $('#subakun').val('');
        })
    })
</script>
@endsection

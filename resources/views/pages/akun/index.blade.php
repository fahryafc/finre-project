@extends('layouts.vertical', ['title' => 'Akun'])

@section('css')
@vite(['node_modules/nice-select2/dist/css/nice-select2.css'])
<link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="grid lg grid-cols-1 gap-6">
    <div class="card p-5">
        <div class="card-header">
            <div class="flex justify-between items-center">
                <h4 class="card-title">Data Akun</h4>
                <button id="tambahAkun" class="btn bg-primary text-white" data-fc-target="modalTambahAkun" data-fc-type="modal" type="button"><i class="mgc_add_fill text-base me-4"></i>
                    Tambah Data
                </button>
            </div>
        </div>
        <div class="card-body">
            <table id="akun-table">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Akun</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Saldo</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter = 1; @endphp
                    @foreach($akun as $key)
                        <tr id="akun-{{ $key->id_akun }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $counter }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{$key->kode_akun}}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{$key->nama_akun}}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{$key->kategori_akun}}</td>

                            @php
                                $saldo = $key->saldo;
                            @endphp
                            @if (!empty($saldo))
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ "Rp. ".number_format($saldo, 0, ".", ".") }}</td>
                            @else
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">Rp. 0</td>
                            @endif
                            @csrf
                            @method('DELETE')
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                {{-- @if ($key->saldo < 1)
                                    <a href="{{ route('akun.destroy', $key->id_akun) }}" data-confirm-delete="true" class="btn rounded-full bg-danger/25 text-danger hover:bg-danger hover:text-white"><i class="mgc_delete_2_line"></i></a>
                                @endif --}}
                                <button type="button" data-id="{{ $key->id_akun }}" data-fc-target="modalTambahAkun" data-fc-type="modal" class="edit btn rounded-full bg-warning/25 text-warning hover:bg-warning hover:text-white"><i class="mgc_edit_2_line"></i></button>
                            </td>
                        </tr>
                        @php $counter++; @endphp
                    @endforeach
                </tbody>
            </table>
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
        <form action="{{ route('akun.store') }}" id="formAkun" method="POST" enctype="multipart/form-data">
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
                            <select id="subakun" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="subakun">
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
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
<script>
    if (document.getElementById("akun-table") && typeof simpleDatatables.DataTable !== 'undefined') {
        const dataTable = new simpleDatatables.DataTable("#akun-table", {
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
        $('#kategori_akun').on('change', async function() {
            let kategoriId = $(this).val();
            const subakunSelect = document.getElementById('subakun');
            subakunSelect.innerHTML = '<option value="">-- Pilih Sub Kategori Akun --</option>';

            if (kategoriId) {
                const res = await fetch(`/get-subkategori?kategori=${kategoriId}`)
                const result = await res.json();

                result.forEach(sub => {
                    const option = document.createElement('option');
                    option.value = sub.subakun; // Ubah sesuai field
                    option.textContent = sub.subakun; // Ubah sesuai field
                    subakunSelect.appendChild(option);
                });
            } else {
                $('#subakun').empty().append('<option value="">-- Pilih Sub Kategori Akun --</option>');
            }
        });

        $(document).on('click', '.edit', async function() {
            const id = $(this).attr('data-id');
            let kategoriTerpilih
            const subakunSelect = document.getElementById('subakun');
            const kategoriSelect = $('#kategori_akun');
            $("#formAkun").prepend('<input type="hidden" name="_method" id="method" value="PUT">');
            $("#formAkun").attr('action', `/akun/${id}`);

            const fetchAkun = await fetch(`/akun/${id}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': @json(csrf_token())
                }
            });
            const resultAkun = await fetchAkun.json();
            const { data } = resultAkun;

            $('#formAkun')[0].reset();
            $('#nama_akun').val(data.nama_akun);
            $('#kode_akun').val(data.kode_akun);

            const fetchKategoriAkun = await fetch(`/get-kategori-akun`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': @json(csrf_token())
                }
            });
            const resultKategoriAkun = await fetchKategoriAkun.json();

            kategoriSelect.empty().append('<option value="">-- Pilih Kategori Akun --</option>');
            subakunSelect.innerHTML = '<option value="">-- Pilih Sub Kategori Akun --</option>';

            resultKategoriAkun.forEach(kategori => {
                if (kategori.id_kategori_akun == data.id_kategori_akun) {
                    kategoriTerpilih = data.kategori_akun
                    kategoriSelect.append('<option value="' + kategori.nama_kategori + '" selected>' + kategori.nama_kategori + '</option>');
                } else {
                    kategoriSelect.append('<option value="' + kategori.nama_kategori + '">' + kategori.nama_kategori + '</option>');
                }
            });

            if (kategoriTerpilih) {
                const res = await fetch(`/get-subkategori?kategori=${kategoriTerpilih}`)
                const result = await res.json();
                console.log(result)
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

        $('#tambahAkun').on('click', function() {
            const subakunSelect = document.getElementById('subakun');
            // Hapus opsi lama
            subakunSelect.innerHTML = '<option value="">-- Pilih Sub Kategori Akun --</option>';

            $("#formAkun").attr('action', `{{ route('akun.store') }}`);
            $("#method").remove();
            $('#formAkun')[0].reset();
        })
    });
</script>
@endsection

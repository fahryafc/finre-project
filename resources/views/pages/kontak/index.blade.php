@extends('layouts.vertical', ['title' => 'Kontak', 'sub_title' => 'Pages', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
<div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-5">
    <div class="card">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 rounded-full inline-flex items-center justify-center bg-blue-100">
                        <i class="ti ti-users text-3xl text-blue-600"></i>
                    </div>
                    <p class="text-gray-500 mt-2 text-sm dark:text-gray-400">Total Kontak</p>
                </div>
                <div class="flex-grow text-right">
                    <h3 class="text-gray-700 mt-1 text-3xl font-bold dark:text-gray-300">{{ $totals->total_kontak }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 rounded-full inline-flex items-center justify-center bg-green-100">
                        <i class="ti ti-brand-appgallery text-3xl text-green-600"></i>
                    </div>
                    <p class="text-gray-500 mt-2 text-sm dark:text-gray-400">Total Pelanggan</p>
                </div>
                <div class="flex-grow text-right">
                    <h3 class="text-gray-700 mt-1 text-3xl font-bold dark:text-gray-300">{{ $totals->total_pelanggan }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 rounded-full inline-flex items-center justify-center bg-yellow-100">
                        <i class="ti ti-briefcase text-3xl text-yellow-600"></i>
                    </div>
                    <p class="text-gray-500 mt-2 text-sm dark:text-gray-400">Total Karyawan</p>
                </div>
                <div class="flex-grow text-right">
                    <h3 class="text-gray-700 mt-1 text-3xl font-bold dark:text-gray-300">{{ $totals->total_karyawan }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 rounded-full inline-flex items-center justify-center bg-orange-100">
                        <i class="ti ti-building-warehouse text-3xl text-orange-600"></i>
                    </div>
                    <p class="text-gray-500 mt-2 text-sm dark:text-gray-400">Total Vendor</p>
                </div>
                <div class="flex-grow text-right">
                    <h3 class="text-gray-700 mt-1 text-3xl font-bold dark:text-gray-300">{{ $totals->total_vendor }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 rounded-full inline-flex items-center justify-center bg-purple-100">
                        <i class="ti ti-layers-subtract text-3xl text-purple-600"></i>
                    </div>
                    <p class="text-gray-500 mt-2 text-sm dark:text-gray-400">Total Lainnya</p>
                </div>
                <div class="flex-grow text-right">
                    <h3 class="text-gray-700 mt-1 text-3xl font-bold dark:text-gray-300">{{ $totals->total_lainnya }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid lg grid-cols-1 gap-6 mt-5">
    <div class="card">
        <div class="card-header">
            <div class="flex justify-between items-center">
                <h4 class="card-title">Data Kontak</h4>
                <div class="flex space-x-2">
                    <button data-fc-type="dropdown" type="button" class="py-2 px-3 inline-flex bg-success text-white justify-center items-center text-sm gap-2 rounded-md font-medium shadow-sm align-middle transition-all">
                        Export to <i class="ti ti-printer text-base me-1"></i>
                    </button>

                    <div class="hidden fc-dropdown-open:opacity-100 opacity-0 z-50 transition-all duration-300 bg-white border shadow-md rounded-lg p-2 dark:bg-slate-800 dark:border-slate-700">
                        <a class="flex items-center py-2 px-3 rounded-md text-sm text-gray-800 hover:bg-[#307487] hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="{{ route('export.kontak') }}">
                            Excel
                        </a>
                        <a class="flex items-center py-2 px-3 rounded-md text-sm text-gray-800 hover:bg-[#307487] hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="{{ route('export.kontak.pdf') }}">
                            PDF
                        </a>
                    </div>
                    <button class="btn bg-[#307487] text-white" data-fc-target="tambahKontak" data-fc-type="modal" type="button">
                        <i class="mgc_add_fill text-base me-1"></i> Tambah Kontak
                    </button>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <div class="min-w-full inline-block align-middle">
                    <div class="border rounded-lg divide-y divide-gray-200 dark:border-gray-700 dark:divide-gray-700">
                        <div class="py-3 px-4 items-center">
                            <div class="relative max-w-xs">
                                <label for="table-with-pagination-search" class="sr-only">Search</label>
                                <input type="text" name="table-with-pagination-search" id="table-with-pagination-search"
                                    class="form-input ps-11" placeholder="Search for items">
                                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                    <svg class="h-3.5 w-3.5 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" viewBox="0 0 16 16">
                                        <path
                                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">No</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Nama</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Email</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">No Handphone</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Perusahaan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Kategori</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Alamat</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @php $counter = 1; @endphp
                                    @foreach($kontak as $kontaks)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $counter + ($kontak->currentPage() - 1) * $kontak->perPage() }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                            {{ $kontaks->nama_kontak }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                            {{ $kontaks->email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                            {{ $kontaks->no_hp }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                            {{ $kontaks->nm_perusahaan }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                            {{ $kontaks->jenis_kontak }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200">
                                            {{ $kontaks->alamat }}
                                        </td>
                                        @csrf
                                        @method('DELETE')
                                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                            <a href="{{ route('kontak.destroy', $kontaks->id_kontak) }}"
                                                data-confirm-delete="true"
                                                class="btn rounded-full bg-danger/25 text-danger hover:bg-danger hover:text-white"><i
                                                    class="mgc_delete_2_line"></i></a>
                                            <button type="button"
                                                class="btn rounded-full bg-warning/25 text-warning hover:bg-warning hover:text-white"
                                                data-fc-target="editKontak{{$kontaks->id_kontak}}" data-fc-type="modal"><i
                                                    class="mgc_edit_2_line"></i></button>
                                        </td>
                                    </tr>
                                    @php $counter++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="py-1 px-4">
                            <nav class="flex items-center space-x-2">
                                {{ $kontak->links('pagination::tailwind') }}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Kontak -->
<div id="tambahKontak" class="w-full h-full mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
    <div class="sm:max-w-2xl fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                Tambah Kontak
            </h3>
            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <form action="{{ route('kontak.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="px-4 py-8 overflow-y-auto">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="mb-3">
                            <label for="nama_kontak" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Kontak</label>
                            <input type="text" class="form-input" id="nama_kontak" name="nama_kontak" aria-describedby="nama_kontak" placeholder="Masukan Nama Kontak">
                        </div>
                    </div>
                    <div>
                        <div class="mb-3">
                            <label for="jenis_kontak" class="text-gray-800 text-sm font-medium inline-block mb-2">Jenis Kontak</label>
                            <select id="jenis_kontak" name="jenis_kontak" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Jenis Kontak --</option>
                                <option value="pelanggan">Pelanggan</option>
                                <option value="karyawan">Karyawan</option>
                                <option value="vendor">Vendor</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <div class="mb-3">
                            <label for="email" class="text-gray-800 text-sm font-medium inline-block mb-2">Email</label>
                            <input type="text" class="form-input" id="email" name="email" aria-describedby="email" placeholder="Masukan Email">
                        </div>
                    </div>
                    <div>
                        <div class="mb-3">
                            <label for="no_hp" class="text-gray-800 text-sm font-medium inline-block mb-2">No Handphone</label>
                            <input type="text" class="form-input" id="no_hp" name="no_hp" aria-describedby="no_hp" placeholder="Masukan No Handphone">
                        </div>
                    </div>
                    <div>
                        <div class="mb-3">
                            <label for="nm_perusahaan" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Perusahaan</label>
                            <input type="text" class="form-input" id="nm_perusahaan" name="nm_perusahaan" aria-describedby="nm_perusahaan" placeholder="Masukan Nama Perusahaan">
                        </div>
                    </div>
                    <div>
                        <div class="mb-3">
                            <label for="alamat" name="alamat" class="text-gray-800 text-sm font-medium inline-block mb-2">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukan Alamat"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                <button class="btn dark:text-gray-200 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                </button>
                <button class="btn bg-[#307487] text-white" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>
<!-- end modal -->

<!-- Modal Edit Kontak -->
@foreach($kontak as $kontaks)
<div id="editKontak{{$kontaks->id_kontak}}" class="w-full h-full mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
    <div class="sm:max-w-2xl fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                Tambah Kontak
            </h3>
            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <form action="{{ route('kontak.update', $kontaks->id_kontak) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="px-4 py-8 overflow-y-auto">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="mb-3">
                            <label for="nama_kontak" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Kontak</label>
                            <input type="text" class="form-input" id="nama_kontak" name="nama_kontak" aria-describedby="nama_kontak" placeholder="Masukan Nama Kontak" value="{{ old('nama_kontak', $kontaks->nama_kontak) }}">
                        </div>
                    </div>
                    <div>
                        <div class="mb-3">
                            <label for="jenis_kontak" class="text-gray-800 text-sm font-medium inline-block mb-2">Jenis Kontak</label>
                            <select id="jenis_kontak" name="jenis_kontak" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" {{ $kontaks->jenis_kontak == '' ? 'selected' : '' }}>-- Pilih Jenis Kontak --</option>
                                <option value="pelanggan" {{ $kontaks->jenis_kontak == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                                <option value="karyawan" {{ $kontaks->jenis_kontak == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                <option value="vendor" {{ $kontaks->jenis_kontak == 'vendor' ? 'selected' : '' }}>Vendor</option>
                                <option value="lainnya" {{ $kontaks->jenis_kontak == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <div class="mb-3">
                            <label for="email" class="text-gray-800 text-sm font-medium inline-block mb-2">Email</label>
                            <input type="email" class="form-input" id="email" name="email" aria-describedby="email" placeholder="Masukan Email" value="{{ old('email', $kontaks->email) }}">
                        </div>
                    </div>
                    <div>
                        <div class="mb-3">
                            <label for="no_hp" class="text-gray-800 text-sm font-medium inline-block mb-2">No Handphone</label>
                            <input type="text" class="form-input" id="no_hp" name="no_hp" aria-describedby="no_hp" placeholder="Masukan No Handphone" value="{{ old('no_hp', $kontaks->no_hp) }}">
                        </div>
                    </div>
                    <div>
                        <div class="mb-3">
                            <label for="nm_perusahaan" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Perusahaan</label>
                            <input type="text" class="form-input" id="nm_perusahaan" name="nm_perusahaan" aria-describedby="nm_perusahaan" placeholder="Masukan Nama Perusahaan" value="{{ old('nm_perusahaan', $kontaks->nm_perusahaan) }}">
                        </div>
                    </div>
                    <div>
                        <div class="mb-3">
                            <label for="alamat" name="alamat" class="text-gray-800 text-sm font-medium inline-block mb-2">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukan Alamat">{{ old('alamat', $kontaks->alamat) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                <button class="btn dark:text-gray-200 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                </button>
                <button class="btn bg-[#307487] text-white" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>
@endforeach
<!-- end modal -->
@endsection
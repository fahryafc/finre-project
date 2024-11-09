@extends('layouts.vertical', ['title' => 'Asset', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('css')
@vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
@vite([
'node_modules/flatpickr/dist/flatpickr.min.css',
'node_modules/@simonwep/pickr/dist/themes/classic.min.css',
'node_modules/@simonwep/pickr/dist/themes/monolith.min.css',
'node_modules/@simonwep/pickr/dist/themes/nano.min.css',
])
<style>
    .disabled {
        background-color: #e0e0e0 !important;
        color: #a0a0a0;
        cursor: not-allowed !important;
    }

    .asset-name {
        cursor: pointer;
        color: inherit;
        text-decoration: none;
    }

    .asset-name:hover {
        color: #307487;
        text-decoration: underline;
    }

    .swal-custom-title {
        color: #EF3054;
        /* Custom color for the title */
    }
</style>
@vite(['node_modules/sweetalert2/dist/sweetalert2.min.css'])
@endsection

@section('content')
<div class="grid grid-rows-1 grid-flow-col gap-4 mb-5">
    <div class="card">
        <div class="p-5">
            <div class="flex justify-between">
                <div class="w-20 h-20 rounded-full inline-flex items-center justify-center bg-success/25 ">
                    <i class="ti ti-package text-4xl text-success"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-gray-700 mt-1 text-2xl font-bold mb-5 dark:text-gray-300">
                        {{ $totalTersedia }}
                    </h3>
                    <p class="text-gray-500 mb-1 truncate dark:text-primary-400">Asset Tersedia</p>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="p-5">
            <div class="flex justify-between">
                <div class="w-20 h-20 rounded-full inline-flex items-center justify-center bg-danger/25 ">
                    <i class="ti ti-package text-4xl text-danger"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-gray-700 mt-1 text-2xl font-bold mb-5 dark:text-gray-300">
                        {{ $totalTerjual }}
                    </h3>
                    <p class="text-gray-500 mb-1 truncate dark:text-primary-400">Asset Terjual</p>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="p-5">
            <div class="flex justify-between">
                <div class="w-20 h-20 rounded-full inline-flex items-center justify-center bg-[#307487]/25 ">
                    <i class="ti ti-package text-4xl text-primary"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-gray-700 mt-1 text-2xl font-bold mb-5 dark:text-gray-300">
                        {{ "Rp. ".number_format($total_nilai_asset, 0, ".", ".") }}
                    </h3>
                    <p class="text-gray-500 mb-1 truncate dark:text-primary-400">Total Nilai Asset</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid lg grid-cols-1 gap-6 mt-5">
    <div class="card">
        <div class="card-header">
            <div class="flex justify-between items-center">
                <h4 class="card-title">Data Asset</h4>
                <div class="flex space-x-2">
                    <button class="btn bg-[#307487] text-white" data-fc-target="tambahAsset" data-fc-type="modal" type="button">
                        <i class="mgc_add_fill text-base me-1"></i> Tambah Asset
                    </button>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div data-fc-type="tab">
                <nav class="flex space-x-2 border-b border-gray-200 dark:border-gray-700" aria-label="Tabs" role="tablist">
                    <button data-fc-target="#assetTersedia" type="button" class="fc-tab-active:bg-[#307487] fc-tab-active:border-b-transparent fc-tab-active:text-white dark:fc-tab-active:bg-gray-800 dark:fc-tab-active:border-b-gray-800 dark:fc-tab-active:text-white -mb-px py-3 px-4 inline-flex items-center gap-2 bg-gray-50 text-sm font-medium text-center border text-gray-500 rounded-t-lg hover:text-gray-700 dark:bg-gray-700 dark:border-gray-700 dark:text-gray-400 active" id="card-type-tab-item-1" aria-controls="assetTersedia" role="tab">
                        Asset Tersedia
                    </button>
                    <button data-fc-target="#assetTerjual" type="button" class="fc-tab-active:bg-[#307487] fc-tab-active:border-b-transparent fc-tab-active:text-white dark:fc-tab-active:bg-gray-800 dark:fc-tab-active:border-b-gray-800 dark:fc-tab-active:text-white -mb-px py-3 px-4 inline-flex items-center gap-2 bg-gray-50 text-sm font-medium text-center border text-gray-500 rounded-t-lg hover:text-gray-700 dark:bg-gray-700 dark:border-gray-700 dark:text-gray-400 dark:hover:text-gray-300" id="card-type-tab-item-2" aria-controls="assetTerjual" role="tab">
                        Asset Terjual
                    </button>
                </nav>
                <div class="mt-3">
                    <div id="assetTersedia" role="tabpanel" aria-labelledby="card-type-tab-item-1" class="block">
                        <!-- Asset Tersedia -->
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
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">No
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Nama Aset</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Kategori</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Kuantitas</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Tanggal Pembelian</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Harga Beli</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Nilai Buku</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Depresiasi</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Total Nilai Aset</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                                @php $counter = 1; @endphp
                                                @foreach($asset as $ast)
                                                <tr class="hover:bg-gray-200 dark:hover:bg-gray-700">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        {{ $counter + ($asset->currentPage() - 1) * $asset->perPage() }}
                                                    </td>
                                                    <td class="asset-name px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200 cursor-pointer hover:text-blue-600 hover:underline">
                                                        <button class="cursor-pointer" data-fc-target="modalDetailAsset" data-fc-type="modal" data-asset-id="{{ $ast->id_aset }}">
                                                            {{ $ast->nm_aset }}
                                                        </button>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        {{ $ast->kategori }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        {{ $ast->kuantitas }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        {{ $ast->tanggal }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        {{ "Rp. ".number_format($ast->harga_beli, 0, ".", ".") }}
                                                    </td>
                                                    <!-- Harga Buku -->
                                                    @if (!is_null($ast->masa_manfaat) && $ast->masa_manfaat > 0)
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        {{ "Rp. ".number_format($ast->harga_buku_masa_manfaat, 0, ".", ".") }}
                                                    </td>
                                                    @else
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        {{ "Rp. ".number_format($ast->harga_buku_nilai_tahun, 0, ".", ".") }}
                                                    </td>

                                                    @endif
                                                    <!-- Persentase Depresiasi Tahunan -->
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        @php
                                                        // Mengambil tanggal penyusutan
                                                        $tanggalPenyusutan = \Carbon\Carbon::parse($ast->tanggal_penyusutan);
                                                        // Hitung selisih tahun antara tanggal sekarang dengan tanggal penyusutan
                                                        $tahunBerjalan = \Carbon\Carbon::now()->diffInYears($tanggalPenyusutan);

                                                        // Inisialisasi variabel untuk persentase akumulasi
                                                        $persentaseAkumulasi = 0;

                                                        // Logika depresiasi berdasarkan input penyusutan
                                                        if ($ast->penyusutan == 1) {
                                                        if (!is_null($ast->masa_manfaat) && $ast->masa_manfaat > 0) {
                                                        // Jika masa manfaat terisi
                                                        $persentaseDepresiasiTahunan = 100 / $ast->masa_manfaat;
                                                        $persentaseAkumulasi = $tahunBerjalan * $persentaseDepresiasiTahunan;
                                                        } elseif (!is_null($ast->nilai_tahun) && $ast->nilai_tahun > 0) {
                                                        // Jika nilai tahun terisi
                                                        $persentaseAkumulasi = $ast->nilai_tahun * $tahunBerjalan;
                                                        }

                                                        // Pastikan persentase akumulasi tidak melebihi 100%
                                                        $persentaseAkumulasi = min($persentaseAkumulasi, 100);
                                                        }
                                                        @endphp

                                                        <!-- Tampilkan hasil berdasarkan kondisi depresiasi -->
                                                        @if($ast->penyusutan == 1 && $tahunBerjalan >= 1)
                                                        {{ number_format($persentaseAkumulasi, 2, ".", ",") . "%" }}
                                                        @else
                                                        <!-- Tampilkan tanda '-' jika tidak memenuhi syarat -->
                                                        -
                                                        @endif
                                                    </td>

                                                    <!-- Total Harga Aset -->
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        {{ "Rp. ".number_format($ast->total_harga, 0, ".", ".") }}
                                                    </td>

                                                    <!-- Tombol Jual -->
                                                    <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                                        <div class="flex items-center justify-end space-x-2">
                                                            <!-- Jual Button -->
                                                            <button type="button"
                                                                class="btn rounded-full bg-success/25 text-success hover:bg-success hover:text-white"
                                                                data-id="{{ $ast->id_aset }}" data-fc-target="modalJualAset" data-fc-type="modal"
                                                                onclick="loadAssetData(this)">
                                                                <i class="ti ti-credit-card-pay text-base me-1"></i> Jual
                                                            </button>

                                                            <!-- Edit Button -->
                                                            <button class="btn rounded-full bg-[#307487]/25 text-primary hover:bg-[#307487] hover:text-white"
                                                                data-fc-target="modalEditAsset" data-fc-type="modal" type="button" data-id="{{ $ast->id_aset }}">
                                                                <i class="mgc_edit_2_line text-base me-1"></i> Edit
                                                            </button>

                                                            <!-- Delete Form & Button -->
                                                            <form action="{{ route('asset.destroy', $ast->id_aset) }}" method="POST" class="inline delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn rounded-full bg-danger/25 text-danger hover:bg-danger hover:text-white delete-btn" data-id="{{ $ast->id_aset }}" data-image-url="{{ asset('images/confirm-delete.png') }}">
                                                                    <i class=" mgc_delete_2_line text-base me-1"></i> Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @php $counter++; @endphp
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="py-1 px-4">
                                        <nav class="flex items-center space-x-2">
                                            {{ $asset->links('pagination::tailwind') }}
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="assetTerjual" role="tabpanel" aria-labelledby="card-type-tab-item-2" class="hidden">
                        <!-- Asset Terjual -->
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
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">No
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Nama Aset</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Kategori</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Kuantitas</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Tanggal Pembelian</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Harga Beli</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Nilai Buku</th>
                                                    <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Depresiasi</th>
                                                    <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Total Nilai Aset</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                                @php $counter = 1; @endphp
                                                @foreach($assetTerjual as $terjual)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        {{ $counter + ($assetTerjual->currentPage() - 1) * $assetTerjual->perPage() }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        {{ $terjual->nm_aset }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        {{ $terjual->kategori }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        {{ $terjual->terjual }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        {{ $terjual->tanggal }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        {{ "Rp. ".number_format($terjual->harga_beli, 0, ".", ".") }}
                                                    </td>
                                                    <!-- Harga Buku -->
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        {{ "Rp. ".number_format($terjual->harga_buku, 0, ".", ".") }}
                                                    </td>

                                                    <!-- Persentase Depresiasi Tahunan -->
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        @php
                                                        // Mengambil tanggal penyusutan
                                                        $tanggalPenyusutan = \Carbon\Carbon::parse($terjual->tanggal_penyusutan);
                                                        // Hitung selisih tahun antara tanggal sekarang dengan tanggal penyusutan
                                                        $tahunBerjalan = \Carbon\Carbon::now()->diffInYears($tanggalPenyusutan);

                                                        // Inisialisasi variabel untuk persentase akumulasi
                                                        $persentaseAkumulasi = 0;

                                                        // Logika depresiasi berdasarkan input penyusutan
                                                        if ($terjual->penyusutan == 1) {
                                                        if (!is_null($terjual->masa_manfaat) && $terjual->masa_manfaat > 0) {
                                                        // Jika masa manfaat terisi
                                                        $persentaseDepresiasiTahunan = 100 / $terjual->masa_manfaat;
                                                        $persentaseAkumulasi = $tahunBerjalan * $persentaseDepresiasiTahunan;
                                                        } elseif (!is_null($terjual->nilai_tahun) && $terjual->nilai_tahun > 0) {
                                                        // Jika nilai tahun terisi
                                                        $persentaseAkumulasi = $terjual->nilai_tahun * $tahunBerjalan;
                                                        }

                                                        // Pastikan persentase akumulasi tidak melebihi 100%
                                                        $persentaseAkumulasi = min($persentaseAkumulasi, 100);
                                                        }
                                                        @endphp

                                                        <!-- Tampilkan hasil berdasarkan kondisi depresiasi -->
                                                        @if($terjual->penyusutan == 1 && $tahunBerjalan >= 1)
                                                        {{ number_format($persentaseAkumulasi, 2, ".", ",") . "%" }}
                                                        @else
                                                        <!-- Tampilkan tanda '-' jika tidak memenuhi syarat -->
                                                        -
                                                        @endif
                                                    </td>

                                                    <!-- Total Harga Aset -->
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        {{ "Rp. ".number_format($ast->total_harga, 0, ".", ".") }}
                                                    </td>
                                                </tr>
                                                @php $counter++; @endphp
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="py-1 px-4">
                                        <nav class="flex items-center space-x-2">
                                            {{ $assetTerjual->links('pagination::tailwind') }}
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Asset -->
<div id="modalDetailAsset" class="w-full h-auto max-h-[70vh] mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
    <div class="max-w-[60rem] fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                Detail Asset
            </h3>
            <div class="flex items-center">
                <button data-fc-type="dropdown" type="button" class="py-2 px-3 inline-flex justify-center items-center rounded-md font-medium bg-white text-gray-700 shadow-sm align-middle hover:bg-gray-50 transition-all text-sm dark:bg-gray-800 dark:hover:bg-gray-700 dark:border-gray-700 dark:text-gray-400 dark:hover:text-white">
                    <span class="material-symbols-rounded">more_vert</span>
                </button>
                @foreach($asset as $ast)
                <div class="hidden fc-dropdown-open:opacity-100 opacity-0 z-50 transition-all duration-300 bg-white border shadow-md rounded-lg p-2 dark:bg-slate-800 dark:border-slate-700">
                    <a class="flex items-center py-2 px-3 rounded-md text-sm text-gray-800 hover:bg-[#307487] hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="#">
                        Edit
                    </a>
                    <button class="flex items-center py-2 px-3 rounded-md text-sm text-gray-800 hover:bg-[#307487] hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" data-id="{{ $ast->id_aset }}" data-fc-target="modalJualAset" data-fc-type="modal"
                        onclick="loadAssetData(this)">
                        Jual
                    </button>
                    <a class="flex items-center py-2 px-3 rounded-md text-sm text-gray-800 hover:bg-[#307487] hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="#">
                        Hapus
                    </a>
                </div>

                @endforeach
                <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                    <span class="material-symbols-rounded">close</span>
                </button>
            </div>
        </div>
        <!-- Isi detail Asset -->
        <div class="flex flex-col gap-3 px-4 py-4 overflow-y-auto">
            <div class="grid grid-cols-4 items-center gap-6">
                <label for="nm_asset" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Asset</label>
                <div class="md:col-span-3">
                    <span class="nm_asset text-gray-800 text-sm font-medium inline-block"></span>
                </div>
            </div>
            <div class="grid grid-cols-4 items-center gap-6">
                <label for="kode" class="text-gray-800 text-sm font-medium inline-block mb-2">Kode Asset</label>
                <div class="md:col-span-3">
                    <span class="kode text-gray-800 text-sm font-medium inline-block"></span>
                </div>
            </div>
            <div class="grid grid-cols-4 items-center gap-6">
                <label for="tanggal" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Pembelian</label>
                <div class="md:col-span-3">
                    <span class="tanggal text-gray-800 text-sm font-medium inline-block"></span>
                </div>
            </div>
            <div class="grid grid-cols-4 items-center gap-6">
                <label for="kuantitas" class="text-gray-800 text-sm font-medium inline-block mb-2">Kuantitas</label>
                <div class="md:col-span-3">
                    <span class="kuantitas text-gray-800 text-sm font-medium inline-block"></span>
                </div>
            </div>
            <div class="grid grid-cols-4 items-center gap-6">
                <label for="harga_beli" class="text-gray-800 text-sm font-medium inline-block mb-2">Harga Beli</label>
                <div class="md:col-span-3">
                    <span class="harga_beli text-gray-800 text-sm font-medium inline-block"></span>
                </div>
            </div>
            <div class="grid grid-cols-4 items-center gap-6">
                <label for="nilai_buku" class="text-gray-800 text-sm font-medium inline-block mb-2">Nilai Buku</label>
                <div class="md:col-span-3">
                    <span class="nilai_buku text-gray-800 text-sm font-medium inline-block"></span>
                </div>
            </div>
        </div>
        <!-- Penyusutan -->
        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                Penyusutan
            </h3>
        </div>
        <div class="penyusutan-container flex flex-col gap-3 px-4 py-4 overflow-y-auto">
            <div class="grid grid-cols-4 items-center gap-6">
                <label for="nm_asset" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Penyusutan</label>
                <div class="md:col-span-3">
                    <span class="tanggal_penyusutan text-gray-800 text-sm font-medium inline-block"></span>
                </div>
            </div>
            <div class="grid grid-cols-4 items-center gap-6">
                <label for="metode_penyusutan" class="text-gray-800 text-sm font-medium inline-block mb-2">Metode Penyusutan</label>
                <div class="md:col-span-3">
                    <span class="metode_penyusutan text-gray-800 text-sm font-medium inline-block"></span>
                </div>
            </div>
            <div class="grid grid-cols-4 items-center gap-6">
                <label for="nilai_penyusutan" class="text-gray-800 text-sm font-medium inline-block mb-2">Nilai Penyusutan</label>
                <div class="md:col-span-3">
                    <span class="nilai_penyusutan text-gray-800 text-sm font-medium inline-block"></span>
                </div>
            </div>
        </div>

        <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
            <button class="btn dark:text-gray-200 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
            </button>
        </div>
    </div>
</div>
<!-- End Modal -->

<!-- Modal Tambah Assets -->
<div id="tambahAsset" class="w-full h-auto max-h-[70vh] mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
    <div class="max-w-[60rem] fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                Tambah Asset
            </h3>
            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <form action="{{ route('asset.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="px-4 py-4 overflow-y-auto max-h-[60vh]">
                <!-- row pertama -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Kolom 1 -->
                    <div>
                        <div class="mb-3">
                            <label for="pemasok" class="text-gray-800 text-sm font-medium inline-block mb-2">Pemasok</label>
                            <input type="text" class="form-input" id="pemasok" name="pemasok" aria-describedby="pemasok" placeholder="Masukan Nama Pemasok">
                        </div>
                        <div class="mb-3">
                            <label for="no_hp" class="text-gray-800 text-sm font-medium inline-block mb-2">No Handphone</label>
                            <input type="number" class="form-input" id="no_hp" name="no_hp" aria-describedby="no_hp" placeholder="Masukan No Handphone">
                        </div>
                        <div class="mb-3">
                            <label for="nm_perusahaan" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Perusahaan</label>
                            <input type="text" class="form-input" id="nm_perusahaan" name="nm_perusahaan" aria-describedby="nm_perusahaan" placeholder="Masukan Nama Perusahaan">
                        </div>
                        <div class="mb-e">
                            <label for="email" class="text-gray-800 text-sm font-medium inline-block mb-2">Email</label>
                            <input type="email" class="form-input" id="email" name="email" aria-describedby="email" placeholder="Masukan Email">
                        </div>
                        <div class="mb-3">
                            <label for="alamat" name="alamat" class="text-gray-800 text-sm font-medium inline-block mb-2">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukan Alamat"></textarea>
                        </div>
                    </div>
                    <!-- Kolom 2 -->
                    <div>
                        <div class="mb-3">
                            <label for="tanggal" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Pembelian</label>
                            <input type="text" class="form-input" name="tanggal" id="datepicker-basic">
                        </div>
                        <div class="mb-3">
                            <label for="nm_aset" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Asset</label>
                            <input type="text" class="form-input" id="nm_aset" name="nm_aset" aria-describedby="nm_aset" placeholder="Masukan Nama Asset">
                        </div>
                        <div class="mb-3">
                            <label for="satuan" class="text-gray-800 text-sm font-medium inline-block mb-2">Satuan</label>
                            <select id="satuan" name="satuan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Jenis Satuan --</option>
                                @foreach ($satuan as $satuans)
                                <option value="{{ $satuans->nama_satuan }}">{{ $satuans->nama_satuan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="kategori" class="text-gray-800 text-sm font-medium inline-block mb-2">Kategori</label>
                            <select id="kategori" name="satuan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Kategori --</option>
                                @foreach ($kategori as $ktg)
                                <option value="{{ $ktg->nama_kategori }}">{{ $ktg->nama_kategori }}</option>
                                @endforeach
                                <option value="tambahKategori">+ Tambah Kategori</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="kuantitas" class="text-gray-800 text-sm font-medium inline-block mb-2">Kuantitas</label>
                            <input type="number" class="form-input" id="kuantitas" name="kuantitas" aria-describedby="kuantitas" placeholder="Masukan Kuantitas">
                        </div>
                    </div>
                    <!-- kolom 3 -->
                    <div>
                        <div class="mb-3">
                            <label for="kode_sku" class="text-gray-800 text-sm font-medium inline-block mb-2">Kode/SKU</label>
                            <input type="text" class="form-input" id="kode_sku" name="kode_sku" aria-describedby="kode_sku" placeholder="Masukan Kode/SKU">
                        </div>
                        <div class="mb-3">
                            <label for="harga_beli" class="text-gray-800 text-sm font-medium inline-block mb-2">Harga Beli</label>
                            <input type="text" class="form-input" id="harga_beli" name="harga_beli" aria-describedby="harga_beli" placeholder="Masukan Harga Beli">
                        </div>
                        <div class="mb-3">
                            <label for="akun_pembayaran" class="text-gray-800 text-sm font-medium inline-block mb-2">Dibayarkan dari akun</label>
                            <select id="akun_pembayaran" name="akun_pembayaran" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Pembayaran --</option>
                                @foreach ( $kasdanbank as $kasbank )
                                <option value="{{ $kasbank->kode_akun }}">
                                    <span class="flex justify-between w-full">
                                        <span>{{ $kasbank->nama_akun }}</span>
                                        <span> - </span>
                                        <span>{{ $kasbank->kode_akun }}</span>
                                    </span>
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="akun_aset" class="text-gray-800 text-sm font-medium inline-block mb-2">Akun Aset</label>
                            <select id="akun_aset" name="akun_aset" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Akun Asset --</option>
                                @foreach ( $akun as $akuns )
                                <option value="{{ $akuns->kode_akun }}">
                                    <span class="flex justify-between w-full">
                                        <span>{{ $akuns->nama_akun }}</span>
                                    </span>
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <div class="flex items-center">
                                <input type="checkbox" id="penyusutan" name="penyusutan" class="form-switch text-primary" value="1" onchange="toggleCollapseWithSwitch()">
                                <label for="penyusutan" class="ms-1.5">Penyusutan</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="mb-3">
                                <div class="flex items-center">
                                    <input type="checkbox" id="pajakButton" name="pajakButton" class="form-switch text-primary" value="1" onchange="toggleCollapsePajak()">
                                    <label for="pajakButton" class="ms-1.5">Pajak</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <hr class="border-2 border-gray-300 my-4"> <!-- Garis pemisah -->

                <!-- collapse pajak -->
                <div id="collapsePajak" class="hidden w-full overflow-hidden transition-[height] duration-300 mt-5">
                    <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                        <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                            Pajak
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-3">
                        <div class="mb-3">
                            <label for="jns_pajak" class="text-gray-800 text-sm font-medium inline-block mb-2">Jenis Pajak</label>
                            <select id="jns_pajak" name="jns_pajak" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Jenis Pajak --</option>
                                <option value="ppn">PPN</option>
                                <option value="ppnbm">PPnBM</option>
                                <option value="bphtb">BPHTB</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="pajak" class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak (%)</label>
                            <input type="text" class="form-input" id="pajak" name="pajak" aria-describedby="pajak" placeholder="Masukan Pajak (%)">
                        </div>
                        <div class="mb-3">
                            <label for="pajak_dibayarkan" class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak Dibayarkan</label>
                            <input type="text" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" id="pajak_dibayarkan" name="pajak_dibayarkan" aria-describedby="pajak_dibayarkan" placeholder="Pajak Dibayarkan" readonly>
                        </div>
                    </div>
                    <!-- end row collapse -->
                    <hr class="border-2 border-gray-300 my-4"> <!-- Garis pemisah -->
                </div>

                <!-- row kedua: collapse penyusutan-->
                <div id="collapseWithTarget" class="hidden w-full overflow-hidden transition-[height] duration-300 mt-5">
                    <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                        <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                            Penyusutan
                        </h3>
                    </div>

                    <!-- row pertama -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                        <div>
                            <div class="mb-3">
                                <label for="tanggal_penyusutan" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Penyusutan</label>
                                <input type="text" class="form-input" name="tanggal_penyusutan" id="datepicker-basic">
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <!-- row kedua -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                        <div>
                            <div class="mb-3">
                                <label for="masa_manfaat" class="text-gray-800 text-sm font-medium inline-block mb-2 flex items-center">
                                    <input type="checkbox" id="enable_masa_manfaat" class="form-checkbox rounded text-primary mr-2" onchange="toggleFields('masa_manfaat', 'nilai_tahun')">
                                    Masa Manfaat
                                </label>
                                <div class="flex">
                                    <input type="number" class="form-input" id="masa_manfaat" name="masa_manfaat" aria-describedby="masa_manfaat" placeholder="Masukan Masa Manfaat">
                                    <div class="inline-flex items-center px-4 rounded-e border border-s-0 border-gray-200 bg-gray-200 text-gray-500 dark:bg-gray-700 dark:border-gray-700 dark:text-gray-400">
                                        Tahun
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="mb-3">
                                <label for="nilai_tahun" class="text-gray-800 text-sm font-medium inline-block mb-2 flex items-center">
                                    <input type="checkbox" id="enable_nilai_tahun" class="form-checkbox rounded text-primary mr-2" onchange="toggleFields('nilai_tahun', 'masa_manfaat')">
                                    Nilai/Tahun
                                </label>
                                <div class="flex">
                                    <input type="number" class="form-input" id="nilai_tahun" name="nilai_tahun" aria-describedby="nilai_tahun">
                                    <div class="inline-flex items-center px-4 rounded-e border border-s-0 border-gray-200 bg-gray-200 text-gray-500 dark:bg-gray-700 dark:border-gray-700 dark:text-gray-400">
                                        %
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <!-- row ketiga -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                        <div>
                            <div class="mb-3">
                                <label for="akun_penyusutan" class="text-gray-800 text-sm font-medium inline-block mb-2">Akun Penyusutan</label>
                                <select id="akun_penyusutan" name="akun_penyusutan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" selected>-- Pilih Akun --</option>
                                    @foreach ( $akun_penyusutan as $penyusutan )
                                    <option value="{{ $penyusutan->kode_akun }}">
                                        <span class="flex justify-between w-full">
                                            <span>{{ $penyusutan->nama_akun }}</span>
                                        </span>
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <div class="mb-3">
                                <label for="akumulasi_akun" class="text-gray-800 text-sm font-medium inline-block mb-2">Akumulasi Akun Penyusutan</label>
                                <select id="akumulasi_akun" name="akumulasi_akun" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" selected>-- Akun Akumulasi --</option>
                                    @foreach ( $akun_penyusutan as $penyusutan )
                                    <option value="{{ $penyusutan->kode_akun }}">
                                        <span class="flex justify-between w-full">
                                            <span>{{ $penyusutan->nama_akun }}</span>
                                        </span>
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
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

<!-- Modal Edit Assets -->
@foreach($asset as $ast)
<div id="modalEditAsset" class="w-full h-auto max-h-[70vh] mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
    <div class="max-w-[60rem] fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                Edit Asset
            </h3>
            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <form action="{{ route('asset.update', $ast->id_aset) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="px-4 py-4 overflow-y-auto max-h-[60vh]">
                <!-- row pertama -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Kolom 1 -->
                    <div>
                        <div class="mb-3">
                            <label for="pemasok" class="text-gray-800 text-sm font-medium inline-block mb-2">Pemasok</label>
                            <input type="text" class="form-input" id="pemasok" name="pemasok" aria-describedby="pemasok" placeholder="Masukan Nama Pemasok" value="{{ old('pemasok', $ast->pemasok) }}">
                        </div>
                        <div class="mb-3">
                            <label for="no_hp" class="text-gray-800 text-sm font-medium inline-block mb-2">No Handphone</label>
                            <input type="number" class="form-input" id="no_hp" name="no_hp" aria-describedby="no_hp" placeholder="Masukan No Handphone" value="{{ old('no_hp', $ast->no_hp) }}">
                        </div>
                        <div class="mb-3">
                            <label for="nm_perusahaan" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Perusahaan</label>
                            <input type="text" class="form-input" id="nm_perusahaan" name="nm_perusahaan" aria-describedby="nm_perusahaan" placeholder="Masukan Nama Perusahaan" value="{{ old('nm_perusahaan', $ast->nm_perusahaan) }}">
                        </div>
                        <div class="mb-e">
                            <label for="email" class="text-gray-800 text-sm font-medium inline-block mb-2">Email</label>
                            <input type="email" class="form-input" id="email" name="email" aria-describedby="email" placeholder="Masukan Email" value="{{ old('email', $ast->email) }}">
                        </div>
                        <div class="mb-3">
                            <label for="alamat" name="alamat" class="text-gray-800 text-sm font-medium inline-block mb-2">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukan Alamat"> {{ old('alamat', $ast->alamat) }}</textarea>
                        </div>
                    </div>
                    <!-- Kolom 2 -->
                    <div>
                        <div class="mb-3">
                            <label for="tanggal" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Pembelian</label>
                            <input type="text" class="form-input" name="tanggal" id="datepicker-basic" value="{{ old('tanggal', $ast->tanggal) }}">
                        </div>
                        <div class="mb-3">
                            <label for="nm_aset" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Asset</label>
                            <input type="text" class="form-input" id="nm_aset" name="nm_aset" aria-describedby="nm_aset" placeholder="Masukan Nama Asset" value="{{ old('nm_aset', $ast->nm_aset) }}">
                        </div>
                        <div class="mb-3">
                            <label for="satuan" class="text-gray-800 text-sm font-medium inline-block mb-2">Satuan</label>
                            <select id="satuan" name="satuan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" {{ $ast->satuan == '' ? 'selected' : '' }}>-- Pilih Jenis Satuan --</option>
                                @foreach ($satuan as $satuans)
                                <option value="{{ $satuans->nama_satuan }}" {{ $ast->satuan == $satuans->nama_satuan ? 'selected' : '' }}>
                                    {{ $satuans->nama_satuan }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="kategori" class="text-gray-800 text-sm font-medium inline-block mb-2">Kategori</label>
                            <select id="kategori" name="satuan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Kategori --</option>
                                @foreach ($kategori as $ktg)
                                <option value="{{ $ktg->nama_kategori }}">{{ $ktg->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="kuantitas" class="text-gray-800 text-sm font-medium inline-block mb-2">Kuantitas</label>
                            <input type="number" class="form-input" id="kuantitas" name="kuantitas" aria-describedby="kuantitas" placeholder="Masukan Kuantitas" value="{{ old('kuantitas', $ast->kuantitas) }}">
                        </div>
                    </div>
                    <!-- kolom 3 -->
                    <div>
                        <div class="mb-3">
                            <label for="kode_sku" class="text-gray-800 text-sm font-medium inline-block mb-2">Kode/SKU</label>
                            <input type="text" class="form-input" id="kode_sku" name="kode_sku" aria-describedby="kode_sku" placeholder="Masukan Kode/SKU" value="{{ old('kode_sku', $ast->kode_sku) }}">
                        </div>
                        <div class="mb-3">
                            <label for="harga_beli" class="text-gray-800 text-sm font-medium inline-block mb-2">Harga Beli</label>
                            <input type="text" class="form-input" id="harga_beli" name="harga_beli" aria-describedby="harga_beli" placeholder="Masukan Harga Beli" value="{{ old('harga_beli', $ast->harga_beli) }}">
                        </div>
                        <div class="mb-3">
                            <label for="akun_pembayaran" class="text-gray-800 text-sm font-medium inline-block mb-2">Dibayarkan dari akun</label>
                            <select id="akun_pembayaran" name="akun_pembayaran" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" {{ $ast->akun_pembayaran == '' ? 'selected' : '' }}>-- Pilih Pembayaran --</option>
                                @foreach ( $kasdanbank as $kasbank )
                                <option value="{{ $kasbank->kode_akun }}" {{ $ast->akun_pembayaran == $kasbank->kode_akun ? 'selected' : '' }}>
                                    <span class="flex justify-between w-full">
                                        <span>{{ $kasbank->nama_akun }}</span>
                                        <span> - </span>
                                        <span>{{ $kasbank->kode_akun }}</span>
                                    </span>
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="akun_aset" class="text-gray-800 text-sm font-medium inline-block mb-2">Akun Aset</label>
                            <select id="akun_aset" name="akun_aset" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" {{ $ast->akun_aset == '' ? 'selected' : '' }}>-- Pilih Akun Asset --</option>
                                @foreach ( $akun as $akuns )
                                <option value="{{ $akuns->kode_akun }}" {{ $ast->akun_aset == $akuns->kode_akun ? 'selected' : '' }}>
                                    <span class="flex justify-between w-full">
                                        <span>{{ $akuns->nama_akun }}</span>
                                    </span>
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <div class="flex items-center">
                                <input type="checkbox" id="penyusutan1" name="penyusutan1" class="form-switch text-primary" value="1" onchange="toggleCollapseWithSwitch1()" {{ $ast->penyusutan == 1 ? 'checked' : '' }}>
                                <label for="penyusutan1" class="ms-1.5">Penyusutan</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="mb-3">
                                <div class="flex items-center">
                                    <input type="checkbox" id="pajakButton1" name="pajakButton1" class="form-switch text-primary" value="1" onchange="toggleCollapsePajak1()" {{ $ast->pajak == 1 ? 'checked' : '' }}>
                                    <label for="pajakButton1" class="ms-1.5">Pajak</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <hr class="border-2 border-gray-300 my-4"> <!-- Garis pemisah -->

                <!-- collapse pajak -->
                <div id="collapsePajak1" class="{{ $ast->pajak == 1 ? '' : 'hidden' }}hidden w-full overflow-hidden transition-[height] duration-300 mt-5">
                    <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                        <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                            Pajak
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-3">
                        <div class="mb-3">
                            <label for="jns_pajak" class="text-gray-800 text-sm font-medium inline-block mb-2">Jenis Pajak</label>
                            <select id="jns_pajak" name="jns_pajak" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Jenis Pajak --</option>
                                <option value="ppn">PPN</option>
                                <option value="ppnbm">PPnBM</option>
                                <option value="bphtb">BPHTB</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="pajak" class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak (%)</label>
                            <input type="text" class="form-input" id="pajak" name="pajak" aria-describedby="pajak" placeholder="Masukan Pajak (%)">
                        </div>
                        <div class="mb-3">
                            <label for="pajak_dibayarkan" class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak Dibayarkan</label>
                            <input type="text" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" id="pajak_dibayarkan" name="pajak_dibayarkan" aria-describedby="pajak_dibayarkan" placeholder="Pajak Dibayarkan" readonly>
                        </div>
                    </div>
                    <!-- end row collapse -->
                    <hr class="border-2 border-gray-300 my-4"> <!-- Garis pemisah -->
                </div>

                <!-- row kedua: collapse penyusutan-->
                <div id="collapseWithTarget1" class="{{ $ast->penyusutan == 1 ? '' : 'hidden' }}hidden w-full overflow-hidden transition-[height] duration-300 mt-5">
                    <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                        <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                            Penyusutan
                        </h3>
                    </div>

                    <!-- row pertama -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                        <div>
                            <div class="mb-3">
                                <label for="tanggal_penyusutan" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Penyusutan</label>
                                <input type="text" class="form-input" name="tanggal_penyusutan" id="datepicker-basic">
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <!-- row kedua -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                        <div>
                            <div class="mb-3">
                                <label for="masa_manfaat" class="text-gray-800 text-sm font-medium inline-block mb-2 flex items-center">
                                    <input type="checkbox" id="enable_masa_manfaat" class="form-checkbox rounded text-primary mr-2" onchange="toggleFields('masa_manfaat', 'nilai_tahun')">
                                    Masa Manfaat
                                </label>
                                <div class="flex">
                                    <input type="number" class="form-input" id="masa_manfaat" name="masa_manfaat" aria-describedby="masa_manfaat" placeholder="Masukan Masa Manfaat">
                                    <div class="inline-flex items-center px-4 rounded-e border border-s-0 border-gray-200 bg-gray-200 text-gray-500 dark:bg-gray-700 dark:border-gray-700 dark:text-gray-400">
                                        Tahun
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="mb-3">
                                <label for="nilai_tahun" class="text-gray-800 text-sm font-medium inline-block mb-2 flex items-center">
                                    <input type="checkbox" id="enable_nilai_tahun" class="form-checkbox rounded text-primary mr-2" onchange="toggleFields('nilai_tahun', 'masa_manfaat')">
                                    Nilai/Tahun
                                </label>
                                <div class="flex">
                                    <input type="number" class="form-input" id="nilai_tahun" name="nilai_tahun" aria-describedby="nilai_tahun">
                                    <div class="inline-flex items-center px-4 rounded-e border border-s-0 border-gray-200 bg-gray-200 text-gray-500 dark:bg-gray-700 dark:border-gray-700 dark:text-gray-400">
                                        %
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <!-- row ketiga -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                        <div>
                            <div class="mb-3">
                                <label for="akun_penyusutan" class="text-gray-800 text-sm font-medium inline-block mb-2">Akun Penyusutan</label>
                                <select id="akun_penyusutan" name="akun_penyusutan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" selected>-- Pilih Akun --</option>
                                    @foreach ( $akun_penyusutan as $penyusutan )
                                    <option value="{{ $penyusutan->kode_akun }}">
                                        <span class="flex justify-between w-full">
                                            <span>{{ $penyusutan->nama_akun }}</span>
                                        </span>
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <div class="mb-3">
                                <label for="akumulasi_akun" class="text-gray-800 text-sm font-medium inline-block mb-2">Akumulasi Akun Penyusutan</label>
                                <select id="akumulasi_akun" name="akumulasi_akun" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" selected>-- Akun Akumulasi --</option>
                                    @foreach ( $akun_penyusutan as $penyusutan )
                                    <option value="{{ $penyusutan->kode_akun }}">
                                        <span class="flex justify-between w-full">
                                            <span>{{ $penyusutan->nama_akun }}</span>
                                        </span>
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
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

<!-- Modal Jual Asset -->
<div id="modalJualAset" class="w-full h-auto max-h-[70vh] mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
    <div class="max-w-[60rem] fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                Jual Asset
            </h3>
            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <form action="{{ route('penjualan-asset.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="px-4 py-4 overflow-y-auto max-h-[60vh]">
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <input type="hidden" id="id_aset" name="id_aset"> <!-- ID asset yang dijual -->
                        <div class="mb-3">
                            <label for="nm_pelanggan" class="text-gray-800 text-sm font-medium inline-block mb-2">Pelanggan</label>
                            <input type="text" class="form-input" id="nm_pelanggan" name="nm_pelanggan" aria-describedby="nm_pelanggan" placeholder="Masukan Nama Pelanggan">
                        </div>
                        <div class="mb-3">
                            <label for="no_hp" class="text-gray-800 text-sm font-medium inline-block mb-2">No Handphone</label>
                            <input type="number" class="form-input" id="no_hp" name="no_hp" aria-describedby="no_hp" placeholder="Masukan No handphone">
                        </div>
                    </div>

                    <div>
                        <div class="mb-3">
                            <label for="gender" class="text-gray-800 text-sm font-medium inline-block mb-2">Gender</label>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                <select id="gender" name="gender" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="Laki - Laki">Laki - Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </td>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="text-gray-800 text-sm font-medium inline-block mb-2">Email</label>
                            <input type="email" class="form-input" id="email" name="email" aria-describedby="emailHelp" placeholder="Masukan email">
                        </div>
                    </div>

                    <div>
                        <div class="mb-3">
                            <label for="nm_perusahaan" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Perusahaan</label>
                            <input type="text" class="form-input" id="nm_perusahaan" name="nm_perusahaan" aria-describedby="nm_perusahaan" placeholder="Masukan Nama Perusahaan">
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="text-gray-800 text-sm font-medium inline-block mb-2">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Masukan Alamat"></textarea>
                        </div>
                    </div>
                </div>

                <hr class="border-2 border-gray-300 my-4"> <!-- Garis pemisah -->

                <!-- Row 1: Tanggal Pembelian dan Tanggal Penjualan -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="mb-3">
                        <label for="tanggal" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Pembelian</label>
                        <input type="text" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" name="tanggal" id="datepicker-basic" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="tgl_penjualan" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Penjualan</label>
                        <input type="text" class="form-input" name="tgl_penjualan" id="datepicker-basic">
                    </div>
                </div>

                <!-- Row 2: Harga Beli, Kuantitas, Total Nilai Asset -->
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div class="mb-3">
                        <label for="harga_beli" class="text-gray-800 text-sm font-medium inline-block mb-2">Harga Beli</label>
                        <input type="text" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" id="harga_beli_aset" name="harga_beli" placeholder="Masukan Harga Beli" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="kuantitas" class="text-gray-800 text-sm font-medium inline-block mb-2">Kuantitas</label>
                        <input type="text" class="form-input" id="jumlah" name="kuantitas" placeholder="Masukan Kuantitas">
                    </div>
                    <div class="mb-3">
                        <label for="total_nilai_asset" class="text-gray-800 text-sm font-medium inline-block mb-2">Total Nilai Asset</label>
                        <input type="text" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" id="total_nilai_asset" name="total_nilai_asset" placeholder="Total Nilai Asset" readonly>
                    </div>
                </div>

                <!-- Row 3: Harga Pelepasan, Total Nilai Penyusutan -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="mb-3">
                        <label for="harga_pelepasan" class="text-gray-800 text-sm font-medium inline-block mb-2">Harga Pelepasan</label>
                        <input type="text" class="form-input" id="harga_pelepasan" name="harga_pelepasan" placeholder="Masukan Harga Pelepasan">
                    </div>
                    <div class="mb-3">
                        <label for="nilai_penyusutan_terakhir" class="text-gray-800 text-sm font-medium inline-block mb-2">Total Nilai Penyusutan</label>
                        <input type="text" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" id="nilai_penyusutan_terakhir" name="nilai_penyusutan_terakhir" placeholder="Total Nilai Penyusutan" readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="mb-3">
                        <div class="flex items-center">
                            <input type="checkbox" id="buttonPajakPenjualan" name="buttonPajakPenjualan" class="form-switch text-primary" value="1" onchange="toggleCollapsePajakPenjualan()">
                            <label for="buttonPajakPenjualan" class="ms-1.5">Aktifkan Pajak Penjualan</label>
                        </div>
                    </div>
                </div>

                <!-- collapse pajak penjualan -->
                <div id="collapsePajakPenjualan" class="hidden w-full overflow-hidden transition-[height] duration-300 mt-5">
                    <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                        <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                            Pajak
                        </h3>
                    </div>

                    <div class="grid grid-cols-4 gap-4 mb-4">
                        <div class="mb-3">
                            <label for="jns_pajak_penjualan" class="text-gray-800 text-sm font-medium inline-block mb-2">Jenis Pajak</label>
                            <select id="jns_pajak_penjualan" name="jns_pajak_penjualan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Jenis Pajak --</option>
                                <option value="ppn">PPN</option>
                                <option value="ppnbm">PPnBM</option>
                                <option value="bphtb">BPHTB</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="pajak_penjualan" class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak (%)</label>
                            <input type="text" class="form-input" id="pajak_penjualan" name="pajak_penjualan" aria-describedby="pajak" placeholder="Masukan Pajak (%)">
                        </div>
                        <div class="mb-3">
                            <label for="pajak_penjualan_dibayarkan" class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak Dibayarkan</label>
                            <input type="text" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" id="pajak_penjualan_dibayarkan" name="pajak_penjualan_dibayarkan" aria-describedby="pajak_penjualan_dibayarkan" placeholder="Pajak Dibayarkan" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="akun_pajak_penjualan" class="text-gray-800 text-sm font-medium inline-block mb-2">Akun Pajak</label>
                            <select id="akun_pajak_penjualan" name="akun_pajak_penjualan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Akun Pajak --</option>
                                <option value="ppn">Ex Akun Pajak</option>
                            </select>
                        </div>
                    </div>
                    <!-- end row collapse -->
                    <hr class="border-2 border-gray-300 my-4"> <!-- Garis pemisah -->
                </div>

                <hr class="border-2 border-gray-300 my-4"> <!-- Garis pemisah -->

                <div class="overflow-x-auto">
                    <div class="min-w-full inline-block align-middle">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis"></th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis"></th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Debit</th>
                                        <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Kredit</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">Nilai Buku</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                            <input type="text" id="nilai_buku" name="nilai_buku" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" value="Rp. 0">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">Didepositkan Ke</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                            <select id="akun_deposit" name="akun_deposit" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <option value="" selected>-- Akun --</option>
                                                @foreach ( $akun_deposit as $deposit )
                                                <option value="{{ $deposit->kode_akun }}">
                                                    <span class="flex justify-between w-full">
                                                        <span>{{ $deposit->nama_akun }}</span>
                                                    </span>
                                                </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                            <input type="text" id="hargaPelepasanDisplay" name="nominal_deposit" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" value="Rp. 0">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> Keuntungan / Kerugian </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                            <select id="akun_keuntungan_kerugian" name="akun_keuntungan_kerugian" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <option value="" selected>-- Akun --</option>
                                                @foreach ( $akun_kredit as $kredit )
                                                <option value="{{ $kredit->kode_akun }}">
                                                    <span class="flex justify-between w-full">
                                                        <span>{{ $kredit->nama_akun }}</span>
                                                    </span>
                                                </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                            <input type="text" id="keuntungan_kerugian" name="nominal_keuntungan_kerugian" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed" value="Rp. 0">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                <button class="btn dark:text-gray-200 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                </button>
                <button class="btn bg-[#307487] text-white" type="submit">Jual</button>
            </div>
        </form>
    </div>
</div>
<!-- end modal -->

<!-- Modal Tambah Kategori -->
<div id="tambahKategori" class="w-full h-full mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
    <div class="sm:max-w-2xl fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                Tambah Kategori
            </h3>
            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <form action="{{ route('kategori.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="px-4 py-8 overflow-y-auto">
                <div class="grid grid-cols-2 gap-4">
                    <!-- col 1 -->
                    <div>
                        <div class="mb-3">
                            <label for="nama_kategori" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Kategori</label>
                            <input type="text" class="form-input" id="nama_kategori" name="nama_kategori" aria-describedby="nama_kategori" placeholder="Masukan Nama Kategori">
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
@endsection

@section('script')
@vite(['resources/js/pages/table-gridjs.js'])
@vite(['resources/js/pages/highlight.js', 'resources/js/pages/form-flatpickr.js', 'resources/js/pages/form-color-pickr.js'])
@vite(['resources/js/pages/extended-sweetalert.js'])
@vite(['resources/js/pages/highlight.js'])
@vite(['resources/js/custom-js/assets.js'])
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function toggleCollapseWithSwitch1() {
        const collapseElement = document.getElementById('collapseWithTarget1');
        const penyusutan = document.getElementById('penyusutan1');
        // Toggle class based on the switch status
        if (penyusutan.checked) {
            collapseElement.classList.remove('hidden');
        } else {
            collapseElement.classList.add('hidden');
        }
    }

    function toggleCollapsePajak1() {
        const collapseElementPajak = document.getElementById('collapsePajak1');
        const pajak = document.getElementById('pajakButton1');
        // Toggle class based on the switch status
        if (pajak.checked) {
            collapseElementPajak.classList.remove('hidden');
        } else {
            collapseElementPajak.classList.add('hidden');
        }
    }

    function setInitialCollapseState() {
        toggleCollapseWithSwitch1();
        toggleCollapsePajak1();
    }

    function toggleCollapseWithSwitch() {
        const collapseElement = document.getElementById('collapseWithTarget');
        const penyusutan = document.getElementById('penyusutan');
        // Toggle class based on the switch status
        if (penyusutan.checked) {
            collapseElement.classList.remove('hidden');
        } else {
            collapseElement.classList.add('hidden');
        }
    }

    function toggleCollapsePajak() {
        const collapseElementPajak = document.getElementById('collapsePajak');
        const pajak = document.getElementById('pajakButton');
        // Toggle class based on the switch status
        if (pajak.checked) {
            collapseElementPajak.classList.remove('hidden');
        } else {
            collapseElementPajak.classList.add('hidden');
        }
    }

    function toggleCollapsePajakPenjualan() {
        const collapseElementPajakPenjualan = document.getElementById('collapsePajakPenjualan');
        const pajakPenjualan = document.getElementById('buttonPajakPenjualan');
        // Toggle class based on the switch status
        if (pajakPenjualan.checked) {
            collapseElementPajakPenjualan.classList.remove('hidden');
        } else {
            collapseElementPajakPenjualan.classList.add('hidden');
        }
    }

    // Event listener untuk reset switch saat modal terbuka
    document.getElementById('tambahAsset').addEventListener('show', function() {
        const penyusutan = document.getElementById('penyusutan');
        const collapseElement = document.getElementById('collapseWithTarget');
        const pajakButton = document.getElementById('pajakButton');
        const collapseElementPajak = document.getElementById('collapsePajak');
        const buttonPajakPenjualan = document.getElementById('buttonPajakPenjualan');
        const collapseElementPajakPenjualan = document.getElementById('collapsePajakPenjualan');

        // Reset switch dan collapse
        penyusutan.checked = false;
        collapseElement.classList.add('hidden');
        pajakButton.checked = false;
        collapseElementPajak.classList.add('hidden');
        buttonPajakPenjualan.checked = false;
        collapseElementPajakPenjualan.classList.add('hidden');
    });

    // Ambil elemen dropdown jenis pajak, input pajak, harga beli, kuantitas, dan pajak dibayarkan
    const jnsPajakSelect = document.getElementById('jns_pajak');
    const pajakInput = document.getElementById('pajak');
    const pajakDibayarkanInput = document.getElementById('pajak_dibayarkan');

    // harga_beli dan kuantitas diambil dari field input yang ada di form
    const hargaBeliInput = document.getElementById('harga_beli');
    const kuantitasInput = document.getElementById('kuantitas');

    // Fungsi untuk menghapus format Rupiah dan konversi ke angka
    function parseRupiahToNumber(rupiah) {
        return parseInt(rupiah.replace(/[^,\d]/g, '').replace(',', '')) || 0; // Hapus karakter selain angka dan konversi ke integer
    }

    // Ambil elemen dropdown jenis pajak penjualan, input pajak penjualan, dan pajak penjualan dibayarkan
    const jnsPajakPenjualanSelect = document.getElementById('jns_pajak_penjualan');
    const pajakPenjualanInput = document.getElementById('pajak_penjualan');
    const pajakPenjualanDibayarkanInput = document.getElementById('pajak_penjualan_dibayarkan');
    const hargaPelepasanInput = document.getElementById('harga_pelepasan');

    // Fungsi untuk menghitung pajak penjualan dibayarkan
    function hitungPajakPenjualanDibayarkan() {
        const hargaPelepasanValue = parseRupiahToNumber(hargaPelepasanInput.value) || 0;
        const pajakPenjualanValue = parseFloat(pajakPenjualanInput.value) || 0;
        const pajakPenjualanPersen = pajakPenjualanValue / 100;
        const totalPajakPenjualanDibayarkan = hargaPelepasanValue * pajakPenjualanPersen;
        pajakPenjualanDibayarkanInput.value = formatRupiah(totalPajakPenjualanDibayarkan.toString());
    }

    // Event listener untuk mendeteksi perubahan pada dropdown jenis pajak penjualan
    jnsPajakPenjualanSelect.addEventListener('change', function() {
        pajakPenjualanInput.disabled = false;
        pajakPenjualanInput.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        pajakPenjualanInput.value = '';
        pajakPenjualanDibayarkanInput.value = '';
    });

    // Event listener untuk mendeteksi perubahan pada input pajak penjualan dan harga pelepasan
    pajakPenjualanInput.addEventListener('input', hitungPajakPenjualanDibayarkan);
    hargaPelepasanInput.addEventListener('input', hitungPajakPenjualanDibayarkan);

    // Tambahkan ke fungsi prepareForSubmit yang sudah ada
    function prepareForSubmit() {
        // Tambahkan untuk pajak penjualan
        const pajakPenjualanDibayarkan = document.getElementById('pajak_penjualan_dibayarkan');
        if (pajakPenjualanDibayarkan) {
            pajakPenjualanDibayarkan.value = parseRupiahToNumber(pajakPenjualanDibayarkan.value);
        }
    }

    // Fungsi untuk menghitung pajak dibayarkan
    function hitungPajakDibayarkan() {
        const hargaBeliValue = parseRupiahToNumber(hargaBeliInput.value) || 0; // Ambil nilai dari input harga beli
        const kuantitasValue = parseInt(kuantitasInput.value) || 0; // Ambil nilai dari input kuantitas
        // console.log(kuantitasValue);

        const pajakValue = parseFloat(pajakInput.value) || 0; // Ambil nilai pajak dari input dan konversi ke float
        const pajakPersen = pajakValue / 100; // Konversi persen ke desimal
        const totalPajakDibayarkan = hargaBeliValue * kuantitasValue * pajakPersen; // Hitung pajak dibayarkan
        pajakDibayarkanInput.value = formatRupiah(totalPajakDibayarkan.toString()); // Tampilkan hasil format dalam rupiah
    }

    // Event listener untuk mendeteksi perubahan pada dropdown jenis pajak dan input pajak
    jnsPajakSelect.addEventListener('change', function() {
        if (jnsPajakSelect.value === 'ppn') {
            pajakInput.disabled = true;
            pajakInput.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
            pajakInput.value = '11'; // Atur nilai default untuk PPN
            hitungPajakDibayarkan(); // Hitung pajak dibayarkan
        } else {
            pajakInput.disabled = false;
            pajakInput.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
            pajakInput.value = ''; // Kosongkan nilai input
            pajakDibayarkanInput.value = ''; // Reset nilai pajak dibayarkan
        }
    });

    // Event listener untuk mendeteksi perubahan pada input pajak
    pajakInput.addEventListener('input', hitungPajakDibayarkan);
    hargaBeliInput.addEventListener('input', hitungPajakDibayarkan);
    kuantitasInput.addEventListener('input', hitungPajakDibayarkan);

    // Function untuk men-disable field yang tidak dipilih
    function toggleFields(enabledField, disabledField) {
        // Dapatkan checkbox masing-masing field
        var enableMasaManfaat = document.getElementById('enable_masa_manfaat');
        var enableNilaiTahun = document.getElementById('enable_nilai_tahun');

        // Cek kondisi masing-masing checkbox dan set field mana yang aktif atau tidak
        if (enabledField === 'masa_manfaat' && enableMasaManfaat.checked) {
            document.getElementById('masa_manfaat').disabled = false;
            document.getElementById('masa_manfaat').classList.remove('disabled'); // Menghapus kelas disabled
            document.getElementById('nilai_tahun').disabled = true;
            document.getElementById('nilai_tahun').classList.add('disabled'); // Menambahkan kelas disabled
            enableNilaiTahun.checked = false;
        } else if (enabledField === 'nilai_tahun' && enableNilaiTahun.checked) {
            document.getElementById('nilai_tahun').disabled = false;
            document.getElementById('nilai_tahun').classList.remove('disabled'); // Menghapus kelas disabled
            document.getElementById('masa_manfaat').disabled = true;
            document.getElementById('masa_manfaat').classList.add('disabled'); // Menambahkan kelas disabled
            enableMasaManfaat.checked = false;
        } else {
            document.getElementById(enabledField).disabled = true;
            document.getElementById(enabledField).classList.add('disabled'); // Menambahkan kelas disabled
        }
    }

    // Inisialisasi input saat halaman dimuat
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('masa_manfaat').disabled = true; // Awalnya dinonaktifkan
        document.getElementById('nilai_tahun').disabled = true; // Awalnya dinonaktifkan
    });

    function formatRupiah(angka) {
        var numberString = angka.replace(/[^,\d]/g, '').toString();
        var split = numberString.split(',');
        var sisa = split[0].length % 3;
        var rupiah = split[0].substr(0, sisa);
        var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // Tambahkan ribuan ke string rupiah
        if (ribuan) {
            var separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        // Tambahkan bagian desimal jika ada
        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        return 'Rp. ' + rupiah;
    }

    // Event listener untuk input harga pelepasan
    document.getElementById('harga_pelepasan').addEventListener('input', function() {
        const hargaPelepasan = this.value;
        const hargaFormatted = formatRupiah(hargaPelepasan);
        document.getElementById('hargaPelepasanDisplay').value = hargaFormatted;
    });
    // Event listener untuk format input harga pelepasan
    document.getElementById('harga_pelepasan').addEventListener('input', function() {
        // Format nilai input menjadi Rupiah
        let angka = this.value.replace(/[^,\d]/g, "").toString();
        this.value = formatRupiah(angka);

        // Panggil fungsi untuk menghitung keuntungan atau kerugian
        // calculateProfitLoss();
    });

    document.getElementById('harga_beli').addEventListener('input', function() {
        const hargaBeli = this.value;
        const hargaFormatted = formatRupiah(hargaBeli); // Format nilai input menjadi Rupiah
    });
    // Event listener untuk format input harga beli
    document.getElementById('harga_beli').addEventListener('input', function() {
        let angka = this.value.replace(/[^,\d]/g, "").toString();
        this.value = formatRupiah(angka);
    });

    function loadAssetData(button) {
        const idAset = button.getAttribute('data-id');

        fetch(`/get-asset-data/${idAset}`)
            .then(response => response.json())
            .then(data => {
                const hargaBeli = data.asset.harga_beli || 0;
                const kuantitasAwal = parseInt(data.asset.kuantitas) || 0;
                const totalNilaiAssetAwal = hargaBeli * kuantitasAwal;

                const tanggalPenjualan = new Date(data.datas[0].tanggal_penyusutan);
                const tanggalSekarang = new Date();

                const tahunBerjalan = tanggalSekarang.getFullYear() - tanggalPenjualan.getFullYear();

                let nilaiDepresiasiPerTahun = 0;
                let penyusutanPerUnit = 0;

                if (data.asset.penyusutan == 1) {
                    if (data.datas[0].masa_manfaat !== null && data.datas[0].masa_manfaat !== '') {
                        nilaiDepresiasiPerTahun = data.datas[0].nominal_masa_manfaat;
                        penyusutanPerUnit = nilaiDepresiasiPerTahun / kuantitasAwal;
                    } else if (data.datas[0].nilai_tahun !== null && data.datas[0].nilai_tahun !== '') {
                        nilaiDepresiasiPerTahun = data.datas[0].nominal_nilai_tahun;
                        penyusutanPerUnit = nilaiDepresiasiPerTahun / kuantitasAwal;
                    }
                }

                const penyusutanPerUnitBerjalan = penyusutanPerUnit * tahunBerjalan;
                const totalPenyusutanTerakhir = penyusutanPerUnitBerjalan * kuantitasAwal;

                // Nilai buku saat ini setelah depresiasi
                let nilaiBukuSaatIni = totalNilaiAssetAwal - totalPenyusutanTerakhir;

                // Event listener untuk perubahan pada kuantitas dan perhitungan keuntungan/kerugian
                const inputKuantitas = document.getElementById('jumlah');
                const inputHargaPelepasan = document.getElementById('harga_pelepasan');

                function calculateProfitLoss() {
                    let kuantitasBaru = parseInt(inputKuantitas.value) || 0;
                    let hargaPelepasan = parseInt(inputHargaPelepasan.value.replace(/[^,\d]/g, "")) || 0;

                    if (kuantitasBaru > kuantitasAwal) {
                        alert('Kuantitas tidak boleh melebihi batas maksimal: ' + kuantitasAwal);
                        inputKuantitas.value = kuantitasAwal;
                        kuantitasBaru = kuantitasAwal;
                    }

                    const totalNilaiAssetBaru = hargaBeli * kuantitasBaru;
                    const totalPenyusutanBaru = penyusutanPerUnitBerjalan * kuantitasBaru;

                    // Nilai buku baru setelah penyusutan
                    let nilaiBukuBaru = totalNilaiAssetBaru - totalPenyusutanBaru;

                    // Perhitungan keuntungan/kerugian
                    let selisih = hargaPelepasan - nilaiBukuBaru;
                    let resultSpan = document.getElementById('keuntungan_kerugian');

                    if (selisih < 0) {
                        resultSpan.value = `- ${formatRupiah(Math.abs(selisih).toString())}`;
                        resultSpan.style.color = "red";
                    } else {
                        resultSpan.value = `+ ${formatRupiah(selisih.toString())}`;
                        resultSpan.style.color = "green";
                    }

                    // Perbarui field di form
                    document.getElementById('total_nilai_asset').value = formatRupiah(totalNilaiAssetBaru.toString());
                    document.getElementById('nilai_buku').value = formatRupiah(nilaiBukuBaru.toString());
                    document.getElementById('nilai_penyusutan_terakhir').value = formatRupiah(totalPenyusutanBaru.toString());
                }

                // Panggil perhitungan awal saat form dibuka
                calculateProfitLoss();

                // Tambahkan event listener untuk perubahan input harga pelepasan dan kuantitas
                inputKuantitas.addEventListener('input', calculateProfitLoss);
                inputHargaPelepasan.addEventListener('input', calculateProfitLoss);

                // Isi data awal ke form
                document.getElementById('id_aset').value = data.asset.id_aset;
                document.getElementById('harga_beli_aset').value = formatRupiah(hargaBeli.toString());
                document.getElementById('total_nilai_asset').value = formatRupiah(totalNilaiAssetAwal.toString());
                document.getElementById('jumlah').value = kuantitasAwal.toString();
                document.getElementById('nilai_buku').value = formatRupiah(nilaiBukuSaatIni.toString());
                document.getElementById('nilai_penyusutan_terakhir').value = formatRupiah(totalPenyusutanTerakhir.toString());

                // Buka modal setelah data diisi
                const modal = document.getElementById('modalJualAset');
                modal.classList.remove('hidden');
                modal.classList.add('show');
            })
            .catch(error => {
                console.error('Error fetching asset data:', error);
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const assetButtons = document.querySelectorAll('button[data-fc-target="modalDetailAsset"]');
        assetButtons.forEach(button => {
            button.addEventListener('click', function() {
                const assetId = this.getAttribute('data-asset-id');

                // Fetch data from server using the assetId
                fetch(`/get-asset-detail/${assetId}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);

                        // Tampilkan informasi umum asset
                        if (data.asset) {
                            document.querySelector('.nm_asset').textContent = `: ${data.asset.nm_aset}`;
                            document.querySelector('.kode').textContent = `: ${data.asset.kode_sku}`;
                            document.querySelector('.tanggal').textContent = `: ${data.asset.tanggal}`;
                            document.querySelector('.harga_beli').textContent = `: ${formatRupiah(data.asset.harga_beli).toString()}`;
                            document.querySelector('.kuantitas').textContent = `: ${data.asset.kuantitas}`;
                        }

                        // Hitung nilai buku
                        const hargaBeli = data.asset.harga_beli || 0;
                        const kuantitasAwal = parseInt(data.asset.kuantitas) || 0;
                        const totalNilaiAssetAwal = hargaBeli * kuantitasAwal;

                        const tanggalPenjualan = new Date(data.penyusutan.tanggal_penyusutan);
                        const tanggalSekarang = new Date();

                        const tahunBerjalan = tanggalSekarang.getFullYear() - tanggalPenjualan.getFullYear();

                        let nilaiDepresiasiPerTahun = 0;
                        let penyusutanPerUnit = 0;

                        if (data.asset.penyusutan == 1) {
                            if (data.penyusutan.masa_manfaat !== null && data.penyusutan.masa_manfaat !== '') {
                                nilaiDepresiasiPerTahun = data.penyusutan.nominal_masa_manfaat;
                                penyusutanPerUnit = nilaiDepresiasiPerTahun / kuantitasAwal;
                            } else if (data.penyusutan.nilai_tahun !== null && data.penyusutan.nilai_tahun !== '') {
                                nilaiDepresiasiPerTahun = data.penyusutan.nominal_nilai_tahun;
                                penyusutanPerUnit = nilaiDepresiasiPerTahun / kuantitasAwal;
                            }
                        }

                        const penyusutanPerUnitBerjalan = penyusutanPerUnit * tahunBerjalan;
                        const totalPenyusutanTerakhir = penyusutanPerUnitBerjalan * kuantitasAwal;

                        // Nilai buku saat ini setelah depresiasi
                        let nilaiBukuSaatIni = totalNilaiAssetAwal - totalPenyusutanTerakhir;

                        // Tampilkan informasi nilai buku pada modal detail
                        document.querySelector('.nilai_buku').textContent = `: ${formatRupiah(nilaiBukuSaatIni.toString())}`;

                        // Tampilkan informasi penyusutan hanya jika `penyusutan` bernilai `1`
                        if (data.asset.penyusutan == 1) {
                            document.querySelector('.tanggal_penyusutan').textContent = `: ${data.penyusutan.tanggal_penyusutan}`;
                            if (data.penyusutan.masa_manfaat !== null && data.penyusutan.masa_manfaat !== '') {
                                document.querySelector('.metode_penyusutan').textContent = `: Masa Manfaat - ${data.penyusutan.masa_manfaat} Tahun`;
                                document.querySelector('.nilai_penyusutan').textContent = `: ${formatRupiah(data.penyusutan.nominal_masa_manfaat).toString()}`;
                            }
                            if (data.penyusutan.nilai_tahun !== null && data.penyusutan.nilai_tahun !== '') {
                                document.querySelector('.metode_penyusutan').textContent = `: Nilai/Tahun - ${data.penyusutan.nilai_tahun}%`;
                                document.querySelector('.nilai_penyusutan').textContent = `: ${formatRupiah(data.penyusutan.nominal_nilai_tahun).toString()}`;
                            }
                        }

                        // Tampilkan modal
                        document.getElementById('modalDetailAsset').classList.remove('hidden');
                    });
            });
        });
    });


    document.addEventListener("DOMContentLoaded", function() {
        // Attach event listener to buttons
        document.querySelectorAll('[data-fc-target="modalEditAsset"]').forEach(button => {
            button.addEventListener("click", function() {
                setInitialCollapseState();
                // Show the modal
                document.querySelector('#modalEditAsset').classList.remove("hidden");

            });
        });

        // Close modal
        document.querySelector('[data-fc-dismiss]').addEventListener("click", function() {
            document.querySelector('#modalEditAsset').classList.add("hidden");
        });
    });


    // Fungsi untuk menghapus format Rupiah dari elemen input sebelum form disubmit
    function prepareForSubmit() {
        // Ambil elemen input harga_beli_aset dan harga_beli
        const hargaBeliAsetInput = document.getElementById('harga_beli_aset');
        const hargaBeliInput = document.getElementById('harga_beli');
        const pajakdiBayarkan = document.getElementById('pajak_dibayarkan');
        const nominal_keuntungan_kerugian = document.getElementById('nominal_keuntungan_kerugian');
        const nilai_buku = document.getElementById('nilai_buku');
        const nominal_deposit = document.getElementById('nominal_deposit');
        const nilai_penyusutan_terakhir = document.getElementById('nilai_penyusutan_terakhir');
        const harga_pelepasan = document.getElementById('harga_pelepasan');

        // Hapus format Rupiah dari kedua input
        if (hargaBeliAsetInput) {
            hargaBeliAsetInput.value = parseRupiahToNumber(hargaBeliAsetInput.value)
        }
        if (hargaBeliInput) {
            hargaBeliInput.value = parseRupiahToNumber(hargaBeliInput.value)
        }
        if (pajakdiBayarkan) {
            pajakdiBayarkan.value = parseRupiahToNumber(pajakdiBayarkan.value)
        }
        if (nominal_keuntungan_kerugian) {
            nominal_keuntungan_kerugian.value = parseRupiahToNumber(nominal_keuntungan_kerugian.value)
        }
        if (nilai_buku) {
            nilai_buku.value = parseRupiahToNumber(nilai_buku.value)
        }
        if (nominal_deposit) {
            nominal_deposit.value = parseRupiahToNumber(nominal_deposit.value)
        }
        if (nilai_penyusutan_terakhir) {
            nilai_penyusutan_terakhir.value = parseRupiahToNumber(nilai_penyusutan_terakhir.value)
        }
        if (harga_pelepasan) {
            harga_pelepasan.valie = parseRupiahToNumber(harga_pelepasan.value)
        }
    }

    // Tambahkan event listener submit pada form untuk memanggil fungsi prepareForSubmit
    document.querySelector('form').addEventListener('submit', prepareForSubmit);
    // calculateProfitLoss();
</script>
@endsection
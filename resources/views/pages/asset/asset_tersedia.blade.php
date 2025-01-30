@extends('layouts.vertical', ['title' => 'Asset Tersedia', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('css')
<link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
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

<div class="grid lg grid-cols-1 gap-6">
    <div class="card mt-10 p-5">
        <div class="card-header mb-5">
            <div class="flex justify-between items-center">
                <h4 class="card-title">Data Asset Tersedia</h4>
                <a href="{{ route('asset.tambah_asset') }}" class="btn bg-[#307487] text-white">
                    <i class="mgc_add_fill text-base me-4"></i>
                    Tambah Data 
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="asset-tersedia">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Nama Asset</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Kategori</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Kuantitas</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Tanggal Pembelian</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Harga Beli</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Nilai Buku</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Depresiasi</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap overflow-hidden text-ellipsis">Total Nilai Asset</th>
                        <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody>
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
                                <!-- <button type="button"
                                    class="btn rounded-full bg-success/25 text-success hover:bg-success hover:text-white"
                                    data-id="{{ $ast->id_aset }}" data-fc-target="modalJualAset" data-fc-type="modal"
                                    onclick="loadAssetData(this)">
                                    <i class="ti ti-credit-card-pay text-base me-1"></i> Jual
                                </button> -->

                                <a href="{{ route('asset.jual_asset', $ast->id_aset) }}" class="btn rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                    <i class="ti ti-credit-card-pay text-base me-1"></i>
                                    Jual 
                                </a>
                                <a href="{{ route('asset.edit_asset', $ast->id_aset) }}" class="btn rounded-full bg-warning/25 text-warning hover:bg-warning hover:text-white">
                                    <i class="mgc_edit_2_line text-base me-1"></i>
                                    Edit 
                                </a>
                                <a href="{{ route('asset.edit_asset', $ast->id_aset) }}" class="btn rounded-full bg-info/25 text-info hover:bg-info hover:text-white">
                                    <i class="mgc_information_line text-base me-1"></i>
                                    Detail 
                                </a>

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
    </div>
</div>

@endsection

@section('script')
@vite(['resources/js/pages/table-gridjs.js'])
@vite(['resources/js/pages/highlight.js', 'resources/js/pages/form-flatpickr.js', 'resources/js/pages/form-color-pickr.js'])
@vite(['resources/js/pages/extended-sweetalert.js'])
@vite(['resources/js/pages/highlight.js'])
<!-- <script src="{{ asset('js/custom-js/assets.js') }}" defer></script> -->
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- <script src="{{ asset('js/custom-js/assets.js') }}" defer></script> -->
<script>
    if (document.getElementById("asset-tersedia") && typeof simpleDatatables.DataTable !== 'undefined') {
        const dataTable = new simpleDatatables.DataTable("#asset-tersedia", {
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
</script>
@endsection
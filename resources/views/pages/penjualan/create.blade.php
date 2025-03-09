@extends('layouts.vertical', ['title' => 'Tambah Penjualan', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('css')
<link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
@vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
@vite([
'node_modules/flatpickr/dist/flatpickr.min.css',
'node_modules/@simonwep/pickr/dist/themes/classic.min.css',
'node_modules/@simonwep/pickr/dist/themes/monolith.min.css',
'node_modules/@simonwep/pickr/dist/themes/nano.min.css',
])
@vite(['node_modules/sweetalert2/dist/sweetalert2.min.css'])
@endsection

@section('content')
<div class="col-span-2">
    <div class="card">
        <div class="card-header">
            <div class="flex justify-between items-center">
                <h4 class="card-title">Tambah Data Penjualan</h4>
                <!-- <div class="flex items-center gap-2">
                    <button type="button" class="btn-code" data-fc-type="collapse" data-fc-target="GridFormHtml">
                        <i class="mgc_eye_line text-lg"></i>
                        <span class="ms-2">Code</span>
                    </button>

                    <button class="btn-code" data-clipboard-action="copy">
                        <i class="mgc_copy_line text-lg"></i>
                        <span class="ms-2">Copy</span>
                    </button>
                </div> -->
            </div>
        </div>

        <div class="p-6">
            <form id="salesForm" action="{{ route('penjualan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-3">
                        <label for="tanggal" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Penjualan</label>
                        <input type="text" class="form-input tgl_penjualan" name="tanggal" id="datepicker-basic">
                    </div>
                    <div class="mb-3">
                        <label for="id_kontak" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Pelanggan</label>
                        <select id="id_kontak" name="id_kontak" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                            <option value="" selected>-- Pilih Pelanggan --</option>
                            @foreach ($pelanggan as $customer)
                            <option value="{{ $customer->id_kontak }}">{{ $customer->nama_kontak }} - {{ $customer->nm_perusahaan }}</option>
                            @endforeach
                            <option value="tambah_kontak" data-fc-target="tambahKontak" data-fc-type="modal" >+ Tambah Kontak</option>
                        </select>
                    </div>
                </div>
                <hr class="border-1 border-gray-300 my-1">

                <!-- Labels row -->
                <div class="grid grid-cols-8 grid-rows-1 gap-4">
                    <div class="mb-3">
                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Produk</label>
                    </div>
                    <div class="mb-3">
                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Satuan</label>
                    </div>
                    <div class="mb-3">
                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Harga</label>
                    </div>
                    <div class="mb-3">
                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Kuantitas</label>
                    </div>
                    <div class="mb-3">
                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Jenis Pajak</label>
                    </div>
                    <div class="mb-3">
                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak (%)</label>
                    </div>
                    <div class="mb-3">
                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Diskon (%)</label>
                    </div>
                    <div class="mb-3">
                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"></label>
                    </div>
                </div>
                <div id="productRowsContainer">
                    <div id="productRows" class="productRows">
                        <!-- Initial product row -->
                        <div class="product-row grid grid-cols-8 grid-rows-1 gap-4">
                            <div class="mb-3">
                                <select id="produk" name="produk[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option value="" selected>-- Pilih Produk --</option>
                                    @foreach ($produk as $produks)
                                        <option value="{{ $produks->id_produk }}" data-harga="{{ $produks->harga_jual }}" data-satuan="{{ $produks->satuan }}">{{ $produks->nama_produk }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-input satuan" id="satuan" name="satuan[]" placeholder="Masukan satuan" readonly>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-input harga" id="harga" name="harga[]" placeholder="Masukan Harga" readonly>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-input kuantitas" id="kuantitas" name="kuantitas[]" placeholder="Masukan Kuantitas" required>
                            </div>
                            <div class="mb-3">
                                <select id="jns_pajak" name="jns_pajak[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option value="jns_pajak" selected>-- Jenis Pajak --</option>
                                    <option value="ppn11">PPN (11%)</option>
                                    <option value="ppn12">PPN (12%)</option>
                                    <option value="ppnbm">PPnBM</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <input type="text" id="persen_pajak" name="persen_pajak[]" placeholder="Masukan Pajak (%)" class="form-input persen_pajak" readonly>
                            </div>
                            <div class="mb-3">
                                <input type="text" id="diskon" name="diskon[]" placeholder="Masukan Diskon" class="form-input diskon"/>
                            </div>
                            <div class="mb-3">
                                <button type="button" class="delete-row btn bg-red-500 text-white hover:bg-red-600 p-2 rounded-lg" onclick="deleteRow(this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="addProductRow({{ $produk->count() }})" class="btn bg-blue-500 text-white hover:bg-blue-600 mb-4">
                    + Tambah Baris
                </button>
                <hr class="border-1 border-gray-300 my-1">

                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-3">
                        <label for="ongkir" class="text-gray-800 text-sm font-medium inline-block mb-2">Biaya Pengiriman</label>
                        <input type="text" class="form-input ongkir" id="ongkir" name="ongkir" aria-describedby="ongkir" placeholder="Masukan Ongkir">
                    </div>
                    <div class="mb-3">
                        <label for="pembayaran" class="text-gray-800 text-sm font-medium inline-block mb-2"> Kas & Bank </label>
                        <select id="pembayaran" name="pembayaran" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                            <option value="" selected>-- Pilih Akun --</option>
                            @foreach ( $kas_bank as $akuns )
                            <option value="{{ $akuns->kode_akun }}">
                                <span class="flex justify-between w-full">
                                    <span>{{ $akuns->nama_akun }}</span>
                                    <span> - </span>
                                    <span>{{ $akuns->kode_akun }}</span>
                                </span>
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="flex items-center">
                            <label for="piutangSwitch" class="text-gray-800 text-sm font-medium">Piutang</label>
                            <label class="inline-flex items-center ml-2">
                                <input type="checkbox" id="piutangSwitch" name="piutangSwitch" class="form-switch text-primary" onclick="togglePiutangInput()">
                            </label>
                        </div>

                        <!-- Input Field for Piutang (Hidden by Default) -->
                        <div id="piutangInputContainer" class="mt-2 hidden">
                            <input type="text" class="form-input w-full piutang" id="piutang" name="piutang" aria-describedby="piutang" placeholder="Masukan Piutang">
                        </div>
                    </div>
                    <div class="mb-3 hidden" id="tglJatuhTempoContainer">
                        <label for="tgl_jatuh_tempo" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Jatuh Tempo</label>
                        <input type="text" class="form-input tgl_jatuh_tempo" id="tgl_jatuh_tempo" name="tgl_jatuh_tempo" id="datepicker-basic">
                    </div>
                </div>
                <hr class="border-1 border-gray-300 my-2">

                <!-- Totals section -->
                <div class="grid grid-cols-2 gap-4 mt-5">
                    <div></div>
                    <div class="mb-4">
                        <div class="flex w-full items-center">
                            <label class="text-gray-800 text-sm font-medium p-2 w-1/3">Total Pajak</label>
                            <input type="text" id="nominal_pajak" name="nominal_pajak" class="form-input bg-gray-300 text-gray-500 rounded flex-1 pajak-output" readonly>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div></div>
                    <div class="mb-4">
                        <div class="flex w-full items-center">
                            <label class="text-gray-800 text-sm font-medium p-2 w-1/3">Total Diskon</label>
                            <input type="text" id="diskon_output" name="diskon_output" class="form-input bg-gray-300 text-gray-500 rounded flex-1 diskon_output" readonly>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div></div>
                    <div class="mb-4">
                        <div class="flex w-full items-center">
                            <label class="text-gray-800 text-sm font-medium p-2 w-1/3">Total Pemasukan</label>
                            <input type="text" id="total_pemasukan" name="total_pemasukan" class="form-input bg-[#307487] text-white rounded flex-1" value="Total Pemasukan" readonly>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                    <button class="btn dark:text-gray-200 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close</button>
                    <button class="btn bg-[#307487] text-white" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Kontak -->
<x-modals.kontak.tambah-kontak />
<!-- End Modal -->
@endsection

@section('script')
@vite(['resources/js/pages/table-gridjs.js'])
@vite(['resources/js/pages/highlight.js'])
@vite(['resources/js/pages/highlight.js', 'resources/js/pages/form-flatpickr.js', 'resources/js/pages/form-color-pickr.js'])
@vite(['resources/js/pages/extended-sweetalert.js'])
@vite(['resources/js/pages/highlight.js'])
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
<script src="{{ asset('js/custom-js/penjualan.js') }}" defer></script>
<script>
const selectOption = document.getElementById('id_kontak');
const modal = document.getElementById('tambahKontak');
const overlay = document.getElementById('modal-overlay'); // Ambil elemen overlay

// Event listener untuk select
selectOption.addEventListener('change', function () {
    if (this.value === 'tambah_kontak') {
        // Buka modal dan overlay
        document.querySelector('[data-fc-target="tambahKontak"]').click();
    }
});

// Event untuk tombol tutup modal
document.querySelectorAll('[data-modal-hide="tambahKontak"]').forEach((button) => {
    button.addEventListener('click', () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        overlay.classList.add('hidden'); // Sembunyikan overlay
    });
});
</script>
@endsection

@extends('layouts.vertical', ['title' => 'Tambah Produk & Inventori', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('css')
    @vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
    @vite(['node_modules/nice-select2/dist/css/nice-select2.css'])
    @vite(['node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/@simonwep/pickr/dist/themes/classic.min.css', 'node_modules/@simonwep/pickr/dist/themes/monolith.min.css', 'node_modules/@simonwep/pickr/dist/themes/nano.min.css'])
    @vite(['node_modules/sweetalert2/dist/sweetalert2.min.css'])
@endsection

@section('content')
    <div class="col-span-2">
        <div class="card">
            <div class="card-header">
                <div class="flex justify-between items-center">
                    <h4 class="card-title">Tambah Produk & Inventori</h4>
                </div>
            </div>
            <div class="p-6">
                <form id="createProduk" action="{{ route('produkdaninventori.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-3 gap-4">
                        <div class="mb-3">
                            <label for="pemasok"
                                class="text-gray-800 text-sm font-medium inline-block mb-2">Pemasok</label>
                            <select id="pemasok" name="pemasok"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Pemasok --</option>
                                @foreach ($pemasoks as $pemasok)
                                    <option value="{{ $pemasok->id_kontak }}">{{ $pemasok->nama_kontak }}</option>
                                @endforeach
                                <option value="tambah_pemasok" data-fc-target="tambahKontak" data-fc-type="modal">+ Tambah
                                    Pemasok</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal"
                                class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal</label>
                            <input type="text" class="form-input tanggal" name="tanggal" id="datepicker-basic">
                        </div>
                    </div>
                    <hr class="border-2 border-gray-300 my-2">

                    <div class="grid grid-cols-3 gap-4">
                        <div class="mb-3">
                            <label for="nama_produk" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama
                                Produk</label>
                            <input type="text" class="form-input" id="nama_produk" name="nama_produk"
                                aria-describedby="nama_produk" placeholder="Masukan Nama Produk">
                        </div>
                        <div class="mb-3">
                            <label for="satuan" class="text-gray-800 text-sm font-medium inline-block mb-2">Satuan</label>
                            <select id="satuan" class="selectize" name="satuan">
                                @foreach ($satuan as $key)
                                    <option value="{{ $key->nama_satuan }}">{{ $key->nama_satuan }}</option>
                                @endforeach
                                <option value="tambah_satuan" data-fc-target="tambahSatuan" data-fc-type="modal">+ Tambah
                                    Satuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="kategori"
                                class="text-gray-800 text-sm font-medium inline-block mb-2">Kategori</label>
                            <select id="kategori" class="selectize" name="kategori">
                                @foreach ($kategori as $key)
                                    <option value="{{ $key->nama_kategori }}">{{ $key->nama_kategori }}</option>
                                @endforeach
                                <option value="tambah_ketegori">+ Tambah Ketegori</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="kuantitas"
                                class="text-gray-800 text-sm font-medium inline-block mb-2">Kuantitas</label>
                            <input type="number" class="form-input" id="kuantitas" name="kuantitas"
                                placeholder="Masukan Kuantitas">
                        </div>
                        <div class="mb-3">
                            <label for="kode_sku"
                                class="text-gray-800 text-sm font-medium inline-block mb-2">Kode/SKU</label>
                            <input type="text" class="form-input" id="kode_sku" name="kode_sku"
                                aria-describedby="kode_sku" placeholder="Masukan Kode/SKU">
                        </div>
                    </div>
                    <hr class="border-2 border-gray-300 my-2">

                    <div class="grid grid-cols-3 gap-4">
                        <div class="mb-3">
                            <label for="harga_beli" class="text-gray-800 text-sm font-medium inline-block mb-2">Harga
                                Beli</label>
                            <input type="text" class="form-input" id="harga_beli" name="harga_beli"
                                placeholder="Masukan Harga Beli">
                        </div>
                        <div class="mb-3">
                            <label for="harga_jual" class="text-gray-800 text-sm font-medium inline-block mb-2">Harga
                                Jual</label>
                            <input type="text" class="form-input" id="harga_jual" name="harga_jual"
                                placeholder="Masukan Harga Jual">
                        </div>
                    </div>
                    <hr class="border-2 border-gray-300 my-2">

                    <div class="grid grid-cols-3 gap-4">
                        <div class="mb-3">
                            <label for="jns_pajak" class="text-gray-800 text-sm font-medium inline-block mb-2">Jenis
                                Pajak</label>
                            <select id="jns_pajak" name="jns_pajak"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="jns_pajak" selected>-- Pilih Jenis Pajak --</option>
                                <option value="ppn">PPN</option>
                                <option value="ppnbm">PPnBM</option>
                            </select>
                        </div>
                        <div class="mb-3" id="pajakPersenContainer">
                            <label for="pajak_persen" class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak
                                (%)</label>
                            <input type="text" class="form-input" id="pajak_persen" name="pajak_persen"
                                aria-describedby="pajak_persen" placeholder="Masukan Pajak (%)">
                        </div>
                        <div class="mb-3">
                            <label for="nominal_pajak" class="text-gray-800 text-sm font-medium inline-block mb-2">Nominal
                                Pajak (Rp)</label>
                            <input type="text" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed"
                                id="nominal_pajak" name="nominal_pajak" aria-describedby="nominal_pajak"
                                placeholder="Nominal Pajak" readonly>
                        </div>
                    </div>
                    <hr class="border-2 border-gray-300 my-2">

                    <div class="grid grid-cols-3 gap-4">
                        <div class="mb-3">
                            <label for="akun_pembayaran"
                                class="text-gray-800 text-sm font-medium inline-block mb-2">Dibayarkan dari akun</label>
                            <select id="akun_pembayaran"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                name="akun_pembayaran">
                                @foreach ($akun as $key)
                                    <option value="{{ $key->kode_akun }}">{{ $key->nama_akun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- <div class="mb-3">
                                                                        <label for="masuk_akun" class="text-gray-800 text-sm font-medium inline-block mb-2"> Masuk Akun </label>
                                                                        <select id="masuk_akun" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="masuk_akun">
                                                                            @foreach ($akun as $key)
    <option value="{{ $key->kode_akun }}">{{ $key->nama_akun }}</option>
    @endforeach
                                                                        </select>
                                                                    </div> -->
                    </div>
                    <hr class="border-2 border-gray-300 my-2">

                    <div class="grid grid-cols-3 gap-4">
                        <div class="mb-3"></div>
                        <div class="mb-3"></div>
                        <div class="mb-3">
                            <div class="flex w-full">
                                <label for="total_transaksi"
                                    class="text-gray-800 text-sm font-medium inline-block p-2">Total Transaksi</label>
                                <input type="text"
                                    class="form-input ltr:rounded-r-none rtl:rounded-l-none bg-[#307487] flex-1"
                                    style="color: white;" id="total_transaksi" name="total_transaksi" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                        <button
                            class="btn dark:text-gray-200 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 hover:dark:bg-slate-700 transition-all"
                            data-fc-dismiss type="button">Close
                        </button>
                        <button class="btn bg-[#307487] text-white" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Kontak -->


    <!-- End Modal -->
    <x-modals.kontak.tambah-kontak />
    <x-modals.satuan.tambah-satuan />
    <x-modals.kategori.tambah-kategori />
@endsection

@section('script')
    @vite(['resources/js/pages/table-gridjs.js'])
    @vite(['resources/js/pages/highlight.js', 'resources/js/pages/form-select.js'])
    @vite(['resources/js/pages/highlight.js', 'resources/js/pages/form-flatpickr.js', 'resources/js/pages/form-color-pickr.js'])
    @vite(['resources/js/pages/extended-sweetalert.js'])
    @vite(['resources/js/pages/highlight.js'])
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script src="{{ asset('js/custom-js/produk_and_inventory.js') }}" defer></script>
    <script>
        const selectOption = document.getElementById('kategori');
        const modal = document.getElementById('kategoriModal');
        const overlay = document.getElementById('modal-overlay'); // Ambil elemen overlay
        const kategoriModal = document.getElementById('kategoriModal');

        // Event listener untuk select
        selectOption.addEventListener('change', function() {
            if (this.value === 'tambah_ketegori') {
                // Buka modal dan overlay
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                overlay.classList.remove('hidden'); // Tampilkan overlay
            }
        });

        // Event untuk tombol tutup modal
        document.querySelectorAll('[data-modal-hide="kategoriModal"]').forEach((button) => {
            button.addEventListener('click', () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                overlay.classList.add('hidden'); // Sembunyikan overlay
            });
        });

        const selectOptionKontak = document.getElementById('pemasok');
        selectOptionKontak.addEventListener('change', function() {
            if (this.value === 'tambah_pemasok') {
                // Buka modal dan overlay
                document.querySelector('[data-fc-target="tambahKontak"]').click();
            }
        });
        const selectOptionSatuan = document.getElementById('satuan');
        selectOptionSatuan.addEventListener('change', function() {
            if (this.value === 'tambah_satuan') {
                // Buka modal dan overlay
                document.querySelector('[data-fc-target="tambahSatuan"]').click();
            }
        });



    </script>
@endsection

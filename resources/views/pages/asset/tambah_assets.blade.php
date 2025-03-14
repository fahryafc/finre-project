@extends('layouts.vertical', ['title' => 'Tambah Asset', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('css')
    @vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
    @vite(['node_modules/nice-select2/dist/css/nice-select2.css'])
    @vite(['node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/@simonwep/pickr/dist/themes/classic.min.css', 'node_modules/@simonwep/pickr/dist/themes/monolith.min.css', 'node_modules/@simonwep/pickr/dist/themes/nano.min.css'])
    @vite(['node_modules/sweetalert2/dist/sweetalert2.min.css'])
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
    <div class="grid lg grid-cols-1 gap-6 mt-5">
        <div class="card">
            <div class="card-header">
                <div class="flex justify-between items-center">
                    <h4 class="card-title">Data Asset</h4>
                </div>
            </div>
            <div class="p-6">
                <form action="/tambah-asset/store" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- row pertama -->
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
                    </div>
                    <hr class="border-2 border-gray-300 my-4">

                    <div class="grid grid-cols-3 gap-4">
                        <div class="mb-3">
                            <label for="tanggal" class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal
                                Pembelian</label>
                            <input type="text" class="form-input tanggal_pembelian" name="tanggal" id="datepicker-basic">
                        </div>
                        <div class="mb-3">
                            <label for="nm_aset" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama
                                Asset</label>
                            <input type="text" class="form-input" id="nm_aset" name="nm_aset"
                                aria-describedby="nm_aset" placeholder="Masukan Nama Asset" required>
                        </div>
                        <div class="mb-3">
                            <label for="satuan" class="text-gray-800 text-sm font-medium inline-block mb-2">Satuan</label>
                            <select id="satuan" name="satuan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required>
                                <option value="" selected>-- Pilih Jenis Satuan --</option>
                                @foreach ($satuan as $satuans)
                                    <option value="{{ $satuans->nama_satuan }}">{{ $satuans->nama_satuan }}</option>
                                @endforeach
                                <option value="tambah_satuan" data-fc-target="tambahSatuan" data-fc-type="modal">+ Tambah
                                    Satuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="kategori"
                                class="text-gray-800 text-sm font-medium inline-block mb-2">Kategori</label>
                            <select id="kategori" name="kategori"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required>
                                <option value="" selected>-- Pilih Kategori --</option>
                                @foreach ($kategori as $ktg)
                                    <option value="{{ $ktg->nama_kategori }}">{{ $ktg->nama_kategori }}</option>
                                @endforeach
                                <option value="tambahKategori">+ Tambah Kategori</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="kuantitas"
                                class="text-gray-800 text-sm font-medium inline-block mb-2">Kuantitas</label>
                            <input type="number" class="form-input" id="kuantitas" name="kuantitas"
                                aria-describedby="kuantitas" placeholder="Masukan Kuantitas" required>
                        </div>
                    </div>
                    <hr class="border-2 border-gray-300 my-4">

                    <div class="grid grid-cols-3 gap-4">
                        <div class="mb-3">
                            <label for="kode_sku"
                                class="text-gray-800 text-sm font-medium inline-block mb-2">Kode/SKU</label>
                            <input type="text" class="form-input" id="kode_sku" name="kode_sku"
                                aria-describedby="kode_sku" placeholder="Masukan Kode/SKU" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga_beli" class="text-gray-800 text-sm font-medium inline-block mb-2">Harga
                                Beli</label>
                            <input type="text" class="form-input" id="harga_beli" name="harga_beli"
                                aria-describedby="harga_beli" placeholder="Masukan Harga Beli" required>
                        </div>
                        <div class="mb-3">
                            <label for="akun_pembayaran"
                                class="text-gray-800 text-sm font-medium inline-block mb-2">Dibayarkan dari akun</label>
                            <select id="akun_pembayaran" name="akun_pembayaran"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required>
                                <option value="" selected>-- Pilih Pembayaran --</option>
                                @foreach ($kasdanbank as $kasbank)
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
                            <label for="akun_aset" class="text-gray-800 text-sm font-medium inline-block mb-2">Akun
                                Aset</label>
                            <select id="akun_aset" name="akun_aset"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Akun Asset --</option>
                                @foreach ($akun as $akuns)
                                    <option value="{{ $akuns->kode_akun }}">
                                        <span class="flex justify-between w-full">
                                            <span>{{ $akuns->nama_akun }}</span>
                                        </span>
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 flex items-center">
                            <input type="checkbox" id="penyusutan" name="penyusutan" class="form-switch text-primary"
                                value="1" onchange="toggleCollapseWithSwitch()">
                            <label for="penyusutan" class="ms-1.5">Penyusutan</label>
                        </div>
                        <div class="mb-3 flex items-center">
                            <input type="checkbox" id="pajakButton" name="pajakButton" class="form-switch text-primary"
                                value="1" onchange="toggleCollapsePajak()">
                            <label for="pajakButton" class="ms-1.5">Pajak</label>
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
                                <label for="jns_pajak" class="text-gray-800 text-sm font-medium inline-block mb-2">Jenis
                                    Pajak</label>
                                <select id="jns_pajak" name="jns_pajak"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" selected>-- Pilih Jenis Pajak --</option>
                                    <option value="ppn11">PPN (11%)</option>
                                    <option value="ppn12">PPN (12%)</option>
                                    <option value="ppnbm">PPnBM</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="persen_pajak"
                                    class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak (%)</label>
                                <input type="text" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed"
                                    id="persen_pajak" name="persen_pajak" aria-describedby="persen_pajak"
                                    placeholder="Masukan Pajak (%)" inputmode="numeric" pattern="[0-9]*" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="pajak_dibayarkan"
                                    class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak Dibayarkan</label>
                                <input type="text" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed"
                                    id="pajak_dibayarkan" name="pajak_dibayarkan" aria-describedby="pajak_dibayarkan"
                                    placeholder="Pajak Dibayarkan" readonly>
                            </div>
                        </div>
                        <!-- end row collapse -->
                        <hr class="border-2 border-gray-300 my-4"> <!-- Garis pemisah -->
                    </div>

                    <!-- row kedua: collapse penyusutan-->
                    <div id="collapseWithTarget"
                        class="hidden w-full overflow-hidden transition-[height] duration-300 mt-5">
                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                Penyusutan
                            </h3>
                        </div>

                        <!-- row pertama -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                            <div>
                                <div class="mb-3">
                                    <label for="tanggal_penyusutan"
                                        class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal
                                        Penyusutan</label>
                                    <input type="text" class="form-input tanggal_penyusutan" name="tanggal_penyusutan"
                                        id="datepicker-basic">
                                </div>
                            </div>
                        </div>
                        <!-- end row -->

                        <!-- row kedua -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                            <div>
                                <div class="mb-3">
                                    <label for="masa_manfaat"
                                        class="text-gray-800 text-sm font-medium inline-block mb-2 flex items-center">
                                        <input type="checkbox" id="enable_masa_manfaat"
                                            class="form-checkbox rounded text-primary mr-2"
                                            onchange="toggleFields('masa_manfaat', 'nilai_tahun')">
                                        Masa Manfaat (Tahun)
                                    </label>
                                    <div class="flex">
                                        <input type="number" class="form-input" id="masa_manfaat" name="masa_manfaat"
                                            aria-describedby="masa_manfaat" placeholder="Masukan Masa Manfaat">
                                        <div
                                            class="inline-flex items-center px-4 rounded-e border border-s-0 border-gray-200 bg-gray-200 text-gray-500 dark:bg-gray-700 dark:border-gray-700 dark:text-gray-400">
                                            Tahun
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="mb-3">
                                    <label for="nilai_tahun"
                                        class="text-gray-800 text-sm font-medium inline-block mb-2 flex items-center">
                                        <input type="checkbox" id="enable_nilai_tahun"
                                            class="form-checkbox rounded text-primary mr-2"
                                            onchange="toggleFields('nilai_tahun', 'masa_manfaat')">
                                        Nilai/Tahun
                                    </label>
                                    <div class="flex">
                                        <input type="number" class="form-input" id="nilai_tahun" name="nilai_tahun"
                                            aria-describedby="nilai_tahun">
                                        <div
                                            class="inline-flex items-center px-4 rounded-e border border-s-0 border-gray-200 bg-gray-200 text-gray-500 dark:bg-gray-700 dark:border-gray-700 dark:text-gray-400">
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
                                    <label for="akun_penyusutan"
                                        class="text-gray-800 text-sm font-medium inline-block mb-2">Akun Penyusutan</label>
                                    <select id="akun_penyusutan" name="akun_penyusutan"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="" selected>-- Pilih Akun --</option>
                                        @foreach ($akun_penyusutan as $penyusutan)
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
                                    <label for="akumulasi_akun"
                                        class="text-gray-800 text-sm font-medium inline-block mb-2">Akumulasi Akun
                                        Penyusutan</label>
                                    <select id="akumulasi_akun" name="akumulasi_akun"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="" selected>-- Akun Akumulasi --</option>
                                        @foreach ($akun_penyusutan as $penyusutan)
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
    <x-modals.kontak.tambah-kontak />
    <x-modals.kategori.tambah-kategori />
    <x-modals.satuan.tambah-satuan />
@endsection

@section('script')
    @vite(['resources/js/pages/table-gridjs.js'])
    @vite(['resources/js/pages/highlight.js', 'resources/js/pages/form-select.js'])
    @vite(['resources/js/pages/highlight.js', 'resources/js/pages/form-flatpickr.js', 'resources/js/pages/form-color-pickr.js'])
    @vite(['resources/js/pages/extended-sweetalert.js'])
    @vite(['resources/js/pages/highlight.js'])
    <!-- <script src="{{ asset('js/custom-js/assets.js') }}" defer></script> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script src="{{ asset('js/custom-js/tambah-assets.js') }}" defer></script>
    <script>
        const selectOptionPemasok = document.getElementById('pemasok');
        selectOptionPemasok.addEventListener("change", function() {
            if (this.value === 'tambah_pemasok') {
                document.querySelector('[data-fc-target="tambahKontak"]').click();
            }
        });

        const selectOption = document.getElementById('kategori');
        const overlay = document.getElementById('modal-overlay'); // Ambil elemen overlay
        const kategoriModal = document.getElementById('kategoriModal');

        // Event listener untuk select
        selectOption.addEventListener('change', function() {
            console.log(this.value);
            if (this.value === 'tambahKategori') {
                // Buka modal dan overlay
                kategoriModal.classList.remove('hidden');
                kategoriModal.classList.add('flex');
                overlay.classList.remove('hidden'); // Tampilkan overlay
            }
        });

        document.querySelectorAll('[data-modal-hide="kategoriModal"]').forEach((button) => {
            button.addEventListener('click', () => {
                kategoriModal.classList.add('hidden');
                kategoriModal.classList.remove('flex');
                overlay.classList.add('hidden'); // Sembunyikan overlay
            });
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

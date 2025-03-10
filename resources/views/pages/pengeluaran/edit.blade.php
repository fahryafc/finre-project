@extends('layouts.vertical', ['title' => 'Edit Data Pengeluaran', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('css')
    @vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
    @vite(['node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/@simonwep/pickr/dist/themes/classic.min.css', 'node_modules/@simonwep/pickr/dist/themes/monolith.min.css', 'node_modules/@simonwep/pickr/dist/themes/nano.min.css'])
    @vite(['node_modules/sweetalert2/dist/sweetalert2.min.css'])
    <style>
        .hidden {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="col-span-2">
        <div class="card">
            <div class="card-header">
                <div class="flex justify-between items-center">
                    <h4 class="card-title">Edit Data Pengeluaran</h4>
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
                <form id="" action="{{ route('pengeluaran.update', $pengeluaran->id_pengeluaran) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-3 gap-4">
                        <div class="mb-3">
                            <label for="tanggal"
                                class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal</label>
                            <input type="text" class="form-input" name="tanggal" id="datepicker-basic"
                                value="{{ old('tanggal', $pengeluaran->tanggal) }}">
                        </div>
                    </div>
                    <hr class=" border-2 border-gray-300 my-2">
                    <div class="grid grid-cols-2 gap-4">
                        <!-- col 1 -->
                        <div class="mb-3">
                            <label for="nm_pengeluaran" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama
                                Pengeluaran</label>
                            <input type="text" class="form-input" id="nm_pengeluaran" name="nm_pengeluaran"
                                aria-describedby="nm_pengeluaran" placeholder="Masukan Nama Pengeluaran"
                                value="{{ old('nm_pengeluaran', $pengeluaran->nm_pengeluaran) }}">
                        </div>
                        <div class=" mb-3">
                            <label for="kategori"
                                class="text-gray-800 text-sm font-medium inline-block mb-2">Kategori</label>
                            <select id="kategori"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                name="kategori">
                                <option value="" selected>-- Pilih Kategori --</option>
                                @foreach ($kategori as $k)
                                    <option value="{{ $k->nama_kategori }}"
                                        {{ old('kategori', $pengeluaran->kategori) == $k->nama_kategori ? 'selected' : '' }}>
                                        {{ $k->nama_kategori }}</option>
                                @endforeach
                                <option value="tambah_ketegori">+ Tambah Ketegori</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jenis_pengeluaran" class="text-gray-800 text-sm font-medium inline-block mb-2">
                                Jenis Pengeluaran </label>
                            <select id="jenis_pengeluaran" name="jenis_pengeluaran"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                onchange="togglePengeluaran()">
                                <option value="" selected>-- Pilih Jenis Pengeluaran --</option>
                                <option value="gaji_karyawan"
                                    {{ old('jenis_pengeluaran', $pengeluaran->jenis_pengeluaran) == 'gaji_karyawan' ? 'selected' : '' }}>
                                    Gaji Karyawan</option>
                                <option value="pembayaran_vendor"
                                    {{ old('jenis_pengeluaran', $pengeluaran->jenis_pengeluaran) == 'pembayaran_vendor' ? 'selected' : '' }}>
                                    Pembayaran Vendor</option>
                            </select>
                        </div>
                        <div class="mb-3 hidden" id="div_nama_karyawan">
                            <label for="nama_karyawan" class="text-gray-800 text-sm font-medium inline-block mb-2"> Nama
                                Karyawan </label>
                            <select id="nama_karyawan" name="id_kontak"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Nama Karyawan --</option>
                                @foreach ($karyawanKontak as $karyawan)
                                    <option value="{{ $karyawan->id_kontak }}"
                                        {{ old('id_kontak', $pengeluaran->id_kontak) == $karyawan->id_kontak ? 'selected' : '' }}>
                                        {{ $karyawan->nama_kontak }}</option>
                                @endforeach

                                <option value="tambah_karyawan" data-fc-target="tambahKontak" data-fc-type="modal">+ Tambah
                                    Karyawan</option>
                            </select>
                        </div>
                        <div class="mb-3 hidden" id="div_nama_vendor">
                            <label for="nama_vendor" class="text-gray-800 text-sm font-medium inline-block mb-2"> Nama
                                Vendor </label>
                            <select id="nama_vendor" name="id_kontak"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected>-- Pilih Vendor --</option>
                                @foreach ($vendorKontak as $vendors)
                                    <option value="{{ $vendors->id_kontak }}"
                                        {{ old('id_kontak', $pengeluaran->id_kontak) == $vendors->id_kontak ? 'selected' : '' }}>
                                        {{ $vendors->nama_kontak }}</option>
                                @endforeach
                                <option value="tambah_pemasok" data-fc-target="tambahKontak" data-fc-type="modal">+ Tambah
                                    Vendor</option>
                            </select>
                        </div>
                    </div>
                    <hr class="border-2 border-gray-300 my-2">
                    <!-- col 2 -->
                    <div class="grid grid-cols-3 gap-4">
                        <div class="mb-3">
                            <label for="biaya" class="text-gray-800 text-sm font-medium inline-block mb-2">Biaya</label>
                            <input type="text" class="form-input" id="biaya" name="biaya" aria-describedby="biaya"
                                placeholder="Masukan Biaya"
                                value="{{ 'Rp. ' . number_format(old('biaya', parseRupiahToNumber($pengeluaran->biaya), 0, '.', '.')) }}">
                        </div>
                        <div class="mb-3">
                            <label for="akun_pembayaran"
                                class="text-gray-800 text-sm font-medium inline-block mb-2">Dibayarkan dari akun</label>
                            <select id="akun_pembayaran"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                name="akun_pembayaran">
                                <option value="" selected>-- Pilih Akun --</option>
                                @foreach ($kas_bank as $akun)
                                    <option value="{{ $akun->kode_akun }}"
                                        {{ old('akun_pembayaran', $pengeluaran->akun_pembayaran) == $akun->kode_akun ? 'selected' : '' }}>
                                        {{ $akun->nama_akun }} - {{ $akun->kode_akun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="akun_pemasukan"
                                class="text-gray-800 text-sm font-medium inline-block mb-2">Pengeluaran masuk akun</label>
                            <select id="akun_pemasukan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                name="akun_pemasukan">
                                <option value="" selected>-- Pilih Akun --</option>
                                @foreach ($kas_bank as $akun)
                                    <option value="{{ $akun->kode_akun }}"
                                        {{ old('akun_pemasukan', $pengeluaran->akun_pemasukan) == $akun->kode_akun ? 'selected' : '' }}>
                                        {{ $akun->nama_akun }} - {{ $akun->kode_akun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr class="border-2 border-gray-300 my-2">
                    <!-- col 3 -->

                    <div class="grid grid-cols-6">
                        <div class="mb-3">
                            <div class="flex items-center">
                                <input type="checkbox" id="pajakButton" name="pajakButton"
                                    class="form-switch text-primary" value="1" onchange="toggleCollapsePajak()"
                                    {{ $pengeluaran->pajak == 1 ? 'checked' : '' }}>
                                <label for="pajakButton" class="ms-1.5">Pajak</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="flex items-center">
                                <input type="checkbox" id="hutangButton" name="hutangButton"
                                    class="form-switch text-primary" value="1"
                                    {{ $pengeluaran->hutang == 1 ? 'checked' : '' }}>
                                <label for="hutangButton" class="ms-1.5">Hutang</label>
                            </div>
                        </div>
                    </div>

                    <!-- collapse pajak -->
                    <div id="collapsePajak"
                        class="w-full overflow-hidden transition-[height] duration-300 mt-5 {{ $pengeluaran->pajak == 1 ? '' : 'hidden' }}">
                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">Pajak</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-3">
                            <div class="mb-3">
                                <label for="jns_pajak" class="text-gray-800 text-sm font-medium inline-block mb-2">Jenis
                                    Pajak</label>
                                <select id="jns_pajak" name="jns_pajak"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    onchange="aturPajakDanHitung()">
                                    <option value="" selected>-- Pilih Jenis Pajak --</option>
                                    <option value="ppn"
                                        {{ old('jns_pajak', $pengeluaran->jns_pajak) == 'ppn' ? 'selected' : '' }}>PPN
                                    </option>
                                    <option value="ppnbm"
                                        {{ old('jns_pajak', $pengeluaran->jns_pajak) == 'ppnbm' ? 'selected' : '' }}>PPnBM
                                    </option>
                                    <option value="pph"
                                        {{ old('jns_pajak', $pengeluaran->jns_pajak) == 'pph' ? 'selected' : '' }}>PPH
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3" id="pajakPersenContainer">
                                <label for="pajak_persen"
                                    class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak (%)</label>
                                <input type="text" class="form-input" id="pajak_persen" name="pajak_persen"
                                    aria-describedby="pajak_persen" placeholder="Masukan Pajak (%)"
                                    value="{{ old('pajak_persen', $pengeluaran->pajak_persen) }}"
                                    oninput="hitungPajak()">
                            </div>
                            <div class="mb-3">
                                <label for="pajak_dibayarkan"
                                    class="text-gray-800 text-sm font-medium inline-block mb-2">Pajak Dibayarkan
                                    (Rp)</label>
                                <input type="text" class="form-input bg-gray-300 text-gray-500 cursor-not-allowed"
                                    id="pajak_dibayarkan" name="pajak_dibayarkan" aria-describedby="pajak_dibayarkan"
                                    placeholder="Pajak Dibayarkan"
                                    value="{{ 'Rp. ' . number_format(old('pajak_dibayarkan', parseRupiahToNumber($pengeluaran->pajak_dibayarkan), 0, '.', '.')) }}"
                                    readonly>
                            </div>
                        </div>
                        <hr class="border-2 border-gray-300 my-2"> <!-- Garis pemisah -->
                    </div>

                    <!-- collapse Hutang -->
                    <div id="collapseHutang"
                        class="hidden w-full overflow-hidden transition-[height] duration-300 mt-5 {{ $pengeluaran->hutang == 1 ? '' : 'hidden' }}">
                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                Hutang
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-3">
                            <div class="mb-3">
                                <label for="nominal_hutang"
                                    class="text-gray-800 text-sm font-medium inline-block mb-2">Hutang</label>
                                <input type="text" class="form-input" id="nominal_hutang" name="nominal_hutang"
                                    aria-describedby="hutang" placeholder="Masukan Nominal Hutang"
                                    value="{{ 'Rp. ' . number_format(old('nominal_hutang', parseRupiahToNumber($pengeluaran->nominal_hutang), 0, '.', '.')) }}">
                            </div>
                            <div class="mb-3">
                                <label for="tgl_jatuh_tempo"
                                    class="text-gray-800 text-sm font-medium inline-block mb-2">Tanggal Jatuh Tempo</label>
                                <input type="text" class="form-input" name="tgl_jatuh_tempo" id="datepicker-basic">
                            </div>
                        </div>
                        <hr class="border-2 border-gray-300 my-2"> <!-- Garis pemisah -->
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div></div>
                        <div class="mb-4">
                            <div class="flex w-full items-center">
                                <label class="text-gray-800 text-sm font-medium p-2 w-1/3">Total Transaksi</label>
                                <input type="text" id="total_transaksi" name="total_transaksi"
                                    class="form-input bg-[#307487] text-white rounded flex-1" value="Total Transaksi"
                                    readonly>
                            </div>
                        </div>
                        <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                            <button onclick="window.history.back()" type="button"
                                class="btn dark:text-gray-200 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 hover:dark:bg-slate-700 transition-all"
                                data-fc-dismiss type="button">Close
                            </button>
                            <button type="submit" class="btn bg-[#307487] text-white" type="submit">Save</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
    <x-modals.kontak.tambah-kontak />
    <x-modals.kategori.tambah-kategori />
@endsection

@section('script')
    @vite(['resources/js/pages/table-gridjs.js'])
    @vite(['resources/js/pages/highlight.js'])
    @vite(['resources/js/pages/highlight.js', 'resources/js/pages/form-flatpickr.js', 'resources/js/pages/form-color-pickr.js'])
    @vite(['resources/js/pages/extended-sweetalert.js'])
    @vite(['resources/js/pages/highlight.js'])
    <script src="{{ asset('js/custom-js/create_pengeluaran.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>

    <script>
        const selectOption = document.getElementById('kategori');
        const overlay = document.getElementById('modal-overlay'); // Ambil elemen overlay
        const kategoriModal = document.getElementById('kategoriModal');

        // Event listener untuk select
        selectOption.addEventListener('change', function() {
            console.log(this.value);
            if (this.value === 'tambah_ketegori') {
                // Buka modal dan overlay
                kategoriModal.classList.remove('hidden');
                kategoriModal.classList.add('flex');
                overlay.classList.remove('hidden'); // Tampilkan overlay
            }
        });

        // Event untuk tombol tutup modal
        document.querySelectorAll('[data-modal-hide="kategoriModal"]').forEach((button) => {
            button.addEventListener('click', () => {
                kategoriModal.classList.add('hidden');
                kategoriModal.classList.remove('flex');
                overlay.classList.add('hidden'); // Sembunyikan overlay
            });
        });

        const selectOptionKontak = document.getElementById('nama_vendor');
        selectOptionKontak.addEventListener('change', function() {
            if (this.value === 'tambah_pemasok') {
                // Buka modal dan overlay
                document.querySelector('[data-fc-target="tambahKontak"]').click();
            }
        });
        const selectOptionKaryawan = document.getElementById('nama_karyawan');
        selectOptionKaryawan.addEventListener('change', function() {
            if (this.value === 'tambah_karyawan') {
                // Buka modal dan overlay
                document.querySelector('[data-fc-target="tambahKontak"]').click();
            }
        });
    </script>
@endsection

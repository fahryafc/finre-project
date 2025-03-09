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
                            <input type="text" class="form-input" id="nama_kontak" name="nama_kontak" aria-describedby="nama_kontak" placeholder="Masukan Nama Kontak" required>
                        </div>
                    </div>
                    <div>
                        <div class="mb-3">
                            <label for="jenis_kontak" class="text-gray-800 text-sm font-medium inline-block mb-2">Jenis Kontak</label>
                            <select id="jenis_kontak" name="jenis_kontak" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                <option value="" selected>-- Pilih Jenis Kontak --</option>
                                <option value="pelanggan">Pelanggan</option>
                                <option value="karyawan">Karyawan</option>
                                <option value="vendor">Vendor</option>
                                <option value="investor">Investor</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <div class="mb-3">
                            <label for="email" class="text-gray-800 text-sm font-medium inline-block mb-2">Email</label>
                            <input type="text" class="form-input" id="email" name="email" aria-describedby="email" placeholder="Masukan Email" required>
                        </div>
                    </div>
                    <div>
                        <div class="mb-3">
                            <label for="no_hp" class="text-gray-800 text-sm font-medium inline-block mb-2">No Handphone</label>
                            <input type="text" class="form-input" id="no_hp" name="no_hp" aria-describedby="no_hp" placeholder="Masukan No Handphone" required>
                        </div>
                    </div>
                    <div>
                        <div class="mb-3">
                            <label for="nm_perusahaan" class="text-gray-800 text-sm font-medium inline-block mb-2" required >Nama Perusahaan</label>
                            <input type="text" class="form-input" id="nm_perusahaan" name="nm_perusahaan" aria-describedby="nm_perusahaan" placeholder="Masukan Nama Perusahaan" required>
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

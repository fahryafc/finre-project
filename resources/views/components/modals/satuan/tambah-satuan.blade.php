<div id="tambahSatuan" class="w-full h-full mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
    <div class="sm:max-w-md fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                Tambah Satuan
            </h3>
            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <form action="{{ route('satuan.store') }}" method="POST">
            @csrf
            <div class="px-4 py-8 overflow-y-auto">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <div class="mb-3">
                            <label for="nama_satuan" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Satuan</label>
                            <input type="text" class="form-input" id="nama_satuan" name="nama_satuan" placeholder="Masukkan Nama Satuan" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                <button class="btn dark:text-gray-200 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">
                    Close
                </button>
                <button class="btn bg-[#307487] text-white" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>

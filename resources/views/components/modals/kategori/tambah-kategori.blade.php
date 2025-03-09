<div id="kategoriModal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div id="modal-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Tambah Kategori
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-toggle="kategoriModal" data-modal-hide="kategoriModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf
                <div class="px-4 py-8 overflow-y-auto">
                    <div class="grid grid-cols-1">
                        <!-- Input untuk Nama Kategori -->
                        <div>
                            <div class="mb-3">
                                <label for="nama_kategori"
                                    class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Kategori</label>
                                <input type="text" class="form-input" id="nama_kategori" name="nama_kategori"
                                    aria-describedby="nama_kategori" placeholder="Masukan Nama Kategori" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                    <button type="button"
                        class="btn dark:text-gray-200 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 hover:dark:bg-slate-700 transition-all"
                        data-modal-toggle="kategoriModal" data-modal-hide="kategoriModal">
                        Close
                    </button>
                    <button class="btn bg-[#307487] text-white" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

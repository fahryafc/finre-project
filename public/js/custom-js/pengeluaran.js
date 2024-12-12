flatpickr("#datepicker-basic", {
    dateFormat: "d-m-Y",
    defaultDate: "today"
});

document.addEventListener('DOMContentLoaded', function () {
    const collapsibleLinks = document.querySelectorAll('[data-fc-type="collapse"]');

    collapsibleLinks.forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault();

            const subMenu = this.nextElementSibling;
            if (subMenu) {
                if (subMenu.classList.contains('hidden')) {
                    subMenu.classList.remove('hidden');
                } else {
                    subMenu.classList.add('hidden');
                }
            }
        });
    });
});

function validateNumberInput(input) {
    const originalValue = input.value;
    const numericValue = originalValue.replace(/\D/g, '');

    const errorDiv = document.getElementById('no_hp_error');

    // Cek jika ada karakter non-angka
    if (originalValue !== numericValue) {
        errorDiv.classList.remove('hidden'); // Tampilkan pesan error
    } else {
        errorDiv.classList.add('hidden'); // Sembunyikan pesan error
    }

    input.value = numericValue; // Set kembali nilai input dengan angka saja
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

function toggleCollapseHutang() {
    const collapseElementHutang = document.getElementById('collapseHutang');
    const hutang = document.getElementById('hutangButton');
    // Toggle class based on the switch status
    if (hutang.checked) {
        collapseElementHutang.classList.remove('hidden');
    } else {
        collapseElementHutang.classList.add('hidden');
    }
}

function formatRupiah(input) {
    let angka = input.value.replace(/[^,\d]/g, '');
    let split = angka.split(',');
    let sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    input.value = 'Rp ' + rupiah;

    hitungPajak(); // Memanggil fungsi perhitungan setelah format
}

// Fungsi untuk menghapus format Rupiah dan konversi ke angka
function parseRupiahToNumber(rupiah) {
    // Hapus karakter selain angka dan koma, kemudian ganti koma menjadi titik desimal untuk angka desimal
    return parseFloat(rupiah.replace(/[^,\d]/g, '').replace(',', '.')) || 0;
}

document.addEventListener('DOMContentLoaded', () => {
    function togglePengeluaran() {
        const jenisPengeluaran = document.getElementById('jenis_pengeluaran').value;
        const divNamaKaryawan = document.getElementById('div_nama_karyawan');
        const divNamaVendor = document.getElementById('div_nama_vendor');
        const namaKaryawan = document.getElementById('nama_karyawan');
        const namaVendor = document.getElementById('nama_vendor');

        // Sembunyikan kedua elemen terlebih dahulu
        divNamaKaryawan.classList.add('hidden');
        divNamaVendor.classList.add('hidden');
        namaKaryawan.disabled = true;
        namaVendor.disabled = true;

        // Tampilkan elemen berdasarkan pilihan dan aktifkan input yang benar
        if (jenisPengeluaran === "gaji_karyawan") {
            divNamaKaryawan.classList.remove('hidden');
            namaKaryawan.disabled = false;
        } else if (jenisPengeluaran === "pembayaran_vendor") {
            divNamaVendor.classList.remove('hidden');
            namaVendor.disabled = false;
        }
    }

    document.getElementById('jenis_pengeluaran').addEventListener('change', togglePengeluaran);
    togglePengeluaran();
});

function aturPajakDanHitung() {
    const jenisPajak = document.getElementById('jns_pajak').value;
    const pajakPersenInput = document.getElementById('pajak_persen');
    const pajakDibayarkanInput = document.getElementById('pajak_dibayarkan');
    const pajakPersenContainer = document.getElementById('pajakPersenContainer');

    if (jenisPajak === 'ppn') {
        pajakPersenInput.value = '11';
        pajakPersenInput.disabled = true;
        pajakPersenContainer.classList.remove('hidden');
        pajakDibayarkanInput.readOnly = true;
        pajakDibayarkanInput.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        pajakDibayarkanInput.value = ''; // Reset nilai pajak dibayarkan
    } else if (jenisPajak === 'ppnbm') {
        pajakPersenInput.value = '';
        pajakPersenInput.disabled = false;
        pajakPersenContainer.classList.remove('hidden');
        pajakDibayarkanInput.readOnly = true;
        pajakDibayarkanInput.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        pajakDibayarkanInput.value = ''; // Reset nilai pajak dibayarkan
    } else if (jenisPajak === 'pph') {
        pajakPersenInput.value = '';
        pajakPersenInput.disabled = true;
        pajakPersenContainer.classList.add('hidden');
        pajakDibayarkanInput.readOnly = false;
        pajakDibayarkanInput.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        pajakDibayarkanInput.placeholder = "Masukan Nominal Pajak Dibayarkan";
    } else {
        pajakPersenInput.value = '';
        pajakPersenInput.disabled = false;
        pajakPersenContainer.classList.remove('hidden');
        pajakDibayarkanInput.readOnly = true;
        pajakDibayarkanInput.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        pajakDibayarkanInput.value = ''; // Reset nilai pajak dibayarkan
    }

    hitungPajak();
}

function hitungPajak() {
    const biaya = parseFloat(document.getElementById('biaya').value.replace(/[^0-9]/g, '')) || 0;
    const pajakPersen = parseFloat(document.getElementById('pajak_persen').value) || 0;
    const pajakDibayarkanInput = document.getElementById('pajak_dibayarkan');
    const jnsPajak = document.getElementById('jns_pajak').value;

    if (jnsPajak === 'pph') {
        // Biarkan pengguna menginput manual, tidak ada perhitungan otomatis
        pajakDibayarkanInput.oninput = function () {
            formatRupiah(pajakDibayarkanInput);
        };
    } else if (biaya > 0 && jnsPajak) {
        // Hitung Pajak Dibayarkan untuk PPN dan PPnBM
        const pajakDibayarkan = (biaya * pajakPersen) / 100;
        pajakDibayarkanInput.value = 'Rp ' + pajakDibayarkan.toLocaleString('id-ID', { minimumFractionDigits: 2 });
    } else {
        pajakDibayarkanInput.value = "";
    }
}

function prepareForSubmit() {
    const biaya = document.getElementById('biaya');
    const pajakDibayarkan = document.getElementById('pajak_dibayarkan');

    // Hapus format Rupiah dari kedua input
    if (biaya) {
        biaya.value = parseRupiahToNumber(biaya.value);
    }
    if (pajakDibayarkan) {
        pajakDibayarkan.value = parseRupiahToNumber(pajakDibayarkan.value); // Perbaikan untuk pajak
    }
}

// Tambahkan event listener submit pada form untuk memanggil fungsi prepareForSubmit
document.querySelector('form').addEventListener('submit', prepareForSubmit);
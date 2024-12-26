flatpickr(".tanggal_pembelian", {
    dateFormat: "d-m-Y",
    defaultDate: 'today',
});

flatpickr(".tanggal_penyusutan", {
    dateFormat: "d-m-Y",
    defaultDate: 'today',
});

function convertToYMD(dateStr) {
    const [day, month, year] = dateStr.split('-');
    return `${year}-${month}-${day}`;
}

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

if (document.getElementById("asset-terjual") && typeof simpleDatatables.DataTable !== 'undefined') {
    const dataTable = new simpleDatatables.DataTable("#asset-terjual", {
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

// Fungsi untuk menghapus format Rupiah dan konversi ke angka
function parseRupiahToNumber(rupiah) {
    return parseInt(rupiah.replace(/[^,\d]/g, '').replace(',', '')) || 0; // Hapus karakter selain angka dan konversi ke integer
}

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
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById('masa_manfaat').disabled = true; // Awalnya dinonaktifkan
    document.getElementById('nilai_tahun').disabled = true; // Awalnya dinonaktifkan
});

function formatRupiah(value) {
    return 'Rp ' + new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(value);
}

document.getElementById('harga_beli').addEventListener('input', function () {
    const hargaBeli = this.value;
    const hargaFormatted = formatRupiah(hargaBeli); // Format nilai input menjadi Rupiah
});
// Event listener untuk format input harga beli
document.getElementById('harga_beli').addEventListener('input', function () {
    let angka = this.value.replace(/[^,\d]/g, "").toString();
    this.value = formatRupiah(angka);
});

// Perhitungan Pajak
function hitungPajak() {
    // Ambil nilai inputan
    const hargaBeli = parseFloat(document.getElementById('harga_beli').value.replace(/\D/g, '')) || 0;
    const kuantitas = parseInt(document.getElementById('kuantitas').value) || 0;
    const persenPajak = parseFloat(document.getElementById('persen_pajak').value) || 0;

    // Perhitungan Pajak
    const pajak_dibayarkan = (hargaBeli * kuantitas * persenPajak) / 100;

    // Set hasil ke input field
    document.getElementById('pajak_dibayarkan').value = formatRupiah(pajak_dibayarkan);
}

// Event Listeners untuk Inputan
document.addEventListener('DOMContentLoaded', () => {
    const inputs = ['harga_beli', 'kuantitas', 'persen_pajak'];

    inputs.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('input', hitungPajak);
        }
    });

    // Jenis Pajak
    const jenisPajakSelect = document.getElementById('jns_pajak');
    const persenPajakInput = document.getElementById('persen_pajak');

    jenisPajakSelect.addEventListener('change', function () {
        if (jenisPajakSelect.value === 'ppn11') {
            persenPajakInput.value = '11';
            persenPajakInput.setAttribute('readonly', 'readonly');
            persenPajakInput.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        } else if (jenisPajakSelect.value === 'ppn12') {
            persenPajakInput.value = '12';
            persenPajakInput.setAttribute('readonly', 'readonly');
            persenPajakInput.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        } else if (jenisPajakSelect.value === 'ppnbm') {
            persenPajakInput.value = ''; // Clear value
            persenPajakInput.removeAttribute('readonly');
            persenPajakInput.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        } else {
            persenPajakInput.value = ''; // Clear value
            persenPajakInput.setAttribute('readonly', 'readonly');
            persenPajakInput.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        }
        hitungPajak();
    });

    jenisPajakSelect.dispatchEvent(new Event('change')); // Set default behavior
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

    const tanggalPembelian = document.querySelector('.tanggal_pembelian');
    const tanggalPenyusutan = document.querySelector('.tanggal_penyusutan');
    if (tanggalPembelian) {
        tanggalPembelian.value = convertToYMD(tanggalPembelian.value);
    }
    if (tanggalPenyusutan) {
        tanggalPenyusutan.value = convertToYMD(tanggalPenyusutan.value);
    }

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
document.addEventListener('DOMContentLoaded', function () {
    const tanggalInput = document.querySelector('.tgl_edit');
    const defaultDate = tanggalInput.getAttribute('data-tanggal'); // Ambil nilai dari data-tanggal

    flatpickr(".tgl_edit", {
        dateFormat: "d-m-Y",
        defaultDate: defaultDate
    });
});

flatpickr(".tgl_penjualan", {
    dateFormat: "d-m-Y",
    defaultDate: 'today',
});

if (document.getElementById("penjualan-table") && typeof simpleDatatables.DataTable !== 'undefined') {
    const dataTable = new simpleDatatables.DataTable("#penjualan-table", {
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

function togglePiutangInput() {
    const piutangInputContainer = document.getElementById('piutangInputContainer');
    const tglJatuhTempoContainer = document.getElementById('tglJatuhTempoContainer');
    const piutangSwitch = document.getElementById('piutangSwitch');

    // Toggle visibility based on switch state
    if (piutangSwitch.checked) {
        piutangInputContainer.classList.remove('hidden');
        tglJatuhTempoContainer.classList.remove('hidden');
    } else {
        piutangInputContainer.classList.add('hidden');
        tglJatuhTempoContainer.classList.add('hidden');
    }
}

// Automatically show input if piutang switch is checked on page load
document.addEventListener('DOMContentLoaded', function () {
    const piutangSwitches = document.querySelectorAll('input[name="piutangSwitch"]');
    piutangSwitches.forEach(function (switchElement) {
        if (switchElement.checked) {
            const idPenjualan = switchElement.id.replace('piutangSwitch', '');
            const piutangInputContainer = document.getElementById('piutangInputContainer' + idPenjualan);
            piutangInputContainer.classList.remove('hidden');
        }
    });
});

function parseRupiahToNumber(rupiah) {
    // Hapus karakter selain angka dan koma, serta awalan "Rp" jika ada
    return parseFloat(rupiah.replace(/Rp\s?|[^,\d]/g, '').replace(',', '.')) || 0;
}

// Fungsi untuk memformat nilai rupiah
function formatRupiah(value) {
    return 'Rp ' + new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(value);
}

// Fungsi untuk perhitungan diskon dan pajak otomatis
function calculateTotal() {
    const harga = parseFloat(document.getElementById("harga").value.replace(/\D/g, '')) || 0;
    const ongkir = parseFloat(document.getElementById("ongkir").value.replace(/\D/g, '')) || 0;
    const kuantitas = parseFloat(document.getElementById("kuantitas").value) || 0;
    const diskonInput = parseFloat(document.getElementById("diskon").value) || 0;
    const pajakInput = parseFloat(document.getElementById("persen_pajak").value) || 0;
    const piutang = parseRupiahToNumber(document.getElementById("piutang").value) || 0;

    // Perhitungan total harga
    const totalHarga = harga * kuantitas;

    // Perhitungan diskon
    const diskon = (totalHarga * diskonInput) / 100;
    document.querySelector(".diskon-output").textContent = `Rp ${diskon ? diskon.toLocaleString('id-ID') : '0'}`;

    // Perhitungan pajak
    const totalHargaDiskon = totalHarga - diskon;
    const pajak = (totalHargaDiskon * pajakInput) / 100;
    document.querySelector(".pajak-output").value = `Rp ${pajak ? pajak.toLocaleString('id-ID') : '0'}`;

    const total_pemasukan = totalHargaDiskon - piutang + ongkir;
    document.getElementById("total_pemasukan").value = formatRupiah(total_pemasukan);

    // Check jika input tidak terisi atau bernilai 0, maka tampilkan Rp 0
    if (!harga || !kuantitas || diskonInput <= 0) {
        document.querySelector(".diskon-output").textContent = "Rp 0";
    }

    if (!harga || !kuantitas || pajakInput <= 0) {
        document.querySelector(".pajak-output").value = "Rp 0";
    }
}

// Event listener untuk memanggil fungsi saat input berubah
document.getElementById("harga").addEventListener("input", calculateTotal);
document.getElementById("kuantitas").addEventListener("input", calculateTotal);
document.getElementById("diskon").addEventListener("input", calculateTotal);
document.getElementById("persen_pajak").addEventListener("input", calculateTotal);
// Event listener untuk input harga, kuantitas, diskon, dan pajak
document.getElementById("ongkir").addEventListener("input", function (e) {
    e.target.value = formatRupiah(e.target.value.replace(/[^,\d]/g, ''));
    calculateTotal();
});

document.getElementById("harga").addEventListener("input", function (e) {
    e.target.value = formatRupiah(e.target.value.replace(/[^,\d]/g, ''));
    calculateTotal();
});
document.getElementById("piutang").addEventListener("input", function (e) {
    e.target.value = formatRupiah(e.target.value.replace(/[^,\d]/g, ''));
    calculateTotal();
});
document.getElementById("kuantitas").addEventListener("input", function () {
    calculateTotal();
});
document.getElementById("diskon").addEventListener("input", function () {
    calculateTotal();
});
document.getElementById("persen_pajak").addEventListener("input", function () {
    calculateTotal();
});

function prepareForSubmit() {
    const harga = document.getElementById('harga');
    const ongkir = document.getElementById('ongkir');
    const piutang = document.getElementById('piutang');
    const total_pemasukan = document.getElementById('total_pemasukan');

    // Hapus format Rupiah dari kedua input
    if (harga) {
        harga.value = parseRupiahToNumber(harga.value);
    }
    if (piutang) {
        piutang.value = parseRupiahToNumber(piutang.value);
    }
    if (ongkir) {
        ongkir.value = parseRupiahToNumber(ongkir.value);
    }
    if (total_pemasukan) {
        total_pemasukan.value = parseRupiahToNumber(total_pemasukan.value);
    }
}

// Tambahkan event listener submit pada form untuk memanggil fungsi prepareForSubmit
document.querySelector('form').addEventListener('submit', prepareForSubmit);

// Event listener untuk memastikan perhitungan diupdate saat halaman di-load
document.addEventListener("DOMContentLoaded", function () {
    calculateTotal();
});

document.getElementById('jns_pajak').addEventListener('change', function () {
    const jenisPajak = this.value;
    const persenPajakInput = document.getElementById('persen_pajak');

    if (jenisPajak === 'ppn') {
        persenPajakInput.value = '11'; // Set default value to 11%
        persenPajakInput.setAttribute('disabled', 'disabled'); // Disable input
    } else if (jenisPajak === 'ppnbm') {
        persenPajakInput.value = ''; // Clear value
        persenPajakInput.removeAttribute('disabled'); // Enable input
    } else {
        persenPajakInput.value = ''; // Clear value
        persenPajakInput.setAttribute('disabled', 'disabled'); // Keep disabled
    }
});
document.getElementById('persen_pajak').setAttribute('disabled', 'disabled');
flatpickr(".tgl_penjualan", {
    dateFormat: "d-m-Y",
    defaultDate: 'today',
});

flatpickr(".tgl_jatuh_tempo", {
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
    let totalHarga = 0;
    let totalDiskon = 0;
    let totalPajak = 0;
    let totalPemasukan = 0;

    // Ambil semua baris produk
    const productRows = document.querySelectorAll('.product-row');

    // Loop melalui semua baris produk
    productRows.forEach(row => {
        // Ambil nilai dari setiap input di baris produk
        const harga = parseFloat(row.querySelector('.harga').value.replace(/\D/g, '')) || 0;
        const kuantitas = parseFloat(row.querySelector('.kuantitas').value) || 0;
        const diskonInput = parseFloat(row.querySelector('.diskon').value) || 0;
        const jenisPajak = row.querySelector('#jns_pajak').value;

        // Hitung harga total untuk baris produk ini (sebelum diskon)
        const totalHargaBaris = harga * kuantitas;

        // Tentukan persentase pajak berdasarkan jenis pajak
        let persenPajak = 0;
        if (jenisPajak === 'ppn11') {
            persenPajak = 11; // 11% untuk PPN 11%
        } else if (jenisPajak === 'ppn12') {
            persenPajak = 12; // 12% untuk PPN 12%
        } else if (jenisPajak === 'ppnbm') {
            // Jika jenis pajak PPnBM, ambil nilai persen pajak dari input pengguna
            persenPajak = parseFloat(row.querySelector('.persen_pajak').value) || 0;
        }

        // Hitung pajak berdasarkan harga sebelum diskon
        let pajak = 0;
        if (persenPajak > 0) {
            pajak = (totalHargaBaris * persenPajak) / 100;
        }

        // Tambahkan pajak awal (sebelum diskon) ke total pajak
        totalPajak += pajak;

        // Hitung diskon hanya jika ada
        const diskon = (totalHargaBaris * diskonInput) / 100;
        totalDiskon += diskon;  // Tambahkan diskon ke total kumulatif

        // Hitung pemasukan setelah diskon dan pajak
        const totalHargaDiskon = totalHargaBaris - diskon;
        totalHarga += totalHargaBaris;  // Tambahkan harga sebelum diskon ke total harga
        totalPemasukan += totalHargaDiskon;  // Tambahkan harga setelah diskon ke total pemasukan
    });

    // Ambil nilai ongkir dan piutang
    const ongkir = parseFloat(document.getElementById("ongkir").value.replace(/\D/g, '')) || 0;
    const piutang = parseRupiahToNumber(document.getElementById("piutang").value) || 0;

    // Hitung total pemasukan (dengan memasukkan ongkir dan piutang)
    totalPemasukan = totalPemasukan - piutang + ongkir;

    // Update nilai output diskon, pajak, dan total pemasukan
    document.querySelector(".diskon_output").value = `Rp ${totalDiskon ? totalDiskon.toLocaleString('id-ID') : '0'}`;
    document.querySelector(".pajak-output").value = `Rp ${totalPajak ? totalPajak.toLocaleString('id-ID') : '0'}`;
    document.getElementById("total_pemasukan").value = formatRupiah(totalPemasukan);
}

// Event listener untuk memanggil fungsi saat input berubah pada input individual
document.getElementById("harga").addEventListener("input", calculateTotal);
document.getElementById("kuantitas").addEventListener("input", calculateTotal);
document.getElementById("diskon").addEventListener("input", calculateTotal);
document.getElementById("persen_pajak").addEventListener("input", calculateTotal);

// Event delegation untuk semua input dalam #productRows
document.getElementById('productRows').addEventListener('input', function (e) {
    // Cek apakah target adalah elemen dengan kelas 'harga', 'ongkir', 'kuantitas', 'diskon', 'persen_pajak', atau id 'jns_pajak'
    if (e.target && (
        e.target.classList.contains('harga') ||
        e.target.classList.contains('kuantitas') ||
        e.target.classList.contains('diskon') ||
        e.target.classList.contains('persen_pajak') ||
        e.target.id === 'jns_pajak'
    )) {
        // Jika elemen adalah harga
        if (e.target.classList.contains('harga')) {
            // Menghapus karakter yang bukan angka atau koma
            e.target.value = e.target.value.replace(/[^0-9,]/g, '');
            // Format input menjadi Rupiah
            e.target.value = formatRupiah(e.target.value);
        }
        // Panggil fungsi untuk menghitung total setelah input berubah
        calculateTotal();
    }
});

document.getElementById("ongkir").addEventListener("input", function (e) {
    e.target.value = formatRupiah(e.target.value.replace(/[^,\d]/g, ''));
    calculateTotal();
});
document.getElementById("piutang").addEventListener("input", function (e) {
    e.target.value = formatRupiah(e.target.value.replace(/[^,\d]/g, ''));
    calculateTotal();
});

function prepareForSubmit() {
    // Ambil semua elemen input yang memiliki nama atau ID yang relevan
    const harga = document.querySelectorAll('[name="harga[]"]');
    const ongkir = document.getElementById('ongkir');
    const piutang = document.getElementById('piutang');
    const total_pemasukan = document.getElementById('total_pemasukan');
    const nominal_pajak = document.getElementById('nominal_pajak');
    const diskon_output = document.getElementById('diskon_output');

    // Hapus format Rupiah dari array harga
    if (harga) {
        harga.forEach(input => {
            input.value = parseRupiahToNumber(input.value);
        });
    }

    // Hapus format Rupiah dari input lainnya
    if (ongkir) {
        ongkir.value = parseRupiahToNumber(ongkir.value);
    }
    if (piutang) {
        piutang.value = parseRupiahToNumber(piutang.value);
    }
    if (total_pemasukan) {
        total_pemasukan.value = parseRupiahToNumber(total_pemasukan.value);
    }
    if (nominal_pajak) {
        nominal_pajak.value = parseRupiahToNumber(nominal_pajak.value);
    }
    if (diskon_output) {
        diskon_output.value = parseRupiahToNumber(diskon_output.value);
    }
}

// Tambahkan event listener submit pada form untuk memanggil fungsi prepareForSubmit
document.querySelector('form').addEventListener('submit', prepareForSubmit);

// Event listener untuk memastikan perhitungan diupdate saat halaman di-load
document.addEventListener("DOMContentLoaded", function () {
    calculateTotal();
});

document.getElementById('productRows').addEventListener('change', function (event) {
    // Memeriksa apakah event berasal dari elemen dengan id 'jns_pajak'
    if (event.target && event.target.id === 'jns_pajak') {
        const jenisPajak = event.target.value;
        const persenPajakInput = event.target.closest('.product-row').querySelector('.persen_pajak');

        if (jenisPajak === 'ppn11') {
            persenPajakInput.value = '11'; // Set default value to 11%
            persenPajakInput.setAttribute('readonly', 'readonly'); // Disable input
        } else if (jenisPajak === 'ppn12') {
            persenPajakInput.value = '12'; // Set default value to 12%
            persenPajakInput.setAttribute('readonly', 'readonly'); // Disable input
        } else if (jenisPajak === 'ppnbm') {
            persenPajakInput.value = ''; // Clear value
            persenPajakInput.removeAttribute('readonly'); // Enable input
        } else {
            persenPajakInput.value = ''; // Clear value
            persenPajakInput.setAttribute('readonly', 'readonly'); // Keep disabled
        }
    }
});
document.getElementById('persen_pajak').setAttribute('readonly', 'readonly');

function addProductRow(maxProducts) {
    const container = document.getElementById('productRows');
    const currentRows = container.querySelectorAll('.product-row').length; // Hitung jumlah baris yang ada

    if (currentRows >= maxProducts) {
        alert(`Maksimal baris yang bisa ditambahkan adalah ${maxProducts}`);
        return;
    }

    // Clone baris pertama sebagai template
    const template = document.querySelector('.product-row').cloneNode(true);

    // Bersihkan nilai input dan reset dropdown
    template.querySelectorAll('input').forEach(input => input.value = '');
    template.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

    // Tambahkan ke dalam container
    container.appendChild(template);
}


function deleteRow(button) {
    const totalRows = document.querySelectorAll('.product-row').length;

    // Prevent deletion if it's the last row
    if (totalRows > 1) {
        const row = button.closest('.product-row');
        row.remove();

        // Lakukan perhitungan ulang setelah baris dihapus
        calculateTotal();
    } else {
        alert('Minimal harus ada satu baris produk!');
    }
}

document.getElementById('productRows').addEventListener('change', function (event) {
    if (event.target && event.target.matches('select[name="produk[]"]')) {
        const selectedOption = event.target.options[event.target.selectedIndex];

        const productRow = event.target.closest('.product-row');

        const harga = selectedOption.getAttribute('data-harga');
        const satuan = selectedOption.getAttribute('data-satuan');
        const qty = selectedOption.getAttribute('data-qty');

        productRow.querySelector('input[name="harga[]"]').value = formatRupiah(harga) || '';
        productRow.querySelector('input[name="satuan[]"]').value = satuan || '';

        const inputKuantitas = productRow.querySelector('input[name="kuantitas[]"]');
        inputKuantitas.setAttribute('max', qty);
        inputKuantitas.value = ''; // Reset kuantitas saat ganti produk
    } else if (event.target && event.target.matches('input[name="kuantitas[]"]')){
        const productRow = event.target.closest('.product-row');
        const maxQty = parseInt(productRow.querySelector('select[name="produk[]"] option:checked').getAttribute('data-qty')) || 0;
        
        const currentQty = parseInt(event.target.value) || 0;

        if (currentQty > maxQty) {
            event.target.value = maxQty;
            alert(`Maksimal kuantitas yang bisa dimasukkan adalah ${maxQty}`);
        }
    }
});

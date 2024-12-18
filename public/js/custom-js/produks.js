flatpickr(".tanggal", {
    dateFormat: "d-m-Y",
    defaultDate: "today"
});


// Format angka ke Rupiah
function formatRupiah(value) {
    return 'Rp ' + new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(value);
}

function parseRupiahToNumber(rupiah) {
    if (!rupiah) return 0;
    return parseFloat(rupiah.replace(/Rp\s?|[^,\d]/g, '').replace(',', '.')) || 0;
}

// Fungsi untuk menambahkan format rupiah pada input
function addRupiahFormatting(inputId) {
    document.getElementById(inputId).addEventListener('input', function (e) {
        const input = e.target;
        const start = input.selectionStart; // Simpan posisi kursor
        const value = input.value.replace(/[^0-9]/g, ''); // Hapus karakter non-angka
        input.value = formatRupiah(value);
        input.setSelectionRange(start, start); // Kembalikan posisi kursor
    });
}

// Terapkan fungsi pada input tertentu
addRupiahFormatting('harga_beli');
addRupiahFormatting('harga_jual');

// Perhitungan Pajak dan Total Transaksi
function hitungPajakDanTotal() {
    // Ambil nilai inputan
    const hargaBeli = parseFloat(document.getElementById('harga_beli').value.replace(/\D/g, '')) || 0;
    const kuantitas = parseInt(document.getElementById('kuantitas').value) || 0;
    const pajakPersen = parseFloat(document.getElementById('pajak_persen').value) || 0;

    // Perhitungan Pajak
    const nominalPajak = (hargaBeli * kuantitas * pajakPersen) / 100;

    // Perhitungan Total Transaksi
    const totalTransaksi = hargaBeli * kuantitas + nominalPajak;

    // Set hasil ke input field
    document.getElementById('nominal_pajak').value = formatRupiah(nominalPajak);
    document.getElementById('total_transaksi').value = formatRupiah(totalTransaksi);
}

// Event Listeners untuk Inputan
document.addEventListener('DOMContentLoaded', () => {
    const inputs = ['harga_beli', 'kuantitas', 'pajak_persen'];

    inputs.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('input', hitungPajakDanTotal);
        }
    });

    // Jenis Pajak
    const jenisPajakSelect = document.getElementById('jns_pajak');
    const pajakPersenInput = document.getElementById('pajak_persen');

    jenisPajakSelect.addEventListener('change', function () {
        if (jenisPajakSelect.value === 'ppn') {
            pajakPersenInput.value = 11;
            pajakPersenInput.disabled = true;
            pajakPersenInput.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        } else if (jenisPajakSelect.value === 'ppnbm') {
            pajakPersenInput.value = '';
            pajakPersenInput.disabled = false;
            pajakPersenInput.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        } else {
            pajakPersenInput.value = 'Pilih Jenis Pajak';
            pajakPersenInput.disabled = true;
            pajakPersenInput.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        }
        hitungPajakDanTotal();
    });

    jenisPajakSelect.dispatchEvent(new Event('change')); // Set default behavior
});

function convertToYMD(dateStr) {
    const [day, month, year] = dateStr.split('-');
    return `${year}-${month}-${day}`;
}

function prepareForSubmit() {
    const tanggalInput = document.querySelector('.tanggal');
    if (tanggalInput) {
        console.log(convertToYMD(tanggalInput.value))
        tanggalInput.value = convertToYMD(tanggalInput.value);
    }

    // Daftar ID input yang perlu dihapus format Rupiah-nya
    const fields = ['harga_beli', 'harga_jual', 'nominal_pajak', 'total_transaksi'];

    // Proses setiap field
    fields.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.value = parseRupiahToNumber(input.value); // Konversi ke angka tanpa format Rupiah
        }
    });
}

document.getElementById('createProduk').addEventListener('submit', prepareForSubmit);
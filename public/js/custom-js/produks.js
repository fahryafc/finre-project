// Format angka ke Rupiah
function formatRupiah(value) {
    return 'Rp ' + new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(value);
}

// Fungsi untuk menambahkan format rupiah pada input
function addRupiahFormatting(inputId) {
    document.getElementById(inputId).addEventListener('input', function (e) {
        const input = e.target;
        const value = input.value.replace(/[^0-9]/g, ''); // Hapus karakter non-angka
        input.value = formatRupiah(value);
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

    // Event listener untuk jenis pajak
    const jenisPajakSelect = document.getElementById('jns_pajak');
    jenisPajakSelect.addEventListener('change', function () {
        const pajakPersenInput = document.getElementById('pajak_persen');
        if (jenisPajakSelect.value === 'ppn') {
            // Atur default persen pajak ke 11 dan disable input persen pajak
            pajakPersenInput.value = 11;
            pajakPersenInput.disabled = true;
            pajakPersenInput.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
            hitungPajakDanTotal(); // Hitung pajak dan total transaksi dengan nilai default
        } else if (jenisPajakSelect.value === 'ppnbm') {
            // Enable input persen pajak untuk PPnBM
            pajakPersenInput.value = '';
            pajakPersenInput.disabled = false;
            pajakPersenInput.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
            hitungPajakDanTotal(); // Hitung pajak dan total transaksi sesuai input
        } else if (jenisPajakSelect.value === 'jns_pajak' || jenisPajakSelect.value === '') {
            pajakPersenInput.value = 'Pilih Jenis Pajak';
            pajakPersenInput.disabled = true;
            pajakPersenInput.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        }
    });
    const pajakPersenInput = document.getElementById('pajak_persen');
    pajakPersenInput.value = 'Pilih Jenis Pajak';
    pajakPersenInput.disabled = true;
    pajakPersenInput.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
});

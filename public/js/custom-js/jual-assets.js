document.addEventListener('DOMContentLoaded', function () {
    const tanggalInput = document.querySelector('.tgl_pembelian');
    const defaultDate = tanggalInput.getAttribute('data-tanggal'); // Ambil nilai dari data-tanggal

    flatpickr(".tgl_pembelian", {
        dateFormat: "d-m-Y",
        defaultDate: defaultDate
    });
});

flatpickr(".tgl_penjualan", {
    dateFormat: "d-m-Y",
    defaultDate: 'today',
});

function convertToYMD(dateStr) {
    const [day, month, year] = dateStr.split('-');
    return `${year}-${month}-${day}`;
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

function formatRupiah(value) {
    // Pastikan nilai valid dan angka
    const numericValue = parseFloat(value);
    if (isNaN(numericValue)) {
        return 'Rp 0'; // Jika nilai tidak valid, kembalikan Rp 0
    }

    return 'Rp ' + new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(numericValue);
}

document.addEventListener('DOMContentLoaded', function () {
    const kuantitasInput = document.getElementById('kuantitas');

    // Event listener untuk perubahan input
    kuantitasInput.addEventListener('input', function () {
        const maxKuantitas = parseInt(this.dataset.maxKuantitas, 10); // Ambil nilai maksimal
        const currentValue = parseInt(this.value, 10); // Ambil nilai input saat ini

        // Jika nilai input lebih besar dari nilai maksimum
        if (currentValue > maxKuantitas) {
            alert(`Kuantitas tidak boleh lebih dari ${maxKuantitas}`);
            this.value = ''; // Kosongkan input jika melebihi
        }
    });
});

// Perhitungan Pajak
function hitungPajak() {
    // Ambil nilai inputan
    // const hargaBeli = parseFloat(document.getElementById('harga_beli').value.replace(/\D/g, '')) || 0;
    // const kuantitas = parseInt(document.getElementById('kuantitas').value) || 0;
    const persenPajak = parseFloat(document.getElementById('pajak_penjualan').value) || 0;
    const harga_pelepasan = parseFloat(document.getElementById('harga_pelepasan').value.replace(/\D/g, '')) || 0;

    // Perhitungan Pajak
    const pajak_dibayarkan = (harga_pelepasan * persenPajak) / 100;

    // Set hasil ke input field
    document.getElementById('pajak_dibayarkan').value = formatRupiah(pajak_dibayarkan);
}

// Event Listeners untuk Inputan
document.addEventListener('DOMContentLoaded', () => {
    const inputs = ['harga_beli', 'kuantitas', 'pajak_penjualan', 'harga_pelepasan'];

    inputs.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('input', hitungPajak);
        }
    });

    // Jenis Pajak
    const jenisPajakSelect = document.getElementById('jns_pajak');
    const persenPajakInput = document.getElementById('pajak_penjualan');

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

// Event listener untuk input harga pelepasan
document.getElementById('harga_pelepasan').addEventListener('input', function () {
    let hargaPelepasan = this.value.replace(/[^,\d]/g, ""); // Hapus karakter non-angka
    hargaPelepasan = hargaPelepasan ? parseInt(hargaPelepasan) : 0; // Pastikan validitas angka
    const hargaFormatted = formatRupiah(hargaPelepasan);
    document.getElementById('hargaPelepasanDisplay').value = hargaFormatted;
});
// Event listener untuk format input harga pelepasan
document.getElementById('harga_pelepasan').addEventListener('input', function () {
    // Format nilai input menjadi Rupiah
    let angka = this.value.replace(/[^,\d]/g, "").toString();
    this.value = formatRupiah(angka);

    // Panggil fungsi untuk menghitung keuntungan atau kerugian
    // calculateProfitLoss();
});

document.addEventListener('DOMContentLoaded', function () {
    const idAsset = document.getElementById('id_aset').value;
    fetch(`/get-asset-data/${idAsset}`)
        .then(response => response.json())
        .then(data => {
            const hargaBeli = data.asset.harga_beli || 0;
            const kuantitasAwal = parseInt(data.asset.kuantitas) || 0;
            const totalNilaiAssetAwal = hargaBeli * kuantitasAwal;

            const tanggalPenjualan = new Date(data.datas[0].tanggal_penyusutan);
            const tanggalSekarang = new Date();

            const tahunBerjalan = tanggalSekarang.getFullYear() - tanggalPenjualan.getFullYear();

            let nilaiDepresiasiPerTahun = 0;
            let penyusutanPerUnit = 0;

            if (data.asset.penyusutan == 1) {
                if (data.datas[0].masa_manfaat !== null && data.datas[0].masa_manfaat !== '') {
                    nilaiDepresiasiPerTahun = data.datas[0].nominal_masa_manfaat;
                    penyusutanPerUnit = nilaiDepresiasiPerTahun / kuantitasAwal;
                } else if (data.datas[0].nilai_tahun !== null && data.datas[0].nilai_tahun !== '') {
                    nilaiDepresiasiPerTahun = data.datas[0].nominal_nilai_tahun;
                    penyusutanPerUnit = nilaiDepresiasiPerTahun / kuantitasAwal;
                }
            }

            const penyusutanPerUnitBerjalan = penyusutanPerUnit * tahunBerjalan;
            const totalPenyusutanTerakhir = penyusutanPerUnitBerjalan * kuantitasAwal;

            // Nilai buku saat ini setelah depresiasi
            let nilaiBukuSaatIni = totalNilaiAssetAwal - totalPenyusutanTerakhir;

            // Event listener untuk perubahan pada kuantitas dan perhitungan keuntungan/kerugian
            const inputKuantitas = document.getElementById('kuantitas');
            const inputHargaPelepasan = document.getElementById('harga_pelepasan');

            function calculateProfitLoss() {
                let kuantitasBaru = parseInt(inputKuantitas.value) || 0;
                let hargaPelepasan = parseInt(inputHargaPelepasan.value.replace(/[^,\d]/g, "")) || 0;

                if (kuantitasBaru > kuantitasAwal) {
                    alert('Kuantitas tidak boleh melebihi batas maksimal: ' + kuantitasAwal);
                    inputKuantitas.value = kuantitasAwal;
                    kuantitasBaru = kuantitasAwal;
                }

                const totalNilaiAssetBaru = hargaBeli * kuantitasBaru;
                const totalPenyusutanBaru = penyusutanPerUnitBerjalan * kuantitasBaru;

                // Nilai buku baru setelah penyusutan
                let nilaiBukuBaru = totalNilaiAssetBaru - totalPenyusutanBaru;

                // Perhitungan keuntungan/kerugian
                let selisih = hargaPelepasan - nilaiBukuBaru;
                let resultSpan = document.getElementById('keuntungan_kerugian');

                if (selisih < 0) {
                    resultSpan.value = `- ${formatRupiah(Math.abs(selisih).toString())}`;
                    resultSpan.style.color = "red";
                } else {
                    resultSpan.value = `+ ${formatRupiah(selisih.toString())}`;
                    resultSpan.style.color = "green";
                }

                // Perbarui field di form
                document.getElementById('total_nilai_asset').value = formatRupiah(totalNilaiAssetBaru.toString());
                document.getElementById('nilai_buku').value = formatRupiah(nilaiBukuBaru.toString());
                document.getElementById('nilai_penyusutan_terakhir').value = formatRupiah(totalPenyusutanBaru.toString());
            }

            // Panggil perhitungan awal saat form dibuka
            calculateProfitLoss();

            // Tambahkan event listener untuk perubahan input harga pelepasan dan kuantitas
            inputKuantitas.addEventListener('input', calculateProfitLoss);
            inputHargaPelepasan.addEventListener('input', calculateProfitLoss);

            // Isi data awal ke form
            document.getElementById('id_aset').value = data.asset.id_aset;
            document.getElementById('harga_beli').value = formatRupiah(hargaBeli.toString());
            document.getElementById('total_nilai_asset').value = formatRupiah(totalNilaiAssetAwal.toString());
            document.getElementById('kuantitas').value = kuantitasAwal.toString();
            document.getElementById('nilai_buku').value = formatRupiah(nilaiBukuSaatIni.toString());
            document.getElementById('nilai_penyusutan_terakhir').value = formatRupiah(totalPenyusutanTerakhir.toString());
        })
        .catch(error => {
            console.error('Error fetching asset data:', error);
        });

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

    const tanggal_pembelian = document.querySelector('.tgl_pembelian');
    const tanggal_penjualan = document.querySelector('.tgl_penjualan');
    if (tanggal_pembelian) {
        tanggal_pembelian.value = convertToYMD(tanggal_pembelian.value);
    }
    if (tanggal_penjualan) {
        tanggal_penjualan.value = convertToYMD(tanggal_penjualan.value);
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
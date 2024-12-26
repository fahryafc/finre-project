// document.addEventListener('DOMContentLoaded', function () {
//     const tanggalInput = document.querySelector('.tgl_edit');
//     const defaultDate = tanggalInput.getAttribute('data-tanggal'); // Ambil nilai dari data-tanggal

//     flatpickr(".tgl_edit", {
//         dateFormat: "d-m-Y",
//         defaultDate: defaultDate
//     });
// });

flatpickr(".tanggal_pembelian", {
    dateFormat: "d-m-Y",
    defaultDate: 'today',
});

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

// Ambil elemen dropdown jenis pajak, input pajak, harga beli, kuantitas, dan pajak dibayarkan
const jnsPajakSelect = document.getElementById('jns_pajak');
const pajakInput = document.getElementById('pajak');
const pajakDibayarkanInput = document.getElementById('pajak_dibayarkan');

// harga_beli dan kuantitas diambil dari field input yang ada di form
const hargaBeliInput = document.getElementById('harga_beli');
const kuantitasInput = document.getElementById('kuantitas');

// Fungsi untuk menghapus format Rupiah dan konversi ke angka
function parseRupiahToNumber(rupiah) {
    return parseInt(rupiah.replace(/[^,\d]/g, '').replace(',', '')) || 0; // Hapus karakter selain angka dan konversi ke integer
}

// Ambil elemen dropdown jenis pajak penjualan, input pajak penjualan, dan pajak penjualan dibayarkan
const jnsPajakPenjualanSelect = document.getElementById('jns_pajak_penjualan');
const pajakPenjualanInput = document.getElementById('pajak_penjualan');
const pajakPenjualanDibayarkanInput = document.getElementById('pajak_penjualan_dibayarkan');
const hargaPelepasanInput = document.getElementById('harga_pelepasan');

// Fungsi untuk menghitung pajak penjualan dibayarkan
function hitungPajakPenjualanDibayarkan() {
    const hargaPelepasanValue = parseRupiahToNumber(hargaPelepasanInput.value) || 0;
    const pajakPenjualanValue = parseFloat(pajakPenjualanInput.value) || 0;
    const pajakPenjualanPersen = pajakPenjualanValue / 100;
    const totalPajakPenjualanDibayarkan = hargaPelepasanValue * pajakPenjualanPersen;
    pajakPenjualanDibayarkanInput.value = formatRupiah(totalPajakPenjualanDibayarkan.toString());
}

// Event listener untuk mendeteksi perubahan pada dropdown jenis pajak penjualan
jnsPajakPenjualanSelect.addEventListener('change', function () {
    pajakPenjualanInput.disabled = false;
    pajakPenjualanInput.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
    pajakPenjualanInput.value = '';
    pajakPenjualanDibayarkanInput.value = '';
});

// Event listener untuk mendeteksi perubahan pada input pajak penjualan dan harga pelepasan
pajakPenjualanInput.addEventListener('input', hitungPajakPenjualanDibayarkan);
hargaPelepasanInput.addEventListener('input', hitungPajakPenjualanDibayarkan);

// Tambahkan ke fungsi prepareForSubmit yang sudah ada
function prepareForSubmit() {
    // Tambahkan untuk pajak penjualan
    const pajakPenjualanDibayarkan = document.getElementById('pajak_penjualan_dibayarkan');
    if (pajakPenjualanDibayarkan) {
        pajakPenjualanDibayarkan.value = parseRupiahToNumber(pajakPenjualanDibayarkan.value);
    }
}

// Fungsi untuk menghitung pajak dibayarkan
function hitungPajakDibayarkan() {
    const hargaBeliValue = parseRupiahToNumber(hargaBeliInput.value) || 0; // Ambil nilai dari input harga beli
    const kuantitasValue = parseInt(kuantitasInput.value) || 0; // Ambil nilai dari input kuantitas
    // console.log(kuantitasValue);

    const pajakValue = parseFloat(pajakInput.value) || 0; // Ambil nilai pajak dari input dan konversi ke float
    const pajakPersen = pajakValue / 100; // Konversi persen ke desimal
    const totalPajakDibayarkan = hargaBeliValue * kuantitasValue * pajakPersen; // Hitung pajak dibayarkan
    pajakDibayarkanInput.value = formatRupiah(totalPajakDibayarkan.toString()); // Tampilkan hasil format dalam rupiah
}

// Event listener untuk mendeteksi perubahan pada dropdown jenis pajak dan input pajak
jnsPajakSelect.addEventListener('change', function () {
    if (jnsPajakSelect.value === 'ppn') {
        pajakInput.disabled = true;
        pajakInput.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        pajakInput.value = '11'; // Atur nilai default untuk PPN
        hitungPajakDibayarkan(); // Hitung pajak dibayarkan
    } else {
        pajakInput.disabled = false;
        pajakInput.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        pajakInput.value = ''; // Kosongkan nilai input
        pajakDibayarkanInput.value = ''; // Reset nilai pajak dibayarkan
    }
});

// Event listener untuk mendeteksi perubahan pada input pajak
pajakInput.addEventListener('input', hitungPajakDibayarkan);
hargaBeliInput.addEventListener('input', hitungPajakDibayarkan);
kuantitasInput.addEventListener('input', hitungPajakDibayarkan);

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

function formatRupiah(angka) {
    var numberString = angka.replace(/[^,\d]/g, '').toString();
    var split = numberString.split(',');
    var sisa = split[0].length % 3;
    var rupiah = split[0].substr(0, sisa);
    var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // Tambahkan ribuan ke string rupiah
    if (ribuan) {
        var separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    // Tambahkan bagian desimal jika ada
    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    return 'Rp. ' + rupiah;
}

// Event listener untuk input harga pelepasan
document.getElementById('harga_pelepasan').addEventListener('input', function () {
    const hargaPelepasan = this.value;
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

document.getElementById('harga_beli').addEventListener('input', function () {
    const hargaBeli = this.value;
    const hargaFormatted = formatRupiah(hargaBeli); // Format nilai input menjadi Rupiah
});
// Event listener untuk format input harga beli
document.getElementById('harga_beli').addEventListener('input', function () {
    let angka = this.value.replace(/[^,\d]/g, "").toString();
    this.value = formatRupiah(angka);
});

function loadAssetData(button) {
    const idAset = button.getAttribute('data-id');

    fetch(`/get-asset-data/${idAset}`)
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
            const inputKuantitas = document.getElementById('jumlah');
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
            document.getElementById('harga_beli_aset').value = formatRupiah(hargaBeli.toString());
            document.getElementById('total_nilai_asset').value = formatRupiah(totalNilaiAssetAwal.toString());
            document.getElementById('jumlah').value = kuantitasAwal.toString();
            document.getElementById('nilai_buku').value = formatRupiah(nilaiBukuSaatIni.toString());
            document.getElementById('nilai_penyusutan_terakhir').value = formatRupiah(totalPenyusutanTerakhir.toString());

            // Buka modal setelah data diisi
            const modal = document.getElementById('modalJualAset');
            modal.classList.remove('hidden');
            modal.classList.add('show');
        })
        .catch(error => {
            console.error('Error fetching asset data:', error);
        });
}

document.addEventListener('DOMContentLoaded', function () {
    const assetButtons = document.querySelectorAll('button[data-fc-target="modalDetailAsset"]');
    assetButtons.forEach(button => {
        button.addEventListener('click', function () {
            const assetId = this.getAttribute('data-asset-id');

            // Fetch data from server using the assetId
            fetch(`/get-asset-detail/${assetId}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data);

                    // Tampilkan informasi umum asset
                    if (data.asset) {
                        document.querySelector('.nm_asset').textContent = `: ${data.asset.nm_aset}`;
                        document.querySelector('.kode').textContent = `: ${data.asset.kode_sku}`;
                        document.querySelector('.tanggal').textContent = `: ${data.asset.tanggal}`;
                        document.querySelector('.harga_beli').textContent = `: ${formatRupiah(data.asset.harga_beli).toString()}`;
                        document.querySelector('.kuantitas').textContent = `: ${data.asset.kuantitas}`;
                    }

                    // Hitung nilai buku
                    const hargaBeli = data.asset.harga_beli || 0;
                    const kuantitasAwal = parseInt(data.asset.kuantitas) || 0;
                    const totalNilaiAssetAwal = hargaBeli * kuantitasAwal;

                    const tanggalPenjualan = new Date(data.penyusutan.tanggal_penyusutan);
                    const tanggalSekarang = new Date();

                    const tahunBerjalan = tanggalSekarang.getFullYear() - tanggalPenjualan.getFullYear();

                    let nilaiDepresiasiPerTahun = 0;
                    let penyusutanPerUnit = 0;

                    if (data.asset.penyusutan == 1) {
                        if (data.penyusutan.masa_manfaat !== null && data.penyusutan.masa_manfaat !== '') {
                            nilaiDepresiasiPerTahun = data.penyusutan.nominal_masa_manfaat;
                            penyusutanPerUnit = nilaiDepresiasiPerTahun / kuantitasAwal;
                        } else if (data.penyusutan.nilai_tahun !== null && data.penyusutan.nilai_tahun !== '') {
                            nilaiDepresiasiPerTahun = data.penyusutan.nominal_nilai_tahun;
                            penyusutanPerUnit = nilaiDepresiasiPerTahun / kuantitasAwal;
                        }
                    }

                    const penyusutanPerUnitBerjalan = penyusutanPerUnit * tahunBerjalan;
                    const totalPenyusutanTerakhir = penyusutanPerUnitBerjalan * kuantitasAwal;

                    // Nilai buku saat ini setelah depresiasi
                    let nilaiBukuSaatIni = totalNilaiAssetAwal - totalPenyusutanTerakhir;

                    // Tampilkan informasi nilai buku pada modal detail
                    document.querySelector('.nilai_buku').textContent = `: ${formatRupiah(nilaiBukuSaatIni.toString())}`;

                    // Tampilkan informasi penyusutan hanya jika `penyusutan` bernilai `1`
                    if (data.asset.penyusutan == 1) {
                        document.querySelector('.tanggal_penyusutan').textContent = `: ${data.penyusutan.tanggal_penyusutan}`;
                        if (data.penyusutan.masa_manfaat !== null && data.penyusutan.masa_manfaat !== '') {
                            document.querySelector('.metode_penyusutan').textContent = `: Masa Manfaat - ${data.penyusutan.masa_manfaat} Tahun`;
                            document.querySelector('.nilai_penyusutan').textContent = `: ${formatRupiah(data.penyusutan.nominal_masa_manfaat).toString()}`;
                        }
                        if (data.penyusutan.nilai_tahun !== null && data.penyusutan.nilai_tahun !== '') {
                            document.querySelector('.metode_penyusutan').textContent = `: Nilai/Tahun - ${data.penyusutan.nilai_tahun}%`;
                            document.querySelector('.nilai_penyusutan').textContent = `: ${formatRupiah(data.penyusutan.nominal_nilai_tahun).toString()}`;
                        }
                    }

                    // Tampilkan modal
                    document.getElementById('modalDetailAsset').classList.remove('hidden');
                });
        });
    });
});


document.addEventListener("DOMContentLoaded", function () {
    // Attach event listener to buttons
    document.querySelectorAll('[data-fc-target="modalEditAsset"]').forEach(button => {
        button.addEventListener("click", function () {
            setInitialCollapseState();
            // Show the modal
            document.querySelector('#modalEditAsset').classList.remove("hidden");

        });
    });

    // Close modal
    document.querySelector('[data-fc-dismiss]').addEventListener("click", function () {
        document.querySelector('#modalEditAsset').classList.add("hidden");
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
// calculateProfitLoss();
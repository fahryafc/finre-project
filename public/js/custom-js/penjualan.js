flatpickr("#datepicker-basic", {
    dateFormat: "d-m-Y",
    defaultDate: "today"
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

function togglePiutangEdit(idPenjualan) {
    const piutangInputContainer = document.getElementById('piutangInputContainer' + idPenjualan);
    const piutangSwitch = document.getElementById('piutangSwitch' + idPenjualan);

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

function formatRupiah(angka, prefix = 'Rp') {
    const number_string = angka.toString().replace(/[^,\d]/g, '');
    const split = number_string.split(',');
    const sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    const ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        const separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    return prefix + ' ' + (split[1] != undefined ? rupiah + ',' + split[1] : rupiah);
}

function parseRupiahToNumber(rupiah) {
    // Hapus karakter selain angka dan koma, serta awalan "Rp" jika ada
    return parseFloat(rupiah.replace(/Rp\s?|[^,\d]/g, '').replace(',', '.')) || 0;
}

// Fungsi untuk memformat nilai rupiah
function formatRupiah(value) {
    return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(value);
}

// Fungsi untuk perhitungan diskon dan pajak otomatis
function calculateTotal() {
    const harga = parseFloat(document.getElementById("harga").value.replace(/\D/g, '')) || 0;
    const ongkir = parseFloat(document.getElementById("ongkir").value.replace(/\D/g, '')) || 0;
    const kuantitas = parseFloat(document.getElementById("kuantitas").value) || 0;
    const diskonInput = parseFloat(document.getElementById("diskon").value) || 0;
    const pajakInput = parseFloat(document.getElementById("pajak").value) || 0;
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
document.getElementById("pajak").addEventListener("input", calculateTotal);
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
document.getElementById("pajak").addEventListener("input", function () {
    calculateTotal();
});

function calculateTotalEdit(idPenjualan) {
    const harga = parseRupiahToNumber(document.getElementById(`hargaEdit${idPenjualan}`).value);
    const ongkir = parseRupiahToNumber(document.getElementById(`ongkirEdit${idPenjualan}`).value);
    const kuantitas = parseFloat(document.getElementById(`kuantitasEdit${idPenjualan}`).value) || 0;
    const diskon = parseFloat(document.getElementById(`diskonEdit${idPenjualan}`).value) || 0;
    const pajak = parseFloat(document.getElementById(`pajakEdit${idPenjualan}`).value) || 0;
    const piutang = parseRupiahToNumber(document.getElementById(`piutangEdit${idPenjualan}`).value) || 0;

    // Cek apakah harga valid sebelum melanjutkan
    if (isNaN(harga) || harga <= 0) {
        return; // Jangan lanjutkan perhitungan jika harga tidak valid
    }

    const totalHarga = harga * kuantitas;

    // Hitung diskon
    const totalDiskon = (totalHarga * diskon) / 100;
    const diskonOutputElement = document.getElementById(`diskon-output-edit${idPenjualan}`);
    diskonOutputElement.textContent = `Rp ${totalDiskon ? formatRupiah(totalDiskon) : '0'}`;

    // Hitung pajak
    const hargaDiskon = totalHarga - totalDiskon;
    const totalPajak = (hargaDiskon * pajak) / 100;
    const pajakOutputElement = document.getElementById(`pajak-output-edit${idPenjualan}`);
    pajakOutputElement.value = `Rp ${totalPajak ? formatRupiah(totalPajak) : '0'}`;

    // Hitung Total Pemasukan
    const totalSetelahDiskon = hargaDiskon; // harga setelah diskon (belum termasuk pajak)
    const totalPemasukan = totalSetelahDiskon - piutang + ongkir;

    // Update field total pemasukan
    const totalPemasukanElement = document.getElementById(`total_pemasukan_edit${idPenjualan}`);
    totalPemasukanElement.value = `Rp ${totalPemasukan ? formatRupiah(totalPemasukan) : '0'}`;
}

function openEditPenjualan(button) {
    const idPenjualan = button.getAttribute('data-id-penjualan');

    // Event listener untuk input harga
    const hargaInput = document.getElementById(`hargaEdit${idPenjualan}`);
    if (hargaInput && !hargaInput.hasAttribute("data-listener-added")) {
        hargaInput.addEventListener("input", function (e) {
            const parsedValue = parseRupiahToNumber(e.target.value);
            e.target.value = `Rp ${formatRupiah(parsedValue)}`;
            calculateTotalEdit(idPenjualan);
        });
        hargaInput.setAttribute("data-listener-added", "true");
    }
    // document.getElementById("ongkiredit").addEventListener("input", function (e) {
    //     e.target.value = formatRupiah(e.target.value.replace(/[^,\d]/g, ''));
    //     calculateTotal();
    // });

    const ongkirInput = document.getElementById(`ongkirEdit${idPenjualan}`);
    if (ongkirInput && !ongkirInput.hasAttribute("data-listener-added")) {
        ongkirInput.addEventListener("input", function (e) {
            const parsedValue = parseRupiahToNumber(e.target.value);
            e.target.value = `Rp ${formatRupiah(parsedValue)}`;
            calculateTotalEdit(idPenjualan);
        });
        hargaInput.setAttribute("data-listener-added", "true");
    }

    // Event listener untuk input piutang
    const piutangInput = document.getElementById(`piutangEdit${idPenjualan}`);
    if (piutangInput && !piutangInput.hasAttribute("data-listener-added")) {
        piutangInput.addEventListener("input", function (e) {
            const parsedValue = parseRupiahToNumber(e.target.value);
            e.target.value = `Rp ${formatRupiah(parsedValue)}`;
            calculateTotalEdit(idPenjualan);
        });
        piutangInput.setAttribute("data-listener-added", "true");
    }

    // Event listener lainnya
    const kuantitasInput = document.getElementById(`kuantitasEdit${idPenjualan}`);
    const diskonInput = document.getElementById(`diskonEdit${idPenjualan}`);
    const pajakInput = document.getElementById(`pajakEdit${idPenjualan}`);

    if (kuantitasInput && !kuantitasInput.hasAttribute("data-listener-added")) {
        kuantitasInput.addEventListener("input", () => calculateTotalEdit(idPenjualan));
        kuantitasInput.setAttribute("data-listener-added", "true");
    }
    if (diskonInput && !diskonInput.hasAttribute("data-listener-added")) {
        diskonInput.addEventListener("input", () => calculateTotalEdit(idPenjualan));
        diskonInput.setAttribute("data-listener-added", "true");
    }
    if (pajakInput && !pajakInput.hasAttribute("data-listener-added")) {
        pajakInput.addEventListener("input", () => calculateTotalEdit(idPenjualan));
        pajakInput.setAttribute("data-listener-added", "true");
    }

    // Hitung ulang total saat pertama kali modal dibuka
    calculateTotalEdit(idPenjualan);
}

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
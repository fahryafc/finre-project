flatpickr("#datepicker-basic", {
    dateFormat: "d-m-Y",
    defaultDate: "today",
});

function toggleCollapsePajak() {
    const collapseElementPajak = document.getElementById("collapsePajak");
    const pajak = document.getElementById("pajakButton");

    // Toggle class based on the switch status
    if (pajak.checked) {
        collapseElementPajak.classList.remove("hidden");
    } else {
        collapseElementPajak.classList.add("hidden");
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const hutangButton = document.getElementById("hutangButton");
    const collapseHutang = document.getElementById("collapseHutang");

    // Fungsi untuk menampilkan atau menyembunyikan form Hutang
    function toggleCollapseHutang() {
        if (hutangButton.checked) {
            collapseHutang.classList.remove("hidden"); // Tampilkan form Hutang
        } else {
            collapseHutang.classList.add("hidden"); // Sembunyikan form Hutang
        }
    }

    // Event listener untuk perubahan pada checkbox
    hutangButton.addEventListener("change", toggleCollapseHutang);

    // Jalankan fungsi saat halaman dimuat untuk kondisi awal
    toggleCollapseHutang();
});

function formatRupiah(value) {
    return (
        "Rp " +
        new Intl.NumberFormat("id-ID", { minimumFractionDigits: 0 }).format(
            value
        )
    );
}

function parseRupiahToNumber(rupiah) {
    rupiah = rupiah.replace(/[^0-9,]/g, "");
    rupiah = rupiah.replace(",", ".");
    return parseFloat(rupiah.replace(/\.(?=\d{3}(,|$))/g, "")) || 0;
}

function addRupiahFormatting(inputId) {
    document.getElementById(inputId).addEventListener("input", function (e) {
        const input = e.target;
        const value = input.value.replace(/[^0-9]/g, ""); // Hapus karakter non-angka
        input.value = formatRupiah(value);
        calculateTotal();
    });
}

// Terapkan fungsi pada input tertentu
addRupiahFormatting("biaya");
addRupiahFormatting("pajak_dibayarkan");
addRupiahFormatting("nominal_hutang");

document.addEventListener("DOMContentLoaded", () => {
    function togglePengeluaran() {
        const jenisPengeluaran =
            document.getElementById("jenis_pengeluaran").value;
        const divNamaKaryawan = document.getElementById("div_nama_karyawan");
        const divNamaVendor = document.getElementById("div_nama_vendor");
        const namaKaryawan = document.getElementById("nama_karyawan");
        const namaVendor = document.getElementById("nama_vendor");

        // Sembunyikan kedua elemen terlebih dahulu
        divNamaKaryawan.classList.add("hidden");
        divNamaVendor.classList.add("hidden");
        namaKaryawan.disabled = true;
        namaVendor.disabled = true;

        // Tampilkan elemen berdasarkan pilihan dan aktifkan input yang benar
        if (jenisPengeluaran === "gaji_karyawan") {
            divNamaKaryawan.classList.remove("hidden");
            namaKaryawan.disabled = false;
        } else if (jenisPengeluaran === "pembayaran_vendor") {
            divNamaVendor.classList.remove("hidden");
            namaVendor.disabled = false;
        }
        calculateTotal();
    }

    document
        .getElementById("jenis_pengeluaran")
        .addEventListener("change", togglePengeluaran);
    togglePengeluaran();
});

function aturPajakDanHitung() {
    const jenisPajak = document.getElementById("jns_pajak").value;
    const pajakPersenInput = document.getElementById("pajak_persen");
    const pajakDibayarkanInput = document.getElementById("pajak_dibayarkan");
    const pajakPersenContainer = document.getElementById(
        "pajakPersenContainer"
    );

    if (jenisPajak === "ppn") {
        pajakPersenInput.value = "11";
        pajakPersenInput.readOnly = true;
        pajakPersenContainer.classList.remove("hidden");
        pajakDibayarkanInput.readOnly = true;
        pajakDibayarkanInput.classList.add(
            "bg-gray-300",
            "text-gray-500",
            "cursor-not-allowed"
        );
        pajakDibayarkanInput.value = ""; // Reset nilai pajak dibayarkan
    } else if (jenisPajak === "ppnbm") {
        pajakPersenInput.value = "";
        pajakPersenInput.readOnly = false;
        pajakPersenContainer.classList.remove("hidden");
        pajakDibayarkanInput.readOnly = true;
        pajakDibayarkanInput.classList.add(
            "bg-gray-300",
            "text-gray-500",
            "cursor-not-allowed"
        );
        pajakDibayarkanInput.value = ""; // Reset nilai pajak dibayarkan
    } else if (jenisPajak === "pph") {
        pajakPersenInput.value = "";
        pajakPersenInput.readOnly = true;
        pajakPersenContainer.classList.add("hidden");
        pajakDibayarkanInput.readOnly = false;
        pajakDibayarkanInput.classList.remove(
            "bg-gray-300",
            "text-gray-500",
            "cursor-not-allowed"
        );
        pajakDibayarkanInput.placeholder = "Masukan Nominal Pajak Dibayarkan";
    } else {
        pajakPersenInput.value = "";
        pajakPersenInput.readOnly = false;
        pajakPersenContainer.classList.remove("hidden");
        pajakDibayarkanInput.readOnly = true;
        pajakDibayarkanInput.classList.add(
            "bg-gray-300",
            "text-gray-500",
            "cursor-not-allowed"
        );
        pajakDibayarkanInput.value = ""; // Reset nilai pajak dibayarkan
    }

    hitungPajak();
    calculateTotal();
}

function hitungPajak() {
    const biaya =
        parseFloat(
            document.getElementById("biaya").value.replace(/[^0-9]/g, "")
        ) || 0;
    const pajakPersen =
        parseFloat(document.getElementById("pajak_persen").value) || 0;
    const pajakDibayarkanInput = document.getElementById("pajak_dibayarkan");
    const jnsPajak = document.getElementById("jns_pajak").value;

    if (jnsPajak === "pph") {
        // Biarkan pengguna menginput manual, tidak ada perhitungan otomatis
        pajakDibayarkanInput.oninput = function () {
            formatRupiah(pajakDibayarkanInput);
        };
    } else if (biaya > 0 && jnsPajak) {
        // Hitung Pajak Dibayarkan untuk PPN dan PPnBM
        const pajakDibayarkan = (biaya * pajakPersen) / 100;
        pajakDibayarkanInput.value =
            "Rp " +
            pajakDibayarkan.toLocaleString("id-ID", {
                minimumFractionDigits: 2,
            });
    } else {
        pajakDibayarkanInput.value = "";
    }
    calculateTotal();
}

function calculateTotal() {
    // Ambil elemen input
    const biayaInput = document.getElementById("biaya");
    const pajakInput = document.getElementById("pajak_dibayarkan");
    const hutangInput = document.getElementById("nominal_hutang");
    const totalInput = document.getElementById("total_transaksi");

    if (!biayaInput || !pajakInput || !hutangInput || !totalInput) {
        console.error("Salah satu elemen tidak ditemukan.");
        return;
    }

    // Ambil nilai input dan ubah ke angka
    const harga = parseRupiahToNumber(biayaInput.value) || 0;
    const pajak = parseRupiahToNumber(pajakInput.value) || 0;
    const hutang = parseRupiahToNumber(hutangInput.value) || 0;

    // Hitung total dengan operator yang benar
    let totalHarga = harga + pajak - hutang;

    // Tampilkan hasil di input total
    totalInput.value = formatRupiah(totalHarga);
}

document.addEventListener("DOMContentLoaded", () => {
    aturPajakDanHitung();
});

function prepareForSubmit() {
    const biaya = document.getElementById("biaya");
    const pajakDibayarkan = document.getElementById("pajak_dibayarkan");
    const nominal_hutang = document.getElementById("nominal_hutang");

    // Hapus format Rupiah dari kedua input
    if (biaya) {
        biaya.value = parseRupiahToNumber(biaya.value);
    }
    if (nominal_hutang) {
        nominal_hutang.value = parseRupiahToNumber(nominal_hutang.value);
    }
    if (pajakDibayarkan) {
        pajakDibayarkan.value = parseRupiahToNumber(pajakDibayarkan.value); // Perbaikan untuk pajak
    }
}

// Tambahkan event listener submit pada form untuk memanggil fungsi prepareForSubmit
document.querySelector("form").addEventListener("submit", prepareForSubmit);

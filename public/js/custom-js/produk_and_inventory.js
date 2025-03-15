flatpickr(".tanggal", {
    dateFormat: "d-m-Y",
    defaultDate: "today",
});

// Format angka ke Rupiah
function formatRupiah(angka, prefix = "Rp ") {
    return prefix + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function parseRupiahToNumber(rupiah) {
    if (!rupiah) return 0;
    return (
        parseFloat(rupiah.replace(/Rp\s?|[^,\d]/g, "").replace(",", ".")) || 0
    );
}

// Fungsi untuk menambahkan format rupiah pada input
document.addEventListener("DOMContentLoaded", function () {
    const formatRupiah = (angka, prefix) => {
        let number_string = angka.replace(/[^,\d]/g, "").toString(),
            split = number_string.split(","),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // Tambahkan tanda titik jika angka memiliki ribuan
        if (ribuan) {
            let separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }

        rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
        return prefix == undefined ? rupiah : rupiah ? "Rp " + rupiah : "";
    };

    const handleInput = (event) => {
        let target = event.target;
        target.value = formatRupiah(target.value, "Rp ");
    };

    // Daftarkan event listener pada input harga beli dan harga jual
    const hargaBeliInput = document.querySelector("#harga_beli");
    const hargaJualInput = document.querySelector("#harga_jual");
    if (hargaBeliInput) hargaBeliInput.addEventListener("input", handleInput);
    if (hargaJualInput) hargaJualInput.addEventListener("input", handleInput);
});

// Perhitungan Pajak dan Total Transaksi
function hitungPajakDanTotal() {
    // Ambil nilai inputan
    const hargaBeli =
        parseFloat(
            document.getElementById("harga_beli").value.replace(/\D/g, "")
        ) || 0;
    const kuantitas = parseInt(document.getElementById("kuantitas").value) || 0;
    const pajakPersen =
        parseFloat(document.getElementById("pajak_persen").value) || 0;

    // Perhitungan Pajak
    const nominalPajak = (hargaBeli * kuantitas * pajakPersen) / 100;

    // Perhitungan Total Transaksi
    const totalTransaksi = hargaBeli * kuantitas + nominalPajak;

    // Set hasil ke input field
    document.getElementById("nominal_pajak").value = formatRupiah(nominalPajak);
    document.getElementById("total_transaksi").value =
        formatRupiah(totalTransaksi);
}

// Event Listeners untuk Inputan
document.addEventListener("DOMContentLoaded", () => {
    const inputs = ["harga_beli", "kuantitas", "pajak_persen"];

    inputs.forEach((id) => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener("input", hitungPajakDanTotal);
        }
    });

    // Jenis Pajak
    const jenisPajakSelect = document.getElementById("jns_pajak");
    const pajakPersenInput = document.getElementById("pajak_persen");

    jenisPajakSelect.addEventListener("change", function () {
        if (jenisPajakSelect.value === "ppn") {
            pajakPersenInput.value = 11;
            pajakPersenInput.disabled = true;
            pajakPersenInput.classList.add(
                "bg-gray-300",
                "text-gray-500",
                "cursor-not-allowed"
            );
        } else if (jenisPajakSelect.value === "ppnbm") {
            pajakPersenInput.value = "";
            pajakPersenInput.disabled = false;
            pajakPersenInput.classList.remove(
                "bg-gray-300",
                "text-gray-500",
                "cursor-not-allowed"
            );
        } else {
            pajakPersenInput.value = "Pilih Jenis Pajak";
            pajakPersenInput.disabled = true;
            pajakPersenInput.classList.add(
                "bg-gray-300",
                "text-gray-500",
                "cursor-not-allowed"
            );
        }
        hitungPajakDanTotal();
    });

    jenisPajakSelect.dispatchEvent(new Event("change")); // Set default behavior
});

function convertToYMD(dateStr) {
    const [day, month, year] = dateStr.split("-");
    return `${year}-${month}-${day}`;
}

function prepareForSubmit() {
    const tanggalInput = document.querySelector(".tanggal");
    if (tanggalInput) {
        console.log(convertToYMD(tanggalInput.value));
        tanggalInput.value = convertToYMD(tanggalInput.value);
    }

    // Daftar ID input yang perlu dihapus format Rupiah-nya
    const fields = [
        "harga_beli",
        "harga_jual",
        "nominal_pajak",
        "total_transaksi",
    ];

    // Proses setiap field
    fields.forEach((id) => {
        const input = document.getElementById(id);
        if (input) {
            input.value = parseRupiahToNumber(input.value); // Konversi ke angka tanpa format Rupiah
        }
    });
}

document
    .getElementById("createProduk")
    .addEventListener("submit", prepareForSubmit);

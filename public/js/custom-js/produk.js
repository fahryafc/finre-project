function formatRupiah(angka, prefix = "Rp ") {
    return prefix + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function cleanRupiah(value) {
    return value.replace(/[^,\d]/g, "");
}

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
    const hargaBeliInput = document.querySelector("#harga_beli_input");
    const hargaJualInput = document.querySelector("#harga_jual_input");

    if (hargaBeliInput) hargaBeliInput.addEventListener("input", handleInput);
    if (hargaJualInput) hargaJualInput.addEventListener("input", handleInput);
});

function hitungTotal() {
    // Ambil elemen input
    const hargaBeliInput = document.getElementById("harga_beli_input");
    const kuantitasInput = document.getElementById("kuantitas");
    const nominalPajakInput = document.getElementById("nominal_pajak");
    const totalPemasukanInput = document.getElementById("total_pemasukan");

    // Bersihkan format rupiah untuk perhitungan
    const hargaBeli = parseFloat(cleanRupiah(hargaBeliInput.value)) || 0;
    const kuantitas = parseInt(kuantitasInput.value) || 0;

    // Hitung pajak dan total pemasukan
    const pajakPersen = 11 / 100; // 11%
    const nominalPajak = hargaBeli * kuantitas * pajakPersen;
    const totalPemasukan = hargaBeli * kuantitas;

    // Tampilkan hasil dengan format rupiah
    nominalPajakInput.value = formatRupiah(nominalPajak.toFixed(0));
    totalPemasukanInput.value = formatRupiah(totalPemasukan.toFixed(0));
}

// Tambahkan event listener untuk menghapus format rupiah sebelum form dikirim
document.querySelector("form").addEventListener("submit", function (e) {
    const hargaBeliInput = document.getElementById("harga_beli_input");
    const hargaJualInput = document.getElementById("harga_jual_input");
    const totalPemasukanInput = document.getElementById("total_pemasukan");

    hargaBeliInput.value = cleanRupiah(hargaBeliInput.value);
    hargaJualInput.value = cleanRupiah(hargaJualInput.value);
    totalPemasukanInput.value = cleanRupiah(totalPemasukanInput.value);
});

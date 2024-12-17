flatpickr("#datepicker-basic", {
    dateFormat: "d-m-Y",
    defaultDate: "today"
});

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
    // untuk modal edit
    // const hargaBeliEdit = document.querySelector("#harga_beli_Edit");
    // const hargaJualEdit = document.querySelector("#harga_jual_Edit");
    // if (hargaBeliEdit) hargaBeliEdit.addEventListener("input", handleInput);
    // if (hargaJualEdit) hargaJualEdit.addEventListener("input", handleInput);


});

function hitungTotal() {
    // Ambil elemen input
    const hargaBeliInput = document.getElementById("harga_beli_input");
    const kuantitasInput = document.getElementById("kuantitas");
    const nominalPajakInput = document.getElementById("nominal_pajak");
    const totalPemasukanInput = document.getElementById("total_transaksi");

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
    const totalPemasukanInput = document.getElementById("total_transaksi");
    const nominalPajak = document.getElementById("nominal_pajak");

    hargaBeliInput.value = cleanRupiah(hargaBeliInput.value);
    hargaJualInput.value = cleanRupiah(hargaJualInput.value);
    totalPemasukanInput.value = cleanRupiah(totalPemasukanInput.value);
    nominalPajak.value = cleanRupiah(nominalPajak.value);
});

function hitungTotalEdit(idProduk) {
    // Ambil elemen input
    const hargaBeliEdit = cleanRupiah(document.getElementById(`harga_beli_edit${idProduk}`).value);
    const kuantitasEdit = parseInt(document.getElementById(`kuantitasEdit${idProduk}`).value || 0);
    const nominalPajakEdit = document.getElementById(`nominal_pajak_edit${idProduk}`);
    const totalPemasukanEdit = document.getElementById(`total_transaksi_edit${idProduk}`);

    // Hitung pajak dan total pemasukan
    const pajakPersen = 11 / 100; // 11%
    const nominalPajak = hargaBeliEdit * kuantitasEdit * pajakPersen;
    const totalPemasukan = hargaBeliEdit * kuantitasEdit;

    // Tampilkan hasil dengan format rupiah
    nominalPajakEdit.value = formatRupiah(nominalPajak.toFixed(0));
    totalPemasukanEdit.value = formatRupiah(totalPemasukan.toFixed(0));
}

function openEditProduk(button) {
    const idProduk = button.getAttribute('data-id-produk');

    const hargaBeliEdit = document.getElementById(`harga_beli_edit${idProduk}`);
    if (hargaBeliEdit && !hargaBeliEdit.hasAttribute("data-listener-added")) {
        hargaBeliEdit.addEventListener("input", function (e) {
            const parsedValue = cleanRupiah(e.target.value);
            e.target.value = `${formatRupiah(parsedValue)}`;
            hitungTotalEdit(idProduk);
        });
        hargaBeliEdit.setAttribute("data-listener-added", "true");
    }

    const hargaJualEdit = document.getElementById(`harga_jual_edit${idProduk}`);
    if (hargaJualEdit && !hargaJualEdit.hasAttribute("data-listener-added")) {
        hargaJualEdit.addEventListener("input", function (e) {
            const parsedValue = cleanRupiah(e.target.value);
            e.target.value = `${formatRupiah(parsedValue)}`;
        });
        hargaJualEdit.setAttribute("data-listener-added", "true");
    }

    const KuantitasEdit = document.getElementById(`kuantitasEdit${idProduk}`);
    if (KuantitasEdit && !KuantitasEdit.hasAttribute("data-listener-added")) {
        KuantitasEdit.addEventListener("input", () => hitungTotalEdit(idProduk));
        KuantitasEdit.setAttribute("data-listener-added", "true");
    }

    hitungTotalEdit(idProduk);
}

function aturPajakDanHitung() {
    const jenisPajak = document.getElementById('jns_pajak').value;
    const pajakPersenInput = document.getElementById('pajak_persen');
    const nominal_pajak = document.getElementById('nominal_pajak');
    const pajakPersenContainer = document.getElementById('pajakPersenContainer');

    if (jenisPajak === 'ppn') {
        pajakPersenInput.value = '11';
        pajakPersenInput.disabled = true;
        pajakPersenContainer.classList.remove('hidden');
        nominal_pajak.readOnly = true;
        nominal_pajak.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        nominal_pajak.value = ''; // Reset nilai pajak dibayarkan
    } else if (jenisPajak === 'ppnbm') {
        pajakPersenInput.value = '';
        pajakPersenInput.disabled = false;
        pajakPersenContainer.classList.remove('hidden');
        nominal_pajak.readOnly = true;
        nominal_pajak.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        nominal_pajak.value = ''; // Reset nilai pajak dibayarkan
    } else {
        pajakPersenInput.value = '';
        pajakPersenInput.disabled = false;
        pajakPersenContainer.classList.remove('hidden');
        nominal_pajak.readOnly = true;
        nominal_pajak.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        nominal_pajak.value = ''; // Reset nilai pajak dibayarkan
    }

    hitungPajak();
}

function hitungPajak() {
    const harga_beli = parseFloat(document.getElementById('harga_beli').value.replace(/[^0-9]/g, '')) || 0;
    const pajakPersen = parseFloat(document.getElementById('pajak_persen').value) || 0;
    const nominal_pajak = document.getElementById('nominal_pajak');
    const jnsPajak = document.getElementById('jns_pajak').value;

    if (harga_beli > 0 && jnsPajak) {
        // Hitung Pajak Dibayarkan untuk PPN dan PPnBM
        const nominal_pajak = (harga_beli * pajakPersen) / 100;
        nominal_pajak.value = 'Rp ' + nominal_pajak.toLocaleString('id-ID', { minimumFractionDigits: 2 });
    } else {
        nominal_pajak.value = "";
    }
}

document.addEventListener('DOMContentLoaded', () => {
    aturPajakDanHitung();
});
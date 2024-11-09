flatpickr("#datepicker-basic", {
    dateFormat: "d-m-Y",
    defaultDate: "today"
});

if (document.getElementById("pagination-table") && typeof simpleDatatables.DataTable !== 'undefined') {
    const dataTable = new simpleDatatables.DataTable("#pagination-table", {
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

function filterByKategori() {
    const selectedKategori = document.getElementById("filterKategori").value.toLowerCase();
    const rows = document.querySelectorAll("#pagination-table tbody tr");

    rows.forEach(row => {
        const kategori = row.getAttribute("data-kategori").toLowerCase();
        if (selectedKategori === "" || kategori === selectedKategori) {
            row.style.display = ""; // Tampilkan row
        } else {
            row.style.display = "none"; // Sembunyikan row
        }
    });
}

if (document.getElementById("detail-hutang") && typeof simpleDatatables.DataTable !== 'undefined') {
    const dataTable = new simpleDatatables.DataTable("#detail-hutang", {
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

if (document.getElementById("table-piutang") && typeof simpleDatatables.DataTable !== 'undefined') {
    const dataTable = new simpleDatatables.DataTable("#table-piutang", {
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

if (document.getElementById("riwayat-piutang") && typeof simpleDatatables.DataTable !== 'undefined') {
    const dataTable = new simpleDatatables.DataTable("#riwayat-piutang", {
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


function openDetailHutang(button) {
    const id_hutangpiutang = button.getAttribute('data-id-hutang');
    const modal = document.getElementById(`modalHutang${id_hutangpiutang}`);
    const loader = modal.querySelector('.loading-spinner');
    const tbody = modal.querySelector('tbody');

    if (modal && loader) {
        loader.classList.remove('hidden'); // Tampilkan loader
        tbody.innerHTML = ''; // Kosongkan isi tabel sebelum menambah data baru

        fetch(`/hutangpiutang/detail/${id_hutangpiutang}`)
            .then(response => response.json())
            .then(data => {
                loader.classList.add('hidden'); // Sembunyikan loader saat data selesai dimuat

                // Populate rows
                if (data.length === 0) {
                    // Jika data kosong, tampilkan pesan
                    const emptyRow = `
                        <tr><td class="datatable-empty text-center" colspan="6">Tidak ada data</td></tr>
                    `;
                    tbody.innerHTML = emptyRow;
                } else {
                    data.forEach((data, index) => {
                        const row = `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">${index + 1}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">${data.tanggal_pembayaran}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">Rp. ${new Intl.NumberFormat('id-ID').format(data.dibayarkan)}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">Rp. ${new Intl.NumberFormat('id-ID').format(data.sisa_pembayaran)}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">${data.catatan}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">${data.masuk_akun}</td>
                        </tr>
                    `;
                        tbody.innerHTML += row;
                    });
                }
                // Tampilkan modal
                modal.classList.remove('hidden');
            })
            .catch(error => {
                loader.classList.add('hidden'); // Sembunyikan loader jika terjadi error
                console.error('Error:', error);
            });
    }
}

function riwayatPiutang(button) {
    const id_hutangpiutang = button.getAttribute('data-id-piutang');
    const modal = document.getElementById(`riwayatPiutang${id_hutangpiutang}`);
    const loader = modal.querySelector('.loading-spinner');
    const tbody = modal.querySelector('tbody');

    if (modal && loader) {
        loader.classList.remove('hidden'); // Tampilkan loader
        tbody.innerHTML = ''; // Kosongkan isi tabel sebelum menambah data baru

        fetch(`/hutangpiutang/detail/${id_hutangpiutang}`)
            .then(response => response.json())
            .then(data => {
                loader.classList.add('hidden'); // Sembunyikan loader saat data selesai dimuat

                // Populate rows
                if (data.length === 0) {
                    // Jika data kosong, tampilkan pesan
                    const emptyRow = `
                        <tr><td class="datatable-empty text-center" colspan="6">Tidak ada data</td></tr>
                    `;
                    tbody.innerHTML = emptyRow;
                } else {
                    data.forEach((data, index) => {
                        const row = `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">${index + 1}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">${data.tanggal_pembayaran}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">Rp. ${new Intl.NumberFormat('id-ID').format(data.dibayarkan)}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">Rp. ${new Intl.NumberFormat('id-ID').format(data.sisa_pembayaran)}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">${data.catatan}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">${data.masuk_akun}</td>
                        </tr>
                    `;
                        tbody.innerHTML += row;
                    });
                }
                // Tampilkan modal
                modal.classList.remove('hidden');
            })
            .catch(error => {
                loader.classList.add('hidden'); // Sembunyikan loader jika terjadi error
                console.error('Error:', error);
            });
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const inputPembayaran = document.getElementById('dibayarkan');
    const inputPembayaranPiutang = document.getElementById('piutang_dibayarkan');

    // Format input sebagai rupiah saat mengetik
    inputPembayaran.addEventListener('input', function (e) {
        let value = e.target.value.replace(/[^,\d]/g, "").toString();
        e.target.value = formatRupiah(value, "Rp ");
    });

    inputPembayaranPiutang.addEventListener('input', function (e) {
        let value = e.target.value.replace(/[^,\d]/g, "").toString();
        e.target.value = formatRupiah(value, "Rp ");
    });

    // Fungsi untuk mengubah angka menjadi format rupiah
    function formatRupiah(angka, prefix) {
        let numberString = angka.replace(/[^,\d]/g, "").toString(),
            split = numberString.split(","),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }

        rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? "Rp " + rupiah : "");
    }

    function parseRupiahToNumber(rupiah) {
        // Hapus karakter selain angka dan koma, serta awalan "Rp" jika ada
        return parseFloat(rupiah.replace(/Rp\s?|[^,\d]/g, '').replace(',', '.')) || 0;
    }

    // Bersihkan format rupiah sebelum mengirim ke server
    function prepareForSubmit() {
        const nominal = document.getElementById('dibayarkan');
        const nominalPiutang = document.getElementById('piutang_dibayarkan');

        // Hapus format Rupiah dari kedua input
        if (nominal) {
            nominal.value = parseRupiahToNumber(nominal.value);
            console.log(nominal.value);
        }
        if (nominalPiutang) {
            nominalPiutang.value = parseRupiahToNumber(nominalPiutang.value);
            console.log(nominalPiutang.value);
        }
    }

    // Tambahkan event listener submit pada form untuk memanggil fungsi prepareForSubmit
    document.querySelector('form').addEventListener('submit', prepareForSubmit);
});

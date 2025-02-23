flatpickr("#tanggal_mulai", {
    dateFormat: "d-m-Y",
    defaultDate: "today"
});

flatpickr("#tanggal_selesai", {
    dateFormat: "d-m-Y",
    defaultDate: "today"
});

function formatRupiah(value) {
    return 'Rp ' + new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(value);
}

function parseRupiahToNumber(rupiah) {
    rupiah = rupiah.replace(/[^0-9,]/g, '');
    rupiah = rupiah.replace(',', '.');
    return parseFloat(rupiah.replace(/\.(?=\d{3}(,|$))/g, '')) || 0;
}
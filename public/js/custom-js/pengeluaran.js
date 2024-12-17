flatpickr("#datepicker-basic", {
    dateFormat: "d-m-Y",
    defaultDate: "today"
});

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

function toggleCollapseHutang() {
    const collapseElementHutang = document.getElementById('collapseHutang');
    const hutang = document.getElementById('hutangButton');
    // Toggle class based on the switch status
    if (hutang.checked) {
        collapseElementHutang.classList.remove('hidden');
    } else {
        collapseElementHutang.classList.add('hidden');
    }
}

function formatRupiah(value) {
    return 'Rp ' + new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(value);
}

function parseRupiahToNumber(rupiah) {
    rupiah = rupiah.replace(/[^0-9,]/g, '');
    rupiah = rupiah.replace(',', '.');
    return parseFloat(rupiah.replace(/\.(?=\d{3}(,|$))/g, '')) || 0;
}
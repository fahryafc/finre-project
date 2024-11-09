document.addEventListener('DOMContentLoaded', function () {

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

document.getElementById('modalTambahPengeluaran').addEventListener('show', function () {
    const pajakButton = document.getElementById('pajakButton');
    const hutangButton = document.getElementById('hutangButton');

    // Reset switch dan collapse
    hutangButton.checked = false;
    collapseElementHutang.classList.add('hidden');
    pajakButton.checked = false;
    collapseElementPajak.classList.add('hidden');
});
import { Grid, h, html } from "gridjs";
window.gridjs = Grid;

document.addEventListener('DOMContentLoaded', function() {
        fetch('/sales-data')
        .then(response => response.json())
        .then(data => {
            new Grid({
                columns: [{
                    name: 'No',
                    formatter: (cell) => {
                        return html(`<span class="fw-semibold">${cell}</span>`);
                    }
                },
                "Penjualan", "Kuantitas", "Harga", "Total Harga", "Diskon", "Pajak", "Piutang", "Total Pemasukan",
                {
                    name: 'Actions',
                    width: '120px',
                    formatter: () => {
                        return html(`
                            <a href='#' class='inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-red-500 text-white mr-2'>
                                <i class='ti ti-trash'></i>
                            </a>
                            <a href='#' class='inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-yellow-500 text-white'>
                                <i class='ti ti-edit'></i>
                            </a>
                        `);
                    }
                }],
                pagination: {
                    limit: 5
                },
                sort: true,
                search: true,
                data: data
            }).render(document.getElementById("table-gridjs"));
        })
        .catch(error => console.error('Error:', error));
});
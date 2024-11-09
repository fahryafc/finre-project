/*
Template Name: Konrix - Responsive 5 Admin Dashboard
Author: CoderThemes
Website: https://coderthemes.com/
Contact: support@coderthemes.com
File: datatable js
*/

import { Grid, h, html } from "gridjs";
window.gridjs = Grid;

class GridDatatable {

    init() {
        this.basicTableInit();
    }

    basicTableInit() {

        // Basic Table
        if (document.getElementById("tabel-penjualan"))
        new Grid({
        columns: [
            "No","Penjualan", "Kuantitas", "Harga", "Total Harga", "Diskon", "Pajak", "Piutang", "Total Pemasukan",
            {
                name: 'Actions',
                width: '120px',
                formatter: (cell, row) => {
                    const id_penjualan = row._cells[9].data;  // Mengambil id_penjualan dari kolom ke-9
                    return html(`
                        <form action='/penjualan/${id_penjualan}' method='POST' style='display: inline;'>
                            <input type='hidden' name='_token' value='{{ csrf_token() }}'>
                            <input type='hidden' name='_method' value='DELETE'>
                            <button type='submit' class='inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-red-500 text-white mr-2' id='sweetalert-longcontent'>
                                <i class='ti ti-trash'></i>
                            </button>
                        </form>
                        <a href='/edit/${id_penjualan}' class='inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-yellow-500 text-white'>
                            <i class='ti ti-edit'></i>
                        </a>
                    `);
                }
            }
        ],
        pagination: {
            limit: 5
        },
        search: true,
        resizable: true,
        server:{
            url: "sales-data",
            then: data => data.map(result =>
                [result.No, result.Penjualan, result.Kuantitas, result.Harga, result.Total_harga, result.Diskon, result.Pajak, result.Piutang, result.Total_pemasukan, result.id_penjualan]
            )
        },
    }).render(document.getElementById("tabel-penjualan"));

        // card Table
        if (document.getElementById("table-card"))
            new Grid({
                columns: ["Name", "Email", "Position", "Company", "Country"],
                sort: true,
                pagination: {
                    limit: 5
                },
                data: [
                    ["Jonathan", "jonathan@example.com", "Senior Implementation Architect", "Hauck Inc", "Holy See"],
                    ["Harold", "harold@example.com", "Forward Creative Coordinator", "Metz Inc", "Iran"],
                    ["Shannon", "shannon@example.com", "Legacy Functionality Associate", "Zemlak Group", "South Georgia"],
                    ["Robert", "robert@example.com", "Product Accounts Technician", "Hoeger", "San Marino"],
                    ["Noel", "noel@example.com", "Customer Data Director", "Howell - Rippin", "Germany"],
                    ["Traci", "traci@example.com", "Corporate Identity Director", "Koelpin - Goldner", "Vanuatu"],
                    ["Kerry", "kerry@example.com", "Lead Applications Associate", "Feeney, Langworth and Tremblay", "Niger"],
                    ["Patsy", "patsy@example.com", "Dynamic Assurance Director", "Streich Group", "Niue"],
                    ["Cathy", "cathy@example.com", "Customer Data Director", "Ebert, Schamberger and Johnston", "Mexico"],
                    ["Tyrone", "tyrone@example.com", "Senior Response Liaison", "Raynor, Rolfson and Daugherty", "Qatar"],
                ]
            }).render(document.getElementById("table-card"));


        // pagination Table
        if (document.getElementById("table-pagination"))
            new Grid({
                columns: [{
                    name: 'ID',
                    width: '120px',
                    formatter: (function (cell) {
                        return html('<a href="" class="fw-medium">' + cell + '</a>');
                    })
                }, "Name", "Date", "Total", "Status",
                {
                    name: 'Actions',
                    width: '100px',
                    formatter: (function (cell) {
                        return html("<button type='button' class='btn btn-sm btn-light'>" +
                            "Details" +
                            "</button>");
                    })
                },
                ],
                pagination: {
                    limit: 5
                },

                data: [
                    ["#VL2111", "Jonathan", "07 Oct, 2021", "$24.05", "Paid",],
                    ["#VL2110", "Harold", "07 Oct, 2021", "$26.15", "Paid"],
                    ["#VL2109", "Shannon", "06 Oct, 2021", "$21.25", "Refund"],
                    ["#VL2108", "Robert", "05 Oct, 2021", "$25.03", "Paid"],
                    ["#VL2107", "Noel", "05 Oct, 2021", "$22.61", "Paid"],
                    ["#VL2106", "Traci", "04 Oct, 2021", "$24.05", "Paid"],
                    ["#VL2105", "Kerry", "04 Oct, 2021", "$26.15", "Paid"],
                    ["#VL2104", "Patsy", "04 Oct, 2021", "$21.25", "Refund"],
                    ["#VL2103", "Cathy", "03 Oct, 2021", "$22.61", "Paid"],
                    ["#VL2102", "Tyrone", "03 Oct, 2021", "$25.03", "Paid"],
                ]
            }).render(document.getElementById("table-pagination"));

        // search Table
        if (document.getElementById("table-search"))
            new Grid({
                columns: ["Name", "Email", "Position", "Company", "Country"],
                pagination: {
                    limit: 5
                },
                search: true,
                data: [
                    ["Jonathan", "jonathan@example.com", "Senior Implementation Architect", "Hauck Inc", "Holy See"],
                    ["Harold", "harold@example.com", "Forward Creative Coordinator", "Metz Inc", "Iran"],
                    ["Shannon", "shannon@example.com", "Legacy Functionality Associate", "Zemlak Group", "South Georgia"],
                    ["Robert", "robert@example.com", "Product Accounts Technician", "Hoeger", "San Marino"],
                    ["Noel", "noel@example.com", "Customer Data Director", "Howell - Rippin", "Germany"],
                    ["Traci", "traci@example.com", "Corporate Identity Director", "Koelpin - Goldner", "Vanuatu"],
                    ["Kerry", "kerry@example.com", "Lead Applications Associate", "Feeney, Langworth and Tremblay", "Niger"],
                    ["Patsy", "patsy@example.com", "Dynamic Assurance Director", "Streich Group", "Niue"],
                    ["Cathy", "cathy@example.com", "Customer Data Director", "Ebert, Schamberger and Johnston", "Mexico"],
                    ["Tyrone", "tyrone@example.com", "Senior Response Liaison", "Raynor, Rolfson and Daugherty", "Qatar"],
                ]
            }).render(document.getElementById("table-search"));

        // Sorting Table
        if (document.getElementById("table-sorting"))
            new Grid({
                columns: ["Name", "Email", "Position", "Company", "Country"],
                pagination: {
                    limit: 5
                },
                sort: true,
                data: [
                    ["Jonathan", "jonathan@example.com", "Senior Implementation Architect", "Hauck Inc", "Holy See"],
                    ["Harold", "harold@example.com", "Forward Creative Coordinator", "Metz Inc", "Iran"],
                    ["Shannon", "shannon@example.com", "Legacy Functionality Associate", "Zemlak Group", "South Georgia"],
                    ["Robert", "robert@example.com", "Product Accounts Technician", "Hoeger", "San Marino"],
                    ["Noel", "noel@example.com", "Customer Data Director", "Howell - Rippin", "Germany"],
                    ["Traci", "traci@example.com", "Corporate Identity Director", "Koelpin - Goldner", "Vanuatu"],
                    ["Kerry", "kerry@example.com", "Lead Applications Associate", "Feeney, Langworth and Tremblay", "Niger"],
                    ["Patsy", "patsy@example.com", "Dynamic Assurance Director", "Streich Group", "Niue"],
                    ["Cathy", "cathy@example.com", "Customer Data Director", "Ebert, Schamberger and Johnston", "Mexico"],
                    ["Tyrone", "tyrone@example.com", "Senior Response Liaison", "Raynor, Rolfson and Daugherty", "Qatar"],
                ]
            }).render(document.getElementById("table-sorting"));


        // Loading State Table
        if (document.getElementById("table-loading-state"))
            new Grid({
                columns: ["Name", "Email", "Position", "Company", "Country"],
                pagination: {
                    limit: 5
                },
                sort: true,
                data: function () {
                    return new Promise(function (resolve) {
                        setTimeout(function () {
                            resolve([
                                ["Jonathan", "jonathan@example.com", "Senior Implementation Architect", "Hauck Inc", "Holy See"],
                                ["Harold", "harold@example.com", "Forward Creative Coordinator", "Metz Inc", "Iran"],
                                ["Shannon", "shannon@example.com", "Legacy Functionality Associate", "Zemlak Group", "South Georgia"],
                                ["Robert", "robert@example.com", "Product Accounts Technician", "Hoeger", "San Marino"],
                                ["Noel", "noel@example.com", "Customer Data Director", "Howell - Rippin", "Germany"],
                                ["Traci", "traci@example.com", "Corporate Identity Director", "Koelpin - Goldner", "Vanuatu"],
                                ["Kerry", "kerry@example.com", "Lead Applications Associate", "Feeney, Langworth and Tremblay", "Niger"],
                                ["Patsy", "patsy@example.com", "Dynamic Assurance Director", "Streich Group", "Niue"],
                                ["Cathy", "cathy@example.com", "Customer Data Director", "Ebert, Schamberger and Johnston", "Mexico"],
                                ["Tyrone", "tyrone@example.com", "Senior Response Liaison", "Raynor, Rolfson and Daugherty", "Qatar"]
                            ])
                        }, 2000);
                    });
                }
            }).render(document.getElementById("table-loading-state"));


        // Fixed Header
        if (document.getElementById("table-fixed-header"))
            new Grid({
                columns: ["Name", "Email", "Position", "Company", "Country"],
                sort: true,
                pagination: true,
                fixedHeader: true,
                height: '400px',
                data: [
                    ["Jonathan", "jonathan@example.com", "Senior Implementation Architect", "Hauck Inc", "Holy See"],
                    ["Harold", "harold@example.com", "Forward Creative Coordinator", "Metz Inc", "Iran"],
                    ["Shannon", "shannon@example.com", "Legacy Functionality Associate", "Zemlak Group", "South Georgia"],
                    ["Robert", "robert@example.com", "Product Accounts Technician", "Hoeger", "San Marino"],
                    ["Noel", "noel@example.com", "Customer Data Director", "Howell - Rippin", "Germany"],
                    ["Traci", "traci@example.com", "Corporate Identity Director", "Koelpin - Goldner", "Vanuatu"],
                    ["Kerry", "kerry@example.com", "Lead Applications Associate", "Feeney, Langworth and Tremblay", "Niger"],
                    ["Patsy", "patsy@example.com", "Dynamic Assurance Director", "Streich Group", "Niue"],
                    ["Cathy", "cathy@example.com", "Customer Data Director", "Ebert, Schamberger and Johnston", "Mexico"],
                    ["Tyrone", "tyrone@example.com", "Senior Response Liaison", "Raynor, Rolfson and Daugherty", "Qatar"],
                ]
            }).render(document.getElementById("table-fixed-header"));


        // Hidden Columns
        if (document.getElementById("table-hidden-column"))
            new Grid({
                columns: ["Name", "Email", "Position", "Company",
                    {
                        name: 'Country',
                        hidden: true
                    },
                ],
                pagination: {
                    limit: 5
                },
                sort: true,
                data: [
                    ["Jonathan", "jonathan@example.com", "Senior Implementation Architect", "Hauck Inc", "Holy See"],
                    ["Harold", "harold@example.com", "Forward Creative Coordinator", "Metz Inc", "Iran"],
                    ["Shannon", "shannon@example.com", "Legacy Functionality Associate", "Zemlak Group", "South Georgia"],
                    ["Robert", "robert@example.com", "Product Accounts Technician", "Hoeger", "San Marino"],
                    ["Noel", "noel@example.com", "Customer Data Director", "Howell - Rippin", "Germany"],
                    ["Traci", "traci@example.com", "Corporate Identity Director", "Koelpin - Goldner", "Vanuatu"],
                    ["Kerry", "kerry@example.com", "Lead Applications Associate", "Feeney, Langworth and Tremblay", "Niger"],
                    ["Patsy", "patsy@example.com", "Dynamic Assurance Director", "Streich Group", "Niue"],
                    ["Cathy", "cathy@example.com", "Customer Data Director", "Ebert, Schamberger and Johnston", "Mexico"],
                    ["Tyrone", "tyrone@example.com", "Senior Response Liaison", "Raynor, Rolfson and Daugherty", "Qatar"],
                ]
            }).render(document.getElementById("table-hidden-column"));


    }

}

document.addEventListener('DOMContentLoaded', function (e) {
    new GridDatatable().init();
});
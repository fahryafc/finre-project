import ApexCharts from "apexcharts";
import flatpickr from "flatpickr";

// date range
flatpickr("#datepicker-range", {
    mode: "range",
    defaultDate: ["2025-01-01", "2025-01-07"], // Default sebagai rentang tanggal
    dateFormat: "d/m/Y", // Format output tanggal
    locale: {
        rangeSeparator: " - ", // Mengganti kata sambung
    },
});

// table
import { Grid, h, html } from "gridjs";
window.gridjs = Grid;

document.addEventListener("DOMContentLoaded", () => {
    const carouselContainer = document.getElementById("carousel-images");
    const buttons = document.querySelectorAll("[data-slide]");
    let currentSlide = 0;

    buttons.forEach((button, index) => {
        button.addEventListener("click", () => {
            currentSlide = index;

            // Update the transform to move the carousel to the correct slide
            carouselContainer.style.transform = `translateX(-${
                currentSlide * 100
            }%)`;

            // Update the active dot indicator
            buttons.forEach((btn) =>
                btn.classList.replace("bg-green-500", "bg-gray-300")
            );
            button.classList.replace("bg-gray-300", "bg-green-500");
        });
    });

    chartBar();
    chartPie();
    basicTableInit();
    chartPendapatanLain();
    chartPengeluaran();
});

function chartBar() {
    var options = {
        series: [
            {
                name: "Penjualan",
                data: [
                    300, 400, 350, 450, 380, 320, 420, 360, 475, 400, 380, 420,
                ],
            },
        ],
        chart: {
            height: 217,
            type: "bar",
            toolbar: {
                show: false,
            },
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: "55%",
                endingShape: "rounded",
                borderRadius: 4,
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            show: true,
            width: 2,
            colors: ["#00b461"],
        },
        colors: ["#00b461"],
        grid: {
            borderColor: "#9ca3af20",
        },
        xaxis: {
            categories: [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
                "Oct",
                "Nov",
                "Dec",
            ],
        },
        yaxis: {
            title: {
                text: "Qty",
            },
        },
        tooltip: {
            custom: ({ series, seriesIndex, dataPointIndex, w }) => {
                return (
                    '<div class="tooltip-box">' +
                    "<p>Rp " +
                    series[seriesIndex][dataPointIndex] * 130 +
                    "</p>" +
                    "<p>Qty: " +
                    series[seriesIndex][dataPointIndex] +
                    "</p>" +
                    "</div>"
                );
            },
        },
        annotations: {
            yaxis: [
                {
                    y: 24000,
                    borderColor: "grey",
                    label: {
                        borderColor: "transparent",
                        style: {
                            color: "black",
                            background: "transparent",
                        },
                        text: "Median: 24.000",
                        position: "left",
                        offsetX: 35, // Digeser sedikit ke kanan
                    },
                },
            ],
        },
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
}

function chartPengeluaran() {
    var options = {
        series: [
            {
                name: "Penjualan",
                data: [
                    300, 400, 350, 450, 380, 320, 420, 360, 475, 400, 380, 420,
                ],
            },
        ],
        chart: {
            height: 217,
            type: "bar",
            toolbar: {
                show: false,
            },
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: "55%",
                endingShape: "rounded",
                borderRadius: 4,
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            show: true,
            width: 2,
            colors: ["#ea333a"],
        },
        colors: ["#ea333a"],
        grid: {
            borderColor: "#9ca3af20",
        },
        xaxis: {
            categories: [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
                "Oct",
                "Nov",
                "Dec",
            ],
        },
        yaxis: {
            title: {
                text: "Qty",
            },
        },
        tooltip: {
            custom: ({ series, seriesIndex, dataPointIndex, w }) => {
                return (
                    '<div class="tooltip-box">' +
                    "<p>Rp " +
                    series[seriesIndex][dataPointIndex] * 130 +
                    "</p>" +
                    "</div>"
                );
            },
        },
        annotations: {
            yaxis: [
                {
                    y: 24000,
                    borderColor: "grey",
                    label: {
                        borderColor: "transparent",
                        style: {
                            color: "black",
                            background: "transparent",
                        },
                        text: "Median: 24.000",
                        position: "left",
                        offsetX: 35, // Digeser sedikit ke kanan
                    },
                },
            ],
        },
    };

    var chart = new ApexCharts(
        document.querySelector("#chart-pengeluaran"),
        options
    );
    chart.render();
}
// Donut chart

function chartPie() {
    var options = {
        chart: {
            height: 320,
            type: "donut",
        },
        series: [44, 55, 41, 17, 15],
        labels: ["Series 1", "Series 2", "Series 3", "Series 4", "Series 5"],
        colors: ["#34c38f", "#556ee6", "#f46a6a", "#50a5f1", "#f1b44c"],
        legend: {
            show: true,
            position: "bottom",
            horizontalAlign: "center",
            verticalAlign: "middle",
            floating: false,
            fontSize: "14px",
            offsetX: 0,
        },
        stroke: {
            colors: ["transparent"],
        },
        responsive: [
            {
                breakpoint: 600,
                options: {
                    chart: {
                        height: 240,
                    },
                    legend: {
                        show: false,
                    },
                },
            },
        ],
    };

    var chart = new ApexCharts(document.querySelector("#donut_chart"), options);

    chart.render();
}

// gridjs
function basicTableInit() {
    // Basic Table
    if (document.getElementById("table-gridjs"))
        new Grid({
            columns: [
                {
                    name: "No",
                    width: "80px",
                    data: (row) => row.id,
                    formatter: function (cell) {
                        return html(
                            '<span class="fw-semibold">' + cell + "</span>"
                        );
                    },
                },
                {
                    name: "Nama Pengeluaran",
                    data: (row) => row.name,
                    formatter: function (cell) {
                        return cell;
                    },
                },
                {
                    name: "Nominal",
                    data: (row) => row.nominal,
                    formatter: function (cell) {
                        return cell;
                    },
                },
                {
                    name: "Presentase",
                    data: (row) => row.presentase,
                    formatter: function (cell) {
                        return cell;
                    },
                },
                // {
                //     name: "Actions",
                //     width: "120px",
                //     data: (row) => row.id,
                //     formatter: function (cell) {
                //         return html(
                //             "<a href='#" +
                //                 cell +
                //                 "' class='text-reset text-decoration-underline'>" +
                //                 "Details" +
                //                 "</a>"
                //         );
                //     },
                // },
            ],
            pagination: {
                limit: 5,
            },
            sort: true,
            search: true,
            data: [
                {
                    id: "01",
                    name: "Jonathan",
                    nominal: 781238,
                    presentase: "10%",
                },
                {
                    id: "02",
                    name: "Harold",
                    nominal: 242144,
                    presentase: "10%",
                },
                {
                    id: "03",
                    name: "Shannon",
                    nominal: 2342341,
                    presentase: "10%",
                },
                {
                    id: "04",
                    name: "Shannon",
                    nominal: 634234,
                    presentase: "10%",
                },
                {
                    id: "05",
                    name: "Shannon",
                    nominal: 1235435,
                    presentase: "10%",
                },
                {
                    id: "06",
                    name: "Shannon",
                    nominal: 54523423,
                    presentase: "10%",
                },
                {
                    id: "07",
                    name: "Shannon",
                    nominal: 123654,
                    presentase: "10%",
                },
            ],
            // data: [],
            empty: {
                message: html(`
                    <div style="text-align: center;">
                        <i class="bi bi-exclamation-circle" style="font-size: 24px; color: gray;"></i>
                        <p style="margin: 5px 0 0;">Tidak ada data</p>
                    </div>
                `),
            },
            language: {
                search: {
                    placeholder: "Cari...",
                },
                // noRecordsFound: "Tidak ada data yang cocok",
                noRecordsFound:
                    "Data belum tersedia karena belum ada data transaksi. Atur pada icon filter untuk merubah periode atau filter lainnya!",
                pagination: {
                    previous: "Sebelumnya",
                    next: "Berikutnya",
                    showing: "Menampilkan",
                    of: "dari",
                    to: "ke",
                    results: "hasil",
                },
            },
        }).render(document.getElementById("table-gridjs"));
}

function chartPendapatanLain() {
    // produk terlaris
    if (document.getElementById("table-produk-laris"))
        new Grid({
            columns: [
                {
                    name: "No",
                    width: "80px",
                    data: (row) => row.id,
                    formatter: function (cell) {
                        return html(
                            '<span class="fw-semibold">' + cell + "</span>"
                        );
                    },
                },
                {
                    name: "Nama Produk",
                    data: (row) => row.name,
                    formatter: function (cell) {
                        return cell;
                    },
                },
                {
                    name: "Qty",
                    data: (row) => row.qty,
                    formatter: function (cell) {
                        return cell;
                    },
                },
                {
                    name: "Pendapatan",
                    data: (row) => row.omzet,
                    formatter: function (cell) {
                        return cell;
                    },
                },
                // {
                //     name: "Actions",
                //     width: "120px",
                //     data: (row) => row.id,
                //     formatter: function (cell) {
                //         return html(
                //             "<a href='#" +
                //                 cell +
                //                 "' class='text-reset text-decoration-underline'>" +
                //                 "Details" +
                //                 "</a>"
                //         );
                //     },
                // },
            ],
            pagination: {
                limit: 5,
            },
            sort: true,
            search: true,
            data: [
                {
                    id: 1,
                    jam: "08:00",
                    name: "Produk A",
                    satuan: "PCS",
                    qty: 10,
                    omzet: 500000,
                },
                {
                    id: 2,
                    jam: "09:00",
                    name: "Produk B",
                    satuan: "KG",
                    qty: 5,
                    omzet: 250000,
                },
                {
                    id: 3,
                    jam: "10:00",
                    name: "Produk C",
                    satuan: "L",
                    qty: 20,
                    omzet: 800000,
                },
                {
                    id: 4,
                    jam: "11:00",
                    name: "Produk D",
                    satuan: "PCS",
                    qty: 15,
                    omzet: 600000,
                },
                {
                    id: 5,
                    jam: "12:00",
                    name: "Produk E",
                    satuan: "KG",
                    qty: 8,
                    omzet: 400000,
                },
            ],
            // data: [],
            empty: {
                message: html(`
                    <div style="text-align: center;">
                        <i class="bi bi-exclamation-circle" style="font-size: 24px; color: gray;"></i>
                        <p style="margin: 5px 0 0;">Tidak ada data</p>
                    </div>
                `),
            },
            language: {
                search: {
                    placeholder: "Cari...",
                },
                // noRecordsFound: "Tidak ada data yang cocok",
                noRecordsFound:
                    "Data belum tersedia karena belum ada data transaksi. Atur pada icon filter untuk merubah periode atau filter lainnya!",
                pagination: {
                    previous: "Sebelumnya",
                    next: "Berikutnya",
                    showing: "Menampilkan",
                    of: "dari",
                    to: "ke",
                    results: "hasil",
                },
            },
        }).render(document.getElementById("table-produk-laris"));

    // chart pendapatan lain
    if (document.getElementById("chart-pendapatan-lain")) {
        // Data asli
        const dataPendapatan = [125, 243, 300, 220, 123];

        // Hari dalam bahasa Indonesia
        const hari = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat"];

        // Hitung median untuk setiap bar
        const medians = dataPendapatan.map((value, index) => ({
            hari: hari[index], // Hari dalam bahasa Indonesia
            median: value, // Nilai median (dalam hal ini hanya nilai asli)
        }));
        // Bar chart
        var options = {
            chart: {
                height: 450,
                type: "bar",
                toolbar: {
                    show: false,
                },
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    columnWidth: "55%",
                    endingShape: "rounded",
                    borderRadius: 4,
                },
            },
            dataLabels: {
                enabled: false,
            },
            series: [
                {
                    name: "Pendapatan Lainnya",
                    data: [250, 412, 385, 285, 225], // Data untuk 5 hari
                },
            ],
            stroke: {
                show: true,
                width: 2,
                colors: ["transparent"],
            },
            colors: ["#00b461"],
            grid: {
                borderColor: "#9ca3af20",
            },
            xaxis: {
                categories: [
                    "Senin", // Senin
                    "Selasa", // Selasa
                    "Rabu", // Rabu
                    "Kamis", // Kamis
                    "Jumat", // Jumat
                ],
            },
            tooltip: {
                custom: ({ series, seriesIndex, dataPointIndex, w }) => {
                    return (
                        '<div class="tooltip-box">' +
                        "<p>Rp. " +
                        series[seriesIndex][dataPointIndex] * 100 +
                        "</p>" +
                        "</div>"
                    );
                },
            },
            // ini untuk mengatur median
            // annotations: {
            //     xaxis: medians.map((median, index) => ({
            //         x: median.median, // Posisi garis berdasarkan nilai median
            //         borderColor: "transparent", // Garis jadi transparan (tidak terlihat)
            //         strokeDashArray: 0,
            //         offsetY: index * 83, // offset buat tulisan median
            //         label: {
            //             borderColor: "#ff4560",
            //             style: {
            //                 color: "#fff",
            //                 background: "#ff4560",
            //             },
            //             text: `Median ${median.hari}: $${median.median}`, // Teks label
            //             offsetY: index * 80, // // offset buat tulisan median
            //         },
            //     })),
            // },
        };

        var chart = new ApexCharts(
            document.querySelector("#chart-pendapatan-lain"),
            options
        );

        chart.render();
    }
}

// Fungsi untuk menghitung median
function calculateMedian(data) {
    const sorted = [...data].sort((a, b) => a - b);
    const middle = Math.floor(sorted.length / 2);

    if (sorted.length % 2 === 0) {
        return (sorted[middle - 1] + sorted[middle]) / 2;
    } else {
        return sorted[middle];
    }
}

document.getElementById("filterButton").addEventListener("click", function () {
    const dateFilter = document.getElementById("dateFilter");
    if (dateFilter.classList.contains("hidden")) {
        dateFilter.classList.remove("hidden"); // Show filter
    } else {
        dateFilter.classList.add("hidden"); // Hide filter
    }
});

document.getElementById("applyFilter").addEventListener("click", function () {
    const periode = document.getElementById("datepicker-range").value;

    if (periode) {
        alert(`Filter applied: ${periode}`);
    } else {
        alert("Please select both start and end dates.");
    }
});

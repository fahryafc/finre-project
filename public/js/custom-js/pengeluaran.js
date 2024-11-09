flatpickr("#datepicker-basic", {
    dateFormat: "d-m-Y",
    defaultDate: "today"
});

function validateNumberInput(input) {
    const originalValue = input.value;
    const numericValue = originalValue.replace(/\D/g, '');

    const errorDiv = document.getElementById('no_hp_error');

    // Cek jika ada karakter non-angka
    if (originalValue !== numericValue) {
        errorDiv.classList.remove('hidden'); // Tampilkan pesan error
    } else {
        errorDiv.classList.add('hidden'); // Sembunyikan pesan error
    }

    input.value = numericValue; // Set kembali nilai input dengan angka saja
}

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

document.getElementById('modalTambahPengeluaran').addEventListener('hide', function () {
    const pajakButton = document.getElementById('pajakButton');
    const hutangButton = document.getElementById('hutangButton');

    // Reset switch dan collapse
    hutangButton.checked = false;
    collapseElementHutang.classList.add('hidden');
    pajakButton.checked = false;
    collapseElementPajak.classList.add('hidden');
});

function formatRupiah(input) {
    let angka = input.value.replace(/[^,\d]/g, '');
    let split = angka.split(',');
    let sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    input.value = 'Rp ' + rupiah;

    hitungPajak(); // Memanggil fungsi perhitungan setelah format
}

function aturPajakDanHitung() {
    const jnsPajak = document.getElementById('jns_pajak').value;
    const pajakInput = document.getElementById('pajak');

    if (jnsPajak === "ppn") {
        pajakInput.value = 10;
        pajakInput.setAttribute("disabled", "disabled");
    } else {
        pajakInput.removeAttribute("disabled");
        pajakInput.value = "";
    }

    hitungPajak();
}

function hitungPajak() {
    const biaya = parseFloat(document.getElementById('biaya').value.replace(/[^0-9]/g, '')) || 0;
    const pajakPersen = parseFloat(document.getElementById('pajak_persen').value) || 0;
    const pajakDibayarkanInput = document.getElementById('pajak_dibayarkan');
    const jnsPajak = document.getElementById('jns_pajak').value;

    if (jnsPajak && biaya > 0) {
        const pajakDibayarkan = (biaya * pajakPersen) / 100;
        pajakDibayarkanInput.value = 'Rp ' + pajakDibayarkan.toLocaleString('id-ID', { minimumFractionDigits: 2 });
    } else {
        pajakDibayarkanInput.value = "";
    }
}

// Fungsi untuk menghapus format Rupiah dan konversi ke angka
function parseRupiahToNumber(rupiah) {
    // Hapus karakter selain angka dan koma, kemudian ganti koma menjadi titik desimal untuk angka desimal
    return parseFloat(rupiah.replace(/[^,\d]/g, '').replace(',', '.')) || 0;
}

function prepareForSubmit() {
    const biaya = document.getElementById('biaya');
    const pajakDibayarkan = document.getElementById('pajak_dibayarkan');

    // Hapus format Rupiah dari kedua input
    if (biaya) {
        biaya.value = parseRupiahToNumber(biaya.value);
    }
    if (pajakDibayarkan) {
        pajakDibayarkan.value = parseRupiahToNumber(pajakDibayarkan.value); // Perbaikan untuk pajak
    }
}

// Tambahkan event listener submit pada form untuk memanggil fungsi prepareForSubmit
document.querySelector('form').addEventListener('submit', prepareForSubmit);


function togglePengeluaran() {
    const jenisPengeluaran = document.getElementById('jenis_pengeluaran').value;
    const divNamaKaryawan = document.getElementById('div_nama_karyawan');
    const divNamaVendor = document.getElementById('div_nama_vendor');
    const namaKaryawan = document.getElementById('nama_karyawan');
    const namaVendor = document.getElementById('nama_vendor');

    // Sembunyikan kedua elemen terlebih dahulu
    divNamaKaryawan.classList.add('hidden');
    divNamaVendor.classList.add('hidden');
    namaKaryawan.disabled = true;
    namaVendor.disabled = true;

    // Tampilkan elemen berdasarkan pilihan dan aktifkan input yang benar
    if (jenisPengeluaran === "gaji_karyawan") {
        divNamaKaryawan.classList.remove('hidden');
        namaKaryawan.disabled = false;
    } else if (jenisPengeluaran === "pembayaran_vendor") {
        divNamaVendor.classList.remove('hidden');
        namaVendor.disabled = false;
    }
}

document.addEventListener('DOMContentLoaded', function () {
    var editButtons = document.querySelectorAll('[data-fc-type="modal"]');

    editButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var modalId = this.getAttribute('data-fc-target').replace('#', '');
            var modal = document.getElementById(modalId);

            if (modal) {
                var jenisPengeluaranSelect = modal.querySelector('#jenis_pengeluaran');
                var pajakButton = modal.querySelector('#pajakButton');
                var collapsePajak = modal.querySelector('#collapsePajak');
                var hutangButton = modal.querySelector('#hutangButton');
                var collapseHutang = modal.querySelector('#collapseHutang');

                if (jenisPengeluaranSelect) {
                    var selectedValue = jenisPengeluaranSelect.value;
                    console.log('Jenis Pengeluaran:', selectedValue);

                    // Function to toggle visibility of input forms
                    function toggleInputForms(showKaryawan) {
                        var karyawanDiv = modal.querySelector('#div_nama_karyawan');
                        var vendorDiv = modal.querySelector('#div_nama_vendor');

                        if (karyawanDiv && vendorDiv) {
                            karyawanDiv.style.display = showKaryawan ? 'block' : 'none';
                            vendorDiv.style.display = showKaryawan ? 'none' : 'block';
                        }
                    }

                    // Show appropriate form based on selected value
                    if (selectedValue === 'gaji_karyawan') {
                        toggleInputForms(true);
                    } else if (selectedValue === 'pembayaran_vendor') {
                        toggleInputForms(false);
                    }

                    // Add change event listener to handle future changes
                    jenisPengeluaranSelect.addEventListener('change', function () {
                        var newValue = this.value;
                        if (newValue === 'gaji_karyawan') {
                            toggleInputForms(true);
                        } else if (newValue === 'pembayaran_vendor') {
                            toggleInputForms(false);
                        } else {
                            // Hide both if neither option is selected
                            toggleInputForms(null);
                        }
                    });
                } else {
                    console.log('Jenis Pengeluaran select not found in modal');
                }

                // Function to handle collapse toggle
                function handleCollapseToggle(button, collapse, dataAttribute) {
                    if (button && collapse) {
                        var value = modal.getAttribute(dataAttribute);

                        function toggleCollapse() {
                            if (value === '1') {
                                collapse.classList.remove('hidden');
                                button.checked = true;
                            } else {
                                collapse.classList.add('hidden');
                                button.checked = false;
                            }
                        }

                        // Initial state
                        toggleCollapse();

                        // Add event listener for future changes of the checkbox
                        button.addEventListener('change', function () {
                            value = this.checked ? '1' : '0';
                            toggleCollapse();
                        });
                    } else {
                        console.log(dataAttribute + ' button or collapse div not found in modal');
                    }
                }

                // Handle pajak collapse
                handleCollapseToggle(pajakButton, collapsePajak, 'data-pajak-value');

                // Handle hutang collapse
                handleCollapseToggle(hutangButton, collapseHutang, 'data-hutang-value');
            } else {
                console.log('Modal not found:', modalId);
            }
        });
    });
});
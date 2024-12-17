document.addEventListener('DOMContentLoaded', function () {
    const kategoriSelect = document.getElementById('kategori_akun');
    const subakunSelect = document.getElementById('subakun');

    kategoriSelect.addEventListener('change', function () {
        const kategoriTerpilih = kategoriSelect.value;

        // Hapus opsi lama
        subakunSelect.innerHTML = '<option value="">-- Pilih Sub Kategori Akun --</option>';

        if (kategoriTerpilih) {
            fetch(`/get-subkategori?kategori=${kategoriTerpilih}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(sub => {
                        const option = document.createElement('option');
                        option.value = sub.subakun; // Ubah sesuai field
                        option.textContent = sub.subakun; // Ubah sesuai field
                        subakunSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error:', error));
        }
    });
});
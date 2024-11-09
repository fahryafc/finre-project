document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // Prevent the default button action

            const form = this.closest('.delete-form');
            const imageUrl = this.getAttribute('data-image-url'); // Get the image URL from the data attribute

            Swal.fire({
                title: "Yakin ingin menghapus?",
                text: "Data akan hilang secara permanen ketika dihapus!",
                imageUrl: imageUrl, // Use the imageUrl from the data attribute
                showCancelButton: true,
                confirmButtonColor: "#EF3054",
                cancelButtonColor: "#B6D0D7",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal",
                reverseButtons: true, // Moves confirm button to the right
                customClass: {
                    title: 'swal-custom-title' // Add custom class to title
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit the form if confirmed
                }
            });
        });
    });
});

document.getElementById('kategori').addEventListener('change', function () {
    if (this.value === 'tambahKategori') {
        // Tampilkan modal
        document.getElementById('tambahKategori').classList.remove('hidden');
        // Reset nilai select
        this.value = '';
    }
});

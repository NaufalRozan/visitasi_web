<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmActivate(id) {
        Swal.fire({
            title: 'Aktifkan Akreditasi?',
            text: "Akreditasi ini akan diaktifkan!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, aktifkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('activate-form-' + id).submit();
            }
        });
    }
</script>

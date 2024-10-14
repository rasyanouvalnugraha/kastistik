<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<a href="logout.php" id="logout">Logout</a>

<script>
    document.getElementById('logout').onclick =
        function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Beneran Mau Logout?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Iyaa',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'logout.php';
                }
            });
        };
</script>
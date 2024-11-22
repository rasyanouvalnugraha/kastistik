<?php
session_start();

include "connection/database.php";
if ($_SESSION['role'] != '2') {
    header('location: index.php');
    exit();
}

// Pagination settings
$data_per_page = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_index = ($page - 1) * $data_per_page;

// Filter by date range
$where_clause = "WHERE type IN (1, 2)";
if (isset($_POST['submit'])) {
    $start = $_POST['tanggal_awal'];
    $end = $_POST['tanggal_akhir'];
    if ($start && $end) {
        $where_clause .= " AND date BETWEEN '$start' AND '$end'";
    }
}

// Get total records
$total_query = mysqli_query($db, "SELECT COUNT(*) AS total FROM transactions $where_clause");
$total_data = mysqli_fetch_assoc($total_query)['total'];
$total_pages = ceil($total_data / $data_per_page);

// Fetch paginated data
$pemasukan = mysqli_query($db, "
    SELECT date AS tanggal, fullname AS nama, amount AS jumlah 
    FROM transactions 
    JOIN users ON transactions.id_user = users.id 
    $where_clause 
    ORDER BY date DESC, fullname DESC, amount DESC 
    LIMIT $start_index, $data_per_page
");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DASHBOARD</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/background.css">
    <link rel="stylesheet" href="css/card.css">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="icon" href="asset/BPS.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

</head>

<body class="bg-gray-100">
    <div class="flex">

        <!-- NAVBAR -->
        <section class="relative">
            <nav class="navbar h-screen mr-5">
                <?php include "layout.user/navbar.user.php"; ?>
            </nav>
        </section>

        <section class="flex-1">
            <!-- Header -->
            <div class="text-lg font-mulish-extend w-full p-5 justify-between flex shadow-md navbar">
                <h1>Dashboard</h1>
                <h1><?php print $_SESSION['fullname']; ?></h1>
            </div>

            <h1 class="text-2xl font-mulish-extend mx-4 my-5">Data Pemasukan</h1>

            <!-- TABEL DATA PEMASUKAN -->
            <table id="dataTable" class="min-w-full rounded-lg shadow-md p-6">
                <thead>
                    <tr class="bg-gradient navbar text-white">
                        <th class="py-2 px-4 border-b font-mulish sticky top-0 z-10">Nama</th>
                        <th class="py-2 px-4 border-b font-mulish sticky top-0 z-10">Tanggal</th>
                        <th class="py-2 px-4 border-b font-mulish sticky top-0 z-10 text-start">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($pemasukan)) : ?>
                        <tr class='hover:bg-gray-300 hover:cursor-pointer'>
                            <td class='py-2 px-4 text-center font-mulish'><?= $row['nama'] ?></td>
                            <td class='py-2 px-4 text-center font-mulish'><?= date("d F Y", strtotime($row['tanggal'])) ?></td>
                            <td class='py-2 px-4 text-center font-mulish'>Rp. <?= number_format($row['jumlah'], 0, '.', '.') ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>




        </section>
    </div>

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "pageLength": 6, // Data per halaman
                "lengthChange": false, // Hilangkan opsi dropdown jumlah data per halaman
                "ordering": true, // Aktifkan fitur sorting
                "language": {
                    "paginate": {
                        "previous": "Prev",
                        "next": "Next"
                    },
                    "search": "Cari:",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 data",
                    "infoFiltered": "(dari total _MAX_ data)"
                },
                "dom": '<"flex justify-between items-center mb-4"<"text-left"f><"text-right"p>>t<"flex justify-between items-center mt-4"<"text-left"i><"text-right"p>>', // Ubah layout
            });
        });
    </script>

</body>

<style>
    /* Hapus scrollbar pada browser berbasis WebKit */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    /* Hapus scrollbar untuk browser lain */
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        background-color: #F3F4F6;
        /* Warna tombol pagination */
        color: #374151;
        /* Warna teks */
        border-radius: 4px;
        margin: 0 2px;
        padding: 5px 10px;
        border: none;
        transition: all 0.3s;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: #7D46FD;
        /* Warna hover tombol */
        color: white;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background-color: #7D46FD;
        /* Warna tombol aktif */
        color: white;
    }

    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #E5E7EB;
        border-radius: 4px;
        padding: 4px 10px;
        margin-left: 8px;
    }

    .dataTables_wrapper .dataTables_info {
        font-size: 0.875rem;
        color: #6B7280;
    }

    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 10px;
    }
</style>


</html>
<?php
session_start();

include "connection/database.php";

if ($_SESSION['role'] != '2') {
    header('location: index.php');
    exit();
}

// Query data pengeluaran
$pengeluaran = mysqli_query($db, "
    SELECT 
        transactions.date AS tanggal, 
        users.fullname AS nama, 
        transactions.amount AS jumlah, 
        transactions.keterangan AS Keterangan 
    FROM transactions 
    JOIN users ON transactions.id_user = users.id 
    WHERE transactions.type = 3 AND transactions.approve = 1 
    ORDER BY transactions.date DESC, users.fullname DESC, transactions.amount DESC
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
    <!-- Link ke DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
</head>

<body class="bg-gray-100">
    <div class="flex">

        <!-- NAVBAR -->
        <section class="relative">
            <nav class="navbar h-screen">
                <?php include "layout.user/navbar.user.php"; ?>
            </nav>
        </section>

        <section class="flex-1">

            <!-- Header -->
            <div class="flex text-lg font-mulish-extend w-full sm:p-5 shadow-md navbar">
                <button id="mobileMenuToggle" class="sm:hidden flex px-4 py-2 bg-gradient">
                    <img src="asset/Content.png" alt="Menu" class="h-6 w-6">
                </button>
                <div class="w-full flex justify-between items-center px-2 sm:px-0 sm:mt-0">
                    <h1 class="text-sm sm:text-lg text-black">Dashboard</h1>
                    <h1 class="text-sm sm:text-lg text-black"><?php print $_SESSION['fullname']; ?></h1>
                </div>
            </div>

            <!-- MOBILE MENU -->
            <section id="mobileMenu" class="hidden fixed top-0 left-0 w-full h-screen bg-gray-800 bg-opacity-90 z-20">
                <div class="bg-gray-800 h-full p-5 text-white">
                    <button id="closeMobileMenu" class="text-xl font-bold">X</button>
                    <ul class="mt-5 space-y-4 font-mulish">
                        <li><a href="user.php" class="block">Dashboard</a></li>
                        <hr>
                        <li><a href="data.pemasukan.user.php" class="block">Pemasukan</a></li>
                        <li><a href="data.pengeluaran.user.php" class="block">Pengeluaran</a></li>
                        <hr>
                        <li><a href="request.user.php" class="block">Request</a></li>
                        <li><a href="logout.php" class="block">Logout</a></li>
                    </ul>
                </div>
            </section>

            <h1 class="sm:text-2xl text-sm font-mulish-extend mx-4 my-5">Data Pengeluaran</h1>

            <!-- TABEL DATA PEMASUKAN -->
            <div class="sm:mx-8  mx-4">
                <table id="dataTable" class="min-w-full rounded-lg shadow-md">
                    <thead>
                        <tr class="bg-gradient navbar text-xs sm:text-base text-white">
                            <th class="py-2 px-4 border-b font-mulish sticky top-0 z-10">Nama</th>
                            <th class="py-2 px-4 border-b font-mulish sticky top-0 z-10">Tanggal</th>
                            <th class="py-2 px-4 border-b font-mulish sticky top-0 z-10">Jumlah</th>
                            <th class="py-2 px-4 border-b font-mulish sticky top-0 z-10">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($pengeluaran)) : ?>
                            <tr class='hover:bg-gray-300 hover:cursor-pointer text-xs sm:text-base'>
                                <td class='py-2 px-4 text-center font-mulish'><?= $row['nama'] ?></td>
                                <td class='py-2 px-4 text-center font-mulish'><?= date("d F Y", strtotime($row['tanggal'])) ?></td>
                                <td class='py-2 px-4 text-center font-mulish'>Rp. <?= number_format($row['jumlah'], 0, '.', '.') ?></td>
                                <td class='py-2 px-4 text-center font-mulish'><?= $row['Keterangan'] ?></td>

                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        </section>
    </div>

    <!-- Script jQuery dan DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
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
                "order": [
                    [1, "desc"]
                ], // Mengurutkan kolom ke-2 (index 1) yaitu "Tanggal" descending
                "columnDefs": [{
                        "orderable": false,
                        "targets": [0, 2, 3]
                    } // Kolom "Nama" dan "Jumlah" tidak bisa diurutkan
                ]
                // "dom": '<"flex justify-between items-center mb-4"<"text-left"f><"text-right"p>>t<"flex justify-between items-center mt-4"<"text-left"i><"text-right"p>>'
            });
        });
        // MOBILE MENU TOGGLE
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const closeMobileMenu = document.getElementById('closeMobileMenu');
        const mobileMenu = document.getElementById('mobileMenu');

        mobileMenuToggle.addEventListener('click', () => {
            mobileMenu.classList.remove('hidden');
        });
        closeMobileMenu.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
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
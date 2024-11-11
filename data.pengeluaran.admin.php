<?php
session_start();

include "connection/database.php";
if ($_SESSION['role'] != '1') {
    header('location: index.php');
    exit();
}

$pengeluaran = mysqli_query($db, "
    SELECT 
        transactions.id AS ID,
        transactions.date AS tanggal, 
        users.fullname AS nama, 
        transactions.amount AS jumlah, 
        transactions.keterangan AS Keterangan 
    FROM transactions 
    JOIN users ON transactions.id_user = users.id 
    WHERE transactions.type = 3 
    AND transactions.approve = 1 
    AND transactions.date
    ORDER BY username 
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DASHBOARD ADMIN</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/background.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="icon" href="asset/BPS.png" type="image/x-icon">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

</head>

<body class="bg-gray-100">
    <div class="flex">

        <!-- NAVBAR -->
        <section class="relative">
            <nav class="navbar h-screen mr-5">
                <?php include "layout/navbar.php"; ?>
            </nav>
        </section>

        <section class="flex-1">
            <div class="text-lg font-mulish-extend w-full p-5 justify-between flex shadow-md navbar">
                <h1>Data Pengeluaran</h1>
                <h1><?php print $_SESSION['username']; ?></h1>
            </div>

            <div class="flex justify-between">
                <h1 class="text-2xl font-mulish-extend mx-4 my-5">Data Pengeluaran</h1>
                <div class="flex mr-10">
                    <a href="pengeluaran.admin.php" class="flex items-center justify-center gap-2">
                        <h1 class="font-mulish">Tambah</h1>
                        <img src="asset/Plus Math.svg" alt="" class="w-10 h-10 bg-gradient p-1 rounded-lg">
                    </a>
                </div>
            </div>

            <!-- TABEL DATA PENGELUARAN -->
            <div class="overflow-x-auto mx-8 border-b-2">
                <div class="max-h-80 relative overflow-y-auto no-scrollbar">
                    <table id="pengeluaranTable" class="min-w-full rounded-lg shadow-md">
                        <thead>
                            <tr class="bg-gradient navbar text-white">
                                <th class="py-2 px-4 border-b font-mulish sticky top-0 z-10">Nama</th>
                                <th class="py-2 px-4 border-b font-mulish sticky top-0 z-10">Tanggal</th>
                                <th class="py-2 px-4 border-b font-mulish sticky top-0 z-10">Keterangan</th>
                                <th class="py-2 px-4 border-b font-mulish sticky top-0 z-10 text-start">Jumlah</th>
                                <th class="py-2 px-4 border-b font-mulish sticky top-0 z-10 text-start">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_assoc($pengeluaran)) {
                                echo "<tr class='hover:bg-gray-300 hover:cursor-pointer'>";
                                echo "<td class='py-2 px-4 text-center font-mulish'>" . $row['nama'] . "</td>";
                                echo "<td class='py-2 px-4 text-center font-mulish' data-order='" . date('Y-m-d', strtotime($row['tanggal'])) . "'>" . date('d M Y', strtotime($row['tanggal'])) . "</td>";
                                echo "<td class='py-2 px-4 text-center font-mulish'>" . $row['Keterangan'] . "</td>";
                                echo "<td class='py-2 px-4 text-center font-mulish'>Rp. " . number_format($row['jumlah'], 0, '.', '.') . "</td>";
                                echo "<td class='py-2 px-4 text-center font-mulish'>";
                                echo "<form method='POST' action=''>";
                                echo "<input type='hidden' name='delete_id' value='" . $row['ID'] . "'>";
                                echo "<button type='submit' name='delete' class='focus:outline-none'>";
                                echo "<img src='asset/Remove.svg' alt='Delete' class='w-8 h-8 down p-1 rounded-md'>";
                                echo "</button>";
                                echo "</form>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </section>
    </div>

    <!-- DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#pengeluaranTable').DataTable({
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                "order": [
                    [1, "desc"]
                ], // Urutkan berdasarkan kolom tanggal (index 1) secara descending
                "columnDefs": [{
                        "orderable": false,
                        "targets": [0, 2, 3, 4]
                    } // Nonaktifkan fitur urutan di kolom lainnya
                ],
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "paginate": {
                        "first": "Awal",
                        "last": "Akhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
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

    .down {
        background-color: #F51313;
    }
</style>

</html>
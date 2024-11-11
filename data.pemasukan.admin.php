<?php
session_start();
include "connection/database.php";

if ($_SESSION['role'] != '1') {
    header('location: index.php');
    exit();
}

if (isset($_POST['delete'])) {
    $delete_id = $_POST['delete_id'];

    // Query untuk menghapus data berdasarkan ID
    $delete_query = "DELETE FROM transactions WHERE id = '$delete_id' AND type IN (1,2)";
    $result = mysqli_query($db, $delete_query);

    if ($result) {
        // Jika berhasil, arahkan kembali ke halaman ini
        header("Location: data.pemasukan.admin.php");
        exit();
    } else {
        // Jika gagal, tampilkan pesan error
        echo "<script>alert('Error deleting record');</script>";
    }
}


$pemasukan = mysqli_query(
    $db,
    "
    SELECT 
    transactions.id AS ID, 
    transactions.date AS tanggal, 
    users.fullname AS nama, 
    transactions.amount AS jumlah, 
    transactions.keterangan AS Keterangan 
    FROM transactions 
    JOIN users ON transactions.id_user = users.id 
    WHERE transactions.type IN (1,2)
    AND transactions.approve = 1 
    ORDER BY transactions.date DESC, transactions.id DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DASHBOARD ADMIN</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/background.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/font.css">
    <link rel="icon" href="asset/BPS.png" type="image/x-icon">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .up {
            background-color: #F51313;
        }

        .btn {
            background-color: #7D46FD;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <section class="relative">
            <nav class="navbar h-screen mr-5">
                <?php include "layout/navbar.php"; ?>
            </nav>
        </section>

        <section class="flex-1">
            <div class="text-lg font-mulish-extend w-full p-5 justify-between flex shadow-md navbar stiky">
                <h1>Data Pemasukan</h1>
                <h1><?php echo $_SESSION['username']; ?></h1>
            </div>

            <div class="flex justify-between">
                <h1 class="text-2xl font-mulish-extend mx-4 my-5">Data Pemasukan</h1>
                <div class="flex mr-10">
                    <a href="pemasukan.admin.php" class="flex items-center justify-center gap-2">
                        <h1 class="font-mulish">Tambah</h1>
                        <img src="asset/Plus Math.svg" alt="" class="w-10 h-10 bg-gradient p-1 rounded-lg">
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto mx-8 border-b-2">
                <table id="dataPemasukan" class="min-w-full rounded-lg shadow-md display">
                    <thead>
                        <tr class="bg-gradient text-white">
                            <th class="py-2 px-4 border-b font-mulish">Nama</th>
                            <th class="py-2 px-4 border-b font-mulish">Tanggal</th>
                            <th class="py-2 px-4 border-b font-mulish">Jumlah</th>
                            <th class="py-2 px-4 border-b font-mulish">Keterangan</th>
                            <th class="py-2 px-4 border-b font-mulish">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($pemasukan)) { ?>
                            <tr class='hover:bg-gray-300'>
                                <td class='py-2 px-4 text-center font-mulish'><?= htmlspecialchars($row['nama']); ?></td>
                                <td class='py-2 px-4 text-center font-mulish' data-order="<?= date('Y-m-d', strtotime($row['tanggal'])); ?>">
                                    <?= date('d M Y', strtotime($row['tanggal'])); ?>
                                </td>
                                <td class='py-2 px-4 text-center font-mulish'>Rp. <?= number_format($row['jumlah'], 0, '.', '.'); ?></td>
                                <td class='py-2 px-4 text-center font-mulish'><?= !empty($row['Keterangan']) ? htmlspecialchars($row['Keterangan']) : " - "; ?></td>
                                <td class='py-2 px-4 text-center font-mulish'>
                                    <form method='POST' action=''>
                                        <input type='hidden' name='delete_id' value='<?= $row['ID']; ?>'>
                                        <button type='submit' name='delete' class='focus:outline-none'>
                                            <img src='asset/Remove.svg' alt='Delete' class='w-8 h-8 up p-1 rounded-md'>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pdfmake@0.1.53/build/pdfmake.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pdfmake@0.1.53/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#dataPemasukan').DataTable({
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf'],
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
                },
                "responsive": true,
                "ordering": true,
                "autoWidth": false,
                "order": [
                    [1, "desc"]
                ], // Urutkan berdasarkan kolom tanggal (index 1) secara descending
                "columnDefs": [{
                        "orderable": false,
                        "targets": [0, 2, 3, 4]
                    } // Nonaktifkan fitur urutan di kolom lainnya
                ]
            });
        });
    </script>


</body>

</html>
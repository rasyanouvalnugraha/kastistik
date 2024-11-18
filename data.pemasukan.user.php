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

            <!-- Search Form -->
            <section class="container mx-auto px-4 flex-1">
                <form method="POST" action="" class="flex">
                    <div class="ml-5 w-2/3">
                        <label for="tanggal_awal" class="block text-gray-500 text-sm font-mulish mb-2">Tanggal Awal</label>
                        <input type="date" id="tanggal_awal" name="tanggal_awal" class="py-3 appearance-none border-2 w-full px-2 text-gray-700 leading-tight rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="w-2/3 ml-6">
                        <label for="tanggal_akhir" class="block text-gray-500 text-sm font-mulish mb-2">Tanggal Akhir</label>
                        <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="py-3 appearance-none border-2 w-full px-4 leading-tight rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="ml-4 mr-10 mt-2">
                        <button type="submit" name="submit" class="bg-gradient text-white p-1 rounded w-full mx-2 my-4 font-mulish">
                            <img src="asset/Search.svg" alt="">
                        </button>
                    </div>
                </form>
            </section>

            <!-- TABEL DATA PEMASUKAN -->
            <div class="overflow-x-auto mx-8">
                <div class="max-h-72 relative overflow-y-auto no-scrollbar">
                    <table class="min-w-full rounded-lg shadow-md">
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
                </div>
                <!-- PAGINATION -->
                <div class="flex justify-end mt-4">
                    <?php if ($page > 1) : ?>
                        <a href="?page=<?= $page - 1 ?>" class="bg-gray-300 px-3 py-1 rounded-l">Prev</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                        <a href="?page=<?= $i ?>" class="bg-gray-300 px-3 py-1 <?= ($i == $page) ? 'bg-blue-500 text-white' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages) : ?>
                        <a href="?page=<?= $page + 1 ?>" class="bg-gray-300 px-3 py-1 rounded-r">Next</a>
                    <?php endif; ?>
                </div>
            </div>



        </section>
    </div>
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
</style>

</html>
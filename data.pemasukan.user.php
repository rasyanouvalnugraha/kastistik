<?php
session_start();

include "connection/database.php";
if ($_SESSION['role'] != '2') {
    header('location: index.php');
    exit(); 
} 

// query ambil data pemasukan di database yang type = 1 / 2
// Ambil data pemasukan di database yang type = 1 / 2


// button seacrh 
if (isset($_POST['sumbit'])) {
    $start = $_POST['tanggal_awal'];
    $end = $_POST['tanggal_akhir'];

    //query seacrhing tanggal

    if ($start != null || $end != null) {
        $pemasukan = mysqli_query($db, "
        SELECT date AS tanggal, username AS nama, amount AS jumlah 
        FROM transactions 
        JOIN users ON transactions.id_user = users.id 
        WHERE type IN (1, 2) AND date BETWEEN '$start' AND '$end'
        ORDER BY date DESC. username DESC, amount DESC;
    ");
    } else {
        $pemasukan = mysqli_query($db, "
        SELECT date AS tanggal, username AS nama, amount AS jumlah 
        FROM transactions 
        JOIN users ON transactions.id_user = users.id 
        WHERE type IN (1, 2) 
        ORDEY BY date DESC, username DESC , amount DESC;");
    }
} else {
    $pemasukan = mysqli_query($db, "
        SELECT date AS tanggal, username AS nama, amount AS jumlah 
        FROM transactions 
        JOIN users ON transactions.id_user = users.id 
        WHERE type IN (1, 2) 
        ORDER BY date DESC, username DESC, amount DESC;
        ;");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DASHBOARD ADMIN</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/background.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="card.css">
    <link rel="stylesheet" href="css/background.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/navbar.css">


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

            <!--  -->
            <div class="text-lg font-mulish-extend w-full p-5 justify-between flex shadow-md navbar">
                <h1>Dashboard</h1>
                <h1><?php print $_SESSION['username']; ?></h1>
            </div>


            <h1 class="text-2xl font-mulish-extend mx-4 my-5">Data Pemasukan</h1>

            <section class="container mx-auto px-4 flex-1">
                <!-- FITUR SEACRHING TANGGAL-->
                <form method="POST" action="" class="flex">
                    <div class="ml-5 w-2/3">
                        <label for="tanggal_awal" class="block text-gray-500 text-sm font-mulish mb-2">Tanggal Awal</label>
                        <input type="date" id="tanggal_awal" name="tanggal_awal"
                            class="py-4 appearance-none border-2 w-full px-2 text-gray-700 leading-tight rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="w-2/3 ml-6">
                        <label for="tanggal_akhir" class="block text-gray-500 text-sm font-mulish mb-2">Tanggal Akhir</label>
                        <input type="date" id="tanggal_akhir" name="tanggal_akhir"
                            class="py-4 appearance-none border-2 w-full px-4 leading-tight rounded-lg focus:outline-none focus:border-blue-500"
                            >
                    </div>
                    <div class="ml-4 mr-10 mt-2">
                        <button type="submit" name="sumbit" class="bg-gradient text-white p-2 rounded w-full mx-2 my-5 font-mulish">
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
                            <?php
                            while ($row = mysqli_fetch_assoc($pemasukan)) {
                                echo "<tr class='hover:bg-gray-300 hover:cursor-pointer'>";
                                echo "<td class='py-2 px-4 text-center font-mulish'>" . $row['nama'] . "</td>";
                                echo "<td class='py-2 px-4 text-center font-mulish'>" . $row['tanggal'] . "</td>";
                                echo "<td class='py-2 px-4 text-center font-mulish'>Rp. " . number_format($row['jumlah'], 0, '.', '.') . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </section>


    </div>
</body>
<style>
    /* Hapus scrollbar pada browser berbasis WebKit */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
        /* Sembunyikan scrollbar di Chrome, Safari, dan Opera */
    }

    /* Hapus scrollbar untuk browser lain */
    .no-scrollbar {
        -ms-overflow-style: none;
        /* Hapus scrollbar di Internet Explorer dan Edge */
        scrollbar-width: none;
        /* Hapus scrollbar di Firefox */
    }
</style>


</html>
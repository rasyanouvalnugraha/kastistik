<?php
include 'connection/database.php';
session_start();
if ($_SESSION['role'] != '1') {
    header('location: index.php');
    exit();
}
 
// masukkan data ke table transaction 
if (isset($_POST['min'])) {


    mysqli_query($db, "INSERT INTO transactions 
    (id_user, amount, keterangan, type, date, saldo, approve) 
    VALUES (
        '" . $_POST['username'] . "',
        '" . $_POST['jumlah'] . "',
        '" . $_POST['keterangan'] . "',
        3,
        '" . $_POST['tanggal'] . "',
        '" . $_POST['jumlah'] . "',
        1
    )") or die(mysqli_error($db));

    header('location: pengeluaran.admin.php');

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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body class="">
    <div class="flex">
        <section class="relative">
            <nav class="navbar h-screen">
                <?php include "layout/navbar.php"; ?>
            </nav>
        </section>

        <section class="flex-1">
            <div class="text-lg font-mulish-extend w-full p-5 flex justify-between shadow-md navbar">
                <h1>Input Data</h1>
                <?php print $_SESSION['username']; ?>
            </div>

            <section class="flex flex-col md:flex-row">
                <div class="flex flex-1 flex-col w-full h-full p-6">
                    <h1 class="text-2xl font-mulish-extend mb-6">Pengeluaran</h1>

                    <form action="pengeluaran.admin.php" class="space-y-4 font-mulish" method="POST">
                        <div>
                            <!-- QUERY KALO DATA BERHASIL DITAMBAH -->
                             <?php
                             if (isset($_POST['add'])) {
                                 echo "<div class='text-green-600 text-lg'>Data berhasil dimasukkan</div>";
                             } else {
                                 // query kalo data gagal ditambah
                                 if (!isset($_POST['username']) && isset($_POST['tanggal']) && isset($_POST['jumlah'])) {
                                     echo "<div class='text-red-600 text-lg'>Data gagal dimasukkan</div>";
                                 }
                             }
                            ?>
                            <label for="username" class="flex text-gray-700 font-semibold mb-2">Username</label>
                            <div class="flex items-center border border-gray-300 rounded-md focus-within:ring-2 focus-within:ring-blue-500">
                                <img src="asset/Person.png" alt="Person Icon" class="w-6 h-6 ml-3">
                                <select name="username" id="username" class="w-full px-6 py-4 no-border">
                                    <option value="">Pilih User</option>
                                    <?php
                                    // query mengambil jumlah users di table user
                                    $sql = mysqli_query($db, "SELECT * FROM `users` WHERE role = '2'") or die(mysqli_error($db));
                                    while ($data = mysqli_fetch_array($sql)) {
                                        echo "<option value=$data[id]> $data[username]</option>";
                                    }
                                    ?>
                                </select>

                            </div>
                        </div>

                        <div>
                            <label for="keterangan" class="block text-gray-700 font-semibold mb-2">Keterangan</label>
                            <div class="flex items-center border border-gray-300 rounded-md focus-within:ring-2 focus-within:ring-blue-500">
                                <img src="asset/Strategy.svg" alt="Coins Icon" class="w-6 h-6 ml-3">
                                <input type="text" id="amount" name="keterangan" class="w-full px-4 py-4 focus:outline-none" placeholder="Beli sabun ..." required>
                            </div>
                        </div>
                        <div>
                            <label for="amount" class="block text-gray-700 font-semibold mb-2">Jumlah Uang</label>
                            <div class="flex items-center border border-gray-300 rounded-md focus-within:ring-2 focus-within:ring-blue-500">
                                <img src="asset/Receive Cash.svg" alt="Coins Icon" class="w-6 h-6 ml-3">
                                <input type="text" id="amount" name="jumlah" class="w-full px-4 py-4 focus:outline-none" placeholder="Masukkan jumlah uang" required>
                            </div>
                        </div>

                        <div>
                            <label for="date" class="block text-gray-700 font-semibold mb-2">Tanggal</label>
                            <div class="flex items-center border border-gray-300 rounded-md focus-within:ring-2 focus-within:ring-blue-500">
                                <img src="asset/Calendar.svg" alt="Calendar Icon" class="w-6 h-6 ml-3">
                                <input type="datetime-local" id="date" name="tanggal" class="w-full px-4 py-4 focus:outline-none" required>
                            </div>
                        </div>

                        <div class="mt-3">
                            <input type="submit" name="min" value="Sumbit" class="w-full bg-button text-white px-4 py-4 rounded-md transition-colors duration-300 font-mulish-extend">
                        </div>
                    </form>
                </div>

                <div class="flex-1 w-full md:flex md:items-center hidden">
                    <div>
                        <img src="asset/Woman shopping online on tablet.svg" alt="Man and woman discussing idea" class="p-6">
                    </div>
                </div>
            </section>

            <div class="">
                <?php include "layout/card.php"; ?>
            </div>
        </section>
    </div>

</body>
<style>
    .bg-button {
        background: #7D46FD;
    }

    .custom-height {
        height: 6rem;
    }

    select.no-border {
        border: none;
        outline: none;
        appearance: none;
    }

    select.no-border option {
        border: none;
        /* Pastikan opsi juga tidak memiliki border */
    }
</style>

</html>
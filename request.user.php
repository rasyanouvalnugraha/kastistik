<?php
session_start();
include 'connection/database.php';
if ($_SESSION['role'] != '2') {
    header('location: index.php');
    exit();
}

if (isset($_POST['add'])) {

    $username = $_POST['username'];
    $keperluan = $_POST['keperluan'];
    $amount = $_POST['amount'];
    $tanggal = $_POST['tanggal'];


    mysqli_query($db, "
    INSERT INTO `transactions` (`id`, `id_user`, `amount`, `type`, `date`, `keterangan`, `saldo`, `approve`) VALUES (NULL, '$username', '$amount', '3', '$tanggal', '$keperluan', '$amount', '0');
    ") or die(mysqli_error($db));

    header("location: request.user.php");
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

<body class>
    <div class="flex">
        <section class="relative">
            <nav class="navbar h-screen">
                <?php include "layout.user/navbar.user.php"; ?>
            </nav>
        </section>

        <section class="flex-1">
            <div class="text-lg font-mulish-extend w-full p-5 justify-between flex shadow-md navbar">
                <h1>Dashboard</h1>
                <h1><?php print $_SESSION['username']; ?></h1>
            </div>

            <section class="">
                <section class="flex flex-col md:flex-row">
                    <div class="flex flex-1 flex-col w-full h-full p-6">
                        <h1 class="text-2xl font-mulish-extend mb-6">Request User To Admin</h1>
    
                        <form action="request.user.php" class="space-y-4 font-mulish" method="POST">
                            <div>
                                <?php
                                // query menampilkan jika berhasil di input data
                                if (isset($_POST['add'])) {
                                    echo "<div class='text-green-600 text-lg'>Request Berhasil dikirim ke Admin, tunggu admin approve</div>";
                                }
                                ?>
                                <label for="username" class="flex text-gray-700 font-semibold mb-2">Username</label>
                                <div class="flex items-center border border-gray-300 rounded-md focus-within:ring-2 focus-within:ring-blue-500 bg-white">
                                    <img src="asset/Person.png" alt="Person Icon" class="w-6 h-6 ml-3">
                                    <select name="username" id="username" class="w-full px-6 py-4 no-border" required>
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
                                <label for="needs" class="block text-gray-700 font-semibold mb-2">Keperluan</label>
                                <div class="flex items-center border border-gray-300 rounded-md focus-within:ring-2 focus-within:ring-blue-500 bg-white">
                                    <img src="asset/Strategy.svg" alt="Coins Icon" class="w-6 h-6 ml-3">
                                    <input type="text" id="amount" name="keperluan" class="w-full px-4 py-4 focus:outline-none" placeholder="Beli Sabun....." required>
                                </div>
                            </div>
    
                            <div>
                                <label for="amount" class="block text-gray-700 font-semibold mb-2">Jumlah Uang</label>
                                <div class="flex items-center border border-gray-300 rounded-md focus-within:ring-2 focus-within:ring-blue-500 bg-white">
                                    <img src="asset/Receive Cash.svg" alt="Coins Icon" class="w-6 h-6 ml-3">
                                    <input type="text" id="amount" name="amount" class="w-full px-4 py-4 focus:outline-none" placeholder="Masukkkan Jumlah Uang" required>
                                </div>
                            </div>
    
                            <div>
                                <label for="date" class="block text-gray-700 font-semibold mb-2">Tanggal</label>
                                <div class="flex items-center border border-gray-300 rounded-md focus-within:ring-2 focus-within:ring-blue-500 bg-white">
                                    <img src="asset/Calendar.svg" alt="Calendar Icon" class="w-6 h-6 ml-3">
                                    <input type="datetime-local" id="date" name="tanggal" class="w-full px-4 py-4 focus:outline-none" required>
                                </div>
                            </div>
    
                            <div class="mt-3">
                                <input type="submit" name="add" value="Sumbit" class="w-full bg-button text-white px-4 py-4 rounded-md transition-colors duration-300 font-mulish-extend">
                            </div>
                        </form>
                    </div>
                    <div class="flex-1 w-full md:flex md:items-center hidden ">
                        <div>
                            <img src="asset/Male specialist working in support service.svg" alt="Man and woman discussing idea" class="p-6">
                        </div>
                    </div>
    
                </section>
                
                <section>
                    <?php include 'layout/card.php'?>
                </section>
            </section>


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
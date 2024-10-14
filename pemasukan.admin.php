<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header('location: index.php');
    exit();
}
include "connection/database.php";
if ($_SERVER["REQUEST_METHOD"])
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
                    <h1 class="text-2xl font-mulish-extend mb-6">Pemasukan</h1>

                    <form action="pemasukan.admin.php" class="space-y-4 font-mulish" method="POST">
                        <div>
                            <label for="username" class="flex text-gray-700 font-semibold mb-2">Username</label>
                            <div class="flex items-center border border-gray-300 rounded-md focus-within:ring-2 focus-within:ring-blue-500">
                                <img src="Person.png" alt="Person Icon" class="w-6 h-6 ml-3">
                                <input type="text" id="username" name="username" class="w-full px-4 py-4 focus:outline-none" placeholder="Masukkan username" required>
                            </div>
                        </div>

                        <div>
                            <label for="amount" class="block text-gray-700 font-semibold mb-2">Jumlah Uang</label>
                            <div class="flex items-center border border-gray-300 rounded-md focus-within:ring-2 focus-within:ring-blue-500">
                                <img src="Receive Cash.svg" alt="Coins Icon" class="w-6 h-6 ml-3">
                                <input type="text" id="amount" name="jumlah" class="w-full px-4 py-4 focus:outline-none" placeholder="Masukkan jumlah uang" required>
                            </div>
                        </div>

                        <div>
                            <label for="date" class="block text-gray-700 font-semibold mb-2">Tanggal</label>
                            <div class="flex items-center border border-gray-300 rounded-md focus-within:ring-2 focus-within:ring-blue-500">
                                <img src="Calendar.svg" alt="Calendar Icon" class="w-6 h-6 ml-3">
                                <input type="datetime-local" id="date" name="tanggal" class="w-full px-4 py-4 focus:outline-none" placeholder="dd/mm/yyyy" required>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="w-full bg-button text-white px-4 py-4 rounded-md transition-colors duration-300 font-mulish-extend">
                                Confirm
                            </button>
                        </div>
                    </form>
                </div>

                <div class="flex-1 w-full md:flex md:items-center hidden md:block">
                    <div>
                        <img src="man and woman discussing idea.png" alt="Man and woman discussing idea" class="p-6">
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
</style>

</html>
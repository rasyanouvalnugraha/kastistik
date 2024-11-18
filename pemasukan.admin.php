<?php
include 'connection/database.php';
session_start();
if ($_SESSION['role'] != '1') {
    header('location: index.php');
    exit();
}
// query memasukkan data ke table transactions
if (isset($_POST['add'])) {
    // Ambil premi dari user berdasarkan username yang dipilih
    $fullname = $_POST['fullname'];
    $getPremi = mysqli_query($db, "SELECT premi FROM users WHERE id = '$fullname'");
    $premiData = mysqli_fetch_assoc($getPremi);
    $premi = $premiData['premi'];

    // Ambil jumlah yang diinput user
    $jumlah = preg_replace('/\D/', '', $_POST['jumlah']);
    $username = $_POST['fullname'];
    $tanggal = $_POST['tanggal'];
    $type = $_POST['type'];
    $keterangan = $_POST['keterangan'];

    // Pengecekan apakah jumlah lebih kecil dari premi
    if ($jumlah < $premi) {
        echo "<div class='text-red-600 text-lg'>Data gagal dimasukkan karna kurang dari premi</div>";
    } else {
        // Jika jumlah lebih besar atau sama dengan premi, masukkan ke database
        $result = mysqli_query($db, "INSERT INTO transactions (id_user, amount, type, date, saldo, keterangan,approve)
        VALUES ('$username', '$jumlah', '$type', '$tanggal', '$jumlah','$keterangan' , 1)");

        header('location: data.pemasukan.admin.php');
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/background.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="icon" href="asset/BPS.png" type="image/x-icon">
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

            <div class="flex justify-between items-center px-6 py-2">
                <h1 class="text-2xl font-mulish-extend">Pemasukan</h1>
                <a href="data.pemasukan.admin.php" class="bg-button p-2 rounded-lg mt-2 flex text-white font-mulish gap-2 w-1/7">
                    Cancel
                    <img src="asset/Left 2.svg" alt="" class="h-7 mr-1">
                </a>
            </div>
            <section class="flex flex-col md:flex-row">

                <div class="flex flex-1 flex-col w-full h-full p-6">


                    <form action="" class="space-y-4 font-mulish" method="POST">
                        <div>
                            <label for="username" class="flex text-gray-700 font-semibold mb-2">Username</label>
                            <div class="flex items-center border border-gray-300 rounded-md focus-within:ring-2 focus-within:ring-blue-500">
                                <img src="asset/Person.png" alt="Person Icon" class="w-6 h-6 ml-3">
                                <select name="fullname" id="fullname" class="w-full px-6 py-4 no-border">
                                    <option value="">Pilih User</option>
                                    <?php
                                    $sql = mysqli_query($db, "SELECT * FROM `users` WHERE role = '2'") or die(mysqli_error($db));
                                    while ($data = mysqli_fetch_array($sql)) {
                                        echo "<option value=$data[id]> $data[fullname]</option>";
                                    }
                                    ?>
                                </select>
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
                            <label for="type" class="block text-gray-700 font-semibold mb-2">Type</label>
                            <div class="flex items-center border border-gray-300 rounded-md focus-within:ring-2 focus-within:ring-blue-500">
                                <img src="asset/Strategy.svg" alt="Coins Icon" class="w-6 h-6 ml-3">
                                <select name="type" id="type" class="w-full px-4 py-4 focus:outline-none" onchange="toggleKeterangan()">
                                    <option value="1">Kas</option>
                                    <option value="2">Non Kas</option>
                                </select>
                            </div>
                        </div>

                        <div id="keteranganInput" style="display: none;">
                            <label for="keterangan" class="block text-gray-700 font-semibold mb-2">Keterangan</label>
                            <div class="flex items-center border border-gray-300 rounded-md focus-within:ring-2 focus-within:ring-blue-500">
                                <img src="asset/Law Book.svg" alt="Coins Icon" class="w-6 h-6 ml-3">
                                <input type="text" id="keterangan" name="keterangan" class="w-full px-4 py-4 focus:outline-none" placeholder="Perjadin....">
                            </div>
                        </div>

                        <div>
                            <label for="date" class="block text-gray-700 font-semibold mb-2">Tanggal</label>
                            <div class="flex items-center border border-gray-300 rounded-md focus-within:ring-2 focus-within:ring-blue-500">
                                <img src="asset/Calendar.svg" alt="Calendar Icon" class="w-6 h-6 ml-3">
                                <input type="datetime-local" id="date" name="tanggal" class="w-full px-4 py-4 focus:outline-none" required>
                            </div>
                        </div>

                        <div class="mt-3 flex">
                            <input type="submit" name="add" value="Confirm" class="w-full bg-button text-white px-4 py-4 rounded-md transition-colors duration-300 font-mulish-extend">
                        </div>
                    </form>
                </div>

                <div class="flex-1 w-full md:flex md:items-center hidden ">
                    <div>
                        <img src="asset/man and woman discussing idea.png" alt="Man and woman discussing idea" class="p-6">
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
<script>
    function toggleKeterangan() {
        var typeSelect = document.getElementById("type");
        var keteranganInput = document.getElementById("keteranganInput");
        if (typeSelect.value == "2") { // Non Kas
            keteranganInput.style.display = "block"; // Tampilkan input keterangan
        } else {
            keteranganInput.style.display = "none"; // Sembunyikan input keterangan
        }
    }

    const jumlahUangInput = document.getElementById('amount');

    jumlahUangInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, ''); // Menghapus karakter non-digit
        let formatted = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(value);

        e.target.value = formatted.replace("Rp", "Rp ");
    });

    jumlahUangInput.addEventListener('focus', function(e) {
        // Menghapus format saat fokus, sehingga bisa diubah
        e.target.value = e.target.value.replace(/\D/g, '');
    });

    jumlahUangInput.addEventListener('blur', function(e) {
        // Memformat kembali ketika input kehilangan fokus
        let value = e.target.value.replace(/\D/g, '');
        e.target.value = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(value).replace("Rp", "Rp ");
    });
</script>

</html>
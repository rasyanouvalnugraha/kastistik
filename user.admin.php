<?php
session_start();
if ($_SESSION['role'] != '1') {
    header('location: index.php');
    exit();
}

include 'connection/database.php';

// Ambil data pengguna
$getuser = mysqli_query($db, "SELECT * FROM users WHERE role = '2'");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DASHBOARD ADMIN</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../kastistik/css/font.css">
    <link rel="stylesheet" href="../kastistik/css/navbar.css">
</head>

<body class="bg-gray-100">
    <div class="flex">
        <section class="relative">
            <nav class="navbar h-screen mr-5">
                <?php include "layout/navbar.php"; ?>
            </nav>
        </section>  
        <section class="flex-1">
            <div class="text-lg font-mulish-extend w-full p-5 justify-between flex shadow-md navbar">
                <h1>User</h1>
                <?php print $_SESSION['username']; ?>
            </div>
            <!-- <h1 class="font-mulish text-center mt-4 p-2 text-xl">Data yang sudah bayar</h1> -->
            <section class="flex p-6 mx-4 font-mulish-extend">
                <h1 class="text-3xl mr-2 items-center justify-center flex">2024</h1>
                <img src="../kastistik/asset/Calendar.png" alt="" class="bg-gradient p-2 rounded-lg text-white">
            </section>  
            <section class="flex-1">
                <section class="flex overflow-x-auto p-2">
                    <table class="flex mb-2 border-2 border-purple-500 font-mulish mx-auto">
                        <tbody class="bg-white divide-y divide-gray-200 p-4 rounded-lg">
                            <?php
                            while ($row = mysqli_fetch_array($getuser)) {
                                $premi = (float)$row['premi']; // Ambil premi dari database
                                $pemasukan_result = mysqli_query($db, "SELECT SUM(amount) AS total_pemasukan FROM transactions WHERE id_user = '{$row['id']}' AND type = '1' AND approve = '1'");
                                $pemasukan_row = mysqli_fetch_assoc($pemasukan_result);
                                $total_pemasukan = (float)$pemasukan_row['total_pemasukan'];

                                echo "<tr class='py-6'>";
                                echo "<td class='px-4 py-2'>" . htmlspecialchars($row['fullname']) . "</td>";

                                // Hitung status untuk setiap bulan
                                for ($bulan = 1; $bulan <= 12; $bulan++) {
                                    $status = '';
                                    // Ganti logika ini sesuai dengan kriteria yang Anda inginkan
                                    if ($total_pemasukan >= $bulan * $premi) {
                                        $status = 'acc text-white py-4 px-6 text-center'; // Bulan sudah bayar
                                    } else {
                                        $status = 'dec text-black py-4 px-6 text-center'; // Bulan belum bayar
                                    }
                                    echo "<td class='text-center $status'>" . $bulan . "</td>"; // Ganti dengan angka bulan
                                }
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </section>
            </section>
        </section>
    </div>
</body>

<style>
    .acc {
        background-color: #13F566;
    }

    .dec {
        background: #B0B0B0;
    }
</style>

</html>
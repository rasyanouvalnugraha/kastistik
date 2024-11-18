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
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../kastistik/css/font.css">
    <link rel="stylesheet" href="../kastistik/css/navbar.css">
    <link rel="icon" href="asset/BPS.png" type="image/x-icon">
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

            <section class="flex p-4 mt-4 mx-4 font-mulish-extend gap-2 ">
                <div class="flex">
                    <h1 class="text-3xl mr-2 items-center justify-center flex" id="year"></h1>
                    <img src="asset/Calendar.png" alt="" class="bg-gradient h-12 p-2 rounded-lg text-white">
                </div>
                <a href="manage.user.admin.php" class="bg-gradient p-2 rounded-lg">
                    <img src="asset/Add User Male.svg" alt="" class="ml-1 h-8 ">
                </a>
            </section>

            <section class="flex-1 mx-6 pb-4">
                <section class="overflow-x-auto p-2">
                    <table class="border-2 border-purple-500 font-mulish mx-auto w-full text-center rounded-lg overflow-hidden max-h-96">
                        <!-- Header Bulan -->
                        <thead class="bg-gradient text-white">
                            <tr>
                                <th class="px-4 py-2">#</th>
                                <th class="px-4 py-2">Nama</th>
                                <?php
                                $bulanArray = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                                foreach ($bulanArray as $bulan) {
                                    echo "<th class='px-4 py-2'>$bulan</th>";
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 p-4">
                            <?php
                            $no = 1;
                            while ($row = mysqli_fetch_array($getuser)) {
                                $premi = (float) $row['premi'];
                                $pemasukan_result = mysqli_query($db, "SELECT SUM(amount) AS total_pemasukan FROM transactions WHERE id_user = '{$row['id']}' AND type = '1' AND approve = '1'");
                                $pemasukan_row = mysqli_fetch_assoc($pemasukan_result);
                                $total_pemasukan = (float) $pemasukan_row['total_pemasukan'];

                                echo "<tr>";
                                echo "<td class='px-4 py-2'>$no</td>";
                                echo "<td class='px-4 py-2'>" . htmlspecialchars($row['fullname']) . "</td>";

                                // Status pembayaran per bulan
                                $sisa = $total_pemasukan;
                                for ($bulan = 1; $bulan <= 12; $bulan++) {
                                    if ($sisa >= $premi) {
                                        echo "<td class='acc text-white font-mulish-extend py-4 px-6 bg-green-500'>✔</td>"; // Sudah bayar
                                        $sisa -= $premi;
                                    } else {
                                        echo "<td class='dec text-white font-mulish-extend py-4 px-6 bg-red-500'>✖</td>"; // Belum bayar
                                    }
                                }
                                echo "</tr>";
                                $no++; // Increment nomor urut
                            }
                            ?>
                        </tbody>
                    </table>
                </section>
            </section>


        </section>
    </div>

    <script>
        const tahun = new Date().getFullYear();
        document.getElementById('year').innerHTML = tahun;
    </script>
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
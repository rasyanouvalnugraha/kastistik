<?php
session_start();
if ($_SESSION['role'] != '1') {
    header('location: index.php');
    exit();
}

include 'connection/database.php';

// Ambil tahun dari input (default ke tahun sekarang jika kosong)
$tahun = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

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
            <nav class="hidden sm:flex navbar h-screen ">
                <?php include "layout/navbar.php"; ?>
            </nav>
        </section>

        <section class="flex-1">
            <!-- Navbar atas -->
            <div class="flex">
                <div class="flex sm:hidden shadow-md">
                    <?php require "layout/responnavbar.php"; ?>
                </div>
                <div class="sm:text-lg font-mulish-extend w-full py-4 px-3 sm:p-5 justify-between flex shadow-md navbar">
                    <h1>User</h1>
                    <?php print $_SESSION['username']; ?>
                </div>
            </div>

            <!-- Form untuk memilih tahun -->
            <section class="sm:flex sm:px-6 sm:py-2 sm:mx-4 font-mulish-extend sm:gap-2 sm:bg-white sm:m-2 sm:rounded-lg sm:shadow-xl">
                <form method="GET" class="flex items-center sm:gap-4 gap-2 mx-4 sm:mx-0 mt-2 sm:mt-0">
                    <h1 class="sm:text-xl text-xs">Data user yang sudah bayar perbulan dalam 1 tahun</h1>
                    <img src="asset/Calendar.png" alt="Calendar" class="bg-gradient h-12 p-2 rounded-lg text-white">
                    <input type="number" id="year" name="year" min="1990" max="2100" value="<?php echo $tahun; ?>"
                        class="border border-gray-300 rounded-md px-2 py-3" required>
                    <button type="submit" class="bg-gradient px-2 py-3 rounded-lg font-mulish-extend text-white">Tampilkan</button>
                </form>
                <a href="manage.user.admin.php" class="bg-gradient sm:p-2 rounded-lg p-1 m-2 flex justify-center">
                    <img src="asset/Add User Male.svg" alt="" class="ml-1 h-8 ">
                    <h1 class="sm:hidden flex m-1 text-white">Tambah user</h1>
                </a>
            </section>

            <!-- Tabel untuk Mobile -->
            <div class="sm:hidden overflow-x-auto w-screen p-4">
                <table class="table-auto border-collapse border border-gray-300">
                    <thead class="bg-gray-200">
                        <tr class="rounded-lg text-white text-xs bg-gradient font-mulish-extend">
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
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php
                        $no = 1;
                        while ($row = mysqli_fetch_array($getuser)) {
                            $premi = (float)$row['premi'];
                            $pemasukan_result = mysqli_query($db, "SELECT SUM(amount) AS total_pemasukan 
                                FROM transactions 
                                WHERE id_user = '{$row['id']}' AND type = '1' AND approve = '1' 
                                AND YEAR(date) = $tahun");
                            $pemasukan_row = mysqli_fetch_assoc($pemasukan_result);
                            $total_pemasukan = (float)$pemasukan_row['total_pemasukan'];

                            echo "<tr class='font-mulish text-xs'>";
                            echo "<td class='px-2'>$no</td>";
                            echo "<td class='p-2'>" . htmlspecialchars($row['fullname']) . "</td>";

                            // Status pembayaran per bulan
                            $sisa = $total_pemasukan;
                            $sudahKurang = false;

                            for ($bulan = 1; $bulan <= 12; $bulan++) {
                                if ($sisa >= $premi) {
                                    echo "<td class='acc text-white text-center'>✔</td>";
                                    $sisa -= $premi;
                                } else {
                                    if ($sisa > 0 && !$sudahKurang) {
                                        $sisaPembayaran = $premi - $sisa;
                                        echo "<td class='dec text-white text-center'>Sisa<br>Rp " . number_format($sisaPembayaran, 0, ',', '.') . "</td>";
                                        $sisa = 0;
                                        $sudahKurang = true;
                                    } else {
                                        echo "<td class='dec text-white text-center'>✖</td>";
                                    }
                                }
                            }
                            echo "</tr>";
                            $no++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Tabel untuk Desktop -->
            <section class="hidden sm:block overflow-x-auto sm:mx-6 sm:p-4 sm:bg-white sm:rounded-md sm:shadow-lg">
                <table class="table-auto w-full border-collapse border border-gray-300 text-center">
                    <thead class="bg-gradient text-white">
                        <tr>
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">Nama</th>
                            <?php
                            foreach ($bulanArray as $bulan) {
                                echo "<th class='px-4 py-2'>$bulan</th>";
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        <?php
                        $no = 1;
                        mysqli_data_seek($getuser, 0); // Reset pointer ke awal
                        while ($row = mysqli_fetch_array($getuser)) {
                            $premi = (float)$row['premi'];
                            $pemasukan_result = mysqli_query($db, "SELECT SUM(amount) AS total_pemasukan 
                                FROM transactions 
                                WHERE id_user = '{$row['id']}' AND type = '1' AND approve = '1' 
                                AND YEAR(date) = $tahun");
                            $pemasukan_row = mysqli_fetch_assoc($pemasukan_result);
                            $total_pemasukan = (float)$pemasukan_row['total_pemasukan'];

                            echo "<tr class='font-mulish'>";
                            echo "<td class='px-2'>$no</td>";
                            echo "<td class='p-2'>" . htmlspecialchars($row['fullname']) . "</td>";

                            $sisa = $total_pemasukan;
                            $sudahKurang = false;

                            for ($bulan = 1; $bulan <= 12; $bulan++) {
                                if ($sisa >= $premi) {
                                    echo "<td class='acc text-white'>✔</td>";
                                    $sisa -= $premi;
                                } else {
                                    if ($sisa > 0 && !$sudahKurang) {
                                        $sisaPembayaran = $premi - $sisa;
                                        echo "<td class='dec text-white'>Sisa<br>Rp " . number_format($sisaPembayaran, 0, ',', '.') . "</td>";
                                        $sisa = 0;
                                        $sudahKurang = true;
                                    } else {
                                        echo "<td class='dec text-white'>✖</td>";
                                    }
                                }
                            }
                            echo "</tr>";
                            $no++;
                        }
                        ?>
                    </tbody>
                </table>
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
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
            <nav class="navbar h-screen mr-5">
                <?php include "layout/navbar.php"; ?>
            </nav>
        </section>

        <section class="flex-1">
            <div class="text-lg font-mulish-extend w-full p-5 justify-between flex shadow-md navbar">
                <h1>User</h1>
                <?php print $_SESSION['username']; ?>
            </div>

            <!-- Form untuk memilih tahun -->
            <section class="flex px-6 py-2 mt-4 mx-4 font-mulish-extend gap-2">
                <!-- form untuk memilih data sesuai tahun -->
                <form method="GET" class="flex items-center gap-4">
                    <img src="asset/Calendar.png" alt="Calendar" class="bg-gradient h-12 p-2 rounded-lg text-white">
                    <input type="number" id="year" name="year" min="1990" max="2100" value="<?php echo $tahun; ?>"
                        class="border border-gray-300 rounded-md px-2 py-3" required>
                    <button type="submit" class="bg-gradient px-2 py-3 rounded-lg font-mulish-extend text-white">Tampilkan</button>
                </form>
                <a href="manage.user.admin.php" class="bg-gradient p-2 rounded-lg">
                    <img src="asset/Add User Male.svg" alt="" class="ml-1 h-8 ">
                </a>
            </section>

            <!-- Tabel Data -->
            <section class="flex-1 pb-6 mx-6 px-4">
                <section class="overflow-x-auto p-2">
                    <table class="border-2 border-purple-500 font-mulish mx-auto w-full text-center rounded-lg overflow-hidden max-h">
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
                                $premi = (float)$row['premi']; // Jumlah premi per bulan
                                $pemasukan_result = mysqli_query($db, "SELECT SUM(amount) AS total_pemasukan 
                                    FROM transactions 
                                    WHERE id_user = '{$row['id']}' AND type = '1' AND approve = '1' 
                                    AND YEAR(date) = $tahun");
                                $pemasukan_row = mysqli_fetch_assoc($pemasukan_result);
                                $total_pemasukan = (float)$pemasukan_row['total_pemasukan'];

                                echo "<tr>";
                                echo "<td class='px-2'>$no</td>";
                                echo "<td class='p-2'>" . htmlspecialchars($row['fullname']) . "</td>";

                                // Status pembayaran per bulan
                                $sisa = $total_pemasukan; // Total pemasukan pengguna
                                $sudahKurang = false; // Flag untuk mendeteksi jika sudah kekurangan

                                for ($bulan = 1; $bulan <= 12; $bulan++) {
                                    if ($sisa >= $premi) {
                                        echo "<td class='acc text-white font-mulish-extend bg-green-500'>✔</td>"; // Sudah bayar
                                        $sisa -= $premi; // Kurangi sisa dengan premi
                                    } else {
                                        if ($sisa > 0 && !$sudahKurang) {
                                            // Tampilkan nominal sisa pembayaran jika belum lunas
                                            $sisaPembayaran = $premi - $sisa;
                                            echo "<td class='dec text-white font-mulish-extend'> Sisa <br>
                                                <span class='text-md font-mulish-extend'>Rp " . number_format($sisaPembayaran, 0, ',', '.') . "</span>
                                                </td>";
                                            $sisa = 0; // Sisa habis karena sudah dihitung
                                            $sudahKurang = true; // Tandai bahwa nominal sudah ditampilkan
                                        } else {
                                            echo "<td class='dec text-white font-mulish-extend'>✖</td>"; // Tampilkan tanda X
                                        }
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
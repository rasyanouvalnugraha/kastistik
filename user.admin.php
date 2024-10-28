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
</head>
<body class="bg-gray-100">
    <div class="flex">
        <section class="relative">
            <nav class="navbar h-screen">
                <?php include "layout/navbar.php"; ?>
            </nav>
        </section>
        <section class="flex-1">
            <div class="text-lg font-mulish-extend w-full p-5 justify-between flex shadow-md navbar">
                <h1>User</h1>
                <?php print $_SESSION['fullname']; ?>
            </div>
            <section class="flex-1">
                <section class="flex overflow-x-auto">
                    <table class="divide-gray-200 mx-auto">
                        <thead>
                            <tr>
                                <th class="px-6 py-4">Nama</th>
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <th class="px-6 py-4">Bulan <?php echo $i; ?></th>
                                <?php endfor; ?>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            while ($row = mysqli_fetch_array($getuser)) {
                                $premi = (float)$row['premi']; // Ambil premi dari database
                                $pemasukan_result = mysqli_query($db, "SELECT SUM(amount) AS total_pemasukan FROM transactions WHERE id_user = '{$row['id']}' AND type = '1' AND approve = '1'");
                                $pemasukan_row = mysqli_fetch_assoc($pemasukan_result);
                                $total_pemasukan = (float)$pemasukan_row['total_pemasukan'];
                                
                                echo "<tr>";
                                echo "<td class='wrap'>" . htmlspecialchars($row['fullname']) . "</td>";
                                
                                // Hitung status untuk setiap bulan
                                for ($bulan = 1; $bulan <= 12; $bulan++) {
                                    $status = '';
                                    if ($total_pemasukan >= $bulan * $premi) {
                                        $status = 'bg-green-500 text-white'; // Bulan dapat bayaran
                                    } else {
                                        $status = 'bg-gray-200 black'; // Bulan tidak dapat bayaran
                                    }
                                    echo "<td class='px-6 py-4 whitespace-nowrap $status'>" . $bulan . "</td>";
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
</html>

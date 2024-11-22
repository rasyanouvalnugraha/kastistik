<?php
session_start();

include "connection/database.php";

if ($_SESSION['role'] != '1') {
    header('location: index.php');
    exit();
}

// Query untuk menghitung pemasukan dan pengeluaran per bulan di tahun 2024
$querySaldoBulanan = "
    SELECT 
    DATE_FORMAT(date, '%m') AS bulan, 
    SUM(CASE WHEN type = 1 THEN amount ELSE 0 END) AS pemasukan,
    SUM(CASE WHEN type = 3 AND approve = 1 THEN amount ELSE 0 END) AS pengeluaran
    FROM transactions
    WHERE YEAR(date) = YEAR(CURDATE())
    GROUP BY bulan
    ORDER BY bulan;
";

$result = $db->query($querySaldoBulanan);
$saldoBulanan = [];

// Inisialisasi array pemasukan, pengeluaran, dan saldo dengan 0
$pemasukanArray = array_fill(0, 12, 0);
$pengeluaranArray = array_fill(0, 12, 0);
$saldoArray = array_fill(0, 12, 0);

while ($row = $result->fetch_assoc()) {
    $bulan = $row['bulan'];
    $pemasukan = $row['pemasukan'];
    $pengeluaran = $row['pengeluaran'];
    $saldo = $pemasukan - $pengeluaran;

    $saldoBulanan[] = [
        'bulan' => $bulan,
        'pemasukan' => $pemasukan,
        'pengeluaran' => $pengeluaran,
        'saldo' => $saldo
    ];

    // Mengupdate array sesuai dengan bulan yang ditemukan
    $bulanIndex = intval($bulan) - 1; // Mengonversi bulan ke indeks array
    $pemasukanArray[$bulanIndex] = $pemasukan;
    $pengeluaranArray[$bulanIndex] = $pengeluaran;
    $saldoArray[$bulanIndex] = $saldo;
}

$pemasukanJson = json_encode($pemasukanArray);
$pengeluaranJson = json_encode($pengeluaranArray);
$saldoJson = json_encode($saldoArray);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title class="font-mulish">Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/background.css">
    <link rel="stylesheet" href="card.css">
    <link rel="stylesheet" href="css/navbar.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" href="asset/BPS.png" type="image/x-icon">
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
                <h1>Dashboard</h1>
                <h1><?php print $_SESSION['username']; ?></h1>
            </div>
            <?php include 'layout/card.php' ?>
            <section class="flex-1 ml-2">
                <section class="border-3">
                    <div class="flex">
                        <h1 class="mx-6 text-xl font-mulish-extend mr-4" id="tahun">Saldo :</h1>
                        <p id="year" class="text-xl font-mulish-extend"></p>
                    </div>

                    <script>
                        const getYear = new Date();
                        const year = getYear.getFullYear();
                        document.getElementById('year').innerHTML = year;
                    </script>

                    <div class="flex h-80 mx-auto justify-center">
                        <canvas id="saldoChart"></canvas>
                    </div>
                </section>
            </section>
        </section>
    </div>
</body>

<style>
    .button {
        background-color: #7D46FD;
    }
</style>

<script>
    var ctx = document.getElementById('saldoChart').getContext('2d');
    var saldoChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']); ?>, // Bulan
            datasets: [{
                    label: 'Pemasukan',
                    data: <?php echo $pemasukanJson; ?>, // Data pemasukan
                    backgroundColor: '#4CAF50', // Warna hijau untuk pemasukan
                    borderColor: '#4CAF50',
                    borderWidth: 1
                },
                {
                    label: 'Pengeluaran',
                    data: <?php echo $pengeluaranJson; ?>, // Data pengeluaran
                    backgroundColor: '#F44336', // Warna merah untuk pengeluaran
                    borderColor: '#F44336',
                    borderWidth: 1
                },
                {
                    label: 'Saldo',
                    data: <?php echo $saldoJson; ?>, // Data saldo
                    backgroundColor: '#7D46FD', // Warna ungu untuk saldo
                    borderColor: '#7D46FD',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Bulan'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Jumlah (dalam rupiah)'
                    },
                    beginAtZero: true
                }
            }
        }
    });
</script>

</html>
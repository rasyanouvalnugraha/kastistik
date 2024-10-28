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
    WHERE YEAR(date) = 2024
    GROUP BY bulan
    ORDER BY bulan;
";

$result = $db->query($querySaldoBulanan);
$saldoBulanan = [];

while ($row = $result->fetch_assoc()) {
    $bulan = $row['bulan'];
    $pemasukan = $row['pemasukan'];
    $pengeluaran = $row['pengeluaran'];
    $saldo = $pemasukan - $pengeluaran;

    $saldoBulanan[] = [
        'bulan' => $bulan,
        'saldo' => $saldo
    ];
}


$bulanArray = [
    'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
];

$saldoArray = array_fill(0, 12, 0); // Inisialisasi saldo dengan 0

foreach ($saldoBulanan as $data) {
    $bulanIndex = intval($data['bulan']) - 1; // Mengonversi bulan ke indeks array
    $saldoArray[$bulanIndex] = $data['saldo']; // Mengupdate saldo pada bulan yang sesuai
}

$bulanJson = json_encode($bulanArray);
$saldoJson = json_encode($saldoArray);
$year = 2024;

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
                <h1><?php print $_SESSION['fullname']; ?></h1>
            </div>
            <section class="flex-1 ml-2">
                <?php include 'layout/card.php' ?>
                <section class="border-3">
                    <h1 class="mx-6 text-xl font-mulish-extend">Saldo <?php print $year ?></h1>

                    <section class="flex justify-around mx-auto p-3">
                        <div class="max-h-72 flex items-center justify-center flex-1">
                            <canvas id="saldoChart"></canvas>
                        </div>
                        <div class="flex-col flex  px-5 justify-evenly "> 
                            <a href="pemasukan.php" target="_blank" rel="noopener noreffer" class="button rounded-lg font-mulish text-white py-2 px-4 text-center">CETAK DATA PEMASUKAN</a>
                            <a href="pengeluaran.php" target="_blank" rel="noopener noreffer" class="button rounded-lg font-mulish text-white py-2 px-4 text-center">CETAK DATA PENGELUARAN</a>
                        </div>
                    </section>
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
        type: 'bar', // 
        data: {
            labels: <?php echo $bulanJson; ?>, // Bulan
            datasets: [{
                label: 'Saldo',
                data: <?php echo $saldoJson; ?>, // Data saldo
                borderColor: '#7D46FD',
                backgroundColor: '#7D46FD',
                fill: true,
                borderWidth: 2
            }]
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
                        text: 'Saldo (dalam rupiah)'
                        
                    }
                }
            }
        }
    });
</script>


</html>
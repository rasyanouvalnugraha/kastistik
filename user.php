<?php
session_start();
include "../kastistik/connection/database.php";

// Redirect jika role bukan 2
if ($_SESSION['role'] != '2') {
    header('location: index.php');
    exit();
}

// Default tahun ke tahun saat ini
$tahun = isset($_GET['tahun']) ? intval($_GET['tahun']) : date('Y');

// Query untuk menghitung pemasukan dan pengeluaran berdasarkan tahun yang dipilih
$querySaldoBulanan = "
    SELECT 
        LPAD(MONTH(date), 2, '0') AS bulan, 
        SUM(CASE WHEN type = 1 THEN amount ELSE 0 END) AS pemasukan,
        SUM(CASE WHEN type = 3 AND approve = 1 THEN amount ELSE 0 END) AS pengeluaran
    FROM transactions
    WHERE YEAR(date) = $tahun
    GROUP BY bulan
    ORDER BY bulan;
";

$result = $db->query($querySaldoBulanan);

// Inisialisasi array 12 bulan dengan nilai 0
$pemasukanArray = array_fill(0, 12, 0);
$pengeluaranArray = array_fill(0, 12, 0);
$saldoArray = array_fill(0, 12, 0);

// Proses hasil query
while ($row = $result->fetch_assoc()) {
    $bulanIndex = intval($row['bulan']) - 1; // Konversi bulan jadi index array (0-11)
    $pemasukanArray[$bulanIndex] = intval($row['pemasukan'] ?? 0);
    $pengeluaranArray[$bulanIndex] = intval($row['pengeluaran'] ?? 0);
    $saldoArray[$bulanIndex] = $pemasukanArray[$bulanIndex] - $pengeluaranArray[$bulanIndex];
}

// Konversi array ke JSON untuk Chart.js
$pemasukanJson = json_encode($pemasukanArray);
$pengeluaranJson = json_encode($pengeluaranArray);
$saldoJson = json_encode($saldoArray);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DASHBOARD</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/background.css">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="icon" href="asset/BPS.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <!-- SIDEBAR -->
        <nav class="h-full hidden sm:flex">
            <div class="navbar">
                <?php include "layout.user/navbar.user.php"; ?>
            </div>
        </nav>

        <!-- MAIN CONTENT -->
        <section class="flex-1">

            <div class="flex text-lg font-mulish-extend w-full sm:p-5 shadow-md">
                <button id="mobileMenuToggle" class="sm:hidden flex px-4 py-2 bg-gradient">
                    <img src="asset/Content.png" alt="Menu" class="h-6 w-6">
                </button>
                <div class="w-full flex justify-between items-center px-2 sm:px-0 sm:mt-0">
                    <h1 class="text-sm sm:text-lg text-black">Dashboard</h1>
                    <h1 class="text-sm sm:text-lg text-black">
                        <?php print $_SESSION['fullname']; ?>
                    </h1>
                </div>
            </div>

            <section id="mobileMenu" class="hidden fixed top-0 left-0 w-full h-screen bg-gradient bg-opacity-90 z-20">
                <div class="bg-gradient h-full p-5 text-white">
                    <button id="closeMobileMenu" class="text-xl font-bold">X</button>
                    <ul class="mt-5 space-y-4 font-mulish">
                        <li><a href="user.php" class="block">Dashboard</a></li>
                        <hr>
                        <li><a href="data.pemasukan.user.php" class="block">Pemasukan</a></li>
                        <li><a href="data.pengeluaran.user.php" class="block">Pengeluaran</a></li>
                        <hr>
                        <li><a href="request.user.php" class="block">Request</a></li>
                        <li><a href="logout.php" class="block">Logout</a></li>
                    </ul>
                </div>
            </section>

            <div>
                <?php include "layout/card.php" ?>
            </div>

            <section class="flex-1">
                <section class="flex items-center">
                    <h1 class="mx-6 text-xl font-mulish-extend mr-4">Saldo Tahun :</h1>
                    <form id="tahunForm" action="" method="GET" class="space-x-2">
                        <input type="number" name="tahun" id="tahunInput" class="w-16 border rounded p-1" placeholder="Year"
                            value="<?php echo $tahun; ?>" min="2000" max="<?php echo date('Y'); ?>" />
                        <button class="bg-gradient font-mulish text-white px-2 py-1 rounded-md" type="submit">Tampilkan</button>
                    </form>
                </section>
                <div class="xl:bg-white xl:h-72 xl:my-4 xl:mx-8 xl:p-2 xl:rounded-md xl:shadow-lg xl:w-auto w-screen h-72 p-2">
                    <canvas id="saldoChart" class=""></canvas>
                </div>
            </section>
        </section>
    </div>

    <!-- SCRIPT -->
    <script>
        // MOBILE MENU TOGGLE
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const closeMobileMenu = document.getElementById('closeMobileMenu');
        const mobileMenu = document.getElementById('mobileMenu');

        mobileMenuToggle.addEventListener('click', () => {
            mobileMenu.classList.remove('hidden');
        });
        closeMobileMenu.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
        });

        // CHART.JS CONFIG
        var ctx = document.getElementById('saldoChart').getContext('2d');
        var saldoChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']); ?>,
                datasets: [{
                        label: 'Pemasukan',
                        data: <?php echo $pemasukanJson; ?>,
                        backgroundColor: '#4CAF50',
                    },
                    {
                        label: 'Pengeluaran',
                        data: <?php echo $pengeluaranJson; ?>,
                        backgroundColor: '#F44336',
                    },
                    {
                        label: 'Saldo',
                        data: <?php echo $saldoJson; ?>,
                        backgroundColor: '#7D46FD',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Membiarkan chart mengisi area container
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Bulan'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah (dalam rupiah)'
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>
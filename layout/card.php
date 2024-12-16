<?php
include "connection/database.php";


$sql = "SELECT COUNT(*) as total_users FROM users WHERE role = '2'";
$result = $db->query($sql);
// Mendapatkan hasil sebagai array asosiatif
$row = $result->fetch_assoc();
$total_users = $row['total_users'];


$current_year = date('Y');
// Menghitung pemasukan untuk tahun berjalan
$querypemasukan = "SELECT SUM(amount) AS pemasukan 
                   FROM `transactions` 
                   WHERE type = 1 AND approve = 1 AND YEAR(date) = $current_year;";
$result1 = $db->query($querypemasukan);
$data = $result1->fetch_assoc();
$pemasukan = $data['pemasukan'] ?? 0; // Jika null, set ke 0

// Menghitung pengeluaran untuk tahun berjalan
$querypengeluaran = "SELECT SUM(amount) AS pengeluaran 
                     FROM `transactions` 
                     WHERE type = 3 AND approve = 1 AND YEAR(date) = $current_year;";
$result1 = $db->query($querypengeluaran);
$data = $result1->fetch_assoc();
$pengeluaran = $data['pengeluaran'] ?? 0; // Jika null, set ke 0

// Menghitung saldo untuk tahun berjalan
$sisa = $pemasukan - $pengeluaran;

?>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link rel="stylesheet" href="card.css">
<link rel="stylesheet" href="css/background.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/font.css">

<body>
    <!-- LAYOUT CARD -->
    <ul class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4">

        <!-- CARD USER -->
        <li class="bg-gradient-user p-3 sm:p-4 rounded-lg text-white font-mulish-user flex items-center shadow-lg">
            <img src="asset/People.png" alt="User Icon" class="hidden sm:block w-6 h-6 sm:w-12 sm:h-12 mr-4">
            <div class="flex space-x-1 sm:space-x-0 sm:flex-col">
                <label class="text-sm md:text-2xl">User</label>
                <label class="text-sm sm:hidden">:</label>

                <label class="text-sm md:text-xl"><?php print $total_users; ?></label>
            </div>
        </li>

        <!-- CARD PEMASUKAN -->
        <li class="bg-gradient-pemasukan p-3 sm:p-4 rounded-lg text-white font-mulish-user flex items-center shadow-lg">
            <img src="asset/Increase.png" alt="Pemasukan Icon" class="hidden sm:block w-6 h-6 sm:w-12 sm:h-12 mr-4">
            <div class="flex space-x-1 sm:space-y-0 flex-col">
                <div>
                    <label class="text-sm md:text-2xl">Pemasukan</label>
                    <label class="text-sm sm:hidden">:</label>
                </div>
                <div class="flex">
                    <label class="text-sm md:text-xl">
                        <?php
                        if ($pemasukan > 0) {
                            echo "Rp " . number_format($pemasukan, 0, ',', '.');
                        } else {
                            echo "<span class='text-xl'>Tidak Ada</span>";
                        }
                        ?>
                    </label>
                </div>
                <label class="hidden md:text-xl md:block font-mulish-ket">Tahun Ini</label>
            </div>
        </li>

        <!-- CARD PENGELUARAN -->
        <li class="bg-gradient-pengeluaran p-3 sm:p-4 rounded-lg text-white font-mulish-user flex items-center shadow-lg">
            <img src="asset/Increase.png" alt="Pemasukan Icon" class="hidden sm:block w-6 h-6 sm:w-12 sm:h-12 mr-4">
            <div class="flex space-x-1 sm:space-y-0 flex-col">
                <div>
                    <label class="text-sm md:text-2xl">Pengeluaran</label>
                    <label class="text-sm sm:hidden">:</label>
                </div>
                <div class="flex">
                    <label class="text-sm md:text-xl">
                        <?php
                        if ($pengeluaran > 0) {
                            echo "Rp " . number_format($pengeluaran, 0, ',', '.');
                        } else {
                            echo "<span class='text-xl'>Tidak Ada</span>";
                        }
                        ?>
                    </label>
                </div>
                <label class="hidden md:text-xl md:block font-mulish-ket">Tahun Ini</label>
            </div>
        </li>

        <!-- CARD SALDO -->
        <li class="bg-gradient-saldo p-3 sm:p-4 rounded-lg text-white font-mulish-user flex items-center shadow-lg">
            <img src="asset/Coin Wallet.png" alt="Sisa Icon" class="hidden sm:block w-6 h-6 sm:w-12 sm:h-12 mr-4">
            <div class="flex space-x-1 sm:space-y-0 sm:flex-col">
                <label class="text-sm md:text-2xl">Saldo</label>
                <label class="text-sm sm:hidden">:</label>
                <label class="text-sm md:text-xl">
                    Rp <?php
                        if ($sisa < 0) {
                            echo "0";
                        } else {
                            print number_format($sisa, 0, ',', '.');
                        }
                        ?>
                </label>
                <label class="text-sm md:text-xl font-mulish-ket"></label>
            </div>
        </li>
    </ul>
</body>


<style>
    /* URL FONT MULISH */
    @import url('https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap');

    .bg-gradient-user {
        background: linear-gradient(135deg, #00EBF8, #0E09E3);
    }

    .bg-gradient-pemasukan {
        background: linear-gradient(135deg, #00FE54, #006521);
    }

    .bg-gradient-pengeluaran {
        background: linear-gradient(135deg, #FFD6A4, #BF1F60);
    }

    .bg-gradient-saldo {
        background: linear-gradient(to right, #ff9800, #ffb74d);
    }
</style>
<?php
include "connection/database.php";

$sql = "SELECT COUNT(*) as total_users FROM users WHERE role = 'user'";

$result = $db->query($sql);

// Mendapatkan hasil sebagai array asosiatif
$row = $result->fetch_assoc();
// Jumlah total user
$total_users = $row['total_users'];

$pemasukan = 500000;
$pengeluaran = 200000;
$sisa = 300000;
?>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link rel="stylesheet" href="card.css">
<link rel="stylesheet" href="css/background.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/font.css">

<body>
    <ul class="flex flex-col flex-wrap sm:flex-row sm:justify-center sm:space-x-6 space-y-6 sm:space-y-0 p-4">
        <li class="bg-gradient-user p-4 rounded-lg text-white font-mulish-user flex-1 flex items-center shadow-lg">
            <img src="People.png" alt="User Icon" class="w-12 h-12 mr-4">
            <div class="flex flex-col">
                <label class="md:text-2xl">User</label>
                <label class="md:text-xl"><?php print $total_users; ?></label>
            </div>
        </li>
        <li class="bg-gradient-pemasukan p-4 rounded-lg text-white font-mulish-user flex-1 flex items-center shadow-lg">
            <img src="Increase.png" alt="Pemasukan Icon" class="w-12 h-12 mr-4">
            <div class="flex flex-col">
                <label class="md:text-2xl">Pemasukan</label>
                <label class="md:text-xl">Rp <?php print number_format($pemasukan, 0, ',', '.'); ?></label>
                <label class="text-lg font-mulish-ket">Bulan Ini</label>
            </div>
        </li>
        <li class="bg-gradient-pengeluaran p-4 rounded-lg text-white font-mulish-user flex-1 flex items-center shadow-lg">
            <img src="Decrease.png" alt="Pengeluaran Icon" class="w-12 h-12 mr-4">
            <div class="flex flex-col">
                <label class="md:text-2xl">Pengeluaran</label>
                <label class="md:text-xl">Rp <?php print number_format($pengeluaran, 0, ',', '.'); ?></label>
                <label class="text-lg font-mulish-ket">Bulan Ini</label>
            </div>
        </li>
        <li class="bg-gradient-saldo p-4 rounded-lg text-white font-mulish-user flex-1 flex items-center shadow-lg">
            <img src="Coin Wallet.png" alt="Sisa Icon" class="w-12 h-12 mr-4">
            <div class="flex flex-col">
                <label class="md:text-2xl">Saldo</label>
                <label class="md:text-xl">Rp <?php print number_format($sisa, 0, ',', '.'); ?></label>
                <label class="text-lg font-mulish-ket"></label>
            </div>
        </li>
    </ul>
</body>

<style>
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
<?php
// Tidak ada PHP dinamis di sini, jadi tidak perlu PHP tags di awal dan akhir file
?>
<!-- Stylesheets -->
<link rel="stylesheet" href="css/background.css">
<link rel="stylesheet" href="css/font.css">

<!-- DESKTOP NAVBAR -->
<section class="hidden bg-gradient px-3 py-2 text-white sm:flex sm:flex-col h-screen">
    <!-- Application Name -->
    <div class="p-2 sm:p-5 text-2xl font-bold flex justify-center font-mulish-extend">Kastistik</div>

    <!-- Navigation Menu -->
    <nav class="font-mulish">
        <ul class="space-y-2">
            <!-- Dashboard -->
            <li class="px-3 py-4 sm:p-5 rounded-xl mx-1">
                <a href="user.php" class="flex items-center space-x-3">
                    <img src="asset/Content.png" alt="" class="w-6 h-6">
                    <span>Dashboard</span>
                </a>
            </li>
            <hr>
            <!-- Data Section -->
            <h1 class="font-mulish flex justify-center items-center pt-5">DATA</h1>
            <li class="px-3 py-4 sm:p-5 rounded-xl mx-1">
                <a href="data.pemasukan.user.php" class="flex items-center space-x-3">
                    <img src="asset/Database Restore.png" alt="" class="w-6 h-6">
                    <span>Pemasukan</span>
                </a>
            </li>
            <li class="px-3 py-4 sm:p-5 rounded-xl mx-1">
                <a href="data.pengeluaran.user.php" class="flex items-center space-x-3">
                    <img src="asset/Database Export.png" alt="" class="w-6 h-6">
                    <span>Pengeluaran</span>
                </a>
            </li>
            <hr>
            <!-- Request -->
            <li class="px-3 py-4 sm:p-5 rounded-xl mx-1">
                <a href="request.user.php" class="flex items-center space-x-3">
                    <img src="asset/Invite.svg" alt="" class="w-6 h-6">
                    <span>Request</span>
                </a>
            </li>
            <!-- Logout -->
            <li class="px-3 py-4 sm:p-5 rounded-xl mx-1">
                <a href="logout.php" class="flex items-center space-x-3">
                    <img src="asset/Logout.svg" alt="" class="w-6 h-6">
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>
</section>

<!-- Navbar Styling -->
<style>
    .font-mulish {
        font-family: "Mulish", sans-serif;
        font-weight: 600;
    }

    .navbar {
        position: sticky;
        top: 0;
        width: 100%;
        z-index: 10;
        background-color: white;
        overflow-y: auto;
    }

    .navbar::-webkit-scrollbar {
        display: none;
    }
</style>
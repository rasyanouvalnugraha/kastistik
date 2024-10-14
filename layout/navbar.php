<?php

?>
<link rel="stylesheet" href="css/background.css">
<link rel="stylesheet" href="css/font.css">

<section class="bg-gradient px-3 py-2 h-full text-white sm:w-56 navbar sticky">
    <div class="p-2 sm:p-5 text-2xl font-bold flex justify-center my-5 font-mulish-extend">Kastistik</div>
    <nav>
        <ul class="gap-5 font-mulish">
            <li class="px-3 py-4 sm:p-5 transition-colors duration-300 ease-in-out hover:bg-blue-700 hover:text-white rounded-xl my-4 mx-1">
                <a href="admin.php" class="flex items-center space-x-3 transition-colors duration-300 ease-in-out hover:text-white">
                    <img src="Content.png" alt="" class="w-6 h-6">
                    <span class="">Dashboard</span>
                </a>
            </li>
            <li class="px-3 py-4 sm:p-5 transition-colors duration-300 ease-in-out hover:bg-blue-700 hover:text-white rounded-xl my-4 mx-1">
                <a href="user.admin.php" class="flex items-center space-x-3 transition-colors duration-300 ease-in-out hover:text-white">
                    <img src="User.png" alt="" class="w-6 h-6">
                    <span class="">User</span>
                </a>
            </li>
            <hr>
            <h1 class="font-mulish flex justify-center items-center pt-5">INPUT DATA</h1>
            <li class="px-3 py-4 sm:p-5 transition-colors duration-300 ease-in-out hover:bg-blue-700 hover:text-white rounded-xl my-4 mx-1">
                <a href="pemasukan.admin.php" class="flex items-center space-x-3 transition-colors duration-300 ease-in-out hover:text-white">
                    <img src="Database Restore.png" alt="" class="w-6 h-6">
                    <span class="">Pemasukan</span>
                </a>
            </li>

            <li class="px-3 py-4 sm:p-5 transition-colors duration-300 ease-in-out hover:bg-blue-700 hover:text-white rounded-xl my-4 mx-1">
                <a href="pengeluaran.admin.php" class="flex items-center space-x-3 transition-colors duration-300 ease-in-out hover:text-white">
                    <img src="Database Export.png" alt="" class="w-6 h-6">
                    <span class="">Pengeluaran</span>
                </a>
            </li>

            <li class="px-3 py-4 sm:p-5 transition-colors duration-300 ease-in-out hover:bg-blue-700 hover:text-white rounded-xl my-4 mx-1">
                <?php include "layout/exit.php" ?>
            </li>
        </ul>
    </nav>
</section>
<style>

    .font-mulish {
        font-family: "Mulish", sans-serif;
        font-weight: 600;
        font-style: normal;
    }
    .navbar {
    position: sticky; /* Atur posisi menjadi sticky */
    top: 0; /* Tetap di atas halaman */
    width: 100%; /* Pastikan navbar memenuhi lebar halaman */
    z-index: 10; /* Pastikan navbar tetap di atas elemen lain */
    background-color: white; /* Atur latar belakang navbar */
}

</style>
<link rel="stylesheet" href="css/background.css">
<link rel="stylesheet" href="css/font.css">

<section class="bg-gradient px-3 py-2 h-full text-white sm:w-56 navbar sticky">

    <!-- NAME OF APPLICATION -->
    <div class="p-2 sm:p-5 text-2xl font-bold flex justify-center font-mulish-extend">Kastistik</div>

    <!-- NAVIGASI SECTION / BAGIAN NAVIGASI -->
    <nav>
        <ul class="font-mulish">
            <li class="px-3 py-4 sm:p-5 transition-colors duration-300 ease-in-out hover:text-white rounded-xl mx-1">
                <a href="admin.php" class="flex items-center space-x-3 transition-colors duration-300 ease-in-out hover:text-white">
                    <img src="asset/Content.png" alt="" class="w-6 h-6">
                    <span class="">Dashboard</span>
                </a>
            </li>
            <li class="px-3 py-4 sm:p-5 transition-colors duration-300 ease-in-out hover:text-white rounded-xl mx-1">
                <a href="user.admin.php" class="flex items-center space-x-3 transition-colors duration-300 ease-in-out hover:text-white">
                    <img src="asset/User.png" alt="" class="w-6 h-6">
                    <span class="">User</span>
                </a>
            </li>
            <hr>

            <hr>

            <!-- NAVIGASI DATA PEMASUKAN -->
            <h1 class="font-mulish flex justify-center items-center pt-5">DATA</h1>
            <li class="px-3 py-4 sm:p-5 transition-colors duration-300 ease-in-out  hover:text-white rounded-xl mx-1">
                <a href="data.pemasukan.admin.php" class="flex items-center space-x-3 transition-colors duration-300 ease-in-out hover:text-white">
                    <img src="asset/Database Restore.png" alt="" class="w-6 h-6">
                    <span class="">Pemasukan</span>
                </a>
            </li>

            <li class="px-3 py-4 sm:p-5 transition-colors duration-300 ease-in-out  hover:text-white rounded-xl mx-1">
                <a href="data.pengeluaran.admin.php" class="flex items-center space-x-3 transition-colors duration-300 ease-in-out hover:text-white">
                    <img src="asset/Database Export.png" alt="" class="w-6 h-6">
                    <span class="">Pengeluaran</span>
                </a>
            </li>

            <hr>

            <hr>

            <!-- NAVIGASI LOG OUT -->
            <li class="px-3 py-4 sm:p-5 transition-colors duration-300 ease-in-out  hover:text-white rounded-xl mx-1">
                <a href="request.admin.php" class="flex items-center space-x-3 transition-colors duration-300 ease-in-out hover:text-white">
                    <img src="asset/Invite.svg" alt="" class="w-6 h-6">
                    <span class="">Request</span>
                </a>
            </li>
            <li class="px-3 py-4 sm:p-5 transition-colors duration-300 ease-in-out  hover:text-white rounded-xl mx-1">
                <a href="history.admin.php" class="flex items-center space-x-3 transition-colors duration-300 ease-in-out hover:text-white">
                    <img src="asset/Urgent Message.svg" alt="" class="w-6 h-6">
                    <span class="">History</span>
                </a>
            </li>
            <li class="px-3 py-4 sm:p-5 transition-colors duration-300 ease-in-out  hover:text-white rounded-xl mx-1">
                <a href="logout.php" class="flex items-center space-x-3 transition-colors duration-300 ease-in-out hover:text-white">
                    <img src="asset/Logout.svg" alt="" class="w-6 h-6">
                    <span class="">Logout</span>
                </a>
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
        position: sticky;
        /* Atur posisi menjadi sticky */
        top: 0;
        /* Tetap di atas halaman */
        width: 100%;
        /* Pastikan navbar memenuhi lebar halaman */
        z-index: 10;
        /* Pastikan navbar tetap di atas elemen lain */
        background-color: white;
        /* Atur latar belakang navbar */
        overflow-y: auto;
    }
    .navbar::-webkit-scrollbar {
        display: none; /* Menyembunyikan scrollbar untuk Webkit browsers */
    }
</style>
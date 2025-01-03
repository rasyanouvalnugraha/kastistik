<link rel="stylesheet" href="css/background.css">
<link rel="stylesheet" href="css/font.css">
<!-- NAVBAR DESKTOP -->


<section class="bg-gradient p-4 h-full text-white navbar flex flex-col">
    <!-- NAME OF APPLICATION -->
    <div class="p-2 sm:p-5 text-2xl font-bold flex justify-center font-mulish-extend">Kastistik</div>

    <!-- NAVIGASI SECTION / BAGIAN NAVIGASI -->
    <nav id="navbarMenu" class="">
        <ul class="font-mulish 2xl:space-y-2">
            <li class="px-3 py-4 sm:p-5 transition-colors duration-300 ease-in-out hover:text-white rounded-xl mx-1">
                <a href="admin.php" class="flex items-center space-x-3">
                    <img src="asset/Content.png" alt="" class="w-6 h-6">
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="px-3 py-4 sm:p-5 transition-colors duration-300 ease-in-out hover:text-white rounded-xl mx-1">
                <a href="user.admin.php" class="flex items-center space-x-3">
                    <img src="asset/User.png" alt="" class="w-6 h-6">
                    <span>User</span>
                </a>
            </li>
            <hr>
            <h1 class="font-mulish flex justify-center items-center pt-5">DATA</h1>
            <li class="px-3 py-4 sm:p-5 transition-colors duration-300 ease-in-out hover:text-white rounded-xl mx-1">
                <a href="data.pemasukan.admin.php" class="flex items-center space-x-3">
                    <img src="asset/Database Restore.png" alt="" class="w-6 h-6">
                    <span>Pemasukan</span>
                </a>
            </li>
            <li class="px-3 py-4 sm:p-5 transition-colors duration-300 ease-in-out hover:text-white rounded-xl mx-1">
                <a href="data.pengeluaran.admin.php" class="flex items-center space-x-3">
                    <img src="asset/Database Export.png" alt="" class="w-6 h-6">
                    <span>Pengeluaran</span>
                </a>
            </li>
            <hr>
            <li class="px-3 py-4 sm:p-5 transition-colors duration-300 ease-in-out hover:text-white rounded-xl mx-1">
                <a href="request.admin.php" class="flex items-center space-x-3">
                    <img src="asset/Invite.svg" alt="" class="w-6 h-6">
                    <span>Request</span>
                </a>
            </li>
            <li class="px-3 py-4 sm:p-5 transition-colors duration-300 ease-in-out hover:text-white rounded-xl mx-1">
                <a href="logout.php" class="flex items-center space-x-3">
                    <img src="asset/Logout.svg" alt="" class="w-6 h-6">
                    <span>Logout</span>
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
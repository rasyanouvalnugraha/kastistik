<link rel="stylesheet" href="css/background.css">
<link rel="stylesheet" href="css/font.css">
<!-- NAVBAR MOBILE -->

<button class="ml-auto flex bg-gradient px-5 items-center justify-center" id="mobileMenuToggle">
    <img src="asset/Content.png" alt="Menu" class="flex object-contain">
</button>

<!-- MOBILE NAVBAR MENU -->
<section id="mobileMenu" class="hidden fixed top-0 left-0 w-full h-full bg-gray-900 bg-opacity-75 z-20">
    <div class="bg-gradient h-full p-5 text-white">
        <button id="closeMobileMenu" class="text-xl font-bold">
            X
        </button>
        <ul class="mt-5 space-y-4 font-mulish">
            <li><a href="admin.php" class="block">Dashboard</a></li>
            <li><a href="user.admin.php" class="block">User</a></li>
            <hr>
            <li><a href="data.pemasukan.admin.php" class="block">Pemasukan</a></li>
            <li><a href="data.pengeluaran.admin.php" class="block">Pengeluaran</a></li>
            <hr>
            <li><a href="request.admin.php" class="block">Request</a></li>
            <li><a href="logout.php" class="block">Logout</a></li>
        </ul>
    </div>
</section>

<script>
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    const closeMobileMenu = document.getElementById('closeMobileMenu');

    mobileMenuToggle.addEventListener('click', () => {
        mobileMenu.classList.remove('hidden');
    });

    closeMobileMenu.addEventListener('click', () => {
        mobileMenu.classList.add('hidden');
    });
</script>
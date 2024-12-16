<button id="mobileMenuToggle" class="sm:hidden flex px-4 py-2 bg-gradient">
    <img src="asset/Content.png" alt="Menu" class="h-6 w-6">
</button>
<!-- MOBILE MENU -->
<section id="mobileMenu" class="hidden fixed top-0 left-0 w-full h-screen bg-gradient bg-opacity-90 z-20">
    <div class="bg-gray-800 h-full p-5 text-white">
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

<script>
    // Ambil elemen tombol toggle, tombol close, dan menu mobile
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const closeMobileMenu = document.getElementById('closeMobileMenu');
    const mobileMenu = document.getElementById('mobileMenu');

    // Fungsi untuk membuka menu
    mobileMenuToggle.addEventListener('click', () => {
        mobileMenu.classList.remove('hidden'); // Hapus kelas hidden
    });

    // Fungsi untuk menutup menu
    closeMobileMenu.addEventListener('click', () => {
        mobileMenu.classList.add('hidden'); // Tambahkan kelas hidden
    });

    // Menutup menu jika area luar menu diklik
    window.addEventListener('click', (e) => {
        if (e.target === mobileMenu) {
            mobileMenu.classList.add('hidden');
        }
    });
</script>
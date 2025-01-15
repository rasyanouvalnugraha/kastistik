    <?php
    session_start();

    include "connection/database.php";
    if ($_SESSION['role'] != '2') {
        header('location: index.php');
        exit();
    }

    // Ambil data tahun, default ke tahun sekarang
    $tahun = isset($_GET['tahun']) ? intval($_GET['tahun']) : date('Y');
    if ($tahun < 1000 || $tahun > intval(date('Y'))) {
        $tahun = date('Y');
    }
    // Fetch paginated data
    $pemasukan = mysqli_query($db, "
        SELECT 
        transactions.date AS tanggal, 
        users.fullname AS nama, 
        transactions.amount AS jumlah, 
        transactions.keterangan AS Keterangan 
    FROM transactions 
    JOIN users ON transactions.id_user = users.id 
    WHERE transactions.type IN (1,2) AND transactions.approve = 1 AND YEAR (transactions.date) = $tahun
    ORDER BY transactions.date DESC, users.fullname DESC, transactions.amount DESC
    ");

    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>DASHBOARD</title>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/background.css">
        <link rel="stylesheet" href="css/card.css">
        <link rel="stylesheet" href="css/font.css">
        <link rel="stylesheet" href="css/navbar.css">
        <link rel="icon" href="asset/BPS.png" type="image/x-icon">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    </head>

    <body class="bg-gray-100">
        <div class="flex">

            <!-- NAVBAR -->
            <section class="relative">
                <nav class="navbar max-h-screen 2xl:mr-5">
                    <?php include "layout.user/navbar.user.php"; ?>
                </nav>
            </section>

            <section class="flex-1">
                <!-- NAVBAR MOBILE -->
                <div class="flex text-lg font-mulish-extend w-full sm:p-5 shadow-md navbar">
                    <button id="mobileMenuToggle" class="sm:hidden flex px-4 py-2 bg-gradient">
                        <img src="asset/Content.png" alt="Menu" class="h-6 w-6">
                    </button>
                    <div class="w-full flex justify-between items-center px-2 sm:px-0 sm:mt-0">
                        <h1 class="text-sm sm:text-lg text-black">Dashboard</h1>
                        <h1 class="text-sm sm:text-lg text-black"><?php print $_SESSION['fullname']; ?></h1>
                    </div>
                </div>

                <!-- MOBILE MENU -->
                <section id="mobileMenu" class="hidden fixed top-0 left-0 w-full h-screen bg-gray-800 bg-opacity-90 z-20">
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

                <div class="flex">
                    <h1 class="sm:text-2xl font-mulish-extend mx-4 my-5">Data Pemasukan</h1>
                    <form action="" method="GET" class="space-x-2 justify-center items-center flex">
                        <input type="number" name="tahun" min="1000" max="<?php echo date('Y'); ?>" value="<?php echo htmlspecialchars($tahun); ?>" class="sm:w-16 border-1 border-gray-500 rounded-md focus:border-teal-500 py-2">
                        <button type="submit" class="sm:px-4 sm:py-2 rounded-lg bg-gradient font-mulish text-white text-xs sm:text-base p-3">Tampilkan</button>
                    </form>
                </div>


                <!-- TABEL DATA PEMASUKAN -->
                <div class="sm:mx-8 sm:px-6 px-2">
                    <table id="dataTable" class="min-w-full rounded-lg shadow-md">
                        <thead>
                            <tr class="bg-gradient navbar text-white">
                                <th class="py-2 px-4 border-b font-mulish sticky top-0 z-10 text-sm sm:text-lg">Nama</th>
                                <th class="py-2 px-4 border-b font-mulish sticky top-0 z-10 text-sm sm:text-lg">Tanggal</th>
                                <th class="py-2 px-4 border-b font-mulish sticky top-0 z-10 text-sm sm:text-lg text-start">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($pemasukan)) : ?>
                                <tr class='hover:bg-gray-300 hover:cursor-pointer'>
                                    <td class='py-2 px-4 text-center font-mulish text-sm sm:text-lg'><?= $row['nama'] ?></td>
                                    <td class='py-2 px-4 text-center font-mulish text-sm sm:text-lg'><?= date("d F Y", strtotime($row['tanggal'])) ?></td>
                                    <td class='py-2 px-4 text-center font-mulish text-sm sm:text-lg'>Rp. <?= number_format($row['jumlah'], 0, '.', '.') ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            </section>
        </div>

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
            $(document).ready(function() {
                $('#dataTable').DataTable({
                    "pageLength": 6, // Data per halaman
                    "lengthChange": false, // Hilangkan opsi dropdown jumlah data per halaman
                    "order": [0],
                    "language": {
                        "paginate": {
                            "previous": "Prev",
                            "next": "Next"
                        },
                        "search": "Cari:",
                        "zeroRecords": "Data tidak ditemukan",
                        "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        "infoEmpty": "Menampilkan 0 data",
                        "infoFiltered": "(dari total _MAX_ data)"
                    },
                    // "dom": '<"flex justify-between items-center mb-4"<"text-left"f><"text-right"p>>t<"flex justify-between items-center mt-4"<"text-left"i><"text-right"p>>', // Ubah layout
                });
            });
        </script>

    </body>

    <style>
        /* Hapus scrollbar pada browser berbasis WebKit */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* Hapus scrollbar untuk browser lain */
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            background-color: #F3F4F6;
            /* Warna tombol pagination */
            color: #374151;
            /* Warna teks */
            border-radius: 4px;
            margin: 0 2px;
            padding: 5px 10px;
            border: none;
            transition: all 0.3s;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #7D46FD;
            /* Warna hover tombol */
            color: white;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #7D46FD;
            /* Warna tombol aktif */
            color: white;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #E5E7EB;
            border-radius: 4px;
            padding: 4px 10px;
            margin-left: 8px;
        }

        .dataTables_wrapper .dataTables_info {
            font-size: 0.875rem;
            color: #6B7280;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 10px;
        }
    </style>


    </html>
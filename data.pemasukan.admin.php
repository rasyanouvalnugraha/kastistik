<?php
session_start();
include "connection/database.php";

// Cek role pengguna
if ($_SESSION['role'] != '1') {
    header('location: index.php');
    exit();
}

// Pesan delete
$messageDeleteData = '';
if (isset($_POST['delete'])) {
    $delete_id = mysqli_real_escape_string($db, $_POST['delete_id']);

    // Query untuk menghapus data berdasarkan ID
    $delete_query = "DELETE FROM transactions WHERE id = '$delete_id' AND type IN (1,2)";
    $result = mysqli_query($db, $delete_query);

    if ($result && mysqli_affected_rows($db) > 0) {
        $messageDeleteData = "Success";
    } else {
        $messageDeleteData = "Failed";
    }

    // Redirect ke halaman ini dengan pesan
    header("Location: data.pemasukan.admin.php?messageDeleteData=" . $messageDeleteData);
    exit();
}

// Ambil data tahun, default ke tahun sekarang
$tahun = isset($_GET['tahun']) ? intval($_GET['tahun']) : date('Y');
if ($tahun < 1000 || $tahun > intval(date('Y'))) {
    $tahun = date('Y');
}

// Query untuk data pemasukan
$pemasukan = mysqli_query(
    $db,
    "
    SELECT
        transactions.id AS ID,
        transactions.date AS tanggal,
        users.fullname AS nama,
        transactions.amount AS jumlah,
        transactions.keterangan AS Keterangan
    FROM transactions
    JOIN users ON transactions.id_user = users.id
    WHERE transactions.type IN (1,2)
        AND transactions.approve = 1
        AND YEAR(date) = $tahun
    ORDER BY transactions.date DESC, transactions.id DESC"
);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/background.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/font.css">
    <link rel="icon" href="asset/BPS.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
</head>

<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <section class="sm:flex sm:relative hidden">
            <nav class="h-full 2xl:mr-5">
                <?php include "layout/navbar.php"; ?>
            </nav>
        </section>

        <!-- Main Content -->
        <section class="flex-1">
            <div class="flex">
                <div class="sm:hidden flex shadow-md">
                    <?php include "layout/responnavbar.php" ?>
                </div>
                <div class="text-lg font-mulish-extend w-full sm:p-5 p-3 justify-between flex shadow-md navbar sticky">
                    <h1 class="text-md sm:text-lg items-center">Data Pemasukan</h1>
                    <h1 class="text-md sm:text-lg items-center"><?php echo htmlspecialchars($_SESSION['username']); ?></h1>
                </div>
            </div>

            <!-- Filter dan Tambah -->
            <div class="flex justify-between">
                <div class="flex sm:mx-4 mx-2 my-5 space-x-4">
                    <h1 class="sm:text-2xl mt-2 text-sm sm:mt-0 font-mulish-extend hidden sm:flex">Data Pemasukan</h1>
                    <form action="" method="GET" class="space-x-2 flex">
                        <input type="number" name="tahun" min="1000" max="<?php echo date('Y'); ?>" value="<?php echo htmlspecialchars($tahun); ?>" class="sm:w-16 border-1 border-gray-500 rounded-md focus:border-teal-500">
                        <button type="submit" class="sm:px-4 sm:py-2 rounded-lg bg-gradient font-mulish text-white text-xs sm:text-base p-3">Tampilkan</button>
                    </form>
                </div>
                <div class="flex sm:mr-10 mr-6">
                    <a href="pemasukan.admin.php" class="flex items-center justify-center gap-2">
                        <h1 class="font-mulish text-sm sm:text-base">Tambah</h1>
                        <img src="asset/Plus Math.svg" alt="Tambah" class="w-10 h-10 bg-gradient p-1 rounded-lg">
                    </a>
                </div>
            </div>

            <!-- Tabel -->
            <div class="overflow-x-auto sm:mx-4 border-b-2 md:mx-8 my-4 sm:my-0">
                <table id="dataPemasukan" class="rounded-lg shadow-md">
                    <thead>
                        <tr class="bg-gradient text-white text-xs sm:text-base">
                            <th class="py-2 px-4 border-b font-mulish">Nama</th>
                            <th class="py-2 px-4 border-b font-mulish">Tanggal</th>
                            <th class="py-2 px-4 border-b font-mulish">Jumlah</th>
                            <th class="py-2 px-4 border-b font-mulish">Keterangan</th>
                            <th class="py-2 px-4 border-b font-mulish">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($pemasukan)) { ?>
                            <tr class='hover:bg-gray-300 text-xs sm:text-base'>
                                <td class='py-2 px-4 text-center font-mulish'><?= htmlspecialchars($row['nama']); ?></td>
                                <td class='py-2 px-4 text-center font-mulish' data-order="<?= date('Y-m-d', strtotime($row['tanggal'])); ?>">
                                    <?= date('d M', strtotime($row['tanggal'])); ?>
                                </td>
                                <td class='py-2 px-4 text-center font-mulish'>Rp. <?= number_format($row['jumlah'], 0, '.', '.'); ?></td>
                                <td class='py-2 px-4 text-center font-mulish'><?= !empty($row['Keterangan']) ? htmlspecialchars($row['Keterangan']) : " - "; ?></td>
                                <td class='py-2 px-4 text-center font-mulish'>
                                    <form method='POST' action=''>
                                        <input type='hidden' name='delete_id' value='<?= $row['ID']; ?>'>
                                        <button type='submit' name='delete' class='focus:outline-none'>
                                            <img src='asset/Remove.svg' alt='Delete' class='w-8 h-8 p-1 rounded-md bg-red-700'>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jszip@3.10.1/dist/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pdfmake@0.2.7/build/pdfmake.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pdfmake@0.2.7/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script>
        const messageCreateData = new URLSearchParams(window.location.search).get("messageCreateData");
        if (messageCreateData) {
            if (messageCreateData === "Sucsess") {
                Swal.fire({
                    title: 'Berhasil',
                    text: 'Data Pemasukan Berhasil ditambah',
                    icon: 'success',
                    confirmButtonText: 'Okay'
                }).then(() => {
                    const url = new URL(window.location);
                    url.searchParams.delete("messageCreateData");
                    window.history.replaceState({}, document.title, url);
                })
            } else if (messageCreateData === "Failed") {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Data Pemasukan gagal ditambah',
                }).then(() => {
                    const url = new URL(window.location);
                    url.searchParams.delete("messageCreateData");
                    window.history.replaceState({}, document.title, url);
                });
            }
        }

        const messageDeleteData = new URLSearchParams(window.location.search).get("messageDeleteData");
        if (messageDeleteData) {
            Swal.fire({
                icon: messageDeleteData === "Success" ? 'success' : 'error',
                title: messageDeleteData === "Success" ? 'Berhasil' : 'Gagal',
                text: messageDeleteData === "Success" ? 'Data pemasukan berhasil dihapus' : 'Data pemasukan gagal dihapus',
            }).then(() => {
                const url = new URL(window.location);
                url.searchParams.delete("messageDeleteData");
                window.history.replaceState({}, document.title, url);
            });
        }

        $(document).ready(function() {
            // Ambil parameter tahun dari URL
            const urlParams = new URLSearchParams(window.location.search);
            const tahun = urlParams.get('tahun') || new Date().getFullYear(); // Default tahun adalah tahun sekarang

            $('#dataPemasukan').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'csv',
                        text: 'Export CSV',
                        title: '',
                        filename: 'Data_Pemasukan_' + tahun,
                        exportOptions: {
                            columns: ':not(:last-child)' // Kecualikan kolom aksi
                        }
                    },
                    {
                        extend: 'excel',
                        title: '',
                        text: 'Export Excel',
                        filename: 'Data_Pemasukan_' + tahun,
                        exportOptions: {
                            columns: ':not(:last-child)' // Kecualikan kolom aksi
                        }
                    },
                    {
                        extend: 'pdf',
                        title: '',
                        text: 'Export PDF',
                        filename: 'Data_Pemasukan_' + tahun,
                        customize: function(doc) {
                            doc.content.splice(0, 0, {
                                text: 'Data Pemasukan Tahun ' + tahun,
                                style: 'header',
                                alignment: 'center',
                                margin: [0, 0, 0, 10]
                            });
                            doc.styles.tableHeader = {
                                bold: true,
                                color: 'white',
                                fillColor: '#4CAF50',
                                alignment: 'center'
                            };
                        },
                        exportOptions: {
                            columns: ':not(:last-child)' // Kecualikan kolom aksi
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Data Pemasukan Tahun ' + tahun,
                        exportOptions: {
                            columns: ':not(:last-child)' // Kecualikan kolom aksi
                        },
                        customize: function(win) {
                            $(win.document.body).css('font-family', 'Arial, sans-serif').css('text-align', 'center');
                            $(win.document.body).find('h1').addClass('text-lg font-bold').text('Data Pemasukan');
                        }
                    }
                ],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    paginate: {
                        first: "Awal",
                        last: "Akhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    },
                    zeroRecords: "Data tidak ditemukan",
                    infoEmpty: "Menampilkan 0 data",
                    infoFiltered: "(dari total _MAX_ data)"
                },
                responsive: true,
                order: [
                    [1, "desc"]
                ],
                columnDefs: [{
                        targets: -1, // Kolom terakhir untuk aksi
                        orderable: false, // Kolom ini tidak bisa diurutkan
                        searchable: false // Kolom ini tidak ikut dalam pencarian
                    },
                    {
                        targets: [0, 1, 2, 3, 4], // Center align
                        className: "dt-center"
                    }
                ]
            });
        });
    </script>
</body>

</html>
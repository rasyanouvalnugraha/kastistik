<?php
session_start();
if ($_SESSION['role'] != '1') {
    header('location: index.php');
    exit();
}

include "connection/database.php";


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

// get data request user
$getData = mysqli_query($db, "
    SELECT 
        transactions.date AS tanggal, 
        transactions.id AS nomor,
        users.fullname AS nama, 
        transactions.amount AS jumlah, 
        transactions.keterangan AS Keterangan 
    FROM transactions
    JOIN users ON transactions.id_user = users.id 
    WHERE transactions.approve = 0 
    ORDER BY transactions.date DESC;
");
$message = "";

// Jika tombol approve atau decline dipencet
if (isset($_POST['approve']) || isset($_POST['decline'])) {
    $id = $_POST['id'];

    if (isset($_POST['approve'])) {
        // Ambil jumlah request transaksi berdasarkan ID
        $queryTransaction = "SELECT amount FROM transactions WHERE id = '$id' AND approve = 0";
        $resultTransaction = $db->query($queryTransaction);
        $transactionData = $resultTransaction->fetch_assoc();
        $requestedAmount = $transactionData['amount'] ?? 0;

        // Validasi jika request amount <= sisa saldo
        if ($requestedAmount <= $sisa) {
            // Jika saldo cukup, update approve menjadi 1 (disetujui)
            $query = "UPDATE transactions SET approve = 1 WHERE id = '$id'";
            $message = "Sucsess";
            $sisa -= $requestedAmount;
        } else {
            $message = "notbalance";
        }
    } elseif (isset($_POST['decline'])) {
        // Jika tombol decline dipencet
        $query = "UPDATE transactions SET approve = 2 WHERE id = '$id'";
        $message = "Decline";
    }

    // Eksekusi query hanya jika query telah diatur
    if (isset($query)) {
        mysqli_query($db, $query);
    }

    header('location: request.admin.php?message=' . $message);
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/background.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="card.css">
    <link rel="stylesheet" href="css/background.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="icon" href="asset/BPS.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="sweetalert2.min.css">
</head>

<body class="bg-gray-100">
    <div class="flex">
        <section class="sm:relative sm:flex hidden">
            <nav class="navbar h-screen">
                <?php include "layout/navbar.php"; ?>
            </nav>
        </section>

        <section class="flex-1">
            <div class="flex">
                <div class="sm:hidden flex shadow-md">
                    <?php include "layout/responnavbar.php" ?>
                </div>
                <div class="w-full">
                    <div class="text-lg font-mulish-extend w-full sm:p-5 p-3 shadow-md navbar sticky">
                        <div class="flex gap-3 justify-between">
                            <h1 class="text-md sm:text-lg items-center">Request</h1>
                            <h1 class="text-md sm:text-lg items-center"><?php echo htmlspecialchars($_SESSION['username']); ?></h1>
                        </div>
                    </div>
                    <div class="hidden sm:flex space-x-2 text-xs xl:text-sm 2xl:text-base font-mulish-extend mx-5 mt-3">
                        <a href="request.admin.php" class="bg-gradient p-2 text-white rounded-md">Request</a>
                        <a href="history.admin.php" class="bg-gradient p-2 text-white rounded-md">History Request</a>
                    </div>
                </div>
            </div>
            <div class="flex space-x-2 text-xs xl:text-sm 2xl:text-base font-mulish-extend mx-5 mt-3">
                <a href="request.admin.php" class="bg-gradient p-2 text-white rounded-md">Request</a>
                <a href="history.admin.php" class="bg-gradient p-2 text-white rounded-md">History Request</a>
            </div>
            <section>
                <?php include "layout/card.php" ?>
            </section>

            <div class="overflow-x-auto sm:mx-8 my-2 rounded-lg w-screen sm:w-auto">
                <div class="max-h-72 xl:max-h-72 2xl:max-h-full relative overflow-y-auto no-scrollbar">
                    <?php
                    if (mysqli_num_rows($getData) > 0) {
                        // Jika data tersedia, tampilkan tabel
                        echo '<table class="min-w-full rounded-lg shadow-md">
                    <thead>
                        <tr class="bg-gradient navbar text-white text-sm sm:text-base">
                            <th class="py-2 px-4 border-b font-mulish sticky top-0 z-10">Tanggal</th>
                            <th class="py-2 px-4 border-b font-mulish sticky top-0 z-10">Nama</th>
                            <th class="py-2 px-4 border-b font-mulish sticky top-0 z-10">Jumlah</th>
                            <th class="py-2 px-4 border-b font-mulish sticky top-0 z-10">Keterangan</th>
                            <th class="py-2 px-4 border-b font-mulish sticky top-0 z-10">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>';

                        // Loop untuk menampilkan data
                        while ($row = mysqli_fetch_assoc($getData)) {
                            echo "<tr class='text-xs sm:text-base'>";
                            echo "<td class='py-2 px-4 text-center font-mulish'>" . date('d M Y', strtotime($row['tanggal'])) . "</td>";
                            echo "<td class='py-2 px-4 text-center font-mulish'>" . $row['nama'] . "</td>";
                            echo "<td class='py-2 px-4 text-center font-mulish'>" . "Rp " . number_format($row['jumlah'], 0, ',', '.') . "</td>";
                            echo "<td class='py-2 px-4 text-center font-mulish'>" . $row['Keterangan'] . "</td>";
                            echo "<td class='py-2 px-4 text-center font-mulish'>
                                    <form action='request.admin.php' method='post' class='flex items-center justify-center'>
                                        <input type='hidden' name='id' value='" . $row['nomor'] . "'>
                                        <button type='submit' name='approve' value='setujui'>
                                            <img src='asset/Thumbs Up.svg' alt='Setujui' class='w-8 h-8 up m-1 rounded-md p-1'>
                                        </button>
                                        <button type='submit' name='decline' value='tolak'>
                                            <img src='asset/Thumbs Down.svg' alt='Tolak' class='w-8 h-8 down m-1 rounded-md py-1'>
                                        </button>
                                    </form>
                                </td>";
                            echo "</tr>";
                        }

                        echo '</tbody></table>';
                    } else {
                        // Jika tidak ada data, tampilkan pesan
                        echo "<div class='text-center text-lg py-5 my-5 font-mulish text-gray-500'>Data tidak ada</div>";
                    }
                    ?>
                </div>
            </div>
            <div class="sm:mx-8 hidden sm:block">
                <a href="history.admin.php" class="">
                    <p class="font-mulish-extend text-white bg-gradient p-2">History</p>
                </a>
            </div>


        </section>
    </div>

    <style>
        .up {
            background-color: #13F566;
        }

        .down {
            background-color: #F51313;
        }
    </style>

    <script>
        // tangkap parameter message dari action approve dan decline
        const searchParams = new URLSearchParams(window.location.search);

        // sweetalert parameter message
        const message = searchParams.get("message");
        if (message) {
            if (message === "Sucsess") {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil, Request diterima',
                    text: 'Permintaan berhasil ditambahkan ke pengeluaran'
                }).then(() => {
                    // hapus parameter setelah SweetAlert ditutup
                    const currentUrl = new URL(window.location);
                    currentUrl.searchParams.delete("message");
                    window.history.replaceState({}, document.title, currentUrl);
                });
            } else if (message === "Decline") {
                Swal.fire({
                    icon: 'success',
                    title: 'Request Permintaan Ditolak',
                    text: 'Permintaan yang ditolak masuk ke History'
                }).then(() => {
                    const currentUrl = new URL(window.location);
                    currentUrl.searchParams.delete("message");
                    window.history.replaceState({}, document.title, currentUrl);
                });
            }
        }

        if (message === "notbalance") {
            Swal.fire({
                icon: 'error',
                title: 'Saldo Tidak Cukup',
                text: 'Permintaan tidak dapat disetujui karena saldo tidak mencukupi.'
            }).then(() => {
                // Hapus parameter setelah SweetAlert ditutup
                const currentUrl = new URL(window.location);
                currentUrl.searchParams.delete("message");
                window.history.replaceState({}, document.title, currentUrl);
            });
        }
    </script>
</body>

</html>
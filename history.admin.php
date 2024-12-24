<?php
session_start();
if ($_SESSION['role'] != '1') {
    header('location: index.php');
    exit();
}

include "connection/database.php";

// Validasi dan ambil tahun dari query string
$tahun = isset($_GET['tahun']) && is_numeric($_GET['tahun']) ? intval($_GET['tahun']) : date('Y');

// Ambil data request user
$getData = mysqli_query($db, "
    SELECT 
        transactions.date AS tanggal, 
        transactions.id AS nomor,
        users.fullname AS nama, 
        transactions.amount AS jumlah, 
        transactions.keterangan AS Keterangan 
    FROM transactions
    JOIN users ON transactions.id_user = users.id 
    WHERE transactions.approve = 2 
    AND YEAR(transactions.date) = $tahun 
    ORDER BY transactions.date DESC
");

$message = '';
// Jika tombol delete diklik
if (isset($_POST['delete'])) {
    $id = $_POST['id'];

    // Gunakan prepared statement
    $stmt = $db->prepare("DELETE FROM transactions WHERE id = ? AND approve = 2");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = "Success";
    } else {
        $message = "Failed";
    }

    header("Location: history.admin.php?message=" . $message);
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
    <link rel="stylesheet" href="card.css">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="icon" href="asset/BPS.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.all.min.js"></script>
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
                            <h1 class="text-md sm:text-lg items-center">History</h1>
                            <h1 class="text-md sm:text-lg items-center"><?php echo htmlspecialchars($_SESSION['username']); ?></h1>
                        </div>
                    </div>
                </div>
            </div>
            <section>
                <?php include "layout/card.php" ?>
            </section>

            <section>
                <div class="flex mx-5 font-mulish-extend space-x-2">
                    <div class="flex space-x-2">
                        <h1 class="p-2 text-xs 2xl:text-xl xl:text-lg  justify-center items-center mt-1 sm:mt-0">Pilih Tahun :</h1>
                        <form action="" method="GET" class="space-x-2">
                            <input type="number" class="py-2 sm:w-20 w-10 h-8 sm:h-11 rounded-md" name="tahun" min="1990" max="<?php echo date('Y'); ?>" value="<?php echo htmlspecialchars($_GET['tahun'] ?? date('Y')); ?>" required>
                            <button class="bg-gradient p-2 text-white rounded-md font-mulish-extend 2xl:text-lg xl:text-base text-sm" type="submit">Tampilkan</button>
                            <a href="request.admin.php" class="bg-gradient p-2.5 text-white rounded-md font-mulish-extend 2xl:text-lg xl:text-base text-sm">Request</a>
                        </form>
                    </div>
                </div>
            </section>

            <div class="overflow-x-auto w-screen sm:w-auto sm:mx-8 my-2">
                <div class="max-h-80 relative overflow-y-auto no-scrollbar rounded-lg">
                    <?php
                    if (mysqli_num_rows($getData) > 0) {
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

                        while ($row = mysqli_fetch_assoc($getData)) {
                            echo "<tr class='text-xs sm:text-base'>";
                            echo "<td class='py-2 px-4 text-center font-mulish'>" . date('d M Y', strtotime($row['tanggal'])) . "</td>";
                            echo "<td class='py-2 px-4 text-center font-mulish'>" . $row['nama'] . "</td>";
                            echo "<td class='py-2 px-4 text-center font-mulish'>" . 'Rp ' . number_format($row['jumlah'], 0, ',', '.') . "</td>";
                            echo "<td class='py-2 px-4 text-center font-mulish'>" . $row['Keterangan'] . "</td>";
                            echo "<td class='py-2 px-4 text-center font-mulish'>
                                    <form action='' method='post'>
                                        <input type='hidden' name='id' value='" . $row['nomor'] . "'>
                                        <button type='submit' name='delete' value='tolak'>
                                            <img src='asset/Remove.svg' alt='Tolak' class='w-8 h-8 down m-1 rounded-md p-1'>
                                        </button>
                                    </form>
                                </td>";
                            echo "</tr>";
                        }

                        echo '</tbody></table>';
                    } else {
                        echo "<div class='text-center text-lg py-5 my-5 font-mulish text-gray-500'>Data tidak ada</div>";
                    }
                    ?>
                </div>
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
        const searchParams = new URLSearchParams(window.location.search);
        const message = searchParams.get("message");

        if (message === "Success") {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'History Berhasil di Delete',
            }).then(() => {
                // Hapus parameter setelah SweetAlert ditutup
                const currentUrl = new URL(window.location);
                currentUrl.searchParams.delete("message");
                window.location.href = currentUrl.toString();
            });
        } else if (message === "Failed") {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Permintaan gagal ditolak',
            });
        }
    </script>
</body>

</html>
<?php
session_start();
if ($_SESSION['role'] != '1') {
    header('location: index.php');
    exit();
}

include "connection/database.php";

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

// Jika tombol approve atau decline dipencet
if (isset($_POST['approve']) || isset($_POST['decline'])) {
    $id = $_POST['id'];

    if (isset($_POST['approve'])) {
        // Jika tombol approve dipencet
        $query = "UPDATE transactions SET approve = 1 WHERE id = '$id'";
    } elseif (isset($_POST['decline'])) {
        // Jika tombol decline dipencet
        $query = "UPDATE transactions SET approve = 2 WHERE id = '$id'";
    }

    // eksekusi query 
    $result = mysqli_query($db, $query);

    // Cek apakah query berhasil dijalankan
    if ($result) {
        echo "<script>alert('Data berhasil diperbarui');</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan: " . mysqli_error($db) . "');</script>";
    }

    header('location: request.admin.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DASHBOARD ADMIN</title>
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
</head>

<body class="bg-gray-100">
    <div class="flex">
        <section class="relative">
            <nav class="navbar h-screen">
                <?php include "layout/navbar.php"; ?>
            </nav>
        </section>

        <section class="flex-1">
            <div class="text-lg font-mulish-extend w-full p-5 justify-between flex shadow-md navbar">
                <h1>Permintaan Dari User</h1>
                <h1><?php print $_SESSION['username']; ?></h1>
            </div>
            <section>
                <?php include "layout/card.php" ?>
            </section>

            <div class="overflow-x-auto mx-8 my-2">
                <div class="max-h-72 relative overflow-y-auto no-scrollbar">
                    <?php
                    if (mysqli_num_rows($getData) > 0) {
                        // Jika data tersedia, tampilkan tabel
                        echo '<table class="min-w-full rounded-lg shadow-md">
                    <thead>
                        <tr class="bg-gradient navbar text-white">
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
                            echo "<tr>";
                            echo "<td class='py-2 px-4 text-center font-mulish'>" . $row['tanggal'] . "</td>";
                            echo "<td class='py-2 px-4 text-center font-mulish'>" . $row['nama'] . "</td>";
                            echo "<td class='py-2 px-4 text-center font-mulish'>" . number_format($row['jumlah'], 0, ',', '.') . "</td>";
                            echo "<td class='py-2 px-4 text-center font-mulish'>" . $row['Keterangan'] . "</td>";
                            echo "<td class='py-2 px-4 text-center font-mulish'>
                        <form action='request.admin.php' method='post'>
                            <input type='hidden' name='id' value='" . $row['nomor'] . "'>
                            <button type='submit' name='approve' value='setujui'>
                                <img src='asset/Thumbs Up.svg' alt='Setujui' class='w-8 h-8 up m-1 rounded-md p-1'>
                            </button>
                            <button type='submit' name='decline' value='tolak'>
                                <img src='asset/Remove.svg' alt='Tolak' class='w-8 h-8 down m-1 rounded-md p-1'>
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
</body>

</html>
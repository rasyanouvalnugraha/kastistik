<?php
session_start();
if ($_SESSION['role'] != '1') {
    header('location: index.php');
    exit();
}

include "connection/database.php";

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
    ORDER BY transactions.date DESC;
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
            <nav class="navbar h-screen 2xl:mr-5">
                <?php include "layout/navbar.php"; ?>
            </nav>
        </section>

        <section class="flex-1">
            <div class="flex">
                <div class="sm:hidden flex shadow-md">
                    <?php include "layout/responnavbar.php" ?>
                </div>
                <div class="text-lg font-mulish-extend w-full sm:p-5 p-3 justify-between flex shadow-md navbar sticky">
                    <h1 class="text-md sm:text-lg items-center">History Request</h1>
                    <h1 class="text-md sm:text-lg items-center"><?php echo htmlspecialchars($_SESSION['username']); ?></h1>
                </div>
            </div>
            <section>
                <?php include "layout/card.php" ?>
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
                            echo "<tr class='text-sm sm:text-base'>";
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
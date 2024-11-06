<?php
session_start();
include 'connection/database.php';

if ($_SESSION['role'] != '2') {
    header('location: index.php');
    exit();
}

$message = '';

if (isset($_POST['add'])) {

    $username = $_SESSION['fullname']; // Mengambil nama pengguna dari session
    $keperluan = $_POST['keperluan'];
    $amount = $_POST['amount'];
    $tanggal = $_POST['tanggal'];

    // Ambil ID pengguna berdasarkan username
    $query = "SELECT id FROM users WHERE fullname = '$username' AND role = '2'";
    $result = mysqli_query($db, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $id_user = $row['id']; // Dapatkan ID pengguna

        // Sekarang kita bisa menggunakan $id_user di query insert
        $inputdata = "INSERT INTO `transactions` (`id`, `id_user`, `amount`, `type`, `date`, `keterangan`, `saldo`, `approve`) VALUES (NULL, '$id_user', '$amount', '3', '$tanggal', '$keperluan', '$amount', '0')";

        // kondisi pengecekan
        if (mysqli_query($db, $inputdata)) {
            // melakukan pengecekan jika data berhasil ditambah
            $message = "<div class='text-green-600 text-lg'>Request berhasil dikirim ke Admin, tunggu admin approve</div>";
        } else {
            // melakukan pengecekan jika data gagal ditambah
            $message = "<div class='text-red-600 text-lg'>Request gagal dikirim: " . mysqli_error($db) . "</div>";
        }
    } else {
        $message = "<div class='text-red-600 text-lg'>User tidak ditemukan.</div>";
    }

    header("location: request.user.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DASHBOARD</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/background.css">
    <link rel="stylesheet" href="card.css">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="icon" href="asset/BPS.png" type="image/x-icon">
</head>

<body>
    <div class="flex">
        <section class="relative">
            <nav class="navbar h-screen">
                <?php include "layout.user/navbar.user.php"; ?>
            </nav>
        </section>

        <section class="flex-1">
            <div class="text-lg font-mulish-extend w-full p-5 justify-between flex shadow-md navbar">
                <h1>Dashboard</h1>
                <h1><?php print $_SESSION['fullname']; ?></h1>
            </div>

            <section class="">
                <section class="flex flex-col md:flex-row">
                    <div class="flex flex-1 flex-col w-full h-full p-6">
                        <h1 class="text-2xl font-mulish-extend mb-6">Request User To Admin</h1>

                        <!-- Menampilkan pesan berhasil atau gagal -->
                        <?php if (!empty($message)) {
                            echo $message;
                        }
                        ?>

                        <form action="request.user.php" class="space-y-4 font-mulish" method="POST">
                            <!-- Tambahkan input tersembunyi untuk username -->
                            <input type="hidden" name="username" value="<?php echo $_SESSION['fullname']; ?>">

                            <div>
                                <label for="needs" class="block text-gray-700 font-semibold mb-2">Keperluan</label>
                                <div class="flex items-center border border-gray-300 rounded-md focus-within:ring-2 focus-within:ring-blue-500 bg-white">
                                    <img src="asset/Strategy.svg" alt="Coins Icon" class="w-6 h-6 ml-3">
                                    <input type="text" id="amount" name="keperluan" class="w-full px-4 py-4 focus:outline-none" placeholder="Beli Sabun....." required>
                                </div>
                            </div>

                            <div>
                                <label for="amount" class="block text-gray-700 font-semibold mb-2">Jumlah Uang</label>
                                <div class="flex items-center border border-gray-300 rounded-md focus-within:ring-2 focus-within:ring-blue-500 bg-white">
                                    <img src="asset/Receive Cash.svg" alt="Coins Icon" class="w-6 h-6 ml-3">
                                    <input type="text" id="amount" name="amount" class="w-full px-4 py-4 focus:outline-none" placeholder="Masukkkan Jumlah Uang" required>
                                </div>
                            </div>

                            <div>
                                <label for="date" class="block text-gray-700 font-semibold mb-2">Tanggal</label>
                                <div class="flex items-center border border-gray-300 rounded-md focus-within:ring-2 focus-within:ring-blue-500 bg-white">
                                    <img src="asset/Calendar.svg" alt="Calendar Icon" class="w-6 h-6 ml-3">
                                    <input type="datetime-local" id="date" name="tanggal" class="w-full px-4 py-4 focus:outline-none" required>
                                </div>
                            </div>

                            <div class="mt-3">
                                <input type="submit" name="add" value="Sumbit" class="w-full bg-button text-white px-4 py-4 rounded-md transition-colors duration-300 font-mulish-extend">
                            </div>
                        </form>

                    </div>
                    <div class="flex-1 w-full md:flex md:items-center hidden ">
                        <div>
                            <img src="asset/Male specialist working in support service.svg" alt="Man and woman discussing idea" class="p-6">
                        </div>
                    </div>

                </section>

                <section>
                    <?php include 'layout/card.php' ?>
                </section>
            </section>
        </section>
    </div>
</body>

<style>
    .bg-button {
        background: #7D46FD;
    }

    .custom-height {
        height: 6rem;
    }

    select.no-border {
        border: none;
        outline: none;
        appearance: none;
    }

    select.no-border option {
        border: none;
    }
</style>

</html>
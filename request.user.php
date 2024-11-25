<?php
session_start();
include 'connection/database.php';

// Validasi role user
if ($_SESSION['role'] != '2') {
    header('location: index.php');
    exit();
}


// menghitung pemasukan
$querypemasukan = "SELECT SUM(amount) AS pemasukan FROM `transactions` WHERE type = 1 AND approve = 1; ";
$result1 = $db->query($querypemasukan);
$data = $result1->fetch_assoc();
$pemasukan = $data['pemasukan'];

// menghitung pengeluaran 
$querypengeluaran = "SELECT SUM(amount) AS pengeluaran FROM `transactions` WHERE type = 3 AND approve = 1;";
$result1 = $db->query($querypengeluaran);
$data = $result1->fetch_assoc();
$pengeluaran = $data['pengeluaran'];
//menghitung saldo

$sisa = $pemasukan - $pengeluaran;

$messageSaldo = '';
if (isset($_POST['add'])) {
    $username = $_SESSION['fullname']; // Nama pengguna dari session
    $keperluan = $_POST['keperluan'];

    // mengubah format menjadi nominal biasa
    $amounts = preg_replace('/\D/', '', $_POST['amount']);


    // Ambil id user berdasarkan username
    $query = "SELECT id FROM users WHERE fullname = '$username' AND role = '2'";
    $result = mysqli_query($db, $query);

    // pengecekan jika datanya bertambah
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $id_user = $row['id'];

        // Validasi input saldo jika mencukupi
        if ($amounts <= $sisa) {
            $inputdata = "INSERT INTO `transactions` (`id`, `id_user`, `amount`, `type`, `date`, `keterangan`, `saldo`, `approve`) 
                          VALUES (NULL, '$id_user', '$amounts', '3', NOW(), '$keperluan', '$amounts', '0')";

            if (mysqli_query($db, $inputdata)) {
                $messageSaldo = "Success";
            }
        } else {
            $messageSaldo = "Kurang";
        }
        header('Location: request.user.php?messageSaldo=' . $messageSaldo);
    }
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
    <link rel="stylesheet" href="css/card.css">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="icon" href="asset/BPS.png" type="image/x-icon">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.all.min.js"></script>
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
                <h1><?php echo $_SESSION['fullname']; ?></h1>
            </div>

            <section class="">
                <section class="flex flex-col md:flex-row">
                    <div class="flex flex-1 flex-col w-full h-full px-4">
                        <h1 class="text-2xl font-mulish-extend mt-4">Request User To Admin</h1>

                        <!-- Form -->
                        <form action="request.user.php" class="2xl:space-y-8 space-y-4 font-mulish" method="POST">
                            <input type="hidden" name="username" value="<?php echo $_SESSION['fullname']; ?>">

                            <div>
                                <label for="keperluan" class="block text-gray-700 font-semibold mb-2">Keperluan</label>
                                <div class="flex items-center border border-gray-300 rounded-md focus-within:ring-2 focus-within:ring-blue-500 bg-white">
                                    <img src="asset/Strategy.svg" alt="Keperluan Icon" class="w-6 h-6 ml-3">
                                    <input type="text" id="keperluan" name="keperluan" class="w-full px-4 py-4 focus:outline-none 2xl:py-6" placeholder="Beli Sabun....." required>
                                </div>
                            </div>

                            <div>
                                <label for="amount" class="block text-gray-700 font-semibold mb-2">Jumlah Uang</label>
                                <div class="flex items-center border border-gray-300 rounded-md focus-within:ring-2 focus-within:ring-blue-500">
                                    <img src="asset/Receive Cash.svg" alt="Coins Icon" class="w-6 h-6 ml-3">
                                    <input type="text" id="amount" name="amount" class="w-full px-4 py-4 focus:outline-none 2xl:py-6" placeholder="Masukkan jumlah uang" required>
                                </div>
                            </div>

                            <div class="mt-3">
                                <input type="submit" name="add" value="Submit" class="w-full bg-button text-white px-4 py-4 rounded-md transition-colors duration-300 font-mulish-extend">
                            </div>
                        </form>
                    </div>
                    <div class="flex-1 w-full xl:flex hidden">
                        <div>
                            <img src="asset/Male specialist working in support service.svg" alt="Support Service" class="">
                        </div>
                    </div>
                </section>

                <section class="">
                    <?php include 'layout/card.php'; ?>
                </section>
            </section>
        </section>
    </div>

    <script>
        // SweetAlert berdasarkan pesan yang diterima di URL
        const urlParams = new URLSearchParams(window.location.search);
        const messagesaldo = urlParams.get('messageSaldo');

        if (messagesaldo) {
            if (messagesaldo === "Kurang") {
                Swal.fire({
                    icon: 'error',
                    title: 'Saldo Tidak Cukup!',
                    text: 'Input nominal request anda melebihi saldo.'
                }).then(() => {
                    const currentUrl = new URL(window.location);
                    currentUrl.searchParams.delete("messageSaldo");
                    window.history.replaceState({}, document.title, currentUrl);
                });
            } else if (messagesaldo === "Success") {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Permintaan Anda telah dikirim ke Admin, tunggu konfirmasi dan cek data pengeluaran jika di setujui'
                }).then(() => {
                    const currentUrl = new URL(window.location);
                    currentUrl.searchParams.delete("messageSaldo");
                    window.history.replaceState({}, document.title, currentUrl);
                });
            }
        }

        // Format input uang
        const jumlahUangInput = document.getElementById('amount');

        jumlahUangInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            let formatted = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(value);

            e.target.value = formatted.replace("Rp", "Rp ");
        });

        jumlahUangInput.addEventListener('focus', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });

        jumlahUangInput.addEventListener('blur', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(value).replace("Rp", "Rp ");
        });
    </script>
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
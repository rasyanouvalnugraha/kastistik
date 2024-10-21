<?php
include "connection/database.php";
session_start();



if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE id_pegawai = '$username' AND password = '$password'";

    $result = $db->query($sql);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == '1') {
            header('Location: admin.php');
        }
        if ($user['role'] == '2') {
            header('Location: user.php');
        } else {
            print "<script>alert('akun tidak ditemukan')</script>";
        }
    }
}
$db->close();


?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Halaman Login</title>
</head>

<body>
    <!-- Bagian Background Gradient -->
    <main class="h-screen flex">
        <?php include 'layout/form.login.php' ?>
    </main>
</body>

</html>
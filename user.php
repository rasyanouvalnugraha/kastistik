<?php
session_start();
if ($_SESSION['role'] != 'user') {
    header('location: index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/background.css">
    <style>
        .bg-gradient {
            background: linear-gradient(135deg, #7D46FD, #FDC991);
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <section class="relative">
            <nav class="navbar sticky top-0 z-10 h-screen">
                <?php include "layout/navbar.user.php"; ?>
            </nav>
        </section>
        <section class="flex-1">
            <h1 class="text-lg font-bold w-full p-5 justify-end flex">
                <?php print $_SESSION['username']; ?>
            </h1>
            <hr>
            <section class="flex-1">
                <?php include 'layout/card.php' ?>
            </section>
        </section>
    </div>
</body>

</html>
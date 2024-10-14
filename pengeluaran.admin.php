<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header('location: index.php');
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
    <link rel="stylesheet" href="css/background.css">

</head>


<body class="">
    <div class="flex">
        <section class="relative">
            <nav class="navbar h-screen">
                <?php include "layout/navbar.php"; ?>
            </nav>
        </section>

        <section class="flex-1">
            <div class="text-lg font-mulish-extend w-full p-5 flex justify-between shadow-md">
                <h1>Input Data</h1>
                <?php print $_SESSION['username']; ?>
            </div>

            <section class="flex">
                <section>
                    <div class="flex flex-wrap gap-4">
                        
                    </div>
                </section>
            </section>
            <div class="">
                <?php include "layout/card.php"; ?>
            </div>
        </section>
    </div>

</body>
<style>
    .bg-button {
        background: #7D46FD;
    }
</style>

</html>
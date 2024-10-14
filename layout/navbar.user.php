<?php

?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/font.css">
<link rel="stylesheet" href="css/navbar.css">
<section class="bg-gradient px-3 py-2 h-screen text-white sm:w-56 navbar">
    <div class="p-2 sm:p-5 text-2xl font-bold flex justify-center my-5">Kastistik</div>
    <nav>
        <ul class="gap-5">
            <li class="px-3 py-4 sm:p-5 transition-colors duration-300 ease-in-out hover:bg-blue-700 hover:text-white rounded-xl my-4 mx-1">
                <a href="#">Profile</a>
            </li>
            <li class="px-3 py-4 sm:p-5 transition-colors duration-300 ease-in-out hover:bg-blue-700 hover:text-white rounded-xl my-4 mx-1">
                <a href="#">Messages</a>
            </li>
            <li class="px-3 py-4 sm:p-5 transition-colors duration-300 ease-in-out hover:bg-blue-700 hover:text-white rounded-xl my-4 mx-1">
                <?php include "layout/exit.php" ?>
            </li>
        </ul>
    </nav>
</section>
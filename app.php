<?php
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KASTISTIK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
    <link rel="stylesheet" href="css/background.css">

</head>

<body class="bg-gradient-to-r from-purple-500 to-indigo-500">
    <main class="flex h-screen">
        <div class="flex-1 flex flex-col justify-center items-center text-center">
            <div class="bg-white bg-opacity-10 p-8 rounded-lg shadow-lg">
                <span id="element" class="text-5xl font-bold text-white mb-8 block"></span>
                
                <a href="login.php" class="mt-6 inline-block bg-white text-indigo-600 font-semibold text-lg py-2 px-8 rounded-lg shadow-lg transition-all duration-300 hover:bg-indigo-500 hover:text-white hover:shadow-xl transform hover:scale-105">
                    LOGIN
                </a>
            </div>
        </div>
    </main>
    <script>
        var typed = new Typed('#element', {
            strings: ['WELCOME', 'KASTISTIK', 'AMANAH'],
            typeSpeed: 60,
            backSpeed: 70,
            startDelay: 900,
            backDelay: 900,
            loop: true,
            showCursor: false,
        });
    </script>
</body>

</html>

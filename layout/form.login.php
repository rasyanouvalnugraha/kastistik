<link rel="stylesheet" href="css/background.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<!-- TEXT SELAMAT DATANG -->
<div class="hidden md:flex md:w-1/2 md:mx-auto bg-gradient">
    <div class="flex items-center justify-center w-full h-full">
        <h1 id="welcome-text" class="text-white text-6xl font-mulish fade">Selamat Datang!</h1>
    </div>
</div>

<!-- FORM LOGIN  -->
<div class="w-full md:w-1/2 flex items-center justify-center bg-white">
    <div class="max-w-md w-full px-8 py-6">
        <h2 class="text-2xl font-mulish-extend text-center mb-6">Login</h2>

        <!-- LOGIN FORM -->
        <form action="index.php" method="POST">
            <div class="mb-4">
                <!-- USERNAME -->
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                <div class="flex items-center border border-gray-300 rounded-md focus-within:ring-2 focus-within:ring-blue-500">
                    <img src="asset/Person.Svg" class="w-6 h-6 ml-3">
                    <input type="text" name="username" class="w-full px-4 py-4 focus:outline-none" required placeholder="username" >
                </div>
            </div>

            <!-- PASSWORD -->
            <div class="mb-4">
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <div class="flex items-center border border-gray-300 rounded-md focus-within:ring-2 focus-within:ring-blue-500">
                    <img src="asset/Lock.Svg" class="w-6 h-6 ml-3">
                    <input type="password" name="password" class="w-full px-4 py-4 focus:outline-none" required placeholder="password">
                </div>
            </div>

            <!-- BUTTON SUMBIT -->
            <div class="flex items-center justify-between">
                <button
                    class="bg-login w-full p-3 rounded-lg text-white font-mulish"
                    type="submit" name="login">
                    Login
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // ANIMASI TEXT FADE IN , FADE OUT 
    const texts = ["Selamat Datang!", "Kastistik"];
    let index = 0;

    function changeText() {
        const welcomeText = document.getElementById("welcome-text");
        welcomeText.classList.remove("fade-in");

        setTimeout(() => {
            index = (index + 1) % texts.length;
            welcomeText.textContent = texts[index];
            welcomeText.classList.add("fade-in");
        }, 500);

        setTimeout(changeText, 3000);
    }

    document.addEventListener("DOMContentLoaded", () => {
        changeText();
        document.getElementById("welcome-text").classList.add("fade-in");
    });
</script>

<style>
    .bg-login {
        background-color: #7D46FD;
    }
</style>
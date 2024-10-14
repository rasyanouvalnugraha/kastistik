<link rel="stylesheet" href="css/background.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<div class="hidden md:flex md:w-1/2 md:mx-auto bg-gradient">
    <div class="flex items-center justify-center w-full h-full">
        <h1 id="welcome-text" class="text-white text-6xl font-mulish fade">Selamat Datang!</h1>
    </div>
</div>
<div class="w-full md:w-1/2 flex items-center justify-center bg-white">
    <div class="max-w-md w-full px-8 py-6">
        <h2 class="text-2xl font-mulish-extend text-center mb-6">Login</h2>
        <form action="index.php" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-mulish mb-2">
                    <i class="fa-solid fa-user"></i> ID User
                </label>
                <label for="">
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        id="text" type="text" placeholder="ID" name="username">
                </label>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    <i class="fa-solid fa-lock"></i> Password
                </label>
                <label for="">
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        id="password" type="password" placeholder="Password" required name="password">
                </label>
            </div>

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
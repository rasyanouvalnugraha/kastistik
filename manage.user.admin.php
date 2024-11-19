<?php
session_start();
include "connection/database.php";

if ($_SESSION['role'] != '1') {
    header('location: index.php');
    exit();
}

// Proses hapus data jika ada permintaan delete
if (isset($_POST['delete'])) {
    $delete_id = $_POST['delete_id'];

    // Hapus transaksi terkait user
    $deleteTransactions = $db->prepare('DELETE FROM transactions WHERE id_user = ?');
    $deleteTransactions->bind_param('d', $delete_id);
    $deleteTransactions->execute();

    // Hapus user
    $query = $db->prepare('DELETE FROM users WHERE id = ?');
    $query->bind_param('d', $delete_id);

    if ($query->execute()) {
        header('Location: manage.user.admin.php');
        exit();
    } else {
        echo "Error: " . $query->error;
    }
}

// Ambil data semua user
$getAlluser = $db->query('SELECT * FROM `users` WHERE role = 2;');
$result = [];
while ($row = $getAlluser->fetch_assoc()) {
    $result[] = (object) $row;
}

// Create user
if (isset($_POST['create'])) {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $premi = $_POST['premi'];
    $premi = str_replace(['.', ','], '', $premi); // Hapus format rupiah
    $newpremi = (float) $premi;

    $query = $db->prepare("INSERT INTO users (id, fullname, username, password, role, premi) VALUES (NULL, ?, ?, ?, 2, ?)");
    $query->bind_param('sssd', $fullname, $username, $password, $newpremi);

    if ($query->execute()) {
        header('Location: manage.user.admin.php');
        exit();
    } else {
        echo "Error: " . $query->error;
    }
}

// Edit / update user
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $premi = $_POST['premi'];
    $premi = str_replace(['.', ','], '', $premi); // Hapus format rupiah
    $premi = (float) $premi;

    $query = $db->prepare('UPDATE users SET fullname = ?, username = ?, password = ?, premi = ? WHERE id = ?');
    $query->bind_param('ssssd', $fullname, $username, $password, $premi, $id);
    $query->execute();
    header('location: manage.   user.admin.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/background.css">
    <link rel="stylesheet" href="card.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="icon" href="asset/BPS.png" type="image/x-icon">
</head>

<body class="bg-gray-100">
    <div class="flex">
        <section class="relative">
            <nav class="navbar h-screen mr-5">
                <?php include "layout/navbar.php"; ?>
            </nav>
        </section>

        <section class="flex-1">
            <div class="text-lg font-mulish-extend w-full p-5 justify-between flex shadow-md navbar">
                <h1>Manage User</h1>
                <h1><?php echo htmlspecialchars($_SESSION['username']); ?></h1>
            </div>


            <!-- table user -->
            <div class="overflow-x-auto mx-8 mt-4 border-b-2 rounded-lg">


                <div class="max-h relative overflow-y-auto no-scrollbar">
                    <table class="min-w-full rounded-lg">
                        <thead>
                            <tr class="bg-gradient text-white sticky top-0 z-10">
                                <th class="py-3 px-4 font-mulish text-center">#</th>
                                <th class="py-3 px-4 font-mulish text-center">Nama</th>
                                <th class="py-3 px-4 font-mulish text-center">Username</th>
                                <th class="py-3 px-4 font-mulish text-center">Password</th>
                                <th class="py-3 px-4 font-mulish text-center">Premi</th>
                                <th class="py-3 px-4 font-mulish text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($result as $index => $data) { ?>
                                <tr class="bg-white border-b hover:bg-gray-100 font-mulish">
                                    <td class="py-3 px-4 font-mulish text-center"><?php echo $index + 1; ?></td>
                                    <td class="py-3 px-4 font-mulish"><?php echo htmlspecialchars($data->fullname); ?></td>
                                    <td class="py-3 px-4 font-mulish"><?php echo htmlspecialchars($data->username); ?></td>
                                    <td class="py-3 px-4 font-mulish text-center"><?php echo htmlspecialchars($data->password); ?></td>
                                    <td class="py-3 px-4 font-mulish text-center"><?php echo "Rp " . number_format($data->premi, 0, ',', '.'); ?></td>
                                    <td class="py-3 px-4 font-mulish text-center flex justify-center">
                                        <a href="javascript:void(0);" onclick="openEditForm('<?php echo $data->id; ?>', '<?php echo htmlspecialchars($data->fullname); ?>', '<?php echo htmlspecialchars($data->username); ?>', '<?php echo htmlspecialchars($data->password); ?>', '<?php echo number_format($data->premi, 0, ',', '.'); ?>')" class="text-blue-600 hover:text-blue-800 font-semibold mr-3 font-mulish">Edit</a>
                                        <!-- Form Hapus -->
                                        <form action="" method="POST">
                                            <input type="hidden" name="delete_id" value="<?php echo $data->id; ?>">
                                            <button type="submit" class="text-red-500 font-mulish" name="delete" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="flex">
                    <a href="javascript:void(0);" class="w-1/2 font-mulish p-3 text-white bg-gradient-r text-center" onclick="create()">Tambah Data</a>
                    <a href="user.admin.php" class="w-1/2 font-mulish p-3 bg text-center text-white justify-center flex bg-gradient">Kembali</a>
                </div>
            </div>



            <!-- Form Edit Modal -->
            <div id="editForm" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-20">
                <div class="bg-white rounded-lg shadow-lg p-6 w-96">
                    <h2 class="text-2xl font-mulish-extend mb-4">Edit User</h2>
                    <form action="" method="POST">
                        <input type="hidden" name="id" id="userId">

                        <label for="fullname" class="block text-sm font-mulish text-gray-700">Nama</label>
                        <input type="text" name="fullname" id="fullname" class="w-full mt-1 p-2 border rounded-lg mb-4" required>

                        <label for="username" class="block text-sm font-mulish text-gray-700">Username</label>
                        <input type="text" name="username" id="username" class="w-full mt-1 p-2 border rounded-lg mb-4" required>

                        <label for="password" class="block text-sm font-mulish text-gray-700">Password</label>
                        <input type="password" name="password" id="password" class="w-full mt-1 p-2 border rounded-lg mb-4" required>

                        <label for="premi" class="block text-sm font-mulish text-gray-700">Premi</label>
                        <input type="text" name="premi" id="premi" class="w-full mt-1 p-2 border rounded-lg mb-4" required oninput="formatRupiah(this)">

                        <div class="flex justify-end">
                            <button type="button" onclick="closeEditForm()" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2 font-mulish">Cancel</button>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-mulish" name="update">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Form Create User -->
            <div id="add" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-20">
                <div class="bg-white rounded-lg shadow-lg p-6 w-96">
                    <h2 class="text-2xl font-mulish-extend mb-4">Tambah Data</h2>
                    <form action="" method="POST" class="font-mulish">
                        <label for="fullname" class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="fullname" id="fullname" class="w-full mt-1 p-2 border rounded-lg mb-4" required>

                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" name="username" id="username" class="w-full mt-1 p-2 border rounded-lg mb-4" required>

                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password" class="w-full mt-1 p-2 border rounded-lg mb-4" required>

                        <label for="premi" class="block text-sm font-medium text-gray-700">Premi</label>
                        <input type="text" name="premi" id="premi" class="w-full mt-1 p-2 border rounded-lg mb-4" required oninput="formatRupiah(this)">

                        <div class="flex justify-end">
                            <button type="button" onclick="closeForm()" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2 font-mulish">Cancel</button>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-mulish" name="create">Create</button>
                        </div>
                    </form>
                </div>
            </div>


        </section>
    </div>

    <script>
        // Fungsi untuk membuka form tambah data
        function create() {
            document.getElementById('add').classList.remove('hidden');
        }

        // Fungsi untuk membuka form edit
        function openEditForm(id, fullname, username, password, premi) {
            document.getElementById('editForm').classList.remove('hidden');
            document.getElementById('userId').value = id;
            document.getElementById('fullname').value = fullname;
            document.getElementById('username').value = username;
            document.getElementById('password').value = password;
            document.getElementById('premi').value = premi;
        }

        // Fungsi untuk menutup form edit
        function closeEditForm() {
            document.getElementById('editForm').classList.add('hidden');
        }

        // Fungsi untuk menghapus user
        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
                document.getElementById('delete_id').value = id;
                document.getElementById('deleteForm').submit();
            }
        }

        // Fungsi untuk format input Rupiah tanpa simbol Rp
        function formatRupiah(input) {
            let value = input.value.replace(/[^,\d]/g, '');
            let formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            input.value = formattedValue;
        }

        // Fungsi untuk menutup form tambah data
        function closeForm() {
            document.getElementById('add').classList.add('hidden');
        }
    </script>

    <style>
        .bg-gradient-r {
            background: rgb(125, 70, 253);
            background: linear-gradient(270deg, rgba(125, 70, 253, 1) 0%, rgba(253, 201, 145, 1) 100%);
        }
        .max-h {
            max-height: 29rem;
        }
    </style>
</body>

</html>
<?php
include "config/db.php";
// session_start();
// $login_message = "";

// if (isset($_SESSION["is_login"])) {
//   header("location: admin/dashboard.php");
// }

if (isset($_POST["login"])) {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $enc_password = hash("sha256", $password);

  $sql = "SELECT * FROM users WHERE username='$username' AND password='$enc_password'";

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();

    $_SESSION["username"] = $data["username"];
    $_SESSION["is_login"] = true;

    header("location: admin/dashboard.php");
  } else {
    $login_message = "Username atau Password salah!";
  }
  $conn->close();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - TopUp Game</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-950 text-white flex items-center justify-center min-h-screen">

    <div class="max-w-md w-full bg-gray-900 rounded-2xl shadow-xl p-8">
        <h3 class="text-3xl font-bold text-center text-blue-400 mb-6">Login Akun</h3>
        <form class="space-y-6" action="login.php" method="POST">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-300 mb-2">Email / Username</label>
                <input type="text" name="username" id="username" placeholder="Masukkan email atau username"
                    class="w-full px-4 py-3 rounded-lg bg-gray-800 border border-gray-700 text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-600" />
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                <input type="password" name="password" id="password" placeholder="Masukkan password"
                    class="w-full px-4 py-3 rounded-lg bg-gray-800 border border-gray-700 text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-600" />
            </div>

            <button type="submit" name="login"
                class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg text-white font-semibold shadow-lg transition">
                Login
            </button>
        </form>

        <p class="mt-6 text-center text-gray-400">Belum punya akun? <a href="#"
                class="text-blue-400 hover:underline">Daftar sekarang</a></p>
        <p class="mt-2 text-center"><a href="index.php" class="text-sm text-gray-400 hover:text-blue-400">← Kembali ke
                Beranda</a></p>
    </div>

</body>

</html>
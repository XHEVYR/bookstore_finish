<?php
session_start(); // Memulai session PHP untuk menyimpan status login admin
include('config.php'); // Memasukkan file konfigurasi database

// Memeriksa apakah form login telah dikirim
if (isset($_POST['login'])) {
    $username = $_POST['username']; // Mengambil nilai username dari form
    $password = $_POST['password']; // Mengambil nilai password dari form

    // Memeriksa kredensial login admin
    if ($username == 'admin' && $password == '123') {
        $_SESSION['admin_logged_in'] = true; // Menyimpan status login admin di session
        header('Location: admin_books.php'); // Redirect ke halaman admin_books.php setelah login berhasil
        exit;
    } else {
        $error = "Username atau password salah."; // Pesan error jika username atau password salah
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="styles.css"> <!-- Memuat file CSS untuk styling -->
</head>
<body>
    <header>
        <h1>Login Admin</h1>
    </header>
    <main>
        <form method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required> <!-- Input username -->
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required> <!-- Input password -->
            <button type="submit" name="login">Login</button> <!-- Tombol untuk mengirimkan form login -->
        </form>
        <!-- Menampilkan pesan error jika login gagal -->
        <?php if (isset($error)): ?>
            <p><?php echo $error; ?></p>
        <?php endif; ?>
    </main>
    <script src="script.js"></script> <!-- Memuat file JavaScript jika diperlukan -->
</body>
</html>

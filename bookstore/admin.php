<?php
session_start(); // Memulai atau melanjutkan session yang sedang berjalan

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php'); // Redirect ke halaman login jika admin belum login
    exit; // Menghentikan eksekusi script untuk memastikan tidak ada kode lain yang dijalankan
}

include('config.php'); // Menyertakan file konfigurasi database
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Menentukan set karakter untuk dokumen HTML -->
    <title>Admin Toko Buku</title> <!-- Judul halaman HTML -->
    <link rel="stylesheet" href="styles.css"> <!-- Menyertakan file CSS untuk styling halaman -->
</head>
<body>
    <header>
        <h1>Selamat Datang Admin</h1> <!-- Judul yang ditampilkan pada halaman admin -->
        <nav>
            <ul>
                <li><a href="admin_books.php">Data Buku</a></li> <!-- Link ke halaman Data Buku -->
                <li><a href="admin_payments.php">Data Pembayaran</a></li> <!-- Link ke halaman Data Pembayaran -->
                <li><a href="admin_customers.php">Data Pelanggan</a></li> <!-- Link ke halaman Data Pelanggan -->
                <li><a href="logout.php">Logout</a></li> <!-- Link untuk logout -->
            </ul>
        </nav>
    </header>
    <main>
        <!-- Konten halaman admin akan ditampilkan di sini -->
    </main>
</body>
</html>

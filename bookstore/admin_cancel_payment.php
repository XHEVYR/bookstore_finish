<?php
session_start();
include('config.php'); // Memasukkan file untuk konfigurasi database

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php'); // Jika admin belum login, redirect ke halaman login admin
    exit;
}

// Memastikan bahwa parameter 'id' telah diterima melalui GET request
if (!isset($_GET['id'])) {
    header('Location: admin_payments.php'); // Jika tidak ada parameter 'id', redirect ke halaman pembayaran admin
    exit;
}

$order_id = $_GET['id']; // Mendapatkan nilai 'id' dari parameter GET

// Batalkan pembayaran dengan mengubah status order menjadi 'Cancelled'
$query = "UPDATE orders SET status = 'Cancelled' WHERE id = $order_id";
mysqli_query($conn, $query); // Menjalankan query untuk membatalkan pembayaran

header('Location: admin_payments.php'); // Redirect ke halaman pembayaran admin setelah berhasil membatalkan
exit;
?>
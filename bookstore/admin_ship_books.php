<?php
session_start(); // Memulai atau melanjutkan session yang sedang berlangsung
include('config.php'); // Menyertakan file konfigurasi database

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php'); // Redirect ke halaman login jika admin belum login
    exit; // Menghentikan eksekusi script
}

// Cek apakah ada parameter 'id' dalam URL
if (!isset($_GET['id'])) {
    header('Location: admin_payments.php'); // Redirect ke halaman data pembayaran jika 'id' tidak ada
    exit; // Menghentikan eksekusi script
}

$order_id = $_GET['id']; // Mendapatkan nilai 'id' dari URL

// Mengubah status pesanan menjadi 'Shipped' di tabel orders
$query = "UPDATE orders SET status = 'Shipped' WHERE id = $order_id";
mysqli_query($conn, $query); // Menjalankan query untuk mengubah status pesanan

// Mengubah status pesanan menjadi 'Terkirim' di tabel order_history
$query_update_status = "UPDATE order_history SET status = 'Terkirim' WHERE order_id = $order_id";
mysqli_query($conn, $query_update_status); // Menjalankan query untuk mengubah status riwayat pesanan

// Redirect ke halaman data pembayaran
header('Location: admin_payments.php');
exit; // Menghentikan eksekusi script
?>

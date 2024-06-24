<?php
session_start(); // Memulai session PHP untuk memastikan session yang sedang berjalan dapat diakses

session_unset(); // Menghapus semua variabel session
session_destroy(); // Menghancurkan session yang sedang berjalan

header("Location: admin_login.php"); // Redirect ke halaman login admin setelah logout
exit; // Menghentikan eksekusi script untuk memastikan tidak ada kode lain yang dijalankan
?>

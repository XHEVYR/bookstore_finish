<?php
session_start();
include('config.php'); // Memasukkan file konfigurasi database

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php'); // Jika admin belum login, redirect ke halaman login admin
    exit;
}

// Ambil data pelanggan dari database
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);
$customers = mysqli_fetch_all($result, MYSQLI_ASSOC); // Menyimpan hasil query dalam bentuk array asosiatif
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Pelanggan - Admin</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: central;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <header>
        <h1>Data Pelanggan</h1>
        <nav>
            <ul>
                <li><a href="admin_books.php">Data Buku</a></li>
                <li><a href="admin_payments.php">Data Pembayaran</a></li>
                <li><a href="admin_customers.php">Data Pelanggan</a></li>
                <li><a href="admin_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Daftar Pelanggan</h2>
        <?php if (!empty($customers)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Password</th> <!-- Catatan: Sebaiknya tidak menampilkan password tanpa enkripsi di aplikasi nyata -->
                        <th>Email</th>
                        <th>Nomor Telepon</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td><?php echo $customer['name']; ?></td>
                            <td><?php echo $customer['username']; ?></td>
                            <td><?php echo $customer['password']; ?></td> <!-- Menampilkan password tanpa enkripsi -->
                            <td><?php echo $customer['email']; ?></td>
                            <td><?php echo $customer['phone']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada pelanggan yang terdaftar.</p>
        <?php endif; ?>
    </main>
    <script src="script.js"></script> <!-- Jika ada script JavaScript yang diperlukan -->
</body>
</html>

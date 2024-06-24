<?php
session_start();
include('config.php'); // file untuk konfigurasi database

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM orders WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pembelian</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Riwayat Pembelian</h1>
        <nav>
            <ul>
                <li class="active"><a href="index.php">Rekomendasi Buku</a></li>
                <li class="active"><a href="cart.php">Keranjang</a></li>
                <li class="active"><a href="payment.php">Pembayaran</a></li>
                <li class="active"><a href="history.php">Riwayat</a></li>
                <li class="active"><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Riwayat Pembelian Anda</h2>
        <?php if (!empty($orders)): ?><br>
            <ul>
                <?php foreach ($orders as $order): ?>
                    <li>
                        <h3>Order ID: <?php echo $order['id']; ?></h3>
                        <p>Total Harga: Rp <?php echo $order['total_price']; ?></p>
                        <p>Alamat: <?php echo $order['address']; ?></p>
                        <p>Status: <?php echo $order['status']; ?></p>
                        <p>Tanggal: <?php echo $order['created_at']; ?></p><br>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Anda belum melakukan pembelian.</p>
        <?php endif; ?>
    </main>
    <script src="script.js"></script>
</body>
</html>

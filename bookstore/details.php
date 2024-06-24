<?php
session_start();
include('config.php'); // file untuk konfigurasi database

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$book_id = $_GET['id'];
$query = "SELECT * FROM books WHERE id = $book_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "Buku tidak ditemukan.";
    exit;
}

$book = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detail Buku</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1><?php echo $book['title']; ?></h1>
        <nav>
            <ul>
                <li><a href="index.php">Rekomendasi Buku</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="cart.php">Keranjang</a></li>
                    <li><a href="payment.php">Pembayaran</a></li>
                    <li><a href="history.php">Riwayat</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
        <div class="book-detail">
            <img src="<?php echo $book['image']; ?>" alt="<?php echo $book['title']; ?>">
            <div class="book-info">
                <h2><?php echo $book['title']; ?></h2>
                <p><strong>Penerbit:</strong> <?php echo $book['author']; ?></p>
                <p><strong>Mata Pelajaran:</strong> <?php echo $book['genre']; ?></p>
                <p><strong>Stock:</strong> <?php echo $book['stock']; ?></p>
                <p><strong>Harga:</strong> Rp <?php echo $book['price']; ?></p>
                <p><?php echo $book['description']; ?></p>
                <form action="cart.php" method="GET">
                    <input type="hidden" name="add_to_cart" value="<?php echo $book['id']; ?>">
                    <label for="quantity">Jumlah:</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $book['stock']; ?>">
                    <button type="submit">Tambah ke Keranjang</button>
                </form>
            </div>
        </div>
    </main>
    <script src="script.js"></script>
</body>
</html>

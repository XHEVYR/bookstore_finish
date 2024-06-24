<?php
session_start();
include('config.php'); // file untuk konfigurasi database

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Ambil detail buku dari keranjang
$cart_books = array();
$total_price = 0;
if (!empty($_SESSION['cart'])) {
    $cart_ids = implode(',', array_keys($_SESSION['cart']));
    $query = "SELECT * FROM books WHERE id IN ($cart_ids)";
    $result = mysqli_query($conn, $query);
    while ($book = mysqli_fetch_assoc($result)) {
        $book['quantity'] = $_SESSION['cart'][$book['id']];
        $book['subtotal'] = $book['price'] * $book['quantity'];
        $total_price += $book['subtotal'];
        $cart_books[] = $book;
    }
}

// Proses pembayaran
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $address = $_POST['address'];
    $total = $total_price;
    $status = 'Pending';

    $query = "INSERT INTO orders (user_id, total_price, address, status) VALUES ('$user_id', '$total', '$address', '$status')";
    mysqli_query($conn, $query);
    $order_id = mysqli_insert_id($conn);

    foreach ($cart_books as $book) {
        $book_id = $book['id'];
        $quantity = $book['quantity'];
        $price = $book['price'];
        $subtotal = $book['subtotal'];
    
        $query = "INSERT INTO order_items (order_id, book_id, quantity, price, subtotal) VALUES ('$order_id', '$book_id', '$quantity', '$price', '$subtotal')";
        mysqli_query($conn, $query);
    
        // Decrease the stock of the book
        $query = "UPDATE books SET stock = stock - $quantity WHERE id = $book_id";
        mysqli_query($conn, $query);
    }    

    unset($_SESSION['cart']);
    header('Location: history.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran</title>
    <style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
    </style>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Pembayaran</h1>
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
    <h2>Rincian Pembayaran</h2>
    <?php if (!empty($cart_books)): ?>
        <!-- Add a table to display the books in the cart -->
        <table>
            <tr>
                <th>Judul</th>
                <th>Harga per Buku</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
            <?php foreach ($cart_books as $book): ?>
                <tr>
                    <td><?php echo $book['title']; ?></td>
                    <td>Rp <?php echo $book['price']; ?></td>
                    <td><?php echo $book['quantity']; ?></td>
                    <td>Rp <?php echo $book['subtotal']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <h3>Total Harga: Rp <?php echo $total_price; ?></h3>
        <form method="POST"><br>
            <label for="address">Alamat Pengiriman:</label>
            <textarea id="address" name="address" required></textarea><br>
            <button type="submit">Bayar</button>
        </form>
        <br>
        <button><a href="https://wa.me/6285851400116" target="_blank">Kirim Nota Pembayaran via WhatsApp</a></button>
    <?php else: ?>
        <p>Rincian Pembayaran anda kosong.</p>
    <?php endif; ?><br>
    <button><a href="index.php">Kembali Ke Halaman Utama</a></button>
</main>
    <script src="script.js"></script>
</body>
</html>

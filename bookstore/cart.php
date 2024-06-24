<?php
session_start(); // Memulai atau melanjutkan session yang sedang berjalan

include('config.php'); // Menyertakan file konfigurasi database

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect ke halaman login jika user belum login
    exit; // Menghentikan eksekusi script untuk memastikan tidak ada kode lain yang dijalankan
}

// Tambahkan item ke keranjang
if (isset($_GET['add_to_cart']) && isset($_GET['quantity'])) {
    $book_id = $_GET['add_to_cart']; // Mengambil ID buku dari URL
    $quantity = intval($_GET['quantity']); // Mengambil dan mengubah kuantitas menjadi integer
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array(); // Inisialisasi keranjang jika belum ada
    }
    
    if (isset($_SESSION['cart'][$book_id])) {
        $_SESSION['cart'][$book_id] += $quantity; // Tambahkan kuantitas jika buku sudah ada di keranjang
    } else {
        $_SESSION['cart'][$book_id] = $quantity; // Tambahkan buku ke keranjang dengan kuantitas tertentu
    }
    $message = "Buku telah dimasukkan di keranjang"; // Pesan konfirmasi
}

// Hapus item dari keranjang
if (isset($_GET['remove_from_cart'])) {
    $book_id = $_GET['remove_from_cart']; // Mengambil ID buku dari URL untuk dihapus
    if (isset($_SESSION['cart'][$book_id])) {
        unset($_SESSION['cart'][$book_id]); // Hapus buku dari keranjang
    }
}

// Ambil detail buku dari keranjang
$cart_books = array();
$total_price = 0;
if (!empty($_SESSION['cart'])) {
    $cart_ids = implode(',', array_keys($_SESSION['cart'])); // Menggabungkan ID buku menjadi string untuk query
    $query = "SELECT * FROM books WHERE id IN ($cart_ids)"; // Query untuk mendapatkan detail buku
    $result = mysqli_query($conn, $query);
    while ($book = mysqli_fetch_assoc($result)) {
        $book['quantity'] = $_SESSION['cart'][$book['id']]; // Menambahkan kuantitas buku dari session ke array buku
        $book['total_price'] = $book['price'] * $book['quantity']; // Menghitung total harga per buku
        $total_price += $book['total_price']; // Menambahkan ke total harga keseluruhan
        $cart_books[] = $book; // Menambahkan buku ke array keranjang
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Menentukan set karakter untuk dokumen HTML -->
    <title>Keranjang</title> <!-- Judul halaman HTML -->
    <link rel="stylesheet" href="styles.css"> <!-- Menyertakan file CSS untuk styling halaman -->
</head>
<body>
    <header>
        <h1>Keranjang</h1> <!-- Judul yang ditampilkan pada halaman keranjang -->
        <nav>
            <ul>
                <li class="active"><a href="index.php">Rekomendasi Buku</a></li> <!-- Link ke halaman rekomendasi buku -->
                <li class="active"><a href="cart.php">Keranjang</a></li> <!-- Link ke halaman keranjang -->
                <li class="active"><a href="payment.php">Pembayaran</a></li> <!-- Link ke halaman pembayaran -->
                <li class="active"><a href="history.php">Riwayat</a></li> <!-- Link ke halaman riwayat -->
                <li class="active"><a href="logout.php">Logout</a></li> <!-- Link untuk logout -->
            </ul>
        </nav>
    </header>
    <main>
        <h2>Keranjang Belanja</h2> <!-- Sub-judul untuk halaman keranjang belanja -->
        <?php if (!empty($cart_books)): ?>
            <ul>
                <?php foreach ($cart_books as $book): ?>
                    <li>
                        <img src="<?php echo $book['image']; ?>" alt="<?php echo $book['title']; ?>"> <!-- Gambar buku -->
                        <h3><?php echo $book['title']; ?></h3> <!-- Judul buku -->
                        <p>Total Harga Keseluruhan: Rp <?php echo $total_price; ?></p> <!-- Total harga keseluruhan -->
                        <p>Jumlah: <?php echo $book['quantity']; ?></p><br> <!-- Jumlah buku yang dibeli -->
                        <button><a href="cart.php?remove_from_cart=<?php echo $book['id']; ?>">Hapus</a></button> <!-- Tombol untuk menghapus buku dari keranjang -->
                    </li>
                <?php endforeach; ?>
            </ul>
            <button><a href="payment.php">Lanjutkan ke Pembayaran</a></button> <!-- Tombol untuk lanjut ke pembayaran -->
        <?php else: ?>
            <p>Keranjang anda kosong.</p> <!-- Pesan jika keranjang kosong -->
        <?php endif; ?><br>
            <button><a href="index.php">Kembali Ke Halaman Utama</a></button> <!-- Tombol untuk kembali ke halaman utama -->
    </main>
    <script src="script.js"></script> <!-- Menyertakan file JavaScript jika ada -->
</body>
</html>

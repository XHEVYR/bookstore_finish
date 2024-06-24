<?php
session_start();
include('config.php'); // file untuk konfigurasi database

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

// Tambah buku baru
if (isset($_POST['add_book'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']); // Menghindari SQL injection
    $author = mysqli_real_escape_string($conn, $_POST['author']); // Menghindari SQL injection
    $genre = mysqli_real_escape_string($conn, $_POST['genre']); // Menghindari SQL injection
    $stock = mysqli_real_escape_string($conn, $_POST['stock']); // Menghindari SQL injection
    $price = mysqli_real_escape_string($conn, $_POST['price']); // Menghindari SQL injection
    $description = mysqli_real_escape_string($conn, $_POST['description']); // Menghindari SQL injection
    $image = mysqli_real_escape_string($conn, $_POST['image']); // Menghindari SQL injection

    // Query untuk menambahkan buku baru ke dalam database
    $query = "INSERT INTO books (title, author, genre, stock, price, description, image) 
              VALUES ('$title', '$author', '$genre', '$stock', '$price', '$description', '$image')";
    mysqli_query($conn, $query); // Menjalankan query
    header('Location: admin_books.php'); // Redirect ke halaman daftar buku admin
    exit;
}

// Hapus buku
if (isset($_GET['delete_book'])) {
    $book_id = mysqli_real_escape_string($conn, $_GET['delete_book']); // Menghindari SQL injection

    // Query untuk menghapus buku berdasarkan ID
    $query = "DELETE FROM books WHERE id = $book_id";
    mysqli_query($conn, $query); // Menjalankan query
    header('Location: admin_books.php'); // Redirect ke halaman daftar buku admin
    exit;
}

// Ambil data buku
$query = "SELECT * FROM books";
$result = mysqli_query($conn, $query);
$books = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Buku - Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Data Buku</h1>
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
        <h2>Tambah Buku Baru</h2><br>
        <form method="POST">
            <label for="title">Judul:</label>
            <input type="text" id="title" name="title" required> <!-- Input judul buku -->
            <label for="author">Penerbit:</label>
            <input type="text" id="author" name="author" required> <!-- Input penerbit buku -->
            <label for="genre">Mata Pelajaran:</label>
            <input type="text" id="genre" name="genre" required> <!-- Input genre atau mata pelajaran buku -->
            <label for="stock">Stok:</label>
            <input type="number" id="stock" name="stock" required> <!-- Input stok buku -->
            <label for="price">Harga:</label>
            <input type="number" id="price" name="price" required><br><br> <!-- Input harga buku -->
            <label for="description">Deskripsi:</label>
            <textarea id="description" name="description" required></textarea> <!-- Input deskripsi buku -->
            <label for="image">URL Gambar:</label>
            <textarea type="text" id="image" name="image"></textarea> <!-- Input URL gambar buku -->
            <button type="submit" name="add_book">Tambah Buku</button><br><br><br> <!-- Tombol untuk menambahkan buku -->
        </form>

        <h2>Daftar Buku</h2><br>
        <?php if (!empty($books)): ?>
            <ul>
                <?php foreach ($books as $book): ?>
                    <li>
                        <img src="<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" width="200"> <!-- Menampilkan gambar buku -->
                        <h3><?php echo htmlspecialchars($book['title']); ?></h3><br> <!-- Menampilkan judul buku -->
                        <p>Penerbit: <?php echo htmlspecialchars($book['author']); ?></p> <!-- Menampilkan penerbit buku -->
                        <p>Mata Pelajaran: <?php echo htmlspecialchars($book['genre']); ?></p> <!-- Menampilkan genre atau mata pelajaran buku -->
                        <p>Stok: <?php echo htmlspecialchars($book['stock']); ?></p> <!-- Menampilkan stok buku -->
                        <p>Harga: Rp <?php echo htmlspecialchars($book['price']); ?></p> <!-- Menampilkan harga buku -->
                        <p><?php echo htmlspecialchars($book['description']); ?></p><br> <!-- Menampilkan deskripsi buku -->
                        <a href="admin_edit_book.php?id=<?php echo $book['id']; ?>" class="action-button">Edit</a> <!-- Tombol edit untuk mengedit buku -->
                        <a href="admin_books.php?delete_book=<?php echo $book['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')" class="action-button">Hapus</a><br><br> <!-- Tombol hapus untuk menghapus buku -->
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Tidak ada buku yang tersedia.</p> <!-- Pesan jika tidak ada buku yang tersedia -->
        <?php endif; ?>
    </main>
    <script src="script.js"></script> <!-- Memuat script JavaScript jika diperlukan -->
</body>
</html>
  
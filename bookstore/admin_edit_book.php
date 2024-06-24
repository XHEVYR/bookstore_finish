<?php
session_start();
include('config.php'); // Memasukkan file konfigurasi database

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php'); // Redirect ke halaman login jika admin belum login
    exit;
}

// Memastikan parameter 'id' ada dalam request GET
if (!isset($_GET['id'])) {
    header('Location: admin_books.php'); // Redirect ke halaman data buku jika tidak ada 'id' yang diberikan
    exit;
}

$book_id = $_GET['id']; // Mengambil nilai 'id' buku dari parameter GET

// Ambil data buku dari database berdasarkan ID yang diberikan
$query = "SELECT * FROM books WHERE id = $book_id";
$result = mysqli_query($conn, $query);

// Memeriksa apakah buku dengan ID yang diberikan ditemukan
if (mysqli_num_rows($result) == 0) {
    echo "Buku tidak ditemukan."; // Pesan error jika buku tidak ditemukan
    exit;
}

$book = mysqli_fetch_assoc($result); // Menyimpan data buku dalam bentuk array asosiatif

// Update buku jika form dikirimkan
if (isset($_POST['update_book'])) {
    // Mengambil nilai dari form untuk memperbarui data buku
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_POST['image'];

    // Query untuk melakukan update data buku dalam database
    $query = "UPDATE books SET title = '$title', author = '$author', genre = '$genre', stock = '$stock', price = '$price', description = '$description', image = '$image' WHERE id = $book_id";
    mysqli_query($conn, $query); // Eksekusi query untuk melakukan update

    header('Location: admin_books.php'); // Redirect kembali ke halaman data buku setelah berhasil memperbarui
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Buku - Admin</title>
    <link rel="stylesheet" href="styles.css"> <!-- Memuat file CSS untuk styling -->
</head>
<body>
    <header>
        <h1>Edit Buku</h1>
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
        <h2>Edit Buku</h2>
        <form method="POST">
            <label for="title">Judul:</label>
            <input type="text" id="title" name="title" value="<?php echo $book['title']; ?>" required> <!-- Input judul buku -->
            <label for="author">Penulis:</label>
            <input type="text" id="author" name="author" value="<?php echo $book['author']; ?>" required> <!-- Input penulis buku -->
            <label for="genre">Genre:</label>
            <input type="text" id="genre" name="genre" value="<?php echo $book['genre']; ?>" required> <!-- Input genre buku -->
            <label for="stock">Stok:</label>
            <input type="number" id="stock" name="stock" value="<?php echo $book['stock']; ?>" required> <!-- Input stok buku -->
            <label for="price">Harga:</label>
            <input type="number" id="price" name="price" value="<?php echo $book['price']; ?>" required> <!-- Input harga buku -->
            <label for="description">Deskripsi:</label>
            <textarea id="description" name="description" required><?php echo $book['description']; ?></textarea> <!-- Input deskripsi buku -->
            <label for="image">URL Gambar:</label>
            <input type="text" id="image" name="image" value="<?php echo $book['image']; ?>" required> <!-- Input URL gambar buku -->
            <button type="submit" name="update_book">Update Buku</button> <!-- Tombol untuk mengirimkan form update -->
        </form>
    </main>
    <script src="script.js"></script> <!-- Memuat file JavaScript jika diperlukan -->
</body>
</html>

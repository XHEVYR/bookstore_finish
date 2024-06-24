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
<html>

    <head>

    <title>Pencarian</title>

    </head>

</html>
<body>
    
    <body>
    <div id="container">
        <div id="header">
        <div class="headerBackground">
            <h1><font color="#FF33B5"><br><br><b>&nbsp;&nbsp;CV DUA PUTRA </font></b></h1>
        </div>
        </div>
    <body>

    <form action="pencarian.php" method="get" class="navbar-form navbar-right">
				<input type="text" name="keyword" class="form-control" placeholder="Pencarian">
				<button class="btn btn-primary">Cari</button>
			</form>
</body>
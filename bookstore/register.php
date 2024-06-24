<?php
session_start();
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Periksa apakah username sudah ada
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $error = "Username sudah ada. Silakan pilih username lain.";
    } else {
        // Masukkan data pengguna baru ke dalam database
        $query = "INSERT INTO users (username, password, name, email, phone) VALUES ('$username', '$password', '$name', '$email', '$phone')";
        if (mysqli_query($conn, $query)) {
            header('Location: login.php');
            exit;
        } else {
            $error = "Terjadi kesalahan saat mendaftarkan pengguna. Silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Register</h1>
    </header>
    <main>
        <form action="register.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <label for="name">Nama:</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="phone">Telepon:</label>
            <input type="text" id="phone" name="phone" required>
            <button type="submit">Register</button>
            <?php if (isset($error)): ?>
                <p><?php echo $error; ?></p>
            <?php endif; ?>
        </form>
        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </main>
</body>
</html>

<?php
session_start(); // Memulai atau melanjutkan session yang sedang berlangsung
include('config.php'); // Menyertakan file konfigurasi database

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php'); // Redirect ke halaman login jika belum login
    exit; // Menghentikan eksekusi script
}

// Ambil data pembayaran dengan menggabungkan tabel orders dan users
$query = "SELECT orders.*, users.username FROM orders INNER JOIN users ON orders.user_id = users.id";
$result = mysqli_query($conn, $query); // Eksekusi query
$payments = mysqli_fetch_all($result, MYSQLI_ASSOC); // Mengambil semua hasil query dalam bentuk array asosiatif
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Pembayaran - Admin</title>
    <link rel="stylesheet" href="styles.css"> <!-- Menyertakan stylesheet -->
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
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
    <h1>Data Pembayaran</h1>
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
    <h2>Daftar Pembayaran</h2>
    <?php if (!empty($payments)): ?> <!-- Cek apakah ada pembayaran -->
        <table>
            <thead>
            <tr>
                <th>Order ID</th>
                <th>Nama Pelanggan</th>
                <th>Total Harga</th>
                <th>Alamat</th>
                <th>Status</th>
                <th>Nota Pembayaran</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($payments as $payment): ?> <!-- Looping untuk setiap pembayaran -->
                <tr>
                    <td><?php echo $payment['id']; ?></td>
                    <td><?php echo $payment['username']; ?></td>
                    <td>Rp <?php echo $payment['total_price']; ?></td>
                    <td><?php echo $payment['address']; ?></td>
                    <td id="status-<?php echo $payment['id']; ?>"><?php echo $payment['status']; ?></td>
                    <td>
                        <p id="nota-<?php echo $payment['id']; ?>" class="nota">
                            <?php echo ($payment['status'] === 'Shipped') ? 'Ada nota pembayaran' : 'Tidak ada nota pembayaran'; ?>
                        </p>
                    </td>
                    <td id="actions-<?php echo $payment['id']; ?>">
                        <?php if ($payment['status'] == 'Pending'): ?> <!-- Opsi aksi berdasarkan status -->
                            <a href="#" class="cancel-button" data-id="<?php echo $payment['id']; ?>" onclick="cancelPayment(<?php echo $payment['id']; ?>)">Batalkan Pembayaran</a> atau<br>
                            <a href="#" class="ship-button" data-id="<?php echo $payment['id']; ?>" onclick="shipOrder(<?php echo $payment['id']; ?>)">Kirim</a>
                        <?php elseif ($payment['status'] == 'Paid'): ?>
                            <a href="#" class="ship-button" data-id="<?php echo $payment['id']; ?>" onclick="shipOrder(<?php echo $payment['id']; ?>)">Kirim Buku</a>
                        <?php elseif ($payment['status'] == 'Shipped'): ?>
                            Shipped
                        <?php elseif ($payment['status'] == 'Cancelled'): ?>
                            Cancelled
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Tidak ada pembayaran yang tersedia.</p> <!-- Pesan jika tidak ada pembayaran -->
    <?php endif; ?>
</main>
<script>
    // Function untuk mengirimkan order
    function shipOrder(orderId) {
        const confirmation = confirm('Apakah Anda yakin ingin mengirimkan buku ini?');
        if (confirmation) {
            // Simpan perubahan status di database (contoh implementasi)
            updateOrderStatus(orderId, 'Shipped');
        }
    }

    // Function untuk membatalkan pembayaran
    function cancelPayment(orderId) {
        const confirmation = confirm('Apakah Anda yakin ingin membatalkan pembayaran ini?');
        if (confirmation) {
            // Simpan perubahan status di database (contoh implementasi)
            updateOrderStatus(orderId, 'Cancelled');
        }
    }

    // Function untuk update status order dan nota pembayaran
    function updateOrderStatus(orderId, status) {
        const xhr = new XMLHttpRequest(); // Membuat objek XMLHttpRequest
        xhr.open('POST', 'update_order_status.php'); // Membuka koneksi POST ke update_order_status.php
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Set header untuk tipe konten
        xhr.onload = function() { // Fungsi callback ketika permintaan selesai
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText); // Parse JSON response
                if (response.success) {
                    // Update UI
                    const notaElement = document.getElementById(`nota-${orderId}`);
                    if (notaElement) {
                        notaElement.textContent = (status === 'Shipped') ? 'Ada nota pembayaran' : 'Tidak ada nota pembayaran';
                    }
                    const statusElement = document.querySelector(`#status-${orderId}`);
                    if (statusElement) {
                        statusElement.textContent = (status === 'Shipped') ? 'Shipped' : 'Cancelled';
                    }
                    const actionElement = document.querySelector(`#actions-${orderId}`);
                    if (actionElement) {
                        actionElement.innerHTML = ''; // Mengosongkan tombol aksi setelah di-klik
                    }
                    console.log('Status order diperbarui.');
                } else {
                    console.error('Gagal memperbarui status order.');
                }
            } else {
                console.error('Terjadi kesalahan saat memperbarui status order.');
            }
        };
        xhr.send(`orderId=${orderId}&status=${status}`); // Mengirim data orderId dan status ke server
    }
</script>
</body>
</html>

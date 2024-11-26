<?php
include '../../koneksi.php';
session_start();

// Pastikan pengguna telah login
if (!isset($_SESSION['id_user'])) {
    header('Location: ../../login.php'); // Arahkan ke halaman login jika belum login
    exit;
}

// Pastikan ada data transaksi yang dikirim melalui POST
if (!isset($_POST['id_transaksi'])) {
    header('Location: ../forms/user/transaction.php?error=invalid_data'); // Arahkan kembali dengan error
    exit;
}

$id_transaksi = $_POST['id_transaksi'];
$id_user = $_SESSION['id_user'];

// Query untuk memastikan transaksi milik pengguna yang sedang login
$query = "SELECT * FROM transaksi WHERE id_transaksi = ? AND id_user = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("ii", $id_transaksi, $id_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: ../forms/user/transaction.php?error=not_found'); // Arahkan kembali jika tidak ditemukan
    exit;
}

// Query untuk menghapus transaksi
$delete_query = "DELETE FROM transaksi WHERE id_transaksi = ?";
$delete_stmt = $koneksi->prepare($delete_query);
$delete_stmt->bind_param("i", $id_transaksi);

if ($delete_stmt->execute()) {
    // Jika berhasil, arahkan kembali ke halaman transaksi
    header('Location: ../forms/user/transaction.php?status=cancel_success');
    exit;
} else {
    // Jika gagal, arahkan kembali dengan pesan error
    header('Location: ../forms/user/transaction.php?error=cancel_failed');
    exit;
}

// Menutup koneksi
$delete_stmt->close();
$stmt->close();
$koneksi->close();
?>

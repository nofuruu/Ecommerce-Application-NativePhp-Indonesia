<?php
include '../../koneksi.php'; // Path ke koneksi database
session_start(); // Mulai session untuk menyimpan pesan

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_transaksi = $_POST['id_transaksi'];

    // Validasi apakah `id_transaksi` ada
    if (empty($id_transaksi)) {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'ID Transaksi tidak boleh kosong!'];
        header("Location: ../pages/transaction.php"); // Redirect kembali ke halaman transaction.php
        exit;
    }

    // Query untuk update status transaksi menjadi 'sukses'
    $status = 'sukses';
    $query = "UPDATE transaksi SET status = ? WHERE id_transaksi = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("si", $status, $id_transaksi);

    if ($stmt->execute()) {
        // Jika sukses, simpan pesan sukses ke session
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Status transaksi berhasil diperbarui menjadi "sukses".'];
    } else {
        // Jika gagal, simpan pesan error ke session
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal memperbarui status transaksi: ' . $stmt->error];
    }

    $stmt->close();
} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Akses tidak valid!'];
}

// Redirect kembali ke halaman transaction.php
header("Location: ../forms/user/transaction.php");
$koneksi->close();
?>

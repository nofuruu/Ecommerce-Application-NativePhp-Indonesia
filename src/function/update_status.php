<?php
include '../../koneksi.php'; // Sesuaikan dengan path koneksi database Anda

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_transaksi = $_POST['id_transaksi'];
    $status = $_POST['status'];

    // Validasi input
    if (empty($id_transaksi) || empty($status)) {
        // Redirect ke halaman transaction.php dengan pesan error
        header("Location: ../forms/user/transaction.php?status=error&message=ID Transaksi atau Status tidak boleh kosong!");
        exit;
    }

    // Query untuk mengupdate status transaksi menjadi 'sukses'
    $query = "UPDATE transaksi SET status = ? WHERE id_transaksi = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("si", $status, $id_transaksi);

    // Eksekusi query
    if ($stmt->execute()) {
        // Redirect ke halaman transaction.php dengan pesan sukses
        header("Location: ../forms/user/transaction.php?status=success&message=Status berhasil diperbarui!");
        exit;
    } else {
        // Redirect ke halaman transaction.php dengan pesan error
        header("Location: ../forms/user/transaction.php?status=error&message=Gagal memperbarui status: " . urlencode($stmt->error));
        exit;
    }

    $stmt->close();
} else {
    // Redirect ke halaman transaction.php jika akses tidak valid
    header("Location: ../forms/user/transaction.php?status=error&message=Akses tidak valid!");
    exit;
}

$koneksi->close();
?>

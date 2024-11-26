<?php
// Sertakan file koneksi ke database
include '../../../koneksi.php'; // Sesuaikan path dengan struktur folder Anda

header('Content-Type: application/json'); // Mengatur tipe respons sebagai JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id_transaksi = $_POST['id_transaksi'];
    $status = $_POST['status'];

    // Validasi input
    if (empty($id_transaksi) || empty($status)) {
        echo json_encode(["status" => "error", "message" => "ID Transaksi atau Status tidak boleh kosong!"]);
        exit;
    }

    // Query untuk mengupdate status berdasarkan ID Transaksi
    $query = "UPDATE transaksi SET status = ? WHERE id_transaksi = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("si", $status, $id_transaksi);

    // Eksekusi query
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Status berhasil diperbarui!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal memperbarui status: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Akses tidak valid!"]);
}

$koneksi->close();
?>

<?php
include '../../koneksi.php'; // Sesuaikan dengan path koneksi database Anda
header('Content-Type: application/json'); // Mengatur respons menjadi JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mendapatkan id_transaksi dan file bukti pembayaran
    $id_transaksi = $_POST['id_transaksi'];
    $bukti_pembayaran = $_FILES['bukti_pembayaran'];

    // Validasi input
    if (empty($id_transaksi) || empty($bukti_pembayaran)) {
        echo json_encode(["status" => "error", "message" => "ID Transaksi atau Bukti Pembayaran tidak boleh kosong!"]);
        exit;
    }

    // Validasi file upload (bukti pembayaran)
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg']; // Jenis file yang diizinkan
    $file_type = $bukti_pembayaran['type'];
    $file_size = $bukti_pembayaran['size'];

    if (!in_array($file_type, $allowed_types)) {
        echo json_encode(["status" => "error", "message" => "Format file tidak valid. Harap unggah file gambar (JPEG/PNG)."]);
        exit;
    }

    if ($file_size > 5000000) { // Batas ukuran file 5MB
        echo json_encode(["status" => "error", "message" => "Ukuran file terlalu besar. Harap unggah file yang lebih kecil dari 5MB."]);
        exit;
    }

    // Menyimpan file ke folder uploads
    $upload_dir = '../../public/uploads/bukti_pembayaran';
    $file_name = time() . '-' . basename($bukti_pembayaran['name']);
    $file_path = $upload_dir . $file_name;

    if (!move_uploaded_file($bukti_pembayaran['tmp_name'], $file_path)) {
        echo json_encode(["status" => "error", "message" => "Gagal mengunggah bukti pembayaran."]);
        exit;
    }

    // Assign nilai status
    $status = 'dikirim';

    // Update status transaksi menjadi "dikirim" dan simpan bukti pembayaran
    $query = "UPDATE transaksi SET status = ?, bukti_pembayaran = ? WHERE id_transaksi = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("ssi", $status, $file_name, $id_transaksi); // Bind parameter dengan benar

    // Eksekusi query
    if ($stmt->execute()) {
        // Kirimkan respons JSON untuk menampilkan modal sukses
        echo json_encode(["status" => "success", "message" => "Status transaksi diperbarui menjadi 'dikirim' dan bukti pembayaran berhasil diunggah."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal memperbarui status transaksi: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Akses tidak valid!"]);
}

$koneksi->close();
?>

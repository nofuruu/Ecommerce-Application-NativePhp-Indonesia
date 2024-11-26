<?php
// Sertakan file koneksi database
include '../../koneksi.php';
session_start();

// Pastikan id_user sudah ada di session
if (!isset($_SESSION['id_user'])) {
    echo '<div class="alert alert-danger">Anda harus login terlebih dahulu.</div>';
    exit;
}

$id_user = $_SESSION['id_user']; // Ambil id_user dari session login

// Pastikan id_kendaraan ada di POST
if (!isset($_POST['id_kendaraan'])) {
    echo '<div class="alert alert-danger">Tidak ada kendaraan yang dipilih.</div>';
    exit;
}

$id_kendaraan = $_POST['id_kendaraan'];

// Periksa dan ambil nilai 'nama_pelanggan' dari POST
if (isset($_POST['nama_pelanggan']) && !empty($_POST['nama_pelanggan'])) {
    $nama_pelanggan = $_POST['nama_pelanggan'];
} else {
    echo '<div class="alert alert-danger">Nama pelanggan harus diisi.</div>';
    exit;
}

// Periksa dan ambil nilai-nilai lainnya dari POST
$no_ktp = isset($_POST['no_ktp']) ? $_POST['no_ktp'] : '';
$alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
$kode_pos = isset($_POST['kode_pos']) ? $_POST['kode_pos'] : '';
$metode_pembayaran = isset($_POST['metode_pembayaran']) ? $_POST['metode_pembayaran'] : '';

// Cek apakah metode pembayaran valid
$valid_metode_pembayaran = ['cod', 'transfer_bank', 'kartu_kredit', 'e-wallet'];
if (!in_array($metode_pembayaran, $valid_metode_pembayaran)) {
    echo '<div class="alert alert-danger">Metode pembayaran tidak valid.</div>';
    exit;
}

// Periksa apakah alamat, no_ktp, dan kode_pos sudah diisi
if (empty($nama_pelanggan) || empty($no_ktp) || empty($alamat) || empty($kode_pos)) {
    echo '<div class="alert alert-danger">Semua field harus diisi.</div>';
    exit;
}

// Ambil nilai tambahan jika ada (misalnya bank_name, credit_card_type, wallet_type)
$bank_name = isset($_POST['bank_name']) ? $_POST['bank_name'] : NULL;
$credit_card_type = isset($_POST['credit_card_type']) ? $_POST['credit_card_type'] : NULL;
$wallet_type = isset($_POST['wallet_type']) ? $_POST['wallet_type'] : NULL;
$metode_pengiriman = isset($_POST['metode_pengiriman']) ? $_POST['metode_pengiriman'] : '';

// Status transaksi yang ditunggu
$status = 'menunggu persetujuan'; // Status awal

// Query untuk memasukkan data transaksi
$query = "INSERT INTO transaksi 
            (id_user, id_kendaraan, nama_pelanggan, no_ktp, alamat, kode_pos, metode_pembayaran, 
            bank_name, credit_card_type, wallet_type, metode_pengiriman, status) 
          VALUES 
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $koneksi->prepare($query);

// Periksa jika persiapan statement gagal
if (!$stmt) {
    echo '<div class="alert alert-danger">Kesalahan pada query database: ' . $koneksi->error . '</div>';
    exit;
}

// Bind parameter untuk statement SQL
$stmt->bind_param("iissssssssss", $id_user, $id_kendaraan, $nama_pelanggan, $no_ktp, $alamat, $kode_pos, 
                  $metode_pembayaran, $bank_name, $credit_card_type, $wallet_type, $metode_pengiriman, $status);

// Eksekusi query dan periksa hasilnya
if ($stmt->execute()) {
    // Query untuk mengubah status kendaraan menjadi "Terjual"
    $update_kendaraan_query = "UPDATE kendaraan SET status = 'Terjual' WHERE id_kendaraan = ?";
    $update_stmt = $koneksi->prepare($update_kendaraan_query);

    // Periksa apakah persiapan statement berhasil
    if ($update_stmt) {
        // Bind parameter dan eksekusi
        $update_stmt->bind_param("i", $id_kendaraan);
        if ($update_stmt->execute()) {
            // Jika berhasil, alihkan ke halaman checkout dengan status sukses
            header("Location: ../forms/user/checkout.php?status=success");
            exit;
        } else {
            // Jika gagal memperbarui status kendaraan
            echo '<div class="alert alert-danger">Terjadi kesalahan saat memperbarui status kendaraan: ' . $update_stmt->error . '</div>';
        }
        $update_stmt->close();
    } else {
        echo '<div class="alert alert-danger">Kesalahan pada query update kendaraan: ' . $koneksi->error . '</div>';
    }
} else {
    // Jika gagal memasukkan data transaksi
    echo '<div class="alert alert-danger">Terjadi kesalahan saat memproses transaksi: ' . $stmt->error . '</div>';
}


// Tutup statement dan koneksi
$stmt->close();
$koneksi->close();
?>

<?php
include '../../koneksi.php';
session_start();

// Pastikan pengguna telah login
if (!isset($_SESSION['id_user'])) {
    echo '<script>
            alert("Anda harus login terlebih dahulu.");
            window.location.href = "../../src/forms/login.php";
          </script>';
    exit;
}

$id_user = $_SESSION['id_user'];
$id_kendaraan = $_POST['id_kendaraan'] ?? null;

if (!$id_kendaraan) {
    echo '<script>
            alert("ID kendaraan tidak ditemukan.");
            window.history.back();
          </script>';
    exit;
}

// Cek apakah kendaraan sudah ada di garasi pengguna
$query = "SELECT * FROM garasi WHERE id_user = ? AND id_kendaraan = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("ii", $id_user, $id_kendaraan);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Jika belum ada, tambahkan ke garasi
    $query = "INSERT INTO garasi (id_user, id_kendaraan) VALUES (?, ?)";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("ii", $id_user, $id_kendaraan);

    if ($stmt->execute()) {
        echo '<script>
                alert("Kendaraan berhasil dimasukan ke garasi.");
                window.location.href = "../forms/user/garage.php";
              </script>';
    } else {
        echo "Terjadi kesalahan saat menambahkan ke garasi: " . $stmt->error;
    }
} else {
    echo '<script>
            alert("Kendaraan sudah ada di garasi.");
            window.location.href = "../forms/user/garage.php";
          </script>';
}

// Menutup statement dan koneksi
$stmt->close();
$koneksi->close();
?>


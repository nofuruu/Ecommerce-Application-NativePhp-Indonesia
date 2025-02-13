<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../../../public/css/garage.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Transaction Details</title>
</head>
<body>

<?php
include '../../../koneksi.php';
session_start();

// Display session message if exists
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    echo '<div class="alert alert-' . ($message['type'] == 'error' ? 'danger' : 'success') . ' text-center">' . $message['text'] . '</div>';
    // Clear the message after displaying
    unset($_SESSION['message']);
}

// Pastikan pengguna telah login
if (!isset($_SESSION['id_user'])) {
    echo '<div class="alert alert-danger text-center">Anda harus login terlebih dahulu.</div>';
    exit;
}

$id_user = $_SESSION['id_user'];

// Query untuk mendapatkan transaksi pengguna berdasarkan id_user
$query = "SELECT * FROM transaksi WHERE id_user = ? ORDER BY id_transaksi DESC";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
?>

<nav class="navbar navbar-expand-lg" style="position:fixed; width: 100%; z-index: 10; margin-bottom:120px;">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="../../../public/resource/logoB.png" alt="dslogo" id="dslogo">
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../../../home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#">Transactions</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Transaction Cards -->
<div class="content-section" style="margin-top: 200px;">
    <div class="container">
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id_transaksi = htmlspecialchars($row['id_transaksi']);
                    $status = htmlspecialchars($row['status']);
                    $nama_pelanggan = htmlspecialchars($row['nama_pelanggan']);
                    $id_kendaraan = htmlspecialchars($row['id_kendaraan']);
                    $metode_pembayaran = htmlspecialchars($row['metode_pembayaran']);
                    
                    // Tentukan warna status
                    $status_class = ($status == 'menunggu persetujuan') ? 'text-warning' : 
                                    (($status == 'bayar sekarang') ? 'text-success' : 
                                    (($status == 'sukses') ? 'text-success' : 'text-danger'));


                    echo '<div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Transaksi ' . $id_kendaraan . '</h5>
                                    <p class="card-text">Pelanggan: ' . $nama_pelanggan . '</p>
                                    <p class="card-text">Metode Pembayaran: ' . $metode_pembayaran . '</p>
                                    <p class="card-text ' . $status_class . '">Status: ' . $status . '</p>' ;

                    // Tombol Bayar jika status adalah "bayar sekarang"
                    if ($status == 'bayar sekarang') {
                        echo '<button class="btn btn-primary btn-sm" onclick="showPaymentModal(' . $id_transaksi . ')">Bayar</button>';
                    }
                    if ($status == 'bayar sekarang') {
                        echo '<form action="../../function/batalkan_transaksi.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id_transaksi" value="' . $id_transaksi . '">
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Anda yakin ingin membatalkan transaksi ini?\')">Batal</button>
                      </form>';
                    }

                    // Tombol Batalkan jika statusnya masih dalam keadaan "menunggu persetujuan"
                    if ($status == 'menunggu persetujuan') {
                        echo '<form action="../../function/batalkan_transaksi.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id_transaksi" value="' . $id_transaksi . '">
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Anda yakin ingin membatalkan transaksi ini?\')">Batal</button>
                      </form>';
                    }
                    
                    // Tombol Konfirmasi jika status adalah 'dikirim'
                    if ($status == 'dikirim') {
                        echo '
                        <form action="../../function/proses_sukses.php" method="POST" class="d-inline">
                            <input type="hidden" name="id_transaksi" value="' . $id_transaksi . '">
                            <button type="submit" class="btn btn-success">Konfirmasi Kendaraan Sukses</button>
                        </form>';
                    }

                    echo '   </div>
                          </div>
                          </div>';
                }
            } else {
                echo '<div class="col-12"><div class="alert alert-warning text-center">Tidak ada transaksi yang ditemukan.</div></div>';
            }
            ?>
        </div>
    </div>
</div>


<footer>
    <div class="footer-content">
        <h3>NofuAuto</h3>
        <p>Lorem ipsum dolor sit amet.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-3sL4rdLfJxHLi8YgljFBAyRXn0AF6EJcBlpd/izBTHzkr9zE3Q1uKs96/6FcmgiQ" crossorigin="anonymous"></script>

<script>
function showPaymentModal(id_transaksi) {
    var nomor_rekening = '123' + Math.floor(Math.random() * 10000000).toString().padStart(7, '0');
    
    Swal.fire({
        title: 'Informasi Pembayaran',
        html: `
            <p><strong>Nomor Rekening:</strong> ${nomor_rekening}</p>
            <p>Silakan transfer ke nomor rekening di atas sesuai dengan metode pembayaran yang dipilih.</p>
            <div class="mb-3">
                <label for="buktiPembayaran" class="form-label">Upload Bukti Pembayaran</label>
                <input type="file" class="form-control" id="buktiPembayaran" name="bukti_pembayaran" required>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Kirim',
        cancelButtonText: 'Batal',
        focusConfirm: false,
        width: '500px',
        preConfirm: () => {
            const fileInput = document.getElementById('buktiPembayaran');
            if (!fileInput.files[0]) {
                Swal.showValidationMessage('Harap unggah bukti pembayaran.');
                return false;
            }
            // Kirim data menggunakan FormData AJAX
            var formData = new FormData();
            formData.append("id_transaksi", id_transaksi);
            formData.append("bukti_pembayaran", fileInput.files[0]);

            return fetch('../../function/proses_pembayaran.php', {
                method: 'POST',
                body: formData
            }).then(response => {
                if (!response.ok) {
                    throw new Error('Kesalahan server!');
                }
                return response.json();
            }).then(data => {
                if (data.status === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Dikonfirmasi',
                        text: 'Bukti pembayaran berhasil diunggah.',
                        confirmButtonText: 'Tutup'
                    }).then(() => {
                        location.reload(); // Reload halaman untuk memperbarui status
                    });
                } else {
                    throw new Error(data.message || 'Gagal mengunggah bukti pembayaran.');
                }
            }).catch(error => {
                Swal.showValidationMessage(`Request failed: ${error}`);
            });
        }
    });
}

</script>



</body>
</html>

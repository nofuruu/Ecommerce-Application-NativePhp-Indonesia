<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/garage.css">
    <title>Garasi</title>
</head>
<body>

<?php
include '../../../koneksi.php';

$folder = '../../../public/resource/carousels/'; // Folder tempat menyimpan gambar

session_start();
//mengecek apakah user atau admin yang masuk ke form
if (!isset($_SESSION['login']) || !isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'superadmin', 'user'])) {
    echo '
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <div class="modal fade" id="accessDeniedModal" tabindex="-1" role="dialog" aria-labelledby="accessDeniedModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="accessDeniedModalLabel">Login Terlebih Dahulu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Anda belum login, lakukan register atau login bila sudah memiliki akun
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" onclick="window.location.href=\'../login.php\'">Masuk</button>
                    <button type="button" class="btn btn-success" onclick="window.location.href=\'../../../home.php\'">Kembali ke Beranda</button>

                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $("#accessDeniedModal").modal("show");
        });
    </script>
    ';
    exit();
}



$id_user = $_SESSION['id_user'];

// Mengambil data kendaraan yang disimpan di garasi
$query = "SELECT * FROM garasi WHERE id_user = ? AND id_kendaraan = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("ii", $id_user, $id_kendaraan);
$stmt->execute();
$result = $stmt->get_result();

?>
    
    <nav class="navbar navbar-expand-lg" style="position: fixed; width: 100%; z-index: 9999;">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <img src="../../../public/resource/logoB.png" alt="dslogo" id="dslogo">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
        <a class="nav-link" href="../../../home.php">home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../../forms/user/garage.php">mygarage</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../../forms/user/store-page.php">marketplace</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../../forms/user/transaction.php">transaction</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../nofuautocar/src/forms/user/store-page.php">about</a>
        </li>
      </ul>
</div>

      <!-- Ikon Love dan Chat -->




        <!-- Foto Profil pengguna -->
        <?php
        if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
          $id_user = $_SESSION['id_user'];

          // Query untuk mengambil foto profil dari database
          $query = "SELECT profilepict FROM users WHERE id_user = '$id_user'";
          $result = mysqli_query($koneksi, $query);

          // Cek apakah query berhasil dieksekusi dan ada hasil
          if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Cek apakah pengguna memiliki foto profil, jika tidak, gunakan default.png
            $profilepict = (!empty($row['profilepict'])) ? "../../../public/uploads/user/" . $row['profilepict'] : "../../../public/uploads/user/default.png";
          } else {
            // Jika tidak ada hasil dari query atau pengguna belum memiliki foto profil
            $profilepict = "../../../public/uploads/user/default.png";
          }

          // Tampilkan gambar profil
          echo '<div class="dropdown">
                  <a class="navbar-brand" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="' . htmlspecialchars($profilepict) . '" alt="pfp" id="pfp" class="rounded-circle" width="30" height="30">
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown" id="pfpdr">
                    <li><a class="dropdown-item" href="../user/profile.php">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../../function/logout.php">Logout</a></li>
                  </ul>
                </div>';
        } else {
          // Jika pengguna belum login, tampilkan tombol login dan register
          echo '<div class="d-flex ms-auto">
                  <a href="../login.php.php" class="btn btn-login">Login</a>
                  <a href="../register.php" class="btn btn-register ms-2">Register</a>
                </div>';
        }
        ?>
      </div>
    </div>
  </div>
</nav>

<!-- end navbar -->



<!-- garage items -->
<div id="garage" class="content-section" style="margin-top: 70px;">
    <div class="container">
        <div class="row">
            <?php

            // Pastikan pengguna telah login
            if (!isset($_SESSION['id_user'])) {
                echo '<div class="col-12"><div class="alert alert-danger text-center">Anda harus login terlebih dahulu.</div></div>';
                exit;
            }

            $id_user = $_SESSION['id_user'];

            // Query untuk mendapatkan kendaraan di garasi pengguna
            $query = "
                SELECT k.id_kendaraan, k.nm_kendaraan, k.harga, k.foto
                FROM garasi g
                JOIN kendaraan k ON g.id_kendaraan = k.id_kendaraan
                WHERE g.id_user = ? AND k.status != 'terjual'
            ";

            $stmt = $koneksi->prepare($query);
            $stmt->bind_param("i", $id_user);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Ambil gambar pertama jika ada banyak foto
                    $photos = explode(',', htmlspecialchars($row['foto']));
                    $fotoPath = "../../../public/uploads/admin/item/" . $photos[0]; // Gunakan foto pertama
            
                    echo '<div class="col-md-4 mb-4">
                    <div class="card position-relative">
                        <img src="' . $fotoPath . '" class="card-img-top" alt="Foto Kendaraan" onclick="window.location.href=\'../../function/detail.php?id_kendaraan=' . htmlspecialchars($row['id_kendaraan']) . '\'">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">' . htmlspecialchars($row['nm_kendaraan']) . '</h5>
                                <span class="card-price">Rp ' . number_format($row['harga'], 0, ',', '.') . '</span>
                            </div>
                        </div>
                        <div class="remove-btn" onclick="deleteCar(' . htmlspecialchars($row['id_kendaraan']) . ')">
                            <i class="fas fa-trash"></i>
                        </div>
                    </div>
                </div>';
                }            
            } else {
                echo '<div class="col-12">
                        <div class="alert alert-warning text-center">Tidak ada kendaraan di garasi Anda atau semua kendaraan telah terjual.</div>
                      </div>';
            }
            
            // Tutup statement dan koneksi
            $stmt->close();
            $koneksi->close();
            ?>
        </div>
    </div>
</div>



<!-- end garage -->



<!-- footer content -->
<footer>
  <div class="footer-content">
    <h3>NofuAuto</h3>
    <p>lorem ipsum sing dolor sit amet
    </p>
    <ul class="socials">
      <li><a href=""><i class="bi bi-facebook"></i></a></li>
      <li><a href=""><i class="bi bi-twitter-x"></i></a></li>
      <li><a href=""><i class="bi bi-instagram"></i></a></li>
      <li><a href=""><i class="bi bi-github"></i></a></li>
    </ul>
  </div>

  <div class="footer-bottom">
    <p>copyright &copy;2024 nofuruu. designed by <span>Naufal Fatihul Ihsan</span></p>
  </div>
 </footer>
<!-- end footer content -->









</body>

<script>
function deleteCar(id_kendaraan) {
    if (confirm('Apakah Anda yakin ingin menghapus kendaraan ini dari garasi?')) {
        window.location.href = `../../function/garage-delete.php?id_kendaraan=${id_kendaraan}`;
    }
}
</script>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</html>
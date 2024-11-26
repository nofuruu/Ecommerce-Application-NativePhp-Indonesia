<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="./public/css/home.css">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <title>Home</title>
</head>
<body>

<?php
include 'koneksi.php';

session_start();

?>
<!-- navbar -->

<nav class="navbar navbar-expand-lg" style="position: fixed; width: 100%; z-index: 9999;">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <img src="../nofuautocar/public/resource/logoB.png" alt="dslogo" id="dslogo">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./src/forms/user/garage.php">mygarage</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../nofuautocar/src/forms/user/store-page.php">marketplace</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./src/forms/user/transaction.php">transaction</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../nofuautocar/src/forms/user/store-page.php">about</a>
        </li>
      </ul>
</div>

      <!-- Ikon Love dan Chat -->
      <?php
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    // Check if the logged-in user has either 'admin' or 'superadmin' role
    if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'superadmin') {
        echo '<div class="d-flex ms-auto align-items-center">
            <a href="./src/forms/admin/admin.php" class="icon-link me-3">
                <img src="./public/resource/icons/admin.png" alt="Chat" class="icon-chat">
            </a>
        </div>';
    }
}
?>




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
            $profilepict = (!empty($row['profilepict'])) ? "./public/uploads/user/" . $row['profilepict'] : "./public/uploads/user/default.png";
          } else {
            // Jika tidak ada hasil dari query atau pengguna belum memiliki foto profil
            $profilepict = "./public/uploads/user/default.png";
          }

          // Tampilkan gambar profil
          echo '<div class="dropdown">
                  <a class="navbar-brand" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="' . htmlspecialchars($profilepict) . '" alt="pfp" id="pfp" class="rounded-circle" width="30" height="30">
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown" id="pfpdr">
                    <li><a class="dropdown-item" href="./src/forms/user/profile.php">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="./src/function/logout.php">Logout</a></li>
                  </ul>
                </div>';
        } else {
          // Jika pengguna belum login, tampilkan tombol login dan register
          echo '<div class="d-flex ms-auto">
                  <a href="./src/forms/login.php" class="btn btn-login">Login</a>
                  <a href="./src/forms/register.php" class="btn btn-register ms-2">Register</a>
                </div>';
        }
        ?>
      </div>
    </div>
  </div>
</nav>

<!-- end navbar -->


<!-- Hero Section -->
<section id="hero" style="position: relative; padding-top: 100px;">
  <div id="carouselauto" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <?php
        $folder = './public/resource/carousels/'; // Folder yang berisi gambar
        $files = scandir($folder); // Mendapatkan semua file di folder
        $first = true; // Flag untuk menandai gambar pertama sebagai aktif

        foreach ($files as $file) {
          // Hanya sertakan file gambar (jpg, jpeg, png, gif)
          if (in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'mp4'])) {
              echo '<div class="carousel-item ' . ($first ? 'active' : '') . '">';
              echo '<img src="' . $folder . $file . '" class="d-block w-100" alt="Image">';
              echo '</div>';
              $first = false; // Setelah gambar pertama, ubah menjadi false
          }
        }
      ?>
    </div>
  </div>

  <!-- Bagian Overlay dengan Teks -->
  <div class="overlay-text" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: flex-start; padding-left: 50px;">
    <div style="text-align: left; color: white; max-width: 600px;">
      <h1 style="font-size: 4rem; font-weight: bold; letter-spacing: 2px;">Discover New Adventures</h1>
      <p style="font-size: 1.5rem; line-height: 1.5;">Explore the best cars at unbeatable prices, find your perfect match for every journey.</p>
      <a href="<?php echo isset($_SESSION['login']) && $_SESSION['login'] ? './src/forms/user/store-page.php' : './src/forms/login.php'; ?>" 
           class="btn" 
           style="background-color: #f7b731; color: #fff; padding: 15px 30px; font-size: 1.2rem; font-weight: bold; text-transform: uppercase; border-radius: 50px; margin-top: 20px;">
            Explore Now
          </a>
      </div>
  </div>
</section>



<!-- end carousel -->

<div class="discover-header my-4" data-aos="zoom-in">
    <h1>Discover Your Vehicles</h1>
</div>
<div class="card-categories row" data-aos="zoom-out-down">
    <?php
    $categories = [
        ["name" => "Mobil", "image" => "./public/resource/banner/corolla.jpeg", "color" => "#24252a"],
        ["name" => "Motor", "image" => "./public/resource/banner/motor.jpeg", "color" => "#F76707"],
        ["name" => "SUV", "image" => "./public/resource/banner/Toyota Rush 2006-08.jpeg", "color" => "#2F9E44"],
        ["name" => "Scooter", "image" => "./public/resource/banner/spooki.png", "color" => "#F59F00"]
    ];

    foreach ($categories as $category) {
        $name = $category['name'];
        $image = $category['image'];
        $color = $category['color'];

        echo '
        <div class="col-md-3 mb-4">
            <a href="./src/forms/user/store-page.php?category=' . urlencode($name) . '" class="category-link text-decoration-none" style="--category-color: ' . $color . ';">
                <div class="card h-100 shadow-lg border-0 rounded-lg position-relative overflow-hidden">
                    <div class="card-overlay"></div>
                    <img src="' . $image . '" class="card-img-top" alt="Kategori ' . htmlspecialchars($name) . '">
                    <div class="card-body text-center position-relative">
                        <h5 class="card-title mb-0">' . htmlspecialchars($name) . '</h5>
                    </div>
                </div>
            </a>
        </div>';
    }
    ?>
</div>


<div class="row">
  <div class="col-sm-6 mb-3 mb-sm-0" data-aos="fade-right">
    <div class="card vehicle-card">
      <img src="./public/resource/pajero.jpg" class="card-img-top" alt="Vehicle Image">
      <div class="card-body">
        <h5 class="card-title">Panduan Umum</h5>
        <p class="card-text">Lihat panduan membeli kendaraan bekas di online webstore</p>
        <a href="#" class="btn btn-primary btn-explore">Pelajari</a>
      </div>
    </div>
  </div>

  <div class="col-sm-6 mb-3 mb-sm-0" data-aos="fade-left">
    <div class="card vehicle-card">
      <img src="./public/resource/JDM.jpeg" class="card-img-top" alt="Vehicle Image">
      <div class="card-body">
        <h5 class="card-title">Pelajari cara membeli kendaraan disini</h5>
        <p class="card-text">Pelajari bagaimana sistem pembelian di website kami</p>
        <a href="#" class="btn btn-primary btn-explore">Baca</a>
      </div>
    </div>
  </div>
</div>

</div>


<!-- footer -->
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

<!-- end footer -->
</body>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init();
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</html>
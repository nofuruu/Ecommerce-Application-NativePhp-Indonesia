<?php
// Sertakan file koneksi database
include '../../../koneksi.php';
session_start();

// Cek apakah id_kendaraan sudah ada di session atau POST
if (isset($_POST['id_kendaraan'])) {
    $_SESSION['id_kendaraan'] = $_POST['id_kendaraan'];
} elseif (!isset($_SESSION['id_kendaraan'])) {
    echo '<div class="alert alert-danger">Tidak ada kendaraan yang dipilih.</div>';
    exit;
}

// Ambil id_kendaraan dari session
$id_kendaraan = $_SESSION['id_kendaraan'];

// Ambil detail kendaraan dari database
$query = "SELECT * FROM kendaraan WHERE id_kendaraan = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id_kendaraan);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo '<div class="alert alert-warning">Detail kendaraan tidak ditemukan.</div>';
    exit;
}

// Tutup statement dan koneksi
$stmt->close();
$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../../public/css/checkout.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Checkout</title>
</head>
<body>
<div class="checkout-container">
    <div class="checkout-card">

    <div class="detail-card">
    <div class="detail-title">Vehicle Details</div>
    <div class="checkout-item">
        <strong>Nama Kendaraan:</strong>
        <span><?php echo htmlspecialchars($row['nm_kendaraan']); ?></span>
    </div>
    <div class="checkout-item price">
        <strong>Harga:</strong>
        <span class="price-amount">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></span>
    </div>
    <div class="checkout-item">
        <strong>Jenis Kendaraan:</strong>
        <span><?php echo htmlspecialchars($row['jenis_kendaraan']); ?></span>
    </div>
</div>


        <form action="../../function/proses_checkout.php" method="POST" class="checkout-form">
            <input type="hidden" name="id_kendaraan" value="<?php echo htmlspecialchars($id_kendaraan); ?>">
            <input type="hidden" name="id_user" value="<?php echo htmlspecialchars($id_user); ?>">
            
            <div class="form-group">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" id="nama_pelanggan" name="nama_pelanggan" class="form-control" placeholder="Masukan Nama Anda" required>
            </div>

            <div class="form-group">
                <label for="no_ktp" class="form-label">No KTP</label>
                <input type="number" id="no_ktp" name="no_ktp" class="form-control" placeholder="Masukkan Nomor KTP" required minlength="16" maxlength="16">
            </div>
            
            <div class="form-group">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea id="alamat" name="alamat" class="form-control" rows="3" placeholder="Masukan Alamat Anda" required></textarea>
            </div>

            <div class="form-group">
                <label for="kode_pos" class="form-label">Kode POS</label>
                <textarea id="kode_pos" name="kode_pos" class="form-control" rows="1" placeholder="Masukan Kode Pos Anda" required></textarea>
            </div>

<div class="mb-3">
    <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
    <select class="form-control" id="metode_pembayaran" name="metode_pembayaran" required>
        <option value="cod">-</option>
        <option value="transfer_bank">Transfer Bank</option>
    </select>
</div>

<!-- Additional items for Transfer Bank -->
<!-- Dropdown Nama Bank -->
<div id="bank-details" class="additional-items" style="display: none;">
    <div class="mb-3">
        <label for="bank_name" class="form-label">Nama Bank</label>
        <select class="form-control" id="bank_name" name="bank_name">
            <option value="bca" data-image="../../../public/resource/icons/bca.png">BCA</option>
            <option value="mandiri" data-image="../../../public/resource/icons/mandiri.png">Mandiri</option>
            <option value="bni" data-image="../../../public/resource/icons/bni.png">BNI</option>
            <option value="bri" data-image="../../../public/resource/icons/bri.png">BRI</option>
        </select>
    </div>
</div>

<!-- Dropdown Jenis Kartu Kredit -->
<div id="credit-card-details" class="additional-items" style="display: none;">
    <div class="mb-3">
        <label for="credit_card_type" class="form-label">Jenis Kartu Kredit</label>
        <select class="form-control" id="credit_card_type" name="credit_card_type">
            <option value="visa" data-image="../../../public/resource/icons/visa.png">Visa</option>
            <option value="mastercard" data-image="../../../public/resource/icons/mastercard.png">MasterCard</option>
            <option value="american_express" data-image="../../../public/resource/icons/american_express.png">American Express</option>
        </select>
    </div>
</div>

<!-- Dropdown E-Wallet -->
<div id="wallet-details" class="additional-items" style="display: none;">
    <div class="mb-3">
        <label for="wallet_type" class="form-label">Pilih E-Wallet</label>
        <select class="form-control" id="wallet_type" name="wallet_type">
            <option value="ovo" data-image="../../../public/resource/icons/ovo.png">OVO</option>
            <option value="gopay" data-image="../../../public/resource/icons/gopay.png">GoPay</option>
            <option value="dana" data-image="../../../public/resource/icons/dana.png">DANA</option>
        </select>
    </div>
</div>


<!-- Additional items for Delivery Method -->
<div id="delivery-method" class="additional-items" style="display: none;">
    <div class="mb-3">
        <label for="metode_pengiriman" class="form-label">Metode Pengiriman Kendaraan</label>
        <select class="form-control" id="metode_pengiriman" name="metode_pengiriman" required>
    <option value="kurir" data-image="../../../public/resource/icons/towing.png" style="width: 10rem; height: 5rem; margin-right: 10px;">
        Towing kendaraan (Jasa Aplikasi)
        </option>
        <option value="ambil_kendaraan" data-image="../../../public/resource/icons/gerai.png" style="width: 10rem; height: 5rem; margin-right: 10px;">
            Ambil Kendaraan di gerai dan cek fisik kendaraan secara langsung
        </option>
    </select>
    </div>
</div>

<button type="submit" class="btn btn-success">Proses Pembayaran</button>
<a href="javascript:history.back()" class="btn btn-secondary">Kembali</a>

</form>
</div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
const metodePembayaran = document.getElementById('metode_pembayaran');
const bankDetails = document.getElementById('bank-details');
const creditCardDetails = document.getElementById('credit-card-details');
const walletDetails = document.getElementById('wallet-details');
const deliveryMethod = document.getElementById('delivery-method');
// Event listener ketika nilai metode pembayaran berubah
metodePembayaran.addEventListener('change', function() {
    // Sembunyikan semua item tambahan terlebih dahulu
    bankDetails.style.display = 'none';
    creditCardDetails.style.display = 'none';
    walletDetails.style.display = 'none';
    deliveryMethod.style.display = 'none';

    // Tampilkan item tambahan berdasarkan pilihan
    if (metodePembayaran.value === 'transfer_bank') {
        bankDetails.style.display = 'block';
        deliveryMethod.style.display = 'block'; // Tampilkan metode pengiriman jika pilih transfer bank
    } else if (metodePembayaran.value === 'kartu_kredit') {
        creditCardDetails.style.display = 'block';
        deliveryMethod.style.display = 'block'; // Tampilkan metode pengiriman jika pilih kartu kredit
    } else if (metodePembayaran.value === 'e-wallet') {
        walletDetails.style.display = 'block';
        deliveryMethod.style.display = 'block'; // Tampilkan metode pengiriman jika pilih e-wallet
    } else if (metodePembayaran.value === 'cod') {
        // Jangan tampilkan metode pengiriman jika pilih cek di gerai
        deliveryMethod.style.display = 'none';
    }
});
</script>


<script>
  $(document).ready(function() {
    function formatOption(state) {
        if (!state.id) {
            return state.text; // Placeholder case
        }

        // Ambil path gambar dari atribut `data-image`
        var imgUrl = $(state.element).data('image');
        var $state = $(
            '<span><img src="' + imgUrl + '" style="width: 50px; height: 50px; margin-right: 10px;">' + state.text + '</span>'
        );
        return $state;
    }

    // Inisialisasi Select2 dengan template untuk Bank
    $('#bank_name').select2({
        templateResult: formatOption,
        templateSelection: formatOption,
        placeholder: "Pilih Bank",
        allowClear: true
    });

    // Inisialisasi Select2 dengan template untuk Kartu Kredit
    $('#credit_card_type').select2({
        templateResult: formatOption,
        templateSelection: formatOption,
        placeholder: "Pilih Jenis Kartu Kredit",
        allowClear: true
    });

    // Inisialisasi Select2 dengan template untuk E-Wallet
    $('#wallet_type').select2({
        templateResult: formatOption,
        templateSelection: formatOption,
        placeholder: "Pilih E-Wallet",
        allowClear: true
    });
});

</script>

<script>
    $('#metode_pengiriman').select2({
    templateResult: function(state) {
        if (!state.id) {
            return state.text; // Return default text for placeholder
        }
        var imgUrl = $(state.element).data('image');
        var $state = $(
            '<span><img src="' + imgUrl + '" style="width: 50px; height: 50px; margin-right: 10px;">' + state.text + '</span>'
        );
        return $state;
    },
    templateSelection: function(state) {
        if (!state.id) {
            return state.text; // Default for selected item
        }
        var imgUrl = $(state.element).data('image');
        var $state = $(
            '<span><img src="' + imgUrl + '" style="width: 30px; height: 30px; margin-right: 10px;">' + state.text + '</span>'
        );
        return $state;
    }
});

</script>

    <script>
    const urlParams = new URLSearchParams(window.location.search);

    // Pesan sukses setelah checkout
    if (urlParams.has('status') && urlParams.get('status') === 'success') {
        Swal.fire({
            icon: 'success',
            title: 'Pesanan Berhasil',
            text: 'Pesanan Anda sedang diproses oleh admin.',
            showConfirmButton: false,
            timer: 3000,
            willClose: () => {
                window.location.href = '../../../home.php'; // Ganti sesuai rute yang diinginkan
            }
        });
    }
</script>



</script>
</body>
</html>

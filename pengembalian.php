<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'];
$id_transaksi = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Ambil data transaksi yang aktif
$query = mysqli_query($koneksi, "SELECT tp.*, b.nama_barang, b.id_barang 
                                 FROM transaksi_peminjaman tp 
                                 JOIN barang b ON tp.id_barang = b.id_barang 
                                 WHERE tp.id_transaksi = '$id_transaksi' AND tp.id_pemilik = '$id_pengguna' AND tp.status = 'Dipinjam'");
$transaksi = mysqli_fetch_assoc($query);

if (!$transaksi) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['proses_kembali'])) {
    $tgl_kembali       = date('Y-m-d');
    $kondisi_akhir     = mysqli_real_escape_string($koneksi, $_POST['kondisi_akhir']);
    $catatan_kerusakan = mysqli_real_escape_string($koneksi, $_POST['catatan_kerusakan']);
    $id_barang         = $transaksi['id_barang'];

    // 1. Update status transaksi peminjaman menjadi Selesai
    $update_trx = mysqli_query($koneksi, "UPDATE transaksi_peminjaman SET 
                    tgl_kembali = '$tgl_kembali',
                    kondisi_akhir = '$kondisi_akhir',
                    catatan_kerusakan = '$catatan_kerusakan',
                    status = 'Selesai'
                    WHERE id_transaksi = '$id_transaksi'");

    if ($update_trx) {
        // 2. Kembalikan status barang di tabel barang menjadi 'Tersedia' (atau sesuaikan jika rusak parah)
        $status_barang_baru = ($kondisi_akhir == 'Rusak') ? 'Tersedia' : 'Tersedia'; // Bisa disesuaikan jika ingin diblokir
        mysqli_query($koneksi, "UPDATE barang SET status = '$status_barang_baru', kondisi_awal = '$kondisi_akhir' WHERE id_barang = '$id_barang'");

        header("Location: riwayat.php?sukses=1");
        exit;
    } else {
        $error = "Gagal memproses pengembalian: " . mysqli_error($koneksi);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Pengembalian Barang - AssetTrack</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="logoaja.png">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main>
        <h2>Inspeksi Pengembalian Barang</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <p><strong>Nama Barang:</strong> <?php echo htmlspecialchars($transaksi['nama_barang']); ?></p>
            <p><strong>Nama Peminjam:</strong> <?php echo htmlspecialchars($transaksi['nama_peminjam']); ?></p>
            <p><strong>Tanggal Pinjam:</strong> <?php echo $transaksi['tgl_pinjam']; ?> | <strong>Tenggat:</strong> <?php echo $transaksi['tgl_tenggat']; ?></p>
        </div>

        <form method="POST" action="">
            <label>Kondisi Barang Saat Dikembalikan:</label><br>
            <select name="kondisi_akhir" required>
                <option value="Baik">Baik</option>
                <option value="Lecet Ringan">Lecet Ringan</option>
                <option value="Perlu Perhatian">Perlu Perhatian</option>
                <option value="Rusak">Rusak</option>
            </select><br><br>

            <label>Catatan Kerusakan / Keterangan Tambahan (Opsional):</label><br>
            <textarea name="catatan_kerusakan" rows="3" placeholder="Misal: Ada lecet kecil di sudut kanan atau berfungsi normal"></textarea><br><br>

            <button type="submit" name="proses_kembali">Konfirmasi Barang Dikembalikan</button>
            <a href="index.php"><button type="button" style="background-color: #7f8c8d;">Batal</button></a>
        </form>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
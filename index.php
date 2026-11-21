<?php
session_start();
include 'koneksi.php';
include 'functions.php';

if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'];

// Hitung Statistik untuk 4 Kartu Dashboard
$q_total_barang = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM barang WHERE id_pengguna = '$id_pengguna'");
$total_barang = mysqli_fetch_assoc($q_total_barang)['total'];

$q_dipinjamkan = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM transaksi_peminjaman WHERE id_pemilik = '$id_pengguna' AND status = 'Dipinjam'");
$total_dipinjamkan = mysqli_fetch_assoc($q_dipinjamkan)['total'];

$q_pinjam_masuk = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pinjaman_masuk WHERE id_pengguna = '$id_pengguna' AND status = 'Dipinjam'");
$total_pinjam_masuk = mysqli_fetch_assoc($q_pinjam_masuk)['total'];

$q_kategori = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kategori");
$total_kategori = mysqli_fetch_assoc($q_kategori)['total'];

// Ambil daftar transaksi peminjaman aktif milik pengguna
$transaksi_aktif = mysqli_query($koneksi, "SELECT tp.*, b.nama_barang 
                                           FROM transaksi_peminjaman tp 
                                           JOIN barang b ON tp.id_barang = b.id_barang 
                                           WHERE tp.id_pemilik = '$id_pengguna' AND tp.status = 'Dipinjam' 
                                           ORDER BY tp.tgl_tenggat ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - AssetTrack</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main>
        <h2>Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?>! 👋</h2>
        <p>Kelola sirkulasi pinjam-meminjam aset pribadi dan kos secara transparan dan bertanggung jawab.</p>

        <!-- 4 Kartu Statistik Dashboard -->
        <div class="card-container">
            <div class="card">
                <h3>Total Barang Saya</h3>
                <p><?php echo $total_barang; ?></p>
            </div>
            <div class="card" style="border-left-color: #e67e22;">
                <h3>Dipinjam Teman</h3>
                <p><?php echo $total_dipinjamkan; ?></p>
            </div>
            <div class="card" style="border-left-color: #9b59b6;">
                <h3>Barang Saya Pinjam</h3>
                <p><?php echo $total_pinjam_masuk; ?></p>
            </div>
            <div class="card" style="border-left-color: #2ecc71;">
                <h3>Kategori Tersedia</h3>
                <p><?php echo $total_kategori; ?></p>
            </div>
        </div>

        <h3>Monitoring Status Peminjaman Aktif (Barang Anda di Luar)</h3>
        <table border="1" cellpadding="8" cellspacing="0">
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Peminjam</th>
                <th>Tgl Pinjam</th>
                <th>Tenggat</th>
                <th>Status Tenggat</th>
                <th>Aksi / WhatsApp</th>
            </tr>
            <?php
            $no = 1;
            if (mysqli_num_rows($transaksi_aktif) > 0) :
                while ($t = mysqli_fetch_assoc($transaksi_aktif)) :
                    $wa_link = generateWAPesan($t['kontak_peminjam'], $t['nama_peminjam'], $t['nama_barang'], $t['tgl_tenggat']);
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($t['nama_barang']); ?></td>
                    <td><?php echo htmlspecialchars($t['nama_peminjam']); ?> (<?php echo $t['kontak_peminjam']; ?>)</td>
                    <td><?php echo $t['tgl_pinjam']; ?></td>
                    <td><?php echo $t['tgl_tenggat']; ?></td>
                    <td><?php echo cekStatusTenggat($t['tgl_tenggat']); ?></td>
                    <td>
                        <a href="pengembalian.php?id=<?php echo $t['id_transaksi']; ?>" class="btn" style="padding: 5px 10px; font-size: 0.85rem;">Proses Kembali</a>
                        <a href="<?php echo $wa_link; ?>" target="_blank" class="btn" style="background-color: #27ae60; padding: 5px 10px; font-size: 0.85rem; margin-top: 4px;">Ingatkan WA</a>
                    </td>
                </tr>
            <?php
                endwhile;
            else :
            ?>
                <tr>
                    <td colspan="7" align="center">Tidak ada peminjaman aktif saat ini. Semua barang aman di tangan Anda!</td>
                </tr>
            <?php endif; ?>
        </table>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'];

// Riwayat barang milik sendiri yang sudah selesai dikembalikan
$riwayat_keluar = mysqli_query($koneksi, "SELECT tp.*, b.nama_barang 
                                          FROM transaksi_peminjaman tp 
                                          JOIN barang b ON tp.id_barang = b.id_barang 
                                          WHERE tp.id_pemilik = '$id_pengguna' AND tp.status = 'Selesai' 
                                          ORDER BY tp.tgl_kembali DESC");

// Riwayat barang orang lain yang sudah dikembalikan pengguna
$riwayat_masuk  = mysqli_query($koneksi, "SELECT * FROM pinjaman_masuk 
                                          WHERE id_pengguna = '$id_pengguna' AND status = 'Dikembalikan' 
                                          ORDER BY tanggal_kembali DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - AssetTrack</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="logoaja.png">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main>
        <h2>Arsip Riwayat Peminjaman</h2>
        <?php if (isset($_GET['sukses'])) echo "<p style='color: #155724; background: #d4edda; padding: 10px; border-radius: 4px;'>Pengembalian barang berhasil dicatat dan status aset telah diperbarui!</p>"; ?>

        <h3>1. Riwayat Barang Saya yang Pernah Dipinjam Teman</h3>
        <table border="1" cellpadding="8" cellspacing="0">
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Peminjam</th>
                <th>Tgl Pinjam</th>
                <th>Tenggat</th>
                <th>Tgl Kembali</th>
                <th>Ketepatan</th>
                <th>Kondisi Akhir</th>
                <th>Catatan</th>
            </tr>
            <?php
            $no = 1;
            if (mysqli_num_rows($riwayat_keluar) > 0) :
                while ($rk = mysqli_fetch_assoc($riwayat_keluar)) :
                    $tepat = ($rk['tgl_kembali'] <= $rk['tgl_tenggat']) 
                             ? "<span class='badge-aman'>Tepat Waktu</span>" 
                             : "<span class='badge-terlambat'>Terlambat</span>";
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($rk['nama_barang']); ?></td>
                    <td><?php echo htmlspecialchars($rk['nama_peminjam']); ?></td>
                    <td><?php echo $rk['tgl_pinjam']; ?></td>
                    <td><?php echo $rk['tgl_tenggat']; ?></td>
                    <td><?php echo $rk['tgl_kembali']; ?></td>
                    <td><?php echo $tepat; ?></td>
                    <td><strong><?php echo $rk['kondisi_akhir']; ?></strong></td>
                    <td><?php echo htmlspecialchars($rk['catatan_kerusakan']); ?></td>
                </tr>
            <?php
                endwhile;
            else :
            ?>
                <tr>
                    <td colspan="9" align="center">Belum ada riwayat transaksi selesai.</td>
                </tr>
            <?php endif; ?>
        </table>

        <br>

        <h3>2. Riwayat Barang Teman yang Pernah Saya Pinjam</h3>
        <table border="1" cellpadding="8" cellspacing="0">
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Pemilik</th>
                <th>Tgl Pinjam</th>
                <th>Tenggat</th>
                <th>Tgl Kembali</th>
                <th>Kondisi Awal</th>
            </tr>
            <?php
            $no = 1;
            if (mysqli_num_rows($riwayat_masuk) > 0) :
                while ($rm = mysqli_fetch_assoc($riwayat_masuk)) :
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($rm['nama_barang']); ?></td>
                    <td><?php echo htmlspecialchars($rm['nama_pemilik']); ?></td>
                    <td><?php echo $rm['tanggal_pinjam']; ?></td>
                    <td><?php echo $rm['tanggal_tenggat']; ?></td>
                    <td><?php echo $rm['tanggal_kembali']; ?></td>
                    <td><?php echo $rm['kondisi_awal']; ?></td>
                </tr>
            <?php
                endwhile;
            else :
            ?>
                <tr>
                    <td colspan="7" align="center">Belum ada riwayat peminjaman barang masuk.</td>
                </tr>
            <?php endif; ?>
        </table>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
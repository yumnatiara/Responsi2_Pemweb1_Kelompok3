<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'];
$selected_barang = isset($_GET['id_barang']) ? (int) $_GET['id_barang'] : 0;

if (isset($_POST['simpan_transaksi'])) {
    $id_barang       = (int) $_POST['id_barang'];
    $nama_peminjam   = mysqli_real_escape_string($koneksi, $_POST['nama_peminjam']);
    $kontak_peminjam = mysqli_real_escape_string($koneksi, $_POST['kontak_peminjam']);
    $tgl_pinjam      = mysqli_real_escape_string($koneksi, $_POST['tgl_pinjam']);
    $tgl_tenggat     = mysqli_real_escape_string($koneksi, $_POST['tgl_tenggat']);

    // Pastikan barang memang milik pengguna dan statusnya Tersedia
    $cek_barang = mysqli_query($koneksi, "SELECT id_barang FROM barang WHERE id_barang = '$id_barang' AND id_pengguna = '$id_pengguna' AND status = 'Tersedia'");

    if (mysqli_num_rows($cek_barang) > 0) {
        $insert = mysqli_query($koneksi, "INSERT INTO transaksi_peminjaman 
                    (id_barang, id_pemilik, nama_peminjam, kontak_peminjam, tgl_pinjam, tgl_tenggat, status) 
                    VALUES ('$id_barang', '$id_pengguna', '$nama_peminjam', '$kontak_peminjam', '$tgl_pinjam', '$tgl_tenggat', 'Dipinjam')");

        if ($insert) {
            // Update otomatis status barang di tabel barang menjadi Dipinjam
            mysqli_query($koneksi, "UPDATE barang SET status = 'Dipinjam' WHERE id_barang = '$id_barang'");
            header("Location: pinjamkan.php?sukses=1");
            exit;
        } else {
            $error = "Gagal mencatat peminjaman: " . mysqli_error($koneksi);
        }
    } else {
        $error = "Barang tidak valid atau sedang dipinjam.";
    }
}

// Ambil barang milik user yang masih Tersedia
$barang_tersedia = mysqli_query($koneksi, "SELECT * FROM barang WHERE id_pengguna = '$id_pengguna' AND status = 'Tersedia' ORDER BY nama_barang ASC");

// Ambil daftar transaksi barang milik user yang sedang dipinjam orang lain
$transaksi_aktif = mysqli_query($koneksi, "SELECT tp.*, b.nama_barang, b.kondisi_awal 
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
    <title>Catat Barang Dipinjamkan - AssetTrack</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="logoaja.png">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main>
        <h2>Catat Barang Dipinjamkan ke Teman</h2>
        <?php if (isset($_GET['sukses'])) echo "<p style='color:green;'>Transaksi peminjaman berhasil dicatat!</p>"; ?>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="POST" action="" onsubmit="return validasiTanggal()">
            <label>Pilih Barang yang Dipinjamkan:</label><br>
            <select name="id_barang" required>
                <option value="">-- Pilih Barang Tersedia --</option>
                <?php while ($b = mysqli_fetch_assoc($barang_tersedia)) : ?>
                    <option value="<?php echo $b['id_barang']; ?>" <?php if ($selected_barang == $b['id_barang']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($b['nama_barang']) . " (Kondisi: " . $b['kondisi_awal'] . ")"; ?>
                    </option>
                <?php endwhile; ?>
            </select><br><br>

            <label>Nama Peminjam:</label><br>
            <input type="text" name="nama_peminjam" placeholder="Nama teman / anak kos" required><br><br>

            <label>Nomor WhatsApp Peminjam (Awali 62, misal: 628123456789):</label><br>
            <input type="text" name="kontak_peminjam" placeholder="628123456789" required><br><br>

            <label>Tanggal Pinjam:</label><br>
            <input type="date" id="tgl_pinjam" name="tgl_pinjam" value="<?php echo date('Y-m-d'); ?>" required><br><br>

            <label>Tanggal Tenggat Pengembalian:</label><br>
            <input type="date" id="tgl_tenggat" name="tgl_tenggat" required><br><br>

            <button type="submit" name="simpan_transaksi">Catat Peminjaman</button>
        </form>

        <hr>
        <h3>Daftar Barang Saya yang Sedang Dipinjam Orang Lain</h3>
        <table border="1" cellpadding="8" cellspacing="0">
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Kondisi Awal</th>
                <th>Peminjam</th>
                <th>Kontak WA</th>
                <th>Tgl Pinjam</th>
                <th>Tenggat Kembali</th>
                <th>Aksi</th>
            </tr>
            <?php
            $no = 1;
            if (mysqli_num_rows($transaksi_aktif) > 0) :
                while ($t = mysqli_fetch_assoc($transaksi_aktif)) :
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($t['nama_barang']); ?></td>
                    <td><?php echo $t['kondisi_awal']; ?></td>
                    <td><?php echo htmlspecialchars($t['nama_peminjam']); ?></td>
                    <td><?php echo htmlspecialchars($t['kontak_peminjam']); ?></td>
                    <td><?php echo $t['tgl_pinjam']; ?></td>
                    <td><?php echo $t['tgl_tenggat']; ?></td>
                    <td>
                        <a href="pengembalian.php?id=<?php echo $t['id_transaksi']; ?>">Proses Kembali</a>
                    </td>
                </tr>
            <?php
                endwhile;
            else :
            ?>
                <tr>
                    <td colspan="8" align="center">Tidak ada barang yang sedang dipinjamkan.</td>
                </tr>
            <?php endif; ?>
        </table>
    </main>

    <script>
        function validasiTanggal() {
            const pinjam = document.getElementById("tgl_pinjam").value;
            const tenggat = document.getElementById("tgl_tenggat").value;
            if (tenggat < pinjam) {
                alert("Tanggal tenggat pengembalian tidak boleh lebih awal dari tanggal pinjam!");
                return false;
            }
            return true;
        }
    </script>

    <?php include 'footer.php'; ?>
</body>
</html>
<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'];

// Tambah catatan barang yang kita pinjam dari orang lain
if (isset($_POST['simpan_pinjaman'])) {
    $nama_barang    = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
    $nama_pemilik   = mysqli_real_escape_string($koneksi, $_POST['nama_pemilik']);
    $kontak_pemilik = mysqli_real_escape_string($koneksi, $_POST['kontak_pemilik']);
    $kondisi_awal   = mysqli_real_escape_string($koneksi, $_POST['kondisi_awal']);
    $tanggal_pinjam = mysqli_real_escape_string($koneksi, $_POST['tanggal_pinjam']);
    $tanggal_tenggat= mysqli_real_escape_string($koneksi, $_POST['tanggal_tenggat']);

    $query = "INSERT INTO pinjaman_masuk 
              (id_pengguna, nama_barang, nama_pemilik, kontak_pemilik, kondisi_awal, tanggal_pinjam, tanggal_tenggat, status) 
              VALUES ('$id_pengguna', '$nama_barang', '$nama_pemilik', '$kontak_pemilik', '$kondisi_awal', '$tanggal_pinjam', '$tanggal_tenggat', 'Dipinjam')";

    if (mysqli_query($koneksi, $query)) {
        header("Location: pinjam_masuk.php?sukses=1");
        exit;
    } else {
        $error = "Gagal menyimpan data pinjaman: " . mysqli_error($koneksi);
    }
}

// Tandai sudah dikembalikan ke pemiliknya
if (isset($_GET['kembalikan'])) {
    $id_pinjaman = (int) $_GET['kembalikan'];
    $tgl_hari_ini = date('Y-m-d');
    mysqli_query($koneksi, "UPDATE pinjaman_masuk 
                            SET status = 'Dikembalikan', tanggal_kembali = '$tgl_hari_ini' 
                            WHERE id_pinjaman = '$id_pinjaman' AND id_pengguna = '$id_pengguna'");
    header("Location: pinjam_masuk.php?kembali=1");
    exit;
}

$daftar_pinjaman = mysqli_query($koneksi, "SELECT * FROM pinjaman_masuk 
                                           WHERE id_pengguna = '$id_pengguna' AND status = 'Dipinjam' 
                                           ORDER BY tanggal_tenggat ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catat Barang yang Saya Pinjam - AssetTrack</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main>
        <h2>Catat Barang Milik Teman yang Saya Pinjam</h2>
        <?php if (isset($_GET['sukses'])) echo "<p style='color:green;'>Catatan barang pinjaman berhasil ditambahkan!</p>"; ?>
        <?php if (isset($_GET['kembali'])) echo "<p style='color:green;'>Status barang diperbarui menjadi Dikembalikan!</p>"; ?>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="POST" action="" onsubmit="return validasiPinjamanMasuk()">
            <label>Nama Barang yang Dipinjam:</label><br>
            <input type="text" name="nama_barang" placeholder="Contoh: Jas Lab Lengan Panjang" required><br><br>

            <label>Nama Pemilik Barang:</label><br>
            <input type="text" name="nama_pemilik" placeholder="Nama teman pemilik barang" required><br><br>

            <label>Nomor WhatsApp Pemilik (Awali 62):</label><br>
            <input type="text" name="kontak_pemilik" placeholder="628123456789" required><br><br>

            <label>Kondisi Barang Saat Diterima:</label><br>
            <select name="kondisi_awal" required>
                <option value="Baik">Baik</option>
                <option value="Lecet Ringan">Lecet Ringan</option>
                <option value="Perlu Perhatian">Perlu Perhatian</option>
            </select><br><br>

            <label>Tanggal Pinjam:</label><br>
            <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" value="<?php echo date('Y-m-d'); ?>" required><br><br>

            <label>Tenggat Pengembalian:</label><br>
            <input type="date" id="tanggal_tenggat" name="tanggal_tenggat" required><br><br>

            <button type="submit" name="simpan_pinjaman">Simpan Catatan Pinjaman</button>
        </form>

        <hr>
        <h3>Barang Milik Orang Lain yang Sedang Saya Pinjam</h3>
        <table border="1" cellpadding="8" cellspacing="0">
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Pemilik</th>
                <th>Kontak WA Pemilik</th>
                <th>Kondisi Awal</th>
                <th>Tgl Pinjam</th>
                <th>Tenggat Kembali</th>
                <th>Aksi</th>
            </tr>
            <?php
            $no = 1;
            if (mysqli_num_rows($daftar_pinjaman) > 0) :
                while ($p = mysqli_fetch_assoc($daftar_pinjaman)) :
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($p['nama_barang']); ?></td>
                    <td><?php echo htmlspecialchars($p['nama_pemilik']); ?></td>
                    <td><?php echo htmlspecialchars($p['kontak_pemilik']); ?></td>
                    <td><?php echo $p['kondisi_awal']; ?></td>
                    <td><?php echo $p['tanggal_pinjam']; ?></td>
                    <td><?php echo $p['tanggal_tenggat']; ?></td>
                    <td>
                        <a href="pinjam_masuk.php?kembalikan=<?php echo $p['id_pinjaman']; ?>" 
                           onclick="return confirm('Tandai barang ini sudah dikembalikan ke pemiliknya?')">Tandai Dikembalikan</a>
                    </td>
                </tr>
            <?php
                endwhile;
            else :
            ?>
                <tr>
                    <td colspan="8" align="center">Kamu tidak sedang meminjam barang dari orang lain.</td>
                </tr>
            <?php endif; ?>
        </table>
    </main>

    <script>
        function validasiPinjamanMasuk() {
            const pinjam = document.getElementById("tanggal_pinjam").value;
            const tenggat = document.getElementById("tanggal_tenggat").value;
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
<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'];
$id_barang   = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$cek = mysqli_query($koneksi, "SELECT * FROM barang WHERE id_barang = '$id_barang' AND id_pengguna = '$id_pengguna'");
$barang = mysqli_fetch_assoc($cek);

if (!$barang) {
    header("Location: barang.php");
    exit;
}

if (isset($_POST['update'])) {
    $nama_barang  = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
    $id_kategori  = (int) $_POST['id_kategori'];
    $deskripsi    = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $kondisi_awal = mysqli_real_escape_string($koneksi, $_POST['kondisi_awal']);

    $query = "UPDATE barang SET 
                nama_barang = '$nama_barang',
                id_kategori = '$id_kategori',
                deskripsi = '$deskripsi',
                kondisi_awal = '$kondisi_awal'
              WHERE id_barang = '$id_barang' AND id_pengguna = '$id_pengguna'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: barang.php");
        exit;
    } else {
        $error = "Gagal memperbarui barang: " . mysqli_error($koneksi);
    }
}

$kategori = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang - AssetTrack</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main>
        <h2>Edit Data Barang</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="POST" action="">
            <label>Nama Barang:</label><br>
            <input type="text" name="nama_barang" value="<?php echo htmlspecialchars($barang['nama_barang']); ?>" required><br><br>

            <label>Kategori:</label><br>
            <select name="id_kategori" required>
                <?php while ($kat = mysqli_fetch_assoc($kategori)) : ?>
                    <option value="<?php echo $kat['id_kategori']; ?>" <?php if ($kat['id_kategori'] == $barang['id_kategori']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($kat['nama_kategori']); ?>
                    </option>
                <?php endwhile; ?>
            </select><br><br>

            <label>Deskripsi / Kelengkapan:</label><br>
            <textarea name="deskripsi" rows="4" cols="40"><?php echo htmlspecialchars($barang['deskripsi']); ?></textarea><br><br>

            <label>Kondisi Barang:</label><br>
            <select name="kondisi_awal" required>
                <option value="Baik" <?php if ($barang['kondisi_awal'] == 'Baik') echo 'selected'; ?>>Baik</option>
                <option value="Lecet Ringan" <?php if ($barang['kondisi_awal'] == 'Lecet Ringan') echo 'selected'; ?>>Lecet Ringan</option>
                <option value="Perlu Perhatian" <?php if ($barang['kondisi_awal'] == 'Perlu Perhatian') echo 'selected'; ?>>Perlu Perhatian</option>
                <option value="Rusak" <?php if ($barang['kondisi_awal'] == 'Rusak') echo 'selected'; ?>>Rusak</option>
            </select><br><br>

            <button type="submit" name="update">Simpan Perubahan</button>
            <a href="barang.php"><button type="button">Batal</button></a>
        </form>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
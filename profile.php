<?php
session_start();
include 'koneksi.php';

// halaman wajib login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// proses up profile
if (isset($_POST['update_profil'])) {
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $nim          = mysqli_real_escape_string($koneksi, $_POST['nim']);
    $no_wa        = mysqli_real_escape_string($koneksi, $_POST['no_telepon']);
    $alamat       = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    $update = mysqli_query($koneksi, "UPDATE pengguna SET 
                nama_lengkap = '$nama_lengkap', 
                nim = '$nim', 
                no_telepon = '$no_telepon', 
                alamat = '$alamat' 
                WHERE id_pengguna = '$id_pengguna'");

    if ($update) {
        $_SESSION['nama_lengkap'] = $nama_lengkap;
        $pesan = "Profil kamu berhasil diperbarui!";
    } else {
        $error = "Gagal memperbarui profil.";
    }
}

// Ambil data profil terbaru dari database
$query = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE id_pengguna = '$id_pengguna'");
$profil = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; // Akan dibuat oleh Anggota 3 ?>
    
    <main>
        <h2>Manajemen Profil Mahasiswa</h2>
        <?php if (isset($pesan)) echo "<p style='color:green;'>$pesan</p>"; ?>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="POST" action="">
            <label>Username (Tidak dapat diubah)</label><br>
            <input type="text" value="<?php echo $profil['username']; ?>" readonly><br><br>

            <label>Nama Lengkap</label><br>
            <input type="text" name="nama_lengkap" value="<?php echo $profil['nama_lengkap']; ?>" required><br><br>

            <label>NIM</label><br>
            <input type="text" name="nim" value="<?php echo $profil['nim']; ?>" required><br><br>

            <label>Nomor Telepon Aktif</label><br>
            <input type="text" name="no_telepon" value="<?php echo $profil['no_telepon']; ?>" required><br><br>

            <label>Alamat</label><br>
            <textarea name="alamat" rows="3" required><?php echo $profil['alamat']; ?></textarea><br><br>

            <button type="submit" name="update_profil">Simpan Perubahan</button>
        </form>
    </main>

    <?php include 'footer.php'; // Akan dibuat oleh Anggota 3 ?>
</body>
</html>

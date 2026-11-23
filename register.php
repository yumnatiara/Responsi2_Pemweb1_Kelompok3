<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['id_pengguna'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['register'])) {
    $username     = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password     = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $nim          = mysqli_real_escape_string($koneksi, $_POST['nim']);
    $no_telepon   = mysqli_real_escape_string($koneksi, $_POST['no_telepon']);
    $alamat       = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    // Cek apakah username sudah dipakai
    $cek_user = mysqli_query($koneksi, "SELECT username FROM pengguna WHERE username = '$username'");
    if (mysqli_num_rows($cek_user) > 0) {
        $error = "Sayangnya username sudah terdaftar, silakan gunakan username lain ya!";
    } else {
        $query = "INSERT INTO pengguna (username, password, nama_lengkap, nim, no_telepon, alamat) 
                  VALUES ('$username', '$password', '$nama_lengkap', '$nim', '$no_telepon', '$alamat')";
        
        if (mysqli_query($koneksi, $query)) {
            echo "<script>alert('Mantap, registrasimu berhasil! Silakan login.'); window.location='login.php';</script>";
            exit;
        } else {
            $error = "Terjadi kesalahan saat mendaftar: " . mysqli_error($koneksi);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun AssetTrack</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="logoaja.png">
</head>
<body>
    <main>
        <h2>Registrasi Akun</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        
        <form method="POST" action="">
            <label>Nama Lengkap</label><br>
            <input type="text" name="nama_lengkap" required><br><br>

            <label>NIM</label><br>
            <input type="text" name="nim" placeholder="Contoh: H1D025001" required><br><br>

            <label>Nomor Telepon Aktif</label><br>
            <input type="text" name="no_telepon" placeholder="Contoh: 081234567890" required><br><br>

            <label>Alamat</label><br>
            <textarea name="alamat" rows="3" required></textarea><br><br>

            <label>Username</label><br>
            <input type="text" name="username" required><br><br>

            <label>Password</label><br>
            <input type="password" name="password" required><br><br>

            <button type="submit" name="register">Daftar Sekarang</button>
        </form>
        <p>Loh, sudah punya akun? <a href="login.php">Langsung Masuk aja di sini</a></p>
    </main>
</body>
</html>
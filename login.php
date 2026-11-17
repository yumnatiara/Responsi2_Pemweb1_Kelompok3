<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE username = '$username'");
    $data = mysqli_fetch_assoc($query);

    // Pencocokan password (bisa pakai password_verify jika di register pakai password_hash)
    if ($data && password_verify($password, $data['password'])) {
        $_SESSION['id_pengguna'] = $data['id_pengguna'];
        $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login AssetTrack</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <h2>Login AssetTrack</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST" action="">
            <label>Username</label><br>
            <input type="text" name="username" required><br><br>
            
            <label>Password</label><br>
            <input type="password" name="password" required><br><br>
            
            <button type="submit" name="login">Masuk</button>
        </form>
        <p>Belum punya akun ya? <a href="register.php">Daftar di sini dulu yuk!</a></p>
    </main>
</body>
</html>

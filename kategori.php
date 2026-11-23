<?php
session_start();
include 'koneksi.php';

// wajib login
if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit;
}

// tambah kategori
if (isset($_POST['tambah_kategori'])) {
    $nama_kategori = mysqli_real_escape_string($koneksi, $_POST['nama_kategori']);
    mysqli_query($koneksi, "INSERT INTO kategori (nama_kategori) VALUES ('$nama_kategori')");
    header("Location: kategori.php");
    exit;
}

// hapus kategori
if (isset($_GET['hapus'])) {
    $id_hapus = (int) $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM kategori WHERE id_kategori = '$id_hapus'");
    header("Location: kategori.php");
    exit;
}

$data_kategori = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY id_kategori ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="logoaja.png">
</head>
<body>
    <?php include 'navbar.php'; // Akan dibuat oleh Anggota 3 ?>

    <main>
        <h2>Data Master Kategori Barang</h2>
        
        <form method="POST" action="">
            <label>Nama Kategori Baru:</label>
            <input type="text" name="nama_kategori" placeholder="Misal: Alat Olahraga" required>
            <button type="submit" name="tambah_kategori">Tambah</button>
        </form>
        <br>

        <table border="1" cellpadding="8" cellspacing="0">
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>

            <?php 
            $no = 1;
            while ($row = mysqli_fetch_assoc($data_kategori)) : 
            ?>

            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $row['nama_kategori']; ?></td>
                <td>
                    <a href="kategori.php?hapus=<?php echo $row['id_kategori']; ?>" 
                        onclick="return confirm('Yakin ingin menghapus kategori ini?')">Hapus</a>
                </td>
            </tr>
            
            <?php endwhile; ?>
            
        </table>
    </main>

    <?php include 'footer.php'; // Akan dibuat oleh Anggota 3 ?>

</body>
</html>

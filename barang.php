<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'];

// Filter kategori jika dipilih
$filter_kategori = isset($_GET['kategori']) ? (int) $_GET['kategori'] : 0;

$sql_barang = "SELECT barang.*, kategori.nama_kategori 
               FROM barang 
               JOIN kategori ON barang.id_kategori = kategori.id_kategori 
               WHERE barang.id_pengguna = '$id_pengguna'";

if ($filter_kategori > 0) {
    $sql_barang .= " AND barang.id_kategori = '$filter_kategori'";
}

$sql_barang .= " ORDER BY barang.id_barang DESC";
$data_barang = mysqli_query($koneksi, $sql_barang);
$list_kategori = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Barang Saya - AssetTrack</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main>
        <h2>Katalog Aset Pribadi Saya</h2>

        <div style="margin-bottom: 15px;">
            <a href="barang_tambah.php"><button type="button">+ Tambah Barang Baru</button></a>
        </div>

        <form method="GET" action="barang.php" style="margin-bottom: 15px;">
            <label for="kategori">Filter Kategori:</label>
            <select name="kategori" id="kategori">
                <option value="0">-- Semua Kategori --</option>
                <?php while ($kat = mysqli_fetch_assoc($list_kategori)) : ?>
                    <option value="<?php echo $kat['id_kategori']; ?>" <?php if ($filter_kategori == $kat['id_kategori']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($kat['nama_kategori']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Filter</button>
        </form>

        <table border="1" cellpadding="8" cellspacing="0">
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Deskripsi / Kelengkapan</th>
                <th>Kondisi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php
            $no = 1;
            if (mysqli_num_rows($data_barang) > 0) :
                while ($row = mysqli_fetch_assoc($data_barang)) :
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama_kategori']); ?></td>
                    <td><?php echo htmlspecialchars($row['deskripsi']); ?></td>
                    <td><?php echo $row['kondisi_awal']; ?></td>
                    <td>
                        <strong><?php echo $row['status']; ?></strong>
                    </td>
                    <td>
                        <a href="barang_edit.php?id=<?php echo $row['id_barang']; ?>">Edit</a> |
                        <?php if ($row['status'] == 'Tersedia') : ?>
                            <a href="barang_hapus.php?id=<?php echo $row['id_barang']; ?>" onclick="return confirm('Yakin ingin menghapus barang ini?')">Hapus</a> |
                            <a href="pinjamkan.php?id_barang=<?php echo $row['id_barang']; ?>">Pinjamkan</a>
                        <?php else : ?>
                            <span>Sedang Dipinjam</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php
                endwhile;
            else :
            ?>
                <tr>
                    <td colspan="7" align="center">Belum ada barang yang terdaftar.</td>
                </tr>
            <?php endif; ?>
        </table>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'];

if (isset($_POST['simpan'])) {
    $nama_barang  = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
    $id_kategori  = (int) $_POST['id_kategori'];
    $deskripsi    = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $kondisi_awal = mysqli_real_escape_string($koneksi, $_POST['kondisi_awal']);

    $query = "INSERT INTO barang (id_pengguna, id_kategori, nama_barang, deskripsi, kondisi_awal, status) 
              VALUES ('$id_pengguna', '$id_kategori', '$nama_barang', '$deskripsi', '$kondisi_awal', 'Tersedia')";

    if (mysqli_query($koneksi, $query)) {
        header("Location: barang.php");
        exit;
    } else {
        $error = "Gagal menambahkan barang: " . mysqli_error($koneksi);
    }
}

$kategori = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang - AssetTrack</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main>
        <h2>Tambah Barang Pribadi</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="POST" action="" onsubmit="return validasiBarang()">
            <label>Nama Barang:</label><br>
            <input type="text" id="nama_barang" name="nama_barang" placeholder="Contoh: Kalkulator Casio fx-991ES" required><br><br>

            <label>Kategori:</label><br>
            <select name="id_kategori" id="id_kategori" required>
                <option value="">-- Pilih Kategori --</option>
                <?php while ($kat = mysqli_fetch_assoc($kategori)) : ?>
                    <option value="<?php echo $kat['id_kategori']; ?>">
                        <?php echo htmlspecialchars($kat['nama_kategori']); ?>
                    </option>
                <?php endwhile; ?>
            </select><br><br>

            <label>Deskripsi / Kelengkapan (Maks. 200 karakter):</label><br>
            <textarea id="deskripsi" name="deskripsi" rows="4" cols="40" onkeyup="hitungKarakter()" placeholder="Contoh: Lengkap dengan tutup dan baterai baru"></textarea><br>
            <small>Sisa karakter: <span id="sisa_karakter">200</span></small><br><br>

            <label>Kondisi Awal Barang:</label><br>
            <select name="kondisi_awal" required>
                <option value="Baik">Baik</option>
                <option value="Lecet Ringan">Lecet Ringan</option>
                <option value="Perlu Perhatian">Perlu Perhatian</option>
                <option value="Rusak">Rusak</option>
            </select><br><br>

            <button type="submit" name="simpan">Simpan Barang</button>
            <a href="barang.php"><button type="button">Batal</button></a>
        </form>
    </main>

    <script>
        function hitungKarakter() {
            const deskripsi = document.getElementById("deskripsi");
            const sisa = document.getElementById("sisa_karakter");
            const max = 200;
            if (deskripsi.value.length > max) {
                alert("Deskripsi maksimal 200 karakter!");
                deskripsi.value = deskripsi.value.substring(0, max);
            }
            sisa.textContent = max - deskripsi.value.length;
        }

        function validasiBarang() {
            const nama = document.getElementById("nama_barang").value.trim();
            if (nama === "") {
                alert("Nama barang tidak boleh kosong!");
                return false;
            }
            return true;
        }
    </script>

    <?php include 'footer.php'; ?>
</body>
</html>
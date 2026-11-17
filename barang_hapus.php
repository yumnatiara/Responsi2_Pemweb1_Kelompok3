<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'];
$id_barang   = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Hanya hapus barang milik pengguna sendiri yang berstatus Tersedia
mysqli_query($koneksi, "DELETE FROM barang WHERE id_barang = '$id_barang' AND id_pengguna = '$id_pengguna' AND status = 'Tersedia'");

header("Location: barang.php");
exit;
?>
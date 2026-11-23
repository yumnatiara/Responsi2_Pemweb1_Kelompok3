<?php
?>
<header style="background-color: #2c3e50; color: #fff; padding: 1rem 2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto; flex-wrap: wrap; gap: 15px;">
        <!-- Logo Aplikasi -->
        <h1 style="margin: 0; font-size: 1.5rem;">
        <a href="index.php"><img src="logo.png" alt="Logo AssetTrack" style="height: 50px; width: auto;"></a>
        </h1>
        
        <!-- Menu Navigasi -->
        <nav style="display: flex; align-items: center; flex-wrap: wrap; gap: 15px;">
            <a href="index.php" style="color: #ecf0f1; text-decoration: none; font-weight: 500; font-size: 0.95rem;">Dashboard</a>
            <a href="barang.php" style="color: #ecf0f1; text-decoration: none; font-weight: 500; font-size: 0.95rem;">Katalog Barang</a>
            <a href="kategori.php" style="color: #ecf0f1; text-decoration: none; font-weight: 500; font-size: 0.95rem;">Kategori</a>
            <a href="pinjamkan.php" style="color: #ecf0f1; text-decoration: none; font-weight: 500; font-size: 0.95rem;">Barang Dipinjamkan</a>
            <a href="pinjam_masuk.php" style="color: #ecf0f1; text-decoration: none; font-weight: 500; font-size: 0.95rem;">Barang Saya Pinjam</a>
            <a href="riwayat.php" style="color: #ecf0f1; text-decoration: none; font-weight: 500; font-size: 0.95rem;">Riwayat</a>
            <a href="profile.php" style="color: #ecf0f1; text-decoration: none; font-weight: 500; font-size: 0.95rem;">Profil (<?php echo isset($_SESSION['nama_lengkap']) ? htmlspecialchars($_SESSION['nama_lengkap']) : 'Akun'; ?>)</a>
            <a href="logout.php" onclick="return confirm('Yakin ingin keluar dari aplikasi?')" style="color: #e74c3c; text-decoration: none; font-weight: 600; font-size: 0.95rem; background: rgba(231, 76, 60, 0.1); padding: 5px 10px; border-radius: 4px;">Logout</a>
        </nav>
    </div>
</header>
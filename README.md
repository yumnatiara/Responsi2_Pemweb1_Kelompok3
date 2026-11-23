# AssetTrack - Sistem Pengelolaan & Peminjaman Barang Pinjaman

Sebuah platform berbasis web untuk mencatat serta memantau sirkulasi barang yang sedang dipinjamkan maupun dipinjam di sekitar lingkungan kampus ataupun luar kampus.

## Anggota Kelompok
1. Varel Hapita Salma H1D025031 (Database, Autentikasi serta Data Master)
   - linkedIn (https://lnkd.in/p/gd7kxdqF)
3. Rizky Satya Firmansyah H1D025044 (Dashboard, Tracker Tenggat, Pengembalian & UI Global)
   - linkedIn (https://lnkd.in/p/g4pW5U6q)
5. Yumna Tiara Afif H1D025129 (Katalog Aset & Pencatatan Sirkulasi Pinjaman)
   - linkedIn (https://lnkd.in/p/gxsJ7Kux)

---

## Latar Belakang
Dalam lingkungan di sekitar kampus, kebutuhan untuk saling meminjam barang sangatlah tinggi seiringnya waktu. Mulai dari kebutuhan anak kos, fasilitas dan perlengkapan pendukung yang ada di kampus, serta perlengkapan acara organisasi. Tetapi, sayangnya mayoritas masih dilakukan secara informal dan lisan tanpa adanya pencatatan yang jelas. Akibatnya sering memicu berbagai persoalan, seperti barang hilang tanpa kejelasan,lupa dikembalikan karena melewati tenggat waktu, serta memicu perselisihan akibat kerusakan barang yang kondisi awalnya tidak tercatat.

Berdasarkan permasalahan tersebut, dalam mengatasinya ini dari kami pun merancang suatu proyek bernama **AssetTrack** sebagai platform berbasis web untuk mempermudah pengelolaan serta pencatatan barang peminjaman. Yang memiliki tujuan untuk:
- Mencatat data identitas peminjam dan tenggat waktu pengembalian secara lebih terstruktur.
- Pemantauan kondisi barang sebelum dipinjam dan sesudah dikembalikan.
- Mendukung pencapaian SDG 12 (Responsible Consumption and Production) dengan mendorong budaya pinjam-pakai aset bersama di kalangan mahasiswa.

---

## Daftar 9 Modul Fitur Utama
1. **Registrasi & Autentikasi:** Pendaftaran akun pengguna baru, login, penjagaan sesi (`$_SESSION`), dan logout.
2. **Manajemen Profil Pengguna:** Pembaruan biodata pengguna (Nama, Nomor WhatsApp, dan alamat/keterangan terkait).
3. **Katalog & Inventaris Barang:** Operasi CRUD daftar barang milik pengguna beserta status dan kondisi awal.
4. **Kategori & Filter Aset:** Pengelolaan data master kategori serta filter dan pencarian barang pada katalog.
5. **Pencatatan Barang Dipinjamkan:** Mencatat transaksi barang milik sendiri yang dipinjam orang lain dan mengubah status barang menjadi *Dipinjam*.
6. **Pencatatan Barang Dipinjam:** Mencatat daftar barang milik orang lain yang sedang dipinjam pengguna.
7. **Monitoring Tenggat Waktu:** Perhitungan sisa hari otomatis dengan indikator warna (Aman, Peringatan, Terlambat) serta tautan pengingat WhatsApp.
8. **Pengembalian & Cek Kondisi:** Pencatatan tanggal kembali aktual, pengecekan kondisi akhir, dan pemulihan status barang menjadi *Tersedia*.
9. **Dashboard & Riwayat Transaksi:** Ringkasan statistik aset, daftar pinjaman aktif, dan arsip riwayat transaksi selesai.

---

## Teknologi yang Digunakan
- Frontend: HTML5, CSS, JavaScript
- Backend: PHP Native
- Database: MySQL
- Web Server Lokal: XAMPP (Apache)

---

## Panduan Instalasi & Konfigurasi
1. Unduh atau *clone* repository ini ke dalam folder `htdocs` pada direktori instalasi XAMPP Anda (contoh: `C:\xampp\htdocs\assettrack`).
2. Buka **XAMPP Control Panel**, lalu jalankan modul **Apache** dan **MySQL**.
3. Buka browser dan akses `http://localhost/phpmyadmin`.
4. Buat database baru sesuai nama database pada file koneksi (misalnya **`assettrack`**).
5. Pilih database tersebut, buka tab **Import** (atau tab **SQL**), lalu unggah dan jalankan file **`.sql`** yang tersedia di dalam folder proyek ini.
6. Pastikan pengaturan pada file **`koneksi.php`** sudah sesuai dengan konfigurasi MySQL lokal Anda (`host: localhost`, `user: root`, `password: ""`).

---

## Cara Menjalankan Aplikasi
1. Pastikan server Apache dan MySQL di XAMPP dalam keadaan aktif (*Running*).
2. Buka browser dan kunjungi alamat: `http://localhost/assettrack/login.php`.
3. Klik link pendaftaran untuk membuat akun baru melalui halaman registrasi jika belum memiliki akun.
4. Login menggunakan username dan password yang telah didaftarkan untuk mulai mengelola inventaris dan mencatat peminjaman barang.

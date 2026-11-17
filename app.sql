CREATE DATABASE assettrack;

USE assettrack;

CREATE TABLE pengguna (
    id_pengguna INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    nim VARCHAR(30),
    no_telepon VARCHAR(20),
    alamat TEXT
);

CREATE TABLE kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(50) NOT NULL
);

CREATE TABLE barang (
    id_barang INT AUTO_INCREMENT PRIMARY KEY,
    id_pengguna INT NOT NULL,
    id_kategori INT NOT NULL,
    nama_barang VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    kondisi_awal ENUM('Baik', 'Lecet Ringan', 'Perlu Perhatian', 'Rusak') DEFAULT 'Baik',
    status ENUM('Tersedia', 'Dipinjam') DEFAULT 'Tersedia',
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna) ON DELETE CASCADE,
    FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori)
);

-- milik pengguna yang dipinjam orang lain
CREATE TABLE transaksi_peminjaman (
  id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
  id_barang INT NOT NULL,
  id_pemilik INT NOT NULL,
  nama_peminjam VARCHAR(100) NOT NULL,
  kontak_peminjam VARCHAR(20),
  tgl_pinjam DATE NOT NULL,
  tgl_tenggat DATE NOT NULL,
  tgl_kembali DATE NULL,
  kondisi_akhir ENUM('Baik','Lecet Ringan','Perlu Perhatian','Rusak') NULL,
  catatan_kerusakan TEXT NULL,
  status ENUM('Dipinjam','Selesai') NOT NULL DEFAULT 'Dipinjam',
  FOREIGN KEY (id_barang) REFERENCES barang(id_barang) ON DELETE CASCADE,
  FOREIGN KEY (id_pemilik) REFERENCES pengguna(id_pengguna) ON DELETE CASCADE
);

-- milik orang lain yang dipinjam pengguna
CREATE TABLE pinjaman_masuk (
  id_pinjaman INT AUTO_INCREMENT PRIMARY KEY,
  id_pengguna INT NOT NULL,                    -- peminjam (user aplikasi)
  nama_barang VARCHAR(100) NOT NULL,
  nama_pemilik VARCHAR(100) NOT NULL,
  kontak_pemilik VARCHAR(20),
  kondisi_awal ENUM('Baik','Lecet Ringan','Perlu Perhatian') NOT NULL DEFAULT 'Baik',
  tanggal_pinjam DATE NOT NULL,
  tanggal_tenggat DATE NOT NULL,
  tanggal_kembali DATE NULL,
  status ENUM('Dipinjam','Dikembalikan') NOT NULL DEFAULT 'Dipinjam',
  FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna) ON DELETE CASCADE
);

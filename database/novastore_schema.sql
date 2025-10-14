-- ------------------------------------------------------------
-- Rebuild schema for Novastore project
-- ------------------------------------------------------------
-- ------------------------------------------------------------
-- Table: users
-- Used by login.php for administrator authentication.
-- ------------------------------------------------------------
CREATE TABLE `users` (
  `id_user` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `password` CHAR(64) NOT NULL,
  `nama_lengkap` VARCHAR(150) DEFAULT NULL,
  `role` ENUM('admin','staff') NOT NULL DEFAULT 'admin',
  `dibuat_pada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `uniq_users_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed a default admin (password: admin123).
INSERT INTO `users` (`username`, `password`, `nama_lengkap`, `role`)
VALUES ('nova', SHA2('nova', 256), 'Administrator', 'admin');

-- ------------------------------------------------------------
-- Table: produk
-- Referenced across index.php, detail.php, and admin pages.
-- ------------------------------------------------------------
CREATE TABLE `produk` (
  `id_produk` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_produk` VARCHAR(150) NOT NULL,
  `deskripsi_produk` TEXT NOT NULL,
  `instruksi_produk` TEXT DEFAULT NULL,
  `daftar_paket` LONGTEXT DEFAULT NULL,
  `nama_berkas_gambar` VARCHAR(255) DEFAULT NULL,
  `tanggal_dibuat` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diperbarui` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_produk`),
  KEY `idx_produk_nama` (`nama_produk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample catalogue entries so the landing page has data to render.
INSERT INTO `produk`
  (`nama_produk`, `deskripsi_produk`, `instruksi_produk`, `daftar_paket`, `nama_berkas_gambar`)
VALUES
  (
    'Mobile Legends',
    'Top up diamond Mobile Legends cepat dan aman.',
    'Masukkan User ID dan Server ID (contoh: 123456789 (1234)).',
    '[{"nama":"86 Diamonds","harga":"27000"},{"nama":"172 Diamonds","harga":"52000"},{"nama":"Starlight Member","harga":"149000"}]',
    'ml.webp'
  ),
  (
    'PUBG Mobile',
    'Pembelian UC PUBG Mobile resmi dan instan.',
    'Masukkan Player ID dan pilih nominal UC yang diinginkan.',
    '[{"nama":"60 UC","harga":"19000"},{"nama":"325 UC","harga":"95000"},{"nama":"990 UC","harga":"279000"}]',
    'pb.webp'
  );

-- ------------------------------------------------------------
-- Table: pesanan
-- Populated from success.php after pengguna melakukan checkout.
-- ------------------------------------------------------------
CREATE TABLE `pesanan` (
  `id_pesanan` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_produk` VARCHAR(150) NOT NULL,
  `nama_produk` VARCHAR(150) NOT NULL,
  `user_id_game` VARCHAR(100) NOT NULL,
  `server_id_game` VARCHAR(100) NOT NULL,
  `nickname_game` VARCHAR(150) DEFAULT NULL,
  `paket_nominal` VARCHAR(150) NOT NULL,
  `metode_pembayaran` VARCHAR(100) NOT NULL,
  `catatan_pembeli` TEXT DEFAULT NULL,
  `tanggal_dibuat` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pesanan`),
  KEY `idx_pesanan_kode` (`kode_produk`),
  KEY `idx_pesanan_tanggal` (`tanggal_dibuat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Tambahkan kolom untuk menyimpan detail paket harga per produk
ALTER TABLE produk
    ADD COLUMN daftar_paket LONGTEXT NULL AFTER deskripsi_produk;

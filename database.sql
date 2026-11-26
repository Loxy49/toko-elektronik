-- ============================================
-- Database: toko_elektronik
-- Dibuat untuk UAS Pemrograman Web 2025/2026
-- Tema: Manajemen Penjualan Toko Elektronik
-- ============================================

CREATE DATABASE IF NOT EXISTS toko_elektronik
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE toko_elektronik;

-- Tabel produk utama untuk CRUD
CREATE TABLE IF NOT EXISTS produk (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    kode_produk VARCHAR(20)     NOT NULL UNIQUE,
    nama_produk VARCHAR(150)    NOT NULL,
    kategori    VARCHAR(80)     NOT NULL,
    merek       VARCHAR(80)     NOT NULL,
    harga       DECIMAL(15, 2)  NOT NULL,
    stok        INT             NOT NULL DEFAULT 0,
    deskripsi   TEXT,
    created_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Data contoh
INSERT INTO produk (kode_produk, nama_produk, kategori, merek, harga, stok, deskripsi) VALUES
('ELK-001', 'Laptop Asus VivoBook 14',      'Laptop',      'Asus',       7500000, 12, 'Laptop ringan dengan prosesor Intel Core i5, RAM 8GB, SSD 512GB'),
('ELK-002', 'Smartphone Samsung Galaxy A54', 'Smartphone',  'Samsung',    4200000, 25, 'Layar Super AMOLED 6.4 inci, kamera 50MP, baterai 5000mAh'),
('ELK-003', 'Earbuds TWS Sony WF-C500',      'Aksesoris',   'Sony',        850000, 40, 'True wireless stereo, baterai 20 jam, IPX4 water resistant'),
('ELK-004', 'Monitor LG 24 inci FHD',        'Monitor',     'LG',         1950000,  8, 'Panel IPS Full HD 1920x1080, 75Hz, AMD FreeSync'),
('ELK-005', 'Printer Epson L3210',            'Printer',     'Epson',      1650000,  6, 'Printer multifungsi ink tank, cetak/scan/copy, USB'),
('ELK-006', 'SSD External Samsung T7 1TB',   'Storage',     'Samsung',    1450000, 15, 'Kecepatan baca 1050MB/s, USB 3.2 Gen2, tahan benturan'),
('ELK-007', 'Keyboard Mechanical Rexus',     'Aksesoris',   'Rexus',       550000, 30, 'Mechanical keyboard RGB, switch blue, anti-ghosting'),
('ELK-008', 'Tablet Lenovo Tab M10 Plus',    'Tablet',      'Lenovo',     2800000, 10, 'Layar 10.6 inci 2K, prosesor Helio G80, RAM 4GB');

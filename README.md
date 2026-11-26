# TokoElektro – Manajemen Penjualan Toko Elektronik

## Informasi Aplikasi

| Item | Keterangan |
|------|-----------|
| **Nama Aplikasi** | TokoElektro – Manajemen Penjualan |
| **Tema** | Manajemen Penjualan Toko Elektronik |
| **Mata Kuliah** | Pemrograman Web – Semester 4B |
| **Tahun Akademik** | 2025/2026 Genap |

---

## Deskripsi Aplikasi

TokoElektro adalah aplikasi web manajemen penjualan produk elektronik berbasis PHP Native dan MySQL. Aplikasi ini memungkinkan pengelola toko untuk mengelola data produk (laptop, smartphone, tablet, dll) secara lengkap, memantau stok, dan menampilkan data produk terkini dari API publik DummyJSON.

---

## Fitur Aplikasi

1. **Dashboard** – Statistik total produk, kategori, stok, dan nilai inventaris
2. **CRUD Produk** – Tambah, tampilkan, ubah, dan hapus data produk elektronik
3. **Validasi Input** – Validasi form (field wajib, kode unik, format angka)
4. **Pencarian Real-time** – Filter tabel produk langsung dari browser
5. **Panel API** – Menampilkan produk smartphone & laptop dari DummyJSON API
6. **Notifikasi** – Pesan berhasil/gagal setelah setiap operasi CRUD
7. **Responsif** – Tampilan menyesuaikan layar desktop dan mobile

---

## Struktur Tabel Database

**Nama tabel:** `produk`  
**Database:** `toko_elektronik`

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| `id` | INT AUTO_INCREMENT PK | Primary key |
| `kode_produk` | VARCHAR(20) UNIQUE | Kode unik produk |
| `nama_produk` | VARCHAR(150) | Nama lengkap produk |
| `kategori` | VARCHAR(80) | Kategori produk |
| `merek` | VARCHAR(80) | Merek/brand |
| `harga` | DECIMAL(15,2) | Harga jual |
| `stok` | INT | Jumlah stok |
| `deskripsi` | TEXT | Deskripsi singkat |
| `created_at` | TIMESTAMP | Waktu dibuat |
| `updated_at` | TIMESTAMP | Waktu diperbarui |

---

## API Publik yang Digunakan

**DummyJSON** – `https://dummyjson.com`

- Endpoint Smartphones: `https://dummyjson.com/products/category/smartphones?limit=8`
- Endpoint Laptops: `https://dummyjson.com/products/category/laptops?limit=8`

---

## Cara Menjalankan Aplikasi

### Prasyarat
- XAMPP (Apache + MySQL) terinstall
- Browser modern (Chrome, Firefox, Edge)

### Langkah-langkah

1. **Salin folder project** ke `C:\xampp\htdocs\` (Windows) atau `/var/www/html/` (Linux/Mac)
2. **Jalankan XAMPP** → Start Apache dan MySQL
3. **Import database:**
   - Buka `http://localhost/phpmyadmin`
   - Klik "Import" → pilih file `database.sql`
   - Klik "Go"
4. **Konfigurasi database** (jika perlu):
   - Buka file `includes/config.php`
   - Sesuaikan `DB_USER` dan `DB_PASS` dengan MySQL Anda
5. **Akses aplikasi:** Buka browser → `http://localhost/toko_elektronik/`

---

## Struktur Folder

```
toko_elektronik/
├── index.php               ← Halaman utama aplikasi
├── database.sql            ← File SQL untuk membuat & mengisi database
├── README.md               ← Dokumentasi ini
├── assets/
│   ├── css/
│   │   └── style.css       ← Stylesheet aplikasi
│   └── js/
│       └── app.js          ← JavaScript (navigasi, CRUD, API fetch)
└── includes/
    ├── config.php          ← Konfigurasi koneksi database
    └── handler.php         ← Handler operasi CRUD (POST)
```

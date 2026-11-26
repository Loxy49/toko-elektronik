<?php
// ============================================
// Toko Elektronik - Halaman Utama (index.php)
// UAS Pemrograman Web 2025/2026
// ============================================

session_start();
require_once 'includes/config.php';

// Ambil pesan dari session (setelah redirect)
$pesan = $_SESSION['pesan'] ?? null;
unset($_SESSION['pesan']);

// ---- Ambil semua produk ----
$produk_list = [];
$res = $conn->query("SELECT * FROM produk ORDER BY id DESC");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $produk_list[] = $row;
    }
}

// ---- Statistik dashboard ----
$total_produk  = count($produk_list);
$total_stok    = array_sum(array_column($produk_list, 'stok'));
$total_nilai   = array_sum(array_map(fn($p) => $p['harga'] * $p['stok'], $produk_list));

// Hitung total kategori unik
$kategori_unik = count(array_unique(array_column($produk_list, 'kategori')));

// Warna badge per kategori
$warna_badge = [
    'Laptop'      => '',
    'Smartphone'  => 'badge-orange',
    'Tablet'      => 'badge-purple',
    'Monitor'     => 'badge-green',
    'Printer'     => 'badge-red',
    'Storage'     => 'badge-orange',
    'Aksesoris'   => '',
];

function badgeKelas($kat) {
    global $warna_badge;
    return $warna_badge[$kat] ?? '';
}

function rupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Elektronik – Manajemen Penjualan</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="app-layout">

    <!-- ========== SIDEBAR ========== -->
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon">TE</div>
            <div>
                <h1>Toko Elektronik</h1>
                <p>Manajemen Penjualan</p>
            </div>
        </div>

        <nav class="menu">
            <a href="#" data-target="dashboard" onclick="navigasi('dashboard'); return false;" class="active">
                Dashboard
            </a>
            <a href="#" data-target="data-lokal" onclick="navigasi('data-lokal'); return false;">
                Data Produk
            </a>
            <a href="#" data-target="api-panel" onclick="navigasi('api-panel'); loadApiProduk(); return false;">
                Panel API
            </a>
            <a href="#" data-target="tentang" onclick="navigasi('tentang'); return false;">
                Tentang
            </a>
        </nav>

        <div class="sidebar-footer">
            UAS Pemrograman Web<br>
            Semester 4 (Genap)
        </div>
    </aside>

    <!-- ========== KONTEN ========== -->
    <main class="content">

        <!-- Topbar -->
        <header class="topbar">
            <div>
                <h2>Toko Elektronik</h2>
                <p>Aplikasi Manajemen Penjualan Produk Elektronik</p>
            </div>
            <button class="btn btn-primary" onclick="navigasi('data-lokal'); bukaModalTambah()">
                + Tambah Produk
            </button>
        </header>

        <!-- Pesan Notifikasi -->
        <?php if ($pesan): ?>
        <div class="alert alert-<?= $pesan['tipe'] === 'sukses' ? 'success' : 'error' ?>">
            
            <span><?= $pesan['teks'] ?></span>
        </div>
        <?php endif; ?>

        <!-- ============================
             SECTION: DASHBOARD
        ============================= -->
        <section id="dashboard" class="section">
            <div class="hero-card">
                <h3>Selamat Datang di TokoElektro!</h3>
                <p>Kelola data produk elektronik Anda dengan mudah. Tambah, ubah, dan hapus produk, serta pantau stok secara real-time.</p>
            </div>

            <!-- Stat Cards -->
            <div class="grid-4">
                <div class="stat-card">
                    <div class="stat-icon blue">01</div>
                    <div class="stat-info">
                        <span>Total Produk</span>
                        <strong><?= $total_produk ?></strong>
                        <small>item terdaftar</small>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green">02</div>
                    <div class="stat-info">
                        <span>Kategori</span>
                        <strong><?= $kategori_unik ?></strong>
                        <small>jenis produk</small>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange">03</div>
                    <div class="stat-info">
                        <span>Total Stok</span>
                        <strong><?= number_format($total_stok) ?></strong>
                        <small>unit tersedia</small>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon purple">04</div>
                    <div class="stat-info">
                        <span>Nilai Inventaris</span>
                        <strong style="font-size:16px;"><?= rupiah($total_nilai) ?></strong>
                        <small>total aset stok</small>
                    </div>
                </div>
            </div>

            <!-- Tabel Ringkasan -->
            <?php if (!empty($produk_list)): ?>
            <div class="panel" style="margin-top:20px;">
                <div class="section-title">Produk Terbaru</div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach (array_slice($produk_list, 0, 5) as $p): ?>
                            <tr>
                                <td><code><?= htmlspecialchars($p['kode_produk']) ?></code></td>
                                <td><?= htmlspecialchars($p['nama_produk']) ?></td>
                                <td><span class="badge <?= badgeKelas($p['kategori']) ?>"><?= htmlspecialchars($p['kategori']) ?></span></td>
                                <td><?= rupiah($p['harga']) ?></td>
                                <td><?= $p['stok'] > 0 ? $p['stok'] . ' unit' : '<span style="color:var(--danger)">Habis</span>' ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($total_produk > 5): ?>
                <div style="margin-top:12px;text-align:center;">
                    <a href="#" onclick="navigasi('data-lokal'); return false;" style="color:var(--primary);font-size:14px;">
                        Lihat semua <?= $total_produk ?> produk →
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </section>

        <!-- ============================
             SECTION: DATA LOKAL (CRUD)
        ============================= -->
        <section id="data-lokal" class="section" style="display:none;">
            <div class="section-title">Manajemen Data Produk</div>

            <div class="panel">
                <!-- Search & Tombol -->
                <div style="display:flex; gap:12px; margin-bottom:16px; flex-wrap:wrap; align-items:center;">
                    <input type="text" id="search-input" placeholder="Cari produk..." 
                           oninput="filterTabel()" style="max-width:300px;">
                    <button class="btn btn-primary" onclick="bukaModalTambah()">+ Tambah Produk</button>
                </div>

                <!-- Tabel Produk -->
                <div class="table-wrap">
                    <table id="tabel-produk">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Merek</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($produk_list)): ?>
                            <tr>
                                <td colspan="8" style="text-align:center; color:var(--muted); padding:30px;">
                                    Belum ada data produk. Klik "Tambah Produk" untuk memulai.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($produk_list as $i => $p): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><code><?= htmlspecialchars($p['kode_produk']) ?></code></td>
                                <td>
                                    <strong><?= htmlspecialchars($p['nama_produk']) ?></strong>
                                    <?php if ($p['deskripsi']): ?>
                                    <br><small style="color:var(--muted)"><?= htmlspecialchars(substr($p['deskripsi'], 0, 60)) ?>...</small>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge <?= badgeKelas($p['kategori']) ?>"><?= htmlspecialchars($p['kategori']) ?></span></td>
                                <td><?= htmlspecialchars($p['merek']) ?></td>
                                <td><?= rupiah($p['harga']) ?></td>
                                <td>
                                    <?php if ($p['stok'] > 10): ?>
                                        <span style="color:var(--success); font-weight:600;"><?= $p['stok'] ?></span>
                                    <?php elseif ($p['stok'] > 0): ?>
                                        <span style="color:var(--accent); font-weight:600;"><?= $p['stok'] ?></span>
                                    <?php else: ?>
                                        <span style="color:var(--danger); font-weight:600;">Habis</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-primary btn-sm"
                                            onclick="bukaModalEdit(
                                                <?= $p['id'] ?>,
                                                '<?= addslashes($p['kode_produk']) ?>',
                                                '<?= addslashes($p['nama_produk']) ?>',
                                                '<?= addslashes($p['kategori']) ?>',
                                                '<?= addslashes($p['merek']) ?>',
                                                <?= $p['harga'] ?>,
                                                <?= $p['stok'] ?>,
                                                '<?= addslashes($p['deskripsi']) ?>'
                                            )">
                                            Ubah
                                        </button>
                                        <button class="btn btn-danger btn-sm"
                                            onclick="konfirmasiHapus(<?= $p['id'] ?>, '<?= addslashes($p['nama_produk']) ?>')">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <p style="margin-top:12px; font-size:13px; color:var(--muted);">
                    Total: <?= $total_produk ?> produk &bull; Total stok: <?= number_format($total_stok) ?> unit &bull; Nilai inventaris: <?= rupiah($total_nilai) ?>
                </p>
            </div>
        </section>

        
        <!-- ============================
             SECTION: PANEL API
        ============================= -->
        <section id="api-panel" class="section" style="display:none;">
            <div class="section-title">Katalog Produk Eksternal</div>
                                            
            <div class="panel">
                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom:16px;">
                                            
                    <div>
                        <h3 style="font-size:16px; margin-bottom:6px;">
                            Jelajahi Produk Elektronik
                        </h3>
                                            
                        <p style="color:var(--muted); font-size:13px;">
                            Temukan berbagai pilihan produk elektronik dari sumber data eksternal.
                            Informasi produk ditampilkan secara dinamis melalui integrasi Public API.
                        </p>
                                            
                        <p style="font-size:12px; margin-top:6px; color:var(--muted);">
                            Sumber Data:
                            <a href="https://dummyjson.com/products/category/smartphones"
                               target="_blank"
                               style="color:var(--primary); text-decoration:none;">
                                DummyJSON API
                            </a>
                            &bull; Kategori: Smartphones
                        </p>
                    </div>
                                            
                    <div style="display:flex; align-items:center; gap:12px;">
                        <span id="api-count" style="font-size:13px; color:var(--muted);"></span>
                                            
                        <button class="btn btn-outline btn-sm" onclick="loadApiProduk()">
                            ↻ Refresh Data
                        </button>
                    </div>
                </div>
                                            
                <!-- Container Produk API -->
                <div id="api-container">
                    <div class="api-loading">
                        <div class="spinner"></div>
                        <p>Memuat katalog produk...</p>
                    </div>
                </div>
            </div>
        </section>


        <!-- ============================
             SECTION: TENTANG
        ============================= -->
        <section id="tentang" class="section" style="display:none;">
            <div class="section-title">Tentang Aplikasi</div>

            <div class="panel">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="label">Nama Aplikasi</div>
                        <div class="value">TokoElektro – Manajemen Penjualan</div>
                    </div>
                    <div class="info-item">
                        <div class="label">Tema</div>
                        <div class="value">Manajemen Penjualan Toko Elektronik</div>
                    </div>
                    <div class="info-item">
                        <div class="label">Mata Kuliah</div>
                        <div class="value">Pemrograman Web – Semester 4B</div>
                    </div>
                    <div class="info-item">
                        <div class="label">Tahun Akademik</div>
                        <div class="value">2025/2026 Genap</div>
                    </div>
                    <div class="info-item">
                        <div class="label">Teknologi</div>
                        <div class="value">PHP Native, MySQL, JavaScript (Fetch API)</div>
                    </div>
                    <div class="info-item">
                        <div class="label">Public API</div>
                        <div class="value">DummyJSON (dummyjson.com)</div>
                    </div>
                </div>

                <div style="margin-top:20px;">
                    <div class="section-title" style="font-size:14px;">Fitur Aplikasi</div>
                    <ul style="padding-left:20px; line-height:2; font-size:14px; color:var(--muted);">
                        <li>Dashboard dengan statistik produk, stok, dan nilai inventaris</li>
                        <li>CRUD produk elektronik (Tambah, Tampilkan, Ubah, Hapus)</li>
                        <li>Validasi input pada form produk</li>
                        <li>Pencarian/filter produk secara real-time</li>
                        <li>Panel produk eksternal dari DummyJSON API (Smartphones & Laptops)</li>
                        <li>Tampilan responsif untuk desktop dan mobile</li>
                        <li>Notifikasi pesan berhasil/gagal setelah operasi CRUD</li>
                    </ul>
                </div>

                <div style="margin-top:20px;">
                    <div class="section-title" style="font-size:14px;">Cara Menjalankan</div>
                    <ol style="padding-left:20px; line-height:2; font-size:14px; color:var(--muted);">
                        <li>Salin folder <code>toko_elektronik</code> ke dalam folder <code>htdocs</code> (XAMPP)</li>
                        <li>Jalankan <strong>Apache</strong> dan <strong>MySQL</strong> melalui XAMPP Control Panel</li>
                        <li>Buka <strong>phpMyAdmin</strong> dan import file <code>database.sql</code></li>
                        <li>Akses aplikasi di browser: <code>http://localhost/toko_elektronik/</code></li>
                    </ol>
                </div>

                <div style="margin-top:20px;">
                    <div class="section-title" style="font-size:14px;">Struktur Tabel Database</div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr><th>Kolom</th><th>Tipe</th><th>Keterangan</th></tr>
                            </thead>
                            <tbody>
                                <tr><td><code>id</code></td><td>INT AI PK</td><td>Primary key auto increment</td></tr>
                                <tr><td><code>kode_produk</code></td><td>VARCHAR(20)</td><td>Kode unik produk</td></tr>
                                <tr><td><code>nama_produk</code></td><td>VARCHAR(150)</td><td>Nama lengkap produk</td></tr>
                                <tr><td><code>kategori</code></td><td>VARCHAR(80)</td><td>Kategori (Laptop, Smartphone, dll)</td></tr>
                                <tr><td><code>merek</code></td><td>VARCHAR(80)</td><td>Merek/brand produk</td></tr>
                                <tr><td><code>harga</code></td><td>DECIMAL(15,2)</td><td>Harga jual produk</td></tr>
                                <tr><td><code>stok</code></td><td>INT</td><td>Jumlah stok tersedia</td></tr>
                                <tr><td><code>deskripsi</code></td><td>TEXT</td><td>Deskripsi singkat produk</td></tr>
                                <tr><td><code>created_at</code></td><td>TIMESTAMP</td><td>Waktu data dibuat</td></tr>
                                <tr><td><code>updated_at</code></td><td>TIMESTAMP</td><td>Waktu data terakhir diubah</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

    </main>
</div>

<!-- ========== MODAL FORM PRODUK ========== -->
<div class="modal-overlay" id="modal-produk">
    <div class="modal">
        <div class="modal-header">
            <h3 id="modal-title">Tambah Produk</h3>
            <button class="modal-close" onclick="tutupModal()">✕</button>
        </div>

        <form method="POST" action="includes/handler.php">
            <input type="hidden" name="action" id="form-action" value="tambah">
            <input type="hidden" name="id" id="form-id">

            <div class="form-row">
                <div class="form-group">
                    <label for="f-kode">Kode Produk *</label>
                    <input type="text" id="f-kode" name="kode_produk" placeholder="Contoh: ELK-009" required>
                </div>
                <div class="form-group">
                    <label for="f-merek">Merek *</label>
                    <input type="text" id="f-merek" name="merek" placeholder="Contoh: Samsung" required>
                </div>
            </div>

            <div class="form-group">
                <label for="f-nama">Nama Produk *</label>
                <input type="text" id="f-nama" name="nama_produk" placeholder="Nama lengkap produk" required>
            </div>

            <div class="form-group">
                <label for="f-kat">Kategori *</label>
                <select id="f-kat" name="kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Laptop">Laptop</option>
                    <option value="Smartphone">Smartphone</option>
                    <option value="Tablet">Tablet</option>
                    <option value="Monitor">Monitor</option>
                    <option value="Printer">Printer</option>
                    <option value="Storage">Storage</option>
                    <option value="Aksesoris">Aksesoris</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="f-harga">Harga (Rp) *</label>
                    <input type="number" id="f-harga" name="harga" placeholder="0" min="0" required>
                </div>
                <div class="form-group">
                    <label for="f-stok">Stok *</label>
                    <input type="number" id="f-stok" name="stok" placeholder="0" min="0" required>
                </div>
            </div>

            <div class="form-group">
                <label for="f-desk">Deskripsi</label>
                <textarea id="f-desk" name="deskripsi" placeholder="Deskripsi singkat produk (opsional)"></textarea>
            </div>

            <button type="submit" class="btn btn-success btn-full">Simpan Produk</button>
        </form>
    </div>
</div>

<!-- ========== KONFIRMASI HAPUS ========== -->
<div class="confirm-overlay" id="confirm-overlay">
    <div class="confirm-box">
        
        <h4>Hapus Produk?</h4>
        <p>Anda akan menghapus produk <strong id="konfirmasi-nama"></strong>. Tindakan ini tidak dapat dibatalkan.</p>
        <div class="confirm-btns">
            <button class="btn btn-outline" onclick="tutupKonfirmasi()">Batal</button>
            <button class="btn btn-danger" onclick="eksekusiHapus()">Ya, Hapus</button>
        </div>
    </div>
</div>

<script src="assets/js/app.js"></script>
</body>
</html>

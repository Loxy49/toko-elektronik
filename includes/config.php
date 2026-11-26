<?php
// ============================================
// Konfigurasi koneksi database
// ============================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // Ganti dengan username MySQL Anda
define('DB_PASS', '');           // Ganti dengan password MySQL Anda
define('DB_NAME', 'toko_elektronik');

// Buat koneksi menggunakan MySQLi
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek koneksi
if ($conn->connect_error) {
    die('<div style="padding:20px;color:red;font-family:sans-serif;">
        <strong>Koneksi database gagal:</strong> ' . $conn->connect_error . '<br>
        Pastikan MySQL berjalan dan database <em>toko_elektronik</em> sudah dibuat.
    </div>');
}

// Set charset agar mendukung karakter Indonesia
$conn->set_charset('utf8mb4');
?>

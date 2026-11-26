<?php
// ============================================
// Handler CRUD Produk
// Menerima request POST dari form utama
// ============================================

require_once 'config.php';

$action = $_POST['action'] ?? '';

// ---- Fungsi validasi input ----
function validasiInput($conn, $data) {
    $errors = [];

    if (empty(trim($data['kode_produk'])))
        $errors[] = 'Kode produk tidak boleh kosong.';

    if (empty(trim($data['nama_produk'])))
        $errors[] = 'Nama produk tidak boleh kosong.';

    if (empty(trim($data['kategori'])))
        $errors[] = 'Kategori tidak boleh kosong.';

    if (empty(trim($data['merek'])))
        $errors[] = 'Merek tidak boleh kosong.';

    if (!is_numeric($data['harga']) || $data['harga'] <= 0)
        $errors[] = 'Harga harus berupa angka positif.';

    if (!is_numeric($data['stok']) || $data['stok'] < 0)
        $errors[] = 'Stok tidak boleh negatif.';

    return $errors;
}

// ---- TAMBAH DATA ----
if ($action === 'tambah') {
    $errors = validasiInput($conn, $_POST);

    if (!empty($errors)) {
        $_SESSION['pesan'] = ['tipe' => 'error', 'teks' => implode('<br>', $errors)];
    } else {
        $kode   = $conn->real_escape_string(trim($_POST['kode_produk']));
        $nama   = $conn->real_escape_string(trim($_POST['nama_produk']));
        $kat    = $conn->real_escape_string(trim($_POST['kategori']));
        $merek  = $conn->real_escape_string(trim($_POST['merek']));
        $harga  = floatval($_POST['harga']);
        $stok   = intval($_POST['stok']);
        $desk   = $conn->real_escape_string(trim($_POST['deskripsi'] ?? ''));

        // Cek kode duplikat
        $cek = $conn->query("SELECT id FROM produk WHERE kode_produk = '$kode'");
        if ($cek->num_rows > 0) {
            $_SESSION['pesan'] = ['tipe' => 'error', 'teks' => "Kode produk <strong>$kode</strong> sudah digunakan."];
        } else {
            $sql = "INSERT INTO produk (kode_produk, nama_produk, kategori, merek, harga, stok, deskripsi)
                    VALUES ('$kode','$nama','$kat','$merek',$harga,$stok,'$desk')";
            if ($conn->query($sql)) {
                $_SESSION['pesan'] = ['tipe' => 'sukses', 'teks' => "Produk <strong>$nama</strong> berhasil ditambahkan."];
            } else {
                $_SESSION['pesan'] = ['tipe' => 'error', 'teks' => 'Gagal menyimpan: ' . $conn->error];
            }
        }
    }
}

// ---- UBAH DATA ----
if ($action === 'ubah') {
    $id     = intval($_POST['id']);
    $errors = validasiInput($conn, $_POST);

    if (!empty($errors)) {
        $_SESSION['pesan'] = ['tipe' => 'error', 'teks' => implode('<br>', $errors)];
    } else {
        $kode   = $conn->real_escape_string(trim($_POST['kode_produk']));
        $nama   = $conn->real_escape_string(trim($_POST['nama_produk']));
        $kat    = $conn->real_escape_string(trim($_POST['kategori']));
        $merek  = $conn->real_escape_string(trim($_POST['merek']));
        $harga  = floatval($_POST['harga']);
        $stok   = intval($_POST['stok']);
        $desk   = $conn->real_escape_string(trim($_POST['deskripsi'] ?? ''));

        // Cek kode duplikat (selain id ini sendiri)
        $cek = $conn->query("SELECT id FROM produk WHERE kode_produk = '$kode' AND id != $id");
        if ($cek->num_rows > 0) {
            $_SESSION['pesan'] = ['tipe' => 'error', 'teks' => "Kode produk <strong>$kode</strong> sudah digunakan oleh produk lain."];
        } else {
            $sql = "UPDATE produk SET
                        kode_produk  = '$kode',
                        nama_produk  = '$nama',
                        kategori     = '$kat',
                        merek        = '$merek',
                        harga        = $harga,
                        stok         = $stok,
                        deskripsi    = '$desk'
                    WHERE id = $id";
            if ($conn->query($sql)) {
                $_SESSION['pesan'] = ['tipe' => 'sukses', 'teks' => "Produk <strong>$nama</strong> berhasil diperbarui."];
            } else {
                $_SESSION['pesan'] = ['tipe' => 'error', 'teks' => 'Gagal memperbarui: ' . $conn->error];
            }
        }
    }
}

// ---- HAPUS DATA ----
if ($action === 'hapus') {
    $id = intval($_POST['id']);
    // Ambil nama dulu untuk pesan
    $row = $conn->query("SELECT nama_produk FROM produk WHERE id = $id")->fetch_assoc();
    if ($row) {
        $conn->query("DELETE FROM produk WHERE id = $id");
        $_SESSION['pesan'] = ['tipe' => 'sukses', 'teks' => "Produk <strong>{$row['nama_produk']}</strong> berhasil dihapus."];
    } else {
        $_SESSION['pesan'] = ['tipe' => 'error', 'teks' => 'Data tidak ditemukan.'];
    }
}

// Kembali ke halaman utama
header('Location: ../index.php#data-lokal');
exit;
?>

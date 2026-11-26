// ============================================
// Toko Elektronik - JavaScript
// ============================================

/* ---- NAVIGASI SIDEBAR ---- */
function navigasi(target) {
    // Sembunyikan semua section
    document.querySelectorAll('.section').forEach(s => s.style.display = 'none');
    // Tampilkan section yang dipilih
    const el = document.getElementById(target);
    if (el) el.style.display = 'block';
    // Update menu aktif
    document.querySelectorAll('.menu a').forEach(a => a.classList.remove('active'));
    const link = document.querySelector(`.menu a[data-target="${target}"]`);
    if (link) link.classList.add('active');
    // Scroll ke atas
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ---- INIT: Tampilkan section dari hash ---- 
document.addEventListener('DOMContentLoaded', function () {
    const hash = window.location.hash.replace('#', '') || 'dashboard';
    navigasi(hash);

    // Auto-dismiss pesan setelah 5 detik
    const alert = document.querySelector('.alert');
    if (alert) {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    }

    // Load API panel jika langsung ke sana
    if (hash === 'api-panel') loadApiProduk();
});

/* ---- MODAL TAMBAH ---- */
function bukaModalTambah() {
    document.getElementById('modal-title').textContent = 'Tambah Produk Baru';
    document.getElementById('form-action').value = 'tambah';
    document.getElementById('form-id').value = '';
    document.getElementById('form-produk').reset();
    document.getElementById('modal-produk').classList.add('open');
}

/* ---- MODAL EDIT ---- */
function bukaModalEdit(id, kode, nama, kat, merek, harga, stok, desk) {
    document.getElementById('modal-title').textContent = 'Ubah Produk';
    document.getElementById('form-action').value = 'ubah';
    document.getElementById('form-id').value = id;
    document.getElementById('f-kode').value = kode;
    document.getElementById('f-nama').value = nama;
    document.getElementById('f-kat').value = kat;
    document.getElementById('f-merek').value = merek;
    document.getElementById('f-harga').value = harga;
    document.getElementById('f-stok').value = stok;
    document.getElementById('f-desk').value = desk;
    document.getElementById('modal-produk').classList.add('open');
}

/* ---- TUTUP MODAL ---- */
function tutupModal() {
    document.getElementById('modal-produk').classList.remove('open');
}

// Tutup modal jika klik overlay
document.addEventListener('click', function (e) {
    if (e.target.id === 'modal-produk') tutupModal();
    if (e.target.id === 'confirm-overlay') tutupKonfirmasi();
});

/* ---- KONFIRMASI HAPUS ---- */
let pendingHapusId = null;

function konfirmasiHapus(id, nama) {
    pendingHapusId = id;
    document.getElementById('konfirmasi-nama').textContent = nama;
    document.getElementById('confirm-overlay').classList.add('open');
}

function tutupKonfirmasi() {
    pendingHapusId = null;
    document.getElementById('confirm-overlay').classList.remove('open');
}

function eksekusiHapus() {
    if (!pendingHapusId) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'includes/handler.php';
    form.innerHTML = `
        <input name="action" value="hapus">
        <input name="id" value="${pendingHapusId}">
    `;
    document.body.appendChild(form);
    form.submit();
}

/* ---- FORMAT RUPIAH ---- */
function formatRupiah(angka) {
    return 'Rp ' + Number(angka).toLocaleString('id-ID');
}

/* ---- PANEL PUBLIC API: DummyJSON Electronics ---- */
async function loadApiProduk() {
    const container = document.getElementById('api-container');
    container.innerHTML = `
        <div class="api-loading">
            <div class="spinner"></div>
            <p>Memuat data produk dari DummyJSON API...</p>
        </div>`;

    try {
        // Ambil kategori smartphones dan laptops dari DummyJSON
        const [resSmartphone, resLaptop] = await Promise.all([
            fetch('https://dummyjson.com/products/category/smartphones?limit=8'),
            fetch('https://dummyjson.com/products/category/laptops?limit=8')
        ]);

        const dataSmartphone = await resSmartphone.json();
        const dataLaptop     = await resLaptop.json();

        const semuaProduk = [...dataSmartphone.products, ...dataLaptop.products];

        if (!semuaProduk.length) {
            container.innerHTML = '<div class="api-error">Tidak ada data yang diterima dari API.</div>';
            return;
        }

        // Update jumlah di counter
        const elJml = document.getElementById('api-count');
        if (elJml) elJml.textContent = semuaProduk.length + ' produk';

        // Render cards
        let html = '<div class="api-grid">';
        semuaProduk.forEach(p => {
            const bintang = 'Rating ' + Number(p.rating).toFixed(1);
            html += `
            <div class="api-card">
                <img src="${p.thumbnail}" alt="${p.title}" loading="lazy">
                <span class="api-badge">${p.category}</span>
                <h4>${p.title}</h4>
                <div class="price">${formatRupiah(Math.round(p.price * 16000))}</div>
                <div class="rating">${bintang} &bull; Stok: ${p.stock}</div>
            </div>`;
        });
        html += '</div>';
        container.innerHTML = html;

    } catch (err) {
        container.innerHTML = `
            <div class="api-error">
                <p>Gagal memuat data API.</p>
                <p style="font-size:12px;margin-top:8px;">Error: ${err.message}</p>
                <button class="btn btn-primary btn-sm" onclick="loadApiProduk()" style="margin-top:14px;">Coba Lagi</button>
            </div>`;
    }
}

/* ---- SEARCH/FILTER TABEL ---- */
function filterTabel() {
    const q = document.getElementById('search-input').value.toLowerCase();
    document.querySelectorAll('#tabel-produk tbody tr').forEach(tr => {
        tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}

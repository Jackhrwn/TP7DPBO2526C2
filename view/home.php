<?php
// home.php: halaman utama (hero dan pilihan cepat)

// Membuat objek class untuk mengambil jumlah data masing-masing tabel
$categoryObj = new Category();
// Objek Menu
$menuObj = new Menu();
// Objek Order
$orderObj = new Order();
// Mengambil jumlah kategori
$totalCategories = $categoryObj->countAll();
// Mengambil jumlah menu
$totalMenus = $menuObj->countAll();
// Mengambil jumlah order
$totalOrders = $orderObj->countAll();
?>
<!-- Kartu hijau besar di bagian atas -->
<section class="hero">
    <!-- Judul hero -->
    <h2>Semua akses ada disini</h2>
    <!-- Deskripsi hero -->
    <p>Kelola order kopi, menu, dan kategori kafe dalam satu tempat.</p>
    <!-- Kumpulan tombol di dalam hero -->
    <div class="hero-actions">
        <!-- Tombol merah menuju halaman Order -->
        <a class="btn" href="index.php?page=orders">Order sekarang</a>
        <!-- Tombol krem menuju halaman Menu -->
        <a class="btn btn-cream" href="index.php?page=menus">Lihat menu</a>
        <!-- Tombol krem menuju halaman Kategori -->
        <a class="btn btn-cream" href="index.php?page=categories">Kategori</a>
    </div>
</section>

<!-- Judul bagian pilihan cepat -->
<h3 class="section-label">PILIHAN CEPAT</h3>

<!-- Kartu berisi daftar pilihan cepat -->
<section class="card list-card">
    <!-- Item 1: Order -->
    <div class="quick-item">
        <!-- Ikon order -->
        <div class="quick-icon">&#128722;</div>
        <!-- Teks item -->
        <div class="quick-text">
            <!-- Judul item -->
            <strong>Order Pelanggan</strong>
            <!-- Jumlah order dari database -->
            <small><?= e($totalOrders) ?> pesanan tercatat.</small>
        </div>
        <!-- Tombol menuju halaman Order -->
        <a class="btn btn-small" href="index.php?page=orders">Buka</a>
    </div>
    <!-- Item 2: Menu -->
    <div class="quick-item">
        <!-- Ikon menu -->
        <div class="quick-icon">&#9749;</div>
        <!-- Teks item -->
        <div class="quick-text">
            <!-- Judul item -->
            <strong>Daftar Menu</strong>
            <!-- Jumlah menu dari database -->
            <small><?= e($totalMenus) ?> menu tersedia.</small>
        </div>
        <!-- Tombol menuju halaman Menu -->
        <a class="btn btn-small" href="index.php?page=menus">Buka</a>
    </div>
    <!-- Item 3: Kategori -->
    <div class="quick-item">
        <!-- Ikon kategori -->
        <div class="quick-icon">&#9776;</div>
        <!-- Teks item -->
        <div class="quick-text">
            <!-- Judul item -->
            <strong>Kategori Menu</strong>
            <!-- Jumlah kategori dari database -->
            <small><?= e($totalCategories) ?> kategori terdaftar.</small>
        </div>
        <!-- Tombol menuju halaman Kategori -->
        <a class="btn btn-small" href="index.php?page=categories">Buka</a>
    </div>
</section>

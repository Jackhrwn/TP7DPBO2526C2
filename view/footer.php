<?php /* footer.php: bagian bawah halaman, berisi navigasi bawah dan penutup HTML */ ?>

<!-- Penutup .container yang dibuka di header.php -->
</div>

<!-- Navigasi tetap di bagian bawah layar seperti gambar referensi -->
<nav class="bottom-nav">
    <!-- Link ke Home; kelas active jika sedang di halaman Home -->
    <a href="index.php?page=home" class="<?= $page === 'home' ? 'active' : '' ?>">
        <!-- Ikon dan teks Home -->
        <span class="ico">&#9638;</span><span>Home</span>
    </a>

    <!-- Link ke halaman Kategori -->
    <a href="index.php?page=categories" class="<?= $page === 'categories' ? 'active' : '' ?>">
        <!-- Ikon dan teks Kategori -->
        <span class="ico">&#9776;</span><span>Kategori</span>
    </a>

    <!-- Link ke halaman Menu -->
    <a href="index.php?page=menus" class="<?= $page === 'menus' ? 'active' : '' ?>">
        <!-- Ikon dan teks Menu -->
        <span class="ico">&#9749;</span><span>Menu</span>
    </a>
    
    <!-- Link ke halaman Order -->
    <a href="index.php?page=orders" class="<?= $page === 'orders' ? 'active' : '' ?>">
        <!-- Ikon dan teks Order -->
        <span class="ico">&#128722;</span><span>Order</span>
    </a>
</nav>

<!-- Footer hak cipta di paling bawah halaman -->
<footer class="copyright">
    <p>&copy; <?= date('Y') ?> Jaka Permana Herawan.</p>
</footer>

</body>
</html>


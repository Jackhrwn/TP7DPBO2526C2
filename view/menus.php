<?php
// menus.php: halaman CRUD tabel menus

// Membuat objek Menu untuk mengakses tabel menus
$menuObj = new Menu();
// Membuat objek Category untuk mengisi pilihan kategori (foreign key)
$categoryObj = new Category();

// CREATE / UPDATE: dijalankan jika tombol "save" ditekan
if (isset($_POST['save'])) {
    // Mengambil id (0 berarti data baru)
    $id = (int) $_POST['id'];
    // Mengambil id kategori yang dipilih
    $category_id = (int) $_POST['category_id'];
    // Mengambil nama menu
    $name = trim($_POST['name']);
    // Mengambil harga dalam bentuk angka
    $price = (int) $_POST['price'];
    // Mengambil deskripsi menu
    $description = trim($_POST['description']);

    // Validasi: kategori harus dipilih, nama wajib, harga tidak negatif
    if ($category_id <= 0 || $name === '' || $price < 0) {
        // Simpan pesan error
        $_SESSION['flash'] = ['type' => 'error', 'text' => 'Kategori, nama, dan harga menu wajib diisi dengan benar.'];
    } elseif ($id > 0) {
        // Jika id ada, berarti UPDATE
        $menuObj->update($id, $category_id, $name, $price, $description);
        // Simpan pesan sukses
        $_SESSION['flash'] = ['type' => 'success', 'text' => 'Menu berhasil diperbarui.'];
    } else {
        // Jika id kosong, berarti CREATE
        $menuObj->create($category_id, $name, $price, $description);
        // Simpan pesan sukses
        $_SESSION['flash'] = ['type' => 'success', 'text' => 'Menu berhasil ditambahkan.'];
    }
    // Redirect agar form tidak terkirim ulang
    header('Location: index.php?page=menus');
    // Hentikan script setelah redirect
    exit;
}

// DELETE: dijalankan jika tombol "delete" ditekan
if (isset($_POST['delete'])) {
    // Mencoba menghapus; bisa gagal jika menu sudah dipakai di order (foreign key)
    try {
        // Menghapus menu berdasarkan id
        $menuObj->delete((int) $_POST['id']);
        // Simpan pesan sukses
        $_SESSION['flash'] = ['type' => 'success', 'text' => 'Menu berhasil dihapus.'];
    } catch (PDOException $e) {
        // Jika gagal karena foreign key, tampilkan penjelasan
        $_SESSION['flash'] = ['type' => 'error', 'text' => 'Menu tidak bisa dihapus karena sudah dipakai pada order.'];
    }
    // Redirect kembali ke halaman menu
    header('Location: index.php?page=menus');
    // Hentikan script setelah redirect
    exit;
}

// Data untuk mode edit: diisi jika URL memiliki ?edit=ID
$editData = null;
// Cek apakah ada parameter edit
if (isset($_GET['edit'])) {
    // Mengambil data menu yang akan diedit
    $editData = $menuObj->getById((int) $_GET['edit']);
}

// Mengambil semua kategori untuk dropdown
$categories = $categoryObj->getAll();
// READ: mengambil semua menu (sudah di-JOIN dengan nama kategori)
$menus = $menuObj->getAll();
?>
<!-- Judul halaman -->
<h3 class="section-label">DAFTAR MENU</h3>

<!-- Kartu form tambah/ubah menu -->
<section class="card form-card">
    <!-- Judul form berubah sesuai mode -->
    <h4><?= $editData ? 'Ubah menu' : 'Tambah menu' ?></h4>
    <!-- Form dikirim dengan method POST -->
    <form method="post" action="index.php?page=menus">
        <!-- Input tersembunyi berisi id (0 = data baru) -->
        <input type="hidden" name="id" value="<?= $editData ? e($editData['id']) : 0 ?>">
        <!-- Label dropdown kategori -->
        <label for="category_id">Kategori</label>

        <!-- Dropdown kategori (foreign key) -->
        <select id="category_id" name="category_id" required>
            <!-- Pilihan awal kosong -->
            <option value="">-- Pilih kategori --</option>
            <!-- Satu option untuk setiap kategori -->
            <?php foreach ($categories as $cat): ?>
                <!-- Option terpilih otomatis saat mode edit -->
                <option value="<?= e($cat['id']) ?>" <?= ($editData && $editData['category_id'] == $cat['id']) ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <!-- Label input nama -->
        <label for="name">Nama menu</label>
        <!-- Input nama menu -->
        <input type="text" id="name" name="name" maxlength="100" required value="<?= $editData ? e($editData['name']) : '' ?>">
        <!-- Label input harga -->
        <label for="price">Harga (Rp)</label>
        <!-- Input harga menu -->
        <input type="number" id="price" name="price" min="0" required value="<?= $editData ? e($editData['price']) : '' ?>">
        <!-- Label input deskripsi -->
        <label for="description">Deskripsi</label>
        <!-- Input deskripsi menu -->
        <input type="text" id="description" name="description" maxlength="255" value="<?= $editData ? e($editData['description']) : '' ?>">

        <!-- Baris tombol form -->
        <div class="form-actions">
            <!-- Tombol simpan -->
            <button type="submit" name="save" class="btn">Simpan</button>
            <?php if ($editData): ?>
                <!-- Tombol batal hanya muncul saat mode edit -->
                <a class="btn btn-cream" href="index.php?page=menus">Batal</a>
            <?php endif; ?>
        </div>
    </form>
</section>

<!-- Kartu berisi tabel menu -->
<section class="card table-card">
    <!-- Pembungkus agar tabel bisa digulir di layar kecil -->
    <div class="table-wrap">
        <!-- Tabel menu -->
        <table>
            <!-- Kepala tabel -->
            <thead>
                <!-- Judul kolom -->
                <tr><th>ID</th><th>Menu</th><th>Kategori</th><th>Harga</th><th>Deskripsi</th><th>Aksi</th></tr>
            </thead>

            <!-- Isi tabel -->
            <tbody>
                <?php if (count($menus) === 0): ?>
                    <!-- Jika data kosong, tampilkan pesan -->
                    <tr><td colspan="6">Belum ada menu. Tambahkan lewat form di atas.</td></tr>
                <?php endif; ?>

                <!-- Perulangan: satu baris untuk setiap menu -->
                <?php foreach ($menus as $row): ?>
                    <tr>
                        <!-- Kolom id -->
                        <td data-label="ID"><?= e($row['id']) ?></td>
                        <!-- Kolom nama menu -->
                        <td data-label="Menu"><strong><?= e($row['name']) ?></strong></td>
                        <!-- Kolom nama kategori hasil JOIN -->
                        <td data-label="Kategori"><?= e($row['category_name']) ?></td>
                        <!-- Kolom harga dengan format rupiah -->
                        <td data-label="Harga">Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
                        <!-- Kolom deskripsi -->
                        <td data-label="Deskripsi"><?= e($row['description']) ?></td>
                        <!-- Kolom tombol aksi -->
                        <td data-label="Aksi" class="actions">
                            <!-- Tombol edit membawa id lewat URL -->
                            <a class="btn btn-small btn-cream" href="index.php?page=menus&edit=<?= e($row['id']) ?>">Edit</a>
                            <!-- Form kecil untuk hapus dengan konfirmasi -->
                            <form method="post" action="index.php?page=menus" onsubmit="return confirm('Hapus menu ini?')">
                                <!-- id data yang dihapus -->
                                <input type="hidden" name="id" value="<?= e($row['id']) ?>">
                                <!-- Tombol hapus -->
                                <button type="submit" name="delete" class="btn btn-small">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

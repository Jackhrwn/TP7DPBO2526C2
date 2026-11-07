<?php
// categories.php: halaman CRUD tabel categories

// Membuat objek Category untuk mengakses tabel categories
$categoryObj = new Category();

// CREATE / UPDATE: dijalankan jika tombol "save" ditekan
if (isset($_POST['save'])) {
    // Mengambil id (0 berarti data baru)
    $id = (int) $_POST['id'];
    // Mengambil nama kategori dan membuang spasi di pinggir
    $name = trim($_POST['name']);
    // Mengambil deskripsi kategori
    $description = trim($_POST['description']);

    // Validasi: nama tidak boleh kosong
    if ($name === '') {
        // Simpan pesan error ke session
        $_SESSION['flash'] = ['type' => 'error', 'text' => 'Nama kategori wajib diisi.'];
    } elseif ($id > 0) {
        // Jika id ada, berarti UPDATE
        $categoryObj->update($id, $name, $description);
        // Simpan pesan sukses
        $_SESSION['flash'] = ['type' => 'success', 'text' => 'Kategori berhasil diperbarui.'];
    } else {
        // Jika id kosong, berarti CREATE
        $categoryObj->create($name, $description);
        // Simpan pesan sukses
        $_SESSION['flash'] = ['type' => 'success', 'text' => 'Kategori berhasil ditambahkan.'];
    }
    // Redirect agar form tidak terkirim ulang saat halaman di-refresh
    header('Location: index.php?page=categories');
    // Hentikan script setelah redirect
    exit;
}

// DELETE: dijalankan jika tombol "delete" ditekan
if (isset($_POST['delete'])) {
    // Mencoba menghapus; bisa gagal jika kategori masih dipakai menu (foreign key)
    try {
        // Menghapus kategori berdasarkan id
        $categoryObj->delete((int) $_POST['id']);
        // Simpan pesan sukses
        $_SESSION['flash'] = ['type' => 'success', 'text' => 'Kategori berhasil dihapus.'];
    } catch (PDOException $e) {
        // Jika gagal karena foreign key, tampilkan penjelasan
        $_SESSION['flash'] = ['type' => 'error', 'text' => 'Kategori tidak bisa dihapus karena masih dipakai oleh menu.'];
    }
    // Redirect kembali ke halaman kategori
    header('Location: index.php?page=categories');
    // Hentikan script setelah redirect
    exit;
}

// Data untuk mode edit: diisi jika URL memiliki ?edit=ID
$editData = null;
// Cek apakah ada parameter edit
if (isset($_GET['edit'])) {
    // Mengambil data kategori yang akan diedit
    $editData = $categoryObj->getById((int) $_GET['edit']);
}

// READ: mengambil semua kategori untuk ditampilkan di tabel
$categories = $categoryObj->getAll();
?>
<!-- Judul halaman -->
<h3 class="section-label">KATEGORI MENU</h3>

<!-- Kartu form tambah/ubah kategori -->
<section class="card form-card">
    <!-- Judul form berubah sesuai mode -->
    <h4><?= $editData ? 'Ubah kategori' : 'Tambah kategori' ?></h4>
    <!-- Form dikirim dengan method POST -->
    <form method="post" action="index.php?page=categories">
        <!-- Input tersembunyi berisi id (0 = data baru) -->
        <input type="hidden" name="id" value="<?= $editData ? e($editData['id']) : 0 ?>">
        <!-- Label input nama -->
        <label for="name">Nama kategori</label>
        <!-- Input nama kategori -->
        <input type="text" id="name" name="name" maxlength="100" required value="<?= $editData ? e($editData['name']) : '' ?>">
        <!-- Label input deskripsi -->
        <label for="description">Deskripsi</label>
        <!-- Input deskripsi kategori -->
        <input type="text" id="description" name="description" maxlength="255" value="<?= $editData ? e($editData['description']) : '' ?>">
        <!-- Baris tombol form -->
        <div class="form-actions">
            <!-- Tombol simpan -->
            <button type="submit" name="save" class="btn">Simpan</button>
            <?php if ($editData): ?>
                <!-- Tombol batal hanya muncul saat mode edit -->
                <a class="btn btn-cream" href="index.php?page=categories">Batal</a>
            <?php endif; ?>
        </div>
    </form>
</section>

<!-- Kartu berisi tabel kategori -->
<section class="card table-card">
    <!-- Pembungkus agar tabel bisa digulir di layar kecil -->
    <div class="table-wrap">
        <!-- Tabel kategori -->
        <table>
            <!-- Kepala tabel -->
            <thead>
                <!-- Judul kolom -->
                <tr><th>ID</th><th>Nama</th><th>Deskripsi</th><th>Aksi</th></tr>
            </thead>
            
            <!-- Isi tabel -->
            <tbody>
                <?php if (count($categories) === 0): ?>
                    <!-- Jika data kosong, tampilkan pesan -->
                    <tr><td colspan="4">Belum ada kategori. Tambahkan lewat form di atas.</td></tr>
                <?php endif; ?>

                <!-- Perulangan: satu baris untuk setiap kategori -->
                <?php foreach ($categories as $row): ?>
                    <tr>
                        <!-- Kolom id -->
                        <td data-label="ID"><?= e($row['id']) ?></td>
                        <!-- Kolom nama -->
                        <td data-label="Nama"><?= e($row['name']) ?></td>
                        <!-- Kolom deskripsi -->
                        <td data-label="Deskripsi"><?= e($row['description']) ?></td>
                        <!-- Kolom tombol aksi -->
                        <td data-label="Aksi" class="actions">
                            <!-- Tombol edit membawa id lewat URL -->
                            <a class="btn btn-small btn-cream" href="index.php?page=categories&edit=<?= e($row['id']) ?>">Edit</a>
                            <!-- Form kecil untuk hapus dengan konfirmasi -->
                            <form method="post" action="index.php?page=categories" onsubmit="return confirm('Hapus kategori ini?')">
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

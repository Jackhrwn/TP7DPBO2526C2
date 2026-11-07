<?php
// orders.php: halaman CRUD tabel orders

// Membuat objek Order untuk mengakses tabel orders
$orderObj = new Order();
// Membuat objek Menu untuk mengisi pilihan menu (foreign key)
$menuObj = new Menu();

// Daftar status pesanan yang diperbolehkan
$statuses = ['Pending', 'Diproses', 'Selesai'];

// CREATE / UPDATE: dijalankan jika tombol "save" ditekan
if (isset($_POST['save'])) {
    // Mengambil id (0 berarti data baru)
    $id = (int) $_POST['id'];
    // Mengambil id menu yang dipilih
    $menu_id = (int) $_POST['menu_id'];
    // Mengambil nama pelanggan
    $customer_name = trim($_POST['customer_name']);
    // Mengambil jumlah pesanan
    $quantity = (int) $_POST['quantity'];
    // Mengambil status; jika tidak valid, pakai 'Pending'
    $status = in_array($_POST['status'], $statuses) ? $_POST['status'] : 'Pending';

    // Validasi: menu dipilih, nama wajib, jumlah minimal 1
    if ($menu_id <= 0 || $customer_name === '' || $quantity < 1) {
        // Simpan pesan error
        $_SESSION['flash'] = ['type' => 'error', 'text' => 'Menu, nama pelanggan, dan jumlah (minimal 1) wajib diisi.'];
    } elseif ($id > 0) {
        // Jika id ada, berarti UPDATE
        $orderObj->update($id, $menu_id, $customer_name, $quantity, $status);
        // Simpan pesan sukses
        $_SESSION['flash'] = ['type' => 'success', 'text' => 'Order berhasil diperbarui.'];
    } else {
        // Jika id kosong, berarti CREATE
        $orderObj->create($menu_id, $customer_name, $quantity, $status);
        // Simpan pesan sukses
        $_SESSION['flash'] = ['type' => 'success', 'text' => 'Order berhasil ditambahkan.'];
    }
    // Redirect agar form tidak terkirim ulang
    header('Location: index.php?page=orders');
    // Hentikan script setelah redirect
    exit;
}

// DELETE: dijalankan jika tombol "delete" ditekan
if (isset($_POST['delete'])) {
    // Menghapus order berdasarkan id
    $orderObj->delete((int) $_POST['id']);
    // Simpan pesan sukses
    $_SESSION['flash'] = ['type' => 'success', 'text' => 'Order berhasil dihapus.'];
    // Redirect kembali ke halaman order
    header('Location: index.php?page=orders');
    // Hentikan script setelah redirect
    exit;
}

// Data untuk mode edit: diisi jika URL memiliki ?edit=ID
$editData = null;
// Cek apakah ada parameter edit
if (isset($_GET['edit'])) {
    // Mengambil data order yang akan diedit
    $editData = $orderObj->getById((int) $_GET['edit']);
}

// Mengambil semua menu untuk dropdown
$menus = $menuObj->getAll();
// READ: mengambil semua order (sudah di-JOIN dengan nama menu)
$orders = $orderObj->getAll();
?>
<h3 class="section-label">ORDER PELANGGAN</h3>
<!-- Judul halaman -->

<section class="card form-card">
    <!-- Kartu form tambah/ubah order -->
    <h4><?= $editData ? 'Ubah order' : 'Tambah order' ?></h4>
    <!-- Judul form berubah sesuai mode -->
    <form method="post" action="index.php?page=orders">
        <!-- Form dikirim dengan method POST -->
        <input type="hidden" name="id" value="<?= $editData ? e($editData['id']) : 0 ?>">
        <!-- Input tersembunyi berisi id (0 = data baru) -->
        <label for="customer_name">Nama pelanggan</label>
        <!-- Label input nama pelanggan -->
        <input type="text" id="customer_name" name="customer_name" maxlength="100" required value="<?= $editData ? e($editData['customer_name']) : '' ?>">
        <!-- Input nama pelanggan -->
        <label for="menu_id">Menu</label>
        <!-- Label dropdown menu -->
        <select id="menu_id" name="menu_id" required>
            <!-- Dropdown menu (foreign key) -->
            <option value="">-- Pilih menu --</option>
            <!-- Pilihan awal kosong -->
            <?php foreach ($menus as $m): ?>
                <!-- Satu option untuk setiap menu -->
                <option value="<?= e($m['id']) ?>" <?= ($editData && $editData['menu_id'] == $m['id']) ? 'selected' : '' ?>><?= e($m['name']) ?> - Rp <?= number_format($m['price'], 0, ',', '.') ?></option>
                <!-- Option menampilkan nama dan harga menu -->
            <?php endforeach; ?>
        </select>
        <label for="quantity">Jumlah</label>
        <!-- Label input jumlah -->
        <input type="number" id="quantity" name="quantity" min="1" required value="<?= $editData ? e($editData['quantity']) : 1 ?>">
        <!-- Input jumlah pesanan -->
        <label for="status">Status</label>
        <!-- Label dropdown status -->
        <select id="status" name="status">
            <!-- Dropdown status pesanan -->
            <?php foreach ($statuses as $s): ?>
                <!-- Satu option untuk setiap status -->
                <option value="<?= e($s) ?>" <?= ($editData && $editData['status'] === $s) ? 'selected' : '' ?>><?= e($s) ?></option>
                <!-- Option status terpilih saat mode edit -->
            <?php endforeach; ?>
        </select>
        <div class="form-actions">
            <!-- Baris tombol form -->
            <button type="submit" name="save" class="btn">Simpan</button>
            <!-- Tombol simpan -->
            <?php if ($editData): ?>
                <!-- Tombol batal hanya muncul saat mode edit -->
                <a class="btn btn-cream" href="index.php?page=orders">Batal</a>
            <?php endif; ?>
        </div>
    </form>
</section>

<!-- Kartu berisi tabel order -->
<section class="card table-card">
    <!-- Pembungkus agar tabel bisa digulir di layar kecil -->
    <div class="table-wrap">
        <!-- Tabel order -->
        <table>
            <!-- Kepala tabel -->
            <thead>
                <!-- Judul kolom -->
                <tr><th>ID</th><th>Pelanggan</th><th>Menu</th><th>Jumlah</th><th>Total</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
            </thead>

            <!-- Isi tabel -->
            <tbody>
                <?php if (count($orders) === 0): ?>
                    <!-- Jika data kosong, tampilkan pesan -->
                    <tr><td colspan="8">Belum ada order. Tambahkan lewat form di atas.</td></tr>
                <?php endif; ?>

                <!-- Perulangan: satu baris untuk setiap order -->
                <?php foreach ($orders as $row): ?>
                    <tr>
                        <!-- Kolom id -->
                        <td data-label="ID"><?= e($row['id']) ?></td>
                        <!-- Kolom nama pelanggan -->
                        <td data-label="Pelanggan"><strong><?= e($row['customer_name']) ?></strong></td>
                        <!-- Kolom nama menu hasil JOIN -->
                        <td data-label="Menu"><?= e($row['menu_name']) ?></td>
                        <!-- Kolom jumlah -->
                        <td data-label="Jumlah"><?= e($row['quantity']) ?></td>
                        <!-- Kolom total harga dengan format rupiah -->
                        <td data-label="Total">Rp <?= number_format($row['total_price'], 0, ',', '.') ?></td>
                        <!-- Kolom status berbentuk badge berwarna -->
                        <td data-label="Status"><span class="badge badge-<?= e(strtolower($row['status'])) ?>"><?= e($row['status']) ?></span></td>
                        <!-- Kolom tanggal order -->
                        <td data-label="Tanggal"><?= e($row['order_date']) ?></td>
                        <!-- Kolom tombol aksi -->
                        <td data-label="Aksi" class="actions">
                            <!-- Tombol edit membawa id lewat URL -->
                            <a class="btn btn-small btn-cream" href="index.php?page=orders&edit=<?= e($row['id']) ?>">Edit</a>
                            <!-- Form kecil untuk hapus dengan konfirmasi -->
                            <form method="post" action="index.php?page=orders" onsubmit="return confirm('Hapus order ini?')">
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

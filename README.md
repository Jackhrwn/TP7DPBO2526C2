# ☕ Maja Koffie - Sistem Manajemen Café

Tugas Praktikum Modul 8: **OOP dalam Website PHP dengan GUI** (PHP Native + PDO + MySQL).

## ❤️ Janji

Saya Jaka Permana Herawan dengan NIM 2509371 mengerjakan Tugas Praktikum 7 pada Mata Kuliah Desain dan
Pemrograman Berorientasi Objek (DPBO) untuk keberkahan-Nya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin

## 1. Tema Website
Maja Koffie adalah website sederhana untuk mengelola data sebuah café: **kategori menu**, **daftar menu**, dan **order pelanggan**. Responsif untuk desktop, tablet, dan smartphone.

## 2. Database (`db_cafe`)
File SQL: `database/db_cafe.sql` (berisi struktur tabel dan data awal).

| Tabel | Kolom | Keterangan |
|---|---|---|
| `categories` | `id` (PK), `name`, `description` | Kategori menu, misalnya Kopi, Non-Kopi, Snack |
| `menus` | `id` (PK), `category_id` (FK), `name`, `price`, `description` | Daftar menu; FK ke `categories.id` |
| `orders` | `id` (PK), `menu_id` (FK), `customer_name`, `quantity`, `total_price`, `status`, `order_date` | Pesanan pelanggan; FK ke `menus.id` |

Relasi:
```
categories (1) ----< menus (1) ----< orders
```
- Satu kategori punya banyak menu, dan satu menu bisa dipesan di banyak order.
- Kategori yang masih dipakai menu, dan menu yang masih dipakai order, **tidak bisa dihapus** (foreign key). Website menampilkan pesan penjelasan.
- `total_price` dihitung otomatis oleh class `Order` (harga menu x jumlah).
- Status order: `Pending`, `Diproses`, `Selesai`.

## 3. Struktur Folder
```
maja-koffie/
├── class/
│   ├── Category.php   -> class untuk tabel categories
│   ├── Menu.php       -> class untuk tabel menus
│   └── Order.php      -> class untuk tabel orders
├── config/
│   └── db.php         -> class Database (koneksi PDO)
├── database/
│   └── db_cafe.sql    -> struktur dan data database
├── Dokumentasi        
│   ├── Dokumentasi CRUD Website.mp4 -> dokumentasi program website(Menampilkan sistem CRUD)
│   └── Dokuementasi Foreign Key.mp4 -> dokumentasi mekanisme foreign key
├── view/
│   ├── header.php     -> bagian atas halaman (logo, jam buka, notifikasi)
│   ├── footer.php     -> navigasi bawah dan penutup HTML
│   ├── home.php       -> halaman utama
│   ├── categories.php -> CRUD kategori
│   ├── menus.php      -> CRUD menu
│   └── orders.php     -> CRUD order
├── index.php          -> titik awal aplikasi (router sederhana)
├── style.css          -> styling halaman
└── README.md
```

## 4. Flow Program
Mengikuti flow standar pada modul:

1. **Browser** membuka `index.php?page=menus` (HTTP Request).
2. **`index.php`** memeriksa nilai `page` (hanya `home`, `categories`, `menus`, `orders` yang diizinkan), lalu meng-include `view/header.php`, `view/<page>.php`, dan `view/footer.php`.
3. **File view** (misalnya `view/menus.php`) membuat objek class dari folder `class/` dan:
   - jika ada aksi form (`$_POST['save']` atau `$_POST['delete']`), view memanggil method class (`create`, `update`, `delete`);
   - lalu memanggil `getAll()` untuk mengambil data tabel dalam bentuk array PHP.
4. **Class** (`Menu`, `Category`, `Order`) menjalankan query ke database lewat koneksi PDO dari `config/db.php`.
5. Hasil query kembali ke **view**, ditampilkan sebagai HTML (tabel dan form), lalu dikirim ke browser sebagai HTTP Response.
6. Setelah create/update/delete, view melakukan **redirect** (`header('Location: ...')`) supaya form tidak terkirim ulang saat halaman di-refresh, dan menampilkan pesan notifikasi lewat session.

Contoh alur tambah order:
`view/orders.php` (form disubmit) -> `Order::create()` -> `Order::hitungTotal()` memanggil `Menu::getById()` untuk mengambil harga -> `INSERT` ke tabel `orders` -> redirect -> `Order::getAll()` -> tabel order terbarui.

## 5. Penerapan Ketentuan Tugas
- **PHP Native** dan konsep OOP: satu class per tabel, satu class `Database` untuk koneksi.
- **3 tabel** dengan **2 foreign key** (`menus.category_id`, `orders.menu_id`).
- **Prepared statement untuk seluruh query**: semua query memakai `$this->db->prepare(...)` lalu `execute(...)`. Tidak ada `query()` mentah.
- **CRUD lengkap** untuk ketiga entitas (Create, Read, Update, Delete).
- Semua output ke HTML diamankan dengan fungsi `e()` (`htmlspecialchars`).

## 6. Cara Menjalankan
1. Jalankan **Apache** dan **MySQL** di Laragon / XAMPP.
2. Salin folder `TP7DPBO2526C2` ke `www` (Laragon) atau `htdocs` (XAMPP).
3. Import `database/db_cafe.sql` lewat phpMyAdmin (atau salin isinya lalu execute).
4. Jika username/password MySQL berbeda, ubah di `config/db.php`.
5. Buka `http://localhost/TP7DPBO2526C2/` di browser.

## 7. Dokumentasi
### Dokumentasi Program Website(Menampilkan sistem CRUD)

### Dokumentasi Mekanisme Foreign Key
---



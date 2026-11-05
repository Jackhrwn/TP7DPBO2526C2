-- Membuat database db_cafe jika belum ada
CREATE DATABASE IF NOT EXISTS db_cafe;
-- Memilih database db_cafe untuk dipakai
USE db_cafe;

-- Menghapus tabel lama (anak dulu, baru induk) agar script bisa dijalankan ulang
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS menus;
DROP TABLE IF EXISTS categories;

-- Tabel 1: categories (kategori menu)
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,   -- id unik kategori
    name VARCHAR(100) NOT NULL,          -- nama kategori
    description VARCHAR(255)             -- keterangan kategori
);

-- Tabel 2: menus (menu kafe), punya foreign key ke categories
CREATE TABLE menus (
    id INT AUTO_INCREMENT PRIMARY KEY,   -- id unik menu
    category_id INT NOT NULL,            -- foreign key ke categories.id
    name VARCHAR(100) NOT NULL,          -- nama menu
    price INT NOT NULL,                  -- harga menu (rupiah)
    description VARCHAR(255),            -- keterangan menu
    FOREIGN KEY (category_id) REFERENCES categories(id)  -- relasi ke tabel categories
);

-- Tabel 3: orders (pesanan), punya foreign key ke menus
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,   -- id unik pesanan
    menu_id INT NOT NULL,                -- foreign key ke menus.id
    customer_name VARCHAR(100) NOT NULL, -- nama pelanggan
    quantity INT NOT NULL,               -- jumlah item yang dipesan
    total_price INT NOT NULL,            -- total harga (harga x jumlah)
    status VARCHAR(20) NOT NULL DEFAULT 'Pending', -- status: Pending / Diproses / Selesai
    order_date DATE NOT NULL,            -- tanggal pesanan dibuat
    FOREIGN KEY (menu_id) REFERENCES menus(id)  -- relasi ke tabel menus
);

-- Data awal untuk tabel categories
INSERT INTO categories (name, description) VALUES
('Kopi', 'Minuman berbasis espresso dan seduhan manual'),
('Non-Kopi', 'Minuman segar tanpa kopi'),
('Snack', 'Camilan untuk teman ngopi');

-- Data awal untuk tabel menus
INSERT INTO menus (category_id, name, price, description) VALUES
(1, 'Kopi Susu Maja', 22000, 'Espresso, susu segar, dan gula aren'),
(1, 'Americano', 18000, 'Espresso dengan air panas'),
(2, 'Matcha Latte', 25000, 'Matcha dengan susu'),
(3, 'Roti Bakar Coklat', 15000, 'Roti bakar dengan selai coklat');

-- Data awal untuk tabel orders
INSERT INTO orders (menu_id, customer_name, quantity, total_price, status, order_date) VALUES
(1, 'Rina', 2, 44000, 'Selesai', CURDATE()),
(3, 'Dimas', 1, 25000, 'Diproses', CURDATE()),
(4, 'Salsa', 3, 45000, 'Pending', CURDATE());

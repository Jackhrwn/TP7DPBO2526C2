<?php
// Memanggil file konfigurasi database satu kali saja
require_once __DIR__ . '/../config/db.php';
// Memanggil class Menu karena harga menu dibutuhkan untuk menghitung total
require_once __DIR__ . '/Menu.php';

// Class Order: mengontrol tabel orders (pesanan pelanggan)
class Order
{
    // Properti privat untuk menyimpan koneksi database
    private $db;

    // Constructor: dijalankan otomatis saat objek Order dibuat
    public function __construct()
    {
        // Membuat objek Database
        $database = new Database();
        // Mengambil koneksi PDO dan menyimpannya ke properti $db
        $this->db = $database->connect();
    }

    // Method privat: menghitung total harga = harga menu x jumlah
    private function hitungTotal($menu_id, $quantity)
    {
        // Membuat objek Menu untuk mengambil data harga
        $menu = new Menu();
        // Mengambil data menu berdasarkan id
        $data = $menu->getById($menu_id);
        // Jika menu tidak ditemukan, total dianggap 0
        if (!$data) {
            return 0;
        }
        // Mengembalikan harga dikali jumlah pesanan
        return $data['price'] * $quantity;
    }

    // READ: mengambil semua pesanan beserta nama menunya (JOIN)
    public function getAll()
    {
        // Query JOIN orders dengan menus lewat foreign key menu_id
        $stmt = $this->db->prepare(
            "SELECT o.*, m.name AS menu_name
             FROM orders o
             JOIN menus m ON o.menu_id = m.id
             ORDER BY o.id DESC"
        );
        // Menjalankan query
        $stmt->execute();
        // Mengembalikan semua baris sebagai array
        return $stmt->fetchAll();
    }

    // READ: mengambil satu pesanan berdasarkan id
    public function getById($id)
    {
        // Menyiapkan query dengan placeholder ? untuk id
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = ?");
        // Menjalankan query dengan mengisi placeholder
        $stmt->execute([$id]);
        // Mengembalikan satu baris data (atau false jika tidak ada)
        return $stmt->fetch();
    }

    // CREATE: menambah pesanan baru
    public function create($menu_id, $customer_name, $quantity, $status)
    {
        // Menghitung total harga pesanan
        $total = $this->hitungTotal($menu_id, $quantity);
        // Menyiapkan query insert dengan placeholder
        $stmt = $this->db->prepare(
            "INSERT INTO orders (menu_id, customer_name, quantity, total_price, status, order_date)
             VALUES (?, ?, ?, ?, ?, CURDATE())"
        );
        // Menjalankan query dan mengembalikan true/false
        return $stmt->execute([$menu_id, $customer_name, $quantity, $total, $status]);
    }

    // UPDATE: mengubah data pesanan
    public function update($id, $menu_id, $customer_name, $quantity, $status)
    {
        // Menghitung ulang total harga karena menu/jumlah bisa berubah
        $total = $this->hitungTotal($menu_id, $quantity);
        // Menyiapkan query update dengan placeholder
        $stmt = $this->db->prepare(
            "UPDATE orders
             SET menu_id = ?, customer_name = ?, quantity = ?, total_price = ?, status = ?
             WHERE id = ?"
        );
        // Menjalankan query dan mengembalikan true/false
        return $stmt->execute([$menu_id, $customer_name, $quantity, $total, $status, $id]);
    }

    // DELETE: menghapus pesanan berdasarkan id
    public function delete($id)
    {
        // Menyiapkan query delete dengan placeholder
        $stmt = $this->db->prepare("DELETE FROM orders WHERE id = ?");
        // Menjalankan query dan mengembalikan true/false
        return $stmt->execute([$id]);
    }

    // Menghitung jumlah pesanan (dipakai di halaman Home)
    public function countAll()
    {
        // Menyiapkan query COUNT
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM orders");
        // Menjalankan query
        $stmt->execute();
        // Mengambil satu baris hasil
        $row = $stmt->fetch();
        // Mengembalikan angka total
        return (int) $row['total'];
    }
}

<?php
// Memanggil file konfigurasi database satu kali saja
require_once __DIR__ . '/../config/db.php';

// Class Menu: mengontrol tabel menus (daftar menu kafe)
class Menu
{
    // Properti privat untuk menyimpan koneksi database
    private $db;

    // Constructor: dijalankan otomatis saat objek Menu dibuat
    public function __construct()
    {
        // Membuat objek Database
        $database = new Database();
        // Mengambil koneksi PDO dan menyimpannya ke properti $db
        $this->db = $database->connect();
    }

    // READ: mengambil semua menu beserta nama kategorinya (JOIN)
    public function getAll()
    {
        // Query JOIN menus dengan categories lewat foreign key category_id
        $stmt = $this->db->prepare(
            "SELECT m.*, c.name AS category_name
             FROM menus m
             JOIN categories c ON m.category_id = c.id
             ORDER BY m.id ASC"
        );
        // Menjalankan query
        $stmt->execute();
        // Mengembalikan semua baris sebagai array
        return $stmt->fetchAll();
    }

    // READ: mengambil satu menu berdasarkan id
    public function getById($id)
    {
        // Menyiapkan query dengan placeholder ? untuk id
        $stmt = $this->db->prepare("SELECT * FROM menus WHERE id = ?");
        // Menjalankan query dengan mengisi placeholder
        $stmt->execute([$id]);
        // Mengembalikan satu baris data (atau false jika tidak ada)
        return $stmt->fetch();
    }

    // CREATE: menambah menu baru
    public function create($category_id, $name, $price, $description)
    {
        // Menyiapkan query insert dengan placeholder
        $stmt = $this->db->prepare(
            "INSERT INTO menus (category_id, name, price, description) VALUES (?, ?, ?, ?)"
        );
        // Menjalankan query dan mengembalikan true/false
        return $stmt->execute([$category_id, $name, $price, $description]);
    }

    // UPDATE: mengubah data menu
    public function update($id, $category_id, $name, $price, $description)
    {
        // Menyiapkan query update dengan placeholder
        $stmt = $this->db->prepare(
            "UPDATE menus SET category_id = ?, name = ?, price = ?, description = ? WHERE id = ?"
        );
        // Menjalankan query dan mengembalikan true/false
        return $stmt->execute([$category_id, $name, $price, $description, $id]);
    }

    // DELETE: menghapus menu berdasarkan id
    public function delete($id)
    {
        // Menyiapkan query delete dengan placeholder
        $stmt = $this->db->prepare("DELETE FROM menus WHERE id = ?");
        // Menjalankan query dan mengembalikan true/false
        return $stmt->execute([$id]);
    }

    // Menghitung jumlah menu (dipakai di halaman Home)
    public function countAll()
    {
        // Menyiapkan query COUNT
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM menus");
        // Menjalankan query
        $stmt->execute();
        // Mengambil satu baris hasil
        $row = $stmt->fetch();
        // Mengembalikan angka total
        return (int) $row['total'];
    }
}

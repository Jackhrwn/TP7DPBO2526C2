<?php
// Memanggil file konfigurasi database satu kali saja
require_once __DIR__ . '/../config/db.php';

// Class Category: mengontrol tabel categories (kategori menu)
class Category
{
    // Properti privat untuk menyimpan koneksi database
    private $db;

    // Constructor: dijalankan otomatis saat objek Category dibuat
    public function __construct()
    {
        // Membuat objek Database
        $database = new Database();
        // Mengambil koneksi PDO dan menyimpannya ke properti $db
        $this->db = $database->connect();
    }

    // READ: mengambil semua kategori
    public function getAll()
    {
        // Menyiapkan query dengan prepared statement
        $stmt = $this->db->prepare("SELECT * FROM categories ORDER BY id ASC");
        // Menjalankan query
        $stmt->execute();
        // Mengembalikan semua baris sebagai array
        return $stmt->fetchAll();
    }

    
    // READ: mengambil satu kategori berdasarkan id
    public function getById($id)
    {
        // Menyiapkan query dengan placeholder ? untuk id
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = ?");
        // Menjalankan query dengan mengisi placeholder
        $stmt->execute([$id]);
        // Mengembalikan satu baris data (atau false jika tidak ada)
        return $stmt->fetch();
    }

    // CREATE: menambah kategori baru
    public function create($name, $description)
    {
        // Menyiapkan query insert dengan placeholder
        $stmt = $this->db->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        // Menjalankan query dan mengembalikan true/false
        return $stmt->execute([$name, $description]);
    }

    // UPDATE: mengubah data kategori
    public function update($id, $name, $description)
    {
        // Menyiapkan query update dengan placeholder
        $stmt = $this->db->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
        // Menjalankan query dan mengembalikan true/false
        return $stmt->execute([$name, $description, $id]);
    }

    // DELETE: menghapus kategori berdasarkan id
    public function delete($id)
    {
        // Menyiapkan query delete dengan placeholder
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ?");
        // Menjalankan query dan mengembalikan true/false
        return $stmt->execute([$id]);
    }

    // Menghitung jumlah kategori (dipakai di halaman Home)
    public function countAll()
    {
        // Menyiapkan query COUNT
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM categories");
        // Menjalankan query
        $stmt->execute();
        // Mengambil satu baris hasil
        $row = $stmt->fetch();
        // Mengembalikan angka total
        return (int) $row['total'];
    }
}

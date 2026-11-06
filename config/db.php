<?php
// File konfigurasi: berisi class Database untuk koneksi ke MySQL memakai PDO

// Mendefinisikan class Database
class Database
{
    // Alamat server database (Laragon/XAMPP default: localhost)
    private $host = "localhost";
    // Nama database yang dipakai aplikasi
    private $db_name = "db_cafe";
    // Username MySQL (default Laragon/XAMPP: root)
    private $username = "root";
    // Password MySQL (default Laragon/XAMPP: kosong)
    private $password = "";
    // Properti publik untuk menyimpan objek koneksi PDO
    public $conn;

    // Method untuk membuat dan mengembalikan koneksi database
    public function connect()
    {
        // Awalnya koneksi dikosongkan
        $this->conn = null;

        // Blok try untuk menangkap error koneksi
        try {
            // Membuat objek PDO dengan driver mysql
            $this->conn = new PDO(
                "mysql:host=$this->host;dbname=$this->db_name;charset=utf8mb4", // DSN koneksi
                $this->username, // username database
                $this->password  // password database
            );
            // Mengaktifkan mode exception agar error SQL bisa ditangkap dengan try-catch
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Hasil fetch default berupa array asosiatif (nama kolom => nilai)
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Jika gagal konek, hentikan program dan tampilkan pesan error
            die("Koneksi database gagal: " . $e->getMessage());
        }

        // Mengembalikan objek koneksi ke class yang memanggil
        return $this->conn;
    }
}

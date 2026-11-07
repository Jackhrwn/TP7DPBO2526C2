<?php
// index.php: titik awal aplikasi (router sederhana). Semua halaman dibuka lewat file ini.

// Memulai output buffering agar fungsi header() (redirect) tetap bisa dipanggil dari file view
ob_start();
// Memulai session untuk menyimpan pesan notifikasi (flash message) antar halaman
session_start();

// Memanggil semua class yang dibutuhkan oleh view (satu kali saja)
require_once 'class/Category.php';
require_once 'class/Menu.php';
require_once 'class/Order.php';

// Fungsi bantu untuk mengamankan teks sebelum ditampilkan di HTML (mencegah XSS)
function e($value)
{
    // Mengubah karakter khusus HTML menjadi aman
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// Daftar halaman yang diizinkan beserta judulnya
$pages = [
    'home'       => 'Home',      // halaman utama
    'categories' => 'Kategori',  // halaman CRUD kategori
    'menus'      => 'Menu',      // halaman CRUD menu
    'orders'     => 'Order',     // halaman CRUD order
];

// Mengambil nama halaman dari URL (?page=...), default 'home'
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
// Jika halaman tidak ada di daftar, kembalikan ke 'home' (mencegah include file sembarangan)
if (!array_key_exists($page, $pages)) {
    $page = 'home';
}

// Menampilkan bagian atas halaman (head, logo, judul)
include 'view/header.php';
// Menampilkan isi halaman sesuai $page (view memanggil class lalu menampilkan hasilnya)
include 'view/' . $page . '.php';
// Menampilkan bagian bawah halaman (navigasi bawah, penutup HTML)
include 'view/footer.php';

// Mengirim semua output yang tertahan ke browser
ob_end_flush();

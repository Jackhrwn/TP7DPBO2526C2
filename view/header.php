<?php /* header.php: bagian atas halaman yang dipakai semua halaman */ ?>
<!-- Dokumen HTML5 -->
<!DOCTYPE html>
<!-- Bahasa halaman: Indonesia -->
<html lang="id">
<!-- Bagian head berisi pengaturan halaman -->
<head>
    <!-- Encoding karakter UTF-8 -->
    <meta charset="UTF-8">
    <!-- Agar tampilan responsif di smartphone/tablet -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Judul tab browser sesuai halaman -->
    <title>Maja Koffie - <?= e($pages[$page]) ?></title>
    <!-- Mempercepat koneksi ke Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <!-- Memuat font Space Grotesk -->
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Memuat file styling -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
<!-- Awal isi halaman -->
<!-- Pembungkus utama agar konten rapi dan di tengah -->
<div class="container">

    <!-- Bagian logo dan nama kafe -->
    <header class="brand">
        <!-- Logo jendela lengkung -->
        <svg class="logo" viewBox="0 0 64 72" aria-hidden="true">
            <!-- Garis luar lengkungan -->
            <path d="M6 68V30C6 14 18 4 32 4s26 10 26 26v38z" fill="none" stroke="#8e2316" stroke-width="4"/>
            <!-- Garis dalam lengkungan -->
            <path d="M16 68V32c0-10 7-17 16-17s16 7 16 17v36" fill="none" stroke="#8e2316" stroke-width="4"/>
            <!-- Garis kusen jendela -->
            <path d="M32 15v53M16 42h32" stroke="#8e2316" stroke-width="3"/>
        </svg>

        <!-- Kolom teks brand -->
        <div>
            <!-- Nama kafe -->
            <h1 class="brand-name">MAJA KOFFIE</h1>
            <!-- Tagline kafe -->
            <p class="brand-tag">Your Favourite Warm Place To Be.</p>
        </div>
    </header>

    <!-- Label jam buka dengan titik hijau -->
    <div class="open-pill"><span class="dot"></span>Buka setiap hari 08.00 - 21.00</div>

    <!-- Jika ada pesan notifikasi di session, tampilkan -->
    <?php if (isset($_SESSION['flash'])): ?>
        <!-- Kotak pesan (hijau = sukses, merah = error) -->
        <div class="flash <?= e($_SESSION['flash']['type']) ?>"><?= e($_SESSION['flash']['text']) ?></div>
        <!-- Hapus pesan agar tidak muncul lagi saat halaman dimuat ulang -->
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

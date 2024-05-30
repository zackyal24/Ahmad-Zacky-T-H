<?php
include 'koneksi.php';

// Get the count of peminjaman
$peminjaman_sql = "SELECT COUNT(*) AS total_peminjaman FROM peminjaman";
$peminjaman_result = $conn->query($peminjaman_sql);
$peminjaman_data = $peminjaman_result->fetch_assoc();
$total_peminjaman = $peminjaman_data['total_peminjaman'];

// Get the count of pengembalian
$pengembalian_sql = "SELECT COUNT(*) AS total_pengembalian FROM peminjaman WHERE status='Tersedia'";
$pengembalian_result = $conn->query($pengembalian_sql);
$pengembalian_data = $pengembalian_result->fetch_assoc();
$total_pengembalian = $pengembalian_data['total_pengembalian'];

// Get the count of anggota
$anggota_sql = "SELECT COUNT(*) AS total_anggota FROM login";
$anggota_result = $conn->query($anggota_sql);
$anggota_data = $anggota_result->fetch_assoc();
$total_anggota = $anggota_data['total_anggota'];

// Get the count of buku
$buku_sql = "SELECT COUNT(*) AS total_buku FROM buku";
$buku_result = $conn->query($buku_sql);
$buku_data = $buku_result->fetch_assoc();
$total_buku = $buku_data['total_buku'];

echo json_encode([
    'peminjaman' => $total_peminjaman,
    'pengembalian' => $total_pengembalian,
    'anggota' => $total_anggota,
    'buku' => $total_buku
]);
?>

<?php
session_start();

// Sertakan file koneksi.php
include 'koneksi.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['nama'])) {
    // Jika belum, arahkan ke halaman login
    header("Location: login.php");
    exit();
}

// Ambil id_rak dari parameter URL
if (isset($_GET['id'])) {
    $id_rak = $_GET['id'];
    
    // Query untuk menghapus rak berdasarkan id_rak
    $query = "DELETE FROM rak WHERE id_rak = '$id_rak'";
    
    if (mysqli_query($conn, $query)) {
        // Jika penghapusan berhasil, arahkan kembali ke halaman rak
        header("Location: rak.php");
        exit();
    } else {
        // Jika penghapusan gagal, tampilkan pesan kesalahan
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
        exit();
    }
} else {
    // Jika id_rak tidak ada, arahkan ke halaman lain atau tampilkan pesan kesalahan
    echo "ID rak tidak ditemukan.";
    exit();
}
?>

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

// Ambil data dari formulir
$id_rak = $_POST['id_rak'];
$nama_rak = $_POST['nama_rak'];

// Query untuk memperbarui data rak
$query = "UPDATE rak SET nama_rak = '$nama_rak' WHERE id_rak = '$id_rak'";

// Jalankan query
if (mysqli_query($conn, $query)) {
    // Jika berhasil, arahkan ke halaman daftar rak
    header("Location: rak.php");
    exit();
} else {
    // Jika gagal, tampilkan pesan kesalahan
    echo "Error: " . $query . "<br>" . mysqli_error($conn);
}
?>

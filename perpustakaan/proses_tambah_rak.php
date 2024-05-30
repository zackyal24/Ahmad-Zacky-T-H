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

include 'koneksi.php';


include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_rak = $_POST['nama_rak'];

    $query = "INSERT INTO rak (nama_rak) VALUES ('$nama_rak')";
    if (mysqli_query($conn, $query)) {
        header("Location: rak.php?success=1");
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}


?>

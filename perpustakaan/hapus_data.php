<?php
// Koneksi ke database
$koneksi = mysqli_connect("localhost", "root", "", "perpustakaan");

// Periksa koneksi
if (mysqli_connect_errno()) {
    echo "Koneksi database gagal: " . mysqli_connect_error();
}

// Periksa apakah id data yang akan dihapus telah dikirim
if (isset($_POST['id'])) {
    // Tangkap id data yang akan dihapus
    $id = $_POST['id'];

    // Query untuk menghapus data berdasarkan id
    $query = "DELETE FROM login WHERE n_id = '$id'";
    
    // Jalankan query
    if (mysqli_query($koneksi, $query)) {
        echo "Data berhasil dihapus.";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
    }
}

// Tutup koneksi database
mysqli_close($koneksi);
?>

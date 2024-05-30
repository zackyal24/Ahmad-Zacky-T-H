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

// Tangkap data yang dikirimkan dari form
$judul = $_POST['judul'];
$nama_kategori = $_POST['nama_kategori'];
$id_rak = $_POST['id_rak'];
$isbn = $_POST['isbn'];
$penerbit = $_POST['penerbit'];
$tgl_masuk = $_POST['tgl_masuk'];
$pengarang = $_POST['pengarang'];
$jml = $_POST['jml'];

// Proses upload gambar sampul buku
$sampul_file = $_FILES['sampul']['name'];
$sampul_tmp = $_FILES['sampul']['tmp_name'];
$sampul_size = $_FILES['sampul']['size'];
$sampul_ext = pathinfo($sampul_file, PATHINFO_EXTENSION);

// Lokasi penyimpanan sampul buku (disesuaikan dengan struktur folder Anda)
$target_dir = "uploads/sampul/";
$sampul_target = $target_dir . uniqid() . '.' . $sampul_ext;

// Cek apakah file yang diunggah adalah gambar JPG, JPEG, atau PNG
$allowed_types = array('jpg', 'jpeg', 'png','JPG', 'JPEG', 'PNG');
if (!in_array($sampul_ext, $allowed_types)) {
    echo "Hanya file JPG, JPEG, atau PNG yang diizinkan.";
    exit();
}

// Proses penyimpanan data buku ke database
$query = "INSERT INTO buku (judul, id_kategori, id_rak, isbn, penerbit, tgl_masuk, pengarang, stok, sampul) 
        VALUES ('$judul', '$nama_kategori', '$id_rak', '$isbn', '$penerbit', '$tgl_masuk', '$pengarang', '$jml', '$sampul_target')";

$result = mysqli_query($conn, $query);

if ($result) {
    // Jika query berhasil dijalankan, pindahkan file sampul ke folder uploads/sampul
    if (move_uploaded_file($sampul_tmp, $sampul_target)) {
        echo "Buku berhasil ditambahkan.";
        header("Location: daftar_buku.php");
        exit();
    } else {
        echo "Gagal mengunggah file sampul.";
    }
} else {
    // Jika query gagal dijalankan, tampilkan pesan error
    echo "Error: " . $query . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>

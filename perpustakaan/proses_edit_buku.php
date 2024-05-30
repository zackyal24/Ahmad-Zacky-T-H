<?php
// Sertakan file koneksi.php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data yang dikirimkan dari form
    $isbn = $_POST['isbn'];
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $tgl_masuk = $_POST['tgl_masuk'];
    $stok = $_POST['stok'];

    // Ambil informasi file sampul jika ada perubahan
    if ($_FILES['sampul']['name']) {
        $file_name = $_FILES['sampul']['name'];
        $file_tmp = $_FILES['sampul']['tmp_name'];

        // Pindahkan file ke direktori yang diinginkan (misalnya folder uploads/sampul)
        $target_dir = "uploads/sampul/";
        $target_file = $target_dir . basename($file_name);

        if (move_uploaded_file($file_tmp, $target_file)) {
            echo "File " . htmlspecialchars(basename($file_name)) . " berhasil diunggah.<br>";
        } else {
            echo "Gagal mengunggah file.<br>";
        }
    } else {
        // Jika tidak ada perubahan, gunakan nama file yang lama
        $query = "SELECT sampul FROM buku WHERE isbn=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $isbn);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($old_sampul);
        $stmt->fetch();
        $target_file = $old_sampul;
    }

    // Update data buku termasuk sampul baru
    $query = "UPDATE buku SET judul=?, pengarang=?, tgl_masuk=?, stok=?, sampul=? WHERE isbn=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssisi", $judul, $pengarang, $tgl_masuk, $stok, $target_file, $isbn);

    // Eksekusi statement
    if ($stmt->execute()) {
        // Jika berhasil diupdate, redirect ke halaman daftar buku
        header("Location: daftar_buku.php");
        exit();
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error updating record: " . $conn->error;
    }

    // Tutup statement
    $stmt->close();
}

// Tutup koneksi
$conn->close();
?>
